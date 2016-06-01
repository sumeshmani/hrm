<html>
<head>
<title>SFTL...WEB...HRM</title>
<link href="tstyle.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body onLoad="return namefocus()">
<script language="JavaScript">
function namefocus()
{
document.userlogin.txtLoginName.focus();
}
</script>
<?php
if(isset($_REQUEST['d']))
{
$dest=$_REQUEST['d'];
}
echo"<html><body><link href='tstyle.css' rel='stylesheet' type='text/css' /><table><tr><td width='200' height='100'></td><td></td><tr><td></td><td align='center' class='row'><table align='center' border='50' width='500' height='200' cellspacing='0' bordercolor='#E8E8E8'>"
?>
<form name="userlogin" action="logincheck.php" method="post">

<tr><td colspan="2">

<?php
if(isset($_REQUEST['login']))
{
$login=$_REQUEST['login'];
}
else
{
$login=0;
}
if($login=="1")
{
echo"<font color='red'>UserName or Password Incorrect</font>";
}
elseif($login=='logout')
{
echo"<font color='red'>You Are successfully logout</font>";
}
elseif($login=='expired')
{
echo"<font color='red'>Your Session Expired Please login Again</font>";
}
elseif($login=='R')
{
echo"<font color='red'>You are not allowed to login to this area</font>";
}
else
{
}
?></td></tr>
<tr><td>Login Name</td><td><input type="text" name="txtLoginName"/></td></tr>
<tr><td>Password</td><td><input type="password" name="txtPassword"/><input type="hidden" name="d" value="<?php echo $dest;?>"</td></tr>

<tr><td colspan="2" align="center"><input type="submit" value="Login"/></td></tr>

</form>
</td></tr>
</table>
</body>
</html>
