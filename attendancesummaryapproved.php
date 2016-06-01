<?php
session_start();
$username=$_SESSION['User_Name'];
include("connectivity.php");
$month=$_REQUEST['month'];

$year=$_REQUEST['year'];
$dateto=$year."-".$month."-20";
$datefrom=date("Y-m-21",strtotime("$dateto-30 days"));
$dateto=$dateto." 23:59:59";
$datefrom=$datefrom." 00:00:00";
$hidStatus=$_REQUEST['hidStatus'];
if($hidStatus="Approved")
{
$qryreq=mysql_query("select * from employee_request where (ER_RequestTimeIn between '$datefrom' and '$dateto') and (ER_Status='Pending')");
$pendingqueries=mysql_num_rows($qryreq);
if($pendingqueries>0)
{
//echo "ok";
$datefrom=$_REQUEST['datefrom'];
$dateto=$_REQUEST['dateto'];
header("location:employeerequest.php?datefrom=$datefrom&dateto=$dateto&hidStatus='P'");
}
else
{
mysql_query("update attendance_summary set Attendance_Approval='Y' where Attendance_Month='$month' and Attendance_Year='$year'");
//echo"update attendance_summary set Attendance_Approval='Y' where Attendance_Month='$month' and Attendance_Year='$year'";
mysql_query("insert into attendance_lock (attendance_dateFrom,attendance_dateTo,attendance_lockMonth,attendance_lockYear,attendance_lockStatus,attendance_lockedBy) values('$datefrom','$dateto','$month','$year','Y','$username')");
$datefrom=$_REQUEST['datefrom'];
$dateto=$_REQUEST['dateto'];
header("location:attendancesummary.php?datefrom=$datefrom&dateto=$dateto");
}
//echo "insert into attendance_lock (attendance_dateFrom,attendance_dateTo,attendance_lockMonth,attendance_lockYear,attendance_lockStatus,attendance_lockedBy) values('$datefrom','$dateto','$month','$year','Y','$username')";
}

?>