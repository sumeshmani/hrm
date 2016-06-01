<?php
include("connectivity.php");
$User_Id=$_REQUEST['user'];
//echo"User is $User_Id";
$qryemployeelist1=mysql_query("select * from ncr_user where User_ManagerId='$User_Id' or User_SupervisorId='$User_Id' or User_Id='$User_Id'");
while($objemployeelist1=mysql_fetch_object($qryemployeelist1))
{
	$employee_list1[$objemployeelist1->User_Id]=$objemployeelist1->User_Name;
}
$kpi_year=$_REQUEST['year'];
$kpi_name=$_REQUEST['name'];

include("functions.php");
echo "<table>";
echo "<tr><td>name is $kpi_name is & year is". $employee_list1[$kpi_name] ."<td></tr>";

echo"</table>";
?>