<?php
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
$v = new Violin;
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

$v->addRuleMessage('isPhoneNumber', 'رقم هاتف غير صحيح ');

$v->addRule('isPhoneNumber', function($value, $input, $args) {
  if(preg_match("/^[0-9]{10,15}$/",$value) || empty($value)){
    $x=(bool) 1;
  }
    return $x;
});

$v->addRuleMessage('isPrice', 'المبلغ غير صحيح');

$v->addRule('isPrice', function($value, $input, $args) {
  if(preg_match("/^(0|\-\d*|\d*)(\.\d{2})?$/",$value)){
    $x=(bool) 1;
  }
  return   $x;
});

$v->addRuleMessage('unique', 'القيمة المدخلة مستخدمة بالفعل ');

$v->addRule('unique', function($value, $input, $args) {
    $value  = trim($value);
    $exists = getData($GLOBALS['con'],"SELECT * FROM orders WHERE order_no ='".$value."' and id <> '".$GLOBALS['id']."'");
    return ! (bool) count($exists);
});
$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'      => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'تم ادخال بيانات اكثر من الحد المسموح',
    'email'      => 'البريد الالكتروني غيز صحيح',
]);
$error = [];
$success = 0;
$manger = $_SESSION['userid'];

$id = $_REQUEST['barcode'];
$number = $_REQUEST['order']['e_order_no'];
$order_type = 'multi';
$order_price = $_REQUEST['order']['e_price'];
$order_discount= $_REQUEST['order']['e_discount'];
$client = $_REQUEST['order']['e_client'];
$store = $_REQUEST['order']['e_store'];
$client_phone = $_REQUEST['order']['e_client_phone'];
$customer_name = $_REQUEST['order']['e_customer_name'];
$customer_phone = $_REQUEST['order']['e_customer_phone'];
$city_to = $_REQUEST['order']['e_city'];
$town_to = $_REQUEST['order']['e_town'];
$order_note= $_REQUEST['order']['e_order_note'];
$price = $_REQUEST['price'];
$barcode = $_REQUEST['barcode'];
$date= $_REQUEST['order']['e_date'];
if(!validateDate($date)){
  $date_err = "تاريخ غير صالح";
}else{
  $date_err = "";
}
if(empty($number)){
  $number = "1";
}
$v->validate([
    'id'            => [$id,    'required|int'],
    'order_no'      => [$number,'required|min(1)|max(100)'],
    'order_price'   => [$order_price,   "isPrice"],
    'order_discount'=> [$order_discount,"isPrice"],
    'store'         => [$store,  'int'],
    'customer_name' => [$customer_name, 'min(2)|max(100)'],
    'customer_phone'=> [$customer_phone,'isPhoneNumber'],
    'city'          => [$city_to,  'int'],
    'town'          => [$town_to,  'int'],
    'order_note'    => [$order_note,'max(250)'],
]);

$response = [];
$sql ="select * from orders where id = ?";
$order = setData($con,$sql,[$id]);
if($v->passes() && $date_err =="" ) {
  try{
  $sql = 'update orders set order_no="'.$number.'"';
  $up = "";
  if(!empty($weight) && $weight > 0){
    $up .= ' , weight='.$weight;
  }
  if(!empty($qty) && $qty > 0){
    $up .= ' , qty='.$qty;
  }
  if(!empty($branch_to)  && $branch_to > 0){
    $up .= ' , to_branch='.$branch_to;
  }
  if(!empty($branch)  && $branch > 0){
    $up .= ' , from_branch='.$branch;
  }
  if(!empty($city_to) && $city_to > 0){
    $up .= ' , to_city='.$city_to;
  }
  if(!empty($town_to) && $town_to > 0){
    $up .= ' , to_town='.$town_to;
  }
  if(!empty($order_price)){
    $up .= ' , price="'.$price.'"';
  }
  if(!empty($order_iprice)){
    $up .= ' , new_price="'.$price.'"';
  }
  if(!empty($customer_phone)){
    $up .= ' , customer_phone="'.$customer_phone.'"';
  }
  if(!empty($customer_name)){
    $up .= ' , customer_name="'.$customer_name.'"';
  }
  if(!empty($order_note)){
    $up .= ' , note="'.$order_note.'"';
  }
  if(!empty($date)){
    $up .= ' , date="'.$date.'"';
  }
  $where = " where id =".$barcode."  and invoice_id=0 and driver_invoice_id=0 and confirm = 5 and client_id=?";
  $sql .= $up.$where;
  $result = setData($con,$sql,[$clinetdata['id']]);
  if($result > 0){
    $success = 1;
  }
  }catch(PDOException $ex) {
   $error=["error"=>$ex];
   $success="0";
}
}else{
$error = [
           'id'=> implode($v->errors()->get('id')),
           'order_no'=>implode($v->errors()->get('order_no')),
           'order_price'=>implode($v->errors()->get('order_price')),
           'order_discount'=>implode($v->errors()->get('v')),
           'store'=>implode($v->errors()->get('store')),
           'customer_name'=>implode($v->errors()->get('customer_name')),
           'customer_phone'=>implode($v->errors()->get('customer_phone')),
           'city'=>implode($v->errors()->get('city')),
           'town'=>implode($v->errors()->get('town')),
           'order_note'=>implode($v->errors()->get('order_note')),
           'date'=>$date_err,
           'premission'=>$premission
           ];
}
echo json_encode([$_REQUEST,'success'=>$success, 'error'=>$error]);
?>