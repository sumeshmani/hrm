<?php
session_start();
$userid=$_SESSION['User_Id'];
include("connectivity.php");
$employee_id=$_REQUEST['hidUserId'];
$accountname=$_REQUEST['account_name'];
$bankname=$_REQUEST['bankname'];
$bankcode=$_REQUEST['bank_code'];
$account_no=$_REQUEST['account_No'];
$iban_no=$_REQUEST['iban_No'];
mysql_query("insert into payroll_bankdetails(payroll_userid,payroll_bankname,payroll_bankcode,payroll_accountno,payroll_iban,payroll_accountname,created_by) values('$employee_id','$bankname','$bankcode','$account_no','$iban_no','$accountname','$userid')");
header("location:payrollmaster.php");
?>