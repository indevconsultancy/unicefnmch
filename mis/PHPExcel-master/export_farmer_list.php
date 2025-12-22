<?php 
ini_set('memory_limit', '-1');
// include_once('config.php'); 
// include('../includes/config.php');
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

error_reporting(E_ALL ^E_NOTICE);

ini_set('memory_limit', -1);
//set_time_limit (0) ;

include('Classes/PHPExcel.php');


$objPHPExcel	=	new	PHPExcel();


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Farmer Name');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Mobile No');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'State Name');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'District Name');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Block Name');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Village Name');


$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);

$rowCount	=	2;
for($i=0; $i<=10;$i++){
	
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'sdfghj');
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'fsdsavbd');
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'asdsadsd');
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'shgdas');
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'astgrdfgas');
	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'asdhfsagdh');
	$rowCount++;
}


$objWriter	=	new PHPExcel_Writer_Excel2007($objPHPExcel);


header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="Farmer_list.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
$objWriter->save('php://output');


?>
