<?php
session_start();
$userid=$_SESSION['User_Id'];
$date=date("Y-m-d");
include("connectivity.php");
include("topbar.php");
include("menubar1.php");
$allowancenamearray=array("HRA"=>"HRA","CONVEYANCE"=>"CONVEYANCE","MOBILE"=>"MOBILE","UTILITY"=>"UTILITY","EDUCATION ALLOWANCE"=>"EDUCATION ALLOWANCE","PERFORMANCE ALLOWANCE"=>"PERFORMANCE ALLOWANCE","OTHER ALLOWANCE"=>"OTHER ALLOWANCE","VACATION SALARY"=>"VACATION SALARY","Personal Loan"=>"Personal Loan");
$allowance_add=array("Add"=>"Add","Deduct"=>"Deduct");
$allowance_fixed=array("Fixed"=>"Fixed","Variable"=>"Variable");
$qry_user=mysql_query("select * from ncr_user where User_LeavingDate='0000-00-00'");
echo "<table class='tableaddp' border='1'>";
echo "<tr>";
echo "<td>Sl</td>";
echo "<td>User ID</td>";
echo "<td>Employee Code</td>";
echo "<td>User Name</td>";
echo "<td>Joining Date</td>";
echo "<td>Basic Salary</td>";
echo "<td>Bank Details</td>";
foreach($allowancenamearray as $id=>$value)
{
	echo "<td>".$value."</td>";
}
echo "</tr>";
$sl=0;
while($obj_user=mysql_fetch_object($qry_user))
{
 $sl++;
 $User_Id=$obj_user->User_Id;	
 echo "<tr><td>".$sl."</td>";
 echo "<td>". $User_Id."</td>";
 echo "<td>".$obj_user->User_EmployeeCode."</td>";
 echo "<td>".$obj_user->User_Name."</td>";
 echo "<td>".date('d-M-Y',strtotime($obj_user->User_JoiningDate))."</td>";
 $qrybasic=mysql_query("select * from payroll_basic where user_id='$User_Id' and (effective_from<='$date' and effective_to>='$date')");
 $numbasic=mysql_num_rows($qrybasic);
 if($numbasic==0)
 {
	 echo "<td><a href='basicsalary.php?hidUserId=$User_Id'>Update</a></td>";
 }
 else
 {
	 while($objbasic=mysql_fetch_object($qrybasic))
	 {
		echo "<td><a href='basicsalary.php?hidUserId=$User_Id'>$objbasic->basic_amount</a></td>";
	 }
 }
 $qrybank=mysql_query("select * from payroll_bankdetails where payroll_userid='$User_Id' and (effective_from<='$date' and effective_to>='$date')");
 $numbank=mysql_num_rows($qrybank);
 if($numbank==0)
 {
	 echo "<td><a href='bankdata.php?hidUserId=$User_Id'>Update</a></td>";
 }
 else
 {
	 while($objbank=mysql_fetch_object($qrybank))
	 {
		echo "<td><a href='bankdata.php?hidUserId=$User_Id'>$objbank->payroll_accountno</a></td>";
	 }
 }
 
 foreach($allowancenamearray as $id=>$value)
{
	echo "<td>";
 $qryallowance=mysql_query("select * from  payroll_allowance where user_id='$User_Id' and allowance_name='$value' and (Allowance_EffectiveFrom<='$date' and Allowance_EffectiveTo>='$date')");
 $numallowance=mysql_num_rows($qryallowance);
 if($numallowance==0)
 {
	echo "<a href='allowanceentry.php?allowancename=$value&hidUserId=$User_Id'>--</a>";
 }
 else
 {
	while($objallowance=mysql_fetch_object($qryallowance))
	 {
		 echo "<a href='allowanceentry.php?allowancename=$value&hidUserId=$User_Id'>";
			/*if($objallowance->allowance_fixed=="Fixed")
			{echo "Fixed";}else{echo $objallowance->allowance_fixed;}*/
			if($objallowance->allowance_add=="Add")
			{echo "+";}else{echo "-";}
					
		if($objallowance->allowance_amount)
		{echo $objallowance->allowance_amount;}
		else{echo $objallowance->allowance_percentage."% of ".$objallowance->variable_dependent;}
		//echo "$objallowance->allowance_schedule";
		echo "</a>";
	 }
	 } 
 echo "</td>";
 }
 
 echo "</tr>";
}
echo "</table>"
?>

<?php
include("bottombar.php");
?>