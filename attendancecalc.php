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
if($s==0)
{
$s++;
}
else
{
//echo "<table border='1'>";
//echo "<tr>";

if(is_array($heads))
{
$heads["F"]=explode("-",$heads["F"]);
$heads["F"]="20".$heads["F"][2]."-".$heads["F"][0]."-".$heads["F"][1];
$intime=explode(" ",$heads["G"]);
$outtime=explode(" ",$heads["H"]);

if($intime[1]=="PM")
{
$intimes=explode(":",$intime[0]);
if($intimes[0]<12)
{
$intimes[0]=$intimes[0]+12;
}
$intime=implode(":",$intimes);
//echo '<td>$heads["G"]</td>';
}
elseif($intime[1]=="AM")
{
$intimes=explode(":",$intime[0]);
if($intimes[0]==12)
{
$intimes[0]="00";
}
//$intimes[0]=$intimes[0]+12;
$intime=implode(":",$intimes);
//echo '<td>$heads["G"]</td>';
}
else
{

}
if($outtime[1]=="PM")
{
$outtimes=explode(":",$outtime[0]);
if($outtimes[0]<12)
{
$outtimes[0]=$outtimes[0]+12;
}

$outtime=implode(":",$outtimes);
//echo '<td>$heads["G"]</td>';
}
elseif($outtime[1]=="AM")
{
$outtimes=explode(":",$outtime[0]);
if($outtimes[0]==12)
{
$outtimes[0]="00";
}
//$intimes[0]=$intimes[0]+12;
$outtime=implode(":",$outtimes);
//echo '<td>$heads["G"]</td>';
}
else
{

}
$in[$s]=$heads["F"]." ".$intime;
$out[$s]=$heads["F"]." ".$outtime;
//echo " out is".$out[$s];
$dur[$s]=round((strtotime($out[$s])-strtotime($in[$s]))/(60*60),2);
$mins[$s]=((strtotime($out[$s])-strtotime($in[$s]))/60)-(floor($dur[$s])*60);

$hrs[$s]=floor($dur[$s]);

$short[$s]=(12*60)-((strtotime($out[$s])-strtotime($in[$s]))/60);

   $no[$s]=$heads["A"];
   $dept[$s]=$heads["B"];
   $name[$s]=$heads["C"];
   $card[$s]=$heads["E"];
   
    
  // echo "<td>$head</td>";
  //echo "<td>$name[$s]</td><td>$in[$s]</td><td>$out[$s]</td><td>$hrs[$s] Hours $mins[$s] minutes</td><td></td>";
  mysql_query("insert into sftl_attendance(Attendance_UserName,Attendance_McInTime,Attendance_McOutTime,Attendance_AttenId,AttendanceCardNo) values('$name[$s]','$in[$s]','$out[$s]','$no[$s]','$card[$s]')");
 // echo "insert into sftl_attendance(Attendance_UserName,Attendance_McInTime,Attendance_McOutTime,Attendance_AttenId) values('$name[$s]','$in[$s]','$out[$s]','$no[$s]')</br>";
  $s++;
   // echo "<td>".$hrs."Hours". $mins." minutes</td>";
	
}	
//echo "</tr></table>";

}
header("location:attendancelist.php");
}

?>
