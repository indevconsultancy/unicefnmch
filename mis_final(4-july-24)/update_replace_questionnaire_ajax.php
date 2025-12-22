<?php 
include_once('includes/config.php');
include_once('includes/functions.php'); 


		$surveyID = $_REQUEST['survey_id'];
		if($surveyID){
			
			$questionnaire_sql = mysqli_query($conn,"SELECT question_json FROM `questionnaires` WHERE survey_id = '".$surveyID."'");
			$questionnaire_obj = mysqli_fetch_object($questionnaire_sql);
			$questionnaire_json = json_decode($questionnaire_obj->question_json);
			// print_r($questionnaire_json);
			// print_r($questionnaire_json->choices);
			
				
			$language_ids = array(1,2);
				
			foreach($questionnaire_json->questions as $key => $value){
				
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
				$choice_relation = $value->choice_relation;
				$relevant = $value->relevant;
				
				foreach($language_ids as $language_id){
				
				if($type=='begin_group' || $type=='begin_repeat'){
					$name = trim(safe_var($conn,str_replace(" ","",$value->name)));
				}
				
				// if($language_id != 1){
					// $language_code = getone($conn,"languages","language_code","language_id",$language_id);
					// $code = strtoupper($language_code);
					// $qwerty = "label::".$code;
					
					// $label = $value->$qwerty; 
					// $label = $value->$qwerty; 
					// $label = $value->$qwerty; 
				// }
				
				$sqlquery = mysqli_query($conn, "SELECT question_id FROM questions_language WHERE survey_id = $surveyID and field_name = '".$name."' and language_id = '".$language_id."'");
				$questionidObj = mysqli_fetch_object($sqlquery);
				$questionID = $questionidObj->question_id; 
				
				if($questionID != ''){
				
					if($type=='begin_group' || $type=='begin_repeat'){
						// echo $questionID; 
						if($language_id  == 1){
							
							$beginGroupSql = mysqli_query($conn,"update questions set appearance='".$appearance."' WHERE question_id='".$questionID."'");
							
							$beginGroupSql = mysqli_query($conn,"update questions_language set appearance='".$appearance."' WHERE question_id='".$questionID."' and language_id = '".$language_id."'");
							
						}
						else{
							
							// $beginGroupSql = mysqli_query($conn,"update questions_language set appearance='".$appearance."' WHERE question_id='".$questionID."' and language_id = '".$language_id."'");
							
						}
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
							
								// $updateQuestionsLanguage = mysqli_query($conn,"update questions_language set question_name='".$label."',dictionary_label='".$dictionary_label."',encrpt='".$deidentify."',question_description='".$hint."',repeat_count='".$repeat_count."',read_only='".$read_only."',calculation='".$calculation."',max_input='".$limit."',relevant='".$relevant."', required='".$required."', parameters='".$parameters."',constraints='".$constraint."', constraint_msg='".$constraint_message."' , choice_filter='".$choice_filter."' , appearance='".$appearance."', choice_relation='".$choice_relation."',default_response='".$default_response."', paradata='".$paradata."', unique_id='".$unique_id."', preserve='".$preserve."', lookups='".$lookups."', media_file='".$media_file."' where question_id='".$questionID."' and language_id = '".$language_id."'");
							
							}
						}
					}
				}
			}
			
			foreach($questionnaire_json->choices as $key => $value){
				
					$option_id = safe_var($conn,$value->value);
					$label = $value->label;
					$name = $value->list_name;
					
					// select language_code,language_name from languages where status = 0
					
					
				foreach($language_ids as $language_id){
				
				if($language_id != 1){
					$language_code = getone($conn,"languages","language_code","language_id",$language_id);
					$code = strtoupper($language_code);
					$qwerty = "label::".$code;
					
					$label = $value->$qwerty; 
				}
				$sqlquery = mysqli_query($conn, "SELECT question_id FROM questions_language WHERE survey_id = $surveyID and field_name = '".$name."' and language_id = '".$language_id."'");
				$questionidObj = mysqli_fetch_object($sqlquery);
				$questionID = $questionidObj->question_id; 
					if($questionID != ''){
						if($language_id == 1){
							// echo "UPDATE options SET option_name='".$label."' WHERE question_id='".$questionID."' and option_sequence = '".$option_id."'"; echo "___";
							$options_sql = mysqli_query($conn,"UPDATE options SET option_name='".$label."' WHERE question_id='".$questionID."' and option_sequence = '".$option_id."'");
							// echo "UPDATE options_language SET option_name='".$label."' WHERE question_id='".$questionID."' and option_sequence = '".$option_id."' and language_id = '".$language_id."'"; echo "___";
							$options_language_sql = mysqli_query($conn,"UPDATE options_language SET option_name='".$label."' WHERE question_id='".$questionID."' and option_sequence = '".$option_id."' and language_id = '".$language_id."'");
						}
						else{
							// echo "UPDATE options_language SET option_name='".$label."' WHERE question_id='".$questionID."' and option_sequence = '".$option_id."' and language_id = '".$language_id."'"; echo "___";
							$options_language_sql = "UPDATE options_language SET option_name='".$label."' WHERE question_id='".$questionID."' and option_sequence = '".$option_id."' and language_id = '".$language_id."'";
						}
					}
				}
			}
			
			$getSurvey=mysqli_query($conn,"select form_version from survey where id='".$surveyID."'");
			$datasvirson=mysqli_fetch_object($getSurvey);
			$form_version=$datasvirson->form_version;
			$fvid=substr($form_version,1);
			$fverid=$fvid+1;
			$updateVersion="V".$fverid;
			
			$currentDateTime = currentTimeStamp();
			
			$updateSurveySQL = mysqli_query($conn,"update survey set status = '0', form_version = '".$updateVersion."', updated_at = '".$currentDateTime."' where id = '".$surveyID."'");
			
			$resArr = array("status"=>1,"msg"=>"Success");
			echo json_encode($resArr);
		}
		else{
			$resArr = array("status"=>0,"msg"=>"Failed");
			echo json_encode($resArr);
		}	
			

?>