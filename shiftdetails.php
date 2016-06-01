<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
include("functions.php");
$qryshiftmaster1=mysql_query("select * from shift_master where Shift_Status='Y'");
while($objshiftmaster1=mysql_fetch_object($qryshiftmaster1))
{
$shiftmasterid=$objshiftmaster1->Shift_Id;
$shiftmastername1[$shiftmasterid]=$objshiftmaster1->Shift_Name;
$shiftmasterintime1[$shiftmasterid]=$objshiftmaster1Shift_InTime;
$shiftmasterouttime1[$shiftmasterid]=$objshiftmaster1->Shift_OutTime;
}
$hidAction=$_REQUEST['hidAction'];
if($hidAction=='edit')
{
$hidId=$_REQUEST['hidId'];
$qryshiftdata=mysql_query("select * from shift_details where shift_DetailsId='$hidId'");
while($objshiftdata=mysql_fetch_object($qryshiftdata))
{
$employeeeditid=$objshiftdata->Shift_EmployeeId;
$shifteditid=$objshiftdata->Shift_MasterId;
$starteditdate=$objshiftdata->Shift_StartTime;
$endeditdate=$objshiftdata->Shift_EndTime;
}
//echo "date is ".$starteditdate;
}
elseif($hidAction=='delete')
{
$hidId=$_REQUEST['hidId'];	
$qryshiftdata=mysql_query("select * from shift_details where shift_DetailsId='$hidId'");
while($objshiftdata=mysql_fetch_object($qryshiftdata))
{
$employeeeditid=$objshiftdata->Shift_EmployeeId;
$shifteditid=$objshiftdata->Shift_MasterId;
$starteditdate=$objshiftdata->Shift_StartTime;
$endeditdate=$objshiftdata->Shift_EndTime;
}
$qryshiftdatas=mysql_query("select * from shift_details where Shift_EmployeeId='$employeeeditid' orderby Shift_EndTime");
$numqryshiftdatas=mysql_num_rows($qryshiftdatas);
$check=1;
$nums=$numqryshiftdatas-1;
while($objshiftdatas=mysql_fetch_object($qryshiftdatas))
{
	if($nums==$check)
	{
$employeepreviousid=$objshiftdatas->Shift_EmployeeId;
$endeditdate=$objshiftdatas->Shift_EndTime;
	}
	$check++;
}
if($numqryshiftdatas>1)
{
	mysql_query("update shift_details set Shift_EndTime='2100-01-01 23:59:59' where Shift_EmployeeId='$employeepreviousid'");
}
mysql_query("delete from shift_details where shift_DetailsId='$hidId'");

}
else
{
$hidAction="new";
}
$ids=join(",",$employee_ids);
$searchterm="";
$searchterm=$_REQUEST['searchterm'];
$qryshiftdetails1=mysql_query("select * from shift_details where Shift_EmployeeId in ($ids)");

while($objshiftdetails=mysql_fetch_object($qryshiftdetails1))
{
$employeeidadded[]=$objshiftdetails->Shift_EmployeeId;
}
?>
<form name='search' action='shiftdetails.php' method='post'>
<table name='search'>
<tr><td><input type='text' name='searchterm' value='<?php echo $searchterm;?>'/></td><td><input type='submit' Value='search'/></td></tr>
</table>
</form>
<form name='shiftdetails' action="shiftdetailsaction.php" method="post">

<table class="tableaddp" align='left' border="1">

<tr><td>

</td><td bgcolor="#00CC33">
<input type="hidden" name="hidAction" value="<?php echo $hidAction;?>"/>
<input type="hidden" name="hidId" value="<?php echo $hidId;?>"/>
<input type="hidden" name="hidstarteditdate" value="<?php echo $starteditdate;?>"/>
<input type='hidden' name='searchterm' value='<?php echo $searchterm;?>'/>
<?php
//if adding new shift
if($hidAction=='edit')
{
echo $employee_list[$employeeeditid];
}
elseif($hidAction=='delete')
{
	
}
else
{
 echo "<select name='employee_id' style='width:150'>";
  foreach($employee_list as $employee_id=>$employee_name)
  {
   $empshiftadded=in_array($employee_id,$employeeidadded);
   if($empshiftadded>0)
   {}
   else
   {
    echo "<option value='$employee_id'>$employee_name</option>";
   }
  }
 echo "</select>";
}
?>
</td><td bgcolor="#00CC33">
<select name='shift_id' style="width:150"><?php

foreach($shiftmastername1 as $shift_id=>$shift_name)
{
if($shifteditid==$shift_id)
{
echo "<option value='$shift_id' selected>$shift_name - $shiftmasterintime[$shift_id]- $shiftmasterouttime[$shift_id]</option>";
}
else
{
echo "<option value='$shift_id'>$shift_name - $shiftmasterintime[$shift_id]- $shiftmasterouttime[$shift_id]</option>";
}
}
?></select>
</td><td><input type="date" name="startdate" min="<?php echo substr($starteditdate,0,10);?>" value="<?php echo substr($starteditdate,0,10);?>" style="width:150"/></td><td><input type="date" name="enddate"  style="width:150" readonly="readonly"/></td><td><input type="submit" value="add"/></td></tr>
<tr><td>Sl</td><td>User Name</td><td>Shift Time</td><td>Start Date</td><td>End Date</td></tr>
<?php
$sl=1;

$qryshiftdetails=mysql_query("select * from shift_details where Shift_EmployeeId in ($ids) order by Shift_DetailsId desc");
while($objshiftdetails=mysql_fetch_object($qryshiftdetails))
{
echo "<tr>";
echo "<td>$sl</td>";
echo "<td>".$employee_list[$objshiftdetails->Shift_EmployeeId]."</td>";
echo "<td>".$shiftmastername[$objshiftdetails->Shift_MasterId]."</td>";
echo "<td>".$objshiftdetails->Shift_StartTime."</td>";
if($objshiftdetails->Shift_EndTime=="2100-01-01 23:59:59")
{
echo "<td>-----</td>";
echo "<td><a href='shiftdetails.php?hidAction=edit&hidId=$objshiftdetails->Shift_DetailsId&searchterm=$searchterm'>Edit&nbsp;<a href='shiftdetails.php?hidAction=delete&hidId=$objshiftdetails->Shift_DetailsId&searchterm=$searchterm'>delete</td>";

}
else
{
echo "<td>".$objshiftdetails->Shift_EndTime."</td>";
echo "<td><a href='shiftdetails.php?hidAction=edit&hidId=$objshiftdetails->Shift_DetailsId&searchterm=$searchterm'>Edit&nbsp;<a href='shiftdetails.php?hidAction=delete&hidId=$objshiftdetails->Shift_DetailsId&searchterm=$searchterm'>delete</td>";
}
echo"</tr>";
$sl++;
}
?>
</table>
</form>
<?php

include("bottombar.php");
?>