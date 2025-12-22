<?php include('includes/config.php'); ?>
<?php if($_SESSION['username']==''){ echo "<script>window.location.href='index.php';</script>";} ?>
<?php

  header("Access-Control-Allow-Origin: *");

// header("Access-Control-Allow-Methods: POST");

// header("Content-type: application/json; charset=utf-8");

	if(isset($_REQUEST['survey_id'])){
 
		$survey_id = $_REQUEST['survey_id'];
		$getQuestion = mysqli_query($conn,"SELECT question_id, question_name, field_name, input_field_type,validation_id, question_description, category_name FROM questions WHERE survey_id='".$survey_id."'  AND question_name!='' order by sequence_no asc ");
		while($question = mysqli_fetch_array($getQuestion,MYSQLI_ASSOC)){
			// $questions['question_id'] = $question['question_id'];
			// $questions['question_name'] = $question['question_name'];
			$question_id = $question['question_id'];
			$required=false;
			$description="";
			$subtype='text';
			$class_name = "form-control";
			// $options = array();
			if($question['validation_id']=='1'){ $required=true; }
			if($question['question_description']!=''){ $description=$question['question_description']; }
			if($question['input_field_type']=='header'){ $subtype='h4'; }
			// $questions['question_id'] = $question['question_id'];

            $sql = "select `key` from question_type where hints='".$question['input_field_type']."'";
            $r = mysqli_query($conn, $sql);
			if(mysqli_num_rows($r)>0){
				$d = mysqli_fetch_array($r);
				$questions['type'] = $d['key'];
			}else{
				if($question['input_field_type']=='note'){
					$questions['type'] = "header";
					$class_name="";
				}else if(($question['input_field_type']=='select_one') || ($question['input_field_type']=='select_multiple') ){
					$questions['type'] = "select";
				}else if($question['input_field_type']=='datetime'){
					$questions['type'] = "date";
				}else if($question['input_field_type']=='time'){
					$questions['type'] = "date";
				}
			}
			
			$questions['required'] = $required;
			$questions['description'] = $description;
			$questions['label'] = $question['question_name'];
			$questions['subtype'] = $subtype;
			$questions['className'] = $class_name;
			$questions['name'] = $question['field_name'];

			if($question['category_name']!='' && $question['input_field_type']=='select'){
				$getquest_op = mysqli_query($conn,"SELECT option_id,option_name FROM options WHERE question_id='".$question_id."'");
				while($ques_option = mysqli_fetch_array($getquest_op,MYSQLI_ASSOC)){
					$options['label'] = $ques_option['option_name'];
					$options['value'] = $ques_option['option_id'];
					$options['selected'] = false;
					$optionsd[] = $options;
				}
				$questions['values'] = $optionsd;
				$optionsd = array();
			}else{ $questions['values']=""; }
			$ques_data[] = $questions;
		}
		echo json_encode($ques_data);
	}

?>


<?php  

	if(isset($_REQUEST['form_data']) && isset($_REQUEST['sid'])){
		$form_data = $_REQUEST['form_data'];
		$survey_id = $_REQUEST['sid'];
		$client_id = getone($conn,"survey","client_id","id",$survey_id);
		$sequence_no=1;
		// print_r($form_data);
		// die;
		foreach($form_data as $formdata){
			$questions_type_id="";
			$question_input_type_id="";
			$type = $formdata['type'];
			$required = $formdata['required'];
			$className = $formdata['className'];
			$name = str_replace(" ","",$formdata['name']);
			$label = $formdata['label'];
			$relevant = $formdata['relevant'];
			$constraints = $formdata['constraint'];
			$constraint_msg = $formdata['constraint_msg'];
			$limit= $formdata['limit'];
			$read_only = $formdata['read_only'];
			$multiple = $formdata['multiple'];
			$multiline = $formdata['multiline'];
			$para_data = $formdata['paradata'];
			$media_type = $formdata['media_type'];
			
			$readonly='';
			if($read_only=='true'){
				$readonly='yes';
			}
			$paradata='';
			if($para_data=='true'){
				if($formdata['paudio']=='true'){ $paradata.='audio,';}
				if($formdata['gps']=='true'){ $paradata.='gps,';}
				if($formdata['timestamp']=='true'){ $paradata.='timestamp,';}
				if($formdata['duration']=='true'){ $paradata.='duration,';}
				if($formdata['keystrok']=='true'){ $paradata.='keystrok,';}
				$paradata=substr($paradata,0,-1);
				//$paradata='yes';
			}
			$repeat_count = $formdata['repeat_count'];
			
			$description = $formdata['description'];
			$values = $formdata['values']; //arr when select box
			$setrequired=0;
			$reqrd='';
			if($required=='true'){
				$setrequired=1;
				$reqrd='yes';
			}
			
			if($type=='text'){
				$questions_type_id=1;
				$question_input_type_id=1;
			}
			
			if($type=='file'){
				if($media_type=='camera'){
					$questions_type_id=10;
					$question_input_type_id=1;
					$type='camera';
				}
				if($media_type=='audio'){
					$questions_type_id=14;
					$question_input_type_id=1;
					$type='audio';
				}
				if($media_type=='video'){
					$questions_type_id=15;
					$question_input_type_id=1;
					$type='video';
				}
			}
			
			if($type=='number'){
				$questions_type_id=1;
				$question_input_type_id=1;
			}
			if($type=='numeric'){
				$questions_type_id=1;
				$question_input_type_id=1;
			}
			
			$category_name="";
			if($type=='select'){
				$category_name=$name;
				$questions_type_id=4;
				$question_input_type_id=1;
				$type='select_one';
				if($multiple=='true'){
					$type='select_multiple';
					$questions_type_id=3;
				}
			}

			if($type=='date'){
				$questions_type_id=7;
				$question_input_type_id=1;
			}
			if($type=='header'){
				$questions_type_id=6;
				$question_input_type_id=1;
			}

			if($type=='paragraph'){
				$questions_type_id=16;
				$question_input_type_id=1;
			}
			if($type=='button'){
				$questions_type_id=10;
				$question_input_type_id=1;
			}
			// echo $setrequired;
			if($type=='text' && $multiline=='true'){
				//input_field_type
				$type='textarea';
			}
			$report_format_sql ="insert into report_format set title='".$label."',seq_no='".$sequence_no."',field_lable='".$name."',rm_report='".$name."',survey_id='".$survey_id."', client_id='".$client_id."' ";
            $report_format = mysqli_query($conn,$report_format_sql);
			
			$question_sql = "INSERT INTO questions SET question_name='".$label."', max_input='".$limit."', question_description='".$description."', questions_type_id='".$questions_type_id."', question_input_type_id='".$question_input_type_id."', field_name='".$name."', validation_id='".$setrequired."', title='".$name."', category_name='".$category_name."', survey_id='".$survey_id."', input_field_type='".$type."', required='".$reqrd."',relevant='".$relevant."', constraints='".$constraints."', constraint_msg='".$constraint_msg."',  read_only='".$readonly."', paradata='".$paradata."' ";
			
			$insert = mysqli_query($conn,$question_sql);

			if($insert){
				$inserted_idq = mysqli_insert_id($conn);
				$question_language_sql ="insert into questions_language set language_id='1',question_id='".$inserted_idq."',question_name='".$label."',question_description='".$description."',questions_type_id='".$questions_type_id."',question_input_type_id='".$question_input_type_id."',max_input='".$limit."' ,group_id='0',field_name='".$name."',validation_id='".$setrequired."',title='".$name."',category_name='".$category_name."',survey_id='".$survey_id."', input_field_type='".$type."',  required='".$reqrd."',relevant='".$relevant."', constraints='".$constraints."', constraint_msg='".$constraint_msg."',  read_only='".$readonly."', paradata='".$paradata."' ";
				if($repeat_count=="begin_repeat"){
					$is_repeat = "Yes";
					$repeatedqid=$inserted_idq;
					$rptname = $name;
					
					$insertGroup = mysqli_query($conn,"INSERT INTO questions_group SET group_name='".$rptname."', survey_id='".$survey_id."', client_id='".$client_id."' ");
					$insert_group_id = mysqli_insert_id($conn);
					mysqli_query($conn,"UPDATE questions SET repeated='start', repeat_count='".$rptname."',group_id='".$insert_group_id."' WHERE question_id='".$inserted_idq."'");
					mysqli_query($conn,"UPDATE questions_language SET repeated='".$insert_group_id."' WHERE question_id='".$inserted_idq."'");
				}
				if($repeat_count=="end_repeat"){
					$is_repeat = "No";
				}
				if($is_repeat=="Yes"){
					mysqli_query($conn,"UPDATE questions SET repeated='".$repeatedqid."', repeat_count='".$rptname."' WHERE question_id='".$inserted_idq."' and repeated='0'");
					mysqli_query($conn,"UPDATE questions_language SET group_id='".$insert_group_id."' WHERE question_id='".$inserted_idq."'");
				}
				
                
                mysqli_query($conn,$question_language_sql);
				if($type=='select' || $type=='select_one' || $type=='select_multiple'){
					$values = $formdata['values'];
					foreach($values as $option){
						$option_label = $option['label'];
						$option_value = $option['value'];
						$option_selected = $option['selected'];
						$option_sql = "INSERT INTO options SET option_name='".$option_label."', question_id='".$inserted_idq."', survey_id='".$survey_id."', option_sequence='".$option_value."', serial_no_for_app='1', category_name='".$category_name."' ";

						mysqli_query($conn,$option_sql);
                        $inserted_id = mysqli_insert_id($conn);
                        $option_language_sql = "INSERT INTO options_language SET option_id='".$inserted_id."',language_id='1', survey_id='".$survey_id."', option_name='".$option_label."',question_id='".$inserted_idq."',serial_no_for_app='1',option_sequence='".$option_value."',category_name='".$category_name."'";
                        mysqli_query($conn,$option_language_sql);
					}
				}
				// echo "Data Inserted...";
				$sequence_no=$sequence_no+1;
			}
		}

	}

?>





<?php 

	if(isset($_REQUEST['surveyid'])){

		$surveyid = $_REQUEST['surveyid'];

		$getQuestion = mysqli_query($conn,"SELECT question_id, question_name, field_name,repeated FROM questions WHERE survey_id='".$surveyid."' and question_name!='' ");

		while($ques = mysqli_fetch_array($getQuestion)){ ?>

			<tr >

				<td onclick="getId(this)" data-id="<?=$ques['question_id'];?>" class="pointer <?php if($ques['repeated']!="0" && $ques['repeated']!="start"){ echo "repeat"; }?> "><?=$ques['question_name'];?></td>

			</tr>

		<?php

		}

	}

?>



<?php 

 if(isset($_REQUEST['questionId'])){

 	$questionid = $_REQUEST['questionId'];
	
	//echo "SELECT question_id, question_name, field_name, input_field_type,validation_id, question_description, category_name FROM questions WHERE question_id='".$questionid."' ";
	
 	$getQuestion = mysqli_query($conn,"SELECT question_id, question_name, field_name, input_field_type,validation_id, question_description, category_name FROM questions WHERE question_id='".$questionid."' ");

	while($question = mysqli_fetch_array($getQuestion,MYSQLI_ASSOC)){

		// $questions['question_id'] = $question['question_id'];

		// $questions['question_name'] = $question['question_name'];

		$question_id = $question['question_id'];
		$required=false;
		$description="";
		$subtype='text';
		$class_name = "form-control";
		// $options = array();
		if($question['validation_id']=='1'){ $required=true; }
		if($question['question_description']!=''){ $description=$question['question_description']; }
        $sql = "select `key` from question_type where hints='".strtolower($question['input_field_type'])."'";
        $r = mysqli_query($conn, $sql);
        // $d = mysqli_fetch_array($r);
        // $questions['type'] = $d['key'];
		$multiple_status=false;
		$multiline_status=false;
		if(mysqli_num_rows($r)>0){
			$d = mysqli_fetch_array($r);
			$questions['type'] = $d['key'];
			$input_field_type = $d['key'];
		}else{
			if($question['input_field_type']=='note' || $question['input_field_type']=='paragraph'){
				$questions['type'] = "paragraph";
				$class_name="";
			}else if(($question['input_field_type']=='select_one') || ($question['input_field_type']=='select_multiple') ){
				$questions['type'] = "select";
				$input_field_type="select";
				if($question['input_field_type']=='select_multiple'){
					$multiple_status=true;
				}
				
			}else if($question['input_field_type']=='datetime'){
				$questions['type'] = "date";
				$input_field_type="date";
			}else if($question['input_field_type']=='time'){
				$questions['type'] = "date";
				$input_field_type="date";
			}else if($question['input_field_type']=='textarea'){
				$questions['type'] = "text";
				$input_field_type="text";
				$multiline_status=true;
			}
		}
        
		if($question['input_field_type']=='header'){ $subtype='h2'; }

		$questions['required'] = $required;
		$questions['description'] = $description;
		$questions['label'] = $question['question_name'];
		$questions['subtype'] = $subtype;
		$questions['className'] = $class_name;
		$questions['name'] = $question['field_name'];
		$questions['multiple'] = $multiple_status;
		$questions['multiline'] = $multiline_status;

		if($question['category_name']!='' && $input_field_type=='select'){

			$getquest_op = mysqli_query($conn,"SELECT option_id,option_name,option_sequence FROM options WHERE question_id='".$question_id."'");

			while($ques_option = mysqli_fetch_array($getquest_op,MYSQLI_ASSOC)){
				$options['label'] = $ques_option['option_name'];
				$options['value'] = $ques_option['option_sequence'];//$ques_option['option_id'];
				$options['selected'] = false;
				$optionsd[] = $options;
			}

			$questions['values'] = $optionsd;
			$optionsd = array();

		}else{ $questions['values']=""; }
		
		$ques_data[] = $questions;
	}

	echo json_encode($ques_data);

 }

?>



<?php 

//UPDATE SEQUENCE

if(isset($_REQUEST['secuence']) && isset($_REQUEST['form_data'])){

		$form_data = $_REQUEST['form_data'];
		$survey_id = $_REQUEST['secuence'];
		foreach($form_data as $key=> $formdata){
			$sequence_no = $key+1;
			// $type = $formdata['type'];
			$label = $formdata['label'];
			$setSequenceSql = "UPDATE questions SET sequence_no='".$sequence_no."' WHERE question_name='".$label."' ";
			$update = mysqli_query($conn,$setSequenceSql);
		}

		if($update){
			echo "success";
		}else{ echo "failed";}
	}

?>



<?php 

	/***  UPDATE QUESTION   ***/

	if(isset($_REQUEST['survey_ideditQues']) && isset($_REQUEST['question_id'])){

		$survey_id = $_REQUEST['survey_ideditQues'];
		$question_id = $_REQUEST['question_id'];
		$form_data = $_REQUEST['form_data'];
		foreach($form_data as $formdata){
			// $type = $formdata['type'];
			// $type = $formdata['lab'];
			$questions_type_id="";
			$question_input_type_id="";
			$type = $formdata['type'];
			$required = $formdata['required'];
			$className = $formdata['className'];
			$name = str_replace(" ","",$formdata['name']); //$formdata['name']; 
			$label = $formdata['label'];
			$description = $formdata['description'];
			$values = $formdata['values']; //arr when select box
			$read_only = $formdata['read_only'];
			$multiple = $formdata['multiple'];
			$multiline = $formdata['multiline'];
			$para_data = $formdata['paradata'];
			$readonly='';
			if($read_only=='true'){
				$readonly='yes';
			}
			$paradata='';
			if($para_data=='true'){
				if($formdata['paudio']=='true'){ $paradata.='audio,';}
				if($formdata['gps']=='true'){ $paradata.='gps,';}
				if($formdata['timestamp']=='true'){ $paradata.='timestamp,';}
				if($formdata['duration']=='true'){ $paradata.='duration,';}
				if($formdata['keystrok']=='true'){ $paradata.='keystrok,';}
				$paradata=substr($paradata,0,-1);
				//$paradata='yes';
			}
			
			$setrequired=0;
			if($required=='true'){
				$setrequired=1;
			}
			$input_field_type = $type;
			if($type=='text'){
				$questions_type_id=1;
				$question_input_type_id=1;
			}

			if($type=='number'){
				$questions_type_id=1;
				$question_input_type_id=1;
			}

			$category_name="";
			if($type=='select'){
				$category_name=$name;
				$questions_type_id=2;
				$question_input_type_id=1;
				$input_field_type="select_one";
			}


			if($type=='date'){
				$questions_type_id=7;
				$question_input_type_id=4;
			}

			if($type=='header'){
				$questions_type_id=6;
				$question_input_type_id=3;
			}

			if($type=='paragraph'){
				$questions_type_id=6;
				$question_input_type_id=3;
			}

			$question_sql = "UPDATE questions SET question_name='".$label."', max_input='100', question_description='".$description."', questions_type_id='".$questions_type_id."', question_input_type_id='".$question_input_type_id."', field_name='".$name."', validation_id='".$setrequired."', title='".$name."', category_name='".$category_name."', input_field_type='".$input_field_type."' WHERE  survey_id='".$survey_id."' and question_id='".$question_id."' ";
			$question_sql_lang = "UPDATE questions_language SET question_name='".$label."', max_input='100', question_description='".$description."', questions_type_id='".$questions_type_id."', question_input_type_id='".$question_input_type_id."', field_name='".$name."', validation_id='".$setrequired."', title='".$name."', category_name='".$category_name."', input_field_type='".$input_field_type."' WHERE  survey_id='".$survey_id."' and question_id='".$question_id."' ";
			$update = mysqli_query($conn,$question_sql);
			mysqli_query($conn,$question_sql_lang);
			if($update){
				// $inserted_id = mysqli_insert_id($conn);
				if($type=='select'){
					$values = $formdata['values'];
					mysqli_query($conn,"DELETE FROM options WHERE question_id='".$question_id."' ");
					mysqli_query($conn,"DELETE FROM options_language WHERE question_id='".$question_id."' ");
					foreach($values as $option){
						$option_label = $option['label'];
						$option_value = $option['value'];
						$option_selected = $option['selected'];
						$option_sql = "INSERT INTO options SET option_name='".$option_label."',survey_id='".$survey_id."', question_id='".$question_id."', option_sequence='".$option_value."', serial_no_for_app='1', category_name='".$category_name."' ";
						mysqli_query($conn,$option_sql);
						$oid = mysqli_insert_id($conn);
						$option_sql_lang = "INSERT INTO options_language SET option_name='".$option_label."',language_id='1',survey_id='".$survey_id."', option_id='".$oid."', question_id='".$question_id."', option_sequence='".$option_value."', serial_no_for_app='1', category_name='".$category_name."' ";
						
						mysqli_query($conn,$option_sql_lang);
					}
				}
				// echo "Data Inserted...";
			}
			if($update){
				echo "success";
			}else{ echo "failed";}
		}
	}
?>