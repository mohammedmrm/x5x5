<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require_once("_access.php");
access([1,2,3,5,7,6,8,9]);
require_once("dbconnection.php");
$branch = $_REQUEST['branch'];
try{
  if($_SESSION['user_details']['role_id'] == 1){
  $query = "select clients.*,branches.name as branch from clients
  inner join branches on branches.id = clients.branch_id";
  }else{
  $query = "select clients.*,branches.name as branch from clients
  inner join branches on branches.id = clients.branch_id where branch_id = ?";
  }
  $data = getData($con,$query,[$_SESSION['user_details']['branch_id']]);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
print_r(json_encode(array("success"=>$success,"data"=>$data)));
?>