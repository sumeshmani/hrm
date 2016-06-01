<?php
include("connectivity.php");

?>

<?php
$entoar=$_REQUEST['entoar'];
if($entoar=="ar")
{
echo "<table class='tableadd'><tr><td>Issue Date(Ar)</td><td>";
echo '<input id="arDatefrom" type="text" name="Issue_DateAr" required/></td>';
echo "</tr>";
echo "<tr><td>Expire Date(Ar')</td><td><input id='arDateto' type='text' name='Expire_DateAr' required/></td></tr></table>";
}
else
{
echo "<table><tr><td>Issue Date(En)</td><td><input id='startpicker' type='text' name='Issue_DateEn'/>";
echo "</td></tr>";
echo "<tr><td>Expire Date(En)</td><td><input id='endpicker' type='text' name='Expire_DateEn'/>";
echo "</td></tr></table>";
}


?>
