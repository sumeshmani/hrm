<?php
session_start();
$userid=$_SESSION['User_Id'];
include("connectivity.php");
$allowancename=$_REQUEST['allowancename'];
$hidAction=$_REQUEST['hidAction'];
$employee_id=$_REQUEST['hidUserId'];
$allowancefixed=$_REQUEST['allowancefixed'];
$allowanceadd=$_REQUEST['allowance_add'];
$allowanceamount=$_REQUEST['allowanceamount'];
if($allowanceamount=="" or $allowanceamount=="0")
{
	$allowancepercentage=$_REQUEST['allowancepercentage'];
	$variable_dependent=$_REQUEST['variable_dependent'];
	
}
else
{
	$allowancepercentage="";
	$variable_dependent="";
}
	
$allowanceschedule=$_REQUEST['allowanceschedule'];	

$effectivefrom=date("Y-m-01 00:00:00",strtotime($_REQUEST['effectivefrom']));
if($_REQUEST['effectiveto']=='2099-03-31')
{
$effectiveto=$_REQUEST['effectiveto']." 23:59:59";	
}
else
{
$effectiveto=date("Y-m-01 00:00:00",strtotime($_REQUEST['effectiveto']));
}


$lastpaid1=$_REQUEST['lastpaid'];
$lastpaid=date("Y-m-20",strtotime($lastpaid1));
$nextscheduled1=$_REQUEST['nextscheduled'];
$nextscheduled=date("Y-m-20",strtotime($nextscheduled1));
if($hidAction=="new")
{
	mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values ('$employee_id','$allowanceadd','$allowancename','$allowancefixed','$allowanceamount','$allowancepercentage','$variable_dependent','$allowanceschedule','$effectivefrom','$effectiveto','$lastpaid','$nextscheduled','$userid')");
    echo "insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values ('$employee_id','$allowanceadd','$allowancename','$allowancefixed','$allowanceamount','$allowancepercentage','$variable_dependent','$allowanceschedule','$effectivefrom','$effectiveto','$lastpaid','$nextscheduled','$userid')";
}

header("location:payrollmaster.php");


?>