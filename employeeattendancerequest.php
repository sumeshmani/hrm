<?php
include("connectivity.php");
$ip = $_SERVER['REMOTE_ADDR'];
//echo $ip;

include("topbar.php");
include("menubar1.php");
include("functions.php");
$files="";
$hidAction=$_REQUEST['hidAction'];
$qryshiftdetails=mysql_query("select * from shift_details where Shift_EmployeeId='$id'");
if(isset($_REQUEST['hidId']))
{
$hidId=$_REQUEST['hidId'];
$qry=mysql_query("select * from employee_request where ER_Id='$hidId'");
 while($obj=mysql_fetch_object($qry))
 {
  $id=$obj->ER_UserId;
  $type=$obj->ER_Type;
  $odtype=$obj->ER_ODType;
  $dates=$obj->ER_RequestDate;
  $requsttimein=$obj->ER_RequestTimeIn;
  $requsttimeout=$obj->ER_RequestTimeOut;
  $ApprovedTimeIn=$obj->ER_ApprovedTimeIn;
  $ApprovedTimeOut=$obj->ER_ApprovedTimeOut;
  $ApprovedBy=$obj->ER_ApprovedBy;
  $apporvedstatus=$obj->ER_Status;
  $files=$obj->ER_Files;
  $SupervisorRemarks=$obj->ER_SupervisorRemarks;
  $Supervisorapporvedstatus=$obj->ER_SupervisorApproval;
   $SApprovedBy=$obj->ER_SupervisorApprovedBy;
  $status=$obj->ER_Status;
  $requestpaidstatus=$obj->ER_RequestPaidStatus;
  $ApprovedPaidStatus=$obj->ER_ApprovedPaidStatus;
  if($ApprovedBy>0)
  {
  $hidAction="Approved";
  }
  else
  {
  $ApprovedTimeIn=$requsttimein;
  $ApprovedTimeOut=$requsttimeout;
  $ApprovedPaidStatus=$requestpaidstatus;
  }
  $remarks=$obj->ER_EmployeeRemarks;
  $ManagerRemarks=$obj->ER_ManagerRemarks;
 }
}
else
{
if(isset($_REQUEST['UserId'])) {$id=$_REQUEST['UserId'];$dates=$_REQUEST['dates'];}
else {$id=$User_Id;$dates=date("Y-m-d");}
$type="ON DUTY SLIP";
}
//echo $files;
$name=$fullemployee_list[$id];
       $qryshiftdetails=mysql_query("select * from shift_details where Shift_EmployeeId='$id'");
	   $numid=mysql_num_rows($qryshiftdetails);
	   while($objshiftdetails=mysql_fetch_object($qryshiftdetails))
		{
		 $dates1=$dates."00:00:00"; 
		 		 // to get the shift details for selected date
		 if($dates1>=$objshiftdetails->Shift_StartTime and $dates1<=$objshiftdetails->Shift_EndTime)
		  {
		   $shifttimeforin=$shiftmasterintime[$objshiftdetails->Shift_MasterId];
		   $shifttimeforout=$shiftmasterouttime[$objshiftdetails->Shift_MasterId];
		   $shift_duration=((strtotime($shifttimeforout)-strtotime($shifttimeforin))/(60*60));
		    $shifthours=$shift_duration;
		   if($shifthours<0)
		   {
		    $nightshift=1;
			$shifthours=$shifthours*-1;
		   }
		   else
		   {
		   $nightshift=0;
		   }
		   // echo  $nightshift.",";
		  }
		  }
		  	if(isset($shifthours))
		    {
			$shift_in=$dates." ".$shifttimeforin.":00";
            $shift_out=$dates." ".$shifttimeforout.":00";
		    //$shift_out="2015-04-05 05:00:00";
	        if($nightshift==1)
	        {
	        $format="Y-m-d H:i:00";
	        //$day=1;
	         $shift_out = date($format,strtotime("$shift_out + 1 days"));
			 $shifthours=(strtotime($shift_out)-strtotime($shift_in))/(60*60);
	        }	
		}
if($hidAction=="absent")
{
$requsttimein=$shift_in;
$requsttimeout=$shift_out;
if(!$shift_in)
{
$requsttimein=$dates. " 08:30:00";
$requsttimeout=$dates. " 17:30:00";
}
$remarks="";
$requestpaidstatus="Y";
$hidAction="new";
}

elseif($hidAction=="new")
{
$intime=$_REQUEST['intime'];
$outtime=$_REQUEST['outtime'];
$reqin=$dates."".$intime.":00";
$reqout=$dates."".$outtime.":00";
       
		
$shifttimein=$dates." 08:30:00";
$shifttimeout=$dates." 17:30:00";

if(isset($shift_in))
{
$shifttimein=$shift_in;
$shifttimeout=$shift_out;
}
$indiff=strtotime($reqin)-strtotime($shifttimein);
$outdiff=strtotime($shifttimeout)-strtotime($reqout);
echo $shifttimein."-".$reqin;
if($outdiff>$indiff)
{
$requsttimein=$reqout;
$requsttimeout=$shifttimeout;
}
else
{
$requsttimein=$shifttimein;
$requsttimeout=$reqout;
}
$remarks="";
$requestpaidstatus="Y";
$hidAction="new";
}
elseif($hidAction=="Approved")
{

}
elseif($hidAction=="prior")
{
$requsttimeout=$shift_out;
$requsttimein=$shift_in;
if(isset($remarks)){}else{$remarks="";}
$requestpaidstatus="Y";
$qrylock=mysql_query("select MAX(attendance_dateTo) as mindates from attendance_lock where attendance_lockStatus='Y'");
while($objlock=mysql_fetch_object($qrylock))
{
$mindates=substr($objlock->mindates,0,10);
$mindates=date("Y-m-d",strtotime("$mindates+1 days"));
}
//echo "mindates".$mindates;
//$hidAction="Pending";
}
else
{}
?>
<form name="employeerequest" action="employeerequestaction.php" method="post" enctype="multipart/form-data">
<table class="tableaddp" name="employeerequest" >
<tr><td colspan="3" align="center"><h3>ON DUTY SLIP</h3></td></tr>
<tr><td>Name</td><td>
<?php
if($hidAction=="prior")
{
echo "<select name='hidemployeeid'>";
foreach($employee_list as $empid=>$empname)
{
if($empid==$User_Id)
{
echo "<option value='$empid' selected>$empname</option>";
}
else
{
echo "<option value='$empid'>$empname</option>";
}
}
echo "</select>";
}
else
{
echo "<input type='text' name='UserName' value=".$name." readonly='readonly' size='100'/>";
echo "<input type='hidden' name='hidemployeeid' value=".$id."/>";
}
?>
<input type='hidden' name="hidAction" value="<?php echo $hidAction;?>"/>
<input type='hidden' name="hidRequestId" value="<?php echo $hidId;?>"/></td></tr></tr>
<tr><td>Request Type</td>
<td><input type="text" name="requesttype" value="<?php echo"$type"; ?>" readonly="readonly" size='100'/></td>
</tr></tr>
<?php
if($hidAction=="prior")
{
echo "<tr><input type='hidden' name='Dates' value=".$dates." size='100' min='$mindates'/></tr>";
}
else
{
echo "<tr><input type='hidden' name='Dates' value='$dates' readonly='readonly' size='100'/></tr>";
}
?>
<tr><td>Time</td><td><input type="text" name="TimeIn" value="<?php echo $requsttimein;?>" size='25' min='$mindates'/>
To <input type="text" name="TimeOut" value="<?php echo $requsttimeout;?>" size='25' min='$mindates'/></td></tr></tr>

<tr><td>Leave Type</td><td><select name='odtype'>
<?php
$odtypes=array("sickleave"=>"sickleave","Compensation"=>"Compensation","Sitework"=>"Sitework","others"=>"others");
if($odtype=="")
{
	$odtype="others";
}
foreach($odtypes as $odtype1=>$odvalue)
{
		
	if($odtype1==$odtype)
	{
	echo "<option value='$odtype1' selected>$odvalue</option>";
	}
	else
	{
	 echo "<option value='$odtype1'>$odvalue</option>";
	}
}
?>

</select></td></tr></tr>
<tr><td>Reason</td><td><textarea rows="5" cols="75" name="Remarks"><?php echo $remarks;?></textarea></td></tr></tr>
<?php
if($requestpaidstatus=="Y")
{
echo "<tr><td>Status</td><td><input type='radio' name='paid' value='Y' checked='checked'/>With Pay";
echo "<input type='radio' name='paid' value='N'/> Without Pay</td></tr>";
}
else
{
echo "<tr><td>Status</td><td><input type='radio' name='paid' value='Y'/>With Pay";
echo "<input type='radio' name='paid' value='N' checked='checked'/>With out Pay</td></tr>";
}
?>


<?php
if($files)
{
echo "<tr><td><a href='odslips\\$files'>VIEW OD SLIP</a></td></tr>";
}
else
{
echo "<tr><td>Attachments</td><td><input type='file' name='odslip'/></td></tr></tr>";
}
//echo "action".$hidAction;
if($hidAction=="new" or $hidAction=="prior")
{

}
elseif(($User_Id==$id or $User_Id==$managerof[$id] or $User_Id==$supervisorof[$id] or $adminstatus=="Y") and $Supervisorapporvedstatus=="Pending")
{
if($User_Id==$id)
{
echo"<tr><td colspan='5' bgcolor='yellow' color='red'>Supervisor Reply is pending</td><td>";
}
elseif($User_Id==$managerof[$id] or $User_Id==$supervisorof[$id])
{
echo"<tr><td>Supervisor Approval</td><td>";
echo "<input type='radio' name='saccept' value='Y' checked='checked'/>&nbsp;Accept&nbsp;<input type='radio' name='saccept' value='N'/> Reject</td></td></tr>";
echo "<input type='radio' name='saccept' value='Y' checked='checked'/>&nbsp;Accept&nbsp;<input type='radio' name='saccept' value='N'/> Reject</td></td></tr>";
echo "<tr><td>Reason</td><td><textarea rows='5' cols='75' name='SupervisorRemarks'>$SupervisorRemarks</textarea></td></tr></tr>";

echo "<input type='hidden' name='hidAction' value='sapproval'/>";
echo "<tr><input type='hidden' name='hidId' value='$hidId'/></td>";

}
else
{
echo "<tr><td colspan='5' bgcolor='yellow' color='red'>Supervisor Reply is pending</td><td>";
}
}

elseif(($adminstatus=="Y") and ($ApprovedBy==0))
{
 if(($User_Id==$managerof[$id] or $User_Id==$supervisorof[$id]) and ($adminstatus=="N"))
 {
 if($Supervisorapporvedstatus=="Rejected")
  {
  echo "<tr><td colspan='2' bgcolor='Ff0000'>".$Supervisorapporvedstatus." By ".$fullemployee_list[$SApprovedBy]."   : ".$SupervisorRemarks." </tr>";
  }
 else
  {
   	echo "<tr><td colspan='2' bgcolor='00FF00'>".$Supervisorapporvedstatus." By ".$fullemployee_list[$SApprovedBy]."   : ".$SupervisorRemarks." </tr>";
  }	

 }
 else
 { 
 if($Supervisorapporvedstatus=="Rejected")
  {
  echo "<tr><td colspan='2' bgcolor='Ff0000'>".$Supervisorapporvedstatus." By ".$fullemployee_list[$SApprovedBy]." : ".$SupervisorRemarks." </tr>";
  }
  elseif($odtype=="sickleave" and $User_Id!=1)
  {
	  echo "<tr><td colspan='2' bgcolor='00FF00'>Sick leave to be approved by BUH</td></tr>";
  }
 else
  {
   	echo "<tr><td colspan='2' bgcolor='00FF00'>".$Supervisorapporvedstatus." By ".$fullemployee_list[$SApprovedBy]."  : ".$SupervisorRemarks." </tr>";
	echo "<tr><td colspan='2'> Admin Remarks</td></tr>";
	echo "<tr><td>Time</td><td><input type='text' name='ApprovedTimeIn' value='$ApprovedTimeIn' size='25'/> To <input type='text' name='ApprovedTimeOut' value='$ApprovedTimeOut' 		size='25'/></td></tr></tr>
	<tr><td>Reason</td><td><textarea rows='5' cols='75' name='ManagerRemarks'>$ManagerRemarks</textarea></td></tr></tr>";
	echo "<tr><td>Status<input type='hidden' name='hidId' value='$hidId'/></td>";
	echo "<td>";
	 if($ApprovedPaidStatus=="Y")
		{
		echo"<input type='radio' name='paidapproval' value='Y' checked='checked'/>With Pay<input type='radio' name='paidapproval' value='N'/> WithOut Pay</td>";
		}
	else
		{
		echo"<input type='radio' name='paidapproval' value='Y'/>With Pay<input type='radio' name='paidapproval' value='N' checked='checked'/>Without Pay</td>";
		}
	   echo "</td></tr>";
	   echo"<tr><td>Approval Status</td><td>";
	 if($apporvedstatus=="Rejected")
		{
		echo "<input type='radio' name='accept' value='Y'/>&nbsp;Accept&nbsp;<input type='radio' name='accept' value='N' checked='checked'/> Reject</td></td></tr>";
		}
	 else
		{
		echo "<input type='radio' name='accept' value='Y' checked='checked'/>&nbsp;Accept&nbsp;<input type='radio' name='accept' value='N'/> Reject</td></td></tr>";
		}
		}
   }
}
elseif($hidAction=="forapproval" or $hidAction=="Approved")
{
echo "<tr><td colspan='2'> Admin Remarks</td></tr>";
if($id==$User_Id)
{
	
}
else
{
echo "<tr><td colspan='2'> Admin Remarks</td></tr>";
//echo "<tr><td>Date</td><td><input type='text' name='Dates' value=$dates readonly='readonly' size='100'/></td></tr></tr>";
echo "<tr><td>Time</td><td><input type='text' name='ApprovedTimeIn' value='$ApprovedTimeIn' size='25' readonly='readonly'/> To <input type='text' name='ApprovedTimeOut' value='$ApprovedTimeOut' size='25' readonly='readonly'/></td></tr></tr>
<tr><td>Reason</td><td><textarea rows='5' cols='75' name='Remarks' readonly='readonly'>$ManagerRemarks</textarea></td></tr></tr>";
echo "<tr><td>Status</td>";
echo "<td>";
}
if($ApprovedPaidStatus=="Y")
{
//echo"<input type='radio' name='paidapproval' value='Y' checked='checked' readonly='readonly'/>Paid Leave<input type='radio' name='paidapproval' value='N' readonly='readonly'/> Non Paid Leave</td>";
echo "With Pay";
}
else
{
echo "With Out Pay";
//echo"<input type='radio' name='paidapprval' value='Y' readonly='readonly'/>Paid Leave<input type='radio' name='paidapproval' value='N' checked='checked' readonly='readonly'/> Non Paid Leave</td>";
}
echo "<tr><td colspan='2'> Request is ".$status." </td></tr>";
echo "</td></tr>";
//}
}

elseif($id==$User_Id)
{
if($status)
{
echo "<tr><td colspan='2' bgcolor='red'>Admin Reply is ".$status."</td></tr>";
}
}



?>
<tr><td colspan="3" align="center"> <input type="submit" value="Submit" onclick="return checkform()"/></td></tr>
</table>

</form>

<script language="javascript">
function checkform()
{
var a=document.employeerequest;
var indate=a.TimeIn.value;
var outdate=a.TimeOut.value;
var value = "22.05.2013 11:23:22";
// capture all the parts
//alert(indate+"&"+outdate);
if(Date.parse(outdate) < Date.parse(indate))
{
  alert("In Time Must be greater than start time");
  return false;
}
if(a.Remarks.value=="")
{
  alert("enter reason");
  return false;
}
//if(a.odtype.value=="sickleave")
//{
 //if(a.odslip.value=="")
  //{
    //alert("Please attach medical certificate");
    //return false;
  //}
//}
if(a.paid.value=="Y")
{
 //if(a.odslip.value=="")
  //{
    //alert("Please attach your OD slip");
    //return false;
  //}
}

//return false;
}

</script>