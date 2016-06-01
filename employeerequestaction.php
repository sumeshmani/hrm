<?php
session_start();
function filename_safe($name) { 
   $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|','\''); 
   return str_replace($except, '', $name); 
} 
$userid=$_SESSION['User_Id'];
include("connectivity.php");
$EmployeeId=filename_safe($_REQUEST['hidemployeeid']);
$Type=$_REQUEST['requesttype'];
$ODType=$_REQUEST['odtype'];
$dates=$_REQUEST['Dates'];
$Timein=$_REQUEST['TimeIn'];
$Timeout=$_REQUEST['TimeOut'];
$reqin=$Timein;
$reqout=$Timeout;
$Remarks=addslashes($_REQUEST['Remarks']);
$paidstatus=$_REQUEST['paid'];
$hidAction=$_REQUEST['hidAction'];
$month=explode("-",$dates);
$date1=$month[2];
$month1=$month[1];
$year1=$month[0];
//echo "month=".$date1."". $month1."".$year1;
if($date1>20)
{
$month1++;
if($month1<10)
{$month1="0".$month1;}
}
//echo "action=".$hidAction;
if($hidAction=="sapproval")
{
$hidId=$_REQUEST['hidId'];
$saccept=$_REQUEST['saccept'];
$sremarks=$_REQUEST['SupervisorRemarks'];
if($saccept=="Y")
{
$sstatus="Approved";
}
else
{
$sstatus="Rejected";
$paidapproval="N";
}

mysql_query("update employee_request set ER_SupervisorApproval='$sstatus',ER_SupervisorApprovedBy='$userid',ER_SupervisorRemarks='$sremarks' where ER_Id='$hidId'");
//echo "update employee_request set ER_SupervisorApproval='$sstatus',ER_ApprovedBy='$userid' where ER_Id='$hidId'";
}
elseif($hidAction=="new" or $hidAction=="prior")
{
$qrysummarystatus=mysql_query("select * from attendance_summary where  Attendance_UserId='$EmployeeId' and Attendance_Month='$month1' and Attendance_Year='$year1' and Attendance_Editedby>0");
$numsave=mysql_num_rows($qrysummarystatus);
 if($numsave==1)
 {
 echo "<font color='red'>Attendance Summary saved for $month1 - $year1... Please Contact Administrator or your Manager</font>";
 }
 else
 {
mysql_query("insert into employee_request(ER_Type,ER_ODType,ER_UserId,ER_EmployeeRemarks,ER_RequestPaidStatus,ER_RequestTimeIn,ER_RequestTimeOut,ER_RequestDate,ER_DoneBy) values ('$Type','$ODType','$EmployeeId','$Remarks','$paidstatus','$reqin','$reqout','$dates','$userid')");

$insertid=mysql_insert_id();
   $DrgName=$_FILES["odslip"]["name"];
if($DrgName)
  {
  $DrgName=explode(".",$DrgName);
  $ext=$DrgName[count($DrgName)-1];
  $DrgName=$insertid."s".$dates."s".$EmployeeId.".$ext";
  $DrgName=filename_safe("$DrgName");
  $DrgType=$_FILES["odslip"]["type"];
  $DrgSize=$_FILES["odslip"]["size"]; 
  $DrgTmp=$_FILES["odslip"]["tmp_name"];
  $newdire="odslips//".$DrgName;
  //echo "new directory name is".$newdire;
  $error=$_FILES["odslip"]["error"];
  move_uploaded_file($DrgTmp,$newdire);
  mysql_query("update employee_request set ER_Files='$DrgName' where ER_Id='$insertid'");
  //mysql_query("update employee_request set ER_SupervisorApproval='Approved',ER_SupervisorRemarks='OD Attached',ER_SupervisorApprovedBy='$userid' where ER_Id='$insertid'");
   }
   
   else
   {
   
   }
}
}

elseif($hidAction="forapproval")
{
$accept=$_REQUEST['accept'];
$paidapproval=$_REQUEST['paidapproval'];
if($accept=="Y")
{
$status="Approved";
}
else
{
$status="Rejected";
$paidapproval="N";
}

$ApprovedTimeIn=$_REQUEST['ApprovedTimeIn'];
$ApprovedTimeOut=$_REQUEST['ApprovedTimeOut'];
$Areqin=$ApprovedTimeIn;
$Areqout=$ApprovedTimeOut;
$ManagerRemarks=$_REQUEST['ManagerRemarks'];
$hidId=$_REQUEST['hidId'];
mysql_query("update employee_request set ER_ManagerRemarks='$ManagerRemarks',ER_ApprovedPaidStatus='$paidapproval',ER_ApprovedTimeIn='$Areqin',ER_ApprovedTimeOut='$Areqout',ER_Status='$status',ER_ApprovedBy='$userid',ER_ApprovalDate=now() where ER_Id='$hidId'");
if($paidapproval=='Y')
{

//echo "month=".$date1."". $month1."".$year1;
mysql_query("update attendance_summary set Attendance_Editedby='',Attendance_SalaryDays='',Attendance_SalaryAbsentDays='',Attendance_SalaryOtMins='',Attendance_SalaryDedMins=''	where Attendance_UserId='$EmployeeId' and Attendance_Month='$month1' and Attendance_Year='$year1'");
//echo "update attendance_summary set Attendance_Editedby='',Attendance_SalaryDays='',Attendance_SalaryAbsentDays='',Attendance_SalaryOtMins='',Attendance_SalaryDedMins=''	where Attendance_UserId='$EmployeeId' and Attendance_Month='$month1' and Attendance_Year='$year1'";
}
//echo "update employee_request set 	ER_ManagerRemarks='$ManagerRemarks',ER_ApprovedPaidStatus='$paidapproval',ER_ApprovedTimeIn='$Areqin',ER_ApprovedTimeOut='$Areqout',ER_Status='$status',ER_ApprovedBy='$userid',ER_ApprovalDate=now() where ER_Id='$hidId'";
}
 if($numsave==1)
 {
    header("location:employeerequest.php");	 
 }
 elseif($userid==31)
 {
    header("location:attendancelist.php");
 }
 else
 {
    header("location:employeerequest.php");
 }
?>