<?php
session_start();
include("connectivity.php");
session_destroy();
header("location:index.php?login=logout");
?>