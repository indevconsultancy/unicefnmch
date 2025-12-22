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
  ///////////////////////////////////////////////
$screenQuery = mysqli_query($conn, "SELECT distinct(screen_no) FROM questions where status=0 order by screen_no asc");
while($screen = mysqli_fetch_object($screenQuery)){
////////////////////////////////////////////////
$batchQuery = mysqli_query($conn, "SELECT * FROM questions where status=0 and screen_no='".$screen->screen_no."' order by sequence_no asc");
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
$Qscreen['questions']=$batches1;
$screens[]=$Qscreen;
$batches1=array();	
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
	
////////////  Language /////////////////////////////
$LangQuery = mysqli_query($conn, "SELECT * FROM languages where status=0");
    while($languageData = mysqli_fetch_object($LangQuery)){
        $language['language_name'] = $languageData->language_name;
        $language['language_id'] = $languageData->language_id;
        $languages[]=$language;
	}
	
////////////  Final JSON /////////////////////////////
                $batch_final['screen']=$screens;
			  	//$batch_final['questions']=$batches1;
				$batch_final['question_type']=$question_types;
                $batch_final['question_input_type']=$question_input_types;
                $batch_final['validations']=$validations;
                $batch_final['language']=$languages;

/////////////////////////////////////////////////////
echo json_encode($batch_final);
?>