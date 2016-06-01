<?php
session_start();
$userid=$_SESSION['User_Id'];
include("connectivity.php");
$employeeid=$_REQUEST['employee_id'];
$shift_id=$_REQUEST['shift_id'];
$startdate=$_REQUEST['startdate'];
$hidstarteditdate=$_REQUEST['hidstarteditdate'];
$enddate=$_REQUEST['enddate'];
$hidAction=$_REQUEST['hidAction'];
$hidId=$_REQUEST['hidId'];
if($startdate=="")
{
$startdate="2015-01-01 00:00:00";
}
if($enddate=="")
{
$enddate="2100-01-01 00:00:00";
}
else
{

}
if(isset($enddate))
{
$enddate=substr($enddate,0,10)." 23:59:59";
}
if($hidAction=="new")
{
mysql_query("insert into shift_details (Shift_EmployeeId,Shift_MasterId,Shift_StartTime,Shift_EndTime,Shift_EnteredBy) values ('$employeeid','$shift_id','$startdate','$enddate','$userid')");
}
else
{

$qryshiftdata=mysql_query("select * from shift_details where shift_DetailsId='$hidId'");
while($objshiftdata=mysql_fetch_object($qryshiftdata))
{
$employeeeditid=$objshiftdata->Shift_EmployeeId;
$shifteditid=$objshiftdata->Shift_MasterId;

//$starteditdate=$objshiftdata->Shift_StartTime;
//$endeditdate=$objshiftdata->Shift_EndTime;
}
$startdate=$startdate." 00:00:00";
$enddate1=date("Y-m-d 23:59:59",(strtotime($startdate)-(60*60*24)));
//echo $startdate . $enddate1;
if($hidstarteditdate==$startdate)
{mysql_query("delete * from shift_details where Shift_DetailsId='$hidId'");}
else{mysql_query("update shift_details set Shift_EndTime='$enddate1',Shift_Status='N' where Shift_DetailsId='$hidId'");}
//mysql_query("update shift_details set Shift_EndTime='$enddate1',Shift_Status='N' where Shift_DetailsId='$hidId'");

mysql_query("insert into shift_details (Shift_EmployeeId,Shift_MasterId,Shift_StartTime,Shift_EndTime,Shift_EnteredBy) values ('$employeeeditid','$shift_id','$startdate','$enddate','$userid')");
$id=mysql_insert_id();
if($enddate!== "2100-01-01 23:59:59")
{
mysql_query("update shift_details set Shift_Status='N' where Shift_DetailsId='$id'");
$startdate1=date("Y-m-d",(strtotime($enddate)+(60*60*24)));
//echo $startdate1;
$enddate= "2100-01-01 23:59:59";
mysql_query("insert into shift_details (Shift_EmployeeId,Shift_MasterId,Shift_StartTime,Shift_EndTime,Shift_EnteredBy) values ('$employeeeditid','15','$startdate1','$enddate','$userid')");
}
}
$searchterm=$_REQUEST['searchterm'];
header("location:shiftdetails.php?searchterm=$searchterm");
?>