<?php 
ini_set('memory_limit', '-1');
// include_once('config.php'); 
//include('../includes/config.php');
include('Classes/PHPExcel.php');


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


$objPHPExcel	=	new	PHPExcel();
  //$qry=$_SESSION['notconnreportquery'];
//$result	=$conn->query($qry);
//ini_set('memory_limit', '-1');
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Mobile No');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Name');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'State Name');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'District Name');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Block Name');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Village Name');


$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);

$rowCount	=	2;
for($i=0; $i<=10; $i++){
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "9554161643");
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "satyendra");
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "UP");
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "Deoria");
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, "Patherdewa");
	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "Paksajdsad");
	$rowCount++;
}

$objWriter	=	new PHPExcel_Writer_Excel2007($objPHPExcel);

die;
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="Not_Connected_Report.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
$objWriter->save('php://output');
?>