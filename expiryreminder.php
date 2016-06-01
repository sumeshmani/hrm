<?php
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
include("functions.php");
include("ajax.php");
if($_REQUEST["doctype"])
{
$doctype=$_REQUEST["doctype"];
if($doctype=="documents")
{
}
else
{
$criteria= "and Document_Type='$doctype'";
}
}
$docs=array(0=>"documents",1=>"Iqama",2=>"Passport",3=>"Insurance");
if($_REQUEST["days"])
{
$days=$_REQUEST["days"];
}
else
{
$days=90;
}
$criteria=" Document_Status='Y' ".$criteria;

echo "<form name='search' action='expiryreminder.php' method='post'>";
echo"<table border='1'><tr><td colspan='8'><h2>Alerts</h2></td></tr>";
echo "<tr><td colspan='8'> show <select name='doctype'>";
foreach($docs as $doc)
{
if($doc==$doctype)
{
echo "<option value=$doc selected>$doc</option>";
}
else
{
echo "<option value=$doc>$doc</option>";
}
}
echo" </select>expired in <input type='text' size='1' name='days' value='$days'/> days <input type='submit' value='show'/></td></tr></form>";
echo "<tr><td>Sl no</td><td>Name</td><td>Document</td><td>Document No</td><td>Expiry Date(Ar)</td><td>Expiry Date(En)</td><td>Days Left</td><td>Image</td></tr>";
$qryhrdocs=mysql_query("select * from hr_document where $criteria");
$sl=1;
while($objhrdocs=mysql_fetch_object($qryhrdocs))
{
$expirydateen=$objhrdocs->Document_ExpireDate_En;
$userid=$objhrdocs->Document_UserId;
$now = time(); // or your date as well
     $your_date = strtotime($expirydateen);
     $datediff = $your_date - $now;
     $daysleft=floor($datediff/(60*60*24));
	 
//echo "<tr><td>$expirydateen</td></tr>";
$days=$days;
 if(strtotime($expirydateen) < strtotime('+ '.$days.' day') ) 
 {
  echo "<tr><td>$sl</td>";
  echo "<td>$fullemployee_list[$userid]</td>"; 
  echo "<td>$objhrdocs->Document_Type</td>";
    echo "<td>$objhrdocs->Document_No</td>"; 
  echo "<td>$objhrdocs->Document_ExpireDate_Ar</td>"; 
  echo "<td>$expirydateen</td>";
  echo "<td>$daysleft days left</td>";
  $Image=$objhrdocs->Document_Image;
  if($Image=="")
  {
  echo "<td>NA</td>";
  }
  else
  {    echo "<td><a href='upload\\$Image'>View</a></td>"; }
  echo"</tr>";
  $sl++;
 }

}
echo "</table>";
?>