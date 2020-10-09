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
$client    = $_REQUEST['loan_client'];
$price    = $_REQUEST['loan_price'];
$note    = $_REQUEST['loan_note'];

$v->addRuleMessage('isPrice', 'المبلغ غير صحيح');

$v->addRule('isPrice', function($value, $input, $args) {
  if(preg_match("/^(0|\d*)(\.\d{2})?$/",$value)){
    $x=(bool) 1;
  }
  return   $x;
});

$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'مسموح ب {value} رمز كحد اعلى '
]);

$v->validate([
    'client'  => [$client, 'required|max(3)|int'],
    'price'   => [$price, 'required|isPrice'],
    'note'    => [$note, 'min(1)|max(1)']
]);

if($v->passes()) {
  $sql = 'insert into loans (client_id,price,note) values
                             (?,?,?)';
  $result = setData($con,$sql,[$client,$price,$note]);
  if($result > 0){
    $success = 1;
  }
}else{
  $error = [
           'client_err'=> implode($v->errors()->get('client')),
           'price_err'=>implode($v->errors()->get('price')),
           'note_err'=>implode($v->errors()->get('note')),
           ];
}
echo json_encode(['success'=>$success, 'error'=>$error,$_POST]);
?>