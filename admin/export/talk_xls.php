<?php
define('BASEPATH',realpath('.'));
include_once "../config.php";


if(@$_SESSION["adminID"] == "" )
{
    echo "You are not authorised to view this page.";
    exit;
}




$displayData = $admin_user->DisplayAllDetailsTalk(1,1000000,"");
$filename = "talk_" . date('Ymd-His') . ".xls";

    

 
/*
    
header("Content-Disposition: attachment; filename=\"$filename\""); 
header("Content-Type: application/vnd.ms-excel");
*/

require_once '../excel/PHPExcel.php';
$objPHPExcel = new PHPExcel();






$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Carpark Site')
            ->setCellValue('B1', 'No. Of Carpark')
            ->setCellValue('C1', 'Full Name')
            ->setCellValue('D1', 'Company')
            ->setCellValue('E1', 'Address')
            ->setCellValue('F1', 'Contact No')
            ->setCellValue('G1', 'Email')
            ->setCellValue('H1', 'Note')
            ->setCellValue('I1', 'Date Time')
            
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
            ->setCellValue('A'.$index, $value["carparklocation"])
            ->setCellValue('B'.$index, $value["numberofparkinglots"])
            ->setCellValue('C'.$index, $value["fullname"])
            ->setCellValue('D'.$index, $value["company"])
            ->setCellValue('E'.$index, $value["address"])
            ->setCellValue('F'.$index, $value["contactno"])
            ->setCellValue('G'.$index, $value["emailaddress"])
            ->setCellValue('H'.$index, $value["note"])
            ->setCellValue('I'.$index, $startDateTime);
            
            
        $index++;
    }
}
    
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray(
        array(
              'borders' => array(
                                    'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                                )
             )
        );

    $objPHPExcel->getActiveSheet()->getStyle('A1:I'.(count($displayData["List"]) + 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('A1:I'.(count($displayData["List"]) + 1))->getAlignment()->setWrapText(true); 
    //$objPHPExcel->getActiveSheet()->getStyle('O2:O'.(count($displayData["List"]) + 1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
    
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);    
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(60);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    

    $xo=1;
    foreach($displayData["List"] as $id => $value)
    {

         $objPHPExcel->getActiveSheet()
             ->setCellValueExplicit('F'.($xo+1), $value["contactno"], PHPExcel_Cell_DataType::TYPE_STRING);
            $xo++;
        // $objPHPExcel->getActiveSheet()
        //     ->setCellValueExplicit('G'.($xo+1), $value["mobileNo"], PHPExcel_Cell_DataType::TYPE_STRING);
        // $objPHPExcel->getActiveSheet()
        //     ->setCellValueExplicit('H'.($xo+1), $value["telephoneNo"], PHPExcel_Cell_DataType::TYPE_STRING);
        // $xo++;
        
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