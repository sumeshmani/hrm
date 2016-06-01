<?php
session_start();
$userid=$_SESSION['User_Id'];
include("connectivity.php");
$employee_id=$_REQUEST['hidUserId'];
$hidAction=$_REQUEST['hidAction'];
$basicsalary=$_REQUEST['basicsalary'];
$effectivefrom=$_REQUEST['effectivefrom'];
$effectiveto=$_REQUEST['effectiveto'];
if($hidAction=="new")
{
mysql_query("insert into payroll_basic (user_id,basic_amount,effective_from,effective_to,created_by) values('$employee_id','$basicsalary','$effectivefrom','$effectiveto','$userid')");
}
else
{
	$qrybasic=mysql_query("select * from payroll_basic where user_id='$employee_id' and status='Y'");
    $numbasic=mysql_num_rows($qrybasic);
	 while($objbasic=mysql_fetch_object($qrybasic))
    {
	 
     //echo "<td>".$objbasic->effective_from."</td>";
	 if($objbasic->effective_to=='2099-03-31 00:00:00')
	 {
		 $basic_amount=$objbasic->basic_amount;
		 $basic_id=$objbasic->basic_id;
		 $effective_date=$objbasic->effective_from;
		 if(strtotime($effective_date)<strtotime($effectivefrom))
		 {
			 $effectiveto=date('Y-m-d',strtotime($effectivefrom)-(60*60*24));
			 $newamount=$basicsalary;
			 $diff=$newamount-$basic_amount;
			 mysql_query("update payroll_basic set effective_to='$effectiveto' where basic_id='$basic_id'");
			 $effectiveto='2099-03-31 00:00:00';
			 mysql_query("insert into payroll_basic(user_id,basic_amount,effective_from,effective_to,created_by,increment) values('$employee_id','$basicsalary','$effectivefrom','$effectiveto','$userid','$diff')");
			 //echo  $effectiveto.$newamount.$diff;
			 //mysql_query("");
			 
		 }
		 else
		 {
			 header("location:basicsalary.php?hidUserId=$employee_id");
		 }
         
	 }
	 
     //echo "<td colspan='2' align='center'><a href='payrollmaster.php'>Back</a></td>";
	 
    }
}
//echo "insert into payroll_basic (user_id,basic_amount,effective_from,effective_to,created_by) values('$employee_id','$basicsalary','$effectivefrom','$effectiveto','$userid')";
header("location:payrollmaster.php");
?>