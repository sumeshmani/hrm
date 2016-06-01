<?php
include("connectivity.php");

$kpidetails=$_REQUEST['kpidetails'];
$dept=$_REQUEST['kpidept'];

mysql_query("insert into kpi_detailsmaster(Kpi_Details,Kpi_Dept) values('$kpidetails','$dept')");
header("location:kpidetailsmaster.php");

?>