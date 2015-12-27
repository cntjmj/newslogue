<?php
define('BASEPATH',realpath('.'));
include_once "../config.php";


if(@$_SESSION["adminID"] == "" )
{
    echo "You are not authorised to view this page.";
    exit;
}




$displayData = $admin_user->DisplayAllDetails(1,1000000,"");
$filename = "user_" . date('Ymd-His') . ".xls";

    

 
/*
    
header("Content-Disposition: attachment; filename=\"$filename\""); 
header("Content-Type: application/vnd.ms-excel");
*/

require_once '../excel/PHPExcel.php';
$objPHPExcel = new PHPExcel();






$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Salutation')
	        ->setCellValue('B1', 'Name')
            ->setCellValue('C1', 'Email')
            ->setCellValue('D1', 'Citizenship')
            ->setCellValue('E1', 'NRIC')
            ->setCellValue('F1', 'Occupation')
            ->setCellValue('G1', 'Mobile No.')
            ->setCellValue('H1', 'Office No.')
            ->setCellValue('I1', 'Address 1')
            ->setCellValue('J1', 'Address 2')
            ->setCellValue('K1', 'Postcode')
            ->setCellValue('L1', 'Mode Of Communication')
            ->setCellValue('M1', 'Project Interest')
            ->setCellValue('N1', 'Property Price Range')
            ->setCellValue('O1', 'Purpose of Purchase')
            ->setCellValue('P1', 'How did you come to know of this project?')
            ->setCellValue('Q1', 'Receive Future Info')
            ->setCellValue('R1', 'Date Time')
            
            ;
            


if(is_array($displayData["List"]) && count($displayData["List"]) > 0)
{
    $index = 2;
    foreach($displayData["List"] as $id => $value)
    {

        $startDateTime = strtotime($value["createdDateTime"]);
		$startDateTime = date("d/m/Y H:i:s",$startDateTime);
                
        //$startDateArray = explode(" ",$startDateTime);
        //$startDate = $startDateArray[0];
        //$startTime = $startDateArray[1];
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$index, $value["salutation"])
	        ->setCellValue('B'.$index, $value["name"])
            ->setCellValue('C'.$index, $value["email"])
            ->setCellValue('D'.$index, $value["citizenship"])
            ->setCellValue('E'.$index, $value["nric"])
            ->setCellValue('F'.$index, $value["occupation"])
            ->setCellValue('G'.$index, $value["mobileNo"])
            ->setCellValue('H'.$index, $value["officeNo"])
            ->setCellValue('I'.$index, $value["address1"])
            ->setCellValue('J'.$index, $value["address2"])
            ->setCellValue('K'.$index, $value["postcode"])
            ->setCellValue('L'.$index, $value["modeCommunication"])
            ->setCellValue('M'.$index, $value["projectInterest"])
            ->setCellValue('N'.$index, $value["priceRange"])
            ->setCellValue('O'.$index, $value["purpose"])
            ->setCellValue('P'.$index, $value["how"])
            ->setCellValue('Q'.$index, $value["futureInfo"])
            ->setCellValue('R'.$index, $startDateTime);
            
            
        $index++;
    }
}
	
    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
    	array(
    		  'borders' => array(
    								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
    							)
    		 )
    	);

    $objPHPExcel->getActiveSheet()->getStyle('A1:K'.(count($displayData["List"]) + 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A1:K'.(count($displayData["List"]) + 1))->getAlignment()->setWrapText(true); 
    //$objPHPExcel->getActiveSheet()->getStyle('O2:O'.(count($displayData["List"]) + 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
    
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);    
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
    

    $xo=1;
    foreach($displayData["List"] as $id => $value)
    {

        
        $objPHPExcel->getActiveSheet()
            ->setCellValueExplicit('G'.($xo+1), $value["mobileNo"], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()
            ->setCellValueExplicit('H'.($xo+1), $value["telephoneNo"], PHPExcel_Cell_DataType::TYPE_STRING);
        $xo++;
        
    }
    /*
    for($u=1;$u<=(count($displayData["List"])+1);$u++)
    {
        $objPHPExcel->getActiveSheet()
        ->getRowDimension($u)
        ->setRowHeight(17);    
    }
    
    
    $xo=1;
    foreach($displayData["List"] as $id => $value)
    {
        $objPHPExcel->getActiveSheet()
            ->setCellValueExplicit('H'.($xo+1), $value["mobileHead"], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()
            ->setCellValueExplicit('I'.($xo+1), $value["mobileBody"], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()
            ->setCellValueExplicit('J'.($xo+1), $value["homeHead"], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()
            ->setCellValueExplicit('K'.($xo+1), $value["homeBody"], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()
            ->setCellValueExplicit('L'.($xo+1), $value["extension"], PHPExcel_Cell_DataType::TYPE_STRING);
        $xo++;
    }

    */    
    $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)

    header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;





?>