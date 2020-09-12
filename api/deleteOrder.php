<?php
ob_start();
session_start();
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("_apiAccess.php");
access();
require_once("../script/dbconnection.php");
require_once("../config.php");
use Violin\Violin;
require_once('../validator/autoload.php');
$error = [];
$data = [];
$count = [];
$success = 0;
$barcode = $_REQUEST['barcode'];
$v = new Violin;
$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'    => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'تم ادخال بيانات اكثر من الحد المسموح',
    'email'    => 'البريد الالكتروني غيز صحيح',
]);
      $v->validate([
          'barcode'      => [$barcode, 'required|int'],
      ]);
if($v->passes()) {
  $sql = "delete from orders where id = ? and client_id=? and confirm=5";
  $result = setData($con,$sql,[$barcode,$clinetdata['id']]);
  if($result){
    $success = 1;
  }
}else{
$error = ['barcode'=>implode($v->errors()->get('barcode'))];
}
ob_end_clean();
echo json_encode(['success'=>$success,'error'=>$error]);
?>