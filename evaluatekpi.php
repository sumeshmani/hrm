<?php
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("ajax.php");

$kpi_id=$_REQUEST['hidKpi_Id'];


$qrykpimaster=mysql_query("select * from kpi_master where Kpi_Id='$kpi_id'");
$kpiexists=mysql_num_rows($qrykpimaster);

if($kpiexists)
{

 while($objkpimaster=mysql_fetch_object($qrykpimaster))
  {
   $EmployeeId=$objkpimaster->Kpi_UserId;
   $EmployeeDesignation=$objkpimaster->Kpi_Designation;
   $EmployeeGrade=$objkpimaster->Kpi_Grade;
   $EmployeeDepartment=$objkpimaster->Kpi_Department;
   $EmployeeYear=$objkpimaster->Kpi_Year;
   $EmployeeManager=$objkpimaster->Kpi_ManagerId;
   $EmployeeSupervisor=$objkpimaster->Kpi_SupervisorId;
   $kpi_MasterId=$objkpimaster->Kpi_Id;
   $groupprofile=$objkpimaster->Kpi_Profile;
   $Kpi_Status=$objkpimaster->Kpi_Status;
   $recommend=$objkpimaster->Kpi_Recommend;
  }
}

?>


<table><tr><td colspan="6">

<table class="tableaddp" border="1" width="700">
<tr><td colspan="3" align="center" class="rows">A. PERSONAL INFORMATION</td></tr>
<tr><td class="row1" width="50">A.1.0</td><td class="row1" width="280">Name Of the Employee</td><td class="row1" width="310">

<?php echo $fullemployee_list[$EmployeeId];?></td></tr>
<tr><td class="row1" width="50">A.2.0</td><td class="row1" width="280">Designation</td><td class="row1" width="310"><?php echo"$EmployeeDesignation";?></td></tr>
<tr><td class="row1" width="50">A.3.0</td><td class="row1" width="280">Grade</td><td class="row1" width="310"><?php echo"$EmployeeGrade";?></td></tr>
<tr><td class="row1" width="50">A.4.0</td><td class="row1" width="280">Department</td><td class="row1" width="310"><?php echo"$EmployeeDepartment";?></td></tr>
<tr><td class="row1" width="50">A.5.0</td><td class="row1" width="280">Year Of Assesment</td><td class="row1" width="310"><?php echo"$EmployeeYear";?></td></tr>
<tr><td class="row1" width="50">A.6.0</td><td class="row1" width="280">Immediate Supervisor name</td><td class="row1" width="310">
<input type="hidden" name="Employee_SupervisorId" value='<?php echo $EmployeeSupervisor;?>' readonly="readonly"/>
<?php echo $fullemployee_list[$EmployeeSupervisor];?></td></tr>
<tr><td class="row1" width="50">A.7.0</td><td class="row1" width="280">Manager Name</td><td class="row1" width="310">
<input type="hidden" name="Employee_ManagerId" value="<?php echo $EmployeeManager; ?>" />
<?php echo $fullemployee_list[$EmployeeManager]; ?></td></tr>

 
 

</table>


<?php


 $qrykpigroup=mysql_query("select * from kpi_group where kpi_GroupProfile='$groupprofile' or kpi_GroupProfile=0 order by kpi_GroupVersion");
 while($objkpigroup=mysql_fetch_object($qrykpigroup))
 {
 $groupparentid[$objkpigroup->kpi_GroupParentId]=$objkpigroup->kpi_GroupParentId;
 $groupversion[$objkpigroup->kpi_GroupParentId]=$objkpigroup->kpi_GroupVersion;
 if($objkpigroup->kpi_GroupVersion=="B.1")
 {
 $remarks=$objkpigroup->kpi_GroupRemarks;
 }
 if($objkpigroup->kpi_GroupVersion=="B.2")
 {
 $remarksb2=$objkpigroup->kpi_GroupRemarks;
 }
 }



$qrykpidetails=mysql_query("select * from kpi_details where Kpi_MasterId='$kpi_id' order by Kpi_SlNo");
 $kpigranttotal=0;
 echo "<form name='kpivaluation' action='kpidetailsvaluation.php' method='post'><table class='table' border='1' width='700' cellspacing='0'>";
  echo "<tr><td colspan='12' align='center' class='subhead'>B- Measurable Attributes-KEY PERFORMANCE INDICATORS - KPI";
  echo "<input type='hidden' name='Kpi_MasterId' value='$kpi_id'/></td></tr>";
  echo "<tr><td colspan='12' align='left' class='subhead'>B.1- PERFORMANCE - $remarks</td></tr>";
 
 $s=0;
 $x=0;
 $y=0;
 $c=0;
 $scoreachieved=0;
 $style="style='width:50px' step='0.5' min='0'";
 while($objkpidetails=mysql_fetch_object($qrykpidetails))
		{		
		$version=$objkpidetails->Kpi_SlNo;
		$kpi_detailsid=$objkpidetails->Kpi_Id;
		if ($objkpidetails->Kpi_TotalScore==0)
		{
		 if (strpos($version,"C")===0)
		  {
		   
		    echo "<tr><td colspan='12' align='center'>$objkpidetails->Kpi_SlNo-$objkpidetails->Kpi_Details</td></tr>";
			echo "<tr><td colspan='2' rowspan='2'></td><td rowspan='2'>Score</td><td colspan='2'>Dept.Manager</td><td colspan='2'>Admin Manager</td><td colspan='2'>G.M</td><td rowspan='2'>SCORE</td></tr>";
			echo "<tr><td>Max</td><td>Gained</td><td>Max</td><td>Gained</td><td>Max</td><td>Gained</td></tr>";
		  }
		  else
		  {
		   echo "<tr><td colspan='12' align='center'>$objkpidetails->Kpi_SlNo-$objkpidetails->Kpi_Details</td></tr>"; 
		  }
		 
		 echo "</tr>";		
		}
		elseif (strpos($version,"B.1")===0)
		{
		 if($x==0){ echo "<tr><td class='row' rowspan='2'>Sl</td><td class='row' rowspan='2'>KPI DETAILS</td><td class='row' rowspan='2'>Score</td>";
		 echo "<td colspan='3'>1st half</td><td colspan='3'>2nd half</td>";
		 echo "<td rowspan='2'>Remarks</td>";
		 echo "<td>Score</td></tr>";
		 echo "<tr><td width='5'>% Done</td><td width='5'>Score</td><td width='5'>Given Score</td><td width='5'>% Done</td><td width='5'>Score</td><td width='5'>Given Score</td></tr>";}
        include("kpievaluationsubpage.php");
		$x++;
		}
		elseif (strpos($version,"B.2")===0)
		{
		 if($y==0)
		  {echo "<tr><td colspan='12' align='left' class='subhead'>B.2- COMPETANCY - $remarksb2</td></tr>";}
		  include("kpievaluationsubpage.php");
		  echo "</tr>";	
		  $y++;
		  
		}
		elseif (strpos($version,"B.")===0)
		{
		echo "<tr><td>$version</td><td width='200'>$objkpidetails->Kpi_Details</td><td>$objkpidetails->Kpi_TotalScore</td>";
		 if($User_Id==$EmployeeSupervisor or $User_Id==$EmployeeManager)
		 {
		 echo "<td colspan='7'><textarea name='Remarks".$kpi_detailsid."' rows='2' cols='70' placeholder='Remarks'>$objkpidetails->Kpi_Remarks1</textarea></td>";
		echo "<td><input type='number' $style name='Kpi_FinalTotalScore".$kpi_detailsid."' value='$objkpidetails->Kpi_FinalTotalScore' max='$objkpidetails->Kpi_TotalScore'/></td>";
		 }
		 else
		 {
		echo "<td colspan='7'><textarea name='Remarks".$kpi_detailsid."' rows='2' cols='70' placeholder='Remarks' readonly>$objkpidetails->Kpi_Remarks1</textarea></td>";
		echo "<td><input type='text' size='1' width='1' name='Kpi_FinalTotalScore".$kpi_detailsid."' value='$objkpidetails->Kpi_FinalTotalScore' readonly/></td>";
		}
		echo "</tr>";
		}
		elseif (strpos($version,"C.")===0)
		{
		$max1=round($objkpidetails->Kpi_TotalScore*.4666,1);
		$max2=round($objkpidetails->Kpi_TotalScore*.2,1);
		$max3=$objkpidetails->Kpi_TotalScore-$max1-$max2;
		echo "<tr><td>$version</td><td width='200'>$objkpidetails->Kpi_Details</td><td>$objkpidetails->Kpi_TotalScore</td><td>$max1</td>";
		if($User_Id==$EmployeeSupervisor or $User_Id==$EmployeeManager)
		 {
		 echo "<td><input type='number' $style name='deptmanscore".$kpi_detailsid."' value='$objkpidetails->Kpi_DeptMangerScore1' max='$max1'/></td>";
		 }
		 else
		 {
		 		 echo "<td><input type='number' $style name='deptmanscore".$kpi_detailsid."' value='$objkpidetails->Kpi_DeptMangerScore1' max='$max1' readonly/></td>";
		 }
		 
	    
		echo "<td>$max2</td>";
		if($adminstatus=='Y')
		{
	    echo "<td><input type='number' $style name='adminmanscore".$kpi_detailsid."' value='$objkpidetails->Kpi_AdminMangerScore' max='$max2'/></td>";
		}
		else
		{
		echo "<td><input type='number' $style name='adminmanscore".$kpi_detailsid."' value='$objkpidetails->Kpi_AdminMangerScore' max='$max2' readonly/></td>";
		}
		echo "<td>$max3</td>";
	    echo "<td><input type='number' $style name='gmscore".$kpi_detailsid."' value='$objkpidetails->Kpi_GMScore'  max='$max3'/></td>";
		echo "<td><input type='number' $style name='Kpi_FinalTotalScore".$kpi_detailsid."' value='$objkpidetails->Kpi_FinalTotalScore'/></td>";
		echo "</tr>";
		$c++;
		}		
		else
		{
		echo "<tr><td>$version</td><td width='5'>$objkpidetails->Kpi_Details</td><td>$objkpidetails->Kpi_TotalScore</td>";
    	//echo "<td><input type='number' size='1' width='1' name='txtOverallScore[$kpi_detailsid]'/></td>";
		echo"</tr>";
		}
		
		  $s++;
		  
   		  $slno=$objkpidetails->Kpi_SlNo;
		  
          $scoreachieved=$objkpidetails->Kpi_FinalTotalScore+$scoreachieved;		  
          $kpigranttotal=$kpigranttotal+$objkpidetails->Kpi_TotalScore;
		 }

 echo "<tr><td colspan='9' rowspan='2'>Manager Recommendation:<textarea name='Kpi_Recommend' rows='2' cols='50' placeholder='I Find him very good and he need to be....'>$recommend</textarea></td>";
 echo "<td>Achieved Score</td><td>$scoreachieved</td>";
 echo "</tr>";
 echo "<tr>";
 echo "<td>total Score</td><td>$kpigranttotal</td>";
 echo "</tr>";
 echo "<tr><td colspan='12' align='center'><input type='submit' value='Save'/></td></tr>";

 echo "</table>";

?>

</form>
<script language="JavaScript">
function viewMore(div) {
obj = document.getElementById(div);
obj1 = document.getElementById("1" + div);
col = document.getElementById("x" + div);
col1 = document.getElementById("1x" + div);
if (obj.style.display == "none") {
obj.style.display = "block";
col.innerHTML = "comments";
obj1.style.display = "block";
col1.innerHTML = "comments";
} else {
obj.style.display = "none";
col.innerHTML = "comments";
obj1.style.display = "none";
col1.innerHTML = "comments";
}
}
</script> 


<?php
include("menubar2.php");
include("bottombar.php");
?>