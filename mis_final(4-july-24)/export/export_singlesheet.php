<?php include_once('../includes/config.php'); ?>
<?php
session_start();

function getAnswer($conn,$tablename,$field,$where)
{
//echo  "select $field from $tablename where $qryfeild='".$value."'";
$sn=mysqli_query($conn,"select GROUP_CONCAT($field) as $field from $tablename $where ");
$dn=mysqli_fetch_object($sn);
	return ($dn->$field);
}

$survey_id = $_REQUEST['survey_id'];

$exportdata='<table  border="1"><tr style="background-color:yellow;font-weight: bold;font-style: italic;">';

// $getRepeated = mysqli_query($conn,"SELECT count(id) as totalRepeated FROM report_format WHERE status='0' and survey_id='".$survey_id."' and group_id!='0' and title!='' ORDER BY seq_no ASC");
// $repeated = mysqli_fetch_object($getRepeated);
// $totalRepeated = $repeated->totalRepeated;

$getRepeated = mysqli_query($conn,"SELECT title FROM report_format WHERE status='0' and survey_id='".$survey_id."' and group_id!='0' and title!='' ORDER BY seq_no ASC");
while($repeated = mysqli_fetch_object($getRepeated)){
	$repeated_headers[] = $repeated->title;
}

$get_expt_fields = mysqli_query($conn,"SELECT id,title,field_lable,seq_no FROM report_format WHERE status='0' and survey_id='".$survey_id."' and title!='' ORDER BY group_id,seq_no ASC");

$exportdata.='<td>Survey ID</td>';
while($expt_fields = mysqli_fetch_object($get_expt_fields)){
	$title = $expt_fields->title;
	$exportdata.='<td>'.$title.'</td>';
}
$exportdata.='</tr>';

///END HEADING


//START BODY

$remove_fieldsArr=['survey_id','user_id','termination_reason','reason_of_change','reason','user_type','app_version','survey_status','gps_lat_start','gps_long_start','gps_lat_mid','gps_long_mid','gps_lat_end','gps_long_end','survey_edate','survey_etime'];
$exportdata.='<tr>';
$getsmondata = mysqli_query($conn,"SELECT survey_id,survey_data_json,full_json,survey_name FROM survey_data_monitoring WHERE survey_name_id='".$survey_id."'");

//$exportdata = '<table border="1"><tr style="background-color:yellow;"><td>Survey ID</td><td>Survey Name</td></tr>';
while($smondata = mysqli_fetch_object($getsmondata)){
	//$exportdata.='<tr><td>'.$smondata->survey_id.'</td><td>'.$smondata->survey_name.'</td></tr>';
	$survey_data_json = $smondata->survey_data_json;
	$jsondata = json_decode($survey_data_json, true);
	$surveyid = $smondata->survey_id;

	$jsondata = array_diff_key($jsondata, array_flip($remove_fieldsArr)); 
	
	$exportdata.='<td>'.$surveyid.'</td>';
	foreach($jsondata as $jskey=>$jsondatas){
		//echo $jskey."<br>";
		$exportdata.='<td>'.$jsondatas.'</td>';
	}

	
	
	
	//$exportdata.='<td colspan="'.$totalRepeated.'" ></td>';
	foreach($repeated_headers as $repeated_header){
		$exportdata.='<td style="background-color: #4e4ebb;font-style: italic;font-weight: bold;color: white;">'.$repeated_header.'</td>';
	}
	
	//$exportdata.='</tr>';

//repeated
	$full_json = $smondata->full_json;
	$fulljsondataarr = json_decode($full_json, true);
	
	$colspan = count($fulljsondataarr['survey_data'])+1;

	foreach($fulljsondataarr  as $fulljsonkey=>$fulljsondata){
		
		if($fulljsonkey!="survey_data"){
			if(is_array($fulljsondata)){
				foreach($fulljsondata as $fulljsondatas){
					$exportdata.='<tr>';
					$exportdata.='<td colspan="'.$colspan.'"></td>';
					foreach($fulljsondatas as $fulljsondatass){
						$option_id = $fulljsondatass['option_id'];
						$option_value = $fulljsondatass['option_value'];
						$question_id = $fulljsondatass['question_id'];
						if($option_id!=""){
							$where=" where option_sequence in($option_id) and question_id='".$question_id."' ";
							$answer = getAnswer($conn,"options","option_name", $where);
						}
						else{
							
							$answer = $option_value;
						}
						
						
						$exportdata.='<td>'.$answer.'</td>';
					}
					$exportdata.='</tr>';
				}
			}
		}
		
	}

}
// $exportdata.='</tr>';
$exportdata.='</table>';

 // echo $exportdata; 
// die;

// header('Content-Type: application/csv');
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename=filename.xls');
echo $exportdata; exit();
?>
