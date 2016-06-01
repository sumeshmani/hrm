<?php
//initialise values
$tot='';
$summary=0;
$od=0;
// function to get dates between two date
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

$today=date("Y-m-20");
//To get last uploaded date
$qryupload=mysql_query("select MAX(Attendance_McInTime) as lastuploaddate from sftl_attendance");
while($objupload=mysql_fetch_object($qryupload))
{
$lastuploaddate=$objupload->lastuploaddate;
}
echo "Attendance Updated upto:".substr($lastuploaddate,0,11);
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
$dateto=date("Y-m-20");
}
if($dateto>$lastuploaddate)
{
$dateto=date("Y-m-d",strtotime($lastuploaddate));
}
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
//$dateto="2015-07-25";
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

$qryuserprofile=mysql_query("select * from ncr_user $criteria and (User_JoiningDate<='$dateto') and ((User_LeavingDate>='$datefrom') or(User_LeavingDate='0000-00-00'))   order by User_JoiningDate");
$e=0;
while($objuserprofile=mysql_fetch_object($qryuserprofile))
{
$userids[$e]=$objuserprofile->User_Id;
$attendacecard[$objuserprofile->User_Id]=$objuserprofile->User_AttenId;
$User_Name[$objuserprofile->User_Id]=$objuserprofile->User_Name;
$grade[$objuserprofile->User_Id]=$objuserprofile->User_Grade;
 //echo "name-".$name[$objuserprofile->User_Id];
$dept[$objuserprofile->User_Id]=$objuserprofile->User_Department;
$nationality[$objuserprofile->User_Id]=$objuserprofile->User_Nationality;

$workdur[$objuserprofile->User_Id]=$objuserprofile->User_WorkHours;
$e++;
}
echo "<table class='tableaddp' border='1' cellspacing='0' cellpadding='0'>";

echo "<tr><td colspan=$col2><form name='attendancelist' action='attendancelist.php' method='post'><table class='tableaddp'><tr><td>From <input type='date' name='datefrom' value='$datefrom'/>&nbsp;To&nbsp;<input type='date' name='dateto' value='$dateto'/>";
echo "<select name='searchterm'>";
echo "<option value=''>All</option>";
echo "<option value='User_Name'>Employee</option>";
echo "<option value='User_Department'>Department</option>";
echo "<option value='User_Nationality'>Nationality</option>";
echo "</select>";
echo "<input type='text' name='searchname' value='".$searchname."'/>";
echo "<input type='submit' value='search'/>";
echo"</td></tr></table></form></td></tr>";
echo "<tr><td>Sl</td><td>Name</td><td>Department</td>/";
foreach($dateRange as $dates)
{
echo "<td>$dates</td>";
}
echo "<td>Present(Days)</td><td>Friday</td><td>Absent(Days)</td><td>Deduction</td>";
echo "</tr>";

?>