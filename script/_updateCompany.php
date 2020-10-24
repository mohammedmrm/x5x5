<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require("_access.php");
access([1,2]);
require("dbconnection.php");
require("_crpt.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$success = 0;
$error = [];
$id    = $_REQUEST['editcompanyid'];
$name    = $_REQUEST['e_company_name'];
$phone   = $_REQUEST['e_company_phone'];
$token  = $_REQUEST['e_company_token'];
$dns = $_REQUEST['e_company_dns'];
$text1 = $_REQUEST['e_company_text1'];
$text2 = $_REQUEST['e_company_text2'];


$v->addRuleMessage('isPhoneNumber', ' رقم هاتف غير صحيح  ');

$v->addRule('isPhoneNumber', function($value, $input, $args) {
    return   (bool) preg_match("/^[0-9]{10,15}$/",$value);
});
$v->addRuleMessage('isURL', 'رابط  غير صحيح');

$v->addRule('isURL', function($value, $input, $args) {
    $value = strpos($value, 'http') !== 0 ? "http://$value" : $value;
    return   (bool) (filter_var($value, FILTER_VALIDATE_URL) || filter_var($value, FILTER_VALIDATE_IP));
});

$v->addRuleMessages([
    'required' => ' الحقل مطلوب ',
    'int'      => ' فقط الارقام مسموع بها ',
    'regex'      => ' فقط الارقام مسموع بها ',
    'min'      => ' قصير جداً ',
    'max'      => ' قيمه كبيرة جداً',
    'email'      => ' البريد الالكتروني غيز صحيح ',
]);

$v->validate([
    'id'            => [$id,    'required|int'],
    'company_name'    => [$name,    'required|min(3)|max(20)'],
    'company_phone'   => [$phone,   "required|isPhoneNumber"],
    'company_token'=> [$token,"required|min(6)|max(250)"],
    'company_dns'    => [$dns,   "required|isURL"],
    'company_text1'   => [$text1, 'max(500)'],
    'company_text2'   => [$text2,'max(500)'],
]);
$valid_file_extensions = array(".jpg", ".jpeg", ".png");
if(isset($_FILES['e_company_logo']['tmp_name']) && $_FILES['e_company_logo']['size'] != 0) {
   if($_FILES['e_company_logo']['size'] >= "2048000"){
    $img_err =  "يجب تحميل صورة صالحة بحجم اقل من 2M";
   }else{
     $ext = strrchr($_FILES['e_company_logo']["name"], ".");
     if(in_array($ext, $valid_file_extensions) && @getimagesize($_FILES["e_company_logo"]["tmp_name"]) !== false){
       $img_err =  "";
     }else{
      $img_err =  "صورة غير صالحة";
     }
   }
} else {
   $img_err =  "";
}
if($v->passes() && $img_err =="") {
  $sql = 'update companies set name = ?, token=?,phone=?,text1=?,text2=?,dns=? where id=?';
  $result = setData($con,$sql,[$name,$token,$phone,$text1,$text2,$dns,$id]);
  if(isset($_FILES['e_company_logo']['tmp_name']) && $_FILES['e_company_logo']['size'] != 0){
    $sql = "select * from companies where id=?";
    $res = getData($con,$sql,[$id]);
    unlink("../img/logos/companies/".$res[0]['logo']);
    $id1 = uniqid();
    $destination = "../img/logos/companies/".$id1.".png";
    $imgpath = $id1.".png";
    move_uploaded_file($_FILES['e_company_logo']["tmp_name"], $destination);
    $sql = 'update companies set logo = ?  where id=? ';
    $result = setData($con,$sql,[$imgpath,$id]);
  }
   if($result > 0){
    $success = 1;
  }
}else{
  $error = [
           'id'   =>implode($v->errors()->get('id')),
           'name' =>implode($v->errors()->get('company_name')),
           'phone'=>implode($v->errors()->get('company_phone')),
           'token'=>implode($v->errors()->get('company_token')),
           'dns'  =>implode($v->errors()->get('company_dns')),
           'logo' =>$img_err,
           'text1'=>implode($v->errors()->get('company_text1')),
           'text2'=>implode($v->errors()->get('company_text2')),
           ];
}
echo json_encode([$_FILES,'success'=>$success, 'error'=>$error]);
?>