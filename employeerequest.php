<?php
include("topbar.php");
include("connectivity.php");
include("menubar1.php");
include("functions.php");
if(isset($_REQUEST['hidId']))
{
$hidId=$_REQUEST['hidId'];
}
$day=date("d");
//echo "days is".$day;
if($day>28)
{
$today1=date("Y-m-d");
$today=date("Y-m-20",strtotime("$today1 + 30days"));
}
else
{
$today=date("Y-m-20");
}
if(isset($_REQUEST['datefrom']))
{
$datefrom=$_REQUEST['datefrom'];
}
else
{
$datefrom=date("Y-m-21",strtotime("$today-30 days"));
}
if(isset($_REQUEST['dateto']))
{
$dateto=$_REQUEST['dateto'];
}
else
{
$dateto=$today;
}
$criteria="";
if(isset($_REQUEST["requeststatus"]))
{
$status=$_REQUEST["requeststatus"];
if($status=="")
{echo "";}
else
{
$criteria.=" and (ER_Status='$status')";
}
}
else
{
if($adminstatus=='Y')
{
$status="Pending";
$criteria.=" and (ER_Status='$status')";
}
else
{
echo "";
}
}
$searchterm=$_REQUEST['searchterm'];
$datefrom1=$datefrom." 00:00:00";
$dateto1=$dateto." 23:59:59";
if($User_Id=="78")
{
$adminstatus='N';
//echo "malki";
}
$criteria.=" and (ER_RequestTimeIn between '$datefrom1' and '$dateto1')";
 
if($adminstatus=='Y')
{

   if($searchterm!="")
   { 
    echo $searchterm;
	$count=count($employee_list);
    $countreach=1;
    $userids="(";

   foreach($employee_list as $ids=>$values)
   {
  	$pos = strpos(strtoupper($values), strtoupper($searchterm));
	//echo $searchterm."-".$values.$pos."</br>";
	if($pos === false)
	{
		
	}
	else
	{
		if($count==$countreach)
		{
        if($count==1)
		{
		$userids.=" ER_UserId='$ids')";
		}
		else
		{
		$userids.=" (ER_UserId='$ids'))";
		}
        }
		else
		{
		$userids.="(ER_UserId='$ids') or ";
		}
	}
	$countreach++;
	}
	//echo $userids;
	$qryrequest=mysql_query("select * from employee_request where $userids $criteria");
   }  
   else{$qryrequest=mysql_query("select * from employee_request where (ER_UserId>0) $criteria");}

}
else
{
$count=count($employee_list);
$countreach=1;

$userids="(";

foreach($employee_list as $ids=>$values)
{
if(isset($searchterm))
{
	$pos = strpos(strtoupper($values), strtoupper($searchterm));
	//echo $searchterm."-".$values.$pos."</br>";
	if($pos === false)
	{
		
	}
	else
	{
		if($count==$countreach)
		{
        if($count==1)
		{
		$userids.=" ER_UserId='$ids')";
		}
		else
		{
		$userids.=" (ER_UserId='$ids'))";
		}
        }
		else
		{
		$userids.="(ER_UserId='$ids') or ";
		}
	}
}
else{
if($count==$countreach)
{
if($count==1)
{
$userids.=" ER_UserId='$ids')";
}
else
{
$userids.=" (ER_UserId='$ids'))";
}
}
else
{
$userids.="(ER_UserId='$ids') or ";
}
}

$countreach++;
}
$qryrequest=mysql_query("select * from employee_request where $userids $criteria");
//echo "select * from employee_request where $userids $criteria";
}
$statusarray=array("All"=>"","Pending"=>"Pending","Approved"=>"Approved","Rejected"=>"Rejected");
?>
<table class="tableaddp" border="1">
<tr><td colspan="12">Employee Request</td></tr>
<?php
echo "<tr><td colspan=12><form name='attendancelist' action='employeerequest.php' method='post'>From <input type='date' name='datefrom' value='$datefrom'/>&nbsp;To&nbsp;<input type='date' name='dateto' value='$dateto'/>";
echo "Status <select name='requeststatus'>";
foreach($statusarray as $statusid=>$statusname)
{
if($statusname==$status)
{echo "<option value='$statusname' selected>$statusid</option>";}
else
{echo "<option value='$statusname'>$statusid</option>";}
}
echo "</select>";
echo "<input type='text' name='searchterm' value='$searchterm' placeholder='employee name'/>";
echo "<input type='submit' value='search'/></td></tr>";

?>
<tr><td>Sl No</td><td>Employee Name</td><td>Request Type</td><td>TimeIn</td><td>Time Out</td><td>Remarks</td><td>Paid</td><td>Manager Reply</td><td>Manager Remarks</td><td>Admin Reply</td><td>Paid</td><td>Admin Remarks</td></tr>
<?php
$sl=1;
while($obj=mysql_fetch_object($qryrequest))
{
echo "<tr><td>$sl</td>";
$empid=$obj->ER_UserId;
echo "<td>".$fullemployee_list[$obj->ER_UserId]."</td>";
echo "<td>".$obj->ER_ODType."</td>";
echo "<td>".date('d-m-Y H:i:s',strtotime($obj->ER_RequestTimeIn))."</td>";
echo "<td>".date('d-m-Y H:i:s',strtotime($obj->ER_RequestTimeOut))."</td>";
echo "<td>".$obj->ER_EmployeeRemarks."</td>";
echo "<td>".$obj->ER_RequestPaidStatus."</td>";
echo "<td><a href='employeeattendancerequest.php?hidId=$obj->ER_Id&hidAction=$obj->ER_SupervisorApproval'>".$obj->ER_SupervisorApproval."</a></td>";
echo "<td><a href='employeeattendancerequest.php?hidId=$obj->ER_Id&hidAction=$obj->ER_SupervisorApproval'>".$obj->ER_SupervisorRemarks."</a></td>";
echo "<td><a href='employeeattendancerequest.php?hidId=$obj->ER_Id&hidAction=$obj->ER_Status'>".$obj->ER_Status."</a></td>";
if($obj->ER_Status=='Pending' and ($User_Id==$managerof[$empid] or $User_Id==$supervisorof[$empid] or $adminstatus=='Y'))
{
//echo "$obj->ER_Status=='Pending' and ($User_Id==$managerof[$empid] or $User_Id==$supervisorof[$empid] or $adminstatus=='Y'))";
//echo "<td><a href='employeeattendancerequest.php?hidId=$obj->ER_Id&hidAction=forapproval'>Reply</a></td>";
}
echo "<td>".$obj->ER_ApprovedPaidStatus."</td>";
echo "<td>".$obj->ER_ManagerRemarks."by:".$fullemployee_list[$obj->ER_ApprovedBy]."</td>";
if($obj->ER_Status=='Pending' and $obj->ER_DoneBy==$User_Id)
{
echo "<td><a href='deleterequest.php?hidId=$obj->ER_Id'>delete</a></td>";
}
echo "</tr>";

$sl++;
}
?>
</table>
<?php
include("bottombar.php");
?>