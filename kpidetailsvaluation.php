<?php
include("connectivity.php");

$Kpi_MasterId=$_REQUEST['Kpi_MasterId'];
//echo"master id is".$Kpi_MasterId;



$qry=mysql_query("select * from kpi_details where Kpi_MasterId='$Kpi_MasterId'");
while($obj=mysql_fetch_object($qry))
{
$kpi_detailsid=$obj->Kpi_Id;
$version=$obj->Kpi_SlNo;
$Kpi_TotalScore=$obj->Kpi_TotalScore;
       if ($Kpi_TotalScore==0)
		{
		//echo $version;
		}
        elseif (strpos($version,"B.1")===0 or strpos($version,"B.2")===0)
		{
		$perdone11="perdone1".$kpi_detailsid;
		$perdone1=$_REQUEST[$perdone11];
		$selfscore1=$perdone1*$Kpi_TotalScore/100;
		$finalscore11="finalscore1".$kpi_detailsid;
		$finalscore1=$_REQUEST[$finalscore11];
		$perdone21="perdone2".$kpi_detailsid;
		$perdone2=$_REQUEST[$perdone21];
		$selfscore2=$perdone2*$Kpi_TotalScore/100;
		$finalscore21="finalscore2".$kpi_detailsid;
		$finalscore2=$_REQUEST[$finalscore21];
		$Kpi_FinalTotalScore=$finalscore2+$finalscore1;
		$currentstatus1="currentstatus1".$kpi_detailsid;
		$actionsplanned1="actionsplanned1".$kpi_detailsid;
		$futureplan1="futureplan1".$kpi_detailsid;
		$whentoachieve1="whentoachieve1".$kpi_detailsid;
		$SRemarks1="SRemarks1".$kpi_detailsid;
		$MRemarks1="MRemarks1".$kpi_detailsid;
		$qryremarks1=mysql_query("select * from kpi_remarks where kpi_detailsid='$kpi_detailsid' and kpi_half='firsthalf'");
		$numremarks1=mysql_num_rows($qryremarks1);
		if($numremarks1==0)
		{
			if($currentstatus1!="" or $actionsplanned1!="" or $futureplan1==!="" or $whentoachieve1!="" or $SRemarks1!="" or $MRemarks1!="")
			{
				
			}
			else
			{
				mysql_query("insert into kpi_remarks (kpi_detailsid,kpi_half,kpi_currentstatus,kpi_actionsplanned,kpi_actionsplan,kpi_whentoachieve,kpi_supervisorremarks,kpi_managerremarks) values ('$kpi_detailsid','firsthalf','$currentstatus1','$actionsplanned1','$futureplan1','$whentoachieve1','$SRemarks1','$MRemarks1')");
			
			}	
		}
		else
		{
			mysql_query("update kpi_remarks set where kpi_currentstatus='$currentstatus1'  kpi_detailsid='$kpi_detailsid' and kpi_half='firsthalf'");
		}
		
		mysql_query("update kpi_details set Kpi_PerDone1='$perdone1',Kpi_SelfScore_1='$selfscore1',Kpi_PerDone2='$perdone2',Kpi_SelfScore_2='$selfscore2',Kpi_FinalScore1='$finalscore1',Kpi_FinalScore2='$finalscore2',Kpi_FinalTotalScore='$Kpi_FinalTotalScore' where Kpi_Id='$kpi_detailsid'");
		
		}
		elseif (strpos($version,"B.")===0)
		{
		//echo "test";
		$Kpi_FinalTotalScore="Kpi_FinalTotalScore".$kpi_detailsid;
		$totalfinalscore=$_REQUEST[$Kpi_FinalTotalScore];
		$Remarks="Remarks".$kpi_detailsid;
		$Remarks1=$_REQUEST[$Remarks];
		mysql_query("update kpi_details set Kpi_FinalTotalScore='$totalfinalscore',Kpi_Remarks1='$Remarks1' where Kpi_Id='$kpi_detailsid'");
		}
		elseif (strpos($version,"C.")===0)
		{
		//echo "test";
		$Kpi_FinalTotalScore="Kpi_FinalTotalScore".$kpi_detailsid;
		$totalfinalscore=$_REQUEST[$Kpi_FinalTotalScore];
		$deptmanscore1="deptmanscore".$kpi_detailsid;
		$deptmanscore=$_REQUEST[$deptmanscore1];
		$adminmanscore1="adminmanscore".$kpi_detailsid;
		$adminmanscore=$_REQUEST[$adminmanscore1];
		$gmscore1="gmscore".$kpi_detailsid;
		$gmscore=$_REQUEST[$gmscore1];
		$totalfinalscore=$deptmanscore+$adminmanscore+$gmscore;
		mysql_query("update kpi_details set Kpi_FinalTotalScore='$totalfinalscore',Kpi_DeptMangerScore1='$deptmanscore',Kpi_AdminMangerScore='$adminmanscore',Kpi_GMScore='$gmscore' where Kpi_Id='$kpi_detailsid'");
		//echo "update kpi_details set Kpi_FinalTotalScore='$totalfinalscore',Kpi_DeptMangerScore1='$deptmanscore',Kpi_AdminMangerScore='$adminmanscore',Kpi_GMScore='$gmscore' where Kpi_Id='$kpi_detailsid'";
		}
        else
		{
		
		}
}
if(isset($_REQUEST['Kpi_Recommend']))
{
$Kpi_Recommend=$_REQUEST['Kpi_Recommend'];
mysql_query("update kpi_master set Kpi_Recommend='$Kpi_Recommend' where Kpi_Id=$Kpi_MasterId");
}
header("location:evaluatekpi.php?hidKpi_Id=$Kpi_MasterId");
?>

