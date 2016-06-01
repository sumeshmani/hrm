<?php
/*
// Below logic is to calculate the attendance of employees during their vacation
// We are considering attendance from 21(pre) to 20(current) 
//  
// check whether employee is on vacation during $date_from_vac_calc' and '$date_to_vac_calc 
*/
$vacation_remarks="";
$ppp=0;
if(($current_month_starting<=$vacation_start_date and $date_to_vac_calc>=$vacation_start_date) or ($current_month_starting<=$vacation_end_date and $date_to_vac_calc>=$vacation_end_date) or ($date_from_vac_calc<=$doj[$id] and $dateto>=$doj[$id]))
{
$total_salary_days=date('t',strtotime($dateto));
//echo "<td>".$total_salary_days."</td>";	
if($date_from_vac_calc<=$doj[$id] and $dateto>=$doj[$id])
{
	$c=$c+($total_salary_days-20);
	$vacation_remarks.="Joined on".date('d-M-Y',strtotime($doj[$id]));
}
}	
else
{
   $total_salary_days=date('t',strtotime($date_from_vac_calc));
  // echo "<td>".$total_salary_days."</td>";
}
if($vacation_time==1)
{
/*
// If vacation starts and ends between 01(current)-20(current) time period, use attendance as it is...
// $vacation_start_date=$objvac->Vacation_StartDate;
// $vacation_end_date=$objvac->Vacation_EndDate;
// $date_from_vac_calc=date("Y-m-21",strtotime("$today-30 days"));
// $date_to_vac_calc=date("Y-m-".$num_days_current_month."",strtotime("$today"));
// $current_month_starting=date("Y-m-01",strtotime("$today"));
// 
// $pre_month_ending=$datefrom=date("Y-m-".$num_days_prev_month."",strtotime("$datefrom"));
// 
*/
//for example 1june to 18 june, 1june to 20 june,5 june to 19 june etc
if(count($presntdays)>1)
{
 array_unique($presntdays);
}
 if($current_month_starting<=$vacation_start_date and $dateto>=$vacation_end_date)
 {
   //Use the same attendance from punching data and OD Slips	
 }

/*
//
//
*/

/*
// If vacation starting from date 1(current)-31(current) $ vacation ends after 31(cur) //
// attendance to be calculated from 1(current) to vacation starting date //
//
// and deductions as it is
// 
*/
 elseif($dateto<$vacation_start_date and $date_to_vac_calc>=$vacation_start_date)
 {
	 if($vacation_end_date>$date_to_vac_calc) 
	 {
	 $ppp=0;
	 	 
	 foreach($presntdays as $time=>$num)
	 { 
	  $day_present=date("d",$time);
	  
		 if($day_present<=20 and $day_present>=1)
		 {
			// echo "present days ".date('d-m',$time);
			
			 $ppp++;
		 }
		 
	 }
	 if($vacation_start_date>$dateto)
	 {
	  $date21_to_vac_start_date=date("d",strtotime($vacation_start_date))-date("d",strtotime($dateto))-1;
	  //echo "days".$date21_to_vac_start_date;
	  $c=$ppp+$date21_to_vac_start_date;
	 }
	 //$vacation_remarks.="test";
	 $vacation_remarks.= "Going for Vacation on ".date('d-m-Y',strtotime($vacation_start_date)).":";
	 $vacation_remarks.= "</br>Attendance from 1-".date('m-Y',strtotime($dateto))." to 20-".date('m-Y',strtotime($dateto))." is ".$ppp." Days + </br>";
	 $vacation_remarks.= "From 21-".date('m-Y',strtotime($dateto))." to ".date('d-m-Y',strtotime($vacation_start_date))." is ".$date21_to_vac_start_date."Days";	
	 }
 }
 /* If vacation starting from date 1(cur)-20(cur) $ vacation ends after 31(cur) or vacation ends between 21(cur)-31(cur) //
 //  Attendance from 1st to vacation start date + Vacation end date 31 to cur
 //  and deductions as it is
 */
 elseif($current_month_starting<=$vacation_start_date and $dateto>=$vacation_start_date)
 {
	 if($vacation_end_date>$date_to_vac_calc)
	 {
		$ppp=0;
		
		$vacation_start_day=date("d",strtotime($vacation_start_date));
      	  
	 foreach($presntdays as $time=>$num)
	 { 
	 
	  $day_present=date("d",$time);
	  if($day_present>=1 and $day_present<=20)
	  {
		  $ppp++;
	  }
	   $c=$ppp;
	  // $absence_vac=0;
	 }
	 $absdays1=explode(",",$absdays);
	 //print_r ($absdays1);
	 foreach($absdays1 as $time=>$num1)
	  {
	//	  echo $num1.",";
		  if($num1>100)
		  {
           $day_absent=date("d",$num1);
	      }
	//echo $day_absent;
	     if($day_absent>=21 and $day_absent<=31)
	     {
			 $absence_vac++;
			 //echo $absence_vac;
	     }
		 echo $absence_vac;
		
	  }
	   $c=$c-$absence_vac;
	   
	 //$vacation_remarks.="test";
	 $vacation_remarks.= "Going for Vacation on ".date('d-m-Y',strtotime($vacation_start_date))." and will return on :".date('d-m-Y',strtotime($vacation_end_date));
	 $vacation_remarks.= "</br>Attendance from 1-".date('m-Y',strtotime($dateto))." to 20-".date('m-Y',strtotime($vacation_start_date))." is ".$ppp." Days </br>";
	 $vacation_remarks.= "</br>".$absence_vac." days absent between 21-".date('M-Y',strtotime($dateto."-1 months"))." to 31-".date('m-Y',strtotime($vacation_start_date."-1 months"));
	 
	 	 
	 //$vacation_remarks.= "From ".date('d-m-Y',strtotime($vacation_end_date))." to ".date('d-m-Y',strtotime($date_to_vac_calc))." is ".$date_from_vac_end_date_to_31."Days"; 
	  $ppp=0;
	 }
	 elseif($vacation_end_date<=$date_to_vac_calc and $vacation_end_date>=$dateto)
	 {
		$ppp=0;
	  $vacation_start_day=date("d",strtotime($vacation_start_date));
	  foreach($presntdays as $time=>$num)
	 { 
	  $day_present=date("d",$time);
	  if($day_present>=1 and $day_present<=20)
	  {
		  $ppp++;
	  }
	  $date_from_vac_end_date_to_31=date("d",strtotime($date_to_vac_calc))-date("d",strtotime($vacation_end_date));
	  $c=$ppp+$date_from_vac_end_date_to_31;
	 }
	 $vacation_remarks.= "Went for Vacation on ".date('d-m-Y',strtotime($vacation_start_date))." and will return on :".date('d-m-Y',strtotime($vacation_end_date));
	 $vacation_remarks.= "</br>Attendance from 1-".date('m-Y',strtotime($dateto))." to 20-".date('m-Y',strtotime($vacation_start_date))." is ".$ppp." Days + </br>";
	 $vacation_remarks.= "From ".date('d-m-Y',strtotime($vacation_end_date))." to ".date('d-m-Y',strtotime($date_to_vac_calc))." is ".$date_from_vac_end_date_to_31."Days";  
	 $ppp=0;
	 }
	 else
	 {
	  $vacation_remarks.= "Went for Vacation on ".date('d-m-Y',strtotime($vacation_start_date))." and coming on :".date('d-m-Y',strtotime($vacation_end_date));	 
	 }
	  
 }
/*
//  If vacation starting between 21(pre)-31(pre) .Calculate the deduction during these 
//  period .
//  save deduction datas(Absent and shortages) in attendance_pending table and to be deducted in joining month
// $shortday_day_wise[strtotime($dates)]=$shortdur;

*/
 elseif($date_from_vac_calc<=$vacation_start_date and $pre_month_ending>=$vacation_start_date)
 {
	 
/*	 
// If vacation starting between 21(pre)-31(pre) and ends between 1(cur) 30(cur)
//
// Absents and deduction has to be calculated from vacation Start date to 20(cur) 
// $absdays.=strtotime($dates).",";
// $shortday_day_wise[strtotime($dates)]=$shortdur;
*/
  if($vacation_end_date>=$current_month_starting and $vacation_end_date<=$date_to_vac_calc)
  {
   $totshortdur=0;
   $absent=0;
   $vacation_start_day=date("d",strtotime($vacation_start_date));
   $vacation_end_day=date("d",strtotime($vacation_end_date));
   $absdays1=explode(",",$absdays);
   foreach($absdays1 as $time=>$days)
   {
	 $absentday= date("d",strtotime($days));  
	 if($absentday<=1 and $absentday>=20)  
	 {$absent++;} 
     if($absentday<=21 and $absentday>$vacation_start_day)  
	 {$absent++;} 
   }
   foreach($shortday_day_wise as $time=>$short_dur_daily)
   {
	 $shortage_for_day= date("d",$time);  
	 if($shortage_for_day<=1 and $$shortage_for_day>=20)  
	 {$totshortdur=$totshortdur+$short_dur_daily;} 
     if($shortage_for_day<=21 and $shortage_for_day>$vacation_start_day)  
	 {$totshortdur=$totshortdur+$short_dur_daily;} 
   }
  }
/*	 
// If vacation starting between 21(pre)-31(pre) and ends between 1(cur) 20(cur)
//
// Consider deduction+absent from vacation start date to 31(pre)  
//
// actual attendance from vacation end date to 20(cur) + days from 21(cur) to 30(cur)   
//
*/

     if($vacation_end_date>=$current_month_starting and $vacation_end_date<=$dateto)
     {
	  $c=0;
	 $ppp=0;
	  foreach($presntdays as $time=>$num)
	 { 
	  $day_present=date("d",$time);
	  $vacation_end_day=date("d",strtotime($vacation_end_date));
	  if($day_present>=$vacation_end_day and $day_present<=20)
	  {
		  $ppp++;
	  }
	 }
	 $days_from_21_to_31=date("d",strtotime($date_to_vac_calc))-date("d",strtotime($dateto))+1;
	  $c=$ppp+$days_from_21_to_31;
	  //$vacation_remarks.="test";
	  $vacation_remarks.= "Went for Vacation on ".date('d-m-Y',strtotime($vacation_start_date))." and coming back on :".date('d-m-Y',strtotime($vacation_end_date))."";
	  $vacation_remarks.= "attendance to be taken from 01-".date("m-Y",strtotime($dateto))."to ".$dateto." is ".$ppp;
	  $vacation_remarks.= "days from 21-".date("m-Y",strtotime($dateto))."to ".date("d-m-Y",strtotime($date_to_vac_calc))." is ".$days_from_21_to_31;
	 }
     
// If vacation starting between 21(pre)-31(pre) and  vacation ends between 21(cur) 30(cur) 

	 if($vacation_end_date>$dateto and $vacation_end_date<=$date_to_vac_calc)
     {
	  $ppp=0;
	  $c=0;
	  $vacation_end_day=date("d",strtotime($vacation_end_date));
	  $days_from_21_to_31=date("d",strtotime($date_to_vac_calc))-$vacation_end_day;
	  //$vacation_remarks.="test";
	  $c=$days_from_21_to_31;
	  $vacation_remarks.= "Went for Vacation on ".date('d-m-Y',strtotime($vacation_start_date))." and coming back on :".date('d-m-Y',strtotime($vacation_end_date))."</br>";
	  $vacation_remarks.= "days from ".$vacation_end_day."-".date("m-Y",strtotime($dateto))."to ".date("d-m-Y",strtotime($date_to_vac_calc))." is ".$days_from_21_to_31;
	// $vacation_remarks.= "".date("d",strtotime($date_to_vac_calc))."-".date("d",strtotime(vacation_end_day))."";
	 }
	 if($vacation_end_date>=$date_to_vac_calc)
     {
		 $c=0;
	 }
	
 }
 /*
 // If vacation ends between 1 (cur) to 20(cur)
 
*/
 elseif($current_month_starting<=$vacation_end_date and $dateto>=$vacation_end_date)
{
  //echo "helloe";
    
     $c=0;
	 $ppp=0;
	  foreach($presntdays as $time=>$num)
	 { 
	  $day_present=date("d",$time);
	  $vacation_end_day=date("d",strtotime($vacation_end_date));
	  if($day_present>=1 and $day_present<=20)
	  {
		  $ppp++;
	  }
	 }
	 if($vacation_end_day<=20)
	 {
	 $days_from_21_to_31=date("d",strtotime($date_to_vac_calc))-date("d",strtotime($dateto));
	 }
	 else
	 {
		$days_from_21_to_31=date("d",strtotime($date_to_vac_calc))-date("d",strtotime($vacation_end_date))-1; 
	 }
	 $c=$ppp+$days_from_21_to_31;
	 //$vacation_remarks.="test";
	 $vacation_remarks.= "Went for Vacation on ".date('d-m-Y',strtotime($vacation_start_date))." and coming back on :".date('d-m-Y',strtotime($vacation_end_date))."";
	 $vacation_remarks.= "</br>attendance to be taken from 01-".date("m-Y",strtotime($dateto))."to ".$dateto." is ".$ppp;
	 $vacation_remarks.= "</br>days from 21-".date("m-Y",strtotime($dateto))."to ".date("d-m-Y",strtotime($date_to_vac_calc))." is ".$days_from_21_to_31;

}
 elseif($current_month_starting<=$dateto and $date_to_vac_calc>=$vacation_end_date)
{
  //echo "helloe";
    
     $c=0;
	 $ppp=0;
	 $vacation_end_day=date("d",strtotime($vacation_end_date));
	 if(count($presntdays)>=1)
	 {
	  foreach($presntdays as $time=>$num)
	 { 
	  $day_present=date("d",$time);
	  
	  if($day_present>=1 and $day_present<=20)
	  {
		  $ppp++;
		  //echo $ppp;
		  
	  }
	  $absent=20-$ppp;
	 }
	 }
	 
	 //echo "vacation=".$vacation_end_day."p".$ppp;
	 if($vacation_end_day<=20)
	 {
	 $days_from_21_to_31=date("d",strtotime($date_to_vac_calc))-date("d",strtotime($dateto));
	 }
	 else
	 {
    	$days_from_21_to_31=date("t",strtotime($dateto."-30 days"))-20; 
		//echo date("t",strtotime($dateto."-30 days"));
		//$days_from_21_to_31--;
	 }
	 $c=$ppp+$days_from_21_to_31;
	 //$vacation_remarks.="test";
	 $vacation_remarks.= "Went for Vacatio on ".date('d-m-Y',strtotime($vacation_start_date))." and coming back on :".date('d-m-Y',strtotime($vacation_end_date))."";
	 $vacation_remarks.= "</br>attendance to be taken from 01-".date("m-Y",strtotime($dateto))."to ".$dateto." is ".$ppp;
	 $vacation_remarks.= "</br>days from 21-".date("m-Y",strtotime($dateto))."to ".date("d-m-Y",strtotime($date_to_vac_calc))." is ".$days_from_21_to_31;

}
/*
// If vacation ends betweeen 21(pre) to 31(pre)
// salary for vacation end date to 31(pre) already collected
// so we have to considered deductions & absents from vacation end date to 20(cur)
// and attendance from 1-(cur)20 (cur) + days from 21 to 31(cur) 
// 
//
*/
 elseif($date_from_vac_calc<=$vacation_end_date and $pre_month_ending>=$vacation_end_date)
 {
	 $ppp=0;
   $totshortdur=0;
   $absent=0;
   $vacation_start_day=date("d",strtotime($vacation_start_date));
   $vacation_end_day=date("d",strtotime($vacation_end_date));
   $absdays1=explode(",",$absdays);
   foreach($absdays1 as $time=>$days)
   {
	 $absentday= date("d",$days);  
	 if($absentday<=1 and $absentday>=20)  
	 {$absent++;} 
     if($absentday>$vacation_end_day and $absentday<=31)  
	 {$absent++;} 
   }
   foreach($shortday_day_wise as $time=>$short_dur_daily)
   {
	 $shortage_for_day= date("d",$time);  
	 if($shortage_for_day<=1 and $$shortage_for_day>=20)  
	 {$totshortdur=$totshortdur+$short_dur_daily;} 
     if($shortage_for_day>$vacation_end_day and $shortage_for_day<=31)  
	 {$totshortdur=$totshortdur+$short_dur_daily;} 
   }
   $c=0;
	 $ppp=0;
	  foreach($presntdays as $time=>$num)
	 { 
	  $day_present=date("d",$time);
	  $vacation_end_day=date("d",strtotime($vacation_end_date));
	  
	  if($day_present>1 and $day_present<=20)
	  {
		  $ppp++;
	  }
	  if($day_present>$vacation_end_day and $day_present<=31)
	  {
		  $ppp++;
	  }
	 }
	 $days_from_21_to_31=date("d",strtotime($date_to_vac_calc))-date("d",strtotime($dateto));
	 $c=$ppp+$days_from_21_to_31;
	 $vacation_remarks.= "Went for Vacation on ".date('d-m-Y',strtotime($vacation_start_date))." and coming back on :".date('d-m-Y',strtotime($vacation_end_date))."";
	 $vacation_remarks.= "</br>attendance to be taken from 01-".date("m-Y",strtotime($dateto))."to ".$dateto." is ".$ppp;
	 $vacation_remarks.= "</br>days from 21-".date("m-Y",strtotime($dateto))."to ".date("d-m-Y",strtotime($date_to_vac_calc))." is ".$days_from_21_to_31;
    
    
 }
/*
// If vacation ends between 21(pre) to 31(pre)
// attendance already considered in last month attendance
// Deduction to be considered for 21 (pre) to 31 (pre) 

 elseif($date_from_vac_calc<=$vacation_start_date and $pre_month_ending>=$vacation_start_date)
 {
	if($vacation_end_date>$date_to_vac_calc)
	{
       $absent=0;
   $totshortdur=0;
   $vacation_start_day=date("d",strtotime($vacation_start_date));
   //$vacation_end_day=date("d",strtotime($vacation_end_date));
   $absdays1=explode(",",$absdays);
   foreach($absdays1 as $time=>$days)
   {
	   
	 $absentday= date("d",$days);  
	 if($absentday<=21 and $absentday>$vacation_start_day)  
	 {$absent++;} 
   }
   
   foreach($shortday_day_wise as $time=>$short_dur_daily)
   {
	 $shortage_for_day= date("d",$time);  
	 if($shortage_for_day<=21 and $shortage_for_day>$vacation_start_day)  
	 {$totshortdur=$totshortdur+$short_dur_daily;} 
   }
   //echo "insert in to attendance_adjustment(employee_id,month,year,absent,short_dur,closed_status) values('$id','$month','$year','$absent','$totshortdur','N')";
   $qryadjustment=mysql_query("select * from attendance_adjustment where employee_id='$id' and month='$monthselected' and year='$yearselected'");
   $numadj=mysql_num_rows($qryadjustment);
   if($numadj==0)
   {
	 mysql_query("insert into attendance_adjustment(employee_id,month,year,absent,short_dur,closed_status) values('$id','$monthselected','$yearselected','$absent','$totshortdur','N')");
   }
   else
   {
	 mysql_query("update attendance_adjustment set absent='$absent',short_dur='$totshortdur' where  employee_id='$id' and month='$monthselected' and year='$yearselected'");  
   }
   $vacation_remarks.= "absents of ".$absent." days and ".$totshortdur." to be deducted in next month salary";
   $c=0;
   $absent=0;
   $totshortdur=0;
		
	}
 }
/*
// If vacation ends between 21(pre) to 31(current)
// attendance to be considered from vacation end date to 21(current)+ 
// total days between 22(cur) & 31 (cur) - any deduction in attendance pending sheet
//
*/

//end of if($vacation_time==1)
}
$tot=$c;

?>