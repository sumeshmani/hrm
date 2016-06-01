<?php
session_start();
function filename_safe($name) { 
   $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|','\''); 
   return str_replace($except, '', $name); 
} 
$userid=$_SESSION['User_Id'];
include("connectivity.php");
$EmployeeId=filename_safe($_REQUEST['hidemployeeid']);
$Type=$_REQUEST['request_type'];
//$ODType=$_REQUEST['odtype'];
$month=$_REQUEST['month'];
$year=$_REQUEST['year'];
$days=$_REQUEST['request_days'];
$hours=$_REQUEST['request_hours'];
$minutes=$_REQUEST['request_minutes'];
$Remarks=addslashes($_REQUEST['Remarks']);
mysql_query("insert into attendance_request(user_id,request_type,month,year,request_days,request_hours,request_minutes,request_remarks,requested_by) values ('$EmployeeId','$Type','$month','$year','$days','$hours','$minutes','$Remarks','$userid')");
header("location:attendancerequestreport.php");
?>