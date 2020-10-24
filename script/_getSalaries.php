<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
require_once("_access.php");
access([1,2,5]);
require_once("dbconnection.php");
require_once("_crpt.php");

use Violin\Violin;
require_once('../validator/autoload.php');
$v = new Violin;


$success = 0;
$error = [];
$year  = $_REQUEST['year'];
if(empty($year)){
  $year = date('Y');
}
$branch = $_REQUEST['branch'];
$month  = $_REQUEST['month'];


$v->addRuleMessages([
    'required' => 'الحقل مطلوب',
    'int'      => 'فقط الارقام مسموع بها',
    'regex'    => 'فقط الارقام مسموع بها',
    'min'      => 'قصير جداً',
    'max'      => 'مسموح ب {value} رمز كحد اعلى ',
    'email'    => 'البريد الالكتروني غيز صحيح',
]);

$v->validate([
    'year'   => [$year,  'required|min(4)|max(4)|int'],
    'month'  => [$month, 'int'],
    'branch' => [$branch, "int"],
]);
if($v->passes())  {
  $now = date("Y-m-d H:i:s");
  $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  $start_date = $year."-".$month."-01";
  $end_date = $year."-".$month."-".$days;
  if($_SESSION['role'] == 1){
  if($branch == 0 || empty($branch)){
  $sql = 'select  ROUND(sum((staff.salary))) as salaries, count(*) as staffnumber,branches.id as b_id ,
  branches.name as branch_name,  if(max(salary_pays.confirm) IS NULL,0,max(salary_pays.confirm)) as status,
  max(salary_pays.id) as pay_id
  from staff
  inner join branches on branches.id = staff.branch_id
  left join salary_pays on salary_pays.branch_id = staff.branch_id  and  salary_pays.year = "'.$year.'"  and  salary_pays.month = "'.$month.'"

  left join
  ( select
                    if(
                     sum(
                          if(staff_leave.start_date <  "'.$end_date.'",
                            if(staff_leave.end_date > "'.$end_date.'",'.$days.',datediff(staff_leave.end_date,staff_leave.start_date)),
                            if(staff_leave.end_date < "'.$end_date.'",datediff(staff_leave.end_date,"'.$start_date.'"),datediff("'.$end_date.'",staff_leave.start_date))
                            )
                        ) is NULL,0,
                     sum(
                          if(staff_leave.start_date <  "'.$end_date.'",
                            if(staff_leave.end_date > "'.$end_date.'",'.$days.',datediff(staff_leave.end_date,staff_leave.start_date)+1),
                            if(staff_leave.end_date < "'.$end_date.'",datediff(staff_leave.end_date,"'.$start_date.'")+1,datediff("'.$end_date.'",staff_leave.start_date)+1)
                            )
                        )
                        ) as leave_days,max(staff_id) as s_id
   FROM    staff_leave
   where staff_leave.start_date >= "'.$start_date.'" and staff_leave.end_date <= "'.$end_date.'"  and with_salary <> 1
   group by staff_id
  ) a on a.s_id = staff.id


  where staff.role_id <> 4
  group by  staff.branch_id';
  }else{
  $sql = 'select  ROUND(sum((staff.salary))) as salaries, count(*) as staffnumber,branches.id as b_id ,
  branches.name as branch_name,  if(max(salary_pays.confirm) IS NULL,0,max(salary_pays.confirm)) as status,
  max(salary_pays.id) as pay_id
  from staff
  inner join branches on branches.id = staff.branch_id
  left join salary_pays on salary_pays.branch_id = staff.branch_id  and  salary_pays.year = "'.$year.'"  and  salary_pays.month = "'.$month.'"

  left join
  ( select
                    if(
                     sum(
                          if(staff_leave.start_date <  "'.$end_date.'",
                            if(staff_leave.end_date > "'.$end_date.'",'.$days.',datediff(staff_leave.end_date,staff_leave.start_date)),
                            if(staff_leave.end_date < "'.$end_date.'",datediff(staff_leave.end_date,"'.$start_date.'"),datediff("'.$end_date.'",staff_leave.start_date))
                            )
                        ) is NULL,0,
                     sum(
                          if(staff_leave.start_date <  "'.$end_date.'",
                            if(staff_leave.end_date > "'.$end_date.'",'.$days.',datediff(staff_leave.end_date,staff_leave.start_date)+1),
                            if(staff_leave.end_date < "'.$end_date.'",datediff(staff_leave.end_date,"'.$start_date.'")+1,datediff("'.$end_date.'",staff_leave.start_date)+1)
                            )
                        )
                        ) as leave_days,max(staff_id) as s_id
   FROM    staff_leave
   where staff_leave.start_date >= "'.$start_date.'" and staff_leave.end_date <= "'.$end_date.'"  and with_salary <> 1
   group by staff_id
  ) a on a.s_id = staff.id

  where staff.role_id <> 4  and staff.branch_id = "'.$branch.'"
  group by staff.branch_id';
  }
  }else{
  $sql = 'select  ROUND(sum((staff.salary))) as salaries, count(*) as staffnumber,branches.id as b_id ,
  branches.name as branch_name,  if(max(salary_pays.confirm) IS NULL,0,max(salary_pays.confirm)) as status,
  max(salary_pays.id) as pay_id
  from staff
  inner join branches on branches.id = staff.branch_id
  left join salary_pays on salary_pays.branch_id = staff.branch_id  and  salary_pays.year = "'.$year.'"  and  salary_pays.month = "'.$month.'"

  left join
  ( select
                    if(
                     sum(
                          if(staff_leave.start_date <  "'.$end_date.'",
                            if(staff_leave.end_date > "'.$end_date.'",'.$days.',datediff(staff_leave.end_date,staff_leave.start_date)),
                            if(staff_leave.end_date < "'.$end_date.'",datediff(staff_leave.end_date,"'.$start_date.'"),datediff("'.$end_date.'",staff_leave.start_date))
                            )
                        ) is NULL,0,
                     sum(
                          if(staff_leave.start_date <  "'.$end_date.'",
                            if(staff_leave.end_date > "'.$end_date.'",'.$days.',datediff(staff_leave.end_date,staff_leave.start_date)+1),
                            if(staff_leave.end_date < "'.$end_date.'",datediff(staff_leave.end_date,"'.$start_date.'")+1,datediff("'.$end_date.'",staff_leave.start_date)+1)
                            )
                        )
                        ) as leave_days,max(staff_id) as s_id
   FROM    staff_leave
   where staff_leave.start_date >= "'.$start_date.'" and staff_leave.end_date <= "'.$end_date.'"  and with_salary <> 1
   group by staff_id
  ) a on a.s_id = staff.id

  where staff.role_id <> 4  and staff.branch_id = "'.$branch.'"
  group by staff.branch_id';
  }
  //echo $sql;
  $result = getData($con,$sql,[]);
  if($result > 0){
    $success = 1;
  }
}else{
  $error = [
           'year'=> implode($v->errors()->get('year')),
           'month'=>implode($v->errors()->get('month')),
           'branch'=>implode($v->errors()->get('branch')),
           ];
}
$total = 0;
foreach($result as $k=>$v){
 $total += $v['salaries'];
}

echo json_encode(['year'=>$year,'month'=>$month,'data'=>$result,"total"=>$total,'success'=>$success, 'error'=>$error,'role'=>$_SESSION['user_details']['role_id']]);
?>