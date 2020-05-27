<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require("_access.php");
require_once("dbconnection.php");
access([1]);
$ids= $_REQUEST['ids'];
$success = 0;
$msg="";

if(count($ids)){
      try{
         $query = "update orders set confirm=1 where id=?";
         foreach($ids as $v){
           $data = setData($con,$query,[$v]);
           $success="1";
         }
      } catch(PDOException $ex) {
         $data=["error"=>$ex];
         $success="0";
      }
}else{
  $msg = "فشل الحذف";
  $success = 0;
}
echo json_encode(['success'=>$success, 'msg'=>$msg]);
?><?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require("_access.php");
access([1]);
$id= $_REQUEST['id'];
$success = 0;
$msg="";
require("dbconnection.php");
use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;

$v->validate([
    'order_id'    => [$id,'required|int']
    ]);

if($v->passes()){
         $sql = "update orders set confirm=1 where id = ?";
         $result = setData($con,$sql,[$id]);
         if($result > 0){
            $success = 1;
         }else{
            $msg = "فشل الاعادة";
         }
}else{
  $msg = "فشل الاعاده";
  $success = 0;
}
echo json_encode(['success'=>$success, 'msg'=>$msg]);
?>