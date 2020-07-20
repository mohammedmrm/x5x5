<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require("_access.php");
access([1]);
$days = $_REQUEST['days'];
if(empty($days) || $days < 0){
  $days =7;
}
$success = 0;
require("dbconnection.php");
///-- auto status update ---
$auto = "SET @uids := '';
          UPDATE
          orders SET order_status_id = 4
          WHERE order_status_id = 3 and invoice_id = 0 and driver_invoice_id = 0 and confirm=1 and
          DATE(date) < DATE_SUB(CURDATE(), INTERVAL ".$days." DAY) AND ( SELECT @uids := CONCAT_WS(',', id, @uids));
          SELECT @uids as ids;";
$ids = getAllUpdatedIds($mysqlicon,$auto);
$ids = explode (",", $ids[0][0]);
if(count($ids)>0){
 $success = 1;
}
$tracking = "insert into tracking (order_id,order_status_id,note,staff_id) values(?,?,?,?)";
foreach($ids as $id){
  $addTrack = setData($con,$tracking,[$id,4,'( تم تحديث الطلب تقائياً) ',$_SESSION['userid']]);
}
echo json_encode(['success'=>$success]);
?>