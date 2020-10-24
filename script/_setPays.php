<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,5]);
require_once("dbconnection.php");
require_once("_crpt.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$success = 0;
$error = [];

$reason= trim($_REQUEST['reason']);
$money = trim($_REQUEST['price']);
$note= trim($_REQUEST['note']);


$v->addRuleMessage('isPrice', 'المبلغ غير صحيح');

$v->addRule('isPrice', function($value, $input, $args) {
  if(preg_match("/^(0|[1-9]\d*)(\.\d{2})?$/",$value) || empty($value)){
    $x=(bool) 1;
  }
  return   $x;
});
$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'مسموح ب 250 رمز كحد اعلى ',
    'email'      => 'البريد الالكتروني غيز صحيح',
]);

$v->validate([
    'reason'  => [$reason,'required|max(200)'],
    'money'   => [$money, 'required|isPrice'],
    'note'    => [$note,  'max(1000)'],
]);

$month_err = implode($v->errors()->get('year'))."-".implode($v->errors()->get('month'));
if($v->passes())  {
 $sql = "insert into pays (reason,price,note,staff_id) values (?,?,?,?)";
 $res = setData($con,$sql,[$reason,$money,$note,$_SESSION['userid']]);
 if($res > 0){
   $success = 1;
 }
}
  $error = [
           'reason'=> implode($v->errors()->get('reason')),
           'note'=> implode($v->errors()->get('note')),
           'money'=> implode($v->errors()->get('money')),
           ];


echo json_encode([$_POST,'success'=>$success, 'error'=>$error]);
?>