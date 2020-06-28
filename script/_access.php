<?php
//error_reporting(0);
if(!isset($_SESSION)){
session_start();
}
require_once("dbconnection.php");
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
     $link = "https";
}else{
    $link = "http";
}
// Here append the common URL characters.
$link .= "://";

// Append the host(domain name, ip) to the URL.
$link .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL
$link .= $_SERVER['REQUEST_URI'];

function access($access_roles = []){
  if(!empty($_COOKIE['username']) && !empty($_COOKIE['password'])){
    $sql = "select staff.*,role.home as home from staff inner join role on role.id = staff.role_id where phone = ? and password =? and status=1";
    $result = getData($GLOBALS['con'],$sql,[$_COOKIE['username'],$_COOKIE['password']]);
  }
  if(count($result)> 0){
    $_SESSION['login']=1;
    $_SESSION['username']=$result[0]['phone'];
    $_SESSION['userid']=$result[0]['id'];
    $_SESSION['role']=$result[0]['role_id'];
    $_SESSION['user_details']=$result[0];
  }
  if(!in_array($_SESSION['user_details']['role_id'],$access_roles) || !isset($_SESSION['userid'])){
    header("location: login.php?redirect=".$GLOBALS['link']);
    die("<h1>لاتمتلك صلاحيات الوصول لهذه الصفحة  (<a href='login.php'>سجل الدخول</a>)</h1>");
  }
}
?>