<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2]);
require_once("dbconnection.php");
require_once("_crpt.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$success = 0;
$error = [];
$client = $_REQUEST['e_loan_client'];
$price  = $_REQUEST['e_loan_price'];
$note   = $_REQUEST['e_loan_note'];
$id   = $_REQUEST['e_loan_id'];
$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'مسموح ب {value} رمز كحد اعلى ',
    'email'      => 'البريد الالكتروني غيز صحيح',
]);
$v->addRuleMessage('isPrice', 'المبلغ غير صحيح');

$v->addRule('isPrice', function($value, $input, $args) {
  if(preg_match("/^(0|\d*)(\.\d{2})?$/",$value)){
    $x=(bool) 1;
  }
  return   $x;
});

$v->validate([
    'id'     => [$id,'required|int'],
    'client' => [$client,'required|int'],
    'price'  => [$price, 'required|isPrice'],
    'note'   => [$note,  'max(250)'],
]);

if($v->passes()){
  $sql = 'update loans set client_id = ?, price=? , note = ? where id=?';
  $result = setData($con,$sql,[$client,$price,$note,$id]);
  if($result > 0){
    $success = 1;
  }
}else{
  $error = [
           'id'=>  implode($v->errors()->get('id')),
           'client'=>implode($v->errors()->get('client')),
           'price'=>implode($v->errors()->get('price')),
           'note'=>implode($v->errors()->get('note')),
           ];
}
echo json_encode(['success'=>$success, 'error'=>$error,$_REQUEST]);
?>