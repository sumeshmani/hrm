<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
//include("ajax.php");
//include("functions.php");
$qryallowance=mysql_query("select * from payroll_allowance");
?>
<form name='allowance_master' action='allowancemasteraction.php' method='post'>
<table class='tableaddp'>
<tr><td colspan='2'><h3><u>Allowance Master Form</u></h3></td></tr>
<tr><td class='reg1'>Allowance Name :</td><td>
<select name='allowance_name'>
<option value='Mobile'>Mobile</option>
<option value='Transportation'>Transportation</option>
<option value='HRA'>HRA</option>
<option value='Travel'>Travel</option>
<option value='Performance Allowance'>Performance Allowance</option>
<option value='Food Deduction'>Food Allowance</option>
<option value='Vacation Salary'>Vacation Salary</option>
<option value='HRA'>Leave Salary</option>
<option value='Personal Loan'>Personal Loan</option>
<option value='Food Deduction'>Food Deduction</option>
</select>
</td></tr>
<tr><td class='reg1'>Add / Deduct :</td><td align='left'><select id='allowanceadd' name='allowance_add'>
<option value='Add'>Add</option>
<option value='Deduct'>Deduct</option>
</select></td></tr>

<tr><td class='reg1'>Allowance Type :</td><td align='left'><select id='allowancestatus' name='allowance_status' onchange='return getchangedata()'>
<option value='Fixed'>Fixed</option>
<option value='Variable'>Variable</option>
</select></td></tr>
<tr><td class='reg1'>
Allowance Amount :</td><td><div id='allowancevalue' class='allowancevalue'>
<input type='text' size='50' name='allowance_value' placeholder='Enter Allowance Value for fixed'/>
</div>
</td></tr>

<tr><td class='reg1'>Allowance Schedule :</td><td align='left'><select name='allowance_duration'>
<option value='monthly'>Monthly</option>
<option value='half yearly'>Half yearly</option>
<option value='Yearly'>Yearly</option>
<option value='2 Years'>Once in 2 Years</option>
</select></td></tr>

<tr><td colspan='2' align='center'><input type='submit' value='save'>&nbsp;<input type='reset' value='cancel'></td></tr>
</table>
</form>
<table class='tableaddp' border='1'>
<tr><td>Allowance</td><td>Add/Deduct</td><td>Type</td><td>Value</td><td>Schedule</td></tr>
<?php
while($objallowance=mysql_fetch_object($qryallowance))
{
	echo "<tr>";
	echo "<td>".$objallowance->allowance_name."</td>";
	echo "<td>".$objallowance->allowance_add."</td>";
	echo "<td>".$objallowance->allowance_type."</td>";
	if($objallowance->allowance_type=='fixed')
	{
		echo "<td>".$objallowance->allowance_amount."</td>";
	}
	else
	{
		if($objallowance->allowance_amount=="")
		{
			echo "<td>".$objallowance->allowance_percentage."% of ".$objallowance->variable_dependent."</td>";
		}
		else
		{
			echo "<td>".$objallowance->allowance_amount." SAR as per ".$objallowance->variable_dependent."</td>";
		}
	}
	echo "<td>".$objallowance->allowance_schedule."</td>";
	echo "</tr>";
	
}
?>

</table>
<?php
include("menubar2.php");
?>
<script>

function GetXmlHttpObjectpay()
    { 
        var objXMLHttp=null;
        if (window.XMLHttpRequest)
            {
				// browser has native support for XMLHttpRequest object
                objXMLHttp=new XMLHttpRequest();
            }
        else if (window.ActiveXObject)
            {
				// try XMLHTTP ActiveX (Internet Explorer) version
                objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
        return objXMLHttp;
    }

function getchangedata() 
    {
xmlHttp=GetXmlHttpObjectpay()
if (xmlHttp==null) {
	alert ("Your browser does not support HTTP Request");
	return
                   } 
var allowancestatus = document.getElementById("allowancestatus").value;
if ((allowancestatus == null) || (allowancestatus == "")) return;
var url = "allowanceajax.php?allowancestatus=" + escape(allowancestatus);
xmlHttp.open("GET", url, true);
xmlHttp.onreadystatechange = updateProjectpay;
xmlHttp.send(null);
//check();
    }

function updateProjectpay() {
  if (xmlHttp.readyState == 4 || xmlHttp.readyState=="complete") { 
	document.getElementById("allowancevalue").innerHTML =xmlHttp.responseText;
  }
}
</script>
<?php
include("bottombar.php");
?>