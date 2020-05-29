<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require("_access.php");
access([1,2,3,4,5,6]);
$client = $_REQUEST['client'];

require("dbconnection.php");
try{
  if(empty($client)){
   $query = "select stores.*, clients.name as client_name , clients.phone as client_phone,
             date_format(a.old_date,'%Y-%m-%d') as old_date,a.orders as orders
             from stores
             left join clients on clients.id = stores.client_id
             left join (
                 select SUM(IF (invoice_id = 0,1,0)) as orders,
                        min(date) as old_date,
                        max(store_id) as store_id
                 from orders where orders.confirm=1
                 group by orders.store_id
             ) a on a.store_id = stores.id
             ";
    $data = getData($con,$query);

  }else {
   $query = "select stores.*, clients.name as client_name , clients.phone as client_phone,
   a.old_date as old_date,a.orders as orders
   from stores left join clients on clients.id = stores.client_id
             left join (
                 select SUM(IF (invoice_id = 0,1,0)) as orders,
                        min(date) as old_date,
                        max(store_id) as store_id
                 from orders orders.confirm=1
                 group by orders.store_id
             ) a on a.store_id = stores.id
   ";
   $query .= " where client_id=?";
   $data = getData($con,$query,[$client]);
  }
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
print_r(json_encode(array("success"=>$success,"data"=>$data)));
?>