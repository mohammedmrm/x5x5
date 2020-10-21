<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require_once("_access.php");
access([1,2,5]);
require_once("dbconnection.php");
$client = $_REQUEST['client'];
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
if(!empty($end)) {
   $end .=" 23:59:59";
}else{
   $end =date('Y-m-d', strtotime(' + 1 day'));
   $end .=" 23:59:59";
}
if(!empty($start)) {
   $start .=" 00:00:00";
}
$where = "where ";
function validateDate($date, $format = 'Y-m-d H:i:s')
  {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) == $date;
  }
if(validateDate($start) && validateDate($end)){
    $filter .= " and loans.date between '".$start."' AND '".$end."'";
}
  if($client >= 1){
    $filter .= " and loans.client_id=".$client;
  }
try{
  $query = "SELECT *,loans.id as l_id FROM loans INNER join clients on clients.id = loans.client_id";
  if($filter != ""){
    $filter = preg_replace('/^ and/', '', $filter);
    $filter = $where." ".$filter;
    $query .= " ".$filter;
  }
   $query .= ' order by loans.date DESC';
  $data = getData($con,$query);
  $success="1";
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo (json_encode(["success"=>$success,"data"=>$data]));
?>