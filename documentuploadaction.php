<?php
session_start();
$User_Id=$_SESSION['User_Id'];
include("connectivity.php");
include "Hijri_GregorianConvert.class";

$DateConv1=new Hijri_GregorianConvert;
$DateConv2=new Hijri_GregorianConvert;

//echo $DateConv->GregorianToHijri($date,$format);
$Employee_Id=$_REQUEST['Employee_Id'];
$EmployeeName=$_REQUEST['EmployeeName'];
$Document_Type=$_REQUEST['Document_Type'];
$Document_No=$_REQUEST['Document_No'];
$arenconv=$_REQUEST['entoar'];
$hidStatus=$_REQUEST['hidStatus'];
if($arenconv=='en')
{
$format="MM/DD/YYYY";
if($_REQUEST['Issue_DateEn']=="")
{

}
{
$Issue_DateEn=$_REQUEST['Issue_DateEn'];
$Issue_DateAr=$DateConv1->GregorianToHijri($Issue_DateEn,$format);
//echo"$Issue_DateEn";
$Issue_DateEn1=explode("/",$Issue_DateEn);
$Issue_DateEn=$Issue_DateEn1[2]."-".$Issue_DateEn1[0]."-".$Issue_DateEn1[1];

$Issue_DateAr1=explode("-",$Issue_DateAr);
$Issue_DateAr=$Issue_DateAr1[2]."-".$Issue_DateAr1[1]."-".$Issue_DateAr1[0];
}
$Expiry_DateEn=$_REQUEST['Expire_DateEn'];
$Expiry_DateAr=$DateConv2->GregorianToHijri($Expiry_DateEn,$format);
$Expiry_DateEn1=explode("/",$Expiry_DateEn);
$Expiry_DateEn=$Expiry_DateEn1[2]."-".$Expiry_DateEn1[0]."-".$Expiry_DateEn1[1];

$Expiry_DateAr1=explode("-",$Expiry_DateAr);
$Expiry_DateAr=$Expiry_DateAr1[2]."-".$Expiry_DateAr1[1]."-".$Expiry_DateAr1[0];
}
elseif($arenconv=='ar')
{
$format="YYYY/MM/DD";
if($_REQUEST['Issue_DateAr']=="")
{}else
{
$Issue_DateAr=$_REQUEST['Issue_DateAr'];
$Issue_DateEn=$DateConv1->HijriToGregorian($Issue_DateAr,$format);
//echo"$Issue_DateEn";
$Issue_DateEn1=explode("-",$Issue_DateEn);
$Issue_DateEn=$Issue_DateEn1[2]."-".$Issue_DateEn1[1]."-".$Issue_DateEn1[0];
}
$Expiry_DateAr=$_REQUEST['Expire_DateAr'];
$Expiry_DateEn=$DateConv2->HijriToGregorian($Expiry_DateAr,$format);
$Expiry_DateEn1=explode("-",$Expiry_DateEn);
$Expiry_DateEn=$Expiry_DateEn1[2]."-".$Expiry_DateEn1[1]."-".$Expiry_DateEn1[0];
}
else
{
//echo "date not selected";

}
function filename_safe($name) { 
   $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|','\''); 
   return str_replace($except, '', $name); 
} 
//echo"$Employee_Id,$EmployeeName,$Document_Type,$Document_No,$Issue_DateEn,$Expiry_DateEn,$Issue_DateAr,$Expiry_DateAr";
if($hidStatus=="Renew")
{
$hidHrId=$_REQUEST["hidHrId"];
mysql_query("update hr_document set Document_Status='N' where Document_Id='$hidHrId'");
}
else
{}
mysql_query("insert into hr_document(Document_UserId,Document_UserName,Document_Type,Document_No,Document_IssueDate_En,Document_ExpireDate_En,Document_IssueDate_Ar,Document_ExpireDate_Ar,UploadedBy) values ('$Employee_Id','$EmployeeName','$Document_Type','$Document_No','$Issue_DateEn','$Expiry_DateEn','$Issue_DateAr','$Expiry_DateAr','$User_Id')");
//echo "insert into hr_document(Document_UserId,Document_UserName,Document_Type,Document_No,Document_IssueDate_En,Document_ExpireDate_En,Document_IssueDate_Ar,Document_ExpireDate_Ar) values ('$Employee_Id','$EmployeeName','$Document_Type','$Document_No','$Issue_DateEn','$Expiry_DateEn','$Issue_DateAr','$Expiry_DateAr')";
 $id=mysql_insert_id();
     $DrgName=$_FILES["Document_Image"]["name"];
	 if($DrgName)
	 {
   $DrgName=explode(".",$DrgName);
   $ext=$DrgName[count($DrgName)-1];
  $DrgName=$id."s".$Employee_Id."s".$Document_Type.".$ext";
   $DrgName=filename_safe("$DrgName");
  $DrgType=$_FILES["Document_Image"]["type"];
  $DrgSize=$_FILES["Document_Image"]["size"]; 
  $DrgTmp=$_FILES["Document_Image"]["tmp_name"];
  $newdire="upload\\$DrgName";
 $error=$_FILES["Document_Image"]["error"];
  move_uploaded_file($DrgTmp,$newdire);
   }
   else
   {
   
   }
mysql_query("update hr_document set Document_Image='$DrgName' where Document_Id='$id'");

//echo "update hr_document set Document_Image='$DrgName' where Document_Id='$id' $DrgTmp $error $DrgSize";
header("location:userlist.php");


?>
