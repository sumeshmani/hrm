<?php
session_start();
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
$from='2015-04-01';
$to='2099-03-31';
$userid=$_SESSION['User_Id'];
$date=date("Y-m-d");
$employee_id=$_REQUEST['hidUserId'];
include("functions.php");
$qrybasic=mysql_query("select * from payroll_basic where user_id='$employee_id' and status='Y'");
$numbasic=mysql_num_rows($qrybasic);
if($numbasic==0)
{
echo "<form name='basicsalary' action='basicsalaryaction.php' method='post'>";
echo "<table class='tableaddp' border='1'>";
echo "<tr><td>Name</td><td>".$employee_list[$employee_id]."<input type='hidden' name='hidUserId' value='".$employee_id."'/></td></tr>";
echo "<tr><td>Basic Salary</td><td><input type='text' name='basicsalary' value='".$basicsalary."'/>SAR Monthly";
echo "<input type='hidden' name='hidAction' value='new'/>";
echo "</td></tr>";
echo "<tr><td>Effective From</td><td><input type='date' name='effectivefrom' value='".$from."'/></td></tr>";
echo "<tr><td>Effective To</td><td><input type='hidden' name='effectiveto' value='".$to."'/></td></tr>";
echo "<tr><td colspan='2' align='center'><input type='submit' value='Save'/>&nbsp;&nbsp;<input type='reset' value='cancel'/></td></tr>";
echo "<tr><td colspan='2' align='center'><a href='payrollmaster.php'>Back</a></td></tr>";
echo "</table>";
echo "</form>";
}
else
{
echo "<form name='basicsalary' action='basicsalaryaction.php' method='post'>";
echo "<table class='tableaddp' border='1'>";
echo "<tr><td colspan='2' align='center'><h1>Change in Salary</h1></td></tr>";
echo "<tr><td>New Basic Salary</td><td>";
echo "<input type='hidden' name='hidUserId' value='".$employee_id."'/>";
echo "<input type='hidden' name='hidAction' value='add'/>";
echo "<input type='text' name='basicsalary' value='".$basicsalary."'/>SAR Monthly</td></tr>";
echo "<tr><td>Effective From</td><td><input type='date' name='effectivefrom' value='".$from."'/></td></tr>";
echo "<tr><td colspan='2' align='center'><input type='submit' value='Save'/>&nbsp;&nbsp;<input type='reset' value='cancel'/></td></tr>";
echo "<tr><td colspan='2' align='center'><a href='payrollmaster.php'>Back</a></td></tr>";
echo "</table>";
echo "</form>";
 echo "<table class='tableaddp' border='1'>";
 while($objbasic=mysql_fetch_object($qrybasic))
 {
	 echo "<tr><td>".$employee_list[$employee_id]."</td>";
     echo "<td>".$objbasic->basic_amount."</td>";
     echo "<td>".$objbasic->effective_from."</td>";
	 if($objbasic->effective_to=='2099-03-31 00:00:00')
	 {
      echo "<td>--</td>";
	 }
	 else
	 {
	  echo "<td>".$objbasic->effective_to."</td>";
	 }
     //echo "<td colspan='2' align='center'><a href='payrollmaster.php'>Back</a></td>";
	 echo "</tr>";
   
 }
   echo "</table>";
}
?>


<?php

?>
<?php
include("bottombar.php");
?>