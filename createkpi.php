<?php
session_start();
$Department=$_SESSION['Department'];
$Designation=$_SESSION['Designation'];
$UserName=$_SESSION['User_Name'];
$User_Id=$_SESSION['User_Id'];

include("ajax.php");
include("connectivity.php");


?>