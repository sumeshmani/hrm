<?php
include("connectivity.php");
if(isset($_REQUEST['hidAction']))
{
$hidAction=$_REQUEST['hidAction'];
}
else
{
$hidAction='new';
}
if($hidAction=="insertu")
{
}
else
{
include("topbar.php");
include("menubar1.php");
include("functions.php");

}//include("ajax.php");
$types=array(0 => 'Iqama',1 => 'Passport',2 => 'Insurance');
$nationalitytypes=array(0 => 'Saudi', 1=> 'Indian', 2=> 'Pakistan' ,3=> 'Philippines' ,4=> 'Nepal');
$qry=mysql_query("select * from ncr_user where User_Name='$UserName'");
while($obj=mysql_fetch_object($qry))
{
$kpistat=$obj->User_KpiStatus;
$SupervisorStatus=$obj->User_SupervisorStatus;
$ManagerStatus=$obj->User_ManagerStatus;
$AdminStatus=$obj->User_AdminStatus;
}
$qrydesignation=mysql_query("select * from ncr_designationmaster");
//$qrycompany=mysql_query("select * from ncr_addressmaster");
if(isset($_REQUEST['User_ManagerStatus']))
{
$UserManagerStatus=$_REQUEST['User_ManagerStatus'];
}
else
{
$UserManagerStatus='N';
}
if(isset($_REQUEST['User_SupervisorStatus']))
{
$UserSupervisorStatus=$_REQUEST['User_SupervisorStatus'];
}
else
{
$UserSupervisorStatus='N';
}


//$Loginname=$_REQUEST['User_Loginname'];
//$Password=$_REQUEST['User_Password'];
//$UserId=$_REQUEST['hidUserId'];
$qrycode=mysql_query("select * from sftl_number where Number_Type='Employee_Code'");
while($objcode=mysql_fetch_object($qrycode))
{
$Employee_Cod=$objcode->Number_Value;
$Employee_Code="SFT 0".$Employee_Cod;
}

if($hidAction=="insertu")
{
$Name=$_REQUEST['User_Name'];
$Department=$_REQUEST['User_Department'];
$Grade=$_REQUEST['User_Grade'];
$UserManagerId=$_REQUEST['User_ManagerId'];
$UserSupervisorId=$_REQUEST['User_SupervisorId'];
$UserNationality=$_REQUEST['User_Nationality'];
$UserAttendanceCard=$_REQUEST['User_AttendanceCard'];
$UserLoginName=$_REQUEST['User_Name'];
$Designation=$_REQUEST['User_Designation'];
$JoiningDate=$_REQUEST['User_JoiningDate'];

mysql_query("insert into ncr_user (User_Name,User_LoginName,User_Department,User_Designation,User_Grade,User_ManagerStatus,User_SupervisorStatus,User_ManagerId,User_SupervisorId,User_JoiningDate,User_Nationality,User_AttenId,User_EmployeeCode) values('$Name','$Name','$Department','$Designation','$Grade','$UserManagerStatus','$UserSupervisorStatus','$UserManagerId','$UserSupervisorId','$JoiningDate','$UserNationality','$UserAttendanceCard','$Employee_Code')");
$nextcode=$Employee_Cod+1;
mysql_query("update sftl_number set Number_Value='$nextcode' where Number_Type='Employee_Code'");

header("location:userlist.php");
}
elseif($hidAction=='edit')
{
$hidUserId=$_REQUEST['hidUserId'];
$qryedit=mysql_query("select * from ncr_user where User_Id='$hidUserId'");
while($objedit=mysql_fetch_object($qryedit))
{
$Name=$objedit->User_Name;
$Employee_Code=$objedit->User_EmployeeCode;
$Department=$objedit->User_Department;
$Grade=$objedit->User_Grade;
$UserManagerId=$objedit->User_ManagerId;
$UserSupervisorId=$objedit->User_SupervisorId;
$UserNationality=$objedit->User_Nationality;
$UserAttendanceCard=$objedit->User_AttenId;
$UserLoginName=$objedit->User_Name;
$Designation=$objedit->User_Designation;
$JoiningDate=$objedit->User_JoiningDate;
$UserManagerStatus=$objedit->User_ManagerStatus;

$UserSupervisorStatus=$objedit->User_SupervisorStatus;
//$JoiningDate=date("Y-m-d",substr($JoiningDate,0,10));
}
$hidAction="editaction";
}
elseif($hidAction=='editaction')
{
$hidUserId=$_REQUEST['hidUserId'];
$qryedit=mysql_query("select * from ncr_user where User_Id='$hidUserId'");
while($objedit=mysql_fetch_object($qryedit))
{
$JoiningDate=$objedit->User_JoiningDate;
//$JoiningDate=date("Y-m-d",$JoiningDate);
}
$Name=$_REQUEST['User_Name'];
$Department=$_REQUEST['User_Department'];
$Grade=$_REQUEST['User_Grade'];
$UserManagerId=$_REQUEST['User_ManagerId'];
$UserSupervisorId=$_REQUEST['User_SupervisorId'];
$UserNationality=$_REQUEST['User_Nationality'];
$UserAttendanceCard=$_REQUEST['User_AttendanceCard'];
$UserLoginName=$_REQUEST['User_Name'];
$Designation=$_REQUEST['User_Designation'];
//$JoiningDate=$_REQUEST['User_JoiningDate'];
mysql_query("update ncr_user set User_Name='$Name',User_Department='$Department',User_Grade='$Grade',User_ManagerId='$UserManagerId',User_SupervisorId='$UserSupervisorId',User_AttenId='$UserAttendanceCard',User_Nationality='$UserNationality',User_Designation='$Designation' where User_Id='$hidUserId'");
echo "$Name Details updated";
}
elseif($hidAction=='new')
{
$hidAction="insertu";
$Name="";
$Department="";
$Grade="";
$UserAttendanceCard="";
$JoiningDate=date("Y-m-d");
}
?>

<form name="adduser" action="adduser.php" method="post">
<?php
if($SupervisorStatus=='Y' or $ManagerStatus=='Y')
{
//echo"<input type='button' onclick='return toggleMe(\"addemployee\")' value='New Employee'><br>";
}
?>
<div id="addemployee" style="display:display">

<table class="tableaddp" cellpadding="0" cellspacing="0" width="1000" height="50px">
<tr>

	<td class="header" colspan="5" align="center" height="10%">New Employee Registration</td>
</tr>
<tr>
<td>Employee Code</td><td><input type="text" name="Employee_Code" value="<?php echo $Employee_Code; ?>" readonly="readonly"/></td></tr>
</tr>
<tr>
	<td class="row" height="10%">Name</td>
	<td class="row1"><input type="text" name="User_Name" value="<?php echo $Name;?>"/><input type="hidden" name="hidAction" value="<?php echo $hidAction;?>"/><input type="hidden" name="hidUserId" value="<?php echo $hidUserId;?>"/></td>
    
	<td class="row">Department</td><td class="row1"><select name="User_Department">
    <?php
	foreach($dept_list as $Id=>$Name)
	{
	if($Department==$Name)
	{
	 echo "<option value=".$Id." selected>".$Name."</option>";	
	}
	else
	{
	echo "<option value=".$Id.">".$Name."</option>";	
	}
	}
	
		//while($des=mysql_fetch_object($qrydesignation))
	//{
	//$cat=$des->Designation_Id;
	//$Department[$cat]=$des->Designation_Name;
	//echo"<option value='$des->Designation_Id'>$des->Designation_Name</option>";
	//}
	?>
	</select></td>
</tr>
<tr>
	<td class="row" height="10%">Grade</td>
	<td class="row1" ><input type="text" name="User_Grade" size="2" value="<?php echo "$Grade";?>" required/>
<?php
	if($hidAction=="insertu")
	{
	echo "<td>Joining Date</td>";
	 echo"<td class='row1'><input type='date' name='User_JoiningDate' value='$JoiningDate'/></td>"; 

   	}
	else{
	echo "<td>Joining Date</td>";
    	echo"<td class='row1'>$JoiningDate</td>";   
	}
?>
</tr>
<tr>
	<td class="row" height="10%">Nationality</td><td><select name="User_Nationality">
	<?php
	foreach($nationalitytypes as $nationality)
	{
	if($UserNationality==$nationality)
	{
	echo "<option value='$nationality' selected>$nationality</option>";
	}
	else
	{
	echo "<option value='$nationality'>$nationality</option>";
	}
	}
	?></select></td>
	<td>Attendance Card</td>
    <td class="row1"><input type='text' name="User_AttendanceCard" value="<?php echo $UserAttendanceCard;?>"/></td>
</tr>
<tr>
<td class="row" height="10%">Designation</td>
	<td class="row1" ><input type="text" name="User_Designation" value="<?php echo $Designation;?>"/>
<td colspan="2">
<?php
if($UserManagerStatus=='Y')
{
echo "<input type='checkbox' name='User_ManagerStatus' value='Y' checked/>";
}
else
{
echo "<input type='checkbox' name='User_ManagerStatus' value='Y'/>";
}
?>
Manager&nbsp;&nbsp;
<?php
if($UserSupervisorStatus=='Y')
{
echo "<input type='checkbox' name='User_ManagerStatus' value='Y' checked/>";
}
else
{
echo "<input type='checkbox' name='User_ManagerStatus' value='Y'/>";
}
?>
Supevisor</td>
<!--</tr>
<tr>
<td class="row" height="10%">Login Name</td>
	<td class="row1"><input type="text" name="User_Loginname"/></td>
    <td class="row">Password</td>
	<td class="row1"><input type="password" name="User_Password"/></td>
</tr> -->
<tr>
	
	<td class="row">Supervisor</td>
	<td class="row1"><select name="User_SupervisorId">
    <?php
	foreach($supervisor_list as $id=>$name)
	{
	    if($id==$UserSupervisorId)
		{
		echo"<option value=$id selected>$name</option>";
		}
		 elseif($id==$UserManagerId)
		{
		echo"<option value=$id selected>$name</option>";
		}
	    
		elseif($id==1)
		{
				echo"<option value=$id selected>$name</option>";
		}
		else
		{
		echo"<option value=$id>$name</option>";
		}
	}
	?>
    </select>
    </td>
    	<td class="row">Manager</td>
	<td class="row1"><select name="User_ManagerId">
    <?php
	foreach($manager_list as $id=>$name)
	{   if($id==$UserManagerId)
		{
		echo"<option value=$id selected>$name</option>";
		}
		elseif($id==1)
		{
				echo"<option value=$id selected>$name</option>";
		}
		else
		{
		echo"<option value=$id>$name</option>";
		}
	}
	
	?>
    </select>
    </td>
</tr>

<tr> 
<?php

if($hidAction='editaction')
{
echo "<td colspan='5' class='save'><input type='submit' value='Save' onClick='return checkuser()'/></td>";
}
else
{
echo "<td colspan='5' class='save'><input type='submit' value='Save' onClick='return checkuser()'/></td>";
}
?>
</tr> 
</table>

<script language="JavaScript">
function toggleMe(s){
var e=document.getElementById(s);
if(!e)return true;
if(e.style.display=="none"){
e.style.display="block"
}
else{
e.style.display="none"
}
return true;
}
function checkuser()
{
var a=document.adduser;
if(a.User_Name.value=="")
{
alert("Please enter User Name");
a.User_Name.focus();
return false;
}
if(a.User_AttendanceCard.value=="")
{
alert("Please enter Attendance Card Number");
a.User_AttendanceCard.focus();
return false;
}

if(a.User_Grade.value=="")
{
alert("Please enter User Grade");
a.User_Grade.focus();
return false;
}
/*
if(a.User_Password.value=="")
{
alert("Please enter Password");
a.User_Password.focus();
return false;
}*/
}
</script>
<?php
include("menubar2.php");
include("bottombar.php");
?>