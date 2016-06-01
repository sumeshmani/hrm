<?php
session_start();
$userid=$_SESSION['User_Id'];
$date=date("Y-m-d");
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
$from='2015-04-01';
$to='2099-03-31';
$lastpaid=date('Y-m-20',(strtotime($date)-(60*60*24*30)));
$nextscheduled=date('Y-m-20',(strtotime($date)));
include("functions.php");
$employee_id=$_REQUEST['hidUserId'];
$allowancename=$_REQUEST['allowancename'];

$qryallowance=mysql_query("select * from payroll_allowance where user_id='$employee_id' and allowance_name='$allowancename'");
$numallowance=mysql_num_rows($qryallowance);


echo "<form name='allowance' action='allowanceentryaction.php' method='post'>";
echo "<table class='tableaddp' border='1'>";
echo "<tr><td>Name</td><td>".$employee_list[$employee_id]."<input type='hidden' name='hidUserId' value='".$employee_id."'/></td></tr>";
echo "<tr><td>Allowance Name</td><td><input type='text' name='allowancename' value='".$allowancename."' readonly/>";
echo "<input type='hidden' name='hidAction' value='new'/>";
echo "</td></tr>";
echo "<tr><td>Fixed/Variable</td><td>";
echo "<select name='allowancefixed'>";
echo "<option value='fixed'>Fixed</option>";
echo "<option value='variablewithattendance'>Vary with Attendance</option>";
echo "</select>";
echo "</td></tr>";
echo "<tr><td>Allowance/Deducion</td><td>";
echo "<select name='allowance_add'>";
echo "<option value='Add'>Allowance</option>";
echo "<option value='Deduct'>Deductions</option>";
echo "</select>";
echo "</td></tr>";
echo "<tr><td>Amount</td><td><input type='text' name='allowanceamount' value='".$allowanceamount."'/>";
echo "or <input type='text' name='allowancepercentage' value='".$allowancepercentage."'/> %of ";
echo "<select name='variable_dependent'>";
echo "<option value='basicsalary'>basic salary</option>";
echo "</select>";
echo "</td></tr>";
echo "<tr><td>Effective From</td><td><input type='date' name='effectivefrom' value='".$from."'/></td></tr>";
echo "<tr><td>Effective To</td><td><input type='date' name='effectiveto' value='".$to."'/></td></tr>";
echo "<tr><td>Last Paid</td><td><input type='date' name='lastpaid' value='".$lastpaid."'/> <font color='red'>*date not considered, Month & Year Important</font></td></tr>";
echo "<tr><td>Next Scheduled</td><td><input type='date' name='nextscheduled' value='".$nextscheduled."'/> <font color='red'>*date not considered, Month & Year Important</font></td></tr>";
echo "<tr><td>Payment Schedule</td><td>";
echo "<select name='allowanceschedule'>";
echo "<option value='Monthly'>Monthly</option>";
echo "<option value='Yearly'>Yearly</option>";
echo "<option value='2Years'>Once in 2 year</option>";
echo "</select>";
echo "</td></tr>";
echo "<tr><td colspan='2' align='center'><input type='submit' value='Save'/>&nbsp;&nbsp;<input type='reset' value='cancel'/></td></tr>";
echo "<tr><td colspan='2' align='center'><a href='payrollmaster.php'>Back</a></td></tr>";
echo "</table>";
echo "</form>";

echo "<table class='tableaddp' border='1'>";
echo "<tr><td>Name</td><td>Type</td><td>Fixed</td><td>Amount</td><td>Schedule</td><td>Last Paid</td><td>Next Pay</td><td>Pay From</td><td>Pay Upto</td></tr>";
while($objallowance=mysql_fetch_object($qryallowance))
{
	
	echo "<tr><td>".$objallowance->allowance_name."</td>";
	if($objallowance->allowance_add=="add")
	{
	echo "<td>Allowance</td>";	
	}
	else
	{
	echo "<td>Deduction</td>";	
	}
	
	echo "<td>".$objallowance->allowance_fixed."</td>";
	if($objallowance->allowance_amount=="" or $objallowance->allowance_amount=="0")
	{
	echo "<td>".$objallowance->allowance_percentage."";
	echo "".$objallowance->variable_dependent."</td>";
		
	}
	else
	{
	echo "<td>".$objallowance->allowance_amount."</td>";	
	}
	
	echo "<td>".$objallowance->allowance_schedule."</td>";
	
	echo "<td>".date('M-Y',strtotime($objallowance->last_paid))."</td>";
	echo "<td>".date('M-Y',strtotime($objallowance->next_scheduled))."</td>";
	echo "<td>".date('M-Y',strtotime($objallowance->Allowance_EffectiveFrom))."</td>";
	if($objallowance->Allowance_EffectiveTo=='2099-03-31')
	{
	echo "<td>--</td>";
	}
	else
	{
	echo "<td>".date('M-Y',strtotime($objallowance->Allowance_EffectiveTo))."</td>";
	}
	echo "</tr>";
}
echo "</table>";
?>
<?php
include("bottombar.php");
?>