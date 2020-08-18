<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require("_access.php");
access([1,2,5]);
$client = $_REQUEST['client'];
$store = $_REQUEST['store'];
$start = $_REQUEST['start'];
$end = $_REQUEST['end'];
$branch= $_REQUEST['branch'];
$branch_price = !$_REQUEST['branch_price'] ? 0 :$_REQUEST['branch_price'] ;
if(empty($end)) {
   $end = date('Y-m-d', strtotime(' + 1 day'));
}else{
   $end = date('Y-m-d', strtotime($end.' + 1 day'));
}

require("dbconnection.php");
try{
  $query = "select invoice.*,date_format(invoice.date,'%Y-%m-%d') as in_date,clients.name as client_name,clients.phone as client_phone
           ,stores.name as store_name
           from invoice
           inner join stores on stores.id = invoice.store_id
           inner join clients on stores.client_id = clients.id
           ";

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    if(validateDate($start) && validateDate($end)){
      $filter = "where invoice.date between '".$start."' AND '".$end."'";
    }

    if($client >= 1){
       $filter .= " and stores.client_id =".$client;
    }
    if($branch >= 1){
       $filter .= " and clients.branch_id =".$branch;
    }
    if($store >= 1){
       $filter .= " and invoice.store_id =".$store;
    }

    $query .=  $filter;
    $query .=  " order by invoice.date DESC limit 100";
///--------------prices ------------
    $sql = 'select
            sum(
                 if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,
                     if(to_city = 1,
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_b'].' - discount),(client_dev_price.price - discount))),
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_o'].' - discount),(client_dev_price.price - discount)))
                      ),0
                  )
             ) as earnings,
            sum(
                 if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,
                     if(to_city = 1,
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_b'].' - discount),(client_dev_price.price - discount))),
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_o'].' - discount),(client_dev_price.price - discount)))
                      ) - '.$branch_price.',0
                  )
             ) as real_earnings,
            sum(
                 if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,
                     '.$branch_price.',0
                  )
             ) as branch_earnings,

             sum(
                if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,
                 new_price -
                 (
                     if(to_city = 1,
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_b'].' - discount),(client_dev_price.price - discount))),
                           if(order_status_id=9,0,if(client_dev_price.price is null,('.$config['dev_o'].' - discount),(client_dev_price.price - discount)))
                      )
                ),0)
             ) as client_price,
            sum(if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,new_price,0)) as income,
            sum(if(order_status_id = 4 or order_status_id = 5 or order_status_id = 6,1,0)) as orders_with_dev,
            count(orders.id) as orders
            from orders
            inner join clients on clients.id = orders.client_id
            inner join invoice on invoice.id = orders.invoice_id
            left JOIN client_dev_price
            on client_dev_price.client_id = orders.client_id AND client_dev_price.city_id = orders.to_city
            where orders.confirm = 1 and invoice.date between "'.$start.'" and "'.$end.' and orders.invoice_id<>0"
           ';
    if($branch >= 1){
       $sql .= " and orders.from_branch =".$branch;
    }
$total[0] =[
 'discount'=>0,
 'orders_with_dev'=>0,
 'orders'=>0,
 'earnings'=>0,
 'real_earnings'=>0,
 'branch_earnings'=>0,
 'income'=>0,
];
if($_SESSION['role'] == 1){
$total=getData($con,$sql);
}
    $data = getData($con,$query);
    $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
print_r(json_encode(array("success"=>$success,"data"=>$data,"total"=>$total)));
?>