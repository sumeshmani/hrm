<?php
include("connectivity.php");
include("functions.php");
$Attendance_file=$_FILES["Attendance_file"]["name"];
//echo "file name is" .$Attendance_file;
$DrgTmp=$_FILES["Attendance_file"]["tmp_name"];
$newdire="upload\\$Attendance_file";
$error=$_FILES["Attendance_file"]["error"];
move_uploaded_file($DrgTmp,$newdire);
set_include_path(get_include_path() . PATH_SEPARATOR . 'PHPExcel/Classes/');
include 'PHPExcel/IOFactory.php';
$inputFileName = 'upload\\'.$Attendance_file;
//echo 'Loading file ,upload\\'.$Attendance_file.', using IOFactory to identify the format<br />';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
//var_dump($sheetData);
$s=0;

foreach($sheetData as $heads)
{
if($s<2)
{
$s++;
}
else
{
//print_r($heads);

//to enter basic salary data	
$schedule="Monthly";
$validfrom="2015-04-01 00:00:00";
$validupto="2099-03-31 23:59:59";
$last_paid="2015-12-20 00:00:00";
$next_scheduled="2016-01-20 00:00:00";

$user_id=$heads[B];
$basic_salary=$heads[M];
$vary="variablewithattendance";
//bank datas
$account_name=$heads[AP];
$bank_name=$heads[AQ];
$bank_code=$heads[AR];
$account_no=$heads['AS'];
$iban=$heads[AT];
$grade=$heads[L];
$doj=$heads[E];
$division=$heads[K];
$designation=$heads[I];
$sponser=$heads[J];
mysql_query("update ncr_user set User_Designation='$designation',User_Sponsor='$sponser',User_Division='$division',User_Grade='$grade',User_JoiningDate='$doj' where User_Id='$user_id'");
if($basic_salary=="" or $basic_salary=="0")
{}
else{
mysql_query("insert into payroll_basic (user_id,basic_amount,effective_from,effective_to,created_by) values('$user_id','$basic_salary','$validfrom','$validupto','13')");
}
if($account_no=="")
{}
else{
mysql_query("insert into payroll_bankdetails(payroll_bankcode,payroll_userid,payroll_bankname,payroll_accountno,payroll_iban,payroll_accountname,created_by) values('$bank_code','$user_id','$bank_name','$account_no','$iban','$account_name','13')");	
}
//allowance HRA
$allowance_name="HRA";
$allowanceadd="Add";
$allowance_amount=$heads[AZ];
$allowancefixed="Fixed";
$last_paid="2015-12-20 00:00:00";
$next_scheduled="2016-01-20 00:00:00";
if($allowance_amount=="" or $allowance_amount=='0')
{
	$allowancepercentage=$heads[AY];
}
else
{
$allowancepercentage="";	
}
$allowanceschedule="Monthly";
}
$variable_dependent="Basicsalary";

if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");
}
// mobile data upload starts
$allowance_name="Mobile";
$validfrom="2015-04-01 00:00:00";
$validupto="2099-03-31 23:59:59";
$last_paid="2015-12-20";
$next_scheduled="2016-01-20";
$allowancefixed=$vary;
$allowance_amount=$heads[P];
$allowancepercentage="";	
$allowanceschedule="Monthly";

if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");
 }
 // food data-Allowance
 $allowance_name="Food";
$validfrom="2015-04-01 00:00:00";
$validupto="2099-03-31 23:59:59";
$last_paid="2015-12-20";
$next_scheduled="2016-01-20";
$allowancefixed=$vary;
$allowance_amount=$heads[W];
$allowancepercentage="";	
$allowanceadd="Add";
if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");
 }
//Food Allowance Deduct
$allowance_name="Food";
$validfrom="2015-04-01 00:00:00";
$validupto="2099-03-31 23:59:59";
$last_paid="2015-12-20";
$next_scheduled="2016-01-20";
$allowancefixed=$vary;
$allowance_amount=$heads[X];
$allowancepercentage="";	
$allowanceadd="Deduct";
if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");
 // echo "insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$effectivefrom','$validfrom','$validupto','$nextscheduled','13')";
}
 //Personal Loan 
$allowance_name="Personal Loan";
$validfrom=$heads[AM];
$validupto=$heads[AN];
$last_paid="2015-12-20 00:00:00";
$next_scheduled="2016-01-20 00:00:00";
$allowancefixed="Fixed";
$allowance_amount=$heads[AK];
$allowancepercentage="";	
$allowanceadd="Deduct";
if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");
}

 // Performance Allowance
  $allowance_name="PERFORMANCE ALLOWANCE";
  $validfrom="2015-04-01 00:00:00";
  $validupto="2099-03-31 23:59:59";
  $last_paid="2015-12-20 00:00:00";
  $next_scheduled="2016-01-20 00:00:00";
  $allowance_amount=$heads[S];
  $allowancefixed=$vary;
  $allowanceadd="Add";
if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
  mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");
 //echo "insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$effectivefrom','$validfrom','$validupto','$nextscheduled','13')";

 }
  // Conveyance
  $allowance_name="CONVEYANCE";
  $allowance_amount=$heads[O];
  $allowancefixed=$vary;
  $validfrom="2015-04-01 00:00:00";
  $validupto="2099-03-31 23:59:59";
  $last_paid="2015-12-20 00:00:00";
  $next_scheduled="2016-01-20 00:00:00";
if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
  mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");
}
  // Utility
  $allowance_name="UTILITY";
  $allowance_amount=$heads[Q];
  $allowancefixed=$vary;
  $validfrom="2015-04-01 00:00:00";
  $validupto="2099-03-31 23:59:59";
  $last_paid="2015-12-20 00:00:00";
  $next_scheduled="2016-01-20 00:00:00";
if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
  mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");
}
  // Educational Allowance
  $allowance_name="EDUCATION ALLOWANCE";
  $allowance_amount=$heads[R];
  $allowancefixed=$vary;
  $validfrom="2015-04-01 00:00:00";
  $validupto="2099-03-31 23:59:59";
  $last_paid="2015-12-20 00:00:00";
  $next_scheduled="2016-01-20 00:00:00";
if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
 mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");
}
//vacation salary data
$allowance_name="Vacation Salary";
$validfrom="2015-04-01 00:00:00";
$validupto="2099-03-31 23:59:59";
$last_paid=$heads[AB];
$next_scheduled=$heads[AD];
$allowancefixed="Fixed";
$allowance_amount=$heads[AA];
if($allowance_amount=="" or $allowance_amount=='0')
{
	$allowancepercentage=$heads[Y];
}
else
{
$allowancepercentage="";	
}
$allowanceadd="Add";
$allowanceschedule=$heads[Z];;
if(($allowance_amount=="" or $allowance_amount=="0") and ($allowancepercentage==""  or $allowancepercentage=="0"))
{}
else{
	mysql_query("insert into payroll_allowance (user_id,allowance_add,allowance_name,allowance_fixed,allowance_amount,allowance_percentage,	variable_dependent,allowance_schedule,Allowance_EffectiveFrom,Allowance_EffectiveTo,last_paid,next_scheduled,created_by) values
 ('$user_id','$allowanceadd','$allowance_name','$allowancefixed','$allowance_amount','$allowancepercentage','$variable_dependent','$allowanceschedule','$validfrom','$validupto','$last_paid','$next_scheduled','13')");

}
header("location:payrollmaster.php");

}

?>
