<?php 
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("ajax.php");
$qrykpidetails=mysql_query("select * from kpi_detailsmaster");
?>
<form name="kpidetails" action="kpidetailsmasteraction.php" method="post">
<table class="tableaddp" cellpadding="0" cellspacing="0" height="50px">
<tr><td colspan="2">KPI Details Master</td></tr>
<tr><td>KPI Details</td><td><textarea name="kpidetails" rows="4" cols="50"></textarea></td></tr>
<tr><td>Department</td><td><select name="kpidept">
  <?php
	foreach($dept_list as $Id=>$Name)
	{
	echo "<option value=".$Id.">".$Name."</option>";	
	}
	?>
    </select>    
</td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="save"></td></tr>
<tr><td colspan="2">
<table border="1">
<tr><td>KPI Details</td><td>Department</td></tr>
<?php
while($objkpidetails=mysql_fetch_object($qrykpidetails))
{
	echo "<tr>";
	echo "<td>".$objkpidetails->Kpi_Details."</td>";
	echo "<td>".$objkpidetails->Kpi_Dept."</td>";
	echo "</tr>";
}
?>
</table>
</td></tr>
</table>
</form>
<?php
include("menubar2.php");
include("bottombar.php");
?>