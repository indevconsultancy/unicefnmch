<?php
// Session start and necessary includes
session_start();
ob_start();
include('includes/config.php');
include_once('api/mycrypt.php');
include('includes/functions.php');


date_default_timezone_set('Asia/Kolkata');

$getclient = mysqli_query($conn,"SELECT distinct(survey_name_id) as snid FROM survey_data_sequence limit 0,1");
while($dataclientID=mysqli_fetch_object($getclient))
{

$client_id= getonefield($conn, 'survey', 'client_id', 'id', $dataclientID->snid);
    echo $dataclientID->snid;
	get_enc_key($client_id);
	$mcrypt = new EncryptionUtils($_SESSION['enckey']);

	$surveyid = $dataclientID->snid;
	$language_id = $_REQUEST['language_id'];
	$identification_type = $_REQUEST['identification_type'];

	echo $SqlQ="SELECT full_json, survey_data_monitoring_id, survey_id FROM survey_data_monitoring WHERE survey_name_id=".$surveyid;

	$getSurveyData=mysqli_query($conn,$SqlQ);
	while($surData = mysqli_fetch_object($getSurveyData)){
		$survey_data_monitoring_id=$surData->survey_data_monitoring_id;
		$fullData = $mcrypt->decrypt($surData->full_json);
		$full_json = json_decode($fullData);
		$survey_id = $mdata->survey_id;
		$sequence_unique_id = $full_json->sequence_unique_id;
		echo "update survey_data_monitoring set survey_data_id='".$sequence_unique_id."' where survey_data_monitoring_id='".$survey_data_monitoring_id."'";
		//mysqli_query($conn,"update survey_data_monitoring set survey_data_id='".$sequence_unique_id."' where survey_data_monitoring_id='".$survey_data_monitoring_id."'");
	}
}
?>
