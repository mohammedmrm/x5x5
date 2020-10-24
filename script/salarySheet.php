<?php
ob_start();
session_start();
error_reporting(0);
require_once("_access.php");
access([1,2,5]);
require_once("dbconnection.php");
$style='
<style>

  td,th{
    padding:1px;
    padding-top:20px;
    text-align:center;
    height:40px;
    vertical-align: middle;
  }
  .head-tr {
   background-color: #ddd;
   color:#111;
   height:30px;
  }
  body {
   background-image: url(../img/logos/bg-logo.png);
   background-position: center;
   background-repeat: no-repeat;
   background-size: 70%;
  }
  .table {
   width:850px;
  }
</style>';


$branch = $_REQUEST['id'];
$month = $_REQUEST['month'];
$total = [];
$year  = $_REQUEST['year'];
$now = date("Y-m-d H:i:s");
$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$start_date = $year."-".$month."-01";
$end_date = $year."-".$month."-".$days;

try{
  $query = 'select  (ROUND((staff.salary/'.$days.') * if(datediff("'.$end_date.'", staff.date) > '.$days.','.$days.',datediff("'.$end_date.'", staff.date))
                 - (if (a.leave_days > 0 ,a.leave_days,0) * (staff.salary/'.$days.'))
                 ,2) - if(b.penalty is NULL,0,b.penalty) + if(b.award is NULL,0,b.award) ) as salaries, branches.id as b_id ,
  branches.name as branch_name,staff.name as s_name,staff.salary as s_s,
  a.leave_days as leave_days,staff.phone as phone
  ,if(b.penalty is NULL,0,b.penalty) as penalty,if(b.award is NULL,0,b.award) as award
  from staff
  inner join branches on branches.id = staff.branch_id
  left join
  ( select
                    if(
                     sum(
                          if(staff_leave.start_date <  "'.$end_date.'",
                            if(staff_leave.end_date > "'.$end_date.'",'.$days.',datediff(staff_leave.end_date,staff_leave.start_date)),
                            if(staff_leave.end_date < "'.$end_date.'",datediff(staff_leave.end_date,"'.$start_date.'"),datediff("'.$end_date.'",staff_leave.start_date))
                            )
                        ) is NULL,0,
                     sum(
                          if(staff_leave.start_date <  "'.$end_date.'",
                            if(staff_leave.end_date > "'.$end_date.'",'.$days.',datediff(staff_leave.end_date,staff_leave.start_date)+1),
                            if(staff_leave.end_date < "'.$end_date.'",datediff(staff_leave.end_date,"'.$start_date.'")+1,datediff("'.$end_date.'",staff_leave.start_date)+1)
                            )
                        )
                        ) as leave_days,max(staff_id) as s_id
   FROM    staff_leave
   where staff_leave.start_date >= "'.$start_date.'" and staff_leave.end_date <= "'.$end_date.'"  and with_salary <> 1
   group by staff_id
  ) a on a.s_id = staff.id

  left join (
   select  sum(if(type=1,amount,0)) as award,sum(if(type<>1,amount,0)) as penalty, max(staff_id) as s_id
   FROM    award_penalty
   where award_penalty.year = "'.$year.'" and award_penalty.month = "'.$month.'"
   group by award_penalty.staff_id
  ) b on b.s_id = staff.id

  where staff.role_id <> 4 and staff.developer = 0 and staff.branch_id = "'.$branch.'"
  ';
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
try{
  $i = 0;
  $content = "";
foreach($data as $k=>$v){
        $total['total'] += $data[$i]['salaries'];
$hcontent .=
 '<tr>
   <td width="30" align="center">'.($i+1).'</td>
   <td width="170" align="center">'.$data[$i]['s_name'].'</td>
   <td width="170" align="center">'.$data[$i]['phone'].'</td>
   <td align="center">'.number_format($data[$i]['s_s']).'</td>
   <td align="center">'.$data[$i]['leave_days'].'</td>
   <td align="center">'.number_format($data[$i]['award']).'</td>
   <td align="center">'.number_format($data[$i]['penalty']).'</td>
   <td align="center">'.number_format($data[$i]['salaries']).'</td>
   <td align="center">'.'</td>
 </tr>';
  $total['branch'] = $data[$i]['branch_name'];
  $total['staffNumber'] = $i+1;
  $i++;

}

} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}

require_once("../tcpdf/tcpdf.php");


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('07822816693-الموطور');
$pdf->SetTitle('الرواتب');
$pdf->SetSubject($year."-".$month);
// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'ar';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);
// set font
$pdf->SetFont('aealarabiya', '', 13);

// set default header data
$pdf->SetHeaderData("../img/logos/logopdf.png",0, ' اسم الفرع: '.$total['branch'],"عدد الموظفين: ".$total['staffNumber']."\n"."مجموع الرواتب: ".$total['total']."\n"."الشهر: ".$year."-".$month);

// set header and footer fonts
$pdf->setHeaderFont(Array('aealarabiya', '', 12));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
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

$htmlpersian = '<body>
                <table border="1" class="table">
			       <thead>
	  						<tr  class="head-tr">
                                        <th width="30">#</th>
                                        <th width="170" >الاسم</th>
										<th width="170">رقم الهاتف</th>
										<th >الراتب الاساسي</th>
										<th>عدد ايام الاجازة</th>
										<th>المكافئات</th>
										<th>العقوبات</th>
										<th>الراتب الحالي</th>
                                        <th>التوقيع</th>
							</tr>
      	            </thead>
                            <tbody id="salarTabel">'
                            .$hcontent.
                            '</tbody>
		      </table>
              </body>
        ';
$pdf->WriteHTML($style.$htmlpersian, true, 0, true, 0);

// set LTR direction for english translation
$pdf->setRTL(false);

$pdf->SetFontSize(10);
// print newline
$pdf->Ln();
//Close and output PDF document
ob_end_clean();
//echo '<pre>'.$query;
//print_r($query);
$pdf->Output('Salayies - '.date('Y-m-d').'.pdf', 'I');
?>