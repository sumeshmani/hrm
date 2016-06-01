<?php
session_start();
$Department=$_SESSION['Department'];
$Designation=$_SESSION['Designation'];
$UserName=$_SESSION['User_Name'];
$User_Id=$_SESSION['User_Id'];
include("connectivity.php");
//include("ajax.php");

$Employee_Id=$_REQUEST['Employee_Id'];
$Employee_Name=$_REQUEST['Employee_Name'];
$Employee_Designation=$_REQUEST['Employee_Designation'];
$Employee_Grade=$_REQUEST['Employee_Grade'];
$Employee_Department=$_REQUEST['Employee_Department'];
$Employee_Year=$_REQUEST['Employee_Year'];
$Employee_SupervisorId=$_REQUEST['Employee_SupervisorId'];
$Employee_ManagerId=$_REQUEST['Employee_ManagerId'];
$Kpi_Sl=$_REQUEST['Kpi_Sl'];
$Kpi_Details=$_REQUEST['Kpi_Details'];
$Kpi_TotalScore=$_REQUEST['Kpi_TotalScore'];
//$Kpi_PerDone1=$_REQUEST['Kpi_PerDone1'];
//$Kpi_Score1=$_REQUEST['Kpi_Score1'];
//$Kpi_PerDone2=$_REQUEST['Kpi_PerDone2'];
//$Kpi_Score2=$_REQUEST['Kpi_Score2'];
//$Kpi_Remarks=$_REQUEST['Kpi_Remarks'];
//$Kpi_OverallScore=$_REQUEST['Kpi_OverallScore'];

$Id=$_REQUEST['Kpi_Id'];
mysql_query("insert into kpi_details(Kpi_MasterId,Kpi_SlNo,Kpi_Details,Kpi_TotalScore) values('$Id','$Kpi_Sl','$Kpi_Details','$Kpi_TotalScore')");
header("location:addkpidetails.php?Kpi_Employee=$Employee_Id&Kpi_Year=$Employee_Year");
?>