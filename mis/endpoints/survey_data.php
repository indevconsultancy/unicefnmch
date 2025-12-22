<?php

include('config.php');
require '../s3bucket/s3_bucket_config.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
header('Content-Type: application/json');

$surveyid=$_REQUEST['ssidd'];


	$batch_final=array();
	$batches1=array();
	$optionsDetails=array();
	$optionsDetail=array();
	$question_types=array();
	$question_input_types=array();
	$validations=array();
	$languages=array();
	$screens=array();
	$Maingroup=array();
	$LanguageGroup=array();
	$LanguageId=array();

	///////////////////////////////////////////////
	$lookups_value=strtolower('yes');
	
	$sqllookups = mysqli_query($conn, "SELECT COUNT(lookups) AS tot_lookups FROM questions_language WHERE survey_id='" . $surveyid . "' AND lookups='".$lookups_value."'");
	$getlookups = mysqli_fetch_array($sqllookups);
	 $tot_lookups = $getlookups['tot_lookups'];
	
	if ($tot_lookups > 0) {
		$sqllookupss = mysqli_query($conn, "SELECT loockup_data FROM survey_loockups WHERE survey_id='" . $surveyid . "'");
		$getlookupss = mysqli_fetch_array($sqllookupss);
		// Decode JSON data to PHP array
		$lookups = json_decode($getlookupss['loockup_data'], true); 
		
		if (!is_array($lookups) && empty($lookups)) {
			
			$ress = array("status" => 3,"message" => "Lookups Files are not available!");
			echo json_encode($ress);
			exit;
		}
		
	}
	$sqlsurvey = mysqli_query($conn,"SELECT id,user_id,client_id,status FROM survey where id='".$surveyid."' ");
	$getSurvey=mysqli_fetch_array($sqlsurvey);
	$client_id=$getSurvey['client_id'];
	$survey_id=$getSurvey['id'];
	
	$sqlmedia = mysqli_query($conn, "SELECT COUNT(media_file) AS tot_media FROM questions_language WHERE survey_id='".$surveyid."' AND media_file!=''");
	$getMedia = mysqli_fetch_array($sqlmedia);
	$tot_media = $getMedia['tot_media'];
		
	$clientId="C".$client_id;
	//$location = "mis/medialookups/" . $clientId . "/" . $survey_id . "/";
 	 $bucket = 'mquaddata';
	 if ($tot_media > 0 && $tot_media!='') {
		/* $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $location;
		if (!file_exists($absolute_path)) {
			$ress = array("status" => 2, "total" => $tot_media, "message" => "Media Files are not available!");
			echo json_encode($ress);
			exit;
		} */
		// Check if the folder exists
         $existingFiles = $s3Client->listObjectsV2([
            'Bucket' => $bucket,
            'Prefix' => 'media_lookups/' . $clientId . '/' . $survey_id . '/',
        ]); 
		 /*if (!isset($existingFiles['Contents']) || empty($existingFiles['Contents'])) {
			echo json_encode(["status" => 2, "total" => $tot_media, "message" => "Media Files are not available!"]);
			exit;
		}*/
	 } 
	 
	
	if ($getSurvey['status'] == 1) {
		$ress = array("status" => 0, "message" => "Already Published");
		echo json_encode($ress);
	} else if ($getSurvey['status'] == 0) {
		$update = mysqli_query($conn, "UPDATE survey SET status='1' WHERE id='".$surveyid."'");
		$hasScreens=[];  // This questions have screen question
		$getEndGroupsQuestions = mysqli_query($conn,"SELECT question_id, dictionary_label, field_name, input_field_type, relevant,normal_group_id, repeat_count, repeated, screened_count , screened,parent_relevent,roster_relevent,appearance FROM `questions_language` WHERE survey_id='".$surveyid."' AND language_id='1' AND input_field_type='end_group' AND ( ( screened!='' AND screened_count!='') || (input_field_type='end_group' AND repeated!='' AND repeat_count!='') ) ");
		while($endGroupQuestions = mysqli_fetch_array($getEndGroupsQuestions, MYSQLI_ASSOC)){
			
			$endgqId = $endGroupQuestions['question_id'];
			// $getQuess = mysqli_query($conn,"SELECT question_id FROM questions where survey_id='".$surveyid."' AND input_field_type!='end_group' AND dictionary_label!='' AND (screened='' || screened IS null) AND (repeated='0' || repeated='' || repeated IS null) AND question_id<'".$endgqId."' order by question_id desc limit 1  ");
			//$getQuess = mysqli_query($conn,"SELECT question_id FROM questions_language where survey_id='".$surveyid."' AND language_id='1' AND input_field_type!='end_group' AND dictionary_label!='' AND ((screened_count='screen') || (screened='' || screened IS null)  )  AND (repeated='0' || repeated='' || repeated IS null) AND question_id<'".$endgqId."' order by question_id desc limit 1  ");
			$getQuess = mysqli_query($conn,"SELECT question_id FROM questions where survey_id='".$surveyid."' AND input_field_type!='end_group' AND dictionary_label!='' AND ((screened='start') || (screened_count='' || screened_count IS null)  )  AND (repeated='0' || repeated='' || repeated IS null) AND question_id<'".$endgqId."' order by question_id desc limit 1  ");
			$quess=mysqli_fetch_array($getQuess);
			$questionId = $quess['question_id']; // Question id which questions have screen questions
			$hasScreens[$questionId][] = $endGroupQuestions;
		}
			

	  $getSurveyLanguages = mysqli_query($conn, "SELECT GROUP_CONCAT(DISTINCT(language_id)) AS language_ids FROM questions_language WHERE survey_id='".$surveyid."' ");
	  $surveyLanguages = mysqli_fetch_object($getSurveyLanguages);
	  $language_ids = $surveyLanguages->language_ids;
	 
	  $languageQuery = mysqli_query($conn, "SELECT language_id FROM languages where status=0 and language_id IN ($language_ids) order by language_id asc");
		while($language_data = mysqli_fetch_object($languageQuery)){
			$LanguageId['language_id'] = $language_data->language_id;
			$groupQuery = mysqli_query($conn, "SELECT distinct(group_id) as group_id FROM questions_language where status=0 and language_id='".$language_data->language_id."' and survey_id='".$surveyid."' order by group_id asc");
			while($groups = mysqli_fetch_object($groupQuery)){
				$groupId['group_id'] = $groups->group_id;
				$screenQuery = mysqli_query($conn, "SELECT distinct(screen_no) FROM questions_language where status=0 and group_id='".$groups->group_id."' and language_id='".$language_data->language_id."' and survey_id='".$surveyid."' order by screen_no asc");
				while($screen = mysqli_fetch_object($screenQuery)){
					
					$batchQuery = mysqli_query($conn, "SELECT questions_language.* FROM questions_language,questions  where questions.question_id=questions_language.question_id and questions_language.status=0 and questions_language.question_name!='' and questions_language.language_id='".$language_data->language_id."' and questions_language.screen_no='".$screen->screen_no."' and questions_language.group_id='".$groups->group_id."' and questions_language.survey_id='".$surveyid."' order by questions.sequence_no asc");
					while($batches = mysqli_fetch_object($batchQuery)){
					$normal_group_id = $batches->normal_group_id;
					$survey_idnormal = $batches->survey_id; 
					$pre_group_id=0;
					if($normal_group_id!=''){ 
						$grpArr = explode(",",$normal_group_id);
						$pre_group =  count($grpArr)-1;
						$pre_group_id = $grpArr[$pre_group];
					}
					$conditions='';
					$getGroup_conditions = mysqli_query($conn,"SELECT GROUP_CONCAT(conditions SEPARATOR ' & ') AS conditions FROM normal_groups WHERE survey_id='".$survey_idnormal."' AND id IN($pre_group_id) ");
					$group_conditions = mysqli_fetch_object($getGroup_conditions);
					$conditions = $group_conditions->conditions;
		
					$send_condition = '';
					$conditions1 = '';
					$relevant='';
					$relevant = $batches->relevant;
					// die();
					
					if($conditions!=''){
						
						if($relevant!=''){
							if(strpos($relevant,"!=")>0){
								$send_condition=$relevant."&(".$conditions.")";
							}else{
								$send_condition=$relevant;
							}
							
						}else{
							
							if((strpos($conditions,"!=")>0)){
								$pre_group =  count($grpArr)-1;
								$pre_group1 =  count($grpArr)-2;
								if($pre_group1<0){ $pre_group1=0; }
								$pre_group_id = $grpArr[$pre_group].",".$grpArr[$pre_group1];
								$getGroup_conditions1 = mysqli_query($conn,"SELECT GROUP_CONCAT(conditions SEPARATOR ' & ') AS conditions FROM normal_groups WHERE survey_id='".$survey_idnormal."' AND id IN($pre_group_id) ");
								$group_conditions1 = mysqli_fetch_object($getGroup_conditions1);
								$conditions1 = $group_conditions1->conditions;
								$send_condition=$conditions1;
							}
							else{
								$send_condition=$conditions;
							}
							//$send_condition=$conditions;
						}
					}else{ 
						$send_condition=$relevant;
					}
		
					/////////////////////// questions /////////////////

					$batch['question_id'] = $batches->question_id;
					$batch['language_id'] = 1;
					$questions_type_id=1;
					$question_input_type_id="";
					if($batches->input_field_type=='number'){
						$questions_type_id = "2";
						$question_input_type_id = "2";
					}
					if($batches->input_field_type=='text'){
						$questions_type_id = "1";
					}
					/*if($batches->input_field_type=='radio'){
						$questions_type_id = "2";
					}*/
					if($batches->input_field_type=='select_one'){
						$questions_type_id = "4";
					}

					if($batches->input_field_type=='select_multiple'){
						$questions_type_id = "3";
					}
					if($batches->input_field_type=='header'){
						$questions_type_id = "6";
						$question_input_type_id = "4";
					}
					$batch['question_name'] = $batches->question_name;
					$batch['question_type'] = $batches->questions_type_id;
					$batch['question_input_type'] = $batches->input_field_type;//$batches->question_input_type_id;
					$batch['validation_id'] = $batches->validation_id;
					$batch['max_limit'] = $batches->max_input;
					$batch['pre_field'] = $batches->prefill;
					$batch['screen_no'] = $batches->screen_no;
					$batch['group_id'] = $batches->group_id;
					$batch['group_relation_id'] = $batches->group_relation_id;
					$batch['field_name'] = $batches->field_name;
					$batch['ref_table'] = $batches->ref_table;
					$batch['parameters'] = $batches->parameters;
					$batch['read_only'] = $batches->read_only;
					$batch['calculation'] = $batches->calculation;
					$batch['constraints'] = $batches->constraints;
					$batch['constraint_msg'] = $batches->constraint_msg;
					$batch['repeat_count'] = in_array($batches->repeat_count, [null, '']) ? '' : $batches->repeat_count;

					$batch['relevant'] =   $send_condition?:""; //$batches->relevant;
					$batch['limit'] = $batches->limit?:"";
					$batch['required'] = $batches->required;
					$batch['choice_filter'] = $batches->choice_filter;
					$batch['appearance'] = $batches->appearance;
					$batch['choice_relation'] = $batches->choice_relation;
					$batch['default_response'] = $batches->default_response?:"";
					$batch['repeated'] = $batches->repeated;
					$batch['question_description'] = $batches->question_description?:"";
					$batch['paradata'] = $batches->paradata?:""; 
					$batch['unique_id'] = $batches->unique_id?:""; 
					$batch['preserve'] = $batches->preserve?:"";
					$batch['remarks'] = "";
					$batch['lookups'] = $batches->lookups?:"";
					$batch['media_file'] = $batches->media_file?:"";
					$batch['screened'] = $batches->screened?:"";
					$batch['screened_count'] = $batches->screened_count?:"";
					$batch['parent_relevent'] = $batches->parent_relevent?:"";
					$batch['roster_relevent'] = $batches->roster_relevent?:"";
					// echo $field_name[$batches->screened_count];
					/////////////// OPTIONS DETAILS////////////////////
					$optionsDetail=array();
					if($batches->input_field_type=='select_one' || $batches->input_field_type=='select_multiple' ){
						$OptionQuery = mysqli_query($conn, "SELECT options_language_id,option_id,option_name,option_sequence,is_terminate,option_type,media_file,choice_filter_parent,option_constraint FROM options_language WHERE question_id='".$batches->question_id."' and language_id='".$language_data->language_id."' and status=0 order by options_language_id");
						while($options = mysqli_fetch_object($OptionQuery)){
							$optionsDetails['option_value'] = $options->option_name;

							//$optionsDetails['option_id'] = $options->option_id;
							$optionsDetails['option_id'] = $options->option_sequence;
							$optionsDetails['is_terminate'] = $options->is_terminate;
							$optionsDetails['option_type'] = $options->option_type?:"";
							$optionsDetails['likert_img'] = $options->media_file;//$options->likert_img?:"";
							$optionsDetails['choice_filter_parent'] = $options->choice_filter_parent?:"";
							$optionsDetails['constraint'] = $options->option_constraint?:"";
							$optionsDetail[]=$optionsDetails;
						}
					}
					//---Khushboo---- Other question on screened and screened_count value remove because this condition is use anonymous question
					if($batches->screened!="" && $batches->screened_count!="" && $batches->relevant!=""){
						// echo $batches->field_name;
						$batch['screened']='';
						 $batch['screened_count']= '';
						
					}
					////////////////
					$batch['question_options'] = $optionsDetail;
					
					$batches1[]=$batch;
					$optionsDetail=array();
					
					/// anonymous question custom 20-10-2023
					$anonymousq = '';
					$conditions1=$conditions;
					//echo $batches->parent_relevent;
					if($batches->screened!="" && $batches->relevant!=""){
						
						if(!empty($batches->parent_relevent)){
							 $conditions1=$batches->parent_relevent?:"";
							if(!empty($conditions)){
							$conditions1=$conditions." & ".$batches->parent_relevent?:"";
							}
							
						}
						
						$anonymousq = $batch;
						
						$anonymousq['question_name'] = uniqid(rand());
						$anonymousq['field_name'] = uniqid(rand());
						$anonymousq['question_id'] = "1";
						$anonymousq['question_type'] = "21";
						$anonymousq['question_input_type'] = "anonymous";
						$anonymousq['screened'] = $batches->screened;
						$anonymousq['screened_count'] = $batches->screened_count?:"";
						$anonymousq['relevant'] = $conditions1?:"";
						$anonymousq['parent_relevent'] = "";
						$anonymousq['roster_relevent'] = "";
						$anonymousq['question_options'] = [];
						
						$batch['screened'] = "";
						$batch['screened_count'] = "";
						$batch['parent_relevent'] = "";
						$batch['roster_relevent'] = "";
						
					}
					
					if(!empty($anonymousq)){
						$batches1[]=$anonymousq;
					}
					
					//18-02-20224 start
					$anonymousq = '';
					//echo print_r($hasScreens);
					if(array_key_exists($batches->question_id, $hasScreens)){
						//echo "exist";
						$allqs = $hasScreens[$batches->question_id];
						//print_r($allqs);
						foreach($allqs as $q){
							$aArr =  explode(",",$q['normal_group_id']);
							//$ngroupId = !empty($aArr) ? $aArr[count($aArr)-1] : null;
							$ngroupId = implode(",",$aArr);
							
							// $getGroupconditions = mysqli_query($conn,"SELECT conditions FROM normal_groups WHERE survey_id='".$surveyid."' AND id='".$ngroupId."' ");
							$getGroupconditions = mysqli_query($conn,"SELECT GROUP_CONCAT(conditions SEPARATOR ' & ') AS conditions FROM normal_groups WHERE survey_id='".$surveyid."' AND id IN($ngroupId) ");
							$groupconditions = mysqli_fetch_object($getGroupconditions);
							$anonymsconditions = $groupconditions->conditions;
					
							$anonymousq = $batch;
							$anonymousq['question_name'] = uniqid(rand());
							$anonymousq['field_name'] = uniqid(rand());
							$anonymousq['question_id'] = "1";
							$anonymousq['question_type'] = "21";
							$anonymousq['question_input_type'] = "anonymous";
							$anonymousq['screened'] = $q['screened'];						
							$anonymousq['screened_count'] = $q['screened_count'];
							$anonymousq['repeated'] = $q['repeated'];
							$anonymousq['repeat_count'] = $q['repeat_count'];
							$anonymousq['relevant'] = $anonymsconditions?:"";
							$anonymousq['parent_relevent'] = $q['parent_relevent'];
							$anonymousq['roster_relevent'] = $q['roster_relevent'];
							$anonymousq['appearance'] = $q['appearance'];
							$anonymousq['group_id'] = "";
							$anonymousq['question_options'] = [];
							
							$batch['screened'] = "";
							$batch['screened_count'] = "";
							$batch['repeated'] = "";
							$batch['repeat_count'] = "";
							$batch['parent_relevent'] = "";
							$batch['roster_relevent'] = "";
							$batch['constraints'] = "";
							$batch['default_response'] = ""; 
							
							if(!empty($anonymousq)){
								$batches1[]=$anonymousq;
							}
						}
					}
					//print_r($anonymousq);
					//18-02-20224 end
					///
					
				}

				$batch=array();
				$Qscreen['screen_no']=$screen->screen_no;
				$Qscreen['questions']=$batches1;
				$screens[]=$Qscreen;
				$batches1=array();	
			}

			$groupId['screens']=$screens;
			$screens=array();
			$Maingroup[]=$groupId;
		}

		$LanguageId['group']=$Maingroup;
		$LanguageGroup[]=$LanguageId;
		$Maingroup=array();
	}



		////////////// Question Type /////////////////////////

		$QtypeQuery = mysqli_query($conn, "SELECT questions_type_name,questions_type_id FROM question_type where status=0");

		while($Qtype = mysqli_fetch_object($QtypeQuery)){
			$question_type['question_type'] = $Qtype->questions_type_name;
			$question_type['question_type_id'] = $Qtype->questions_type_id;
			$question_types[]=$question_type;
		}

		////////////  Question Input Type /////////////////////////////

		$QIntypeQuery = mysqli_query($conn, "SELECT question_input_type_name,question_input_type_id FROM question_input_type where status=0");
		while($QIntype = mysqli_fetch_object($QIntypeQuery)){
			$question_input_type['question_input_type_name'] = $QIntype->question_input_type_name;
			$question_input_type['questions_input_type_id'] = $QIntype->question_input_type_id;
			$question_input_types[]=$question_input_type;
		}
		////////////  Validation /////////////////////////////

		$ValidQuery = mysqli_query($conn, "SELECT validation_type,validation_id FROM validations where status=0");
		while($ValidData = mysqli_fetch_object($ValidQuery)){
			$validation['validation_type'] = $ValidData->validation_type;
			$validation['questions_input_type_id'] = $ValidData->validation_id;
			$validations[]=$validation;
		}
		$position=1;
		$ValidQuery = mysqli_query($conn, "SELECT id,group_name,survey_id,client_id,group_type FROM questions_group where survey_id='".$surveyid."' order by id ASC");
		while($ValidData = mysqli_fetch_object($ValidQuery)){
			
			if($ValidData->group_type=="group" || $ValidData->group_type=="screen"){
				$questionsgroup['id'] = $ValidData->id;
				$questionsgroup['group_name'] = $ValidData->group_name;
				$questionsgroup['survey_id'] = $ValidData->survey_id;
				$questionsgroup['client_id'] = $ValidData->client_id;
				$questionsgroup['position_id'] = $position;
				$groups[$ValidData->id]=$questionsgroup;
			}else if($ValidData->group_type=="timeline"){
				$questionsgroupt['id'] = $ValidData->id;
				$questionsgroupt['group_name'] = $ValidData->group_name;
				$questionsgroupt['survey_id'] = $ValidData->survey_id;
				$questionsgroupt['client_id'] = $ValidData->client_id;
				$questionsgroupt['position_id'] = $position;
				$groupst[$ValidData->id]=$questionsgroupt;
			}
			$position++;
			
		}	
		
		////////////  Language /////////////////////////////

		$LangQuery = mysqli_query($conn, "SELECT language_name,language_id FROM languages where status=0 and language_id IN ($language_ids) ");
		while($languageData = mysqli_fetch_object($LangQuery)){
			$language['language_name'] = $languageData->language_name;
			$language['language_id'] = $languageData->language_id;
			$languages[]=$language;
		}
		

		$getLoockups = mysqli_query($conn,"SELECT id,survey_id,loockup_data FROM survey_loockups WHERE survey_id='".$surveyid."' ")	;
		while($loockups = mysqli_fetch_object($getLoockups)){
		//$lookp["id"] = $loockups->id;
		//$lookp["survey_id"] = $loockups->survey_id;
		//$lookp["loockup_data"] = $loockups->loockup_data;
		///$lookps[]=$lookp;
		$lookps = $loockups->loockup_data;
		}
		$lookps = json_decode($lookps);
		$lookps = $lookps->lookups;

		////////////  Final JSON /////////////////////////////

		$batch_final['language_group']=$LanguageGroup;
		// $batch_final['group']=$Maingroup;
		//$batch_final['screen']=$screens;
		//$batch_final['questions']=$batches1;
		$batch_final['question_type']=$question_types;
		$batch_final['question_input_type']=$question_input_types;
		$batch_final['validations']=$validations;
		$batch_final['groups']=$groups;
		$batch_final['timeline'] = $groupst;
		$batch_final['language']=$languages;
		$batch_final['loockups']=$lookps;

		/////////////////////////////////////////////////////
		$finalJsonDataCreated = json_encode($batch_final);
		
		
		$path = 'questionsjson/s'.$surveyid.'.json';
		$myfile = fopen($path, "w") or die("Unable to open file..");
		$txt = $finalJsonDataCreated;
		fwrite($myfile, $txt);
		fclose($myfile);
			$ress = array("status" => 1, "message" => " Published");
			echo json_encode($ress);
		} 
	
	// $ress = array("status"=>1,"msg"=>"published");
	// echo json_encode($ress);

?>