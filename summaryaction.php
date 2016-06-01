<?php
session_start();
//$start_time = microtime(true);
$User_Ids=$_SESSION['User_Id'];
include("connectivity.php");
$User_Id=$_REQUEST['hiddenid'];
foreach($User_Id as $userid)
{
$User_Id=$userid;
}
$SummaryId=$_REQUEST['hiddensummaryid'][$User_Id];
$editedby=$_REQUEST['edited'][$User_Id];
//to enter the previous datas to log book

$qry=mysql_query("select * from attendance_summary where Attendance_Id='$SummaryId'");
  while($obj=mysql_fetch_object($qry))
  {
    $Id=$obj->Attendance_Id;
	$Month=$obj->Attendance_Month;
	$Year=$obj->Attendance_Year;
	$EmployeeId=$obj->Attendance_UserId;
  }

//echo "userid=".$User_Id;
$Present_NormalDays=$_REQUEST['Present_NormalDays'][$User_Id];
//$Present_FriDays=$_REQUEST['Present_FriDays'][$User_Id];
$Present_FriDays=0;
$Present_TotalDays=$_REQUEST['Present_TotalDays'][$User_Id];
$Absent_Days=$_REQUEST['Absent_Days'][$User_Id];
$Deduction_Hours=$_REQUEST['Deduction_McHours'][$User_Id];
$dedHrs=explode(":",$Deduction_Hours);
$ded_McMins=($dedHrs[0]*60)+($dedHrs[1]*1);
//echo 'present days :'.$User_Id.'-'.$Present_NormalDays;
//entries done by administrator
$Tot_Salary_Days=$_REQUEST['Tot_Salary_Days'][$User_Id];
$Present_ManDays=$_REQUEST['Present_ManDays'][$User_Id];
$Deduction_ManHours=$_REQUEST['Deduction_Hours'][$User_Id];
$Deduction_ManMinutes=$_REQUEST['Deduction_Minutes'][$User_Id];
$totdedmins=($Deduction_ManHours*60)+($Deduction_ManMinutes*1);
//$OT_ManMinutes=$_REQUEST['OT_ManMinutes'][$User_Id];
//$OT_ManHours=$_REQUEST['OT_ManHours'][$User_Id];
//$tototmins=($OT_ManHours*60)+($OT_ManMinutes*1);
$tototmins=$_REQUEST['OT_Minutes'][$User_Id];;
$Total_ManDays=$_REQUEST['Total_ManDays'][$User_Id];
$Remarks=$_REQUEST['Remarks'][$User_Id];
$Month--;
$datefrom=$Year."-".$Month."-21";
$Month++;
$dateto=$Year."-".$Month."-20 23:59:59";
$qryreq=mysql_query("select * from employee_request where (ER_RequestTimeIn between '$datefrom' and '$dateto') and (ER_Status='Pending') and ER_UserId='$EmployeeId'");
$pendingqueries=mysql_num_rows($qryreq);
if($Month<10)
{$Month11="0".$Month;}
$qryadjustment=mysql_query("select * from attendance_request where month='$Month11' and year='$Year' and approved_status='P' and user_id='$EmployeeId'");
//echo "select * from attendance_request where month='$Month11' and year='$Year' and approved_status='P' and user_id='$EmployeeId'";
$pendingqueries1=mysql_num_rows($qryadjustment);
if($pendingqueries1>0)
{
header("location:attendancerequestreport.php");
}
elseif($pendingqueries>0)
{
echo "<font color='red' size='10'>Employee request pending..Please go to </font><a href='employeerequest.php'>Request View</a> <font color='red' size='10'>and save it first</font>";
}
else
{

if(($User_Ids!=1) and ($Present_ManDays>0 or $Deduction_ManHours<0 or $Deduction_ManMinutes<0 or $OT_ManMinutes>0 or $OT_ManHours>0))
{
mysql_query("update attendance_summary set Attendance_Editedby='',Attendance_PendingApproval='Y',Attendance_SuggestedBy='$User_Ids' where Attendance_Id='$SummaryId'");
}
else
{
mysql_query("update attendance_summary set Attendance_Editedby='$User_Ids',Attendance_PendingApproval='N' where Attendance_Id='$SummaryId'");
}
	
mysql_query("update attendance_summary set  Attendance_ManPresentDay='$Present_ManDays',Attendance_ManDedMins='$totdedmins',Attendance_ManOtMins='$tototmins',Attendance_Remarks='$Remarks' where Attendance_Id='$SummaryId'");
//total normal working days
$totalworkingdays=$Present_NormalDays+$Present_FriDays+$Absent_Days;
//days to be adjusted for salary
$totaldays=$Present_NormalDays+$Absent_Days;
//to get salary days as 30
/*if($Present_NormalDays==0)
{
//$adjustmentdays=0;
}
else
{
//$adjustmentdays=30-$totalworkingdays;
}*/
$Salary_Days=$Present_ManDays+$Present_NormalDays+$Present_FriDays;
//echo "$Salary_Days=$adjustmentdays+$Present_ManDays+$Present_NormalDays";
$Salary_Absent=$totalworkingdays-$Salary_Days;
//echo "($ded_McMins*1)+($totdedmins*1)";
$Salary_Ded=($ded_McMins*1)+($totdedmins*1);

$Salary_OT=$tototmins;
mysql_query("update attendance_summary set  Attendance_SalaryTotDays='$Tot_Salary_Days',Attendance_SalaryDays='$Salary_Days',Attendance_SalaryAbsentDays='$Salary_Absent',Attendance_SalaryDedMins='$Salary_Ded',Attendance_SalaryOtMins='$Salary_OT' where Attendance_Id='$SummaryId'");
//echo "update attendance_summary set  Attendance_SalaryDays='$Salary_Days',Attendance_SalaryAbsentDays='$Salary_Absent',Attendance_SalaryDedMins='$Salary_Ded',Attendance_SalaryOtMins='$Salary_OT' where Attendance_Id='$SummaryId'";

$datefrom=$_REQUEST['datefrom'];
$dateto=$_REQUEST['dateto'];
$searchname=$_REQUEST['searchname'];
$searchterm=$_REQUEST['searchterm'];
$date1=explode("-",$datefrom);
$month=$date1[1];
$year=$date1[0];
$datefrom=$datefrom[$User_Id];
$dateto=$dateto[$User_Id];
$searchname=$searchname[$User_Id];
$searchterm=$searchterm[$User_Id];
//echo "This page was generated in  ".(number_format(microtime(true) - $start_time, 2))."  seconds.";
header("location:attendancesummary.php?datefrom=$datefrom&dateto=$dateto&searchname=$searchname&searchterm=$searchterm");
}

?>