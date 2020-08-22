<?php
session_start();
header('Content-Type: application/json');
require_once("dbconnection.php");
$id = $_REQUEST['branch'];
try{
  $query = "select * from staff where branch_id = ?";
  $data = getData($con,$query,[$id]);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(array("success"=>$success,"data"=>$data)));
?>