<?php
include("connectivity.php");
$ip = $_SERVER['REMOTE_ADDR'];
//echo $ip;
include("topbar.php");
include("menubar1.php");
include("functions.php");
$files="";
$hidAction=$_REQUEST['hidAction'];

?>
<form name="attendancerequest" action="attendancerequestaction.php" method="post" enctype="multipart/form-data">
<table class="tableaddp" name="attendancerequest" >
<tr><td colspan="3" align="center"><h3>ATTENDANCE REQUEST/OT</h3></td></tr>
<tr><td colspan="3" align="center"><font color='red'>***To request for OT for the month and attendance which are missed in previous months****</font></td></tr>

<tr><td>Name</td><td>
<?php

echo "<select name='hidemployeeid'>";
foreach($employee_list as $empid=>$empname)
{
if($empid==$User_Id)
{
echo "<option value='$empid' selected>$empname</option>";
}
else
{
echo "<option value='$empid'>$empname</option>";
}
}
echo "</select>";
$month=date('m');
$year=date('Y');
?>
<tr><td>Request Type</td>
<td><select name='request_type'>
<option value='Attendance Request'>Attendance Request</option>
<option value='OT Request'>OT Request</option>
</select></td>
</tr></tr>
<tr><td>Request Month</td><td>
<select name='month'>
<option value='<?php echo $month;?>'><?php echo $month;?></option>
</select>
<select name='year'>
<option value='<?php echo $year;?>'><?php echo $year;?></option>
</select>
</td>
<tr><td>Request for</td><td><input type="text" name="request_days" size='3'/>
Days <input type="text" name="request_hours" size='3'/> Hours <input type="text" name="request_minutes" size='3'/>minutes </td></tr></tr>
<tr><td>Reason</td><td><textarea rows="5" cols="75" name="Remarks"><?php echo $remarks;?></textarea></td></tr></tr>

<tr><td colspan="3" align="center"> <input type="submit" value="Submit" onclick="return checkform()"/></td></tr>
</table>

</form>

<script language="javascript">
function checkform()
{
var a=document.attendancerequest;


if(Date.parse(outdate) < Date.parse(indate))
{
  alert("In Time Must be greater than start time");
  return false;
}
if(a.Remarks.value=="")
{
  alert("enter reason");
  return false;
}

}

</script>