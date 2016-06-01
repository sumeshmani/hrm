<?php
session_start();
include "connectivity.php";

$EmployeeId=$_REQUEST['Emp_Id'];
$Vacation_Year=$_REQUEST['Vacation_Year'];
$Vacation_Remarks=$_REQUEST['Vacation_Remarks'];
$StartDate=date('Y-m-d 00:00:00',strtotime($_REQUEST['vacation_StartDate']));
$EndDate=date('Y-m-d 23:59:59',strtotime($_REQUEST['vacation_EndDate']));
$vacation_interval=(strtotime($EndDate)-strtotime($StartDate))/(60*60*24);
echo "vacation days".$vacation_interval.",".$StartDate.",".$EndDate;
$invaliddate="N";
if($StartDate=='1970-01-01 00:00:00' or $EndDate=='1970-01-01 23:59:59' or $vacation_interval<=0)
{
	$invaliddate="Y";
	//echo "vacation days selected is ".$vacation_interval.". It should be greater than 20 days for vacation calculation.";
}

$qry_vacation=mysql_query("select * from sftl_vacation where Vacation_UserId='$EmployeeId' and (((Vacation_StartDate between '$StartDate' and '$EndDate') OR (Vacation_EndDate between '$StartDate' and '$EndDate')) or (Vacation_StartDate<='$StartDate' and Vacation_EndDate>='$EndDate'))");
//echo "select * from sftl_vacation where Vacation_UserId='$EmployeeId' and (((Vacation_StartDate between '$StartDate' and '$EndDate') OR (Vacation_EndDate between '$StartDate' and '$EndDate')) or (Vacation_StartDate<='$StartDate' and Vacation_EndDate>='$EndDate'))";
$num_vacation=mysql_num_rows($qry_vacation);
if($num_vacation>0 or $invaliddate=="Y")
{
	echo "Data Already Exists or overlapping the existing dates or invalid date, Please verify again";
}
else
{
	mysql_query("insert into sftl_vacation (Vacation_UserId,Vacation_Year,Vacation_StartDate,Vacation_EndDate,Vacation_Remarks) values('$EmployeeId','$Vacation_Year','$StartDate','$EndDate','$Vacation_Remarks')");
}
//echo "insert into sftl_vacation (Vacation_UserId,Vacation_Year,Vacation_StartDate,Vacation_EndDate,Vacation_Type) values('$EmployeeId','$Vacation_Year','$StartDate','$EndDate','$Vacation_Type')";
header("location:vacationplanner.php");
?>
