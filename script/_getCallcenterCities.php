<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
require_once("dbconnection.php");
require_once("../config.php");
$id= $_REQUEST['id'];
$success=0;
$i=0;
try{
  $query = "select callcenter_cities.*,cites.name as city_name
            from callcenter_cities
            left join cites on cites.id = callcenter_cities.city_id
            where callcenter_id =?";
  $data = getData($con,$query,[$id]);
  $sql = "select name from staff where id=?";
  $res = getData($con,$sql,[$id]);
  $res =$res[0];
  $success = 1;
} catch(PDOException $ex) {
   $data=["error"=>$ex];
   $success="0";
}
echo json_encode(array($id,"success"=>$success,"data"=>$data,'name'=>$res));
?>