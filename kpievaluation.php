<?php
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("ajax.php");
$employee_idsarray=implode(',',$employee_ids);
//echo "arraY=".$employee_idsarray;
$qrykpilist=mysql_query("select * from kpi_master where kpi_Status>0 and Kpi_UserId IN($employee_idsarray)");

echo "<table border='1'>";
echo "<tr><td  colspan='10'><h1>KPI Evaluation Report</h1></td>";
echo "<tr><td>Sl</td><td>Name</td><td>Designation</td><td>Grade</td><td>Department</td><td>Year</td><td>Supervisor Name</td><td>Manager Name</td><td>Details</td></tr>";
$sl=1;
 while($objkpimaster=mysql_fetch_object($qrykpilist))
  {
   echo"<tr><td>$sl</td>";
   $userid=$objkpimaster->Kpi_UserId;
   echo "<td>$fullemployee_list[$userid]</td>";
   echo "<td>$objkpimaster->Kpi_Designation</td>";
   echo "<td>$objkpimaster->Kpi_Grade</td>";
   echo "<td>$objkpimaster->Kpi_Department</td>";
   echo "<td>$objkpimaster->Kpi_Year</td>";
    $supervisorid=$objkpimaster->Kpi_SupervisorId;
   echo "<td>$fullemployee_list[$supervisorid]</td>";
   $managerid=$objkpimaster->Kpi_ManagerId;
   echo "<td>$fullemployee_list[$managerid]</td>";
   echo"<td><a href='evaluatekpi.php?hidKpi_Id=$objkpimaster->Kpi_Id'>Details</a></td>";
  
   echo "</tr>";
   $sl++;
  }
  
 echo"</table>";
?>
