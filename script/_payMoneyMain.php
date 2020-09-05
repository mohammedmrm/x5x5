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

$type= trim($_REQUEST['type']);
$money = trim($_REQUEST['price']);
$note= trim($_REQUEST['note']);
$branch= trim($_REQUEST['branch']);
$staff= trim($_REQUEST['staff']);
$year= trim($_REQUEST['year']);
$month= trim($_REQUEST['month']);

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
    'type'  => [$type,  'required|int|max(1)'],
    'staff'   => [$staff, 'required|int'],
    'year'    => [$year,  'required|int|max(4)|min(4)'],
    'month'   => [$month, 'required|int|max(2)|min(1)'],
    'branch'   => [$branch, 'required|int'],
    'money'   => [$money, 'required|isPrice'],
    'note'    => [$note,  'max(250)'],
]);

$month_err = implode($v->errors()->get('year'))."-".implode($v->errors()->get('month'));
if($v->passes())  {
 $sql = "select * from salary_pays where year=?  and month=? and branch_id=?";
 $res1 = getData($con,$sql,[$year,$month,$branch]);

 if(count($res1) > 0){
  $month_err = "تم صرف الرواتب لهذا الشهر";
 }else{
   $sql = "insert into award_penalty (type,staff_id,amount,note,year,month) values (?,?,?,?,?,?)";
   $res = setData($con,$sql,[$type,$staff,$money,$note,$year,$month]);
   if($res > 0){
     $success = 1;
   }
 }
}
  $error = [
           'type'=> implode($v->errors()->get('type')),
           'staff'=> implode($v->errors()->get('staff')),
           'note'=> implode($v->errors()->get('note')),
           'branch'=> implode($v->errors()->get('branch')),
           'money'=> implode($v->errors()->get('money')),
           'month_year'=> $month_err,
           ];


echo json_encode([$_POST,'success'=>$success, 'error'=>$error]);
?>