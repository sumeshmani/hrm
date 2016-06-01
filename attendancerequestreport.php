<?php
session_start();
$userid=$_SESSION['User_Id'];
include("topbar.php");
include("connectivity.php");
include("menubar1.php");
include("functions.php");
$month=date('m');
$year=date('Y');
$count=count($employee_list);
$countreach=1;
$userids.="(";
foreach($employee_list as $ids=>$values)
   {
  	
		if($count==$countreach)
		{
        if($count==1)
		{
		$userids.=" user_id='$ids')";
		}
		else
		{
		$userids.=" (user_id='$ids'))";
		}
        }
		else
		{
		$userids.="(user_id='$ids') or ";
		}
	
	$countreach++;
	}
$qry=mysql_query("select * from attendance_request where $userids and (month='$month' and year='$year')");
//echo "select * from attendance_request where $userids and (month='$month' and $year='$year')";
?>
<table class='tableaddp' border='1'>
<tr><td>Sl No</td><td>Month</td><td>Employee Name</td><td>Request Type</td><td>Attendance For</td><td>Employee Reply</td><td>Admin Reply</td></tr>
<?php
$sl=1;
while($obj=mysql_fetch_object($qry))
{
echo "<tr>"; 
echo "<td>".$sl."</td>";	
echo "<td>".$month."-".$year."</td>";	
//echo "<td>".$obj->user_id."</td>";	
echo "<td>".$fullemployee_list[$obj->user_id]."</td>";	
echo "<td>".$obj->request_type."</td>";	
echo "<td>".$obj->request_days." Days</br>".$obj->request_hours." Hours</br>".$obj->request_minutes ." Minutes</td>";	
echo "<td>".$obj->request_remarks."</td>";
$qryuser_r=mysql_query("select User_ManagerId,User_SupervisorId from ncr_user where User_Id='$obj->user_id'");
while($objuser_r=mysql_fetch_object($qryuser_r))
{
	$man_id_of_employee=$objuser_r->User_ManagerId;
	$super_id_of_employee=$objuser_r->User_SupervisorId;
}
/*if($obj->approved_by1>0)
{
//	echo "<td>".$obj->approved_status1."</td>";
}	
elseif($userid==$man_id_of_employee or $userid==$super_id_of_employee)
{	
//echo "<td><a href='requestaction.php?hidrequestid=$obj->request_id&hidaction=s&histatus=Y'>Accept</a></br></br><a href='requestaction.php?hidrequestid=$obj->request_id&hidaction=s&histatus=N'>Reject</a></td>";	
}
else{//echo "<td>".$obj->approved_status1."</td>";
}
*/
if($obj->approved_by2>0)
{
echo "<td>".$obj->approved_status2."</td>";
}
elseif($adminstatus=='Y')
{
echo "<td><a href='requestaction.php?hidrequestid=$obj->request_id&hidaction=a&histatus=Y'>Accept</a></br></br><a href='requestaction.php?hidrequestid=$obj->request_id&hidaction=a&histatus=N'>Reject</a></td>";		
}
else{echo "<td>".$obj->approved_status2."</td>";}

echo "</tr>";	
$sl++;
}
?>
</table>