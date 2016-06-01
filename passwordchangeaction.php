<?php
session_start();
include("connectivity.php");
$UserId=$_SESSION['User_Id'];
$OldPassword=$_REQUEST['txtold'];
$NewPassword=$_REQUEST['txtnew'];
$qrypass=mysql_query("select * from ncr_user where User_Id='$UserId' and User_Password=password('$OldPassword')");
//echo"select * from ncr_user where User_Id='$UserId' and User_Password=password('$OldPassword')";
$numpass=mysql_num_rows($qrypass);
if($numpass)
{
mysql_query("update ncr_user set User_Password=password('$NewPassword') where User_Id=$UserId");
//echo"update ncr_user set User_Password=password('$NewPassword') where User_Id=$UserId";
header("location:index.php");
}
else 
{
header("location:changepassword.php?pass=1");
}
?>