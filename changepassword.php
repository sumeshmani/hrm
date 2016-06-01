<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
?>
<script language="javascript">
function checkpassword()
{
var a=document.passchange;
if(a.txtold.value=="")
{
alert("please enter the old password");
a.txtold.focus();
return false;
}
if(a.txtnew.value=="")
{
alert("please enter the new password");
a.txtnew.focus();
return false;
}
if(a.txtnew1.value=="")
{
alert("please confirm the new confirm password");
a.txtnew.focus();
return false;
}
if(a.txtold.value.length<1)
{
alert("password must contain morethan 1 letters");
return false;
}
if(a.txtnew.value.length<6)
{
alert("password must contain morethan 6 letters");
a.txtnew.focus();
return false;
}
if(a.txtnew1.value.length<6)
{
alert("password must contain morethan 6 letters");
a.txtnew1.focus(0);
return false;
}
if(a.txtnew.value!=a.txtnew1.value)
{
alert("Please enter the correct pasword");
a.txtnew.value="";
a.txtnew1.value="";
a.txtnew.focus();
return false;
}
}
</script>
<form action="passwordchangeaction.php" name="passchange" method="post">
<table class="tableaddp" align="center" v>
<tr><td colspan="2" align="center"><h1> Change Password</h1></td></tr>
<tr><td>OldPassword</td><td><input type="password" name="txtold"/></td></tr>
<tr><td>NewPassword</td><td><input type="password" name="txtnew"/></td></tr>
<tr><td>ConfirmPassword</td><td><input type="password" name="txtnew1"/></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="change" onclick="return checkpassword()"/></td></tr>
</table>
</form>

<?
include("bottombar.php");
?>
