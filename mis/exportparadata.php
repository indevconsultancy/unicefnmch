<?php
//session_start();
/** Error reporting */
ob_start();
include('includes/config.php');
include('includes/functions.php');
require_once "api/mycrypt.php";
//$mysqli = new mysqli(SERVER, USER, PASSWORD, DATABASE);
error_reporting(E_ALL);
error_reporting(1);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1');
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

if($_SESSION['enckey'] === 0){
    die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
/** Include PHPExcel */

// include('PHPexcel/Classes/PHPExcel.php');
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
include 'PHPExcel/Classes/PHPExcel.php';


function is_multi($a) {
    // $rv = array_filter($a,'is_array');
    // if(count($rv)>0){ return "ha"; }else{ return "na"; }
	$a = (array)$a;
	foreach($a as $b){
		if(is_object($b)){ return true; } else{ return false; }
	}
	
}


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Satyendra")
                             ->setLastModifiedBy("Satyendra")
                             ->setTitle("Office 2007 XLSX Family listing")
                             ->setSubject("Office 2007 XLSX Family listing")
                             ->setDescription("Family listing for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Eligible Family result file");
//$index=1;

$survey_id_exported = $_REQUEST['id'];

$objPHPExcel->setActiveSheetIndex(0); 
$objPHPExcel->getActiveSheet()->setCellValue("A1", "Question");
$objPHPExcel->getActiveSheet()->setCellValue("B1", "Duration");
$objPHPExcel->getActiveSheet()->setCellValue("C1", "GPS");
$objPHPExcel->getActiveSheet()->setCellValue("D1", "Timestamp");
$objPHPExcel->getActiveSheet()->setCellValue("E1", "Keystrok");
	

$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true );
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
//-----------getting query from school listing------------------//

// echo "<h2>Value</h2>";

    $get_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id, para_data,full_json  FROM survey_data_monitoring WHERE survey_data_monitoring_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc");
	$sdmon = mysqli_fetch_object($get_sdmon);
	$para_datajason = $sdmon->para_data;
	
	$DecryptedJson = $mcrypt->decrypt($sdmon->full_json);
	$full_json = json_decode($DecryptedJson);
	
	$survey_id = $sdmon->survey_id;
	$para_data = json_decode($para_datajason);
	$counter=2;
	$ssArr = [];
	foreach($full_json as $fjKey=>$fulljson){
		if($fjKey!="error_record" && $fjKey!="hint_record" )
		{
			if(is_array($fulljson)) 
			{
				foreach($fulljson as $fulljson1){
					if(is_multi($fulljson1)){
						foreach($fulljson1 as $fulljson2){
							$ssArr[] = (array)$fulljson2;
						} 
					}else{
						$ssArr[] = (array)$fulljson1;
					}
				}
			}
		}
	}
	// print_r($ssArr);
	// die;
	foreach($ssArr as $paraata){
		//$question_id = $paraata->question_id;
		/* $field_name = $paraata->field_name;
		$duration = $paraata->duration;
		$gps = $paraata->gps;
		$timestamp = $paraata->timestamp;
		$keystrok = $paraata->keystrok; */
		$field_name = $paraata['field_name'];
		$duration = $paraata['duration'];
		$gps = $paraata['gps'];
		$timestamp = dateformate($paraata['timestamp']);
		$keystrok = $paraata['key_stroke'];
		
		//$question = getone($conn,'questions','question_name','question_id',$question_id);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$counter, $field_name);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$counter, $duration);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$counter, $gps);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$counter, $timestamp);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$counter, $keystrok);
		$counter++;
	}
	
	
$sheetname = "paradata-".$survey_id;
$objPHPExcel->getActiveSheet()->setTitle($sheetname);
$objPHPExcel->setActiveSheetIndex(0);

/*
===============================================
==============SHEET ONE COMPLETED==============
===============================================
*/


//SET SHEET ACTIVE DEFAULT
$objPHPExcel->setActiveSheetIndex(0);
//die;
// Redirect output to a client’s web browser (Excel2007)
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=paradata-'.$survey_id.'.xlsx');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>