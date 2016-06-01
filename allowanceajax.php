<?php 
$allowancestatus=$_REQUEST['allowancestatus'];
//echo $allowancestatus;
if($allowancestatus=='Fixed')
{
echo "<input type='text' size='50' name='allowance_value' placeholder='Enter Fixed amount'/>";

}
else
{
echo "<input type='text' size='20' name='allowance_value' placeholder='Amount'/>&nbsp;OR &nbsp;";
echo "<input type='text' size='20' name='allowance_percentage' placeholder='Percentage'/></br>";
echo "<select id='allowancestatus' name='variable_dependant'>";
echo "<option value='Attendance'>Attendance</option>";
echo "<option value='Basic'>Basic Salary</option>";
echo "</select>";

}
?>