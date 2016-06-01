<?php
session_start();
$userid=$_SESSION['User_Id'];
include("connectivity.php");
$request_id=$_REQUEST['hidrequestid'];
$hid_action=$_REQUEST['hidaction'];
$hid_status=$_REQUEST['histatus'];
if($hid_action=="s")
{
	if($hid_status=="Y")
	{
	mysql_query("update attendance_request set approved_by1='$userid',approved_time1=NOW(),approved_status1='accept' where request_id='$request_id'");	
	}
	else
	{
	mysql_query("update attendance_request set approved_by1='$userid',approved_time1=NOW(),approved_status1='reject' where request_id='$request_id'");
    mysql_query("update attendance_request set approved_by2='$userid',approved_time2=NOW(),approved_status2='rejected by Manager',approved_status='N' where request_id='$request_id'");		
	}	
}
if($hid_action=="a")
{
	if($hid_status=="Y")
	{
	mysql_query("update attendance_request set approved_by2='$userid',approved_time2=NOW(),approved_status2='accept',approved_status='Y' where request_id='$request_id'");	
	}
	else
	{
	 mysql_query("update attendance_request set approved_by2='$userid',approved_time2=NOW(),approved_status2='rejected by Admin',approved_status='N' where request_id='$request_id'");		
	}	
	
}
header("location:attendancerequestreport.php");
//echo "Program not completed- In Process";
?>