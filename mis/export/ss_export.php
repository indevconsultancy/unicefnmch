<?php include_once('../includes/config.php'); ?>
<?php
session_start();
	//$export_data = "";
	$survey_qry = $_SESSION['survey_qry'];
	$selected_fields = $_SESSION['selectedfields'];
	
	$get_expt_fields = mysqli_query($conn,"SELECT id,title,field_lable,seq_no FROM report_format WHERE status='0' $selected_fields ORDER BY seq_no ASC");
	while($expt_fields = mysqli_fetch_object($get_expt_fields)){
		$export_data.=str_replace(",","~",$expt_fields->title).",";
		
		$exp_columns[] = $expt_fields->field_lable;
	}
	
	$export_data.="\n";
	//echo "<pre>";
	
	$get_sdmon = mysqli_query($conn,"SELECT survey_data_json,cluster_code,created_on FROM survey_data_monitoring where 1=1 $survey_qry order by survey_data_monitoring_id asc");  
	while($sdmon = mysqli_fetch_object($get_sdmon)){
		$survey_data_json=$sdmon->survey_data_json;
		$jsondata = json_decode($survey_data_json, true);
		foreach($exp_columns as $exp_column){
			$export_data.=str_replace(",","~",$jsondata[$exp_column]).",";
		}
		$export_data.="\n";
	}
	
	$file_name='survey-data-'.date('Ymdhis');
	
	header('Content-Type: application/csv');
	header('Content-Disposition: attachment; filename='.$file_name.'.csv');
	echo $export_data; exit();
  ?>