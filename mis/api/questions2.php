<?php
include('config.php');
header('Content-Type: application/json');
$batch_final=array();
$batches1=array();
$optionsDetails=array();
 $optionsDetail=array();
 	$question_types=array();
  $question_input_types=array();
  $validations=array();
  $languages=array();
  $screens=array();
  //////////////////////////////
  $groupQuery = mysqli_query($conn, "SELECT group_id FROM questions where status=0 group by group_id ORDER BY `questions`.`group_id` asc");
while($group = mysqli_fetch_object($groupQuery)){
    if($group->group_id==0){
                 ///////////////////////////////////////////////
        // echo "SELECT distinct(screen_no),group_id FROM questions where status=0 and group_id='".$gr_zerogroup->group_id."' order by screen_no asc";
$gr_zeroscreenQuery = mysqli_query($conn, "SELECT distinct(screen_no),group_id FROM questions where status=0 and group_id='0' order by screen_no asc");
while($gr_zeroscreen = mysqli_fetch_object($gr_zeroscreenQuery)){
////////////////////////////////////////////////
$gr_zerobatchQuery = mysqli_query($conn, "SELECT * FROM questions where status=0 and screen_no='".$gr_zeroscreen->screen_no."' and group_id='0' order by sequence_no asc");
while($gr_zerobatches = mysqli_fetch_object($gr_zerobatchQuery)){
/////////////////////// questions /////////////////

	$gr_zerobatch['question_id'] = $gr_zerobatches->question_id;
    $gr_zerobatch['language_id'] = 1;
   if($gr_zerobatches->questions_type_id==6)
    {
     $gr_zerobatch['question_name'] = $gr_zerobatches->question_description;
    }
    else
    {
      $gr_zerobatch['question_name'] = $gr_zerobatches->question_name;
    }
    $gr_zerobatch['question_type'] = $gr_zerobatches->questions_type_id;
    $gr_zerobatch['question_input_type'] = $gr_zerobatches->question_input_type_id;
    $gr_zerobatch['validation_id'] = $gr_zerobatches->validation_id;
    $gr_zerobatch['max_limit'] = $gr_zerobatches->max_input;
    $gr_zerobatch['pre_field'] = $gr_zerobatches->prefill;
    $gr_zerobatch['screen_no'] = $gr_zerobatches->screen_no;
    $gr_zerobatch['group_id'] = $gr_zerobatches->group_id;
    $gr_zerobatch['group_relation_id'] = $gr_zerobatches->group_relation_id;
    $gr_zerobatch['field_name'] = $gr_zerobatches->field_name;
    $gr_zerobatch['ref_table'] = $gr_zerobatches->ref_table;
    
/////////////// OPTIONS DETAILS////////////////////
	
	$gr_zeroOptionQuery = mysqli_query($conn, "SELECT * FROM options WHERE question_id='".$gr_zerobatches->question_id."' and status=0");
    while($gr_zerooptions = mysqli_fetch_object($gr_zeroOptionQuery)){
        $gr_zerooptionsDetails['option_value'] = $gr_zerooptions->option_name;
        $gr_zerooptionsDetails['option_id'] = $gr_zerooptions->option_sequence;
        $gr_zerooptionsDetails['is_terminate'] = $gr_zerooptions->is_terminate;
        $gr_zerooptionsDetail[]=$gr_zerooptionsDetails;
	    }
	$gr_zerobatch['question_options'] = $gr_zerooptionsDetail;
  $gr_zerobatches1[]=$gr_zerobatch;
 $gr_zerooptionsDetail=array();
    }
$gr_zerobatch=array();
$gr_zeroQscreen['screen_no']=$gr_zeroscreen->screen_no;
$gr_zeroQscreen['group_id']=$gr_zeroscreen->group_id;
$gr_zeroQscreen['questions']=$gr_zerobatches1;
$gr_zeroscreens[]=$gr_zeroQscreen;
$gr_zerobatches1=array();	
}

////////////// Question Type /////////////////////////
$gr_zeroQtypeQuery = mysqli_query($conn, "SELECT * FROM question_type where status=0");
    while($gr_zeroQtype = mysqli_fetch_object($gr_zeroQtypeQuery)){
        $gr_zeroquestion_type['question_type'] = $gr_zeroQtype->questions_type_name;
        $gr_zeroquestion_type['question_type_id'] = $gr_zeroQtype->questions_type_id;
        $gr_zeroquestion_types[]=$gr_zeroquestion_type;
	}



////////////  Question Input Type /////////////////////////////
$gr_zeroQIntypeQuery = mysqli_query($conn, "SELECT * FROM question_input_type where status=0");
    while($gr_zeroQIntype = mysqli_fetch_object($gr_zeroQIntypeQuery)){
        $gr_zeroquestion_input_type['question_input_type_name'] = $gr_zeroQIntype->question_input_type_name;
        $gr_zeroquestion_input_type['questions_input_type_id'] = $gr_zeroQIntype->question_input_type_id;
	      $gr_zeroquestion_input_types[]=$gr_zeroquestion_input_type;
  }
	
////////////  Validation /////////////////////////////
$gr_zeroValidQuery = mysqli_query($conn, "SELECT * FROM validations where status=0");
    while($gr_zeroValidData = mysqli_fetch_object($gr_zeroValidQuery)){
        $gr_zerovalidation['validation_type'] = $gr_zeroValidData->validation_type;
        $gr_zerovalidation['questions_input_type_id'] = $gr_zeroValidData->validation_id;
        $gr_zerovalidations[]=$gr_zerovalidation;
	}
	
////////////  Language /////////////////////////////
$gr_zeroLangQuery = mysqli_query($conn, "SELECT * FROM languages where status=0");
    while($gr_zerolanguageData = mysqli_fetch_object($gr_zeroLangQuery)){
        $gr_zerolanguage['language_name'] = $gr_zerolanguageData->language_name;
        $gr_zerolanguage['language_id'] = $gr_zerolanguageData->language_id;
        $gr_zerolanguages[]=$gr_zerolanguage;
	}
	
////////////  Final JSON /////////////////////////////
                $batch_final['screen']=$gr_zeroscreens;
			  	//$gr_zerobatch_final['questions']=$gr_zerobatches1;
				$batch_final['question_type']=$gr_zeroquestion_types;
                $batch_final['question_input_type']=$gr_zeroquestion_input_types;
                $batch_final['validations']=$gr_zerovalidations;
                $batch_final['language']=$gr_zerolanguages;

       }else{
         ///////////////////////////////////////////////
        // echo "SELECT distinct(screen_no),group_id FROM questions where status=0 and group_id='".$group->group_id."' order by screen_no asc";
$screenQuery = mysqli_query($conn, "SELECT distinct(screen_no),group_id FROM questions where status=0 and group_id='".$group->group_id."' order by screen_no asc");
while($screen = mysqli_fetch_object($screenQuery)){
////////////////////////////////////////////////
$batchQuery = mysqli_query($conn, "SELECT * FROM questions where status=0 and screen_no='".$screen->screen_no."' and group_id='".$group->group_id."' order by sequence_no asc");
while($batches = mysqli_fetch_object($batchQuery)){
/////////////////////// questions /////////////////

	$batch['question_id'] = $batches->question_id;
    $batch['language_id'] = 1;
   if($batches->questions_type_id==6)
    {
     $batch['question_name'] = $batches->question_description;
    }
    else
    {
      $batch['question_name'] = $batches->question_name;
    }
    $batch['question_type'] = $batches->questions_type_id;
    $batch['question_input_type'] = $batches->question_input_type_id;
    $batch['validation_id'] = $batches->validation_id;
    $batch['max_limit'] = $batches->max_input;
    $batch['pre_field'] = $batches->prefill;
    $batch['screen_no'] = $batches->screen_no;
    $batch['group_id'] = $batches->group_id;
    $batch['group_relation_id'] = $batches->group_relation_id;
    $batch['field_name'] = $batches->field_name;
    $batch['ref_table'] = $batches->ref_table;
    
/////////////// OPTIONS DETAILS////////////////////
	
	$OptionQuery = mysqli_query($conn, "SELECT * FROM options WHERE question_id='".$batches->question_id."' and status=0");
    while($options = mysqli_fetch_object($OptionQuery)){
        $optionsDetails['option_value'] = $options->option_name;
        $optionsDetails['option_id'] = $options->option_sequence;
        $optionsDetails['is_terminate'] = $options->is_terminate;
        $optionsDetail[]=$optionsDetails;
	    }
	$batch['question_options'] = $optionsDetail;
  $batches1[]=$batch;
 $optionsDetail=array();
    }
$batch=array();
$Qscreen['screen_no']=$screen->screen_no;
$Group['group_id']=$screen->group_id;
$Qscreen['questions']=$batches1;
$screens[]=$Qscreen;

$batches1=array();	
}
$Group['group_id']=$screens;
$screens=array();	

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
	
////////////  Language /////////////////////////////
$LangQuery = mysqli_query($conn, "SELECT * FROM languages where status=0");
    while($languageData = mysqli_fetch_object($LangQuery)){
        $language['language_name'] = $languageData->language_name;
        $language['language_id'] = $languageData->language_id;
        $languages[]=$language;
	}
	
////////////  Final JSON /////////////////////////////
                $batch_final['group']=$Group;
			  	//$batch_final['questions']=$batches1;
				$batch_final['question_type']=$question_types;
                $batch_final['question_input_type']=$question_input_types;
                $batch_final['validations']=$validations;
                $batch_final['language']=$languages;

    }
}
 
/////////////////////////////////////////////////////
echo json_encode($batch_final);
?>