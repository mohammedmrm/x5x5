<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require("_access.php");
access([1,2,5]);
require("dbconnection.php");
$action = $_REQUEST['action'];
$driver = $_REQUEST['driver_action'];
$status = $_REQUEST['status_action'];
$discount = $_REQUEST['discount'];
$ids = $_REQUEST['ids'];
$success="0";
if(isset($_REQUEST['ids'])){
  if($action == 'asign'){
    if($driver >= 1){
      try{
         $query = "update orders set driver_id=? where id=?";
         foreach($ids as $v){
           $data = setData($con,$query,[$driver,$v]);
           $success="1";
         }
      } catch(PDOException $ex) {
         $data=["error"=>$ex];
         $success="0";
      }
    }
  }
  //---delete
  if($action == 'delete'){

      try{
         $query = "delete from orders where id=?";
         foreach($ids as $v){
           $data = setData($con,$query,[$v]);
           $success="1";
         }
      } catch(PDOException $ex) {
         $data=["error"=>$ex];
         $success="0";
      }

  }
  //---update
  if($action == 'status'){
    if($status >= 1){
      try{
         $query = "update orders set order_status_id=? where id=?";
         $query2 = "insert into tracking (order_id,order_status_id,date) values(?,?,?)";
         foreach($ids as $v){
           $data = setData($con,$query,[$status,$v]);
           setData($con,$query2,[$v,$status,date('Y-m-d H:i:s')]);
           $success="1";
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
}else{
  $success="2";
}

print_r(json_encode(array("success"=>$success,"data"=>$data)));
?>