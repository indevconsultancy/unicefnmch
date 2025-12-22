<?php
//session_start();
/** Error reporting */
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

$survey_id_exported = $_REQUEST['survey_id'];


// temp DB created
$SqlQ="SELECT full_json, user_id, survey_id FROM survey_data_monitoring WHERE survey_name_id=".$survey_id_exported;
		
		$getSurveyData = mysqli_query($conn,$SqlQ);
		 $dropSql = "DROP TEMPORARY TABLE IF EXISTS temp_full_table".$survey_id_exported;
    mysqli_query($conn,$dropSql);
    $tempTableSql = "CREATE TEMPORARY TABLE IF NOT EXISTS temp_full_table".$survey_id_exported." (
	survey_data_monitoring_id INT AUTO_INCREMENT PRIMARY KEY,
    full_json TEXT,
	user_id INT,
	 
	survey_id VARCHAR(55),
    survey_name_id VARCHAR(255)
)";
mysqli_query($conn,$tempTableSql);


          while($surData = mysqli_fetch_object($getSurveyData)){
    
				$full_json = $mcrypt->decrypt($surData->full_json);
				
                $inser_sql = "INSERT INTO `temp_full_table".$survey_id_exported."` set `full_json`='" . $full_json . "',`survey_id`='" . $surData->survey_id . "',`user_id`=" . $surData->user_id . ", `survey_name_id`='" . $survey_id_exported ."'";
                
				$insertquery = mysqli_query($conn, $inser_sql);
				//$last_id = mysqli_insert_id($conn);
			
 
		}
	
// Temp DB		



$getclientId = mysqli_query($conn,"SELECT client_id, survey_name FROM survey_data_monitoring where survey_name_id='".$survey_id_exported."' and survey_status!=7 limit 1 ");
$clientId = mysqli_fetch_object($getclientId);
$client_id = $clientId->client_id;
$survey_name = $clientId->survey_name;

$user_codes = array();
$user_names = array();
$getUserData = mysqli_query($conn,"SELECT user_id,name,user_code FROM users WHERE client_id='".$client_id."' ");
while($userData = mysqli_fetch_object($getUserData)){
	 $user_codes[$userData->user_id] = $userData->user_code;
	 $user_names[$userData->user_id] = $userData->name;
}

$objPHPExcel->setActiveSheetIndex(0);
        
	$objPHPExcel->getActiveSheet()->setCellValue("A1", "Question");
	$objPHPExcel->getActiveSheet()->setCellValue("B1", "Hints Count");

$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true );
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);

	$data_array=[];
	//echo "SELECT survey_data_monitoring_id,survey_id, hint_record,error_record  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc";
	$get_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id, hint_record,error_record  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id_exported."' and survey_status!=7 order by survey_data_monitoring_id asc");
	while($sdmon = mysqli_fetch_object($get_sdmon)){
		 
		if($sdmon->hint_record!=''){
			$data_array.=$sdmon->hint_record.',';
			//$data_array[]=$sdmon->hint_record;
			
		}
        if($sdmon->error_record!=''){
			$error_recordArr.=$sdmon->error_record.',';
			//$error_recordArr[]=$sdmon->error_record.',';
		}
        $surveyid = $sdmon->survey_id;
    }
	
	 $hintr=substr($data_array,0,-1);
	 $array = explode(',',$hintr);
	
	if ($array[0]=='') {
	  $data_hints=array(); 
	}else{
		$data_hints_arr=array_count_values($array); 
		ksort($data_hints_arr);
		$data_hints = $data_hints_arr;
	} 
	
    //$data_hints=array_count_values($data_array);
    $counter=2;
	foreach($data_hints as $key=>$value ){ 
	//echo $value;
		$question = getone($conn,'questions','field_name','question_id',$key);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$counter, $question);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$counter, $value);
		$counter++;
	}
	//die();
$objPHPExcel->getActiveSheet()->setTitle('Hints Record');
$objPHPExcel->setActiveSheetIndex(0);

/*
===============================================
==============SHEET ONE COMPLETED==============
===============================================
*/


$objPHPExcel->createSheet(1);
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setCellValue("A1", "Question");
$objPHPExcel->getActiveSheet()->setCellValue("B1", "Error Count");

$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true );
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);

	$errors=substr($error_recordArr,0,-1);
	$error_array = explode(',',$errors);
	if ($array[0]=='') {
	  $data_errors=array(); 
	}else{
		$data_errors_arr=array_count_values($error_array); 
		ksort($data_errors_arr);
		$data_errors = $data_errors_arr;
	} 
   // $data_errors=array_count_values($error_array);
    $counter1=2;
	foreach($data_errors as $keyer=>$ervalue ){ 
		$erquestion = getone($conn,'questions','field_name','question_id',$keyer);
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$counter1, $erquestion);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$counter1, $ervalue);
		$counter1++;
	}

$objPHPExcel->getActiveSheet()->setTitle('Error Record');
$objPHPExcel->setActiveSheetIndex(0);
/*
===============================================
==============SHEET TWO COMPLETED==============
===============================================
*/

	//$get_sdmon = mysqli_query($conn,"select survey_id,user_id,JSON_EXTRACT(full_json,'$.survey_data[*].duration') as duration , JSON_EXTRACT(full_json,'$.survey_data[*].field_name') as question from survey_data_monitoring WHERE survey_name_id = '".$survey_id_exported."' and survey_status!=7 order by survey_data_monitoring_id asc");
	
	 $get_sdmon = mysqli_query($conn,"select survey_id,user_id,JSON_EXTRACT(full_json,'$.survey_data[*].duration') as duration , JSON_EXTRACT(full_json,'$.survey_data[*].field_name') as question from temp_full_table".$survey_id_exported." WHERE survey_name_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc");
	
	$counter11d=2;
	while($sdmon = mysqli_fetch_object($get_sdmon))
	{
		$survey_id = $sdmon->survey_id;
		$user_id =  $sdmon->user_id;
		
		$question = explode(",",str_replace('"','',substr($sdmon->question,1,-1)));
		$duration = explode(",",str_replace('"','',substr($sdmon->duration,1,-1)));
		$pdataArr = array_combine($question,$duration);
		$sn=3;
		if($counter11d==2)
		{
			$objPHPExcel->createSheet(2);
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Interview ID");
			$objPHPExcel->getActiveSheet()->setCellValue("B1", "Name");
			$objPHPExcel->getActiveSheet()->setCellValue("C1", "User Code");
			
			foreach($pdataArr as $pdataKey=>$pdata){
				
				$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$sn++]."1", $pdataKey);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true );
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
		}
		
		$sn=3;
		foreach($pdataArr as $pdataKeyv=>$pdatav){
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setCellValue("A".$counter11d, $survey_id);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$counter11d, $user_names[$user_id]);
			$objPHPExcel->getActiveSheet()->setCellValue("C".$counter11d, $user_codes[$user_id]);
			
			$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$sn++].$counter11d, $pdatav);
		}
		$counter11d++;
	}
	
	$objPHPExcel->getActiveSheet()->setTitle('Duration');

/*
===============================================
==============SHEET THREE COMPLETED==============
===============================================
*/

//GPS

/*
===============================================
==============SHEET FOUR COMPLETED==============
===============================================
*/

//$get_stimestampdt = mysqli_query($conn,"select survey_id,user_id,JSON_EXTRACT(full_json,'$.survey_data[*].para_data') as timestamp , JSON_EXTRACT(full_json,'$.survey_data[*].field_name') as question from survey_data_monitoring WHERE survey_name_id = '".$survey_id_exported."' and survey_status!=7 order by survey_data_monitoring_id asc");

 $get_sdmont = mysqli_query($conn,"select survey_id,user_id,JSON_EXTRACT(full_json,'$.survey_data[*].timestamp') as timestamp, JSON_EXTRACT(full_json,'$.survey_data[*].field_name') as question from temp_full_table".$survey_id_exported." WHERE survey_name_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc");


	$counter11t=2;
	while($timesont = mysqli_fetch_object($get_sdmont))
	{
		$survey_id = $timesont->survey_id;
		$user_id =  $timesont->user_id;
		$timedata=str_replace('"','',substr ($timesont->timestamp,1,-1));
		$timereplace=$timereplace=str_replace("-/", "-",$timedata);
		$timestamp = explode(",",$timereplace);
		//$timestamp = explode(",",str_replace('"','',substr ($timesont->timestamp,1,-1)));
		$question = explode(",",str_replace('"','',substr($timesont->question,1,-1)));
		$gdataArr = array_combine($question,$timestamp);
		
		$gpssn=3;
		if($counter11t==2)
		{
			$objPHPExcel->createSheet(3);
			$objPHPExcel->setActiveSheetIndex(3);
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Interview ID");
			$objPHPExcel->getActiveSheet()->setCellValue("B1", "Name");
			$objPHPExcel->getActiveSheet()->setCellValue("C1", "User Code");
			
			foreach($gdataArr as $gdataKey=>$gdata){
				
				$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$gpssn++]."1", $gdataKey);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true );
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
		}
		
		$gpssn=3;
		foreach($gdataArr as $gdataKeyv=>$gdatav){
		//	echo $gdataKeyv;
			$objPHPExcel->setActiveSheetIndex(3);
			$objPHPExcel->getActiveSheet()->setCellValue("A".$counter11t, $survey_id);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$counter11t, $user_names[$user_id]);
			$objPHPExcel->getActiveSheet()->setCellValue("C".$counter11t, $user_codes[$user_id]);
			$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$gpssn++].$counter11t, $gdatav);
		}
		
		$counter11t++;
	}

$objPHPExcel->getActiveSheet()->setTitle('Timestamp');



/*
===============================================
==============SHEET TWO COMPLETED==============
===============================================
*/

	$get_sdmon = mysqli_query($conn,"select survey_id,user_id,JSON_EXTRACT(full_json,'$.survey_data[*].key_stroke') as key_stroke , JSON_EXTRACT(full_json,'$.survey_data[*].field_name') as question from temp_full_table".$survey_id_exported." WHERE survey_name_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc");
	
	$counter11d=2;
	while($sdmon = mysqli_fetch_object($get_sdmon))
	{
		$survey_id = $sdmon->survey_id;
		$user_id =  $sdmon->user_id;
		
		$question = explode(",",str_replace('"','',substr($sdmon->question,1,-1)));
		$key_stroke = explode(",",str_replace('"','',substr($sdmon->key_stroke,1,-1)));
		$pdataArr = array_combine($question,$key_stroke);
		$sn=3;
		if($counter11d==2)
		{
			$objPHPExcel->createSheet(2);
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Interview ID");
			$objPHPExcel->getActiveSheet()->setCellValue("B1", "Name");
			$objPHPExcel->getActiveSheet()->setCellValue("C1", "User Code");
			
			foreach($pdataArr as $pdataKey=>$pdata){
				
				$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$sn++]."1", $pdataKey);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true );
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
		}
		
		$sn=3;
		foreach($pdataArr as $pdataKeyv=>$pdatav){
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setCellValue("A".$counter11d, $survey_id);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$counter11d, $user_names[$user_id]);
			$objPHPExcel->getActiveSheet()->setCellValue("C".$counter11d, $user_codes[$user_id]);
			
			$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$sn++].$counter11d, $pdatav);
		}
		
		$counter11d++;
		//print_r($pdataArr);
	}
	
	 $objPHPExcel->getActiveSheet()->setTitle('Key_stroke');
	
///END Keystrok

//Start GPS

	$get_sdmon = mysqli_query($conn,"select survey_id,user_id,JSON_EXTRACT(full_json,'$.survey_data[*].gps') as gps , JSON_EXTRACT(full_json,'$.survey_data[*].field_name') as question from temp_full_table".$survey_id_exported." WHERE survey_name_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc");
	
	$counter11d=2;
	while($sdmon = mysqli_fetch_object($get_sdmon))
	{
		
		$survey_id = $sdmon->survey_id;
		$user_id =  $sdmon->user_id;
		
		$question = explode(",", str_replace('"', '', substr($sdmon->question, 1, -1)));
		$gps = explode("~", str_replace('", "', '~', substr($sdmon->gps, 2, -2)));
		if (count($question) == count($gps)) {
			$pdataArrg = array_combine($question, $gps);
		} else {
			echo "Arrays are not of equal length.";
		}

		$sn=3;
		if($counter11d==2)
		{
			$objPHPExcel->createSheet(2);
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setCellValue("A1", "Interview ID");
			$objPHPExcel->getActiveSheet()->setCellValue("B1", "Name");
			$objPHPExcel->getActiveSheet()->setCellValue("C1", "User Code");
			
			foreach($pdataArrg as $pdataKey=>$pdata){
				
				$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$sn++]."1", $pdataKey);
			}
			
			$objPHPExcel->getActiveSheet()->getStyle("A1:Z1")->getFont()->setBold( true );
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
		}
		
		$sn=3;
		foreach($pdataArrg as $pdataKeyv=>$pdatav){
			$objPHPExcel->setActiveSheetIndex(2);
			$objPHPExcel->getActiveSheet()->setCellValue("A".$counter11d, $survey_id);
			$objPHPExcel->getActiveSheet()->setCellValue("B".$counter11d, $user_names[$user_id]);
			$objPHPExcel->getActiveSheet()->setCellValue("C".$counter11d, $user_codes[$user_id]);
			
			$objPHPExcel->getActiveSheet()->setCellValue($setValuePosiotion[$sn++].$counter11d, $pdatav);
		}
		
		$counter11d++;
		
	}
	
	 $objPHPExcel->getActiveSheet()->setTitle('GPS');
//End GPS


$file_name = "Paradata-".str_replace(" ","_",$survey_name).".xlsx";

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
$objWriter->save('uploaded_questionnaire/paradata.xlsx');
//header("Content-Type: application/vnd.ms-excel");

$path = base_url()."/uploaded_questionnaire/paradata.xlsx";
$resArr = array('status'=>20033,'path'=>$path,'fname'=>$file_name);
echo json_encode($resArr);
 
?>