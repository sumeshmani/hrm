<?php
session_start();
include ("connectivity.php");
$Kpi_Id=$_REQUEST['Kpi_ParentId'];
$year=$_REQUEST['Kpi_Year'];
$employee_id=$_REQUEST['Kpi_Employee'];
mysql_query("update kpi_master set Kpi_Status='1' where Kpi_Id='$Kpi_Id'");
header("location:addkpidetails.php?Kpi_Employee=$employee_id&Kpi_Year=$year");

?>