<?php
session_start();
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,5]);
require_once("dbconnection.php");
try{
  $query = "SELECT * FROM loans INNER join clients on clients.id = loans.client_id";
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(["success"=>$success,"data"=>$data]));
?>