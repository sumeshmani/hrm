<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
include("functions.php");
//$files="";
//include("ajax.php");
$hidVacationId=$_REQUEST['hidVacationId'];
?>
<script>

</script>
<?php
//print_r ($employee_ids);
$array="(";
$sl=1;
$count=count($employee_ids);
//echo "count=".$count;
foreach($employee_ids as $empids=>$empids)
{
	$array.=$empids;
	if($sl==$count)
	{$array.=")";}
     else{$array.= ",";}
	$sl++;
}
//echo $array;
$qryvacation=mysql_query("select * from sftl_vacation where Vacation_UserId IN $array");
$Emp_Id=$User_Id;
$year=date("Y");
if($adminstatus=='Y'){
echo "<form name='vacation' action='vacationaction.php' method='post'>";}
echo"<table class='tableaddp' border='1'>";
echo "<tr><td colspan='5'><h1 align='center'>Vacation Planner</h1></td></tr>";
echo "<tr><td bgcolor='00cc00'>Sl</td><td bgcolor='00cc00'>Employee Name </td><td bgcolor='00cc00'>Year </td><td bgcolor='00cc00'>Start Date </td><td bgcolor='00cc00'>End Date </td></tr>";
if($adminstatus=='Y'){
echo "";

echo "<tr><td></td><td><select name='Emp_Id' style='width:200px'>";
foreach($employee_list as $empid=>$empname)
{
 if($empid==$Emp_Id)
  {
   echo "<option value='$empid' selected>$empname</option>";
  }
 else
  {
   echo "<option value='$empid'>$empname</option>";
  }
}
echo "</select></td>";
echo "<td><input type='text' name='Vacation_Year' value='".$year."'/></td>";
echo "<td><input type='date' name='vacation_StartDate' value='".$StartDate."'/></td>";
echo "<td><input type='date' name='vacation_EndDate' value='".$EndDate."'/></td>";
//echo "<tr><td>Remarks</td><td><input type='date' name='vacation_Remarks' value='".$Remarks."'/></td></tr>";
echo "<td><input type='submit' value='Add'/></td></tr>";
//echo "</table>";
echo "</form>";
}
?>
<?php
$sl=1;
while($objvacation=mysql_fetch_object($qryvacation))
{
	
	$Vacation_Id=$objvacation->Vacation_Id;
	$Emp_Id=$objvacation->Vacation_UserId;
	echo"<tr>";
		echo "<td>$sl</td>";
	echo "<td>".strtoupper($fullemployee_list[$Emp_Id])."</td>";
	$year=$objvacation->Vacation_Year;
	echo "<td>$year</td>";
	$VacationRemarks=$objvacation->Vacation_Remarks;
	$StartDate=$objvacation->Vacation_StartDate;
	echo "<td>".substr(date('d-M-Y',strtotime($StartDate)),0,12)."</td>";
	$EndDate=$objvacation->Vacation_EndDate;
	echo "<td>".substr(date('d-M-Y',strtotime($EndDate)),0,12)."</td>";
	//echo "<td></td>";
	if($adminstatus=='Y')
	{
	echo "<td><a href='deletevacationdata.php?hidId=$Vacation_Id'>delete</a></td>";
	}
	echo"</tr>";
	$sl++;
}
echo "</table>";
?>