<?php  
include("connectivity.php");
if(isset($_REQUEST['year']))
{
$yearselected=$_REQUEST['year'];
}
else
{
$yearselected=date("Y");
$monthselected=date("m");
}
if(isset($_REQUEST['month']))
{
$monthselected=$_REQUEST['month'];
}
$dateto=date($yearselected.'-'.$monthselected.'-20',strtotime('$yearselected-$monthselected-20'));
$datefrom=date($yearselected.'-'.$monthselected.'-01',strtotime('$yearselected-$monthselected-20'));
$month=$monthselected;
$year=$yearselected;
/** PHPExcel */  
$dates=$year."-".$month."-21";
$e=0;
$qryuserprofile=mysql_query("select * from ncr_user order by User_JoiningDate");
$allowancenamearray=array("HRA"=>"HRA","CONVEYANCE"=>"CONVEYANCE","MOBILE"=>"MOBILE","UTILITY"=>"UTILITY","EDUCATION ALLOWANCE"=>"EDUCATION ALLOWANCE","PERFORMANCE ALLOWANCE"=>"PERFORMANCE ALLOWANCE","OTHER ALLOWANCE"=>"OTHER ALLOWANCE","VACATION SALARY"=>"VACATION SALARY","Personal Loan"=>"Personal Loan");
if(isset($_REQUEST['year']))
{
$yearselected=$_REQUEST['year'];
}
else
{
$yearselected=date("Y");
$monthselected=date("m");
}
if(isset($_REQUEST['month']))
{
$monthselected=$_REQUEST['month'];
}
$dateto=date($yearselected.'-'.$monthselected.'-20',strtotime('$yearselected-$monthselected-20'));
$datefrom=date($yearselected.'-'.$monthselected.'-01',strtotime('$yearselected-$monthselected-20'));

$qryuserprofile=mysql_query("select * from ncr_user where (User_JoiningDate<='$dateto') and ((User_LeavingDate>='$datefrom') or(User_LeavingDate='0000-00-00'))  order by User_JoiningDate");
while($objuserprofile=mysql_fetch_object($qryuserprofile))
{
$userids[$e]=$objuserprofile->User_Id;
$employeecode[$objuserprofile->User_Id]=$objuserprofile->User_EmployeeCode;
$attendacecard[$objuserprofile->User_Id]=$objuserprofile->User_AttenId;
$User_Name[$objuserprofile->User_Id]=$objuserprofile->User_Name;
$designation[$objuserprofile->User_Id]=$objuserprofile->User_Designation;
$grade[$objuserprofile->User_Id]=$objuserprofile->User_Grade;
$division[$objuserprofile->User_Id]=$objuserprofile->User_Division;
$doj[$objuserprofile->User_Id]=$objuserprofile->User_JoiningDate;
 //echo "name-".$name[$objuserprofile->User_Id];
$dept[$objuserprofile->User_Id]=$objuserprofile->User_Department;
$sponsor[$objuserprofile->User_Id]=$objuserprofile->User_Sponsor;
$nationality[$objuserprofile->User_Id]=$objuserprofile->User_Nationality;
$workdur[$objuserprofile->User_Id]=$objuserprofile->User_WorkHours;
$e++;
}


require_once '/PHPExcel/Classes/PHPExcel.php';  

// Create new PHPExcel object  
$objPHPExcel = new PHPExcel();  

// Set properties  
$objPHPExcel->getProperties()->setCreator("SFTL PAYROLL")  
->setLastModifiedBy("Sumesh Mani")  
->setTitle("Office 2007 XLSX Offer Register")  
->setSubject("Office 2007 XLSX Offer Register")  
->setDescription("SFTL PAYROLL, generated using sft HRM")
->setKeywords("office 2007 openxml php")  
->setCategory("SFTL PAYROLL");  

//This is the hard coded *non dynamic* cell formatting
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);  
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);  
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
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
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$rows.':AW'.$rows);  
		// allignment wrap text
        $objPHPExcel->getActiveSheet()->getStyle('A'.$rows.':AW'.$rows)->getAlignment()->setWrapText(true);
		
        //Background color on cells 
       //  $objPHPExcel->getActiveSheet()->getStyle('B'.$rows.':D'.$rows)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00000000');
		  $objPHPExcel->getActiveSheet()
                    ->getRowDimension($rows)
                    ->setRowHeight(70);
		$rows++;
	

		$headings=array('Sl','Employee Code','Name','Joining Date','EXPAT or SAUDI','NATIONALITY','ID ','DESIGNATION','SPONSOR','DIVISION','GRADE','Basic Salary','HRA','CONVEYANCE','MOBILE','UTILITY','EDUCATION ALLOWANCE','PERFORMANCE ALLOWANCE','OTHER ALLOWANCE','VACATION SALARY','TOTAL SALARY','OTHER DEDUCTION','FOOD CHARGES','Exit DATE','Name(as in Bank)','Bank Name','BANK CODE','A/c No','IBAN','Present','Ded Hrs','OT HOURS','BASIC SALARY FOR THE MONTH','HRA FOR THE MONTH','Conveyance for the Month','Mobile Allowance for the month','UTILITY ALLOWANCE','EDUCATION ALLOWANCE','PERFORMANCE ALLOWANCE','OTHER ALLOWANCE','VACATION SALARY','FOOD','OTHER EARNINGS','GROSS SALARY','FOOD DEDUCTION','DEDUCTION','CONVEYANCE DEDUCTION','PERSONAL LOAN DEDUCTION','OTHERS');
		$column="A";
		for($sl=1;$sl<=48;$sl++)
		{
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column.$rows, $headings[$sl]) ;
        $column++;		
		}
					
		 $rows++;
    	 
         foreach($userids as $id)
		 {
			 $column="A";
			 $column="A";
			$arrayvalues[$id]=array($sl,$employeecode[$id]);
			for($sl=0;$sl<=48;$sl++)
		     {
		     $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column.$rows, $arrayvalues[$id][$id]) ;
             $column++;		
			 } 
			 $rows++;
		  }	//foreach ends here	 
            
   // Rename sheet  
   $objPHPExcel->getActiveSheet()->setTitle('SFTL Payroll'.$month."-".$year);

   // Set active sheet index to the first sheet, so Excel opens this as the first sheet
   $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel5) 
    ob_end_clean(); 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
    header('Content-Type: application/vnd.ms-excel'); 
    header('Content-Disposition: attachment;filename="SFTL Payroll.xls"'); 
    header('Cache-Control: max-age=0'); 
    $objWriter->save('php://output');  
    exit;  
    
	
	?> 