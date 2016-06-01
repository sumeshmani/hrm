<?php
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("ajax.php");
$year1=date("Y");
$groupprofile=1;
//echo "year : $year1";
if(isset($_REQUEST['Kpi_Employee']))
{
$employee_id=$_REQUEST['Kpi_Employee'];
$status="show";
}
else
{
$employee_id=$_SESSION['User_Id'];
$status="new";
}

if(isset($_REQUEST['Kpi_Year']))
{
$years=$_REQUEST['Kpi_Year'];
$year=$_REQUEST['Kpi_Year'];
}
else
{
$years=$year1;
$year="$year1";
}


$qrykpimaster=mysql_query("select * from kpi_master where Kpi_UserId='$employee_id' and Kpi_Year='$year'");
$kpiexists=mysql_num_rows($qrykpimaster);

if($kpiexists)
{

 while($objkpimaster=mysql_fetch_object($qrykpimaster))
  {
   $EmployeeId=$objkpimaster->Kpi_UserId;
   $EmployeeDesignation=$objkpimaster->Kpi_Designation;
   $EmployeeGrade=$objkpimaster->Kpi_Grade;
   $EmployeeDepartment=$objkpimaster->Kpi_Department;
   $EmployeeYear=$objkpimaster->Kpi_Year;
   $EmployeeManager=$objkpimaster->Kpi_ManagerId;
   $EmployeeSupervisor=$objkpimaster->Kpi_SupervisorId;
   $kpi_MasterId=$objkpimaster->Kpi_Id;
   $groupprofile=$objkpimaster->Kpi_Profile;
   $Kpi_Status=$objkpimaster->Kpi_Status;
   
  }
}
else
{
  $qrykpimaster=mysql_query("select * from ncr_user where User_Id='$employee_id'");
  while($objkpimaster=mysql_fetch_object($qrykpimaster))
  {
   $EmployeeId=$objkpimaster->User_Id;
   $EmployeeDesignation=$objkpimaster->User_Designation;
   $EmployeeGrade=$objkpimaster->User_Grade;
   $EmployeeDepartment=$objkpimaster->User_Department;
   $Kpi_Status=0;
   if($EmployeeDepartment=='Production')
   {
   $groupprofile=2;
   }
   else
   {
   $groupprofile=1;
   }
   $EmployeeYear=$years;
   $EmployeeManager=$objkpimaster->User_ManagerId;
   $EmployeeSupervisor=$objkpimaster->User_SupervisorId;
  }
    mysql_query("insert into kpi_master(Kpi_UserId,Kpi_Year,Kpi_SupervisorId,Kpi_ManagerId,Kpi_Grade,Kpi_Designation,Kpi_Department,Kpi_Profile) values('$EmployeeId','$EmployeeYear','$EmployeeSupervisor','$EmployeeManager','$EmployeeGrade','$EmployeeDesignation','$EmployeeDepartment','$groupprofile')");
	$kpi_MasterId=mysql_insert_id();
	$qrygroup=mysql_query("select * from kpi_group where kpi_GroupEditStatus=0 and (kpi_GroupProfile=0 or kpi_GroupProfile='$groupprofile')");
	while($objgroup=mysql_fetch_object($qrygroup))
	 {
	 $GroupId=$objgroup->kpi_GroupId;
	 $MaxScore=$objgroup->kpi_MaxScore;
	 $GroupName=$objgroup->kpi_GroupName;
	 $GroupVersion=$objgroup->kpi_GroupVersion;
	 //echo"id=$kpi_MasterId";
     mysql_query("insert into kpi_details(Kpi_GroupId,Kpi_MasterId,Kpi_SlNo,Kpi_Details,Kpi_TotalScore) values('$GroupId','$kpi_MasterId','$GroupVersion','$GroupName','$MaxScore')");
	 }
 }

?>


<form name="employeeselect" action="addkpidetails.php" method="post">
<table class="tablep" cellpadding="0" cellspacing="0" height="50px">
<tr><td colspan="2">Key Performance Indicator</td></tr>
<tr><td class="row1">Employee Name</td><td class="row1"> 
<select name="Kpi_Employee" id="Kp_Name">
<option value="">Select</option>
<?php
foreach($employee_list as $Id=>$Name)
 {
  if($Id==$EmployeeId)
  {
   echo"<option value=".$Id." selected>".$Name."</option>";
  }
  else
  {
   echo"<option value=".$Id.">".$Name."</option>";
  }
 }
?>
</select>
</td><td class="row1">Year</td><td class="row1">
<select name="Kpi_Year" id="Kp_Year">
<?php
for($i=2015;$i<=$year1+1;$i++)
 {
  if($years==$i)
   {
	echo "<option value=".$i." selected='selected'>".$i."</option>";
   }
  else
   {
	echo "<option value=".$i.">".$i."</option>";
   }
 }
?>
</select> </td><td class="row1"><input type="submit" value="show"/></td>
</tr>
</form>
<tr><td colspan="6">
<form name="personalinfo" action="addkpidetailsaction.php" method="post">

<?php

if(isset($_REQUEST['hidAction']))
{
$hidAction=$_REQUEST['hidAction'];
}
else
{
//$hidAction=$_REQUEST['hidAction'];
}
?>

<table class="tablep" border="1" width="700">
<tr><td colspan="3" align="center" class="rows">A. PERSONAL INFORMATION</td></tr>
<tr><td class="row1" width="50">A.1.0</td><td class="row1" width="280">Name Of the Employee</td><td class="row1" width="310">
<input type="hidden" name="Employee_Id" value="<?php echo $EmployeeId;?>"/>
<input type="hidden" id="Kpi_ParentId" name="Kpi_Id" value="<?php echo $kpi_MasterId;?>"/>
<input type="hidden" name="hidAction" value="<?php echo $hidAction;?>"/> 
<input type="text" name="Employee_Name" value="<?php echo $employee_list[$EmployeeId];?>" readonly="readonly"/></td></tr>
<tr><td class="row1" width="50">A.2.0</td><td class="row1" width="280">Designation</td><td class="row1" width="310"><input type="text" name="Employee_Designation" value='<?php echo"$EmployeeDesignation";?>' readonly="readonly"/></td></tr>
<tr><td class="row1" width="50">A.3.0</td><td class="row1" width="280">Grade</td><td class="row1" width="310"><input type="text" name="Employee_Grade" value='<?php echo"$EmployeeGrade";?>' readonly="readonly"/></td></tr>
<tr><td class="row1" width="50">A.4.0</td><td class="row1" width="280">Department</td><td class="row1" width="310"><input type="text" name="Employee_Department" value='<?php echo"$EmployeeDepartment";?>' readonly="readonly"/></td></tr>
<tr><td class="row1" width="50">A.5.0</td><td class="row1" width="280">Year Of Assesment</td><td class="row1" width="310"><input type="text" name="Employee_Year" value='<?php echo"$year";?>' readonly="readonly"/></td></tr>
<tr><td class="row1" width="50">A.6.0</td><td class="row1" width="280">Immediate Supervisor name</td><td class="row1" width="310">
<input type="hidden" name="Employee_SupervisorId" value='<?php echo $EmployeeSupervisor;?>' readonly="readonly"/>
<input type="text" name="Employee_Supervisor" value='<?php echo $fullemployee_list[$EmployeeSupervisor];?>' readonly="readonly"/></td></tr>
<tr><td class="row1" width="50">A.7.0</td><td class="row1" width="280">Manager Name</td><td class="row1" width="310">
<input type="hidden" name="Employee_ManagerId" value="<?php echo $EmployeeManager; ?>" />
<input type="text" name="Employee_Manager" value="<?php echo $fullemployee_list[$EmployeeManager]; ?>" /></td></tr>
</table>


<table class="tablep" border="1" width="700">



<tr><td class="row">Sl</td><td class='row'>KPI DETAILS</td><td class='row'>Total Score</td></tr>

<?php
if($Kpi_Status=="ManagerApproved")
{

echo"<tr><td colspan='4'>";
}

else
{
// to get main Group names group names such as measurable attributes and non measurable attributes
$qrykpigroup=mysql_query("select * from kpi_group where kpi_GroupParentId=0 and kpi_GroupEditStatus=1 and (kpi_GroupProfile='$groupprofile' or kpi_GroupProfile=0)");
//echo "select * from kpi_group where kpi_GroupParentId=0 and kpi_GroupEditStatus=1 and (kpi_GroupProfile='$groupprofile' or kpi_GroupProfile=0)";

echo "<select id='groupid' onChange='return kpigroupselect()'>";
$q=1;
while($objkpigroup=mysql_fetch_object($qrykpigroup))
{
echo "<optgroup label='$objkpigroup->kpi_GroupVersion: $objkpigroup->kpi_GroupName'>";

$qrysubgroup=mysql_query("select * from kpi_group where kpi_GroupParentId='$objkpigroup->kpi_GroupId' and kpi_GroupEditStatus='1' and (kpi_GroupProfile='$groupprofile' or kpi_GroupProfile=0)");

while($objsubgroup=mysql_fetch_object($qrysubgroup))
{
if($q==1)
{
$g_v=$objsubgroup->kpi_GroupVersion;
$MaxScore=$objsubgroup->kpi_MaxScore;
}
echo"<option value='$objsubgroup->kpi_GroupId'>$objsubgroup->kpi_GroupVersion : $objsubgroup->kpi_GroupName</option>";
$q++;
}
echo "</optgroup>";
}
echo"</select>";
echo "</td></tr>";
?>
<?php

$qrysl=mysql_query("select * from kpi_details where Kpi_MasterId='$kpi_MasterId' and Kpi_SlNo like '$g_v%'");
$slno=mysql_num_rows($qrysl);
$qryslajax2=mysql_query("select * from kpi_details where Kpi_MasterId='$kpi_MasterId' and Kpi_SlNo like '$g_v%'");
if(isset($slno))
{
$totscore=0;
while($objajax2=mysql_fetch_object($qryslajax2))
{
$totscore=$totscore+$objajax2->Kpi_TotalScore;
}
}
if(!$slno)
{
$slno=1;
}
else
{
$slno++;
}
$sl=$g_v.".".$slno;
if(isset($_REQUEST['Kpi_Details']))
{
$Kpi_Details=$_REQUEST['Kpi_Details'];
}
else
{
$Kpi_Details="";
}

if(isset($_REQUEST['Kpi_TotalScore']))
{
$Kpi_TotalScore=$_REQUEST['Kpi_TotalScore'];
}
else
{
$Kpi_TotalScore="";
}

echo "<tr><td readonly='readonly' colspan='4'><div id='kpiadddetails'><table><tr><td>";
echo "<input name='Kpi_Sl' value='$sl' size='2'/>";
echo "<input type='hidden' name='currentscore' value='$totscore'/><input type='hidden' name='groupmaxscore' value='$MaxScore'/></td>";
echo "<td><textarea name='Kpi_Details' rows='3' cols='65' required>$Kpi_Details</textarea></td>";
echo "<td><input type='number' name='Kpi_TotalScore' size='1' value='$Kpi_TotalScore' min='1' max='20' required/></td>";
echo "<td><input type='submit' value='Add' onclick='return checkform()'/></td></td></tr></table></div></tr>";
echo "";
}
?>
<tr><td></td></tr>



<?php
$kpigranttotal=0;
 if($kpiexists)
  {
	$qrykpidetails=mysql_query("select * from kpi_details where Kpi_MasterId='$kpi_MasterId' order by Kpi_SlNo");
	 $kpigranttotal=0;
	 while($objkpidetails=mysql_fetch_object($qrykpidetails))
		{
		  echo"<tr><td>$objkpidetails->Kpi_SlNo</td><td>$objkpidetails->Kpi_Details</td><td>$objkpidetails->Kpi_TotalScore</td>";
          $kpigranttotal=$kpigranttotal+$objkpidetails->Kpi_TotalScore;
		  $slvers=substr($objkpidetails->Kpi_SlNo,"0","3");
		  if($Kpi_Status!="ManagerApproved" and ($slvers=="B.1" or $slvers=="B.2"))
		  {
		  echo "<td><a href='addkpidetails.php?hidAction=edit&$hidKpiId=objkpidetails->Kpi_Id'>E</td><td>D</td>";
		  }
		  echo "</tr>";
		}
   }

if($kpigranttotal=="100")
{
if($Kpi_Status=="ManagerApproved")
{
echo"<tr><td colspan='3' align='center'><a href='confirmkpidetails.php?Kpi_ParentId=$kpi_MasterId&Kpi_Employee=$employee_id&Kpi_Year=$year'>Manager Approved</a></td></tr>";
}
elseif($Kpi_Status="EmployeeSubmitted")
{
echo"<tr><td colspan='3' align='center'><a href='confirmkpidetails.php?Kpi_ParentId=$kpi_MasterId&Kpi_Employee=$employee_id&Kpi_Year=$year'>Manager Approval</a></td></tr>";
}
else
{
echo"<tr><td colspan='3' align='center'><a href='confirmkpidetails.php?Kpi_ParentId=$kpi_MasterId&Kpi_Employee=$employee_id&Kpi_Year=$year'><input type='button' value='confirm'/></a></td></tr>";
}
}
?>

</td>
</tr>
</table>
</form>
<script language="JavaScript">
function checkform()
{
var z=document.personalinfo;
var s=z.currentscore.value*1+z.Kpi_TotalScore.value*1;
var groupscore=z.groupmaxscore.value*1;
//alert ("s="+s);
if(s>groupscore)
{
alert ("Maximum score allowed is"+z.groupmaxscore.value);
return false;
}
}
/*
function calculate()
{
var a=document.personalinfo;
function toggleMe(s){
var e=document.getElementById(s);
if(!e)return true;
if(e.style.display=="none")
{
e.style.display="block"
}
else{
e.style.display="none"
}
return true;
}
*/
</script> 


<?php
include("menubar2.php");
include("bottombar.php");
?>