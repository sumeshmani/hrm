<?php
//To find out and correct Non Payment Od slips already overlapped with  Attendance log
function compareNPTimes($start1,$end1)
{
$count=count($start1);
$dur=0;
for($i=0;$i<$count;$i++)
   {
     
     if($start1[$i]>$start1[100] and $start1[$i]<$end1[100])
	 {
	  
	    $dur1=$end1[$i]-$start1[$i];
		$dur=$dur1+$dur;
		//echo $dur;
	  }
	   return $dur1;
	   
   }
$dur1=round($dur1/60);
   
   return $dur1;
}
//to find out duration of present hours from attendance list and OD slips for overlapping times
function compareTimes($start,$end)
{
$count=count($start);
   for($i=0;$i<$count;$i++)
   {
     
     for($j=0;$j<$count;$j++)
	  {
	   if($j==$i)
	   {
	   
	   }
	   elseif($start[$j]>=$start[$i] and $start[$j]<=$end[$i]) 
	   {
	     if($end[$j]>=$start[$i] and $end[$j]<=$end[$i]) 
	     {
     	   $start[$j]="0";
		   $end[$j]="0";
	     }  
		 else
		 {
		    $end[$i]=$end[$j];
			$start[$j]="0";
		   $end[$j]="0";
		 }
	   }
	    
	  }
   }
   $dur=0;
   $count1=count($start);
   for($i=0;$i<$count1;$i++)
   {
    $dur1=$end[$i]-$start[$i];
	$dur=$dur1+$dur;
	
   }
   $dur=round($dur/60);
   
   return $dur;
}
$qrymanlist=mysql_query("select User_Name,User_Id from ncr_user where User_ManagerStatus='Y' order by User_Name");
while($objmanlist=mysql_fetch_object($qrymanlist))
{
	$manager_listid[$objmanlist->User_Id]=$objmanlist->User_Id;
	$manager_list[$objmanlist->User_Id]=$objmanlist->User_Name;
}
$qrysupervisorlist=mysql_query("select User_Name,User_Id from ncr_user where User_SupervisorStatus='Y' order by User_Name");
while($objsupervisorlist=mysql_fetch_object($qrysupervisorlist))
{
	$supervisor_list[$objsupervisorlist->User_Id]=$objsupervisorlist->User_Name;
}

//to get the list of employees working under login user
$searchvalue=$_REQUEST['searchterm'];
if($adminstatus=='Y')
{
if(isset($_REQUEST['searchterm']))
{$qryemployeelist=mysql_query("select User_Name,User_Id from ncr_user where User_Name like '%$searchvalue%'");}
else{
$qryemployeelist=mysql_query("select * from ncr_user");}
}
elseif(isset($_REQUEST['searchterm']))
{
$qryemployeelist=mysql_query("select User_Name,User_Id from ncr_user where (User_ManagerId='$User_Id' or User_SupervisorId='$User_Id' or User_Id='$User_Id') and (User_Name like '%$searchvalue%')");
}
else{
$qryemployeelist=mysql_query("select User_Name,User_Id from ncr_user where User_ManagerId='$User_Id' or User_SupervisorId='$User_Id' or User_Id='$User_Id'");	
}
while($objemployeelist=mysql_fetch_object($qryemployeelist))
{
    $employee_ids[$objemployeelist->User_Id]=$objemployeelist->User_Id;
	$employee_list[$objemployeelist->User_Id]=$objemployeelist->User_Name;
	
}

$dept_list=array("Marketing"=>"Marketing","Production"=>"Production","Design"=>"Design","Purchase"=>"Purchase","Stores"=>"Stores","Quality"=>"Quality","Accounts"=>"Accounts","Admin"=>"Admin","IT"=>"IT","Maintenance"=>"Maintenance","Security"=>"Security");

$qryfullemployeelist=mysql_query("select User_Name,User_Id,User_SupervisorId,User_ManagerId from ncr_user");
while($objfullemployeelist=mysql_fetch_object($qryfullemployeelist))
{
	$fullemployee_list[$objfullemployeelist->User_Id]=$objfullemployeelist->User_Name;
	$managerof[$objfullemployeelist->User_Id]=$objfullemployeelist->User_ManagerId;
	$supervisorof[$objfullemployeelist->User_Id]=$objfullemployeelist->User_SupervisorId;
	$subsuper[$objfullemployeelist->User_SupervisorId]=$objfullemployeelist->User_Id;
    $submanager[$objfullemployeelist->User_ManagerId]=$objfullemployeelist->User_Id;
}

// To get shift details
$qryshiftmaster=mysql_query("select * from shift_master");
while($objshiftmaster=mysql_fetch_object($qryshiftmaster))
{
$shiftmasterid=$objshiftmaster->Shift_Id;
$shiftmastername[$shiftmasterid]=$objshiftmaster->Shift_Name;
$shiftmasterintime[$shiftmasterid]=$objshiftmaster->Shift_InTime;
$shiftmasterouttime[$shiftmasterid]=$objshiftmaster->Shift_OutTime;
$shiftOffDays[$shiftmasterid]=$objshiftmaster->Shift_OffDays;
}
?>