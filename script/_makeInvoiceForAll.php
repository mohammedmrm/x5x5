<?php
//-- create invoce fo recived orders only
ini_set('max_execution_time', 60000);
//ob_start();
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,5]);
require_once("dbconnection.php");

$style='
<style>
 td,th{
    padding:3px;
    text-align:center;
  }
  .re {
    background-color: #FFA07A;
  }
  .ch {
    background-color: #FFFACD;
  }
  .repated {
    background-color:#DA3A3A;
  }
  .head-tr {
   background-color: #70F89C;
   color:#111;
  }
  .price_bg {
    background-color: #70F89C;
  }
  .row_bg {
    background-color: #E9FAE9;
  }
  </style>
';
require("../config.php");

$status = 4;
$store = $_REQUEST['store'];
$loan_outcome = $_REQUEST['loan_outcome'];
$dev_price_b = $_REQUEST['dev_price_b'];
$dev_price_o = $_REQUEST['dev_price_o'];
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
if(!empty($end)) {
   $end .=" 23:59:59";
}else{
  $end =date('Y-m-d H:i:s');
}
if(!empty($start)) {
   $start .=" 00:00:00";
}
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

try{
if($store >= 1 && $dev_price_b > 999 && $dev_price_o > 999){
    $sql= "select * from stores where id= ?";
    $client =getData($con,$sql,[$store]);
    if(count($client) > 0){
    $sql= "delete from client_dev_price where client_id= ?";
    $delete_dev_price = setData($con,$sql,[$client[0]['client_id']]);
    $sql = "select * from cites";
    $cities = getData($con,$sql);
            foreach($cities as $city){
                 $sql = "insert into client_dev_price (price,city_id,client_id) value(?,?,?)";
                 if($city['id'] == 1){
                  $update_price = setData($con,$sql,[$dev_price_b,$city['id'],$client[0]['client_id']]);
                 }else{
                  $update_price = setData($con,$sql,[$dev_price_o,$city['id'],$client[0]['client_id']]);
                 }
            }
   }

}
}catch(PDOException $ex) {

}
$total = [];

try{
  $count = "select count(*) as count,
               SUM(IF (to_city = 1,1,0)) as  b_orders,
               SUM(IF (to_city > 1,1,0)) as  o_orders,
                sum(
                  if(to_city = 1,
                       if(client_dev_price.price is null,(".$config['dev_b']." - discount),(client_dev_price.price - discount)),
                       if(client_dev_price.price is null,(".$config['dev_o']." - discount),(client_dev_price.price - discount))
                  )
                  + if(new_price > 500000 ,( (ceil(new_price/500000)-1) * ".$config['addOnOver500']." ),0)
                  + if(weight > 1 ,( (weight-1) * ".$config['weightPrice']." ),0)
                ) as dev_price,
                sum(new_price) as income
            from orders
            left JOIN client_dev_price on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
            ";
  $query = "select orders.*, date_format(orders.date,'%Y-%m-%d') as dat,
            clients.name as client_name,clients.phone as client_phone,
            cites.name as city,towns.name as town,branches.name as branch_name,
            stores.name as store_name, b.rep as repated
            from orders
            left join clients on clients.id = orders.client_id
            left join cites on  cites.id = orders.to_city
            left join stores on  stores.id = orders.store_id
            left join towns on  towns.id = orders.to_town
            left join branches on  branches.id = orders.to_branch
            left join (
             select order_no,count(*) as rep from orders  where confirm = 1 and store_id=".$store."
              GROUP BY order_no
              HAVING COUNT(orders.id) > 1
            ) b on b.order_no = orders.order_no
            ";
  $where = "where orders.confirm=1  and invoice_id = 0 and ";
  $filter = "";

  ///-----------------status
  $filter .= " and (order_status_id =4 or order_status_id = 6 or order_status_id = 5)";

  //---------------------end of status
  if($store >= 1){
    $filter .= " and store_id=".$store;
  }
  if(validateDate($start) && validateDate($end)){
    $filter .= " and orders.date between '".$start."' AND '".$end."'";
  }
  if($filter != ""){
     $filter = preg_replace('/^ and/', '', $filter);
     $filter = $where." ".$filter;
     $count .= " ".$filter;
     $query .= " ".$filter." order by orders.order_no,to_city";
  }else{
    $query .=" order by orders.date";
  }

  $count1 = getData($con,$count);
  $orders = $count1[0]['count'];
  $total['b_orders'] = $count1[0]['b_orders'];
  $total['o_orders'] = $count1[0]['o_orders'];
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
// set default header data
$status_name = "مستلمة";
$msg = "";
      //---- check if the client has a loan--- start
      $sql= "select * from stores where id= ?";
      $client =getData($con,$sql,[$store]);
      $sql = "SELECT sum(if(type = 1,(price),0)) as total,sum(if(type = 1,price,-price)) as balance, client_id from loans where client_id=? GROUP by client_id ";
      $balance = getData($con,$sql,[$client[0]['client_id']]);
      if(count($balance) == 1){
         if($loan_outcome > $balance['0']['balance']){
           $msg = "المبلغ المستقطع من السلفه اكبر من المتوفر";
         }else{
           $msg ="";
         }
      }else{
        if(empty($loan_outcome) || $loan_outcome == 0){
           $msg = "";
        }else{
           $msg = "لايوجد سلفه لهذا العميل";
        }

      }
      //---- check if the client has a loan--- end
if($orders > 0 && $msg == ""){
    try{
        $i = 0;
        $content = "";
        $success = 0;
        $pdf_name = date('Y-m-d')."_".uniqid().".pdf";
        $sql = "insert into invoice (path,store_id,orders_status,invoice_status,staff_id,total,dev_price) values(?,?,?,?,?,?,?)";
        $res = setData($con,$sql,[$pdf_name,$store,$status,1,$_SESSION['userid'],$count1[0]['income'],$count1[0]['dev_price']]);
    if($res > 0){
      $success = 1;
      $sql = "select * from invoice where path=? and store_id =? order by date DESC limit 1";
      $res = getdata($con,$sql,[$pdf_name,$store]);
      $invoice = $res[0]['id'];

        foreach($data as $k=>$v){
          $total['income'] += $data[$i]['new_price'];
                $sql = "select * from client_dev_price where client_id=? and city_id=?";
                $dev_price  = getData($con,$sql,[$v['client_id'],$v['to_city']]);
                if(count($dev_price) > 0){
                  $dev_p = $dev_price[0]['price']  ;
                }else{
                  if($v['to_city'] == 1){
                   $dev_p = $config['dev_b'];
                  }else{
                   $dev_p = $config['dev_o'];
                  }
                }
                if($v['to_city'] == 1 && $dev_price_b > 999){
                 $dev_p = $dev_price_b;
                }
                if($v['to_city'] != 1 && $dev_price_o > 999){
                 $dev_p = $dev_price_o;
                }
                $over500 = 0;
                if($data[$i]['new_price']>500000){
                  $over500 = (ceil($data[$i]['new_price']/500000) - 1) * $config['addOnOver500'];
                }
                $weightPrice = 0;
                if($data[$i]['weight']>1){
                  $weightPrice = ($data[$i]['weight'] - 1) * $config['weightPrice'];
                }
                $data[$i]['dev_price'] = $dev_p + $over500 + $weightPrice;
                if($data[$i]['order_status_id'] == 9){
                  $data[$i]['dev_price'] = 0;
                  $dev_p = 0;
                  $data[$i]['dicount']=0;
                }
                $data[$i]['client_price'] = ($data[$i]['new_price'] -  $data[$i]['dev_price']) + $data[$i]['discount'];
                $note =  $data[$i]['note'];
                $bg = "";
                if($data[$i]['order_status_id'] == 6){
                 $bg = "re";
                 $note = "راجع جزئي";
                }
               if($data[$i]['order_status_id'] == 5){
                 $bg = "ch";
                 $note = "استبدال";
               }
               if($data[$i]['repated'] > 1){
                 $bg = "repated";
               }
               $row_bg = "";
               if(($i%2) == 1 && $data[$i]['order_status_id'] != 6 && $data[$i]['order_status_id'] != 5 && $data[$i]['repated'] <= 1){
                  $row_bg = "row_bg";
               }
               $price_bg ="";
               if($data[$i]['new_price'] !== $data[$i]['price']){
                 $price_bg = "price_bg";
               }

               $sql = "update orders set invoice_id =? , money_status = ? where id=?";
               $res = setData($con,$sql,[$invoice,1,$v['id']]);

        $hcontent .=
         '<tr class="'.$bg.' '.$row_bg.'">
           <td width="60"  align="center">'.($i+1).'</td>
           <td width="100" align="center">'.$data[$i]['dat'].'</td>
           <td width="80" align="center">'.$data[$i]['order_no'].'</td>
           <td width="120" align="center">'.phone_number_format($data[$i]['customer_phone']).'</td>
           <td width="160" align="center" >'.$data[$i]['city'].' - '.$data[$i]['town'].' - '.$data[$i]['address'].'</td>
           <td width="80" align="center">'.number_format($data[$i]['price']).'</td>
           <td width="80" class="'.$price_bg.'" align="center">'.number_format($data[$i]['new_price']).'</td>
           <td width="80" align="center">'.number_format($data[$i]['dev_price']).'</td>
           <td align="center">'.number_format($data[$i]['client_price']).'</td>
           <td align="center">'.$note.'</td>
         </tr>';
          $total['discount'] += $data[$i]['discount'];
          $total['dev_price'] += $data[$i]['dev_price'];
          $total['client_price'] += $data[$i]['client_price'];

           $i++;
       }
       $total['invoice'] = $invoice;
       $total['status'] = $status_name;
       $total['date'] = $res[0]['date'];

      //---- check if the client has a loan--- start
      if($loan_outcome > 0){
          $sql = "insert into loans (type,price,invoice_id,client_id) values(?,?,?,?)";
          $outcome = setData($con,$sql,[0,$loan_outcome,$invoice,$client[0]['client_id']]);
          $hcontent .= '<tr>
                           <td colspan="4" style="text-align:center;font-size:20px;">صافي العميل: '.number_format($total['client_price']).'</td>
                           <td colspan="3" style="text-align:center;font-size:20px;">المخصوم من السلفه : '.number_format($loan_outcome).'</td>
                           <td colspan="3" style="text-align:center;font-size:20px;">الباقي من السلفه : '.number_format(($balance['0']['balance'] - $loan_outcome)).'</td>
                        </tr>';
     }else{
            $hcontent .= '<tr>
                           <td colspan="5" style="text-align:center;font-size:20px;">صافي العميل:</td>
                           <td colspan="5" style="text-align:center;font-size:20px;">'.number_format($total['client_price']).'</td>
                        </tr>';
     }
      //---- check if the client has a loan--- end
    }
    $total['orders'] = $orders;
    if($store >=1){
     $total['client'] = $data[0]['client_name'];
     $total['store'] = $data[0]['store_name'];
     $total['client_phone'] = $data[0]['client_phone'];
    }else{
     $total['client'] = '/';
     $total['store'] = '/';
    }
    } catch(PDOException $ex) {
       $data=["error"=>$ex];
       $success="0";
       echo $ex;
    }

require_once("../tcpdf/tcpdf.php");
class MYPDF extends TCPDF {
    public function Header() {
        // Set font
        $t = $GLOBALS['total'];
        $this->SetFont('aealarabiya', 'B', 12);
        // Title
        $this->writeHTML('');
    }
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('07822816693');
$pdf->SetTitle('تقرير الطلبيات');
$pdf->SetSubject($start."-".$end);
// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'ar';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);
// set font
$pdf->SetFont('aealarabiya', '', 12);


//$pdf->SetHeaderData("../../../".$config['Company_logo'],30, ' اسم الصفحه او البيح: '.$total['store']."               "." الفترة الزمنية: ".date("Y-m-d",strtotime($start))." || ".date("Y-m-d",strtotime($end))," حالة الطلبات : ".$status_name."\n"."السعر الصافي للعميل: ".number_format($total['client_price'])."                "."\n"."عدد الطلبيات: ".$total['orders']." ");

// set header and footer fonts
$pdf->setHeaderFont(Array('aealarabiya', '', 12));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


// set margins
$pdf->SetMargins(5, 5, 10);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// ---------------------------------------------------------


// add a page
$pdf->AddPage('L', 'A4');

// Persian and English content
$header ='
             <table>
             <tr>
                    <td ></td>
                    <td width="350" rowspan="5">
                      <br />
                      <span align="right" style="color:#DC143C;">كشف حساب العميل</span><br />
                      '.
                      'عدد الطلبيات  الكلي: '.$total['orders'].'<br />'.
                      'عدد طلبيات بغداد : '.$total['b_orders'].'<br />'.
                      'عدد طلبيات المحافظات : '.$total['o_orders'].
                    '</td>
                    <td style="text-align:center;" width="300" rowspan="5">
                      <img src="../img/logos/logo.png" height="100px"/>
                    </td>
             </tr>
             <tr>
                    <td style="text-align:right;">اسم العميل و الصفحه: '.$total['store'].' - '.$total['client_phone'].'</td>
             </tr>
             <tr>
                    <td style="text-align:right;">التاريخ:'.date('Y-m-d').'</td>
             </tr>
             <tr>
                    <td style="text-align:right;">رقم الكشف:'.$total['invoice'].'</td>
             </tr>
             <tr>
                 <td style="text-align:right;font-size:20px;">المبلغ الصافي للعميل : '.number_format($total['client_price']).'</td>
             </tr>
            </table>
        ';
$htmlpersian = '<table border="1" class="table" cellpadding="3">
			       <thead>
	  						<tr  class="head-tr">
                                        <th width="60">#</th>
                                        <th width="100">تاريخ الادخال</th>
										<th width="80">رقم الوصل</th>
										<th width="120">هاتف المستلم</th>
										<th width="160">عنوان المستلم</th>
                                        <th width="80">مبلغ الوصل</th>
										<th width="80">المبلغ المستلم</th>
										<th width="80">مبلغ التوصيل</th>
										<th> المبلغ الصافي للعميل </th>
										<th>الملاحظات</th>
							</tr>
      	            </thead>
                            <tbody id="ordersTable">'
                            .$hcontent.
                            '</tbody>
		</table>
        ';
$pdf->WriteHTML($style.$header.$htmlpersian, true, false, false, false,'');

// set LTR direction for english translation
$pdf->setRTL(false);

$pdf->SetFontSize(10);
// print newline
$pdf->Ln();
//Close and output PDF document
ob_end_clean();
//print_r($hcontent);

//$pdf->Output('order'.date('Y-m-d h:i:s').'.pdf', 'I');
$pdf->Output(dirname(__FILE__).'/../invoice/'.$pdf_name, 'F');
}else{
 $success = 2;

}
echo json_encode([$data,$count1,'num'=>$count,'success'=>$success,'invoice'=>$pdf_name,'msg'=>$msg]);
?>