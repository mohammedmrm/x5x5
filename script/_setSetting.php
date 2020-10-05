<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1]);
require_once("dbconnection.php");
require_once("_crpt.php");
require_once("../config.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;



$v->addRuleMessage('isPhoneNumber', ' رقم هاتف غير صحيح ');

$v->addRule('isPhoneNumber', function($value, $input, $args) {
    return   (bool) preg_match("/^[0-9]{10,15}$/",$value);
});

 $v->addRuleMessage('isPrice', 'المبلغ غير صحيح');

$v->addRule('isPrice', function($value, $input, $args) {
  if(preg_match("/^(0|\d*)(\.\d{2})?$/",$value)){
    $x=(bool) 1;
  }
  return   $x;
});


$v->addRuleMessages([
    'required' => ' الحقل مطلوب',
    'int'      => ' فقط الارقام مسموح بها',
    'regex'    => ' فقط الارقام مسموح بها',
    'min'      => ' قصير جداً',
    'max'      => ' ( {value} ) قيمة كبيرة جداً غير مسموح بها ',
    'email'    => ' البريد الالكتروني غيز صحيح',
]);

$v->validate([
    'name'        => [$_REQUEST['name'],    'min(3)|max(100)'],
    'phone'       => [$_REQUEST['phone'],   'isPhoneNumber'],
    'address'     => [$_REQUEST['address'], 'min(3)|max(100)'],
    'email'     => [$_REQUEST['email'], 'min(3)|max(100)'],
    'dev_b'       => [$_REQUEST['dev_b'],   'isPrice'],
    'dev_o'       => [$_REQUEST['dev_o'],   'isPrice'],
    'driver_price'=> [$_REQUEST['driver_price'],'isPrice'],
    'weightPrice'=> [$_REQUEST['weightPrice'],'isPrice'],
]);
$i=0;
if(empty(implode($v->errors()->get('dev_b')))) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['dev_b'],'dev_b']);
    $i++;
}

if(empty(implode($v->errors()->get('dev_o')))) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['dev_o'],'dev_o']);
    $i++;
}
if(empty(implode($v->errors()->get('driver_price')))) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['driver_price'],'driver_price']);
    $i++;
}
if(empty(implode($v->errors()->get('weightPrice')))) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['weightPrice'],'weightPrice']);
    $i++;
}
if(empty(implode($v->errors()->get('phone')))) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['phone'],'Company_phone']);
    $i++;
}
if(empty(implode($v->errors()->get('name')))) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['name'],'Company_name']);
    $i++;
}

if(empty(implode($v->errors()->get('address')))) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['address'],'Company_address']);
    $i++;
}

if(empty(implode($v->errors()->get('email')))) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['email'],'Company_email']);
    $i++;
}
if(!empty($_REQUEST['reg'])) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['reg'],'Company_reg']);
    $i++;
}

/////------client app
if(!empty($_REQUEST['c_ad1'])) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['c_ad1'],'c_ad1']);
    $i++;
}
if(!empty($_REQUEST['c_ad2'])) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['c_ad2'],'c_ad2']);
    $i++;
}

/////------driver app
if(!empty($_REQUEST['d_ad1'])) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['d_ad1'],'d_ad1']);
    $i++;
}
if(!empty($_FILES['logo'])) {
   if(move_uploaded_file($_FILES['logo']['tmp_name'],'../img/logos/logo.png')){
    $i++;
   }
   if(move_uploaded_file($_FILES['logo']['tmp_name'],'../../driver/img/logos/logo.png')){
    $i++;
   }
   if(move_uploaded_file($_FILES['logo']['tmp_name'],'..../client/img/logos/logo.png')){
    $i++;
   }
}
if(!empty($_REQUEST['d_ad2'])) {
    $sql = "update setting set value = ? where control=?";
    setData($con,$sql,[$_REQUEST['d_ad2'],'d_ad2']);
    $i++;
}

echo json_encode([!empty($_FILES['logo']),'update'=>$i,'success'=>1, 'error'=>$error]);
?>