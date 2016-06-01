<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("ajax.php");
//$months = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
//$mon=date("m");
//$year=date("Y");
//echo "$mon-$year";
?>
<form name="payroll" action="uploadpayrollaction.php" enctype="multipart/form-data" method="post">

<table>


<tr><td colspan="2">Payroll Data</td><td><input type="file" name="Attendance_file" name="attendance" onclick="return checkpassword()"/></td></tr>
<tr><td colspan="2"><input type="submit" name="upload"/></td></tr>
</table>
</form>
<script language="javascript">
function checkpassword()
{
var ans=prompt("what is your name?");
if(ans=='sumesh')
{
return true;
}
else
{
return false;
}

}
</script>