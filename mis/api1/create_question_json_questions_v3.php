<?php

include('config.php');

header('Content-Type: application/json');

$surveyid=$_REQUEST['survey_id'];

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

////////////////////////////////////////////////
// echo "SELECT questions_language.* FROM questions_language,questions  where questions.question_id=questions_language.question_id and questions_language.status=0 and questions_language.question_name!='' and questions_language.language_id='".$language_data->language_id."' and questions_language.screen_no='".$screen->screen_no."' and questions_language.group_id='".$groups->group_id."' and questions_language.survey_id='".$surveyid."' order by questions.sequence_no asc";
// die;
$batchQuery = mysqli_query($conn, "SELECT questions_language.* FROM questions_language,questions  where questions.question_id=questions_language.question_id and questions_language.status=0 and questions_language.question_name!='' and questions_language.language_id='".$language_data->language_id."' and questions_language.screen_no='".$screen->screen_no."' and questions_language.group_id='".$groups->group_id."' and questions_language.survey_id='".$surveyid."' order by questions.sequence_no asc");

while($batches = mysqli_fetch_object($batchQuery)){
 
 
 
	//if($batches->question_id=="16903"){ // test
		
	$normal_group_id = $batches->normal_group_id;
	$survey_idnormal = $batches->survey_id; 
	$pre_group_id=0;
	if($normal_group_id!=''){ 
		$grpArr = explode(",",$normal_group_id);
		$pre_group =  count($grpArr)-1;
		$pre_group_id = $grpArr[$pre_group];
		
		//$pre_group_id = $normal_group_id;// change on 30-05-2022
	}
	
		//print_r($grpArr);
		//echo $batches->relevant;
		//echo "SELECT GROUP_CONCAT(conditions SEPARATOR ' & ') AS conditions FROM normal_groups WHERE survey_id='".$survey_idnormal."' AND id IN($pre_group_id) ";
	//} // end test
	
	
	$getGroup_conditions = mysqli_query($conn,"SELECT GROUP_CONCAT(conditions SEPARATOR ' & ') AS conditions FROM normal_groups WHERE survey_id='".$survey_idnormal."' AND id IN($pre_group_id) ");
	$group_conditions = mysqli_fetch_object($getGroup_conditions);
	$conditions = $group_conditions->conditions;
	
	$send_condition = '';
	$relevant = $batches->relevant;
	if($conditions!=''){
		
		if($relevant!=''){
			if(strpos($relevant,"!=")>0){
				$send_condition=$conditions." & ".$relevant;
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

   //if($batches->questions_type_id==6)

    // {

    //  $batch['question_name'] = $batches->question_description;

    // }

    // else

    // {

    //   $batch['question_name'] = $batches->question_name;

    // }

    // echo $batches->input_field_type;

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
	



/////////////// OPTIONS DETAILS////////////////////

	

	$OptionQuery = mysqli_query($conn, "SELECT * FROM options_language WHERE question_id='".$batches->question_id."' and language_id='".$language_data->language_id."' and status=0 order by options_language_id");

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

	$batch['question_options'] = $optionsDetail;

  $batches1[]=$batch;

 $optionsDetail=array();

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

$QtypeQuery = mysqli_query($conn, "SELECT * FROM question_type where status=0");

    while($Qtype = mysqli_fetch_object($QtypeQuery)){

        $question_type['question_type'] = $Qtype->questions_type_name;

        $question_type['question_type_id'] = $Qtype->questions_type_id;

        $question_types[]=$question_type;

	}







////////////  Question Input Type /////////////////////////////

$QIntypeQuery = mysqli_query($conn, "SELECT * FROM question_input_type where status=0");

    while($QIntype = mysqli_fetch_object($QIntypeQuery)){

        $question_input_type['question_input_type_name'] = $QIntype->question_input_type_name;

        $question_input_type['questions_input_type_id'] = $QIntype->question_input_type_id;

	      $question_input_types[]=$question_input_type;

  }

	

////////////  Validation /////////////////////////////

$ValidQuery = mysqli_query($conn, "SELECT * FROM validations where status=0");

    while($ValidData = mysqli_fetch_object($ValidQuery)){

        $validation['validation_type'] = $ValidData->validation_type;

        $validation['questions_input_type_id'] = $ValidData->validation_id;

        $validations[]=$validation;

	}
    $position=1;
	$ValidQuery = mysqli_query($conn, "SELECT id,group_name,survey_id,client_id,group_type FROM questions_group where survey_id='".$_REQUEST['survey_id']."' ");
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

$LangQuery = mysqli_query($conn, "SELECT * FROM languages where status=0 and language_id IN ($language_ids) ");

    while($languageData = mysqli_fetch_object($LangQuery)){

        $language['language_name'] = $languageData->language_name;

        $language['language_id'] = $languageData->language_id;

        $languages[]=$language;

	}
	

$getLoockups = mysqli_query($conn,"SELECT id,survey_id,loockup_data FROM survey_loockups WHERE survey_id='".$_REQUEST['survey_id']."' ")	;
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



echo json_encode($batch_final);



?>