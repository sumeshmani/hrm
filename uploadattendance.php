<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("ajax.php");
$months = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
$mon=date("m");
$year=date("Y");
//echo "$mon-$year";
?>
<form name="attenadance" action="attendancecalc.php" enctype="multipart/form-data" method="post">

<table>
<tr><td>Month&nbsp; <select name='month'>
<?php
foreach($months as $id=>$month)
{
if($id==$mon)
{
echo "<option value='$id' selected>$month</option>";
}
else
{
echo "<option value='$id'>$month</option>";
}
}
?></select>
</td><td> Year &nbsp;<select name="year">
<?php
for($y=2014;$y<=$year;$y++)
{
if($y==$year)
{
echo "<option value='$y' selected>$y</option>";
}
else
{
echo "<option value='$y'>$y</option>";
}
}
?></select></td>
</tr>
<tr><td colspan="2">Attendance File</td><td><input type="file" name="Attendance_file" name="attendance" onclick="return checkpassword()"/></td></tr>
<tr><td colspan="2"><input type="submit" name="upload"/></td></tr>
</table>
</form>
<script language="javascript">
function checkpassword()
{
var ans=prompt("what is the password");
if(ans=='sft123')
{
return true;
}
else
{
return false;
}

}
</script>