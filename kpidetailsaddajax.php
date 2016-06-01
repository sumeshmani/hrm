<?php
include("connectivity.php");
$groupid=$_REQUEST['groupidajax'];
//echo"groupid=$groupid";
$kpiParentIda=$_REQUEST['kpiParentId'];
//echo"parent id is $kpiParentIda";
$qrygroupajax=mysql_query("select * from kpi_group where kpi_GroupId='$groupid'");
while($objgroupajax=mysql_fetch_object($qrygroupajax))
{
$groupname=$objgroupajax->kpi_GroupName;
$groupversion=$objgroupajax->kpi_GroupVersion;
$MaxScore=$objgroupajax->kpi_MaxScore;
}
//to get the serial number of subgroup
$qryslajax=mysql_query("select * from kpi_details where Kpi_MasterId='$kpiParentIda' and Kpi_SlNo like '$groupversion%'");

$slno=mysql_num_rows($qryslajax);
//to get the group total and verify the same with maximum score value
$qryslajax2=mysql_query("select * from kpi_details where Kpi_MasterId='$kpiParentIda' and Kpi_SlNo like '$groupversion%'");
if(isset($slno))
{
$totscore=0;
while($objajax2=mysql_fetch_object($qryslajax2))
{
$totscore=$totscore+$objajax2->Kpi_TotalScore;
}
}
//assign the serial number 
if(!$slno)
{
$slno=1;
}
else
{
$slno++;
}
$sl=$groupversion.".".$slno;

//echo "<tr><td readonly='readonly'><textarea name='Kpi_Sl' rows='1' cols='1'>$groupversion</textarea></td><td><textarea name='Kpi_Details' rows='3' cols='40'>$groupname</textarea></td>";
//echo "<td><input type='text' name='Kpi_TotalScore' size='1' value='$MaxScore'/></td>";
//echo "<td><input type='submit' value='Add'/></td></tr>";
echo "<table><tr><td><input type='hidden' name='currentscore' value='$totscore'/><input type='hidden' name='groupmaxscore' value='$MaxScore'/><input name='Kpi_Sl' value='$sl' size='2'/></td><td><textarea name='Kpi_Details' rows='3' cols='65' required></textarea></td>";
echo "<td><input type='number' name='Kpi_TotalScore' size='1' required max='50' min='1'/></td>";
echo "<td><input type='submit' value='Add'  onClick='return checkform()'/></td></td></td></tr></table>";
?>