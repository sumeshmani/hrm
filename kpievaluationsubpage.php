<?php
         echo "<tr><td>$version</td><td width='200' class='rows'>$objkpidetails->Kpi_Details</td><td>$objkpidetails->Kpi_TotalScore</td>";	
		 echo "<td><input type='number' $style max='100' name='perdone1".$kpi_detailsid."' size='1' value='$objkpidetails->Kpi_PerDone1'/></td>";
 		 echo "<td>$objkpidetails->Kpi_SelfScore_1</td>";
		 echo "<input type='hidden' name='kpidetailsid' value='".$kpi_detailsid."'>";
		 if($User_Id==$EmployeeSupervisor or $User_Id==$EmployeeManager)
		 {
		 echo "<td><input type='text' name='finalscore1".$kpi_detailsid."' max='$objkpidetails->Kpi_TotalScore' value='$objkpidetails->Kpi_FinalScore1' size='1' required/></td>";
		 }
		 else
		 {
		 echo "<td><input type='text' name='finalscore1".$kpi_detailsid."' max='$objkpidetails->Kpi_TotalScore' value='$objkpidetails->Kpi_FinalScore1' size='1' readonly/></td>";
		 }
		 echo "<td><input type='number' $style max='100' name='perdone2".$kpi_detailsid."' max='100'  size='1' value='$objkpidetails->Kpi_PerDone2'/></td>";
 		 echo "<td>$objkpidetails->Kpi_SelfScore_2</td>";
		  if($User_Id==$EmployeeSupervisor or $User_Id==$EmployeeManager)
		 {
		 echo "<td><input type='text' name='finalscore2".$kpi_detailsid."' value='$objkpidetails->Kpi_FinalScore2'  size='1' required/></td>";
		 }
		 else
		 {
		 echo "<td><input type='text' name='finalscore2".$kpi_detailsid."' value='$objkpidetails->Kpi_FinalScore2'  size='1' readonly/></td>";
		 }
		 echo "<td><a href='javascript:viewMore(1".$s.");' id='x1".$s."'>Emp. Comments</a> </br>";
		 
		 if($EmployeeSupervisor==$EmployeeManager){}
		 else
		 {
		 echo "<a href='javascript:viewMore(2".$s.");' id='x2".$s."'>Sup. Comments</a></br>";
		 }
		 echo "<a href='javascript:viewMore(3".$s.");' id='x3".$s."'>Man. Comments</a></td>";
		 //echo "<td colspan='8'><a href='kpidetailsvaluation.php?kpiid=$kpi_detailsid&MasterId=$kpi_id'>update values</a></td>";
		 //echo "<td><input type='number' size='1' width='1' name='txtOverallScore[$kpi_detailsid]'/></td>";
		  echo "<td>$objkpidetails->Kpi_FinalTotalScore</td>";
		 echo"</tr>";

		 $qryremarks=mysql_query("select * from kpi_remarks where kpi_detailsid='$kpi_detailsid'");
		 $NUMREMARKS=mysql_num_rows($qryremarks);
		 if($NUMREMARKS>0)
		 {
		 while($objremarks=mysql_fetch_object($qryremarks))
		 {
			 if($objremarks->kpi_half=='firsthalf')
			 {
			 $currentstatus1=$objremarks->kpi_currentstatus;
			 $actionsplanned1=$objremarks->kpi_actionsplanned;
			 $actionsplan1=$objremarks->kpi_actionsplan;
			 $achievedate1=$objremarks->kpi_whentoachieve;
			 $kpi_supervisorremarks1=$objremarks->kpi_supervisorremarks;
			 }
			 if($objremarks->kpi_half=='secondhalf')
			 {
			 $currentstatus2=$objremarks->kpi_currentstatus;
			 $actionsplanned2=$objremarks->kpi_actionsplanned;
			 $actionsplan2=$objremarks->kpi_actionsplan;
			 $achievedate2=$objremarks->kpi_whentoachieve;
			 $kpi_managerremarks2=$objremarks->kpi_supervisorremarks;
			 }
		 }
		 }
		 else
	     {
			 $currentstatus1='';
			 $actionsplanned1='';
			 $actionsplan1='';
			 $achievedate1=''; 
			 $currentstatus2='';
			 $actionsplanned2='';
			 $actionsplan2='';
			 $achievedate2='';
			 $kpi_supervisorremarks1='';
			 $kpi_supervisorremarks2='';
			 $kpi_managerremarks1='';
			 $kpi_managerremarks2='';
			 
		 }
		 
		 echo "<tr><td colspan='6'><p id='1".$s."' style='display:none'>";
		 
		 echo "";
		 echo "<select name='kpireviewterm1".$kpi_detailsid."'>";
		 echo "<option value='firsthalf'>First Half</option>";
		 echo "</select></br>";
		 echo "Current Status:</br><textarea name='currentstatus1".$kpi_detailsid."' rows='4' cols='50'>$currentstatus1</textarea></br>";
		 echo "Action Planned:</br><textarea name='actionsplanned1".$kpi_detailsid."' rows='4' cols='50'>$actionsplanned1</textarea></br>";
		 echo "Future Plan:</br><textarea name='futureplan1".$kpi_detailsid."' rows='4' cols='50'>$actionsplan1</textarea></br>";
		 echo "When to achieve:</br><textarea name='whentoachieve1".$kpi_detailsid."' rows='4' cols='50'>$achievedate1</textarea>";
		 echo "</p>";
		 echo "</td>";
		 echo "<td colspan='6'><p id='11".$s."' style='display:none'>";
		 
		 echo "<select name='kpireviewterm2".$kpi_detailsid."'>";
		 echo "<option value='secondhalf'>Second Half</option>";
		 echo "</select></br>";
		 echo "Current Status:</br><textarea name='currentstatus2".$kpi_detailsid."' rows='4' cols='50'>$currentstatus2</textarea></br>";
		 echo "Action Planned:</br><textarea name='actionsplanned2".$kpi_detailsid."' rows='4' cols='50'>$actionsplanned2</textarea></br>";
		 echo "Future Plan:</br><textarea name='futureplan2".$kpi_detailsid."' rows='4' cols='50'>$actionsplan2</textarea></br>";
		 echo "When to achieve:</br><textarea name='whentoachieve2".$kpi_detailsid."' rows='4' cols='50'>$achievedate2</textarea>";
		 echo "</p></td></tr>";
		 if($User_Id==$EmployeeSupervisor or $User_Id==$EmployeeManager)
		 {
		 echo "<tr><td colspan='6'><p id='2".$s."' style='display:none'>Supervisor Remarks:<textarea name='SRemarks1".$kpi_detailsid."' rows='4' cols='50' placeholder='Supervisor Feedback'>$kpi_supervisorremarks1</textarea>";
		 echo "</p></td><td colspan='6'>";
		 echo "<p id='2".$s."' style='display:none'>Supervisor Remarks:<textarea name='SRemarks2".$kpi_detailsid."' rows='4' cols='50' placeholder='Supervisor Feedback'>$kpi_supervisorremarks2</textarea>";
		 echo "</p>";
		 echo "</td>";		 
		  echo "<td colspan='6'><p id='3".$s."' style='display:none'>Manager Remarks:<textarea name='MRemarks1".$kpi_detailsid."' rows='4' cols='50' placeholder='Manager Feedback'>$kpi_managerremarks1</textarea>";
		 echo "</p></td><td colspan='6'>";
		 echo "<p id='2".$s."' style='display:none'>Supervisor Remarks:<textarea name='MRemarks2".$kpi_detailsid."' rows='4' cols='50' placeholder='Manager Feedback'>$kpi_managerremarks2</textarea>";
		 echo "</p>";
		 echo "</td></tr>";	
		 }
		 else
		 {
		  echo "<tr><td colspan='20'><p id='2".$s."' style='display:none' align='top'>Supervisor Remarks<textarea name='Remarks2".$kpi_detailsid."' rows='2' cols='100' readonly placeholder='Supervisor Feedback'>$objkpidetails->Kpi_Remarks2</textarea></td>";
		 echo "<tr><td colspan='20'><p id='3".$s."' style='display:none'>Manager Remarks<textarea name='Remarks3".$kpi_detailsid."' rows='2' cols='100' placeholder='Manager Feedback' readonly>$objkpidetails->Kpi_Remarks3</textarea></td>";
		 }
		 
		 ?>