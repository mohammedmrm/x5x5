<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,5,7,8]);
require_once("dbconnection.php");

$ids = $_REQUEST['ids'];
$statues = $_REQUEST['status'];
$success="0";
function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
if(isset($_REQUEST['ids'])){
      try{
         $query = "update orders set order_status_id=? where id=? and invoice_id=0 and driver_invoice_id=0 and storage_id=0";
         $query2 = "insert into tracking (order_id,order_status_id,date,staff_id) values(?,?,?,?)";
         $updateRecord = "update driver_records INNER join orders on orders.id = driver_records.order_id set driver_records.order_status_id = ? where driver_records.driver_id = orders.driver_id and driver_records.order_id = ?";
         $price = "update orders set new_price=? where id=?";
         $i = 0;
         foreach($ids as $v){
           if($statues[$i] >= 1){
             $data = setData($con,$query,[$statues[$i],$v]);
             if($data > 0){
               setData($con,$query2,[$v,$statues[$i],date('Y-m-d H:i:s'),$_SESSION['userid']]);
               setData($con,$updateRecord,[$statues[$i],$v]);
               if($statues[$i] == 9){
                 setData($con,$price,[0,$v]);
               }
               ///---sync
               $sql = "select isfrom ,clients.sync_token as token,clients.sync_dns as dns from orders
                       inner join clients on clients.id = orders.client_id
                       where orders.id=?";
               $order = getData($con,$sql,[$v]);
               if($order[0]['isfrom'] == 2){
                 $response = httpPost($order[0]['dns'].'/api/orderStatusSync.php',
                      [
                       'token'=>$order[0]['token'],
                       'status'=>$statues[$i],
                       'note'=>'',
                       'id'=>$v,
                      ]);
               }
             }
             $success="1";
           }
           $i++;
         }
      } catch(PDOException $ex) {
          $data=["error"=>$ex];
          $success="0";
      }
 }else{
  $success="2";
}

echo json_encode([$_REQUEST,"success"=>$success,"data"=>$data,"response"=>json_decode(substr($response, 3))]);
?>