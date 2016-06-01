<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
$today=date("Y-m-d");
if($_REQUEST['Employee_Id'])
{
 $Employee_Id=$_REQUEST['Employee_Id'];
 $User_LeavingDate=$_REQUEST['User_LeavingDate'];
 mysql_query("update ncr_user set User_LeavingDate='$User_LeavingDate' where User_Id='$Employee_Id'");
}
$qryresigned=mysql_query("select * from ncr_user where User_LeavingDate > '2000/01/01'");
$qryemp=mysql_query("select * from ncr_user where User_LeavingDate < '2000/01/01'");
?>


<form name='emplyeeresign' action='employeesresigned.php' method='post'>
<table class='tableaddp'>
<tr><td colspan='2'></td></tr>
<tr><td>Name</td><td>Last Working Day</td><td rowspan='2'><input type='submit' value='Save'/></td></tr>
<tr><td><select name='Employee_Id'>
<?php
while($objemp=mysql_fetch_object($qryemp))
{
	echo "<option value='".$objemp->User_Id."'>$objemp->User_Name</option>";
}
?>
</select></td>
<td><input type='date' name='User_LeavingDate' value='<?php echo $today; ?>'/></td></tr>
</table>
</form>
<?php
echo "<table class='tableaddp' border='1'>";
echo "<tr><td>Name</td><td>Last Working Day</td></tr>";
while($objresigned=mysql_fetch_object($qryresigned))
{
	echo "<tr><td>$objresigned->User_Name</td><td>$objresigned->User_LeavingDate</td></tr>";
}
echo "</table>";
?>
<?php
include("bottombar.php");
?>