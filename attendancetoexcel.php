<?php  
session_start();
if(isset($_SESSION['User_Id'])){}else
{
session_destroy();
header("Location: index.php?login=expired");
}
include("connectivity.php");
$month=$_REQUEST['month'];
$year=$_REQUEST['year'];
/** PHPExcel */  
$dates=$year."-".$month."-21";
$e=0;
$qryuserprofile=mysql_query("select * from ncr_user order by User_Id");
while($objuserprofile=mysql_fetch_object($qryuserprofile))
{
$userids[$e]=$objuserprofile->User_Id;
$attendacecard[$objuserprofile->User_Id]=$objuserprofile->User_AttenId;
$User_Name[$objuserprofile->User_Id]=$objuserprofile->User_Name;
$EmployeeCode[$objuserprofile->User_Id]=$objuserprofile->User_EmployeeCode;
$grade[$objuserprofile->User_Id]=$objuserprofile->User_Grade;
$dept[$objuserprofile->User_Id]=$objuserprofile->User_Department;
$nationality[$objuserprofile->User_Id]=$objuserprofile->User_Nationality;
$workdur[$objuserprofile->User_Id]=$objuserprofile->User_WorkHours;
$qryshiftdetails=mysql_query("select * from shift_details where Shift_EmployeeId='$objuserprofile->User_Id'");
$numshiftdetails=mysql_num_rows($qryshiftdetails);
if($numshiftdetails==0)
{
	$shiftminutes[$objuserprofile->User_Id]=480;
}
else
{
	$dates1=$dates ." 00:00:00";
	while($objshiftdetails=mysql_fetch_object($qryshiftdetails))
	{
		if($objshiftdetails->Shift_StartTime<=$dates1 and $objshiftdetails->Shift_EndTime>=$dates1)
		{
			$shiftid=$objshiftdetails->Shift_MasterId;
			$shiftmaster=mysql_query("select * from shift_master where Shift_Id='$shiftid'");
			while($objmaster=mysql_fetch_object($shiftmaster))
			{
				$intime=$objmaster->Shift_InTime;
				$outtime=$objmaster->Shift_OutTime;
				if($intime=="Outside")
				{
					$shiftminutes[$objuserprofile->User_Id]=480;
				}
			    else
				{
					$shift_in=$dates." ".$intime.":00";
					$shift_out=$dates." ".$outtime.":00";
					$shiftminutes[$objuserprofile->User_Id]=((strtotime($shift_in)-strtotime($shift_out))/(60));
					if($shiftminutes[$objuserprofile->User_Id]<0){$shiftminutes[$objuserprofile->User_Id]=$shiftminutes[$objuserprofile->User_Id]*-1;}
				}
			
			}
		}
	}
}
$e++;
}
//print_r ($shiftminutes);
$qryattendance=mysql_query("select * from attendance_summary where Attendance_Month='$month' and Attendance_Year='$year' order by Attendance_UserId");

require_once '../offer/PHPExcel/Classes/PHPExcel.php';  
// Create new PHPExcel object  

$objPHPExcel = new PHPExcel();  

// Set properties  
$objPHPExcel->getProperties()->setCreator("SFTL Attendance")  
->setLastModifiedBy("Sumesh Mani")  
->setTitle("Office 2007 XLSX Offer Register")  
->setSubject("Office 2007 XLSX Offer Register")  
->setDescription("SFTL Attendance, generated using sft HRM")
->setKeywords("office 2007 openxml php")  
->setCategory("SFTL Attendance");  

//This is the hard coded *non dynamic* cell formatting
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(75);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);  
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(5);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale(85);

//echo $enddate ."&".$startdate;
    //  SQl database connections
 $styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 26,
		'align' => center,
        'name'  => 'Verdana'
    ));
		$rows=1;
		$objPHPExcel->setActiveSheetIndex(0) 
							->setCellValue('A'.$rows, '    SAUDI FEDERAL TRANSFORMERS LLC') ;  
	  $objPHPExcel->getActiveSheet()->getStyle('A'.$rows)->applyFromArray($styleArray);
	  $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		//objPHPExcel->getActiveSheet()->getStyle("A1:A1")->getFont()->setSize(26);       
                            //->setCellValue('B'.$rows, 'Offer Number1') //this will give cell C2. 
                            //->setCellValue('C'.$rows, 'Customer1');
		// to merge cells
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$rows.':J'.$rows);  
		// allignment wrap text
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rows.':J'.$rows)->getAlignment()->setWrapText(true);
		
        //Background color on cells 
       //  $objPHPExcel->getActiveSheet()->getStyle('B'.$rows.':D'.$rows)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00000000');
		  $objPHPExcel->getActiveSheet()
                    ->getRowDimension($rows)
                    ->setRowHeight(70);
		$rows++;
		$objPHPExcel->setActiveSheetIndex(0) 
							->setCellValue('A'.$rows, 'Sl No')             
                            ->setCellValue('B'.$rows, 'Employee Code') //this will give cell C2.
							->setCellValue('C'.$rows, 'Name')
							->setCellValue('D'.$rows, 'Department') 
							->setCellValue('E'.$rows, 'Present Days') 
                            ->setCellValue('F'.$rows, 'Absent')
							->setCellValue('G'.$rows, 'Deduction Hours (H:M)')
							->setCellValue('H'.$rows, 'Total Days')
							->setCellValue('I'.$rows, 'OT Hous (H:M)')
							//->setCellValue('Z'.$rows, 'Last Edited By')
							->setCellValue('J'.$rows, 'Remarks');
		$rows++;
		
		    $sl=1;
            while($data=mysql_fetch_object($qryattendance))  
            {  

                //This section is the actual data import fromt he SQL database *dont touch*
				$userid=$data->Attendance_UserId;
				//echo $userid.",";
				$UserEmployeeCode=$EmployeeCode[$userid];
				$UserName=$User_Name[$userid];
				$UserDept=$dept[$userid];
				$shiftminutes1=$shiftminutes[$userid];
				//ec $shiftminutes[$userid];
				$dedmins=$data->Attendance_SalaryDedMins;
				$deddays=round($dedmins/$shiftminutes1,3);
				$dedhrs=floor($dedmins/60);
				$dedmin=($dedmins*1)-($dedhrs*60);
				
				if($dedmin<10)
				{
					$dedmin="0".$dedmin;
				}
				$dedhr=$dedhrs.":".$dedmin;
				$otmins=$data->Attendance_SalaryOtMins;
				$othrs=floor($otmins/60);
				$totdays=$data->Attendance_SalaryTotDays;
				$otmin=($otmins*1)-($othrs*60);
				$othr=$othrs.":".$otmin;
				$editedbyid=$data->Attendance_Editedby;
				//echo $editedbyid;   
				if(isset($editedbyid))
				{
                $editedby=$User_Name[$editedbyid];
								//echo $editedby;
				}
				else
				{
				$editedby="";
				}
				
				          $objPHPExcel->setActiveSheetIndex(0)              
                            ->setCellValue('A'.$rows, $sl)
						    ->setCellValue('B'.$rows, $UserEmployeeCode) //this will give cell C2.
							->setCellValue('C'.$rows, $UserName) 
							->setCellValue('D'.$rows, $UserDept) 
                            ->setCellValue('E'.$rows, $data->Attendance_SalaryDays)
							->setCellValue('F'.$rows, $data->Attendance_SalaryAbsentDays); // this will give cell B2
							$objPHPExcel->setActiveSheetIndex(0)              
                            ->setCellValue('G'.$rows, '\''.$dedhr)
							->setCellValue('H'.$rows, '\''.$totdays)
							->setCellValue('I'.$rows, $othrs)
							->setCellValue('J'.$rows, $data->Attendance_Remarks)
							->setCellValue('K'.$rows, $userid);
							$objPHPExcel->getActiveSheet()->getStyle('C'.($rows))->getAlignment()->setWrapText(true);
							$objPHPExcel->getActiveSheet()->getStyle('D'.($rows))->getAlignment()->setWrapText(true);
							$rows++;
							$sl++;
							
					 }	
            
    // Rename sheet  
   $objPHPExcel->getActiveSheet()->setTitle('SFTL Attendance'.$month.'-'.$year);

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel5) 
    ob_end_clean(); 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
    header('Content-Type: application/vnd.ms-excel'); 
    header('Content-Disposition: attachment;filename="SFTL Attendance.xls"'); 
    header('Cache-Control: max-age=0'); 
    $objWriter->save('php://output');  
    exit;  
?> 