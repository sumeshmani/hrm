<?php
ini_set('max_execution_time', 300);
include("topbar.php");
include("connectivity.php");
include("menubar1.php");
include("functions.php");

?>
<script language='javascript'>
function validateInput(id,userid)
{  
  // var id;
   //var userid;
  var ad=document.getElementById('Present_ManDays_'+id);
  var rem=document.getElementById('Remarks_'+id);
  var dm=document.getElementById('Deduction_Minutes_'+id);
  var dh=document.getElementById('Deduction_Hours_'+id);
  
	if(userid!=1)
	{
		if(ad.value>0)
		{
			if(rem.value=="")
			{
				alert("Please enter the remarks");
				return false;
			}
			alert("Additional days of "+ad.value+" has to be approved by BUH");
			
		}
		if(dm.value<0)
		{
			if(rem.value=="")
			{
				alert("Please enter the remarks");
				return false;
			}
			alert("Deduction adjustment of "+dm.value+" has to be approved by BUH");
			
		}
		if(dh.value<0)
		{
			if(rem.value=="")
			{
				alert("Please enter the remarks");
				return false;
			}
			alert("Deduction adjustment of "+dh.value+" has to be approved by BUH");
			
		}
		
	}
	return true;
	
}
</script>

<?php
$summary=1;
$nodisplay=0;
$od=1;
$completed='Y';
$excessdur=0;
//get dates between start dates and end dates
function getDateRange($startDate, $endDate, $format="Y-m-d")
{
    //Create output variable
    $datesArray = array();
    //Calculate number of days in the range
    $total_days = round(abs(strtotime($endDate) - strtotime($startDate)) / 86400, 0) + 1;
    if($total_days<0) { return false; }
	//$total_days--;
    //Populate array of weekdays and counts
//	$total_days=$total_days-1;
    for($day=0; $day<$total_days; $day++)
    {
        $datesArray[] = date($format, strtotime("{$startDate} + {$day} days"));
    }
    //Return results array
    return $datesArray;
	 
}

if(isset($_REQUEST['year']))
{
$yearselected=$_REQUEST['year'];
}
else
{
$yearselected=date("Y");
$monthselected=date("m");
}
if(isset($_REQUEST['month']))
{
$monthselected=$_REQUEST['month'];
$today=date($yearselected."-".$monthselected."-20");
$datefrom=date("Y-m-21",strtotime("$today-30 days"));
$dateto=$today;
$num_days_prev_month=cal_days_in_month(CAL_GREGORIAN,date("m",strtotime("$datefrom")),date("Y",strtotime("$datefrom")));
$num_days_current_month=cal_days_in_month(CAL_GREGORIAN,$monthselected,$yearselected);
$date_from_vac_calc=date("Y-m-21",strtotime("$today-30 days"));
$date_to_vac_calc=date("Y-m-".$num_days_current_month."",strtotime("$today"));
$current_month_starting=date("Y-m-01",strtotime("$today"));
$pre_month_ending=$datefrom=date("Y-m-".$num_days_prev_month."",strtotime("$datefrom"));
$datefrom=date("Y-m-21",strtotime("$today-30 days"));
}
else
{
$today=date("Y-m-20");
$datefrom=date("Y-m-21",strtotime("$today-30 days"));
$dateto=$today;
$num_days_prev_month=cal_days_in_month(CAL_GREGORIAN,date("m",strtotime("$datefrom")),date("Y",strtotime("$datefrom")));
$num_days_current_month=cal_days_in_month(CAL_GREGORIAN,$monthselected,$yearselected);
$date_from_vac_calc=date("Y-m-21",strtotime("$today-30 days"));
$date_to_vac_calc=date("Y-m-".$num_days_current_month."",strtotime("$today"));
$current_month_starting=date("Y-m-01",strtotime("$today"));
$pre_month_ending=$datefrom=date("Y-m-".$num_days_prev_month."",strtotime("$datefrom"));
$datefrom=date("Y-m-21",strtotime("$today-30 days"));
}
echo"date from=".$datefrom."dateto=".$dateto;
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
$criteria="where (User_Id>0) ".$criteria;
}

else
{
$criteria="where (User_Id='$User_Id' or User_ManagerId='$User_Id' or User_SupervisorId='$User_Id')" .$criteria;
}
$col=((strtotime($dateto)-strtotime($datefrom))/86400);
$col2=$col+6;
$dateRange = getDateRange($datefrom, $dateto);
$qryuserprofile=mysql_query("select * from ncr_user $criteria and (User_JoiningDate<='$dateto') and ((User_LeavingDate>='$datefrom') or(User_LeavingDate='0000-00-00'))  order by User_JoiningDate");
$e=0;
while($objuserprofile=mysql_fetch_object($qryuserprofile))
{
$userids[$e]=$objuserprofile->User_Id;
$employeecode[$objuserprofile->User_Id]=$objuserprofile->User_EmployeeCode;
$attendacecard[$objuserprofile->User_Id]=$objuserprofile->User_AttenId;
$User_Name[$objuserprofile->User_Id]=$objuserprofile->User_Name;
$doj[$objuserprofile->User_Id]=$objuserprofile->User_JoiningDate;
//$grade[$objuserprofile->User_Id]=$objuserprofile->User_Grade;
 //echo "name-".$name[$objuserprofile->User_Id];
$dept[$objuserprofile->User_Id]=$objuserprofile->User_Department;
$nationality[$objuserprofile->User_Id]=$objuserprofile->User_Nationality;
$workdur[$objuserprofile->User_Id]=$objuserprofile->User_WorkHours;
$e++;
}
$months=array("01"=>"Jan","02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"May","06"=>"Jun","07"=>"Jul","08"=>"Aug","09"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dec");
$yearss=array("2016"=>"2016","2015"=>"2015");
echo "<table class='tableaddp' border='1' cellspacing='0' cellpadding='0'>";
echo "<tr><td colspan=$col2><form name='attendancelist' action='attendancesummary.php' method='post'><table class='tableaddp'><tr><td>Month <select name='month'>";
foreach($months as $month_id=>$month_name)
{
if($monthselected==$month_id)
{
echo "<option value='$month_id' selected>$month_name</option>";
}
else
{
echo "<option value='$month_id'>$month_name</option>";
}
}
echo "</select> <input type='hidden' name='datefrom' value='$datefrom'/>&nbsp;Year&nbsp;";

echo "<select name='year'>";
foreach($yearss as $year_id=>$year_name)
{
if($yearselected==$year_id)
{
echo "<option value=".$yearselected." selected>$yearselected</option>";
}
else
{
echo "<option value=".$year_id.">$year_id</option>";
}
}
echo "</select>";
echo "<select name='searchterm'>";
echo "<option value=''>All</option>";
echo "<option value='User_Name'>Employee</option>";
echo "<option value='User_Grade'>Grade</option>";
echo "<option value='User_Department'>Department</option>";
echo "<option value='User_Nationality'>Nationality</option>";
echo "</select>";
echo "<input type='text' name='searchname' value='".$searchname."'/>";
echo "<input type='submit' value='search'/>";
echo"</td></tr></table></form></td></tr>";

echo "<tr><td rowspan='2'>Emp Code</td><td rowspan='2'>Name</td><td rowspan='2'>Shift(Hours)</td>";
//echo "<td>Grade</td><td>Department</td><td>Nationality</td>";
foreach($dateRange as $dates)
{
//echo "<td>$dates</td>";
}
if($User_Id=='1')
{
//$manual_entry_accesss="$User_Id=='1' or $User_Id=='6' or $User_Id=='13' or $User_Id=='19' or $User_Id=='41' or $User_Id=='78' or $User_Id=='36'";
$manual_entry_accesss="$User_Id=='1'";
}
else
{
	$manual_entry_accesss="";
}
echo "<td colspan='4' bgcolor='lightgreen'> Machine Data & OD Slip Data</td>";
if($manual_entry_accesss)
{
echo "<td colspan='6' bgcolor='yellow'> Admin Data(OD Slip & other extra work details)</td>";
}
echo "<td colspan='4'>Data for Salary</td></tr>";
echo "<tr><td width='25'>Pr</td>";
//echo "<td>Fr</td><td>Tot</td>";
echo "<td>Absent</td><td>Shortage</td><td>OT</td>";
echo "<td>absent on</td>";
if($manual_entry_accesss)
{
  echo "<td>Present</td><td>Ded_Hrs</td><td>Ded_Mins</td><td>Remarks</td>";
  echo "<td>Save</td>";  
}
elseif($adminstatus=='Y')
{
	echo "<td>Save</td>"; 
}
else{}
echo "<td>P</td><td>A </td><td>Ded </td><td>OT </td>";
echo "</tr>";
//echo "<form name='cardupdate' action='cardupdateaction.php' method='post'>";
$sl=1;
foreach($userids as $id)
{
$totshortdur=0;
$totshorthrs=0;
$totshortmins=0;
$totsalded=0;
$shortdur=0;
$absdays="";
$presntdays="";
$concession_upto_60minutes=0;
$vacation_start_date="";
$vacation_end_date="";
unset($shortday_day_wise);
$shortday_day_wise[]="";
//unset($grades);
//echo $User_Name[$id]."-".$id."</br>";
//To update attednace card no if it is not available with ncr_user table
	if($attendacecard[$id]=="")
		{
		echo "<tr><td>".$employeecode[$id]."</td><td>".$User_Name[$id]."</td><td>".$grade[$id]."</td><td>".$dept[$id]."</td><td>".$nationality[$id]."</td><td colspan='5'> attendance card number not yet updated <a href='adduser.php?hidUserId=$id&hidAction=edit'>Update Now</a></td></tr>";
		//echo "<tr><td>$name[$id]</td><td>$dept[$id]</td><td><input type='text' name='Attendance_Card' placeholder='Update Attendance Card No'/></td><td><input type='button' value='update'/></td></tr></form>";
		$sl++;
		}
// calculate attendance from attendance table for all employees as per filtering    
	else
	{
		$s=0;
		$totshortdur=0;
        $totshorthrs=0;
        $totshortmins=0;
        $totsalded=0;
        $shortdur=0;
	

//$names=array_unique($name);
//Initiate the values of day count , deduction hours, working hours
			//$sl=1;
			$fri[$id]=0;
			$work_hrs_fri=0;
			$work_hrs=0;
			$work_hrs_total=0;
			$short_hrs=0;
			$absent=0;
			
			echo "<td><font color='green'>".$employeecode[$id]."</font></td>";
			echo "<td>".$User_Name[$id]."</td>";
			
			//echo "<td>".$grade[$id]."</td>";
			//echo "<td>".$dept[$id]."</td>";
			//echo "<td>".$nationality[$id]."</td>";
//		echo "<td>".$grade[$id]."</td>";
			$v=0;
			$c=0;
			unset($presentday);
  			$f=0;
			/*
			Attendance data calculation during vacation
			*/
			$qryvac=mysql_query("select * from sftl_vacation where (Vacation_UserId='$id') and ((Vacation_StartDate between '$date_from_vac_calc' and '$date_to_vac_calc') or (Vacation_EndDate between '$date_from_vac_calc' and '$date_to_vac_calc'))");
			//echo "select * from sftl_vacation where (Vacation_UserId='$id') and ((Vacation_StartDate between '$date_from_vac_calc' and '$date_to_vac_calc') or (Vacation_EndDate between '$date_from_vac_calc' and '$date_to_vac_calc'))";
			$numvac=mysql_num_rows($qryvac);
			if($numvac==1)
			{
			//echo "numvac=".$numvac;
			while($objvac=mysql_fetch_object($qryvac))
			 {
			  $vacation_time=1;
			  $vacation_start_date=$objvac->Vacation_StartDate;
			  $vacation_end_date=$objvac->Vacation_EndDate;
			 }
			}else{$vacation_time=0;}
			
			    foreach($dateRange as $dates)
					{
					$starttime= $dates." 00:00:00";
					$endtime= $dates." 23:59:59" ;
		//Shift data processing start here $dates & $id is the current date and Employee id 
		 $qryshiftdetails=mysql_query("select * from shift_details where Shift_EmployeeId='$id'");
		$numshiftdetails=mysql_num_rows($qryshiftdetails);
		$shift_outside='N';
		if($numshiftdetails==0 or $dept[$id]=='Security')
		{
		  $shift_duration=8;
		  $shift_flexibility="yes";
		}
		else
		{
			$shift_flexibility="no";
		}
		while($objshiftdetails=mysql_fetch_object($qryshiftdetails))
		{
			$dates1=$dates . "00:00:00";
			//$shift_flexibility="no";
		 // to get the shift details for current period
		 if($dates1>=$objshiftdetails->Shift_StartTime and $dates1<=$objshiftdetails->Shift_EndTime)
		  {
		   $shifttimeforin=$shiftmasterintime[$objshiftdetails->Shift_MasterId];
		   $shifttimeforout=$shiftmasterouttime[$objshiftdetails->Shift_MasterId];
		   $shift_in=$dates." ".$shifttimeforin.":00";
		   $shift_out=$dates." ".$shifttimeforout.":00";
		   $shiftOffdays=$shiftOffDays[$objshiftdetails->Shift_MasterId];
		   //echo $shift_in.":".$shift_out;
		   $shift_duration=((strtotime($shifttimeforout)-strtotime($shifttimeforin))/(60*60));
		   $shifthours=$shift_duration;
		   if($shifthours<0)
		   {
		    $nightshift=1;
		   }
		   else
		   {
		   $nightshift=0;
		   }
		    if($shiftmastername[$objshiftdetails->Shift_MasterId]=="Outside Office")
		   {
			   $shift_outside='Y';
		   }
		    
		  }
		  	  
		} 
		$weekday=date('w',strtotime($dates));
		$holiday=0;
		$numoffdays=0;
		if($shift_duration=="0" or $shift_duration=="")
		  {
			 $shift_duration=8;
 			 $shift_flexibility="yes";
		  }
		//echo"shiftoffdays =".$shiftOffdays;
		if(isset($shiftOffdays))
		{ 
	    	if(is_numeric($shiftOffdays))
			{
			 if($shiftOffdays==$weekday)
				 {$holiday=1;}	
			}
			else
			{
			 $shiftOffdaysarray=explode(",",$shiftOffdays);	
			 $numoffdays=count($shiftOffdaysarray);
			 foreach($shiftOffdaysarray as $weekoff)
			 {
				 if($weekoff==$weekday)
				 {$holiday=1;}
			 }			 
			}
		}
		else
		{
		  if($weekday=="5")
		  {$holiday=1;}
	  		
		}
		$dates1=$dates."";
		$vacation=0;
		$qryholiday=mysql_query("select * from sftl_holidays where Holiday_Starts<='$dates1' and Holiday_Ends>='$dates1'");
		$numholiday=mysql_num_rows($qryholiday);
		if($numholiday)
		{
			$holiday=1;
			$vacation=1;
			while($objholiday=mysql_fetch_object($qryholiday))
			{
				 $starttimeh=date("Y-m-d",strtotime("$objholiday->Holiday_Starts -1 days"));
				 $endtimeh=date("Y-m-d",strtotime("$objholiday->Holiday_Ends +1 days"));
				//echo "End time".$starttimeh.;
				//echo date("d",strtotime($dates1));
			}
			
	
	}
		//if shift data not entered it will be calculated from user table 
     	if(isset($shifthours))
		{}
		else
		{
		$shifthours=$workdur[$id];
		}  
			$shift_in=$dates." ".$shifttimeforin.":00";
            $shift_out=$dates." ".$shifttimeforout.":00";
		//$shift_out="2015-04-05 05:00:00";
	   if($nightshift==1)
	   {
	   $format="Y-m-d h:i:00";
	   //$day=1;
	   $shift_out = date($format, strtotime("$shift_out + 1 days"));
	   $shifthours=((strtotime($shift_out)-strtotime($shift_in))/(60*60));
	   }
		////Shift data processing ends here
		
					$qryat=mysql_query("select * from sftl_attendance where AttendanceCardNo='$attendacecard[$id]' and Attendance_McInTime BETWEEN '$starttime' and '$endtime' limit 0,1");
					$num=mysql_num_rows($qryat);
					$weekday=date('w',strtotime($dates));
					
					if($num==0)
					{
					if($holiday==1)
					{					
                     //To check whether employee worked on thursday or saturday start here
					 if($vacation==1)
					 {	
				        //echo $starttimef;
				        $starttimef=$starttimeh;
						 if($nightshift==1)
						 {
							//$starttimef=date("Y-m-d",strtotime("$dates-2 days"));
						 }
						 $endtimef=$endtimeh;
					 }
					elseif($weekday==6)
					{$starttimef=date("Y-m-d",strtotime("$dates-2 days"));}
				    else{$starttimef=date("Y-m-d",strtotime("$dates-1 days"));}
					if($numoffdays==2 and $weekday==5 and $vacation==0)
					{$endtimef=date("Y-m-d",strtotime("$dates+2 days"));}
				   else{if($vacation==1){}else{$endtimef=date("Y-m-d",strtotime("$dates+1 days"));
				   }}
					
					$starttimef= $starttimef." 00:00:00";
					$endtimef= $endtimef." 23:59:59" ;
					
					//check from attendance punching data
                    $qryatf=mysql_query("select * from sftl_attendance where AttendanceCardNo='$attendacecard[$id]' and Attendance_McInTime BETWEEN '$starttimef' and '$endtimef' limit 0,1");
					$numbeforeafterfriday=mysql_num_rows($qryatf);
					//echo "<td bgcolor='yellow'></td>";
					//echo $numbeforeafterfriday;
					//if he is present on thursday or friday check make present on friday otherwise absent on friday
					if($numbeforeafterfriday>0)
					{
					$c++;
					$presntdays[strtotime($dates)]=1;
					//echo date("d",strtotime($dates)).",";
					}
					else
					{
					 //check data from employee request OD Slips
					 $qryodf=mysql_query("select * from employee_request where (ER_UserId='$id') and (ER_RequestTimeIn BETWEEN '$starttimef' and '$endtimef') and (ER_ApprovedPaidStatus='Y')");
					 $numbeforeafterfridays=mysql_num_rows($qryodf);
					 if($numbeforeafterfridays>0)
					  {
					   $c++;
					   $presntdays[strtotime($dates)]=1;
					   //echo date("d",strtotime($dates)).",$c,";
					  }
					  else
					  {
					  $absent++;
					  $absdays.=strtotime($dates).",";
					  //echo $dates1; 
					  }
					
					}				
					}
					else
					{
					$qryod=mysql_query("select * from employee_request where ER_UserId='$id' and ER_RequestTimeIn BETWEEN '$starttime' and '$endtime'");
					$numod=mysql_num_rows($qryod);
						if($numod>0)
						{
						 while($objod=mysql_fetch_object($qryod))
					     {
					      $odTimeIn=$objod->ER_RequestTimeIn;
						  $odTimeOut=$objod->ER_RequestTimeOut;
						  $odApprovedTimeIn=$objod->ER_ApprovedTimeIn;
						  $odApprovedTimeOut=$objod->ER_ApprovedTimeOut;
						  $paidstatus=$objod->ER_ApprovedPaidStatus;
						  //$oddur=(strtotime($odTimeOut)-strtotime($odTimeIn))/(60*60);
						  //echo"<td><a href='employeeattendancerequest.php?hidId=$objod->ER_Id'>OD </br>Status</br></a>".$objod->ER_Status."";
						  if($objod->ER_Status=="Approved" and $paidstatus=="Y")
						  {
						  $outs=$odTimeOut;
						  $ins=$odTimeIn;
						  include("variancecalc.php");
						  }
						   else
						   {
						   $absent++;
						   $absdays.=strtotime($dates).",";
						   
						   }
				    	 }
					    }
					   else
					    {
					    //echo "<td bgcolor='red'><font color='white'><a href='employeeattendancerequest.php?UserId=$id&dates=$dates'>Absent</a></font></td>";
					    $absent++;
						$absdays.=strtotime($dates).",";
						
				    	}
					
					
					}
					}
					
					else
					{			
				     $night=0;
					 $nodisplay=0;
  					while($objat=mysql_fetch_object($qryat))
   					{
					  $ins=$objat->Attendance_McInTime;
					  $outs=$objat->Attendance_McOutTime;
					  
					  if($nightshift==1)
					   {
						   if($weekday==5)
						   {}
					   else{
					   //to check whether intime at evening is matching with shift in time
					   $possibleintime=date("Y-m-d H:i:00",strtotime("$shift_in - 10 Hours"));
    		    	   $possibleouttime=date("Y-m-d H:i:00",strtotime("$shift_out + 10 Hours"));
					   	//echo $possibleouttime;			   
					   $ins=$outs;
					   $format="Y-m-d 00:00:00";
					   $endtimef=date("Y-m-d 23:59:59",strtotime("$endtime + 1 days"));
					   $starttimef=date("Y-m-d 00:00:00",strtotime("$starttime + 1 days"));
					   $qryatfr=mysql_query("select * from sftl_attendance where AttendanceCardNo='$attendacecard[$id]' and Attendance_McInTime BETWEEN '$starttimef' and '$endtimef' limit 0,1");
					   //echo "".strtotime($possibleintime).">=".strtotime($ins)."";
					  
					   
					  // echo "select * from sftl_attendance where AttendanceCardNo='$attendacecard[$id]' and Attendance_McInTime BETWEEN '$starttimef' and '$endtimef'";
					   $numatfrs=mysql_num_rows($qryatfr);
					
					   while($objatfr=mysql_fetch_object($qryatfr))
					   {
					   $outs=$objatfr->Attendance_McInTime;
					   }
					   $night=0;
					  if(strtotime($possibleintime)>=strtotime($ins))
					   {
					    $ins=$outs;
					    $night++;
					   }
					   if($numatfrs==0)
					   {
						   $night++;
					   }
					   elseif(strtotime($possibleouttime)<=strtotime($outs))
					    {
						 $outs=$ins;
						 $night++;
						}
						else{}
						
					   
					  
					// echo "night=".$night;
					  if($night==2)
						{
						//echo "night=".$night;
					  $qryodn=mysql_query("select * from employee_request where (ER_UserId='$id') and (ER_RequestTimeIn BETWEEN '$possibleintime' and '$possibleouttime')");
					
					  $numodn=mysql_num_rows($qryodn);
					    if($numodn==0)
					     {
						 $nodisplay=1;
						 //echo"<td>absent</td>";
						 if($vacation==0)
						 {
						 $absent++;
						 $absdays.=strtotime($dates).",";
						 }
						 else{$c++;}
						// echo $dates1;
						 }
						if($numodn>0)
						{
						 $odd=0;
						 while($objodn=mysql_fetch_object($qryodn))
					     {
					      $odTimeIn=$objodn->ER_RequestTimeIn;
						  $odTimeOut=$objodn->ER_RequestTimeOut;
						  $paidstatus=$objodn->ER_ApprovedPaidStatus;
						  $odApprovedTimeIn=$objodn->ER_ApprovedTimeIn;
						  $odApprovedTimeOut=$objodn->ER_ApprovedTimeOut;
						  //$oddur=(strtotime($odTimeOut)-strtotime($odTimeIn))/(60*60);
						   //echo"<td>";
						  if($paidstatus=="Y")
						  {
						  //echo"<a href='employeeattendancerequest.php?hidId=$objod1->ER_Id&hidAction=forapproval&nightshift=1'>OD </br>Status</br></a>".$objod->ER_Status."";
						  }
						  else
						  {
						 // echo"<a href='employeeattendancerequest.php?hidId=$objod1->ER_Id&hidAction=forapproval&nightshift=1'>OD </br>Status</br></a>".$objod->ER_Status."</br>";
						  }
						     if($objodn->ER_Status=="Approved" and $paidstatus=="Y")
						       {
						    	  $outs=$odApprovedTimeOut;
								  $ins=$odApprovedTimeIn;
						 		  $od=1;
						 		  include("variancecalc.php");
						  		  $od=0;
								  $night--;
						        }
						      else
						        {
								  $nodisplay=1;
						           //echo"<td>absent</td>";
						          $absent++;
								  $absdays.=strtotime($dates).",";
								  
						         }
						      }
						      }
						      }
							  }
					          }
							  
					$od=0;
					if($nodisplay==0)
					{
				     include("variancecalc.php");
					}
					 $od=1;
			         }   
					
									 
				  //$c for total working days except fridays and holidays
				  //$f days No of working fridays
				  //$tot is the total working days
				  //$absent is the number of absent days
				  if(isset($tot)){$tot=$c;} else{$tot=$c;}
			      }
			      }
				  
			      if($f==0)
			      {
			      $f=0;
			      }
			      else
			      {
			     // $c=$c-$f;
			      }
			      include("attduringvacation.php");
				  //attendance adjustment entry and OT entry starts here
				  $num_qry_attendance_adjustment=0;
				  $qry_attendance_adjustment=mysql_query("select * from attendance_request where user_id='$id' and approved_status='Y' and request_type='Attendance Request' and month='$monthselected' and year='$yearselected'");
				  $num_qry_attendance_adjustment=mysql_num_rows($qry_attendance_adjustment);
				  if($num_qry_attendance_adjustment>0)
				  {
					 while($obj_attendance_adjustment=mysql_fetch_object($qry_attendance_adjustment))
					 {
						 $request_days=$obj_attendance_adjustment->request_days;
						 $c=$c+$request_days;
						 $request_hours=$obj_attendance_adjustment->request_hours;
						 $request_minutes=$obj_attendance_adjustment->request_minutes;
					     $request_dur=($request_hours*60)+$request_minutes;
						// echo $request_dur;
						 $totshortdur=$totshortdur-$request_dur;
					 }
				  }
				  $num_qry_ot_entry=0;
				  $ot_hours=0;
				  $ot_minutes=0;
				  $ot_dur=0;
				  
				  $qry_ot_entry=mysql_query("select * from attendance_request where user_id='$id' and approved_status='Y' and request_type='OT Request' and month='$monthselected' and year='$yearselected'");
				  $num_qry_ot_entry=mysql_num_rows($qry_ot_entry);
				  if($num_qry_ot_entry>0)
				  {
					 while($obj_ot_entry=mysql_fetch_object($qry_ot_entry))
					 {
						 $ot_days=$obj_ot_entry->request_days;
						 //$c=$c+$request_days;
						 $ot_hours=$obj_ot_entry->request_hours;
						 $ot_hours=($ot_days*$shift_duration)+$ot_hours;
						 $ot_minutes=$obj_ot_entry->request_minutes;
					     $ot_dur=($ot_hours*60)+$ot_minutes;
						// echo $request_dur;
					 }
				  }
				  
				  if($totshortdur>60)
				  {
				   $totshortdur=$totshortdur;
				   $totshorthrs=floor($totshortdur/60);
				   $totshortmins=$totshortdur-($totshorthrs*60);
				  }
				  elseif($totshortdur<0)
				  {
				  //$totshortdur=$totsalded;
				   if($totshortdur>-60)
				   {$totshorthrs=0;}
			        else{$totshorthrs=floor($totshortdur/60)+1;}
				   
				   $totshortmins=$totshortdur-($totshorthrs*60);
				  }
				  else
				  {
				  //$totshortdur=$totsalded;
				   $totshorthrs=floor($totshortdur/60);
				   $totshortmins=$totshortdur-($totshorthrs*60);
				  }
				  //echo $shift_outside;
				  if($shift_outside=='Y')
				  {
					  $month=date("m",strtotime($datefrom));
                      $year=date("Y",strtotime($datefrom));
//echo $month.",".$year;					  
					  $c=cal_days_in_month(CAL_GREGORIAN,$month,$year);
					  $f=0;
					  $totded=0;
					  $absent=0;
					  
				  }
				  
				  //echo "c=".$c;
				 
				 $qrylock=mysql_query("select * from attendance_lock where attendance_lockMonth='$monthselected' and  attendance_lockYear='$yearselected'");
				 $numlock=mysql_num_rows($qrylock);
				 $lockstatus="N";
				 if($numlock>0)
				 {
				  while($objlock=mysql_fetch_object($qrylock))
				   {
				    $lockstatus=$objlock->attendance_lockStatus;
				   }
				 }
				  $qrysummary=mysql_query("select * from attendance_summary where Attendance_Status='Y' and Attendance_UserId='$id' and Attendance_Month='$monthselected' and Attendance_Year='$yearselected'");
				  //echo"";
				  $numsummary=mysql_num_rows($qrysummary);
				  if($numsummary==0)
				  {
				  $totded=($totshorthrs*60)+$totshortmins;
				  if($lockstatus=='Y')
				  {
				    //  $c=0;
					//  $f=0;
					//  $totded=0;
					//  $absent=0;
					  
				  }
				  else
				  {
				  mysql_query("insert into attendance_summary (Attendance_Month,Attendance_Year,Attendance_UserId,Attendance_McPresentDays,Attendance_McFriDay,Attendance_McDedMins,Attendance_McAbsent) values ('$monthselected','$yearselected','$id','$c','$f','$totded','$absent')");
				  }
				  $summaryid=mysql_insert_id();
				  }
				  else
				  {
				   while($objsummary=mysql_fetch_object($qrysummary))
				    {
					 $totded=($totshorthrs*60)+$totshortmins;
					 $summaryid=$objsummary->Attendance_Id;
					  if($lockstatus=='Y')
				      {			  }
				  	 else
				   	  {
					   $vacation_remarks.=$objsummary->Attendance_Remarks;
					   // echo $vacation_remarks;
					   mysql_query("update attendance_summary set Attendance_McPresentDays='$c',Attendance_McFriDay='$f',Attendance_McDedMins='$totded',Attendance_McAbsent='$absent' where Attendace_Id='$summaryid'");
					   // echo "update attendance_summary set Attendance_Remarks='$vacation_remarks',Attendance_McPresentDays='$c',Attendance_McFriDay='$f',Attendance_McDedMins='$totded',Attendance_McAbsent='$absent' where Attendace_Id='$summaryid'";
					  }
					 $ManPreDays=$objsummary->Attendance_ManPresentDay;
				     $ManDedMin=$objsummary->Attendance_ManDedMins;
					 $edited=$objsummary->Attendance_Editedby;
					 $TotSalaryDays=$objsummary->Attendance_SalaryTotDays;
					 $ManOtMin=$objsummary->Attendance_ManOtMins;
					 $SalaryManDays=$objsummary->Attendance_SalaryDays;
				     $SalaryAbsent=$objsummary->Attendance_SalaryAbsentDays;
					 $SalaryDedMin=$objsummary->Attendance_SalaryDedMins;
					 $SalaryDedMin=floor($SalaryDedMin/60).":".($SalaryDedMin-(floor($SalaryDedMin/60)*60));
					 $SalaryOtMin=$objsummary->Attendance_SalaryOtMins;
					 $Attendance_Approval=$objsummary->Attendance_Approval;
					 
					 //$Remarks="";
					 $Remarks=$objsummary->Attendance_Remarks;
					 if($ManDedMin<60)
					 {
					 $ManDedHrs=round($ManDedMin/60);
					 $ManDedMin=$ManDedMin-($ManDedHrs*60);
					 }
					 elseif($ManDedMin>60)
					 {
					 //$ManDedMin=$ManDedMin-60;
					 $ManDedHrs=floor($ManDedMin/60);
					 $ManDedMin=$ManDedMin-($ManDedHrs*60);
					 
					 }
					 else
					 {
					 $ManDedHrs=0;
					 }
				    }
					//to change the color of the saved rows starts here
					if($edited>1)
					{
					$bgcolr='ccffcc';
					}
					elseif($edited==1)
					{
					$bgcolr='ccffcc';
					}
					else
					{
					$bgcolr='ccccff';
					$completed='N';
					}
					 //to change the color of saved rows ends here
					 if($ManOtMin>60)
					 {
					 $ManOtHrs=floor($ManOtMin/60);
					 $ManOtMin=$ManOtMin-($ManOtHrs*60);
					 }
					 else
					 {
					 $ManOtHrs=0;
					 }
				  }
				  echo "<div id=attendancesummary_".$id.">";
				   echo"<form name='attendancesummary_".$id."' action='summaryaction.php' method='action'>";
				 //$tot=$c+$f;
				  echo "<td><input type='hidden' name='hiddensummaryid[".$id."]' value='".$summaryid."'/><input type='hidden' name='hiddenid[".$id."]' value='".$id."' readonly='readonly' size='2'/><input type='hidden' name='Shift_Hours[".$id."]' value='".$shifthours."' readonly='readonly' size='2'/>".$shifthours."</td>";
				  echo "<td>";
				  if($vacation_time==1)
				  {
				  echo "<span class='dropt'><span style='width:500px; height:100px;'>".$vacation_remarks."</span>";
				  }
				  echo "<input type='hidden' name='Tot_Salary_Days[".$id."]' value='".$total_salary_days."' readonly='readonly' size='2'/>";
				  echo "<input type='hidden' name='Present_NormalDays[".$id."]' value='".$c."' readonly='readonly' size='2'/>".$c."</td>";
				  
				  
				  
				  if($vacation_time==1)
				  {
				  echo "</span>";
				  }
				  //echo "<td><input type='hidden' name='Present_FriDays[".$id."]' readonly='readonly' value='".$f."' size='2'/>".$f."</td><td><input type='hidden' name='Present_TotalDays[".$id."]' readonly='readonly' value='".$tot."' size='2'/>$tot</td>";
				  echo "<td><input type='hidden' name='Absent_Days[".$id."]' readonly='readonly' value='".$absent."' size='2'/>".$absent."</td>";
				  echo "<td><input type='hidden' name='Deduction_McHours[".$id."]' readonly='readonly' value='".$totshorthrs.":".$totshortmins."' size='2'/>".$totshorthrs.":".$totshortmins."</td>";
				  echo "<td><input type='hidden' name='OT_Minutes[".$id."]' value='".$ot_dur."' readonly='readonly' size='2'/>".$ot_hours.":".$ot_minutes."</td>";
				  $absdays=explode(",",$absdays);
				
				  
				  echo "<td>".arrangedates($absdays)."";
				 
				 
				  
                  echo "<input type='hidden' name='searchname[".$id."]' value='$searchname'/><input type='hidden' name='editedby[".$id."]' value='".$edited."'/><input type='hidden' name='searchterm[".$id."]' value='$searchterm'/><input type='hidden' name='datefrom[".$id."] value='".$datefrom."'/><input type='hidden' name='dateto[".$id."] value='".$dateto."'/>";
				  echo "</td>";
				  if($lockstatus=='Y')
				   {
				   $bgcolr='green';
				   if(isset($ManPreDays)){}else{$ManPreDays=0;}
				   if(isset($ManDedHrs)){}else{$ManDedHrs=0;}
				   if(isset($totshorthrs)){}else{$totshorthrs=0;}
   				   if(isset($ManDedMin)){}else{$ManDedMin=0;}
				   if(isset($Remarks)){}else{$Remarks=0;}
				   if(isset($ManOtHrs)){}else{$ManOtHrs=0;}
				   if(isset($Remarks)){}else{$Remarks=0;}
				   echo "<td><input type='text' name='Present_ManDays[".$id."]' value='".$ManPreDays."' size='2' readonly='readonly'/></td><td bgcolor='".$bgcolr."'><input type='number' name='Deduction_Hours[".$id."]' value='".$ManDedHrs."' size='2' min='-".$totshorthrs."' style='width:45' readonly='readonly'/></td><td bgcolor='".$bgcolr."'><input type='text' name='Deduction_Minutes[".$id."]' value='".$ManDedMin."' size='2' readonly='readonly'/></td><td bgcolor='".$bgcolr."'><input type='text' name='OT_ManHours[".$id."]' value='".$ManOtHrs."' size='2' readonly='readonly'/></td><td bgcolor='".$bgcolr."'><input type='text' name='OT_ManMinutes[".$id."]' value='".$ManOtMin."' size='2' readonly='readonly'/></td><td bgcolor='".$bgcolr."'><input name='Remarks[".$id."]' placeholder='remarks' value='".$Remarks."' readonly='readonly'/></td>";
				   }
				   
				  elseif(($manual_entry_accesss)and $Attendance_Approval=='N')
				  {
				  echo "<input type='hidden' name='hiduser_id' value='".$User_Id."'>";
				  echo "<td bgcolor='".$bgcolr."'><input type='text' id='Present_ManDays_".$id."' name='Present_ManDays[".$id."]' value='".$ManPreDays."' size='1'/>";
				  echo "</td><td bgcolor='".$bgcolr."'><input type='number' id='Deduction_Hours_".$id."' name='Deduction_Hours[".$id."]' value='".$ManDedHrs."' size='2' style='width:45'/></td>";
				  echo "<td bgcolor='".$bgcolr."'><input type='text' id='Deduction_Minutes_".$id."' name='Deduction_Minutes[".$id."]' size='2'/></td>";
				  //echo "<td bgcolor='".$bgcolr."'><input type='text' id='OT_ManHours_".$id."' name='OT_ManHours[".$id."]' value='".$ManOtHrs."' size='2'/></td>";
				  //echo "<td bgcolor='".$bgcolr."'><input type='text' id='OT_ManMinutes_".$id."' name='OT_ManMinutes[".$id."]' value='".$ManOtMin."' size='2'/></td>";
				  echo "<td bgcolor='".$bgcolr."'><input id='Remarks_".$id."' name='Remarks[".$id."]' placeholder='remarks' value='".$Remarks."'/></td>";
				  echo "<td><input type='submit' name='save_$id' value='save_$id' onclick='return validateInput(".$id.",".$User_Id.")'/></td>";
				  }
				  elseif($adminstatus=='Y')
				  {
					   echo "<input type='hidden' name='hiduser_id' value='".$User_Id."'>";
					   echo "<td><input type='submit' name='save_$id' value='save_$id' onclick='return validateInput(".$id.",".$User_Id.")'/></td>";
				  }
				  else{}
				  /*
				  else
				  {
				  echo "<td><input type='text' name='Present_ManDays[".$id."]' value='".$ManPreDays."' size='2' readonly='readonly'/></td>";
				  echo "<td bgcolor='".$bgcolr."'><input type='number' name='Deduction_Hours[".$id."]' value='".$ManDedHrs."' size='2' min='-".$totshorthrs."' style='width:45' readonly='readonly'/></td>";
				  echo "<td bgcolor='".$bgcolr."'><input type='text' name='Deduction_Minutes[".$id."]' value='".$ManDedMin."' size='2' readonly='readonly'/></td><td bgcolor='".$bgcolr."'>";
				  echo "<input type='text' name='OT_ManHours[".$id."]' value='".$ManOtHrs."' size='2' readonly='readonly'/></td>";
				  echo "<td bgcolor='".$bgcolr."'><input type='text' name='OT_ManMinutes[".$id."]' value='".$ManOtMin."' size='2' readonly='readonly'/></td><td bgcolor='".$bgcolr."'><input name='Remarks[".$id."]' placeholder='remarks' value='".$Remarks."' readonly='readonly'/></td>";
				  }*/
				  echo "<td bgcolor='".$bgcolr."'>".$SalaryManDays."</td><td bgcolor='".$bgcolr."'>".$SalaryAbsent."</td><td bgcolor='".$bgcolr."'>".$SalaryDedMin."</td><td bgcolor='".$bgcolr."'>".$SalaryOtMin."</td> ";
				  echo "<td bgcolor='".$bgcolr."'>".$TotSalaryDays."</td>";
				  if($adminstatus=='Y')
				  {
				  
				  }
			      //echo"<td>short Hours=".$short_hrs."</td>";
                  
			      echo "</form></div></tr>";
                  $sl++;
              }
}
echo "completed status ".$completed;
if($lockstatus=="Y")
{
echo "<font color='green' size='5'>Salary Sheet is approved</font>";
}
elseif($User_Id=='1' and $completed=='Y' and $lockstatus=="N" and $searchterm=="" and $searchname=="")
{
echo "<tr><td colspan='20' align='center'><a href='attendancesummaryapproved.php?hidStatus=approved&month=$monthselected&year=$yearselected&datefrom=$datefrom&dateto=$dateto'><img src='upload/confirm.jpg' width='100' height='50'/></a></td></tr> ";
}
if($lockstatus=="Y")
{
echo "<tr><td colspan='8' align='center'><a href='attendancetoexcel.php?hidStatus=excel&month=$monthselected&year=$yearselected'>Export to Excel </a></td></tr> ";
}
if($User_Id=="13")
{
echo "<tr><td colspan='8' align='center'><a href='attendancetoexcel.php?hidStatus=excel&month=$monthselected&year=$yearselected'>Export to Excel </a></td></tr> ";
}
//echo"<a href='attendancesummaryapproved.php?hidStatus=approved&month=$monthselected&year=$yearselected&datefrom=$datefrom&dateto=$dateto'><img src='upload/confirm.jpg' width='200' height='100'/></a>";
echo "</table>";
  function arrangedates($absdays)
	{
		$value="";
		$values="";
		$numarray=count($absdays)-1;
		 if($numarray<=0)
		  {
		  return "No absent";
		  }
		 elseif($numarray>3)
		  {
		       $continue="No";
			   for($i=0;$i<=$numarray;$i++)
			   {
				   if($absdays[$i]==""){}
				   else
				   {
                   $datediff=ceil(($absdays[$i+1]-$absdays[$i])/(60*60*24));
				   if($datediff==1 and $continue=="No")
				   {
				   $start=date("d",$absdays[$i]);
				   $continue="Yes";
				   }
				   elseif($continue=="Yes" and $datediff<=1)
				   {
				   $continue="Yes";
				   $numarray--;
				   if($i==$numarray)
				   {
				     $value.=$start."-".date("d",$absdays[$i])."";
				   }
				   $numarray++;
				   }
				   elseif($continue=="Yes" and $datediff>1)
				   {
				   $continue="No";
				   $value.=$start."-".date("d",$absdays[$i]).",";
				   }
				   elseif($continue=="No")
				   {
				   $value.=date("d",$absdays[$i]).",";
				   }
				   else
				   {
				   $value.=date("d",$absdays[$i]).",";
				   $continue="No";
				   }
				   }
				   
				   
				   }
				   return $value;
				  }
				  else
				  {
				   //$numarray=count($absdays);
				   foreach($absdays as $absdayss)
				   {
				   if($absdayss=="")
				   {}
				   else
				   {
				   $values.=date("d",$absdayss).",";
				   }
				   }
				   return $values;				   
				  } 
	}
include("bottombar.php");
?>
