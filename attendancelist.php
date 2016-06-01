<?php
ini_set('max_execution_time', 300);
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("attendancelistheader.php");

//echo "<form name='cardupdate' action='cardupdateaction.php' method='post'>";
$sl=1;
//get details of each employee...user id's saved in array userids 
foreach($userids as $id)
{

//initialise all the variables
$totshortdur=0;
$night=0;
$totexcessdur=0;
$totsalded=0;
$totexcessmins=0;
$concession_upto_60minutes=0;

//To update attednace card no if it is not available with ncr_user table
	if($attendacecard[$id]=="")
		{
		echo "<tr><td>$id</td><td>".$User_Name[$id]."</td><td>".$dept[$id]."</td><td colspan='$col'> attendance card number not yet updated <a href='adduser.php?hidUserId=$id&hidAction=edit'>Update Now</a></td></tr>";
		$sl++;
		}
// calculate attendance from attendance table for all employees as per filtering    
	else
	{
		$s=0;
			$excessdur=0;
			$fri[$id]=0;
			$work_hrs_fri=0;
			$work_hrs=0;
			$work_hrs_total=0;
			$short_hrs=0;
			$absent=0;
			
			//display Employee name and department
			echo "<td>$sl</td>";
			echo "<td>".$User_Name[$id]."</td>";
			echo "<td>".$dept[$id]."</td>";
			//echo "<td>".$nationality[$id]."</td>";
            $v=0;
			$c=0;
  			$f=0;
				foreach($dateRange as $dates)
				{
					$nodisplay=0;
					$starttime= $dates." 00:00:00";
					$endtime= $dates." 23:59:59" ;
					$holiday=0;
		//To check shift details
		$qryshiftdetails=mysql_query("select * from shift_details where Shift_EmployeeId='$id'");
		$numshiftdetails=mysql_num_rows($qryshiftdetails);
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
		 // to get the shift details for current date
		 $dates1=$dates . "00:00:00";
		 
		 if($dates1>=$objshiftdetails->Shift_StartTime and $dates1<=$objshiftdetails->Shift_EndTime)
		  {
		  //echo "$dates>=$objshiftdetails->Shift_StartTime and $dates<=$objshiftdetails->Shift_EndTime";
		   //$shifthours=0;
		   $shifttimeforin=$shiftmasterintime[$objshiftdetails->Shift_MasterId];
		   $shifttimeforout=$shiftmasterouttime[$objshiftdetails->Shift_MasterId];
		   $shiftOffdays=$shiftOffDays[$objshiftdetails->Shift_MasterId];
		   
		   $shift_duration=((strtotime($shifttimeforout)-strtotime($shifttimeforin))/(60*60));
		   //echo $shift_duration.",";
		   $shifthours=$shift_duration;
		   if($shifthours<0)
		   {
		    $nightshift=1;
			//$shifthours=$shifthours*-1;
		   }
		   else
		   {
		   $nightshift=0;
		   }
		   // echo  $nightshift.",";
		  }
		   //echo $shift_duration.",";
		} 
		$weekday=date('w',strtotime($dates));
		//
		if($shift_duration=="" or $shift_duration=="0")
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
		$dates1=$dates ;
		//$dates1ends=$dates . "23:59:59";
		$qryholiday=mysql_query("select * from sftl_holidays where Holiday_Starts<='$dates1' and Holiday_Ends>='$dates1'");
		$numholiday=mysql_num_rows($qryholiday);
		if($numholiday)
		{$holiday=1;}
	
     	if(isset($shifthours))
		{
			$shift_in=$dates." ".$shifttimeforin.":00";
            $shift_out=$dates." ".$shifttimeforout.":00";
		    //$shift_out="2015-04-05 05:00:00";
	        if($nightshift==1)
	       {
	        $format="Y-m-d H:i:00";
	        //$day=1;
	         $shift_out = date($format, strtotime("$shift_out + 1 days"));
			 $shifthours=((strtotime($shift_out)-strtotime($shift_in))/(60*60));
			 
	       }	
		}
		else
		{
		$shifthours=$workdur[$id];
		}
	
	  // echo $shift_in."out,".$shift_out;
	   //echo $shift_in.":".$shift_out;
		//To check whether attendance data is locked or not
		
				$qrylock=mysql_query("select * from attendance_lock");
    	        $numlock=mysql_num_rows($qrylock);
			    $lockstatus='N';
				 if($numlock>0)
				 {
				  while($objlock=mysql_fetch_object($qrylock))
				   {
				    $lockdatefrom=strtotime($objlock->attendance_dateFrom);
					$lockdateto=strtotime($objlock->attendance_dateTo);
				    $selectedtime=strtotime($starttime);
					if($selectedtime>=$lockdatefrom and $selectedtime<=$lockdateto)
					{
				     $lockstatus=$objlock->attendance_lockStatus;
					}
				  }
				 }
				 	$qryat=mysql_query("select * from sftl_attendance where AttendanceCardNo='$attendacecard[$id]' and Attendance_McInTime BETWEEN '$starttime' and '$endtime' limit 0,1");
					$num=mysql_num_rows($qryat);
					$weekday=date('w',strtotime($dates));

					//to check absentism
					//echo $weekdaycheck;
					if($num==0)
					{
					//check is it friday
					
					if($holiday==1 and $nightshift==0)
					 {
					 echo "<td bgcolor='yellow'>$weekdaycheck</td>";
					 }
					 elseif($nightshift==1)
					 {
					  if($holiday==1)
					  {
					   echo "<td bgcolor='yellow'></td>";
					  }
					 else
					 { 
				        
				         //od slip checking has to be entered here
						 $qryod=mysql_query("select * from employee_request where (ER_UserId='$id') and (ER_RequestTimeIn BETWEEN '$starttime' and '$endtime')");
						 $numod=mysql_num_rows($qryod);
						if($numod==1)
						{
						 $odd=0;
						 
						 while($objod=mysql_fetch_object($qryod))
					     {
					      $odTimeIn=$objod->ER_RequestTimeIn;
						  $odTimeOut=$objod->ER_RequestTimeOut;
						  $paidstatus=$objod->ER_ApprovedPaidStatus;
						  $odApprovedTimeIn=$objod->ER_ApprovedTimeIn;
						  $odApprovedTimeOut=$objod->ER_ApprovedTimeOut;
						  //$oddur=(strtotime($odTimeOut)-strtotime($odTimeIn))/(60*60);
						   echo"<td>";
						  if($paidstatus=="Y")
						  {
						  echo"<a href='employeeattendancerequest.php?hidId=$objod->ER_Id&hidAction=forapproval'>OD </br>Status</br></a>".$objod->ER_Status."";
						  }
						  else
						  {
						  echo"<a href='employeeattendancerequest.php?hidId=$objod->ER_Id&hidAction=forapproval'>OD </br>Status</br></a>".$objod->ER_Status."</br>";
						  }
						  if($objod->ER_Status=="Approved" and $paidstatus=="Y")
						  {
						  $outs=$odApprovedTimeOut;
						  $ins=$odApprovedTimeIn;
						  $od=1;
						  include("variancecalc.php");
						  $od=0;
						  }
						  else
						   {
							  $absent++;
						   }
						  
				    	 }
					    }
						
					   else
					    {   
						    if($lockstatus=="Y")
							{
					        echo "<td bgcolor='red'><font color='white'>Absent</font></td>";
						    }
						    else
						    {
						    echo "<td bgcolor='red'><font color='white'><a href='employeeattendancerequest.php?UserId=$id&dates=$dates&hidAction=absent'>Absent1</a></font></td>";
						    }
					    $absent++;
				    	}
						 
					  	 //echo "<td>Absent</td>";
						 //$absent++;
					 }
					 }
					else
					{
					// To check OD slips submitted or not - to be updated to attendance Summary page also
					 $qryod=mysql_query("select * from employee_request where (ER_UserId='$id') and (ER_RequestTimeIn BETWEEN '$starttime' and '$endtime')");
					
					$numod=mysql_num_rows($qryod);
						if($numod==1)
						{
						 $odd=0;
						 
						 while($objod=mysql_fetch_object($qryod))
					     {
					      $odTimeIn=$objod->ER_RequestTimeIn;
						  $odTimeOut=$objod->ER_RequestTimeOut;
						  $paidstatus=$objod->ER_ApprovedPaidStatus;
						  $odApprovedTimeIn=$objod->ER_ApprovedTimeIn;
						  $odApprovedTimeOut=$objod->ER_ApprovedTimeOut;
						  //$oddur=(strtotime($odTimeOut)-strtotime($odTimeIn))/(60*60);
						   echo"<td>";
						  if($paidstatus=="Y")
						  {
						  echo"<a href='employeeattendancerequest.php?hidId=$objod->ER_Id&hidAction=forapproval'>OD </br>Status</br></a>".$objod->ER_Status."";
						  }
						  else
						  {
						  echo"<a href='employeeattendancerequest.php?hidId=$objod->ER_Id&hidAction=forapproval'>OD </br>Status</br></a>".$objod->ER_Status."</br>";
						  }
						  if($objod->ER_Status=="Approved" and $paidstatus=="Y")
						  {
						  $outs=$odApprovedTimeOut;
						  $ins=$odApprovedTimeIn;
						  $od=1;
						  include("variancecalc.php");
						  $od=0;
						  }
						  else
						   {
							  
						   $absent++;
						   }
						  
				    	 }
					    }
						
					   else
					    {   
						    if($lockstatus=="Y")
							{
					        echo "<td bgcolor='red'><font color='white'>Absent</font></td>";
						    }
						    else
						    {
						    echo "<td bgcolor='red'><font color='white'><a href='employeeattendancerequest.php?UserId=$id&dates=$dates&hidAction=absent'>Absent</a></font></td>";
						    }
					    $absent++;
				    	}
					}
					}
					//if attedance data is available
					else
					{		
				     if($holiday==1 and $nightshift==1)
					 {
					  echo "<td bgcolor='yellow'></td>";
					 }
					 else
					 {
  					while($objat=mysql_fetch_object($qryat))
   					{
					  $ins=$objat->Attendance_McInTime;
					  $outs=$objat->Attendance_McOutTime;
					  
					  //for night shift employees
					  if($nightshift==1)
					  {
						  //echo $shift_in."-".$shift_out;
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
					  
					   $numatfrs=mysql_num_rows($qryatfr);
					  // echo "select * from sftl_attendance where AttendanceCardNo='$attendacecard[$id]' and Attendance_McInTime BETWEEN '$starttimef' and '$endtimef' limit 0,1";
					   while($objatfr=mysql_fetch_object($qryatfr))
					   {
					   $outs=$objatfr->Attendance_McInTime;
					   					 //echo $outs;
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
					  }
					  
						
						//absent
						//echo "night=".$night;
						if($night==2)
						{
						 $qryod1=mysql_query("select * from employee_request where (ER_UserId='$id') and (ER_RequestTimeIn BETWEEN '$possibleintime' and '$possibleouttime')");
					     $numod1=mysql_num_rows($qryod1);
					     if($numod1==0)
					      {
						   $nodisplay=1;
						   echo"<td><a href='employeeattendancerequest.php?UserId=$id&dates=$dates&hidAction=absent'>absent</a></td>";
						   $absent++;
						  }
						if($numod1>0)
						{
						 $odd=0;
						 while($objod1=mysql_fetch_object($qryod1))
					     {
					      $odTimeIn=$objod1->ER_RequestTimeIn;
						  $odTimeOut=$objod1->ER_RequestTimeOut;
						  $paidstatus=$objod1->ER_ApprovedPaidStatus;
						  $odApprovedTimeIn=$objod1->ER_ApprovedTimeIn;
						  $odApprovedTimeOut=$objod1->ER_ApprovedTimeOut;
						  //$oddur=(strtotime($odTimeOut)-strtotime($odTimeIn))/(60*60);
						   echo"<td>";
						  if($paidstatus=="Y")
						  {
						  echo"<a href='employeeattendancerequest.php?hidId=$objod1->ER_Id&hidAction=forapproval&nightshift=1'>OD </br>Status</br></a>".$objod->ER_Status."";
						  }
						  else
						  {
						  echo"<a href='employeeattendancerequest.php?hidId=$objod1->ER_Id&hidAction=forapproval&nightshift=1'>OD </br>Status</br></a>".$objod->ER_Status."</br>";
						  }
						     if($objod1->ER_Status=="Approved" and $paidstatus=="Y")
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
						           echo"<td>absent</td>";
						           $absent++;
						         }
						      }
						      }
						      }
					  //check os slip status
  					
					$od=0;
					if($nodisplay==0)
					{
					       if($nightshift==1)
					       {
					       echo "<td bgcolor='eeccdd'>";
					       }
					   	   else
					       {
					       echo "<td>";
					        }
					    include("variancecalc.php");
					 }
					 $od=1;
			         }   
					 }
				  if(isset($tot))
				  {
				  $tot=$c;
				  }
   			      else
				  {
				  $tot=$c;
				  }
			      }
			      }
			      if($f==0)
			      {
			      $f="";
			      }
			      else
			      {
			      $c=$c-$f;

			      }
			    
				  if($totshortdur<60)
				  {
	   			  $totshortdur=$totsalded;
     			   $totshorthrs=floor($totshortdur/60);
				   $totshortmins=$totshortdur-($totshorthrs*60);
				  }
				  else
				  {
				  if($concession_upto_60minutes<60)
				  {
				  $totshortdur=$totsalded;
				  //echo $totsalded;
				  }
				  else
				  {$totshortdur=$totsalded+$concession_upto_60minutes-60;
				  //echo "tot".$totsalded;
				  }
				  //echo "concession time".$concession_upto_60minutes ."short".$totsalded;
				  $totshorthrs=floor($totshortdur/60);
				   $totshortmins=$totshortdur-($totshorthrs*60);
				  }
				  if($totexcessdur>0)
				  {
				  $totexcesshrs=floor($totexcessdur/60);
				  $totexcessmins=($totexcessdur-$totexcesshrs*60);
				  }
				  else
				  {
				  $totexcesshrs=0;
				  $totexcessmins=0;
				  }
				   
				  echo "<td>".$c."</td><td>".$f."</td>";
				  //echo "<td>".$tot."</td>";
				  echo "<td>".$absent."</td><td><font color='red'>".$totshorthrs.":".$totshortmins."</font></td>";

			      //echo"<td>short Hours=".$short_hrs."</td>";
     
                 echo "</tr>";
                 $sl++;
              }
}
echo "</table>";
?>