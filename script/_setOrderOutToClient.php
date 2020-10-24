<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,5,2,8]);
$id= $_REQUEST['id'];
$success = 0;
$msg="";
require_once("dbconnection.php");
use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;

$v->validate([
    'order_id'    => [$id,'required|int']
    ]);
if($_SESSION['user_details']['storage_id'] == 1){
  if($v->passes()){
           $sql = "update orders set storage_id=-1 where storage_id = 1 and id = ? and (order_status_id=? or order_status_id=? or order_status_id=?)";
           $result = setData($con,$sql,[$id,9,6,5]);
           if($result > 0){
              $success = 1;
              $sql ="insert into storage_tracking (order_id,staff_id,status) values(?,?,?)";
              setData($con,$sql,[$id,$_SESSION['userid'],2]);
           }else{
              $msg = "فشل الاخراج! قد لاتملك الصلاحية";
           }
  }else{
    $msg = "فشل الاخراج";
    $success = 0;
  }
}else{
  $msg = "لاتملك الصلاحية";
  $success = 0;
}
echo json_encode([$sql,$id,$_SESSION['user_details']['storage_id'],'success'=>$success, 'msg'=>$msg]);
?>