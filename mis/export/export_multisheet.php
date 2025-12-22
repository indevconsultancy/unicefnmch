<?php
//session_start();
/** Error reporting */
ob_start();
include('../includes/config.php');
//$mysqli = new mysqli(SERVER, USER, PASSWORD, DATABASE);
error_reporting(E_ALL);
//error_reporting(0);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1');
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */

include('PHPexcel/Classes/PHPExcel.php');

echo $survey_id = $_REQUEST['survey_id'];
die;
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
for($i="A";$i<="Z";$i++ )
{
    $setValuePosiotion[]=$i;
}

$get_expt_fields = mysqli_query($conn,"SELECT * FROM report_format WHERE survey_id='".$survey_id."' AND group_id='0' ORDER BY seq_no ASC");
while($expt_fields = mysqli_fetch_object($get_expt_fields)){
        $export_headers[]=$expt_fields->title;//str_replace(",","~",$expt_fields->title);
        
        $exp_columns[] = $expt_fields->field_lable;
    }

$get_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id, survey_data_json, full_json  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id."' order by survey_data_monitoring_id asc");;
while($sdmon = mysqli_fetch_object($get_sdmon)){
        $survey_data_json=$sdmon->survey_data_json;
        $surveyid = $sdmon->survey_id;
        $jsondata = json_decode($survey_data_json, true);
        foreach($exp_columns as $exp_column){
            $export_main_value[]=$jsondata[$exp_column]?:"NA"; 
            //str_replace(",","~",$jsondata[$exp_column])?:"NA";
        }
    }


$objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "Survey Id");

        foreach($export_headers as $headKey=>$export_header){
            $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$headKey+1]."1", $export_header);
            //echo "<br>";
        }

$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true );
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
//-----------getting query from school listing------------------//

// echo "<h2>Value</h2>";
$get_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id, survey_data_json, full_json  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id."' order by survey_data_monitoring_id asc");
$counter=2;
while($sdmon = mysqli_fetch_object($get_sdmon)){
        $survey_data_json=$sdmon->survey_data_json;
        $survey_id=$sdmon->survey_id;

        $jsondata = json_decode($survey_data_json, true);
        $ss=0;

        $objPHPExcel->getActiveSheet()->setCellValue("A".$counter, $survey_id);
        //$counter++;
        $ss++;
        foreach($exp_columns as $exp_column){
            $export_main_value[]=$jsondata[$exp_column]?:"NA"; //str_replace(",","~",$jsondata[$exp_column])?:"NA";
            $mon_data_value = $jsondata[$exp_column]?:"NA";

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$ss++].$counter, $mon_data_value);
           // echo "<br>";
        }
        //echo "<br>";
        $counter++;
    }

$objPHPExcel->getActiveSheet()->setTitle('Monitoring-data');
$objPHPExcel->setActiveSheetIndex(0);

/*
===============================================
==============SHEET ONE COMPLETED==============
===============================================
*/

$get_expt_fields = mysqli_query($conn,"SELECT * FROM report_format WHERE survey_id='".$survey_id."' AND group_id!='0' ORDER BY seq_no ASC");
while($expt_fields = mysqli_fetch_object($get_expt_fields)){
    $export_repeated_headers[]=$expt_fields->title;//str_replace(",","~",$expt_fields->title);
    $exp_repeated_columns[] = $expt_fields->field_lable;
}

// $objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->createSheet(1);
$objPHPExcel->setActiveSheetIndex(1);

$objPHPExcel->getActiveSheet()->setCellValue("A1", "Survey Id");
foreach($export_repeated_headers as $headKeyR=>$export_headerR){
		$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$headKeyR+1]."1", $export_headerR);
		//echo "<br>";
	}


$objPHPExcel->getActiveSheet()->getStyle("A1:U1")->getFont()->setBold( true );
// $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);

$get_repeated_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id, survey_data_json, full_json  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id."' order by survey_data_monitoring_id asc");
while($repeayed_sdmon = mysqli_fetch_object($get_repeated_sdmon)){
    $full_json=$repeayed_sdmon->full_json;
    $surveysid=$repeayed_sdmon->survey_id;
    //echo "<br>";
    $fulljsondata = json_decode($full_json, true);
    //print_r($fulljsondata);
    
    foreach($fulljsondata as $fulljsonKey=>$repeated){
        
        if(is_array($repeated)){
            if($fulljsonKey!="survey_data"){
                $group_name = $fulljsonKey;
                $counter=2;
                
                foreach($repeated as $rptkey=>$repeatedq){
                    $ss=0;
                    $ss++;
                    $objPHPExcel->getActiveSheet()->setCellValue("A".$counter, $surveysid);
                        //echo "<br>";
                    foreach($repeatedq as $repeatedqs){
                        $option_value = $repeatedqs['option_value'];
                        $option_id = $repeatedqs['option_id'];
                        $input_field_type = $repeatedqs['input_field_type'];
                        $question_id = $repeatedqs['question_id'];
                        $field_name = $repeatedqs['field_name'];
                        //$objPHPExcel->setActiveSheetIndex(1);
                        $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$ss++].$counter, $option_value);
                        
                    }

                    $sheet_name=$group_name;//"Sheet";
                    $objPHPExcel->getActiveSheet()->setTitle($sheet_name);
                    $counter++;
                    
                    //echo "<br>";
                }
                 
            }
        }
    }
}


// Rename worksheet
//$objPHPExcel->getActiveSheet()->setTitle('Sheet-2');
//$objPHPExcel->setActiveSheetIndex(1);

//die;
// Redirect output to a client’s web browser (Excel2007)
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="survey-monitoring-data.xlsx"');
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