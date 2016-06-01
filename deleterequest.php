<?php
$hidId=$_REQUEST['hidId'];
include("connectivity.php");
mysql_query("delete from employee_request where ER_Id='$hidId'");

header("location:employeerequest.php");
?>