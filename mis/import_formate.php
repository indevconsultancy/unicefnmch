<?php
//session_start();
/** Error reporting */
ob_start();
include('includes/config.php');
//$mysqli = new mysqli(SERVER, USER, PASSWORD, DATABASE);
error_reporting(E_ALL);
error_reporting(1);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1');
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */

// include('PHPexcel/Classes/PHPExcel.php');
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
include 'PHPExcel/Classes/PHPExcel.php';

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
$export_headers=['startdatetime','enddatetime','device_id','gps','username','surveystatus','terminationreason'];

$survey_id_exported = $_REQUEST['survey_id'];

$sqlquerySurvey = "SELECT id,survey_name,unique_id FROM `survey` where id='" . $survey_id_exported . "'";
$resultSurvey = mysqli_query($conn, $sqlquerySurvey);
$datasurvey = mysqli_fetch_array($resultSurvey);
$survey_name = $datasurvey['survey_name']."-".$datasurvey['unique_id'];

$get_expt_fields = mysqli_query($conn,"SELECT id,title,field_lable FROM report_format WHERE survey_id='".$survey_id_exported."' AND group_id='0' AND title!='' ORDER BY seq_no ASC");
while($expt_fields = mysqli_fetch_object($get_expt_fields)){
        //$export_headers[]=$expt_fields->title;//str_replace(",","~",$expt_fields->title);
        $export_headers[]=$expt_fields->field_lable;//str_replace(",","~",$expt_fields->title);
        
        $exp_columns[] = $expt_fields->field_lable;
    }


$objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "Survey Id");

        foreach($export_headers as $headKey=>$export_header){
            $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$headKey+1]."1", $export_header);
            //echo "<br>";
        }

$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true );
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);

/* 
// echo "<h2>Value</h2>";
$get_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id, survey_name, survey_data_json_coded, full_json  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc LIMIT 0,2");
$counter=2;
while($sdmon = mysqli_fetch_object($get_sdmon)){
      //  $survey_data_json=$sdmon->survey_data_json_coded;
        $survey_data_json=$sdmon->full_json;
        $survey_id=$sdmon->survey_id;
		$survey_name = $sdmon->survey_name;
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


$get_expt_fields_old = mysqli_query($conn,"SELECT * FROM report_format WHERE survey_id='".$survey_id_exported."' AND group_id!='0' AND title!='' ORDER BY seq_no ASC");
while($expt_fields_old = mysqli_fetch_object($get_expt_fields_old)){
    //$export_repeated_headers[]=$expt_fields->title;//str_replace(",","~",$expt_fields->title);
    
    $exp_repeated_columns_old[] = $expt_fields->field_lable;
}


if(count($exp_repeated_columns_old)>0){

$sn=0;
$sheet_no=0;
$counter_r=2;
$get_repeated_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id, survey_data_json, full_json  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc LIMIT 0,2");
while($repeayed_sdmon = mysqli_fetch_object($get_repeated_sdmon)){
    $full_json=$repeayed_sdmon->full_json;
    $surveysid=$repeayed_sdmon->survey_id;
    //echo "<br>";
    $fulljsondata = json_decode($full_json, true);
    //print_r($fulljsondata);
	$sheet_no++;
    $sheet_sl=1;
	
    foreach($fulljsondata as $fulljsonKey=>$repeated){
        
        if(is_array($repeated)){
            if($fulljsonKey!="survey_data" && $fulljsonKey!="hint_record" && $fulljsonKey!="error_record"){
				//$counter_r=2;
                $group_name = $fulljsonKey;
                if($sn=="0"){
					$counter=2;
				}else{
					$arrcount = count($repeated);
					$counter=2+$arrcount;
				}
                $sn++;
                
				
				/// SET REPEATED HEADINGS
				$export_repeated_headers=array();
				$get_group = mysqli_query($conn,"SELECT id FROM questions_group WHERE group_name='".$fulljsonKey."' AND survey_id='".$survey_id_exported."'");
				$group = mysqli_fetch_object($get_group);
				$group_id = $group->id;
				
				$get_expt_fields = mysqli_query($conn,"SELECT * FROM report_format WHERE survey_id='".$survey_id_exported."' AND group_id='".$group_id."' AND title!='' ORDER BY seq_no ASC");
				while($expt_fields = mysqli_fetch_object($get_expt_fields)){
					$export_repeated_headers[]=$expt_fields->field_lable;//str_replace(",","~",$expt_fields->title);
					
					$exp_repeated_columns[] = $expt_fields->field_lable;
				}
				if($sheet_no=="1"){
					

					$objPHPExcel->createSheet($sheet_sl);
					$objPHPExcel->setActiveSheetIndex($sheet_sl);

					$objPHPExcel->getActiveSheet()->setCellValue("A1", "Survey Id");
					foreach($export_repeated_headers as $headKeyR=>$export_headerR){
						$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$headKeyR+1]."1", $export_headerR);
						//echo "<br>";
					}
					$objPHPExcel->getActiveSheet()->getStyle("A1:U1")->getFont()->setBold( true );
					
					$sheet_sl++;
				}
				
				
				///END REPEATED HEADINGS
				// $counter_r=2;
                foreach($repeated as $rptkey=>$repeatedq){
                    
                    $ss=0;
                    $ss++;

                    $objPHPExcel->getActiveSheet()->setCellValue("A".$counter_r, $surveysid);
                        //echo "<br>";
						
                    foreach($repeatedq as $repeatedqs){
						//print_r($repeatedqs);
                        $option_value = $repeatedqs['option_value'];
                        $option_id = $repeatedqs['option_id'];
						$option_ids= str_replace(",","','",$option_id);
                        //$input_field_type = $repeatedqs['input_field_type'];
                        $question_id = $repeatedqs['question_id'];
                        $field_name = $repeatedqs['field_name'];
						if($option_id!=""){
							
							//$getoption = mysqli_query($conn,"SELECT option_name FROM options WHERE question_id='".$question_id."' AND option_sequence='".$option_id."' ");
							
							$getoption = mysqli_query($conn,"SELECT GROUP_CONCAT(option_sequence) as option_sequence FROM options WHERE question_id='".$question_id."' AND option_sequence in ('".$option_ids."')");
							$option = mysqli_fetch_object($getoption);
							$option_sequence = $option->option_sequence;
							$option_value= $option_sequence;//$option_id;//"aaa";
							
						}
						
                        //$objPHPExcel->setActiveSheetIndex(1);
                        
                        $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$ss++].$counter_r, $option_value);
                        
                    }
					
                    $sheet_name=$group_name;//"Sheet";
                    $objPHPExcel->getActiveSheet()->setTitle($sheet_name);
                    $counter_r++;
                    
                    //echo "<br>";
                }
                 
            }
        }
    }
}

} */

$file_name = "Import-".str_replace(" ","_",$survey_name).".xlsx";
//SET SHEET ACTIVE DEFAULT
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$file_name.'" ');
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