<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,5]);
require_once("dbconnection.php");
require_once("../config.php");
require_once('../validator/autoload.php');
use Violin\Violin;
$v = new Violin;


$success = 0;
$callcenter  = $_REQUEST['callcenter_id'];
$city    = $_REQUEST['city'];

$v->addRuleMessages([
    'required' => ' الحقل مطلوب',
    'int'      => ' فقط الارقام مسموح بها',
    'regex'    => ' فقط الارقام مسموح بها',
    'min'      => ' قصير جداً',
    'max'      => ' ( {value} ) قيمة كبيرة جداً غير مسموح بها ',
    'email'    => ' البريد الالكتروني غيز صحيح',
]);

$v->validate([
    'callcenter' => [$callcenter,'required|int'],
    'city'   => [$city,  'required|int'],
]);
$msg = "";
if($v->passes() ) {
  $sql = "select * from callcenter_cities where city_id=? and callcenter_id=?";
  $res = getData($con,$sql,[$city,$callcenter]);
  if(count($res) < 1){
      $sql = 'insert into callcenter_cities (callcenter_id,city_id,manager_id) values (?,?,?)';
      $result = setData($con,$sql,[$callcenter,$city,$_SESSION['userid']]);
      if($result > 0){
        $success = 1;
        $msg = "تم الاضافة";
      }else{
         $msg = "!خطأ";
      }
  }else{
    $msg = "مضافه مسبفاً";
  }

}else{
  $error = [
           'callcenter_err'=> implode($v->errors()->get('callcenter')),
           'city_err'=>implode($v->errors()->get('city')),
           ];
 $msg = "خطأ";
}
echo json_encode([$_REQUEST,'success'=>$success, 'error'=>$error,'msg'=>$msg]);
?>