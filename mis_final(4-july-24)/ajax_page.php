<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include('includes/config.php'); 
if($_SESSION['username']==''){ echo "<script>window.location.href='index.php';</script>";}


if(isset($_POST['questionId'])){
	// echo "hello test";
	 // print_r($_POST);
	$questionId = $_POST['questionId'];
	$input_value = $_POST['input_value'];
	$survey_id = $_POST['survey_id'];

	$getQuestionsd = mysqli_query($conn,"SELECT * FROM `questions` WHERE survey_id='".$survey_id."' AND question_id='".$questionId."' order by sequence_no asc ");
	$getQuestionsdata = mysqli_fetch_object($getQuestionsd);
	
	$repeated_question = '<input type="hidden" value="'.$getQuestionsdata->group_id.'" name="s'.$getQuestionsdata->repeat_count.'[group_id][]" />';
	
	$getQuestions = mysqli_query($conn,"SELECT * FROM `questions` WHERE survey_id='".$survey_id."' AND repeated='".$questionId."' order by sequence_no asc ");
	$sn=0;
	while($question = mysqli_fetch_object($getQuestions)){ 
		// if(strtolower($question->input_field_type=="text")){
			// $type='text';
		// }
		$input_field_type=strtolower($question->input_field_type);
		if (in_array(strtolower($question->input_field_type), ['number','select_one','select_multiple', 'text','note','date','time','datetime','calculate','camera','audio','video','hidden','gps-button'])){
			if(strtolower($question->input_field_type=="note")){
				$repeated_question.='<h3 class="fs-subtitle">Note: '.strip_tags($question->question_name).'</h3>
				<input type="hidden" value="'.$question->question_id.'" name="s'.$question->repeat_count.'[question_id][]" />
				';
				$sn++;
			}
			if(strtolower($question->input_field_type=="text") || strtolower($question->input_field_type=="number")){
				$repeated_question.='<h3 class="fs-subtitle">'.$question->question_name.'</h3><input type="'.$input_field_type.'" class="form-control fs-subtitle" name="s'.$question->repeat_count.'['.$question->field_name.'][]" placeholder="'.$question->question_description.'" >
				<input type="hidden" value="'.$question->question_id.'" name="s'.$question->repeat_count.'[question_id][]" />
				';
				$sn++;
			}
			
			if(strtolower($question->input_field_type=="date") || strtolower($question->input_field_type=="time")){
				$repeated_question.='<h3 class="fs-subtitle">'.$question->question_name.'</h3><input type="'.$input_field_type.'" class="form-control fs-subtitle" name="s'.$question->repeat_count.'['.$question->field_name.'][]" placeholder="'.$question->question_description.'" >
				
				<input type="hidden" value="'.$question->question_id.'" name="s'.$question->repeat_count.'[question_id][]" />
				';
				$sn++;
			}
			if(strtolower($question->input_field_type=="datetime")){
				$repeated_question.='<h3 class="fs-subtitle">'.$question->question_name.'</h3><input type="datetime-local" class="form-control fs-subtitle" name="s'.$question->repeat_count.'['.$question->field_name.'][]" placeholder="'.$question->question_description.'" >
				
				<input type="hidden" value="'.$question->question_id.'" name="s'.$question->repeat_count.'[question_id][]" />
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
				<select class="form-control fs-subtitle" name="s'.$question->repeat_count.'['.$question->field_name.'][]" >
				<option value="">Select Option</option>
				'.$options.'
				</select>
				
				<input type="hidden" value="'.$question->question_id.'" name="s'.$question->repeat_count.'[question_id][]" />
				';
				$sn++;
			}
			/* if(strtolower($question->input_field_type=="select_multiple")){
				$options='';
				$sql = "SELECT * FROM options where question_id = '".$question->question_id."'";
				$getOption = mysqli_query($conn, $sql);
				while ($opt = mysqli_fetch_object($getOption)){ 
					$options.='<option value="'.$opt->option_sequence.'">'.$opt->option_name.'</option>';
				}
			
				$repeated_question.='<h3 class="fs-subtitle">'.$question->question_name.'</h3>
				<select class="form-control fs-subtitle multiple-select" multiple="multiple" onchange="multiSelect('.$question->field_name.')" id="satyendra'.$question->field_name.'" maxlength="'.$question->max_limit;.'" data-placeholder="'.$question_name.'" multiple name="s'.$question->repeat_count.'['.$question->field_name.'][]" >
				<option value="">Select Option</option>
				'.$options.'
				</select>
				
				<input type="hidden" value="'.$question->question_id.'" name="s'.$question->repeat_count.'[question_id][]" />
				';
				$sn++;
			} */
		}

	}
	
	//$repeated_question.= '</fieldset>';
	//for($i=0;$i<$input_value; $i++){
	for($i=0;$i<$input_value; $i++){
		$si=$i+1;
		echo '<div class="fieldset"><span class="legend"> Repeat Group-'.$si.':</span > '.$repeated_question.'</div>';
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
					$qinfo='<i class="fa fa-info-circle tooltips" data-placement="top" data-toggle="tooltip" data-original-title="'.$tooltip_data.'" data-html="true" ></i>';
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