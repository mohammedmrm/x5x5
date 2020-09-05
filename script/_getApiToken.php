<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,5,7]);
require_once("dbconnection.php");
$id = $_REQUEST['id'];
try{
$query = "select * from clients where id=?";
$res = getData($con,$query,[$id]);
if($res[0]['api_token'] == "" || $res[0]['api_token'] == null){
  $token = uniqid().uniqid().uniqid();
  $sql = "update clients set api_token = ? where id=?";
  $res1 = setData($con,$sql,[$token,$id]);
  if(!$res1){
    $token = "";
  }
}else{
  $token = $res[0]['api_token'];
}
$success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex,'q'=>$query];
   $success="0";
}
echo (json_encode(array($id,$res1,$res,"success"=>$success,"token"=>$token)));
?>