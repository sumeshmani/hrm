<?php
session_start();
$inactive = 3600; // Set timeout period in seconds
$start_time = microtime(true);
if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_destroy();
        header("Location: index.php?login=expired");
    }
}
if(isset($_SESSION['User_Id'])){}else
{
session_destroy();
header("Location: index.php?login=expired");
}
$_SESSION['timeout'] = time();
$Department=$_SESSION['Department'];
$Designation=$_SESSION['Designation'];
$UserName=$_SESSION['User_Name'];
$User_Id=$_SESSION['User_Id'];
include("connectivity.php");
$allowancename=$_REQUEST['allowance_name'];
$allowanceadd=$_REQUEST['allowance_add'];
$allowancestatus=$_REQUEST['allowance_status'];
if($allowancestatus=='fixed')
{
	$allowancevalue=$_REQUEST['allowance_value'];
	$variable_dependant="";
	$allowancepercentage="";
	
}
elseif($allowancestatus=='Variable')
{
	$allowancevalue=$_REQUEST['allowance_value'];
	$variable_dependant=$_REQUEST['variable_dependant'];
	if($allowancevalue=="")
	{
	$allowancepercentage=$_REQUEST['allowance_percentage'];
	}
}
$allowanceduration=$_REQUEST['allowance_duration'];
mysql_query("insert into payroll_allowance (allowance_name,allowance_type,allowance_amount,allowance_percentage,variable_dependent,allowance_schedule,created_by,allowance_add) values ('$allowancename','$allowancestatus','$allowancevalue','$allowancepercentage','$variable_dependant','$allowanceduration','$User_Id','$allowanceadd')");
header("location:allowancemaster.php");
?>