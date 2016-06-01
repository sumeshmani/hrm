<tr>
<td width="200" valign="top" height="500" bgcolor="#dcdcdc">
<table border="3" bgcolor="#dcdcdc" width="200">
<tr><td class="logged">Logged in by <?php echo"$UserName";  ?></td></tr>
<?php
if($adminstatus=='Y')
{
echo "<tr><td><a href='adduser.php' class='button1'>New Employee</a></td></tr>";
}
?>
<tr><td><a href="userlist.php" class="button1">User Profile</a></td></tr>
<?php
if($adminstatus=='Y')
{
echo "<tr><td><a href='expiryreminder.php' class='button1'>Alerts</a></td></tr>";
}
?>
<tr><td><a href="employeeattendancerequest.php?hidAction=prior" class="button1">OD Slip</a></td></tr>
<tr><td><a href="attendancelist.php" class="button1">Time Sheet</a></td></tr>

<?php
echo "<tr><td><a href='attendancesummary.php' class='button1'>Attendance Summary</a></td></tr>";
echo "<tr><td><a href='attendancerequest.php' class='button1'>Attendance & OT Request </a></td></tr>";
echo "<tr><td><a href='attendancerequestreport.php' class='button1'>Attendance & OT Request report</a></td></tr>";
if($adminstatus=='Y')
{


}

?>
<tr><td><a href="employeerequest.php" class="button1">Employee Request</a></td></tr>
<?php
if($adminstatus=='Y' or $supervisorstatus='Y' or $managerstatus='Y')
{
echo "<tr><td><a href='shiftdetails.php' class='button1'>Shift Details</a></td></tr>";
}
?>

<tr><td><a href='vacationplanner.php' class='button1'>Vacation Datas</a></td></tr>

<?php
if($UserName=="SUMESH M M" or $Department=="Accounts")
{
echo "<tr><td><a href='uploadpayroll.php' class='button1'>Upload Payroll</a></td></tr>";
echo "<tr><td><a href='payrollmaster.php' class='button1'>Payroll Master</a></td></tr>";
echo "<tr><td><a href='payrollcalculation.php' class='button1'>Payroll Generator</a></td></tr>";
}
if($UserName=="SUMESH M M")
{
echo "<tr><td><a href='allowancemaster.php' class='button1'>Allowance Master</a></td></tr>";
echo "<tr><td><a href='uploadattendance.php' class='button1'>Upload Attendance</a></td></tr>";
}
if($adminstatus=='Y')
{
echo "<tr><td><a href='employeesresigned.php' class='button1'>Exit Employees</a></td></tr>";
}

?>
<?php

echo "<tr><td><h2>Entry Forms</h2></td></tr>";
echo "<tr><td><a href='addkpidetails.php' class='button1'>Create KPI</a></td></tr>";
echo "<tr><td><a href='kpievaluation.php' class='button1'>KPI Score</a></td></tr>";


echo "<tr><td><a href='changepassword.php' class='button1'>Change Password </a></td></tr>";
echo "<tr><td><a href='logout.php' class='button1'>Log Out </a></td></tr>";
?>
</table>
</td>

<td valign="top" bgcolor="#999999">