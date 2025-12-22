<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json; charset=utf-8");
$formdata=$_GET['formID'];
$start=$_GET['countId'];
$api_url = 'https://eu.kobotoolbox.org/api/v2/assets/'.$formdata.'/data/?format=json&start='.$start.'&limit=10';

//$api_url = 'https://eu.kobotoolbox.org/api/v2/assets/aUjYspbGxT8m3J9Tjmv62c/data/?format=json';
$api_key = '7341c8d5e89637cf43252724a6f27b478c3a22a5';  // Replace with your Kobo API key

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Token $api_key",
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);
echo $response;
if (curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
} else {
	$data = json_decode($response, true);
	$arr=array();
	$insertString='';
	$fieldsArr=['_id','formhub__uuid','start','end','today','grp_1__mon_name','grp_1__mon_org','grp_2__district','grp_2__project_name','grp_2__sector','grp_2__gramp_name','grp_2__lgd_code','grp_2__village','grp_2__awc_name','grp_2__code_awc','grp_2__aww_name','grp_2__aww_mob','grp_3v__electricity','grp_3v__toilet','grp_3v__drinking_water','grp_3v__ro_purifier','grp_3v__poshan_vatika','grp_3v__painting','grp_17__hygiene_1','grp_17__IEC_1','grp_4__digital_weighing_machine','grp_4__infanto','grp_4__stadio','grp_4__baby_weighing','grp_6__egg_list','grp_6__peanut_list','grp_6__milk_list','grp_6__hcm_list','grp_6__hcm_menu','grp_6__millet_list','grp_7__reg_05','grp_7__mes_05','grp_7__awc_mam','grp_7__awc_sam','grp_7__awc_suw','grp_7__awc_stunt','grp_7__cmam_vhsnd','grp_7__cmam_enrol','grp_7__nrc_ref','grp_8__child1_name','grp_8__child1_dob','grp_8__child1_gender','grp_8__child1_update','grp_8__child1_rw','grp_8__child1_rhl','grp_8__child1_rcat','grp_8b__child1_cw','grp_8b__child1_chl','grp_9__child2_name','grp_9__child2_dob','grp_9__child2_gender','grp_9__child2_update','grp_9__child2_rw','grp_9__child2_rhl','grp_9__child2_rcat','grp_9b__child2_cw','grp_9b__child2_chl','grp_10__child3_name','grp_10__child3_dob','grp_10__child3_gender','grp_10__child3_update','grp_10__child3_rw','grp_10__child3_rhl','grp_10__child3_rcat','grp_10b__child3_cw','grp_10b__child3_chl','grp_12__aww_knowledge_wt','grp_12__aww_knowledge_ht','grp_16__thr_rec','grp_16__thr_date','grp_16__hom_vis','grp_16__counsel_2','grp_16__preg_couns','grp_17_001__thr_rec_001','grp_17_001__thr_date_001','grp_17_001__hom_vis_001','grp_17_001__counsel_2_001','grp_17_001__childstat','grp_18__thr_rec_002','grp_18__thr_date_002','grp_18__hom_vis_002','grp_18__counsel_2_002','grp_18__childstat_001','grp_19__thr_rec_003','grp_19__thr_date_003','grp_19__hom_vis_003','grp_19__counsel_2_003','grp_19__childstat_002','grp_5__preg_1','grp_5__preg_wt','grp_5a__mo_preg','grp_5a__date','grp_5a__reg_no','grp_5a__CurrPW_wt','__version__','meta__instanceID','_xform_id_string','_uuid','_attachments','_status','_geolocation','_submission_time','_tags','_notes','_validation_status','_submitted_by'];
	foreach($data['results'][0] as $key=>$value)
	{
		//echo $data['results'][$key];
		//print_r($datas[0]);
     $insertString.=$key."='".$value."',";
	}
	$insertString.="'deleted=0'";
	echo $finalString.="insert into suposhit_gram_data set $insertString";
	//$result=$response
	/*
    $data = json_decode($response, true);
	$arr=array();
	$arr['user_id']=0;
	$arr['survey_id']=1;
	$arr['app_version']="Web";
	$arr['survey_status']=1;
	$arr['cluster_no']='';
	$arr['reason_of_change']='';
	$arr['census_district_code']=0;
	$arr['GPS_latitude_start']='';
	$arr['GPS_longitude_start']='';
	$arr['GPS_altitude_start']='';
	$arr['GPS_accuracy_start']='';
	$arr['is_partially']='No';
	$arr['screen_time_out']=0;
	$arr['screen_time_out_duration']="";
	print_r($data['results']);
	foreach($data['results'] as $key=>$value)
	{
		echo $key[0];
		//print_r($datas[0]);
		      
		
	}*/
	
   // print_r($data);  // This will print the form data
	
}

curl_close($curl);
?>

