<?php
session_start();
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once("../script/dbconnection.php");
require_once("../script/_sendNoti.php");
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


$barcode  = $_REQUEST['id'];
$note     = $_REQUEST['note'];
$status   = $_REQUEST['status'];
$price    = $_REQUEST['price'];
$city     = $_REQUEST['city'];
$town     = $_REQUEST['town'];
$token    = $_REQUEST['token'];
$item_no  = $_REQUEST['item_no'];
if(empty($item_no)){
  $item_no = 1;
}
$v->validate([
    'barcode' => [$barcode, 'required|int'],
    'note'    => [$note,    'min(1)|max(100)'],
    'price'   => [$price,   "isPrice"],
    'status'  => [$status,  "int"],
    'city'    => [$city,    'int'],
    'town'    => [$town,    'int'],
    'token'   => [$token,   'required|min(5)|max(250)'],
    'item_no' => [$item_no, 'int'],
]);

$response = [];
if($v->passes()  ) {
 try{
      $sql = "select * from companies where sync_token=?";
      $company = getData($con,$sql,[$token]);
      if(count($company) == 1){
          $sql =" select * from orders where bar_code =? and delivery_company_id=?";
          $order = getData($con,$sql,[$barcode,$company[0]['id']]);
          if(count($order) == 1){
              $order_id = $order[0]['id'];

              $sql = 'update orders set order_status_id="'.$status.'"';
              $up = "";
              if(!empty($city_to) && $city_to > 0){
                $up .= ' , to_city='.$city_to;
              }
              if(!empty($town_to) && $town_to > 0){
                $up .= ' , to_town='.$town_to;
              }
              if(!empty($order_iprice)){
                $up .= ' , new_price="'.$price.'"';
              }
              $where = " where bar_code =".$barcode."  and invoice_id=0 and driver_invoice_id=0 and orders.delivery_company_id =".$company[0]['id'];
              $sql .= $up.$where;
              $result = setData($con,$sql,[$clinetdata['id']]);
              if($result > 0){
                $success = 1;
                $sql = 'insert into tracking (order_status_id,note,items_no,order_id,staff_id) values(?,?,?,?,?)';
                $result = setData($con,$sql,[$status,$note,$item_no,$order_id,-1]);
                $sql = "select staff.token as s_token, clients.token as c_token from orders inner join staff
                        on
                        staff.id = orders.manager_id
                        or
                        staff.id = orders.driver_id
                        inner join clients on clients.id = orders.client_id
                        where orders.id =  ?";
                $res =getData($con,$sql,[$order_id]);
                sendNotification([$res[0]['s_token'],$res[0]['c_token']],[$order_id],'طلب رقم ',$note,"../orderDetails.php?o=".$order_id);
              }
          }else{
             $error=["error"=>"order not found"];
             $success="0";
          }
      }else{
       $error=["error"=>"access denied"];
       $success="0";
      }
  }catch(PDOException $ex) {
   $error=["error"=>$ex];
   $success="0";
}
}else{
$error = [
           'barcode'=> implode($v->errors()->get('id')),
           'price'=>implode($v->errors()->get('price')),
           'status'=>implode($v->errors()->get('status')),
           'city'=>implode($v->errors()->get('city')),
           'town'=>implode($v->errors()->get('town')),
           'token'=>implode($v->errors()->get('token')),
           'item_no'=>implode($v->errors()->get('item_no')),
        ];
}
echo json_encode(['success'=>$success, 'error'=>$error]);
?>