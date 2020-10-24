<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require_once("_access.php");
access([1,2,3,4,5,6]);
require_once("dbconnection.php");
$company = $_REQUEST['company'];
if($company > 0){
  $msg ="";
}else{
  $msg = "يجب تحديد شركه التوصيل";
}
$response = 0;
if($msg == ""){
  $sql ="select * from companies where id=?";
  $res= getData($con,$sql,[$company]);
  if(count($res) == 1){
      $response = httpPost($res[0]['dns'].'api/getStore.php',['token'=>$res[0]['token']]);
  }else{
    $msg = "يجب اختيار شركة التوصيل";
  }
}
function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
echo (json_encode(array("msg"=>$msg,"response"=>json_decode(substr($response, 3)))));
?>