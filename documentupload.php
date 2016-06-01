<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("ajax.php");
$qry=mysql_query("select * from ncr_user where User_Name='$UserName'");
$Employee_Id=$_REQUEST['employeeid'];
$Document_Type=$_REQUEST['type'];
$types=array(0 => 'Iqama',1 => 'Passport',2 => 'Insurance',3 => 'Offer');
$Status=$_REQUEST['hidStatus'];
if($Status=="view")
{
$qryhrd=mysql_query("select * from hr_document where Document_UserId='$Employee_Id' and Document_Type='$Document_Type' and Document_Status='Y'");
//echo "select * from hr_document where Document_UserId='$Employee_Id' and Document_Type='$Document_Type' and Document_Status='Y'";
 while($objhrd=mysql_fetch_object($qryhrd))
  {
    $Document_No=$objhrd->Document_No;
	$IssueDateEn=$objhrd->Document_IssueDate_En;
	$IssueDateAr=$objhrd->Document_IssueDate_Ar;
	$ExpireDateEn=$objhrd->Document_ExpireDate_En;
	$ExpireDateAr=$objhrd->Document_ExpireDate_Ar;
	$image=$objhrd->Document_Image;
	$hrid=$objhrd->Document_Id;
  }
}
?>

<form name='documentupload' action='documentuploadaction.php' enctype="multipart/form-data" method='post'>
<table class="tableaddp">
<tr><td colspan="2"><h2> Document Upload</h2></td></tr>
<tr><td>Employee Name</td><td><input type="hidden" name="Employee_Id" value="<?php echo $Employee_Id;?>"/><input type="text" name='EmployeeName' value="<?php echo $fullemployee_list[$Employee_Id];?>"/></td></tr>
<tr><td>Document Type</td><td><select name="Document_Type">
<?php
foreach($types as $type)
{
 if($Document_Type==$type)
 {
   echo"<option value='$type' selected>$type</option>";
 }
 else
 {
  //echo"<option value='$type'>$type</option>";
 }
}
?>
</select></td></tr>
<tr><td>Document Number</td><td><input type="text" name='Document_No' value="<?php echo "$Document_No"; ?>" required/></td></tr>
<tr><td colspan="2"><select id="entoar" name="entoar" onchange="return ShowMe('entoar')" style="visibility:hidden">
<?php
if($Document_Type=='Iqama' or $Document_Type=='Passport')
{
echo "<option value='en'>English Date</option>";
echo"<option value='ar' selected>Arabic Date</option>";
}
else
{
echo "<option value='en' selected>English Date</option>";
echo"<option value='ar'>Arabic Date</option>";
}
?>
</select>


<?php
if($Document_Type=='Iqama' or $Document_Type=='Passport')
{
//echo "<div id='ardate' name='ardate'><table>";
echo "<tr><td>Issue Date(Ar)</td><td>";
echo '<input id="arDatefrom" type="text" name="Issue_DateAr" value="'.$IssueDateAr.'" /></td>';
echo "</tr>";
echo "<tr><td>Expire Date(Ar)</td><td><input id='arDateto' type='text' name='Expire_DateAr'  value='".$ExpireDateAr."'/></td></tr>";
if($Status=="view")
{
echo "<tr><td>Expire Date(En)</td><td><font color='red'>".$ExpireDateEn ."</font>(Y-M-d)</td></tr>";
}
//echo "</table>";
//echo"</div>";
}
else
{
//echo "<div class='en box' name='endate'><table>";
echo "<tr><td>Issue Date(En)</td><td><input id='startpicker' type='text' name='Issue_DateEn'  value='".$IssueDateEn."'/>";
echo "</td></tr>";
echo "<tr><td>Expire Date(En)</td><td><input id='endpicker' type='text' name='Expire_DateEn'  value='".$ExpireDateEn."'/>";
echo "</td></tr>";
//echo "</table></div>"";
}
?>

</td></tr>
<?php
if($image=="")
{
if($Status=="view")
{
echo "<tr><td colspan='2'><font color='red'>Image not uploaded upload now</font></td></tr>";
}
echo "<tr><td>Image</td><td><input type='file' name='Document_Image'/></td></tr>";
}
else
{
$ext=explode(".",$image);
$exts=strtoupper($ext[count($ext)-1]);
if($exts=="JPEG" or $exts=="JPG")
{
echo "<tr><td colspan='2'><a href='upload/$image' target='_blank'><img src='upload/$image' width=400 height=200/></a></td></tr>";
}
else
{
echo "<tr><td colspan='2'><a href='upload/$image' target='_blank'>View/Download</a></td></tr>";
}
echo "<tr><td colspan='2'>Update Image<input type='file' name='Document_Image'/></td></tr>";
}
?>
<tr><td></td><td></td></tr>
<?php
if($Status=="view")
{

echo "<tr><td colspan='2' align='center'><input type='hidden' name='hidStatus' value='Renew'/><input type='hidden' name='hidImage' value='$image'/><input type='hidden' name='hidHrId' value='$hrid'/> <input type='submit' value='save' onclick='return checkform()'/><a href='userlist.php'>Back</a></td></tr>";
}
else
{
echo "<tr><td colspan='2' align='center'> <input type='submit' value='Update/Renew' onclick='return checkform()'/></td></tr>";
}
?>
<tr><td>

</table>
</form>
<script language="javascript1.1">
function checkform()
{
var a=document.documentupload;
var i=document.documentupload.entoar.value;

if(i=="ar")
{
 if(a.Issue_DateAr.value=="")
 {
  alert ("select Issue date-Arabic");
  //a.Issue_DateEn.value="";
  //a.Issue_DateAr.focus();
  return false;
 }
 if(a.Expire_DateAr.value=="")
 {
  alert ("select Document Expire date-Arabic");
  //a.Expire_DateEn.value="";
  //a.Expire_DateAr.focus();
  return false;
 }
 return true;
}
if(i=="en")
{
 if(a.Issue_DateEn.value=="")
 {
  alert ("select Issue date-English");
  //a.Issue_DateAr.value="";
  //a.Issue_DateEn.focus();
  return false;
 }
 if(a.Expire_DateEn.value=="")
 {
  alert ("select Document Issue date-English");
  //a.Expire_DateAr.value="";
  //a.Expire_DateEn.focus();
  return false;
 }
 return true;
}
}
<?php
include("bottombar.php");
?>