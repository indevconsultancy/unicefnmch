<?php

//session_start();
/** Error reporting */
ob_start();
include('includes/config.php');
include_once('api/mycrypt.php');
include('includes/functions.php');

error_reporting(E_ALL);
error_reporting(1);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1');
date_default_timezone_set('Asia/Kolkata');

if ($_SESSION['enckey'] === 0) {
    die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */

// include('PHPexcel/Classes/PHPExcel.php');
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
include 'PHPExcel/Classes/PHPExcel.php';

function searchFromArray($array, $search)
{
    $result = array();
    foreach ($array as $key => $value) {
        foreach ($search as $k => $v) {
            if (!isset($value[$k]) || $value[$k] != $v) {
                continue 2;
            }
        }
        //$result[] = $key;
        $result[] = $array[$key];
    }
    return $result;
}

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Satyendra")
    ->setLastModifiedBy("Satyendra")
    ->setTitle("Office 2007 XLSX MQUAD")
    ->setSubject("Office 2007 XLSX MQUAD")
    ->setDescription("MQUAD for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("MQUAD result file");
//$index=1;
for ($i = "A"; $i <= "Z"; $i++) {
    $setValuePosiotion[] = $i;
}

// $survey_id_exported = $_REQUEST['survey_id'];


$survey_id_exported = $_POST['survey_id'];
$identification_type = $_POST['export_type'];


$allOptionss = mysqli_query($conn, "SELECT question_id as qid,option_sequence, option_name,(SELECT DISTINCT(questions_language.field_name) FROM questions_language WHERE questions_language.question_id=qid) AS field_name FROM options_language  WHERE survey_id='" . $survey_id_exported . "'  ");
$allOption = mysqli_fetch_all($allOptionss, MYSQLI_ASSOC);

//$get_expt_fields = mysqli_query($conn,"SELECT id,title,field_lable,msci_report FROM report_format WHERE survey_id='".$survey_id_exported."' AND group_id='0' AND title!='' ORDER BY seq_no ASC");
$get_expt_fields = mysqli_query($conn, "SELECT DISTINCT report_format.id,report_format.title,report_format.field_lable,report_format.msci_report,report_format.seq_no FROM report_format
	INNER JOIN questions_language ON report_format.field_lable = questions_language.field_name AND report_format.survey_id = questions_language.survey_id
	WHERE report_format.survey_id = '" . $survey_id_exported . "' AND report_format.group_id = '0' AND report_format.title != '' AND questions_language.input_field_type != 'note' ORDER BY report_format.seq_no ASC");

while ($expt_fields = mysqli_fetch_object($get_expt_fields)) {
    $export_headers[] = $expt_fields->field_lable; //str_replace(",","~",$expt_fields->title);

    $exp_columns[] = $expt_fields->field_lable;
    $msci_reports[] = $expt_fields->msci_report;
}

/*
$get_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id, survey_data_json, full_json,survey_name  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc");;
while($sdmon = mysqli_fetch_object($get_sdmon)){
        $survey_data_json=$sdmon->survey_data_json;
        $surveyid = $sdmon->survey_id;
		$survey_name = $sdmon->survey_name;
        $jsondata = json_decode($survey_data_json, true);
        foreach($exp_columns as $exp_column){
            $export_main_value[]=$jsondata[$exp_column]?:"NA"; 
            //str_replace(",","~",$jsondata[$exp_column])?:"NA";
        }
    }
	*/


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue("A1", "uniqueid");
$objPHPExcel->getActiveSheet()->setCellValue("B1", "startDateTime");
$objPHPExcel->getActiveSheet()->setCellValue("C1", "endDateTime");
$objPHPExcel->getActiveSheet()->setCellValue("D1", "device_id");
$objPHPExcel->getActiveSheet()->setCellValue("E1", "GPS");
$objPHPExcel->getActiveSheet()->setCellValue("F1", "User Name");
$objPHPExcel->getActiveSheet()->setCellValue("G1", "SurveyStatus");
$objPHPExcel->getActiveSheet()->setCellValue("H1", "TerminationReason");

foreach ($export_headers as $headKey => $export_header) {
    $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$headKey + 8] . "1", $export_header);
    //echo "<br>";
}

$objPHPExcel->getActiveSheet()->getStyle("A1:AAA1")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
//-----------getting query from school listing------------------//

// echo "<h2>Value</h2>";
$get_sdmon = mysqli_query($conn, "SELECT survey_data_monitoring_id,survey_id,survey_status,termination_reason,survey_name,users.name as full_name, survey_data_json_coded, full_json  FROM survey_data_monitoring left join users on users.user_id=survey_data_monitoring.user_id WHERE survey_name_id = '" . $survey_id_exported . "' and survey_status!='7' order by survey_data_monitoring_id asc");
$counter = 2;
while ($sdmon = mysqli_fetch_object($get_sdmon)) {
    $survey_data_json = $mcrypt->decrypt($sdmon->survey_data_json_coded);

    $survey_name = $sdmon->survey_name;
    $full_jsons = $mcrypt->decrypt($sdmon->full_json);
    $full_json = json_decode($full_jsons, true);

    $survey_id = $sdmon->survey_id;
    $sequence_unique_id = $full_json[sequence_unique_id];
    $uniqueID = ($sequence_unique_id != '') ? $sequence_unique_id : $survey_id;

    $startDateTime = dateformate($full_json['start_date_time']);
    $endDateTime = dateformate($full_json['end_date_time']);
    $device_id = $full_json['device_id'];
    $gps = substr($full_json['GPS_latitude_mid'], 0, 10) . ',' . substr($full_json['GPS_longitude_mid'], 0, 10);
    $UserName = $sdmon->full_name;
    $SurveyStatus = getSurveyStatus($sdmon->survey_status); //create function on function page
    $TerminationReason = $sdmon->termination_reason;

    $jsondata = json_decode($survey_data_json, true);
    $ss = 0;

    $objPHPExcel->getActiveSheet()->setCellValue("A" . $counter, "'" . $uniqueID);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $counter, $startDateTime);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $counter, $endDateTime);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $counter, $device_id);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $counter, $gps);
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $counter, $UserName);
    $objPHPExcel->getActiveSheet()->setCellValue("G" . $counter, $SurveyStatus);
    $objPHPExcel->getActiveSheet()->setCellValue("H" . $counter, $TerminationReason);
    //$counter++;
    $ss++;

    foreach ($exp_columns as $k => $exp_column) {
        $export_main_value[] = $jsondata[$exp_column];
        $mon_data_value = $jsondata[$exp_column];

        $mon_data_value = str_replace(array("-/", "/"), array("-", "-"), $mon_data_value);

        if (strtolower($msci_reports[$k]) == "yes" && ($_SESSION['functional_role_id'] == 7 || $identification_type == 'deidentify')) {
            //$mon_data_value = str_repeat("X", strlen($mon_data_value));
            $mon_value = substr(str_pad($mon_data_value, 3, '*'), 0, 3);
            $mon_data_value = str_repeat("*", strlen($mon_value));
        }

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[7 + $ss++] . $counter, $mon_data_value);
        // echo "<br>";
    }



    $counter++;
}

$objPHPExcel->getActiveSheet()->setTitle('Survey-data');
$objPHPExcel->setActiveSheetIndex(0);

/*
===============================================
==============SHEET ONE COMPLETED==============
===============================================
*/

$get_expt_fields_old = mysqli_query($conn, "SELECT * FROM report_format WHERE survey_id='" . $survey_id_exported . "' AND group_id!='0' AND title!='' ORDER BY seq_no ASC");
while ($expt_fields_old = mysqli_fetch_object($get_expt_fields_old)) {
    //$export_repeated_headers[]=$expt_fields->title;//str_replace(",","~",$expt_fields->title);
    $exp_repeated_columns_old[] = $expt_fields->field_lable;
}
if (count($exp_repeated_columns_old) > 0) {

    // $objPHPExcel->createSheet(1);
    // $objPHPExcel->setActiveSheetIndex(1);

    // $objPHPExcel->getActiveSheet()->setCellValue("A1", "Survey Id");
    // foreach($export_repeated_headers as $headKeyR=>$export_headerR){
    // $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$headKeyR+1]."1", $export_headerR);
    // echo "<br>";
    // }
    //$objPHPExcel->getActiveSheet()->getStyle("A1:U1")->getFont()->setBold( true );
    // $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
    $sn = 0;
    $sheet_no = 0;
    $counter_r = 2;
    $get_repeated_sdmon = mysqli_query($conn, "SELECT survey_data_monitoring_id,survey_id, survey_data_json, full_json  FROM survey_data_monitoring WHERE survey_name_id = '" . $survey_id_exported . "' and survey_status!='7' order by survey_data_monitoring_id asc");
    while ($repeayed_sdmon = mysqli_fetch_object($get_repeated_sdmon)) {
        $full_json = $mcrypt->decrypt($repeayed_sdmon->full_json);
        $surveysid = $repeayed_sdmon->survey_id;

        $fulljsondata = json_decode($full_json, true);
        //print_r($fulljsondata);
        $sheet_no++;
        $sheet_sl = 1;

        foreach ($fulljsondata as $fulljsonKey => $repeated) {

            if (is_array($repeated)) {
                if ($fulljsonKey != "survey_data" && $fulljsonKey != "hint_record" && $fulljsonKey != "error_record") {
                    //$counter_r=2;
                    $group_name = $fulljsonKey;
                    if ($sn == "0") {
                        $counter = 2;
                    } else {
                        $arrcount = count($repeated);
                        $counter = 2 + $arrcount;
                    }
                    $sn++;


                    /// SET REPEATED HEADINGS
                    $export_repeated_headers = array();
                    $get_group = mysqli_query($conn, "SELECT id FROM questions_group WHERE group_name='" . $fulljsonKey . "' AND survey_id='" . $survey_id_exported . "'");
                    $group = mysqli_fetch_object($get_group);
                    $group_id = $group->id;

                    $get_expt_fields = mysqli_query($conn, "SELECT * FROM report_format WHERE survey_id='" . $survey_id_exported . "' AND group_id='" . $group_id . "' AND title!='' ORDER BY seq_no ASC");
                    while ($expt_fields = mysqli_fetch_object($get_expt_fields)) {
                        $export_repeated_headers[] = $expt_fields->field_lable; //str_replace(",","~",$expt_fields->title);

                        $exp_repeated_columns[] = $expt_fields->field_lable;
                    }
                    if ($sheet_no == "1") {


                        $objPHPExcel->createSheet($sheet_sl);
                        $objPHPExcel->setActiveSheetIndex($sheet_sl);

                        $objPHPExcel->getActiveSheet()->setCellValue("A1", "Survey Id");
                        foreach ($export_repeated_headers as $headKeyR => $export_headerR) {
                            $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$headKeyR + 1] . "1", $export_headerR);
                            //echo "<br>";
                        }
                        $objPHPExcel->getActiveSheet()->getStyle("A1:U1")->getFont()->setBold(true);

                        $sheet_sl++;
                    }
                    ///END REPEATED HEADINGS
                    // $counter_r=2;
                    foreach ($repeated as $rptkey => $repeatedq) {
                        $ss = 0;
                        $ss++;

                        $objPHPExcel->getActiveSheet()->setCellValue("A" . $counter_r, $surveysid);

                        foreach ($repeatedq as $repeatedqs) {
                            $option_value = $repeatedqs['option_value'];
                            $option_id = $repeatedqs['option_id'];
                            //$input_field_type = $repeatedqs['input_field_type'];
                            $question_id = $repeatedqs['question_id'];
                            $field_name = $repeatedqs['field_name'];
                            if ($option_id != "") {
                                $getoption = mysqli_query($conn, "SELECT option_name FROM options WHERE question_id='" . $question_id . "' AND option_sequence='" . $option_id . "' ");
                                $option = mysqli_fetch_object($getoption);
                                $option_name = $option->option_name;
                                $option_value = $option_name; //$option_id;//"aaa";
                            }

                            $objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$ss++] . $counter_r, $option_value);
                        }

                        $sheet_name = $group_name; //"Sheet";
                        $objPHPExcel->getActiveSheet()->setTitle($sheet_name);
                        $counter_r++;
                    }
                }
            }
        }
    }
}

$file_name = "ExcelCoded-" . str_replace(" ", "_", $survey_name) . ".xlsx";
//SET SHEET ACTIVE DEFAULT
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
ob_end_clean();

/* header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="'.$file_name.'" ');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
/*
$objWriter->save('php://output');
*/

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('uploaded_questionnaire/abc.xlsx');
//header("Content-Type: application/vnd.ms-excel");

$path = base_url() . "/uploaded_questionnaire/abc.xlsx";
$resArr = array('status' => 200, 'path' => $path, 'fname' => $file_name);
echo json_encode($resArr);
