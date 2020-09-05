<?php
session_start();
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,3,4,5,6,7,8,9]);
require_once("dbconnection.php");
try{
  $query = "select storage.*,branches.name as branch_name from storage left join branches on branches.id = storage.branch_id";
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
print_r(json_encode(array("success"=>$success,"data"=>$data)));
?>