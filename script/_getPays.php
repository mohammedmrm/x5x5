<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,5]);
require_once("dbconnection.php");
require_once("_crpt.php");

$success = 0;
$error = [];

$branch= $_SESSION['user_details']['branch_id'];
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
if(empty($end)) {
  $end = date('Y-m-d',strtotime(' + 1 day'));
}
if(empty($start)) {
  $start = date('Y-m-d',strtotime(' - 30 day'));
}

if(!validateDate($start)){
  $start_err = "تاريخ البداية غير صالح";
}else{
  $start_err = "";
}

if(!validateDate($end)){
  $end_err = "تاريخ النهاية غير صالح";
}else{
 $end_err="";
}
function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}



if($start_err == "" && $end_err == "")  {

  $sql= "select *,pays.id as p_id ,DATE_FORMAT(pays.date,'%Y-%m-%d %h:%i') as date from pays
         left join staff on staff.id = pays.staff_id
         where pays.date between '".$start."' and '".$end."'
         order by pays.date DESC
         ";
  $res = getData($con,$sql);
  if(count($res) > 0){
    $success = 1;
  }

}else{
  $error = [
           'end'=> $end_err,
           'start'=>$start_err,
           ];
}

echo json_encode(["data"=>$res,'success'=>$success, 'error'=>$error,'role'=>$_SESSION['user_details']['role_id'],'branch'=>$branch]);
?>