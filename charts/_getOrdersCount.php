<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require("../script/_access.php");
access([1,2,5,3]);
require("../script/dbconnection.php");
$start = trim($_REQUEST['start']);
$end = trim($_REQUEST['end']);
$response = [];
if(empty($end)) {
  $end = date('Y-m-d h:i:s', strtotime($end. ' + 1 day'));
}else{
   $end =date('Y-m-d', strtotime($end. ' + 1 day'));
   $end .=" 00:00:00";
}
if(empty($start)) {
  $start = date('Y-m-d h:i:s',strtotime($start. ' - 7 day'));
}else{
   $start .=" 00:00:00";
}
if($_SESSION['user_details']['role_id'] == 1){
/*  $sql = 'select count(*) as counts,order_status,from_branch,branches.name
           from orders
           INNER join branches on branches.id = orders.from_branch
           right join order_status on order_status.id = orders.order_status
           where date between "'.$start.'" and "'.$end.'"
           GROUP by  from_branch, order_status';*/
$sql = "SELECT
          SUM(IF (order_status_id = '1',1,0)) as  regiserd,
          SUM(IF (order_status_id = '2',1,0)) as  redy,
          SUM(IF (order_status_id = '3',1,0)) as  ontheway,
          SUM(IF (order_status_id = '4',1,0)) as  recieved,
          SUM(IF (order_status_id = '5',1,0)) as  chan,
          SUM(IF (order_status_id = '9',1,0)) as  returnd,
          SUM(IF (order_status_id = '7',1,0)) as  posponded,
          branches.name as branch_name
          FROM orders inner join branches on branches.id = orders.from_branch
          where date between '".$start."' and '".$end."'
          GROUP BY from_branch";
}else{
$sql = "SELECT
          SUM(IF (order_status = '1',1,0)) as  regiserd,
          SUM(IF (order_status = '2',1,0)) as  redy,
          SUM(IF (order_status = '3',1,0)) as  ontheway,
          SUM(IF (order_status = '4',1,0)) as  recieved,
          SUM(IF (order_status = '5',1,0)) as  chan,
          SUM(IF (order_status = '9',1,0)) as  returnd,
          SUM(IF (order_status = '7',1,0)) as  posponded,
          branches.name as branch_name
          FROM orders inner join branches on branches.id = orders.from_branch
          where (date between '".$start."' and '".$end."') and from_branch = '".$_SESSION['user_details']['branch_id']."'
          GROUP BY from_branch";
}
$result = getData($con,$sql);

$sql = "select * from auto_update";
$res = getData($con,$sql);
$ids=[];
$j=0;
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
foreach($res as $val){
   ///-- auto status update ---
   if($val['active'] == 1){
   $auto = "SET @uids := '';
              UPDATE
              orders SET order_status_id = 4
              WHERE (order_status_id = 3 or order_status_id = 1) and driver_id > 0 and invoice_id = 0 and driver_invoice_id = 0 and confirm=1 and to_city = '".$val['city_id']."' and
              DATE(date) < DATE_SUB(CURDATE(), INTERVAL ".$val['days']." DAY) AND ( SELECT @uids := CONCAT_WS(',', id, @uids)) limit 500;
              SELECT @uids as ids;";
   $ids = getAllUpdatedIds($mysqlicon,$auto);
   $idss = explode (",", $ids[0][0]);
   $tracking = "insert into tracking (order_id,order_status_id,note,staff_id) values(?,?,?,?)";
   foreach($idss as $id){
       $sql = "select isfrom ,clients.sync_token as token,clients.sync_dns as dns from orders
               inner join clients on clients.id = orders.client_id
               where orders.id=?";
       $order = getData($con,$sql,[$id]);
       if($order[0]['isfrom'] == 2){
         $response = httpPost($order[0]['dns'].'/api/orderStatusSync.php',
              [
               'token'=>$order[0]['token'],
               'status'=>4,
               'note'=>'',
               'id'=>$id,
              ]);
       }
     if($id > 0){
     $addTrack = setData($con,$tracking,[$id,4,'( تم تحديث الطلب تلقائياً) ',$_SESSION['userid']]);
     $j++;
     }
   }
 }
}
  //--delete old notifications
  $delete = "delete FROM notification WHERE date < DATE_SUB(NOW(), INTERVAL 3 MONTH)";
  setData($con,$delete);
  //--delete old tracking
  $delete = "delete FROM tracking WHERE date < DATE_SUB(NOW(), INTERVAL 3 MONTH)";
  setData($con,$delete);
  //---delete old messages
  $delete = "delete FROM message WHERE date < DATE_SUB(NOW(), INTERVAL 3 MONTH)";
  setData($con,$delete);
  //---delete old storage_tracking
  $delete = "delete FROM storage_tracking WHERE date < DATE_SUB(NOW(), INTERVAL 6 MONTH)";
  setData($con,$delete);

echo json_encode(['data'=>$result,$j,"response"=>json_decode(substr($response, 3))]);
?>