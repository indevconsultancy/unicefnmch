<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include('includes/config.php'); 
//if($_SESSION['username']==''){ echo "<script>window.location.href='index.php';</script>";}


if(isset($_POST['questionId'])){
	// echo "hello test";
	 // print_r($_POST);
	$questionId = $_POST['questionId'];
	$input_value = $_POST['input_value'];
	$survey_id = $_POST['survey_id'];
    $repeat_group_name = $_POST['repeat_group_name'];
	$repeat_group_name_array = explode(',', $repeat_group_name);

	$repeat_group_name_array = array_map(function($name) {
		return "'" . trim($name) . "'";
	}, $repeat_group_name_array);

	$repeat_group_name_list = implode(',', $repeat_group_name_array);
    $language_id=$_POST['language_id'];
	
	///////////////Partial RosterGroup Details///////////////////////
	$surveyPartial = "SELECT full_json FROM `partial_survey_data` WHERE survey_status='0' AND session_id='" . $sessionID . "' AND survey_name_id='" . $survey_id . "'";
	$qryPartial = mysqli_query($conn, $surveyPartial);
	
	///////////////Partial RosterGroup Details//////////////////////
	
	$getQuestions = mysqli_query($conn,"SELECT * FROM `questions_language` WHERE survey_id='".$survey_id."' AND repeated='".$questionId."' and language_id='".$language_id."' order by sequence_no asc ");
	$sn=0;
	while($question = mysqli_fetch_object($getQuestions)){ 
		
		$group_id = $question->group_id;
		$read_only = $question->read_only;
		$max_limit = $question->max_input;
		
		$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
		$readonly = (strtolower($read_only) == 'yes') ? "disabled" : "";
		$getrepeatg = mysqli_query($conn,"SELECT group_name FROM `questions_group` where id='".$group_id."' and group_type='group' and survey_id='".$survey_id."'");
		$getrepeatdata = mysqli_fetch_object($getrepeatg);
		$repeat_group_name=$getrepeatdata->group_name;
		
		$input_field_type=strtolower($question->input_field_type);
		if (in_array(strtolower($question->input_field_type), ['number','select_one','select_multiple', 'text','note','date','time','datetime','calculate','camera','audio','video','hidden','gps-button'])){
			if(strtolower($question->input_field_type=="note")){
				$repeated_question.='<h3 class="fs-subtitle">Note: '.strip_tags($question->question_name).'</h3>
				
				';
				$sn++;
			}
			
			if (strtolower($question->input_field_type) == "text" || strtolower($question->input_field_type) == "number") {
    
				$onInputValuer1 = '';
				if ($question_input_type1 == 'number' || $question_input_type1 == 'integer') {
					$onInputValuer1 = 'oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)"';
				}
				
				$repeated_question .= '<h3 class="fs-subtitle">' . $question->question_name . '</h3>';
				
				if (strtolower($question->input_field_type) == "text" && $max_limit >= 100) {
					
					$repeated_question .= '<textarea class="form-control fs-subtitle ' . $repeat_group_name . '" required maxlength="' . $max_limit . '" id="' . $question->field_name . '" ' . $read_only . ' data-id="' . $question->field_name . '" ' . $onInputValuer1 . ' wrap="hard" cols="30" rows="5"></textarea>';
				} else {
				
					$repeated_question .= '<input type="' . $input_field_type . '" class="form-control fs-subtitle ' . $repeat_group_name . '" required maxlength="' . $max_limit . '" id="' . $question->field_name . '" ' . $read_only . ' data-id="' . $question->field_name . '" ' . $onInputValuer1 . '>';
				}
				$sn++;
			}
			
			if(strtolower($question->input_field_type=="date") || strtolower($question->input_field_type=="time")){
				$repeated_question.='<h3 class="fs-subtitle">'.$question->question_name.'</h3>
				<input type="'.$input_field_type.'" class="form-control fs-subtitle '.$repeat_group_name.'" '.$read_only.' required id="'.$question->field_name.'" data-id="'.$question->field_name.'" placeholder="" >
				
				';
				$sn++;
			}
			if(strtolower($question->input_field_type=="datetime")){
				$repeated_question.='<h3 class="fs-subtitle">'.$question->question_name.'</h3>
				<input type="datetime-local" class="form-control fs-subtitle '.$repeat_group_name.'" '.$read_only.' required id="'.$question->field_name.'" data-id="'.$question->field_name.'" placeholder="'.$question->question_description.'" >
				
				';
				$sn++;
			}
			if(strtolower($question->input_field_type=="select_one")){
				$options='';
				$sql = "SELECT * FROM options where question_id = '".$question->question_id."'";
				$getOption = mysqli_query($conn, $sql);
				while ($opt = mysqli_fetch_object($getOption)){ 
					$options.='<option value="'.$opt->option_sequence.'">'.$opt->option_name.'</option>';
				}
			
				$repeated_question.='<h3 class="fs-subtitle">'.$question->question_name.'</h3>
				<select class="form-control fs-subtitle '.$repeat_group_name.'" '.$readonly.' required id="'.$question->field_name.'" data-id="'.$question->field_name.'">
				<option value="">Select Option</option>
				'.$options.'
				</select>
				';
				$sn++;
			}
			 if (strtolower($question->input_field_type) == "select_multiple") {
				$options = '';
				$question_id = mysqli_real_escape_string($conn, $question->question_id); // Sanitize input
				$sql = "SELECT * FROM options WHERE question_id = '$question_id'";
				$getOption = mysqli_query($conn, $sql);

				while ($opt = mysqli_fetch_object($getOption)) { 
					$options .= '<option value="' . htmlspecialchars($opt->option_sequence) . '">' . htmlspecialchars($opt->option_name) . '</option>';
				}

				$repeated_question .= '<h3 class="fs-subtitle">' . htmlspecialchars($question->question_name) . '</h3>
					<select class="form-control multiple-select '.$repeat_group_name.' selectoption' . htmlspecialchars($question->field_name) . '" '.$readonly.' required id="'.$question->field_name.'" data-id="'.$question->field_name.'" multiple="multiple" onchange="multiSelect(\'' . $question->field_name . '\')"  maxlength="' . htmlspecialchars($question->max_limit) . '" data-placeholder="" >
						<option value="">Select Option</option>
						' . $options . '
					</select>
					';
			}
			 
		}

	}
	
	//$repeated_question.= '</fieldset>';
	//for($i=0;$i<$input_value; $i++){
	for($i=0;$i<$input_value; $i++){
		$si=$i+1;
		echo '<div class="fieldset '.$repeat_group_name.' " data-index="'.$i.'"><span class="legend"> Repeat Group-'.$si.':</span > '.$repeated_question.'</div>';
	}	
	
}

?>



<?php
if(isset($_POST['qb_questions']) && isset($_POST['qb_sid']) && $_POST['qb_questions']!='' && $_POST['qb_sid']!=''){
	
	$qb_sid = $_POST['qb_sid'];
	$qb_questions = $_POST['qb_questions'];
	$qbid = implode(",",$qb_questions);
	mysqli_query($conn,"UPDATE question_bank SET common_use=common_use+1 WHERE question_bank_id IN($qbid) ");
	foreach($qb_questions as $qb_question_id){
		$getbquestion = mysqli_query($conn,"SELECT question_bank_id,question_bank_name,field_name,question_type FROM question_bank WHERE question_bank_id='".$qb_question_id."' ");
		$bquestion = mysqli_fetch_object($getbquestion);
		$question_bank_id = $bquestion->question_bank_id;
		$question_type = $bquestion->question_type;
		$question_bank_name = $bquestion->question_bank_name; 
		$field_name = $bquestion->field_name;
		
		
		
		if($question_type=='select_one'){
			$questions_type_id=4;
			$getqbops = mysqli_query($conn,"SELECT id,question_option_name,option_value FROM question_bank_option WHERE question_bank_id='".$question_bank_id."' ");
			$allqbops = mysqli_fetch_all($getqbops, MYSQLI_ASSOC);
			
			$ins = mysqli_query($conn,"INSERT INTO questions SET question_name='".$question_bank_name."',screen_no='1', questions_type_id='".$questions_type_id."', question_input_type_id='1',field_name='".$field_name."',title='".$field_name."',survey_id='".$qb_sid."',input_field_type='".$question_type."' ");
			$lqid = mysqli_insert_id($conn);
			foreach($allqbops as $qbops){
				$question_option_name = $qbops['question_option_name'];
				$option_value = $qbops['option_value'];
				mysqli_query($conn,"INSERT INTO options SET option_name='".$question_option_name."',option_sequence='".$option_value."',category_name='".$field_name."',survey_id='".$qb_sid."',question_id='".$lqid."' ");
				$opid = mysqli_insert_id($conn);
				mysqli_query($conn,"INSERT INTO options_language SET option_id='".$opid."', option_name='".$question_option_name."',option_sequence='".$option_value."',category_name='".$field_name."',survey_id='".$qb_sid."',question_id='".$lqid."' ");
			}
			mysqli_query($conn,"INSERT INTO questions_language SET language_id='1',question_id='".$lqid."',screen_no='1', question_name='".$question_bank_name."', questions_type_id='".$questions_type_id."', question_input_type_id='1',field_name='".$field_name."',title='".$field_name."',survey_id='".$qb_sid."',input_field_type='".$question_type."' ");
		}else{
			if($question_type=='text'){
				$questions_type_id=1;
			}else if($question_type=='number'){
				$questions_type_id=2;
			}else if($question_type=='date'){
				$questions_type_id=7;
			}
			$ins = mysqli_query($conn,"INSERT INTO questions SET question_name='".$question_bank_name."',screen_no='1', questions_type_id='".$questions_type_id."', question_input_type_id='1',field_name='".$field_name."',title='".$field_name."',survey_id='".$qb_sid."',input_field_type='".$question_type."' ");
			$lqid = mysqli_insert_id($conn);
			mysqli_query($conn,"INSERT INTO questions_language SET language_id='1',question_id='".$lqid."',screen_no='1', question_name='".$question_bank_name."', questions_type_id='".$questions_type_id."', question_input_type_id='1',field_name='".$field_name."',title='".$field_name."',survey_id='".$qb_sid."',input_field_type='".$question_type."' ");
		}
	}
	if($ins){
		echo "Question Addedd Successfully";
	}
}

if(isset($_POST['survey_select_txt'])){
	$client_id = $_POST['survey_select_txt'];
	$total_sub=mysqli_query($conn,"select (membership.nof_survey) as total_nof_survey  from clients inner join membership on clients.membership_id=membership.membership_id where clients.id = '".$client_id."'");
	$use_total=mysqli_fetch_array($total_sub);
	$total_survey=$use_total['total_nof_survey'];
	
	$use_sub=mysqli_query($conn,"SELECT count(survey.id) as use_survey FROM `survey`  where client_id='".$client_id."' ");
	$total=mysqli_fetch_array($use_sub);
	$use_survey=$total['use_survey'];
	$client_details["total"] = $total_survey;
	$client_details["used"] = $use_survey;
	$client_details["remain"] = $total_survey-$use_survey;
	echo json_encode($client_details);
}

if(isset($_POST['process']) && $_POST['process']=="get-question-bank-data" && isset($_POST['qbCatId']) && $_POST['qbCatId']!=""){
	$qbCatId = mysqli_real_escape_string($conn, $_POST['qbCatId']);
	
	$getQuestionBank = mysqli_query($conn,"SELECT question_bank_id,question_bank_name,category_id,question_type,target_group,common_use,data_source FROM question_bank WHERE status='0' and category_id='".$qbCatId."' ORDER BY common_use DESC ");

	?> 
	<table id="example" class="table" style="color: black;">
		<thead>
		  <tr>
			  <th style="width:5%!important;"><input type="checkbox" id="ckbCheckAll" /></th>
			  <th>Question</th>
			  <th style="width:13%!important;">Most Use</th>
			  <th>Source</th>
			  <th>Target group</th>
		  </tr>
		</thead>
		<tbody>

			<?php 
			//$totQuestion = mysqli_num_rows($getQuestionBank);
			while($qbank = mysqli_fetch_object($getQuestionBank)){ 
				$qinfo='';
				$tooltip_data='<table><tr><td><b>Label</b></td> <td><b>Value</b></td><tr>';
				/* if($qbank->question_type=='select_one'){
					$getqboptions = mysqli_query($conn,"SELECT id,question_option_name,option_value FROM question_bank_option WHERE question_bank_id='".$qbank->question_bank_id."' order by id DESC ");
					$qbopts = mysqli_fetch_all($getqboptions,MYSQLI_ASSOC);
					foreach($qbopts as $qbopt){
						$question_option_name = $qbopt['question_option_name'];
						$option_value = $qbopt['option_value'];
						$tooltip_data.='<tr><td>'.$question_option_name.'</td><td>'.$option_value.'</td></tr>';
					}
					$tooltip_data.='</table>';
					$qinfo='<i class="fa fa-info-circle tooltips" data-bs-placement="top" data-bs-toggle="tooltip" title="'.$tooltip_data.'" data-bs-html="true" ></i>';
				} */
			?>	
				<tr>
					<td><input type="checkbox" class="checkBoxClass<?=$qbank->category_id;?>" value="<?=$qbank->question_bank_id;?>" name="qb_questions[]" /></td>
					<td><?=$qbank->question_bank_name;?> <?=$qinfo;?>  </td>
					<td><?=$qbank->common_use;?></td>
					<td><?=$qbank->data_source;?></td>
					<td><?=$qbank->target_group;?></td>
				</tr>
				<script>
					$("#ckbCheckAll").click(function () {
						$(".checkBoxClass<?=$qbank->category_id;?>").prop('checked', $(this).prop('checked'));
					});
					</script>	
			<?php	
			} ?>
		</tbody>
	</table>
	
	<?php 
}
?>