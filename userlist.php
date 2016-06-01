<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("ajax.php");
$types=array(0 => 'Iqama',1 => 'Passport',2 => 'Insurance',);
$dateto=date("Y-M-d");
if(isset($_REQUEST['searchname']))
{
$searchname=$_REQUEST['searchname'];
}
else
{
$searchname="";
}

if(isset($_REQUEST['searchterm']))
{
$searchterm=$_REQUEST['searchterm'];
}
else
{
$searchterm="";
}
$criteria="";
if($searchterm=="")
{
if($searchname)
{
$criteria="and (User_Name like '%$searchname%' or User_Department like '%$searchname%' or User_Nationality like '%$searchname%') ";
}
}
else
{
$criteria="and ($searchterm like '%$searchname%')";
}
if($adminstatus=='Y')
{
//$criteria="where (User_Id>0) and (User_JoiningDate<='$dateto') and ((User_LeavingDate>='$dateto') or (User_LeavingDate='0000-00-00'))";
//echo "where (User_Id>0) and (User_JoiningDate<='$dateto') and ((User_LeavingDate>='$dateto') or (User_LeavingDate='0000-00-00'))";
}
else
{
$criteria="where (User_Id='$User_Id' or User_ManagerId='$User_Id' or User_SupervisorId='$User_Id') and (User_JoiningDate<='$dateto') and ((User_LeavingDate>='$dateto') or (User_LeavingDate='0000-00-00'))" .$criteria;
}
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

if(isset($_REQUEST['hidAction']))
{
$hidAction=$_REQUEST['hidAction'];


$Name=$_REQUEST['User_Name'];
$Department=$_REQUEST['User_Department'];
$Grade=$_REQUEST['User_Grade'];
$UserManagerId=$_REQUEST['User_ManagerId'];
$UserSupervisorId=$_REQUEST['User_SupervisorId'];

$Designation=$_REQUEST['User_Designation'];
$JoiningDate=$_REQUEST['User_JoiningDate'];
$Loginname=$_REQUEST['User_Loginname'];
$Password=$_REQUEST['User_Password'];
$UserId=$_REQUEST['hidUserId'];
}
else{
$hidAction="0";
}
if($hidAction=="insertu")
{
mysql_query("insert into ncr_user (User_Name,User_Loginname,User_Password,User_Department,User_Designation,User_Grade,User_ManagerStatus,User_SupervisorStatus,User_ManagerId,User_SupervisorId,User_JoiningDate) values('$Name','$Loginname',password('$Password'),'$Department','$Designation','$Grade','$UserManagerStatus','$UserSupervisorStatus','$UserManagerId','$UserSupervisorId','$JoiningDate')");
}
elseif($hidAction=="Y"||$hidAction=="N")
{
mysql_query("update ncr_user set User_Status='$hidAction' where User_Id=$UserId");
//echo"(update ncr_user set User_Status=$hidAction where User_Id=$UserId)";
//echo"failed";
}
else
{
//echo"failed";	
}
$userprofile=mysql_query("select * from ncr_user $criteria");

?>



<table class="tableaddp" border="1">
<tr>
	<td colspan="3">
	<?php
	
	echo "<form action='printuser.php' method='post' target='_blank'>";
	if($adminstatus=='Y')
	{
	echo "Search<input type='text' id='searchu' name='searchu' onKeyUp='return getuserserchview()'>";
	}
	echo "</form></td>";
	?>
</tr>
<tr>
	<td colspan="3" width="1000" height="500" valign="top" align="left">
		<div id="usersearch" align="left">
	<div id='scroll_clipper' style='position:absolute; width:1000px; height: 500px; overflow:auto'>
	<table class='tableaddp' border='1' cellpadding='2' cellspacing='0'>
	<tr><td class="rows">Sl#</td><td class="rows">Employee Code</td><td class="rows">User Name</td><td class="rows">Grade</td><td class="rows">Designation</td><td class="rows">Department</td><td class="rows" width='100'>   DOJ   </td><td class="rows" width='100'>   Exit   </td><td>Iqama</td><td>Passport</td></tr>
	<?php 
	$n=1;
	while($objuser=mysql_fetch_object($userprofile))
		{
		$employeeid=$objuser->User_Id;
		$employeecode=$objuser->User_EmployeeCode;
		$username=$objuser->User_Name;
		$Departmentname=$objuser->User_Department;
		$Designationname=$objuser->User_Designation;
		$doj=$objuser->User_JoiningDate;
		$exitdate=$objuser->User_LeavingDate;
		      echo"<tr><td class='rows'>$n</td><td class='rows'>$employeecode</td><td class='rows'>$username</td><td class='rows'>$objuser->User_Grade</td>";
			  //echo"<td class='rows'>$objuser->User_LoginName</td>";
			  echo"<td class='rows'>$objuser->User_Designation</td>";
			  if(isset($fullemployee_list[$objuser->User_SupervisorId]))
			  {
			  echo"<td class='rows'>$Departmentname</td>";
			  //echo "<td class='rows'>".$fullemployee_list[$objuser->User_SupervisorId]."</td>";
			  echo "<td class='rows'>".date("d-M-Y",strtotime($doj))."</td>";
			  if($exitdate<='01-00-0000')
			  {echo "<td>--</td>";}
		  else{
			  	  echo "<td class='rows'>".date('d-M-Y',strtotime($exitdate))."</td>";
				  }
			  }
			  else
			  {
			  echo"<td class='rows'>$Departmentname</td><td class='rows'>".date("d-M-Y",strtotime($doj))."</td>";			
			  }
			  if(isset($fullemployee_list[$objuser->User_ManagerId]))
			  {
			  //echo"<td class='rows'>".$fullemployee_list[$objuser->User_ManagerId]."</td>";
			  }
			  else
			  {
			  // echo"<td class='rows'></td>";
			  }
			  $qryhrdocument=mysql_query("select * from hr_document where Document_UserId='$employeeid' and Document_Status='Y' and Document_Dependant='Self'");
			  $numdocs=mysql_num_rows($qryhrdocument);
			  if($numdocs==0)
			  {
               echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Iqama'>Upload</a></td>";			   
   			   echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Passport'>Upload</a></td>";
			   //echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Insurance'>Upload</a></td>";
			  }
			  else
			  {
			  $d=0;
			  while($objhrdocument=mysql_fetch_object($qryhrdocument))
			  {
			   $type=$objhrdocument->Document_Type;
			   //echo"<td>$type</td>";
			   $number[$type]=$objhrdocument->Document_No;
			   $expiry[$type]=$objhrdocument->Document_ExpireDate_En;
			   if( strtotime($expiry[$type]) > strtotime('+90 day') ) 
			     {
                   $color[$type]='green';
                 }
				 else
				 {
				  $color[$type]='red';
				 }
			   $d++;
			  }
			  //print_r($number);
			  if(array_key_exists('Offer',$number))
			  {
			   //echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Offer&hidStatus=view'>$number[Offer]</br> <font color=$color[Offer]> $expiry[Offer]</font></a></td>";
			  }
			  else
			  {
			//  echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Offer'>Upload</a></td>";
			  }
			  if(array_key_exists('Iqama',$number))
			  {
			   echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Iqama&hidStatus=view'>$number[Iqama]</br><font color=$color[Iqama]> $expiry[Iqama]</font></a></td>";
			  }
			  else
			  {
			  echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Iqama'>Upload</a></td>";
			  }
			  if(array_key_exists('Passport',$number))
			  {
			   echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Passport&hidStatus=view'>$number[Passport]</br> <font color=$color[Passport]> $expiry[Passport]</font></a></td>";
			  }
			  else
			  {
			  echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Passport'>Upload</a></td>";
			  }
			    if(array_key_exists('Insurance',$number))
			  {
			  // echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Insurance&hidStatus=view'>$number[Insurance]</br> <font color=$color[Insurance]> $expiry[Insurance]</font></a></td>";
			  }
			  else
			  {
			  //echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Insurance'>Upload</a></td>";
			  }
			  }
			 // echo"<td width='5%'><a href='adduser.php?hidUserId=$objuser->User_Id&hidAction=edit'>Edit</a></td>";
			 // echo"<td width='5%'><a href='adduser.php?hidUserId=$objuser->User_Id&hidAction=delete'>Delete</a></td>";
			  echo"</tr>";		 
		$n++;
		unset($number);
		unset($color);
		
		}
		
	?>
</table>

</div>
</div>
  </td>
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
if(a.User_Grade.value=="")
{
alert("Please enter User Grade");
a.User_Grade.focus();
return false;
}
if(a.User_Password.value=="")
{
alert("Please enter Password");
a.User_Password.focus();
return false;
}
}
</script>
<?php
//include("menubar2.php");
include("bottombar.php");
?>