<?php 
include_once('includes/config.php');
include_once('includes/functions.php'); 

		$surveyID = $_REQUEST['survey_id'];
		
		if($surveyID){
			$questionnaire_sql = mysqli_query($conn,"SELECT question_json FROM `questionnaires` WHERE survey_id = '".$surveyID."'");

			$questionnaire_obj = mysqli_fetch_object($questionnaire_sql);
			$questionnaire_json = json_decode($questionnaire_obj->question_json);
			
			
			foreach($questionnaire_json->questions as $key => $value){
				$allQuesKeys = [];
				foreach($value as $key2 => $val){
					$allQuesKeys[] = $key2;
				}
				
				$type = $value->type;
				$name = $value->name;
				$label = $value->label;
				$dictionary_label = $value->dictionary_label;
				$limit = $value->limit;
				$constraint = $value->constraint;
				$constraint_message = $value->constraint_message;
				$hint = $value->hint;
				$paradata = $value->paradata;
				$appearance = $value->appearance;
				$choice_filter = $value->choice_filter;
				$repeat_count = $value->repeat_count;
				$calculation = $value->calculation;
				$default_response = $value->default_response;
				$deidentify = $value->deidentify;
				$read_only = $value->read_only;
				$preserve = $value->preserve;
				$unique_id = $value->unique_id;
				$required = $value->required;
				$lookups = $value->lookups;
				$media_file = $value->media_file;
				$parameters = $value->parameters;
				$modifiedques = $value->modifiedques;
				$choice_relation = $value->choice_relation;
				$relevant = $value->relevant;
				
			if($modifiedques == 1){
				
				foreach($allQuesKeys as $allQuesKey){
					$search = 'label::';
					if(preg_match("/{$search}/i", $allQuesKey)) {
						$lcode[] = str_replace("label::","",$allQuesKey);
					}
				}
				$langCodes = "'".implode("','",$lcode)."'";
				$getLaguages = mysqli_query($conn,"SELECT language_id FROM languages WHERE status='0' and language_code in($langCodes) order by language_id asc");
				$language_masters = [];
				$language_masters = array('1');
				while($getLangArr = mysqli_fetch_object($getLaguages)){
					$language_masters[] = $getLangArr->language_id; 
				}
				
			foreach($language_masters as $language_id){
				
				if($type=='begin_group' || $type=='begin_repeat'){
					$name = trim(safe_var($conn,str_replace(" ","",$value->name)));
				}
				
				if($language_id != 1){
					$language_code = getone($conn,"languages","language_code","language_id",$language_id);
					$code = strtoupper($language_code);
					$qwerty = "label::".$code;
					
					$label = $value->$qwerty; 
					$label = $value->$qwerty; 
					$label = $value->$qwerty; 
				}
				
				$sqlquery = mysqli_query($conn, "SELECT question_id FROM questions WHERE survey_id = '".$surveyID."' and field_name = '".$name."'");
				$questionidObj = mysqli_fetch_object($sqlquery);
				$questionID = $questionidObj->question_id; 
				
				if($questionID != ''){
					
				
					if($type=='begin_group' || $type=='begin_repeat'){
						if($language_id  == 1){
							
							$beginGroupSql1 = mysqli_query($conn,"update questions set appearance='".$appearance."', relevant='".$relevant."' WHERE question_id='".$questionID."'");
							
							$beginGroupSql2 = mysqli_query($conn,"update questions_language set appearance='".$appearance."', relevant='".$relevant."' WHERE question_id='".$questionID."' and language_id = '".$language_id."'");
							
							$checkNormalGrpSql = mysqli_query($conn, "SELECT * from normal_groups WHERE survey_id ='".$surveyID."' and group_name = '".$name."'");
							
							if(mysqli_num_rows($checkNormalGrpSql) == 1){
								$beginGroupSql3 = mysqli_query($conn,"update normal_groups set conditions='".$relevant."' WHERE survey_id ='".$surveyID."' and group_name = '".$name."'");
							}
							else{
								$beginGroupSql4 = mysqli_query($conn,"INSERT INTO normal_groups set conditions='".$relevant."', survey_id ='".$surveyID."', group_name = '".$name."'");
							}
							
						}
						/* else{
							
							$beginGroupSql = mysqli_query($conn,"update questions_language set appearance='".$appearance."' WHERE question_id='".$questionID."' and language_id = '".$language_id."'");
							
						} */
					}
					
					if($type == "end_group" || $type == "end_repeat"){
						/* skip for update */
					}
					
					else{
						
							if($language_id == 1){
								
							
								$updateQuestions = mysqli_query($conn,"update questions set question_name='".$label."',dictionary_label='".$dictionary_label."',encrpt='".$deidentify."',question_description='".$hint."',repeat_count='".$repeat_count."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraint."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' where question_id='".$questionID."'");
								
								$updateQuestionsLanguage = mysqli_query($conn,"update questions_language set question_name='".$label."',dictionary_label='".$dictionary_label."',encrpt='".$deidentify."',question_description='".$hint."',repeat_count='".$repeat_count."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraint."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' where question_id='".$questionID."' and language_id = '".$language_id."'");
								
							}
							
							else{
								
								
							if($type=='begin_group' || $type=='begin_repeat'){
								/* SKip group questions */
							}
							
							else{
							
								$sqlquery = mysqli_query($conn, "SELECT question_id FROM questions_language WHERE survey_id = '".$surveyID."' and field_name = '".$name."' and language_id = '".$language_id."'");
								$questionidObj = mysqli_fetch_object($sqlquery);
								$questionIDs = $questionidObj->question_id; 
								
								if($questionIDs != ''){
									
										$updateQuestionsLanguage = mysqli_query($conn,"update questions_language set question_name='".$label."',dictionary_label='".$dictionary_label."',encrpt='".$deidentify."',question_description='".$hint."',repeat_count='".$repeat_count."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraint."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' where question_id='".$questionIDs."' and language_id = '".$language_id."'");
									}
								else{
									
									$sql = mysqli_query($conn,"SELECT questions_type_id,question_input_type_id,screen_no,validation_id,sequence_no FROM questions_language WHERE question_id='".$questionID."' and survey_id='".$surveyID."' and language_id = 1");
									$existingData = mysqli_fetch_object($sql);
									$questions_type_id = $existingData->questions_type_id;
									$question_input_type_id = $existingData->question_input_type_id;
									$screen_no = $existingData->screen_no;
									$validation_id = $existingData->validation_id;
									$sequence_no = $existingData->sequence_no;
								
									$updateQuestionsLanguage = mysqli_query($conn,"insert into questions_language set field_name='".$name."',title='".$name."',category_name='".$name."',question_name='".$label."',dictionary_label='".$dictionary_label."',encrpt='".$deidentify."',question_description='".$hint."',repeat_count='".$repeat_count."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraint."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."', question_id='".$questionID."',survey_id='".$surveyID."', language_id = '".$language_id."', input_field_type = '".$type."', questions_type_id = '".$questions_type_id."', question_input_type_id = '".$question_input_type_id."', screen_no = '".$screen_no."', validation_id = '".$validation_id."', sequence_no = '".$sequence_no."'");
									
									$submitSQL = mysqli_query($conn,"SELECT * FROM questions_language WHERE questions_type_id = 16 AND survey_id='".$surveyID."' AND language_id = 1");
									$submitbtnData = mysqli_fetch_object($submitSQL);
									$sequence_no = $submitbtnData->sequence_no;
									$submitQuesID = $submitbtnData->question_id;
									
									
									$submitSQL2 = mysqli_query($conn,"SELECT * FROM questions_language WHERE questions_type_id = 16 AND survey_id='".$surveyID."' AND language_id = '".$language_id."'");
									$isAvail = mysqli_num_rows($submitSQL2);
									
									
									if($isAvail == 0){
										$updateSubmitType =mysqli_query($conn,"insert into questions_language set language_id='".$language_id."', question_id='".$submitQuesID."' ,question_name='Your survey is complete. Do you want to submit?',question_description='Desc',questions_type_id='16',max_input='0',screen_no='1',input_field_type='note',read_only='',ref_table='',group_id='0',sequence_no='".$sequence_no."',field_name='submit',validation_id='0',title='Submit',survey_id='".$surveyID."' ");
									}
								}
							
							}
						}
					}
				}
			}
		}
	}
			
		foreach($questionnaire_json->choices as $key => $value){
				
					$option_id = safe_var($conn,$value->value);
					$label = $value->label;
					$name = $value->list_name;
					$choice_filter_parent = $value->choice_filter_parent;
					$choiceMediaFile = $value->media_file;
					$choiceConstraint = $value->constraint;
					$modifiedques = $value->modified_ques;
			
		if($modifiedques == 1){
			
				// echo $label; echo "__".$modifiedques."__"; 
					$allQuesKeys = [];
					foreach($value as $key2 => $val){
						$allQuesKeys[] = $key2;
					}
					foreach($allQuesKeys as $allQuesKey){
						$search = 'label::';
						if(preg_match("/{$search}/i", $allQuesKey)) {
							$lcode[] = str_replace("label::","",$allQuesKey);
						}
					}
				$langCodes = "'".implode("','",$lcode)."'";
				$getLaguages = mysqli_query($conn,"SELECT language_id FROM languages WHERE status='0' and language_code in($langCodes) order by language_id asc");
				$language_masters = [];
				$language_masters = array('1');
				while($getLangArr = mysqli_fetch_object($getLaguages)){
					$language_masters[] = $getLangArr->language_id; 
				}
				
			foreach($language_masters as $language_id){	
				
				if($language_id != 1){
					$language_code = getone($conn,"languages","language_code","language_id",$language_id);
					$code = strtoupper($language_code);
					$qwerty = "label::".$code;
					
					$label = $value->$qwerty; 
				}
				$sqlquery = mysqli_query($conn, "SELECT question_id FROM questions_language WHERE survey_id = '".$surveyID."' and choice_relation = '".$name."' and language_id = '".$language_id."'");
				
				$questionidObj = mysqli_fetch_object($sqlquery);
				$questionID = $questionidObj->question_id; 
				if($questionID != ''){
					
					if($language_id == 1){
						
							$sql = mysqli_query($conn,"SELECT * FROM options_language WHERE question_id='".$questionID."' and option_sequence = '".$option_id."' and language_id = '".$language_id."'");
							
							$ifExistData = mysqli_num_rows($sql);
							if($ifExistData > 0){
								
								$options_sql = mysqli_query($conn,"UPDATE options SET option_name='".$label."',choice_filter_parent='".$choice_filter_parent."', media_file= '".$choiceMediaFile."', option_constraint= '".$choiceConstraint."' WHERE question_id='".$questionID."' and option_sequence = '".$option_id."'");
								
								$options_language_sql = mysqli_query($conn,"UPDATE options_language SET option_name='".$label."',choice_filter_parent='".$choice_filter_parent."', media_file= '".$choiceMediaFile."', option_constraint= '".$choiceConstraint."' WHERE question_id='".$questionID."' and option_sequence = '".$option_id."' and language_id = '".$language_id."'");
							}
							else{
								 
								$option_sql = "INSERT INTO options set option_name='".$label."',choice_filter_parent='".$choice_filter_parent."',media_file= '".$choiceMediaFile."', option_constraint= '".$choiceConstraint."',question_id='".$questionID."',serial_no_for_app='1',option_sequence='".$option_id."',category_name='".$name."',survey_id='".$surveyID."'"; 
								mysqli_query($conn,$option_sql);
								$last_id1=mysqli_insert_id($conn);
								
								$options_langSQL = "INSERT INTO options_language set option_id='".$last_id1."',  language_id='".$language_id."',choice_filter_parent='".$choice_filter_parent."',media_file= '".$choiceMediaFile."', option_constraint= '".$choiceConstraint."',option_name='".$label."',question_id='".$questionID."',serial_no_for_app='1',option_sequence='".$option_id."',category_name='".$name."',survey_id='".$surveyID."'";  
								mysqli_query($conn,$options_langSQL);
								
							}
							
							
							
					}
					else{
							
						$sqlquery = mysqli_query($conn, "SELECT * FROM options_language WHERE question_id='".$questionID."' and option_sequence = '".$option_id."' and language_id = '".$language_id."' ");
						$rowCount = mysqli_num_rows($sqlquery);
						
							if($rowCount > 0){
							
								$options_language_sql = mysqli_query($conn,"UPDATE options_language SET option_name='".$label."',choice_filter_parent='".$choice_filter_parent."',media_file= '".$choiceMediaFile."', option_constraint= '".$choiceConstraint."' WHERE question_id='".$questionID."' and option_sequence = '".$option_id."' and language_id = '".$language_id."'");
							}
							else{
								
								$sqlquery = mysqli_query($conn, "SELECT * FROM options WHERE question_id='".$questionID."' and option_sequence = '".$option_id."'");
								$result = mysqli_fetch_object($sqlquery);
								
								$insertOptLngSQL = "INSERT INTO options_language set option_id='".$result->option_id."',  language_id='".$language_id."',option_name='".$label."',choice_filter_parent='".$choice_filter_parent."',question_id='".$questionID."',media_file= '".$choiceMediaFile."', option_constraint= '".$choiceConstraint."',serial_no_for_app='1',option_sequence='".$option_id."',category_name='".$name."',survey_id='".$surveyID."'";  
								mysqli_query($conn,$insertOptLngSQL);
								
							}
							
					}
				}
			}
		}
			
			
		}
		$getSurvey=mysqli_query($conn,"select form_version,survey_name from survey where id='".$surveyID."'");
		$datasvirson=mysqli_fetch_object($getSurvey);
		$form_version=$datasvirson->form_version;
		$survey_name=$datasvirson->survey_name;
		$updateVersion=$form_version+1;
		$currentDateTime = currentTimeStamp();
		
		$url = $_REQUEST['url'];
		$unique_id = $_REQUEST['unique_id'];
		$filename=str_replace("sampling/", "",$url);
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$file_name = $survey_name."-".$unique_id.".".$ext;
		
		$updateSurveySQL = mysqli_query($conn,"update survey set status = '0', questinnour_file ='".$file_name."' , form_version = '".$updateVersion."', updated_at = '".$currentDateTime."' where id = '".$surveyID."'");
		
		$resArr = array("status"=>1,"msg"=>"Success");
		echo json_encode($resArr);
	
		}
		else{
			$resArr = array("status"=>0,"msg"=>"Failed");
			echo json_encode($resArr);
		}

?>