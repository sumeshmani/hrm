<?php
ini_set('max_execution_time', 300);
include("topbar.php");
include("connectivity.php");
include("menubar1.php");
include("functions.php");
$allowancenamearray=array("HRA"=>"HRA","CONVEYANCE"=>"CONVEYANCE","MOBILE"=>"MOBILE","UTILITY"=>"UTILITY","EDUCATION ALLOWANCE"=>"EDUCATION ALLOWANCE","PERFORMANCE ALLOWANCE"=>"PERFORMANCE ALLOWANCE","OTHER ALLOWANCE"=>"OTHER ALLOWANCE","VACATION SALARY"=>"VACATION SALARY","Personal Loan"=>"Personal Loan");
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
}
$dateto=date($yearselected.'-'.$monthselected.'-20',strtotime('$yearselected-$monthselected-20'));
$datefrom=date($yearselected.'-'.$monthselected.'-01',strtotime('$yearselected-$monthselected-20'));
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

$qryuserprofile=mysql_query("select * from ncr_user $criteria and (User_JoiningDate<='$dateto') and ((User_LeavingDate>='$datefrom') or(User_LeavingDate='0000-00-00'))  order by User_JoiningDate");
$e=0;
while($objuserprofile=mysql_fetch_object($qryuserprofile))
{
$userids[$e]=$objuserprofile->User_Id;
$employeecode[$objuserprofile->User_Id]=$objuserprofile->User_EmployeeCode;
$attendacecard[$objuserprofile->User_Id]=$objuserprofile->User_AttenId;
$User_Name[$objuserprofile->User_Id]=$objuserprofile->User_Name;
$designation[$objuserprofile->User_Id]=$objuserprofile->User_Designation;
$grade[$objuserprofile->User_Id]=$objuserprofile->User_Grade;
$division[$objuserprofile->User_Id]=$objuserprofile->User_Division;
$doj[$objuserprofile->User_Id]=$objuserprofile->User_JoiningDate;
 //echo "name-".$name[$objuserprofile->User_Id];
$dept[$objuserprofile->User_Id]=$objuserprofile->User_Department;
$nationality[$objuserprofile->User_Id]=$objuserprofile->User_Nationality;
$workdur[$objuserprofile->User_Id]=$objuserprofile->User_WorkHours;
$e++;
}
$months=array("01"=>"Jan","02"=>"Feb","03"=>"Mar","04"=>"Apr","05"=>"May","06"=>"Jun","07"=>"Jul","08"=>"Aug","09"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dec");
$yearss=array("2016"=>"2016","2015"=>"2015");
echo "<table class='tableaddp' border='1' cellspacing='0' cellpadding='0'>";
$col2="30";
echo "<tr><td colspan=$col2><form name='payrollcalc' action='payrollcalculation.php' method='post'><table class='tableaddp'><tr><td>Month <select name='month'>";
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
echo "<form name='payrollgen' action='payrollgenaction.php' method='post'>";
echo "<tr><td>Employee Code</td><td>Name</td>";
echo "<td>DOJ</td><td>EXPAT/SAUDI</td><td>Nationality</td><td>id</td><td>Designation</td><td>Department</td>";
echo "<td>Division</td><td>Grade</td>";
echo "<td>Shif Hours</td><td>Present days</td><td>Absent days</td><td>Deduction minutes</td><td>OT minutes</td>";
echo "<td>Basic Salary</td><td>Tot Days</td><td>Net Basic</td><td>Deduction</td><td>OT</td>";
foreach($allowancenamearray as $value)
{
	echo "<td>".$value."</td>";
}
echo "<td>Net Total</td>";
echo "</tr>";

foreach($userids as $id)
{
	//Shift data processing start here $dates & $id is the current date and Employee id 
		$qryshiftdetails=mysql_query("select * from shift_details where Shift_EmployeeId='$id'");
		$numshiftdetails=mysql_num_rows($qryshiftdetails);
		if($numshiftdetails==0 or $dept[$id]=='Security')
		{
		  $shift_duration=8;
		}
		while($objshiftdetails=mysql_fetch_object($qryshiftdetails))
		{
			$dates1=$dateto . "00:00:00";
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
		    $shift_duration=$shift_duration*-1;
		   }
		   else
		   {
		   $nightshift=0;
		   }
		    if($shiftmastername[$objshiftdetails->Shift_MasterId]=="Outside Office")
		   {
			   $shift_outside='Y';
			   $shift_duration=10;
		   }
		    
		  }
		  }
		  if($shift_duration=="")
		  {
			$shift_duration=8;  
		  }
		 
// shift calculation ends here		  
	echo "<tr>";
	echo "<td>".$employeecode[$id]."</td>";
	echo "<td>".$User_Name[$id]."</td>";
	echo "<td>".date('d-M-Y',strtotime($doj[$id]))."</td>";
	if($nationality[$id]=='Saudi')
	{echo "<td>SAUDI</td>";} 
    else
	{echo "<td>EXPAT</td>";}
    echo "<td>".$nationality[$id]."</td>";
	$qry_idnumber=mysql_query("select Document_No from hr_document where Document_Type='Iqama' and Document_UserId='$id' and Document_Status='Y'");
	$idno="";
	while($obj_id_number=mysql_fetch_object($qry_idnumber))
	{
		$idno=$obj_id_number->Document_No;
	}
    echo "<td>".$idno."</td>";
    echo "<td>".$designation[$id]."</td>";
    echo "<td>".$dept[$id]."</td>";
    echo "<td>".$division[$id]."</td>";
    echo "<td>".$grade[$id]."</td>";
	echo "<td>".$shift_duration."</td>";
	$qryatt=mysql_query("select * from attendance_summary where Attendance_Month='$monthselected' and Attendance_Year='$yearselected' and Attendance_UserId='$id' and Attendance_Status='Y' and Attendance_Editedby>0 limit 0,1");
	$numatt=mysql_num_rows($qryatt);
	if($numatt==0)
	{
		echo "<td>attendance not Confirmed</td>";
	}
	else
	{
	// data from Attendance Summary
	   while($objatt=mysql_fetch_object($qryatt))
	   {
		$present_days=$objatt->Attendance_SalaryDays;
		$deduction_minutes=$objatt->Attendance_SalaryDedMins;
		$ot_minutes=$objatt->Attendance_SalaryOtMins;
		$num_of_days=$objatt->Attendance_SalaryTotDays;
		echo "<td>".$objatt->Attendance_SalaryDays."</td>";   
		echo "<td>".$objatt->Attendance_SalaryAbsentDays."</td>"; 
		echo "<td>".$objatt->Attendance_SalaryDedMins."</td>";
		echo "<td>".$objatt->Attendance_SalaryOtMins."</td>";
	    }
	// data from basic salary and salary calculation as per attendance 
	 $qrybasic=mysql_query("select * from payroll_basic where user_id='$id' and (effective_from<='$dates1' and effective_to>='$dates1')");
     $numbasic=mysql_num_rows($qrybasic);
     if($numbasic==0)
     {
	 $basic_salary=0;
	 $net_basic_salary=0;
	 $salary_deduction=0;
	 $salary_ot=0;
	 echo "<td colspan='5'><a href='basicsalary.php?hidUserId=$id'>Basic Salary not entered</a></td>";
     }
     else
     {
	  while($objbasic=mysql_fetch_object($qrybasic))
	  {
		  $basic_salary=$objbasic->basic_amount;
		  //$num_of_days=date('t',(strtotime($dates1)-(60*60*24*30)));
		  echo "<td>$basic_salary</td>";
		  echo "<td>$num_of_days</td>";
		  $net_basic_salary=round($basic_salary*($present_days/$num_of_days),2);
		  echo "<td>".$net_basic_salary."</td>";
		  $total_net_basic_salary=$total_net_basic_salary+$net_basic_salary;
		  $salary_deduction=round(($basic_salary/$num_of_days)*($deduction_minutes/($shift_duration*60)),2);
		  $salary_deduction=$salary_deduction*-1;
		  $total_salary_deduction=$total_salary_deduction+$salary_deduction;
		  echo "<td>".$salary_deduction."</td>";
		  $salary_ot=round(($basic_salary/$num_of_days)*($ot_minutes/($shift_duration*60)),2);
		  $salary_ot=$salary_ot*1.5;
		  $total_salary_ot=$total_salary_ot+$salary_ot;
		  echo "<td>".$salary_ot."</td>";
	  }
     }
	//Basic salary calculation ends here
	
	//Allowance calculation starts here
	$total_allowance_amount=0;
	 
	foreach($allowancenamearray as $key=>$value)
    {
	echo "<td>";
	$qryallowance=mysql_query("select * from  payroll_allowance where user_id='$id' and allowance_name='$value' and (Allowance_EffectiveFrom<='$dates1' and Allowance_EffectiveTo>='$dates1')");
	//echo "select * from  payroll_allowance where user_id='$id' and allowance_name='$value' and (Allowance_EffectiveFrom<='$dates1' and Allowance_EffectiveTo>='$dates1')";
    $numallowance=mysql_num_rows($qryallowance);
    if($numallowance=="0")
    {
	 $sub_total[$value]=$sub_total[$value]+0;
	 echo "<a href='#'>". $numallowance."</a>";
    }
    else
   {
	   $datefrom1=date('Y-m-01 00:00:00',strtotime($datefrom));
	   $dateto1=date('Y-m-t 23:59:59',strtotime($dateto));
	while($objallowance=mysql_fetch_object($qryallowance))
	 {
		 if($objallowance->allowance_schedule=='Monthly')
		 {
			 $to_process_allowance='Y';
		 }
		 elseif(strtotime($objallowance->next_scheduled)>=strtotime($datefrom1) and strtotime($objallowance->next_scheduled)<=strtotime($dateto1))
		 {
			 $to_process_allowance='Y';
		 }
		 else
		 {
			 $to_process_allowance='N';
		 }
		if($to_process_allowance=='Y')
		{
		 if($objallowance->allowance_amount=="" or $objallowance->allowance_amount=="0")
		 {
			$allowance_amount=($objallowance->allowance_percentage*$basic_salary)/100; 
		 }
		 else
		 {
			$allowance_amount=$objallowance->allowance_amount;
		 }
		if($objallowance->allowance_fixed=='variablewithattendance')
		{
			$allowance_amount=$allowance_amount*($present_days/$num_of_days);
		}
		
		if($objallowance->allowance_add=="Add")
		{$allowance_amount=$allowance_amount*1;}
	    else
		{$allowance_amount=$allowance_amount*-1;}
		$allowance_amount=round($allowance_amount,2);			
		echo $allowance_amount.",";
		$total_allowance_amount=$total_allowance_amount+$allowance_amount;
	   }
	   else
	   {
		$allowance_amount=0;
		echo "0";
       }
	  }//while loop ends 
	  $sub_total[$value]=$sub_total[$value]+$allowance_amount;
	 }
	 
     echo "</td>";
   	}//foreach ends here
	//allowance calculation ends here
	//total netvalue
	$total_net_amount=$total_allowance_amount+$salary_deduction+$net_basic_salary+$salary_ot;
	$total_gross_amount=$total_gross_amount+$total_net_amount;
	echo "<td>".$total_net_amount."</td>";
	}
	echo "<tr>";
}
echo "<tr><td colspan='17'>Total </td>";
echo "<td>".$total_net_basic_salary."</td>";
echo "<td>".$total_salary_deduction."</td>";
echo "<td>".$total_salary_ot."</td>";
foreach($allowancenamearray as $key=>$value)
{
	if($sub_total[$value]=="0" or $sub_total[$value]=="")
	{
	    echo "<td>-0</td>";
	}
	else
	{
		echo "<td>".$sub_total[$value]."</td>";
	}
}
echo "<td>".$total_gross_amount."</td></tr>";
if($User_Id=="13")
{
	echo "<a href='payrolltoexcel.php?month=$monthselected&year=$yearselected'>Export</a>";
}
echo "</form>";




?>


<?php

?>