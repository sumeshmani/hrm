<?php
session_start();
include("connectivity.php");
$LoginName=$_REQUEST['txtLoginName'];
$Password=$_REQUEST['txtPassword'];
$qry=mysql_query("select * from ncr_user where User_LoginName='$LoginName' and User_Password=password('$Password')");
//echo"select * from ncr_user where Ncr_LoginName='$LoginName' and Ncr_Password=password('$Password')";
$num=mysql_num_rows($qry);
$obj=mysql_fetch_object($qry);
   $_SESSION['timeout']=time();
    $_SESSION['Status']=$obj->User_Status;
	$_SESSION['NcrStatus']=$obj->User_NcrStatus;
	$_SESSION['DesignStatus']=$obj->User_StandardDesignStatus;
	$_SESSION['User_Name']=$obj->User_Name;
	$_SESSION['User_Id']=$obj->User_Id;
	//$_SESSION['Email']=$obj->vEmail;
	$_SESSION['Loginname']=$obj->User_LoginName;
    //$_SESSION['Age']=$obj->iAge; 
	$_SESSION['Department']=$obj->User_Department;
	$_SESSION['Designation']=$obj->User_Designation;
	$_SESSION['UserFlowStatus']=$obj->User_FlowStatus;
	$_SESSION['UserKpiStatus']=$obj->User_KpiStatus;
	$_SESSION['UserAdminStatus']=$obj->User_AdminStatus;
	$_SESSION['UserManagerStatus']=$obj->User_ManagerStatus;
	$_SESSION['UserSupervisorStatus']=$obj->User_SupervisorStatus;
	//check whether username exists or not
	if($num>0)
    {
     $User_Id=$_SESSION['User_Id'];	
	 $Designation=$_SESSION['Designation'];
	 $NcrStatus=$_SESSION['NcrStatus'];
	//$date=date("YY-mm-dd");
	 mysql_query("update tbl_userprofile set User_LastLogin=current_date() where User_Id='$User_Id'");
	 $Status=$_SESSION['Status'];
	 $Department=$_SESSION['Department'];
	 $DesignStatus=$_SESSION['DesignStatus'];
	 $FlowStatus=$_SESSION['UserFlowStatus'];
	 $KpiStatus=$_SESSION['UserKpiStatus'];
     //check user login status to KPI Syatem
	 if($Status=='Y' && $KpiStatus=='Y')
	 {
		header("location:userlist.php"); 
	 }
	 else
	 {
		 header("location:index.php?login=R&d=$dest");
	 }
	 
	}
	//if user name not exists go to login page
else
{
header("location:index.php?login=1&d=$dest");
}
?>