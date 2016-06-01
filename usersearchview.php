<?php
session_start();
$UserName=$_SESSION['User_Name'];
$User_Id=$_SESSION['User_Id'];
include("connectivity.php");
include("functions.php");
$search=$_REQUEST['user'];
$qry1=mysql_query("select * from ncr_user where User_Name='$UserName'");
while($obj1=mysql_fetch_object($qry1))
{

}
$column="User_Name like '%$search%' or user_LoginName like '%$search%' or User_Department like '%$search%' or User_Designation like '%$search%'";
//echo"$column";
$UserMaster=mysql_query("select * from ncr_user where $column");
echo "<table class='tableaddp' border='1' cellpadding='2' cellspacing='0'>";
echo '<tr><td class="rows">Sl#</td><td class="rows">User Name</td><td class="rows">Grade</td><td class="rows">Designation</td><td class="rows">Department</td><td class="rows">Supervisor</td><td class="rows">Manager</td><td>Iqama</td><td>Passport</td><td>Insurance</td></tr>';

			 
	$n=1;
	while($objuser=mysql_fetch_object($UserMaster))
		{
		$employeeid=$objuser->User_Id;
		$username=$objuser->User_Name;
		$Departmentname=$objuser->User_Department;
		$Designationname=$objuser->User_Designation;
		
		      echo"<tr><td class='rows'>$n</td><td class='rows'>$username</td><td class='rows'>$objuser->User_Grade</td>";
			  //echo"<td class='rows'>$objuser->User_LoginName</td>";
			  echo"<td class='rows'>$objuser->User_Designation</td>";
			  if(isset($fullemployee_list[$objuser->User_SupervisorId]))
			  {
			  echo"<td class='rows'>$Departmentname</td><td class='rows'>".$fullemployee_list[$objuser->User_SupervisorId]."</td>";
			  }
			  else
			  {
			  echo"<td class='rows'>$Departmentname</td><td class='rows'></td>";			
			  }
			  if(isset($fullemployee_list[$objuser->User_ManagerId]))
			  {
			  echo"<td class='rows'>".$fullemployee_list[$objuser->User_ManagerId]."</td>";
			  }
			  else
			  {
			   echo"<td class='rows'></td>";
			  }
			  $qryhrdocument=mysql_query("select * from hr_document where Document_UserId='$employeeid' and Document_Status='Y' and Document_Dependant='Self'");
			  $numdocs=mysql_num_rows($qryhrdocument);
			  if($numdocs==0)
			  {
               echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Iqama'>Upload</a></td>";			   
   			   echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Passport'>Upload</a></td>";
			   echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Insurance'>Upload</a></td>";
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
			   echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Insurance&hidStatus=view'>$number[Insurance]</br> <fontcolor=$color[Insurance]> $expiry[Insurance]</font></a></td>";
			  }
			  else
			  {
			  echo"<td><a href='documentupload.php?employeeid=$employeeid&type=Insurance'>Upload</a></td>";
			  }
			  }
			 // echo"<td width='5%'><a href='adduser.php?hidUserId=$objuser->User_Id&hidAction=edit'>Edit</a></td>";
			 // echo"<td width='5%'><a href='adduser.php?hidUserId=$objuser->User_Id&hidAction=delete'>Delete</a></td>";
			  echo"</tr>";		 
		$n++;
		unset($number);
		unset($color);
		
		}
			 
			
			
	


echo"</table>";
?>