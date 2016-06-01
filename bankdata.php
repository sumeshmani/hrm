<?php
session_start();
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
include("functions.php");
$date=date("Y-m-d");
$userid=$_SESSION['User_Id'];
$employee_id=$_REQUEST['hidUserId'];
$qrypayroll=mysql_query("select * from payroll_bankdetails where payroll_userid='$employee_id' and (effective_from<='$date' and effective_to>='$date')");
$numpayroll=mysql_num_rows($qrypayroll);
if($numpayroll==0)
{
echo "<form name='bankdetails' action='bankdetailsaction.php' method='post'>";
echo "<table class='tableaddp' border='1'>";
echo "<tr><td>Name</td><td>".$employee_list[$employee_id]."<input type='hidden' name='hidUserId' value='".$employee_id."'/></td></tr>";
echo "<tr><td>Name As in Bank</td><td><input type='text' size='40' name='account_name' value='".$employee_list[$employee_id]."'/>";
echo "<input type='hidden' name='hidAction' value='new'/>";
echo "</td></tr>";
echo "<tr><td>Bank</td><td>";
echo "<select name='bankname'>";
echo "<option value='SABB'>SABB</option>";
echo "<option value='NCB'>NCB</option>";
echo "<option value='ALRAJHI'>ALRAJHI</option>";
echo "</select>";
echo "</td></tr>";
echo "<tr><td>Bank Code</td><td><input type='text' name='bank_code' value='".$bank_code."' size='40'/></td></tr>";
echo "<tr><td>Account Number</td><td><input type='text' name='account_No' value='".$account_no."' size='40'/></td></tr>";
echo "<tr><td>IBAN Number</td><td><input type='text' name='iban_No' value='".$iban_no."' size='40'/></td></tr>";
echo "<tr><td colspan='2' align='center'><input type='submit' value='Save'/>&nbsp;&nbsp;<input type='reset' value='cancel'/></td></tr>";
echo "<tr><td colspan='2' align='center'><a href='payrollmaster.php'>Back</a></td></tr>";
echo "</table>";
echo "</form>";
}
else
{echo "<table class='tableaddp' border='1'>";
	while($objbankdetails=mysql_fetch_object($qrypayroll))
	{
		
		echo "<tr><td>Name</td><td>".$employee_list[$employee_id]."<input type='hidden' name='hidUserId' value='".$employee_id."'/></td></tr>";
		echo "<tr><td>Name As in Bank</td><td>".$objbankdetails->payroll_accountname."</td>";
		echo "</tr>";
		echo "<tr><td>Bank</td><td>".$objbankdetails->payroll_bankname;
		echo "</td></tr>";
		echo "<tr><td>Bank</td><td>".$objbankdetails->payroll_bankcode;
		echo "<tr><td>Account Number</td><td>".$objbankdetails->payroll_accountno."</td></tr>";
		echo "<tr><td>IBAN Number</td><td>".$objbankdetails->payroll_iban."</td></tr>";
		echo "<tr><td colspan='2' align='center'><a href='payrollmaster.php'>Back</a></td></tr>";
		
	}
	
}
?>


<?php
include("bottombar.php");
?>