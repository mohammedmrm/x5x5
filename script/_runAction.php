<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,5,8,7]);
require_once("dbconnection.php");
$action = $_REQUEST['action'];
$driver = $_REQUEST['driver_action'];
$status = $_REQUEST['status_action'];
$discount = $_REQUEST['discount'];
$ids = $_REQUEST['ids'];
$ac = $_SESSION['role'];
$success="0";
$response=[];
if(isset($_REQUEST['ids'])){
  if($action == 'asign' && ( $ac == 1 || $ac == 2 || $ac == 3 || $ac == 5)){
    if($driver >= 1){
      try{
         $query = "update orders set driver_id=? where id=? and driver_invoice_id=0 and (storage_id=0 or order_status_id=4)";
         $record = "call update_or_insert(?,?,?)";
         $order = "update orders set order_status_id = ? where id =? and (order_status_id = 1 or  order_status_id = 2 or order_status_id = 3 or order_status_id = 8 or order_status_id = 13)";
         $query2 = "insert into tracking (order_id,order_status_id,date,staff_id) values(?,?,?,?)";
         foreach($ids as $v){
           $data = setData($con,$query,[$driver,$v]);
           if($data > 0){
               setData($con,$record,[$driver,$v,3]);
               $up = setData($con,$order,[3,$v]);
               if($up) {
                setData($con,$query2,[$v,3,date('Y-m-d H:i:s'),$_SESSION['userid']]);
               }
               $success="1";
           }

         }
      } catch(PDOException $ex) {
         $data=["error"=>$ex];
         $success="0";
      }
    }
  }else{
    $msg = "?????? ????????";
  }
  //---delete
  if($action == 'delete' && ( $ac == 1 || $ac == 2 || $ac == 3 || $ac == 5)){

      try{
         $query = "update orders set confirm=3 where id=?";
         foreach($ids as $v){
           $data = setData($con,$query,[$v]);
           $success="1";
         }
      } catch(PDOException $ex) {
         $data=["error"=>$ex];
         $success="0";
      }

  }else{
    $msg = "?????? ????????";
  }
  //---update
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
  if($action == 'status' && ( $ac == 1 || $ac == 2 || $ac == 3 || $ac == 5 || $ac == 6 || $ac == 7 || $ac == 8)){
    if($status >= 1){
      try{
         $query = "update orders set order_status_id=? where id=?";
         $query2 = "insert into tracking (order_id,order_status_id,date,staff_id) values(?,?,?,?)";
         $updateRecord = "update driver_records INNER join orders on orders.id = driver_records.order_id set driver_records.order_status_id = ? where driver_records.driver_id = orders.driver_id and driver_records.order_id = ?";
         $price = "update orders set new_price=? where id=?";
         foreach($ids as $v){
           $data = setData($con,$query,[$status,$v]);
           setData($con,$query2,[$v,$status,date('Y-m-d H:i:s'),$_SESSION['userid']]);
           setData($con,$updateRecord,[$status,$v]);
           $success="1";
           if($status == 9){
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
                   'status'=>$status,
                   'note'=>'',
                   'id'=>$v,
                  ]);
           }
         }
      } catch(PDOException $ex) {
          $data=["error"=>$ex];
          $success="0";
      }
    }
  }
  //---update money status
  if($action == 'money_out'){
      try{
         $query = "update orders set money_status=1 where id=?";
         foreach($ids as $v){
           $data = setData($con,$query,[$v]);
           $success="1";
         }
      } catch(PDOException $ex) {
          $data=["error"=>$ex];
          $success="0";
      }
  }
  //---update money status
  if($action == 'money_in'){
      try{
         $query = "update orders set money_status=0 where id=?";
         foreach($ids as $v){
           $data = setData($con,$query,[$v]);
           $success="1";
         }
      } catch(PDOException $ex) {
          $data=["error"=>$ex];
          $success="0";
      }
  }
  //---update money status
  if($action == 'discount'){
      try{
         $query = "update orders set discount = ? where id = ?";
         foreach($ids as $v){
           $data = setData($con,$query,[$discount,$v]);
           $success="1";
         }
      } catch(PDOException $ex) {
          $data=["error"=>$ex];
          $success="0";
      }
  }
  //---update money status
  if($action == 'returnedToStore9'){
      try{
         $query = "update orders set order_status_id = ?, storage_id = ? where id = ?";
         foreach($ids as $v){
           $data = setData($con,$query,[9,$_SESSION['user_details']['storage_id'],$v]);
           $query2 = "insert into tracking (order_id,order_status_id,date,staff_id) values(?,?,?,?)";
           setData($con,$query2,[$v,9,date('Y-m-d H:i:s'),$_SESSION['userid']]);
           $sql ="insert into storage_tracking (order_id,staff_id,status) values(?,?,?)";
           setData($con,$sql,[$v,$_SESSION['userid'],2]);
           $success="1";
           ///---sync
           $sql = "select isfrom ,clients.sync_token as token,clients.sync_dns as dns from orders
                   inner join clients on clients.id = orders.client_id
                   where orders.id=?";
           $order = getData($con,$sql,[$v]);
           if($order[0]['isfrom'] == 2){
             $response = httpPost($order[0]['dns'].'/api/orderStatusSync.php',
                  [
                   'token'=>$order[0]['token'],
                   'status'=>9,
                   'note'=>'',
                   'id'=>$v,
                  ]);
           }
         }
      } catch(PDOException $ex) {
          $data=["error"=>$ex];
          $success="0";
      }
  }
  //---update money status
  if($action == 'returnedToStore6'){
      try{
         $query = "update orders set order_status_id = ?, storage_id = ? where id = ?";
         foreach($ids as $v){
           $data = setData($con,$query,[6,$_SESSION['user_details']['storage_id'],$v]);
           $query2 = "insert into tracking (order_id,order_status_id,date,staff_id) values(?,?,?,?)";
           setData($con,$query2,[$v,6,date('Y-m-d H:i:s'),$_SESSION['userid']]);
           $sql ="insert into storage_tracking (order_id,staff_id,status) values(?,?,?)";
           setData($con,$sql,[$v,$_SESSION['userid'],2]);
           $success="1";
           ///---sync
           $sql = "select isfrom ,clients.sync_token as token,clients.sync_dns as dns from orders
                   inner join clients on clients.id = orders.client_id
                   where orders.id=?";
           $order = getData($con,$sql,[$v]);
           if($order[0]['isfrom'] == 2){
             $response = httpPost($order[0]['dns'].'/api/orderStatusSync.php',
                  [
                   'token'=>$order[0]['token'],
                   'status'=>6,
                   'note'=>'',
                   'id'=>$v,
                  ]);
           }
         }
      } catch(PDOException $ex) {
          $data=["error"=>$ex];
          $success="0";
      }
  }
}else{
  $success="2";
}

echo (json_encode(array("success"=>$success,"data"=>$data,"response"=>json_decode(substr($response, 3)))));
?>