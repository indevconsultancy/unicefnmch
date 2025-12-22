<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php
/* session_start();
define('hostname','65.0.119.62');
define('username','Mqm_22');
define('password','Mquad@22');
define('database','new_mquad');
$conn = mysqli_connect(hostname,username,password,database) or die(mysqli_error());
mysqli_set_charset($conn,"utf8");
if(!$conn)
{
    echo "Connection failed.......!";
}

date_default_timezone_set("Asia/Kolkata");
error_reporting(0);
function safe_var($conn, $string)
{
	return $clean = mysqli_real_escape_string($conn, $string);
}
define('BASE_URL', "https://mquad.org/mis/");
function base_url()
{
	return "https://mquad.org/mis/";
} */
if(empty($_SESSION['user_id'])){ echo "access denied!"; exit(); }

?>

<?php
/// CREATE QUESTIONAIRE FROM USING WEB FORM

if(isset($_POST['questions']) && $_POST['questions']!="" && $_POST['surveyId']!="" && isset($_POST['process']) && $_POST['process']=="WEBFORM" )
{
	// echo "qwerty"; die;
    //require realpath(dirname(__FILE__)) . '/create-questionnaire.php';
	$questionnaireArr = [];
	// $questions = $_POST['questions'];
	// $choices = $_POST['choices'];
	$surveyId = mysqli_real_escape_string($conn, $_POST['surveyId']);
	$userId = $_SESSION['user_id']; 
	$clientId = $_SESSION['client_id'];
	$questionnaireArr['questions'] = $_POST['questions'];
	$questionnaireArr['choices'] = $_POST['choices'];
	$jsondata = mysqli_real_escape_string($conn, json_encode($questionnaireArr));
	// print_r($questionnaireArr['choices']);die;
	// echo $jsondata;
	$cdate = date('Y-m-d H:i:s');
	//echo createExcelSheet($jsondata);
	$getQuestionnaire = mysqli_query($conn, "select count(id) as totq from questionnaires where survey_id='".$surveyId."' ");
	$resQuestionnaire = mysqli_fetch_object($getQuestionnaire);
	if($resQuestionnaire->totq>0){
		$insert  = mysqli_query($conn,"update questionnaires set client_id='".$clientId."', user_id='".$userId."', question_json='".$jsondata."', updated_at='".$cdate."' where survey_id='".$surveyId."' ");
		if($insert){
			$resArr = array("status"=>1,"msg"=>"success");
		}else{
			$resArr = array("status"=>0,"msg"=>"failed");
		}
	}else{
		$sql = "insert into questionnaires set survey_id='".$surveyId."', client_id='".$clientId."', user_id='".$userId."', question_json='".$jsondata."', created_at='".$cdate."' ";
		$insert  = mysqli_query($conn,$sql);
		if($insert){
			$resArr = array("status"=>1,"msg"=>"success");
		}else{
			$resArr = array("status"=>0,"msg"=>"failed");
		}
	}
	echo json_encode($resArr);
} 

if(isset($_POST['process']) && $_POST['process']=='downloadQuestionnaire' && $_POST['surveyId']!=""){
	require realpath(dirname(__FILE__)) . '/create-questionnaire.php';
	//require realpath(dirname(__FILE__)) . '/create-questionnaire_v1.php';
	$surveyId = mysqli_real_escape_string($conn, $_POST['surveyId']);
	//$userId = $_SESSION['user_id']; 
	//$clientId = $_SESSION['client_id'];
	$getQuestionnaire = mysqli_query($conn, "select question_json from questionnaires where survey_id='".$surveyId."' ");
	$resQuestionnaire = mysqli_fetch_object($getQuestionnaire);
	$jsondata = $resQuestionnaire->question_json;
	$jsonn = json_decode($jsondata);
	if(!empty($jsonn->questions)){		
		echo createExcelSheet($jsondata); //createExcelSheet function create in create-questionnaire.php
	}
	else{
		$resArr = 0;
		echo $resArr;
	}	
}
//PUBLISH WEB FORM KHUSHBOO
if(isset($_POST['process']) && $_POST['process']=='publishWebForm' && $_POST['surveyId']!=""){
	require realpath(dirname(__FILE__)) . '/create-questionnaire.php';
	$surveyId = mysqli_real_escape_string($conn, $_POST['surveyId']);
	//$userId = $_SESSION['user_id']; 
	//$clientId = $_SESSION['client_id'];
	$getQuestionnaire = mysqli_query($conn, "select question_json from questionnaires where survey_id='".$surveyId."' ");
	$resQuestionnaire = mysqli_fetch_object($getQuestionnaire);
	$jsondata = $resQuestionnaire->question_json;
	// echo $jsondata;
	echo createExcelSheet($jsondata); //createExcelSheet function create in create-questionnaire.php
	//echo "hello";
}
/// CHECK WEB FORM IS ALREADY EXIST

if(isset($_POST['surveyId']) && $_POST['surveyId']!="" && $_POST['process']=="WEBFORMEXIST"  )
{
	
	function findData($array, $search){
		$result = array();
		foreach ($array as $key => $value){
		  foreach ($search as $k => $v){
			if (!isset($value[$k]) || $value[$k] != $v){
			  continue 2;
			}
		  }
		  $result[] = $key;
		}
		return $result;
	}
	


	$surveyId = mysqli_real_escape_string($conn, $_POST['surveyId']);
	$getQuestionnaire = mysqli_query($conn, "select `id`, `survey_id`, `client_id`, `user_id`, `question_json` from questionnaires where survey_id='".$surveyId."' ");
	$totRows = mysqli_num_rows($getQuestionnaire);
	if($totRows>0){
		$resQuestionnaire = mysqli_fetch_object($getQuestionnaire);
		$questionJsonArr = json_decode($resQuestionnaire->question_json);
		$existingQuestions='';
		$isChecked = [''=>'','yes'=>'checked','Yes'=>'checked'];
		$allChoices = json_decode(json_encode($questionJsonArr->choices), true);
		
		function getChoices($fieldName,$optionLangCode){
			$search = array("list_name"=>$fieldName);
			$choices = $GLOBALS['allChoices'];
			$findChoices = findData($choices, $search);
			$options = [];
				
			if(count($findChoices)>0){
				foreach($findChoices as $choiceKey){
					$choicArr['list_name'] = $choices[$choiceKey]['list_name'];
					$choicArr['value'] = $choices[$choiceKey]['value'];
					$choicArr['label'] = $choices[$choiceKey]['label'];
					$choicArr['choice_filter_parent'] = $choices[$choiceKey]['choice_filter_parent'];
					$choicArr['constraint'] = $choices[$choiceKey]['constraint'];
					foreach($optionLangCode as $codeVal){
						if($choices[$choiceKey][$codeVal] != ''){
						$choicArr[$codeVal] = $choices[$choiceKey][$codeVal];
						}
					}
					$options[]=$choicArr;
				}
			}
			return $options;
		}
		
		$group_ques=[]; $repeated_group_ques=[]; $question_groups=''; $repeated_question_groups='';
		// echo "<pre>";
		// print_r($questionJsonArr->questions);die;
		foreach($questionJsonArr->questions as $key=>$questionObj){
			
			$type = $questionObj->type;
			$name = $questionObj->name;
			// $label = $questionObj->label;
			$label = safe_var($conn,$questionObj->label);
			// $dictionary_label = $questionObj->dictionary_label;
			$dictionary_label = safe_var($conn,$questionObj->dictionary_label);
			$limit = $questionObj->limit;
			$constraint = $questionObj->constraint;
			// $constraint_message = $questionObj->constraint_message;
			$constraint_message = safe_var($conn,$questionObj->constraint_message);
			// $hint = $questionObj->hint;
			$hint = safe_var($conn,$questionObj->hint);
			$paradata = $questionObj->paradata;
			$appearance = $questionObj->appearance;
			$questionObj->choice_filter;
			$questionObj->repeat_count;
			$calculation = $questionObj->calculation;
			$dafault_response = $questionObj->default_response;
			$deidentify = $questionObj->deidentify;
			$read_only = $questionObj->read_only;
			$preserve = $questionObj->preserve;
			$unique_id = $questionObj->unique_id;
			$required = $questionObj->required;
			$questionObj->lookups;
			$questionObj->media_file;
			$parameters = $questionObj->parameters;
			$questionObj->choice_relation;
			$questionObj->relevant;
			$relevant_for_form = $questionObj->relevant_for_form; 
			$hint =	htmlspecialchars(stripslashes($hint), ENT_QUOTES);
			$label = htmlspecialchars(stripslashes($label), ENT_QUOTES);
			$dictionary_label =	htmlspecialchars(stripslashes($dictionary_label), ENT_QUOTES);
			$constraint_message =	htmlspecialchars(stripslashes($constraint_message), ENT_QUOTES);
			$questionCode = array();
			$languagemaster = array();
			$langsql = mysqli_query($conn, "select language_code,language_name from languages where status = 0 and language_id not in(1) order by language_name asc");
			$i=0;
			
			$paradataArr = [];
			if($paradata != ''){
				$paradataArr = explode(',',$paradata);
			}
			
			$checkedTimeStamp = '';
			$checkedGPS = '';
			$checkedKeyStroke = '';
			$checkedAudio = '';
			
			if(in_array('T',$paradataArr)){
				$checkedTimeStamp = "checked";
			}
			if(in_array('G',$paradataArr)){
				$checkedGPS = "checked";
			}
			if(in_array('K',$paradataArr)){
				$checkedKeyStroke = "checked";
			}
			if(in_array('A',$paradataArr)){
				$checkedAudio = "checked";
			}

			
			while($result =	mysqli_fetch_object($langsql)){
				
				$lanname = $result->language_name;
				$code = strtoupper($result->language_code);
				
				$langlabel = "label::".$code;
				$langhint = "hint::".$code;
				$langconstraint = "constraint_message::".$code;
				if(array_key_exists ($langlabel , $questionObj )){
					$questionCode[$i]['lanname'] = $lanname;
					$questionCode[$i]['code'] = $code;
					$questionCode[$i]['label'] = $questionObj->$langlabel;
				}
				if(array_key_exists ($langhint , $questionObj )){
					$questionCode[$i]['hint'] = $questionObj->$langhint;
				}
				if(array_key_exists ($langconstraint , $questionObj )){
					$questionCode[$i]['constraint'] = $questionObj->$langconstraint;
				}
				$i++;
			}
			/// Multilingual
			$multilangHtml='';
			$multilangHtmlOption='';
			$countli = 1;
			// echo "<pre/>";
			// print_r($questionCode); 
			foreach($questionCode as $key => $value){
				
					$disabled_multilang_two = '';
					$disabled_multilang_two_option = '';
					if($hint == ''){
						$disabled_multilang_two = 'disabled';
						$disabled_multilang_two_option = 'disabled';
					}
					
					$disabled_multilang_three = '';
					$disabled_multilang_three_option = '';
					if($constraint_message == ''){
						$disabled_multilang_three = 'disabled';
						$disabled_multilang_three_option = 'disabled';
					}
					
							
					$val_label = '';					
					$val_hint = '';					
					$val_contraint = '';					
					if($value['label'] != ''){
						$val_label = safe_var($conn,$value['label']);
					}				
					if($value['hint'] != ''){
						$val_hint = safe_var($conn,$value['hint']);
					}				
					if($value['constraint'] != ''){
						$val_contraint = safe_var($conn,$value['constraint']);
					}
				
					$multilangHtml.='
						<li>
							<select name="languages[]" id="languageSelect" class="form-control languageSelect">
								<option value="'.$value['code'].'">'.$value['lanname'].'</option>
								
							</select>
							<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*" value="'.htmlspecialchars(stripslashes($val_label), ENT_QUOTES).'"/>
							<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint*" value="'.htmlspecialchars(stripslashes($val_hint), ENT_QUOTES).'" '.$disabled_multilang_two.' />
							<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message*" value="'.htmlspecialchars(stripslashes($val_contraint), ENT_QUOTES).'" '.$disabled_multilang_three.' />
							<div class="del-relbtn">
								<i class="fa fa-trash"></i>
							</div>
						</li>';
						
					$multilangHtmlOption.='
						<li>
							<select name="languages[]" id="languageSelectOne" class="form-control languageSelectOne">
								<option value="'.$value['code'].'">'.$value['lanname'].'</option>
								
							</select>
							<input type="hidden" class="form-control count_li" name="licount" value="'.$countli.'">
							<input type="text" class="form-control multi_language multi-one-option red-star-required"  name="lang-label[]" placeholder="Label*" value="'.htmlspecialchars(stripslashes($val_label), ENT_QUOTES).'"/>
							<input type="text" class="form-control multi_language multi-two-option"  name="lang-hint[]" placeholder="Hint*" value="'.htmlspecialchars(stripslashes($val_hint), ENT_QUOTES).'"  '.$disabled_multilang_two_option.' />
							<input type="text" class="form-control multi_language multi-three-option" name="lang-constraint[]" placeholder="Constraint message*" value="'.htmlspecialchars(stripslashes($val_contraint), ENT_QUOTES).'" '.$disabled_multilang_three_option.' />
							<div class="del-multioptionbtn">
								<i class="fa fa-trash"></i>
							</div>
						</li>';
						
					$countli++;
			}
			
				
			$relvHtml='';
			if(!empty($relevant_for_form)){
				foreach($relevant_for_form as $relevantForForm){
					
					$allOpr = '';
					$operators = ['<', '>', '<=', '>=', '=', '!='];
					foreach ($operators as $op) {
						if ($op !== $relevantForForm->operator) {
							$allOpr.='<option value="'.$op.'">'.$op.'</option>';
						}
					}
					
					$allConditions = '';
					$conditonsArr = ['&', '|'];
					if($relevantForForm->rel_and_or == '&'){ 
						$allConditions.='<option value='.$relevantForForm->rel_and_or.' selected>& (AND)</option>';
					}
					else{
						if($relevantForForm->rel_and_or !== ''){
							$allConditions.='<option value='.$relevantForForm->rel_and_or.' selected>| (OR)</option>';
						}
					}
					foreach ($conditonsArr as $ca) {
						if ($ca !== $relevantForForm->rel_and_or) {
							if($ca == '&'){
								$ca_value = "& (AND)";
							}
							else{
								$ca_value = "| (OR)";
							}
							$allConditions.='<option value="'.$ca.'">'.$ca_value.'</option>';
						}
					}
					
					// $allConditions.='<option value="$relevantForForm->rel_and_or" selected>$relevantForForm->rel_and_or</option>';
					
					$relvHtml.=<<<RELV
						<li>
							<select name="relevant[]" class="form-control rel_qnames">
								<option value="$relevantForForm->qname">$relevantForForm->qname</option>
							</select>
							<select name="operator[]" class="form-control rel_operators">
								<option value="">Select Operator</option>
								<option value="$relevantForForm->operator" selected > $relevantForForm->operator </option>
								$allOpr
							</select>
							<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt" value="$relevantForForm->relevant_value">
							<select name="condition1[]" class="form-control rel_andOr">
								<option value="">Select Condition</option>
								$allConditions
							</select>
							<div class="del-relbtn">
								<i class="fa fa-trash"></i>
							</div>
						</li>
					RELV;
				}
				
			}
			
			
			/// NUMBER TYPE QUESTIONS 
			if($type==='number'){
			$appearance_opt = getAppearance($conn,"number",$appearance); 
			$dafault_response = trim($dafault_response, '{}');
						
				$existingQuestions=<<<SSS
						<div class="number-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<input class="form-control ques" name="number-$key" type="number" id="number-$key" placeholder="Type: Number" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="number" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>
										</div>
										<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
									<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" oninput="validateDefaultResponse(this)">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
														<!--	<li class="d-none">
																<select name="languages[]" id="languageSelect" class="form-control languageSelect">
																<option value="">Select Language</option>
																</select>
																<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
																<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
																<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
																<div class="del-relbtn">
																	<i class="fa fa-trash"></i>
																</div>
															</li>-->
															$multilangHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div> -->
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					SSS;
			}
			
			/// DATE TYPE QUESTIONS 
			if($type==='date'){
			$appearance_opt = getAppearance($conn,"date",$appearance); 
			$option1 = $option2 = $option3 = $option5 = $option6 = $option7 = '';
			if($dafault_response == 'nowdate'){
				$option1 = 'selected';
			}
			elseif($dafault_response == 'nowtime'){
				$option2 = 'selected';
			}
			elseif($dafault_response == 'nowmonth-year'){
				$option3 = 'selected';
			}
			elseif($dafault_response == 'nowmonth'){
				$option5 = 'selected';
			}
			elseif($dafault_response == 'nowdays'){
				$option6 = 'selected';
			}
			elseif($dafault_response == 'nowyear'){
				$option7 = 'selected';
			}
			
			$existingQuestions=<<<SSS
						<div class="date-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<input class="form-control ques" name="date-$key" type="text" placeholder="Type: Date" id="date-$key" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="date" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>

										</div>
										<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
									<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select name="dafault_response" class="form-control mt-1 mb-3 q_dafault_response">
												<option value="">Select an option</option>
												<option value="nowdate" $option1>nowdate</option>
												<option value="nowtime" $option2>nowtime</option>
												<option value="nowmonth-year" $option3>nowmonth-year</option>
												<option value="nowmonth" $option5>nowmonth</option>
												<option value="nowdays" $option6>nowdays</option>
												<option value="nowyear" $option7>nowyear</option>
											</select>
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
														<!--	<li class="d-none">
																<select name="languages[]" id="languageSelect" class="form-control languageSelect">
																<option value="">Select Language</option>
																</select>
																<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
																<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
																<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
																<div class="del-relbtn">
																	<i class="fa fa-trash"></i>
																</div>
															</li>-->
															$multilangHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div> -->
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					SSS;
			}
			
			
			/// DATE TYPE QUESTIONS 
			if($type==='note'){
			//$appearance_opt = getAppearance($conn,"note",$appearance); 
				$existingQuestions=<<<SSS
						<div class="date-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<textarea class="form-control ques" name="note-$key" id="note-$key" placeholder="Type: Note" spellcheck="false" readonly></textarea>
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="note" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>

										</div>
									<!--	<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												
											</select>
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
									<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
														<!--	<li class="d-none">
																<select name="languages[]" id="languageSelect" class="form-control languageSelect">
																<option value="">Select Language</option>
																</select>
																<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
																<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
																<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
																<div class="del-relbtn">
																	<i class="fa fa-trash"></i>
																</div>
															</li>-->

															$multilangHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>-->
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					SSS;
			}
			
			
			///TEXT TYPE QUESTIONS
			if($type==='text'){
			$appearance_opt = getAppearance($conn,"text",$appearance); 
			$dafault_response = trim($dafault_response, '{}');
				$existingQuestions=<<<SSS
						<div class="number-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<input class="form-control ques" name="number-$key" type="number" id="number-$key" placeholder="Type: Text" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="text" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>

										</div>
										<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" oninput="validateDefaultResponse(this)">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
														<!--	<li class="d-none">
																<select name="languages[]" id="languageSelect" class="form-control languageSelect">
																<option value="">Select Language</option>
																</select>
																<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
																<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
																<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
																<div class="del-relbtn">
																	<i class="fa fa-trash"></i>
																</div>
															</li>-->

															$multilangHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div> -->
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					SSS;
			}
			
			///AUDIO TYPE QUESTIONS
			if($type==='audio'){
				// $appearance_opt = getAppearance($conn,"audio",$appearance); 
				$existingQuestions=<<<SSS
						<div class="audio-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<input class="form-control ques" name="audio-$key" type="text" id="audio-$key" placeholder="Type: Audio" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="audio" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>

										</div>
									<!--	<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												
											</select>
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
														<!--	<li class="d-none">
																<select name="languages[]" id="languageSelect" class="form-control languageSelect">
																<option value="">Select Language</option>
																</select>
																<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
																<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
																<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
																<div class="del-relbtn">
																	<i class="fa fa-trash"></i>
																</div>
															</li>-->

															$multilangHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div> -->
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					SSS;
			}
			
			///VIDEO TYPE QUESTIONS
			if($type==='video'){
				//$appearance_opt = getAppearance($conn,"video",$appearance); 
				$existingQuestions=<<<SSS
						<div class="video-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<input class="form-control ques" name="video-$key" type="text" id="video-$key" placeholder="Type: Video" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="video" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>

										</div>
									<!--	<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												
											</select>
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
														<!--	<li class="d-none">
																<select name="languages[]" id="languageSelect" class="form-control languageSelect">
																<option value="">Select Language</option>
																</select>
																<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
																<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
																<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
																<div class="del-relbtn">
																	<i class="fa fa-trash"></i>
																</div>
															</li>-->

															$multilangHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div> -->
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					SSS;
			}
			
			///GPS-Button TYPE QUESTIONS
			if($type==='gps_button'){
				// $appearance_opt = getAppearance($conn,"gps_button",$appearance); 
				$existingQuestions=<<<SSS
						<div class="gps-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<input class="form-control ques" name="gps_button-$key" type="text" id="gps_button-$key" placeholder="Type: GPS-Button" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="gps_button" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>

										</div>
									<!--	<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												
											</select>
										</div> -->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
									<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
														<!--	<li class="d-none">
																<select name="languages[]" id="languageSelect" class="form-control languageSelect">
																<option value="">Select Language</option>
																</select>
																<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
																<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
																<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
																<div class="del-relbtn">
																	<i class="fa fa-trash"></i>
																</div>
															</li>-->

															$multilangHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div> -->
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					SSS;
			}
			
			
			///Picture TYPE QUESTIONS
			if($type==='picture'){
				$appearance_opt = getAppearance($conn,"picture",$appearance); 
				$existingQuestions=<<<SSS
						<div class="picture-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<input class="form-control ques" name="picture-$key" type="text" id="picture-$key" placeholder="Type: Picture" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="picture" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>

										</div>
										<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
														<!--	<li class="d-none">
																<select name="languages[]" id="languageSelect" class="form-control languageSelect">
																<option value="">Select Language</option>
																</select>
																<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
																<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
																<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
																<div class="del-relbtn">
																	<i class="fa fa-trash"></i>
																</div>
															</li>-->

															$multilangHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div> -->
										</div>
									</div>
								</div>
								
								
							</div>
						</div>
					SSS;
			}
			
			
			/// SELECT_ONE TYPE QUESTIONS
			if($type==='select_one'){
				$appearance_opt = getAppearance($conn,"select_one",$appearance); 
				$langsql = mysqli_query($conn, "select language_code,language_name from languages where status = 0 and language_id not in(1) order by language_name asc");
				$optionLangCode = array();
				while($result =	mysqli_fetch_object($langsql)){
					
					$code = strtoupper($result->language_code);
					$qwerty = "label::".$code;
					$optionLangCode[] = $qwerty;
				}	
				$qoptions = getChoices($name,$optionLangCode);
				$optdec=''; $opt='';
				// echo "<pre/>";
				// print_r($qoptions); die;
				
				foreach($qoptions as $qoption){
					$qoptn = (object) $qoption;
					// print_r($qoptn); die;
					$multioptionlabels = '';
					$valInc = 1;
					foreach($optionLangCode as $codeVal){
						
						$labelVal = $qoptn->$codeVal;
						
						$qoptn_label = '';
						if($qoptn->label !=''){
							$removequotesstring = safe_var($conn,$qoptn->label);
							$qoptn_label = htmlspecialchars(stripslashes($removequotesstring), ENT_QUOTES);
						}
						
						if($labelVal != ''){
							$labelVal = safe_var($conn,$labelVal);
							
							$multioptionlabels .='<input type="text" class="form-control   option-language-label option-lang-val'.$valInc.'" name="option-lang-val[]" placeholder="'.$codeVal.'*" value="'.htmlspecialchars(stripslashes($labelVal), ENT_QUOTES).'" >';
							$valInc++;
						}
						
					}
					
					$optdec.=<<<OPTDEC
							<li>
								<input type="radio" class="option-selected" checked="checked" class="form-checkbox">
								<input type="text" class="form-control nameOption" name="option-name[]" placeholder="Option Name" value='$qoptn_label' >
								<input type="text" class="form-control valueOption" name="option-value[]" placeholder="Option Value" value="$qoptn->value" >
								$multioptionlabels
								<div class="del-btn">
									<i class="fa fa-trash"></i>
								</div>
							</li>
							OPTDEC;
					$opt.=<<<OPT
							<option value="$qoptn->value" disabled>$qoptn->label</option>
						OPT;
					
				}
				$existingQuestions=<<<SSS
						<div class="number-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<select class="form-control ques" name="$name" id="select-$key" disabled>	
									<option class="select-placeholder" value="">Type: Select One</option>
									$opt
								</select>
							<!--	<input type="text" class="form-control" placeholder="Type: Select One Option" readonly>-->
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
											
											<input class="form-check-input mb-3 q_ismultiple " type="checkbox" value="yes" name="multiple-selects">
											<label class="form-check-label">
												Allow Multiple Selection
												<i data-html="true" data-toggle="tooltip" data-title="Check this to allow select multiple options." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
												
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="select_one" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
											<i data-html="true" data-toggle="tooltip" data-data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>

										</div>
										<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>												
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
								<!--		<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>
										<div>
											<input class="form-check-input mb-3 q_ismultiple " type="checkbox" value="yes" name="multiple-selects">
											<label class="form-check-label">
												Allow Multiple Selection
												<i data-html="true" data-toggle="tooltip" data-title="Check this to allow select multiple options." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										</div>-->
										
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
															$multilangHtmlOption
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield-option">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<div class="fw">
											<div>
												<label class="form-check-label">
													Options<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Add Choices to the question" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>												
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="multi-option-new-ul">
															$optdec
														</ul>
													</div>
												</div>
											</div>
											<div class="add-field" id="add-field-option" >
												Add Options	<i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div> -->
										</div>
									</div>
								</div>
								
							</div>
						</div>
					SSS;
			}
			
			/// SELECT_ONE TYPE QUESTIONS
			if($type==='select_multiple'){
				$appearance_opt = getAppearance($conn,"select_multiple",$appearance); 
				$langsql = mysqli_query($conn, "select language_code,language_name from languages where status = 0 and language_id not in(1) order by language_name asc");
				$optionLangCode = array();
				while($result =	mysqli_fetch_object($langsql)){
					
					$code = strtoupper($result->language_code);
					$qwerty = "label::".$code;
					$optionLangCode[] = $qwerty;
				}	
				$qoptions = getChoices($name,$optionLangCode);
				$optdec=''; $opt='';
				// echo "<pre/>";
				// print_r($qoptions); die;
				
				foreach($qoptions as $qoption){
					$qoptn = (object) $qoption;
					// print_r($qoptn); die;
					$multioptionlabels = '';
					$valInc = 1;
					foreach($optionLangCode as $codeVal){
						
						$labelVal = $qoptn->$codeVal;
						$qoptn_label = '';
						if($qoptn->label !=''){
							$removequotesstring = safe_var($conn,$qoptn->label);
							$qoptn_label = htmlspecialchars(stripslashes($removequotesstring), ENT_QUOTES);
						}
						if($labelVal != ''){
							$labelVal = safe_var($conn,$labelVal);
							$multioptionlabels .='<input type="text" class="form-control   option-language-label option-lang-val'.$valInc.'" name="option-lang-val[]" placeholder="'.$codeVal.'*" value="'.htmlspecialchars(stripslashes($labelVal), ENT_QUOTES).'" >';
							$valInc++;
						}
						
					}
					
					$optdec.=<<<OPTDEC
							<li>
								<input type="radio" class="option-selected" checked="checked" class="form-checkbox">
								<input type="text" class="form-control nameOption" name="option-name[]" placeholder="Option Name" value='$qoptn_label' >
								<input type="text" class="form-control valueOption" name="option-value[]" placeholder="Option Value" value="$qoptn->value" >
								$multioptionlabels
								<div class="del-btn">
									<i class="fa fa-trash"></i>
								</div>
							</li>
							OPTDEC;
					$opt.=<<<OPT
							<option value="$qoptn->value" disabled>$qoptn->label</option>
						OPT;
					
				}
				$existingQuestions=<<<SSS
						<div class="number-seaction">
							<div class="button">
								<div class="edit-form"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-form"><i class="fa-solid fa-xmark"></i></div>
							</div>
							<div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> 
							<div class="input-seaction">
								<select class="form-control ques" name="$name" id="select-$key" disabled>	
									<option class="select-placeholder" value="">Type: Select Multiple</option>
									$opt
								</select>
							<!--	<input type="text" class="form-control" placeholder="Type: Select Multiple Option" readonly>-->
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<div>
											<input class="form-check-input mb-3 q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
											<label class="form-check-label">
												Required 
											</label>
											
											<input class="form-check-input mb-3 q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
											<label class="form-check-label">
												Read Only
											</label>
											
											<input class="form-check-input mb-3 q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
											<label class="form-check-label">
												Unique Id
											</label>
											
											<input class="form-check-input mb-3 q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
											<label class="form-check-label">
												Preserve
											</label>
											
											<input class="form-check-input mb-3 q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
											<label class="form-check-label">
												Deidentify
											</label>
											
											<input class="form-check-input mb-3 q_ismultiple " type="checkbox" value="yes" name="multiple-selects" checked>
											<label class="form-check-label">
												Allow Multiple Selection
												<i data-html="true" data-toggle="tooltip" data-title="Check this to allow select multiple options." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
												
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="select_multiple" >
										</div>
										<div>
											<label class="form-check-label">
												Label<span style="color:red">*</span>
											<i data-html="true" data-toggle="tooltip" data-data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value="$label">
										</div>
										<div>
											<label class="form-check-label">
												Dictionary Label
												<i data-html="true" data-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>
										<div>
											<label class="form-check-label">
												Hint
												<i data-html="true" data-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-html="true" data-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-html="true" data-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-html="true" data-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input mb-3 q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
												<label class="form-check-label">
													Audio
												</label>
											</div>

										</div>
										<div>
											<label class="form-check-label">
												Appearance
												<i data-html="true" data-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-html="true" data-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-html="true" data-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>												
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-html="true" data-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>
										<div>
											<input class="form-check-input mb-3 q_ismultiple " type="checkbox" value="yes" name="multiple-selects">
											<label class="form-check-label">
												Allow Multiple Selection
												<i data-html="true" data-toggle="tooltip" data-title="Check this to allow select multiple options." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										</div> -->
										
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
															$multilangHtmlOption
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield-option">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<div class="fw">
											<div>
												<label class="form-check-label">
													Options<span style="color:red">*</span>
												<i data-html="true" data-toggle="tooltip" data-title="Add Choices to the question" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>												
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="multi-option-new-ul">
															$optdec
														</ul>
													</div>
												</div>
											</div>
											<div class="add-field" id="add-field-option" >
												Add Options	<i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
											</div>
										<!--	<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul>
															<li class="d-none">
																<select name="relevant[]" class="form-control rel_qnames">
																	<option value="">Select Question</option>
																</select>
																<select name="operator[]" class="form-control rel_operators">
																	<option value="">Select Operator</option>
																	<option value="<"><</option>
																	<option value=">">></option>
																	<option value="<="><=</option>
																	<option value=">=">>=</option>
																	<option value="=">=</option>
																	<option value="!=">!=</option>
																</select>
																<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt"/>
																<select name="condition1[]" class="form-control rel_andOr">
																	<option value="">Select Condition</option>
																	<option value="&">& (AND)</option>
																	<option value="|">| (OR)</option>
																</select>
																<div class="del-blank">
																	&nbsp;
																</div>
															</li>
															$relvHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-relfield">
												Add Relevant <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div> -->
										</div>
									</div>
								</div>
								
							</div>
						</div>
					SSS;
			}
			
				
			///	begin_group GROUP SECTION
			
			$inside_group_questions = '';
			if($type==='begin_group'){
				$checked_group = '';
				if($appearance == 'onescreen'){
					$checked_group .= 'checked';
				}
				$group_ques[] = $name;
				$existingQuestions = '';
				$question_groups.=<<<GROUPS
					<fieldset class="dragto ui-sortable">
					  <div class="groups-header">
						<h4 class="group-name">$name<span style="color:red">*</span></h4>
						<div class="button ques">
							<div class="edit-group"><i class="fa-solid fa-pen-to-square"></i></div>
							<div class="close-group"><i class="fa-solid fa-xmark"></i></div>
							
						</div>
						
						<div class="edit-main-container" style="display: none;">
							<div class="edit-wrapper-container">
								<div>
									<label class="form-check-label">
										Group Name<span style="color:red">*</span>
										<i data-html="true" data-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input type="text" class="form-control mt-1 mb-3 q_name" value="$name" name="group-name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
									<p id="errorMessage" class="error-message" style="color: red"></p>
									<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="begin_group">
								</div>
								<input class="form-check-input mb-3 fieldlist" type="checkbox" name="fieldlist" value="yes" $checked_group>
								<label class="form-check-label">
									Onescreen
									<i data-html="true" data-toggle="tooltip" data-title="Show group of questions on same screen." class="fa fa-info-circle tooltip-icon"></i>
								</label>
								
									<!--	<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
														<li class="d-none">
																<select name="languages[]" id="languageSelect" class="form-control languageSelect">
																<option value="">Select Language</option>
																</select>
																<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
																<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
																<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
																<div class="del-relbtn">
																	<i class="fa fa-trash"></i>
																</div>
															</li>

															$multilangHtml
														</ul>
													</div>
												</div>
											</div>
											<div class="add-langfield">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>-->
								<div class="fw hideforfirst">
									<div>
										<label class="form-check-label">
											Relevant
											<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
										</label>
										<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
									</div>
								<!--	<div class="row">
										<div class="col-md-12">
											<div class="multiinputs-div">
												<ul>
													$relvHtml
												</ul>
											</div>
										</div>
									</div>
									<div class="add-relfield">
										Add Relevant <i class="fa fa-plus"></i>
									</div>
									<div class="clearfix"></div> -->
								</div>
							</div>
						</div>
			
			
					  </div>
					GROUPS;
			}
			
			if(count($group_ques)>0){
				$question_groups.=$existingQuestions;
				$existingQuestions = '';
			}
			
			
			if($type==='end_group'){
				// array_pop($group_ques);
				$group_ques = [];
				$existingQuestions = '';
				$question_groups.=<<<GROUPS
					
					  
						<div class="add-fields add-fields-new">
						 <h5>Add Types: </h5>
						  <div>
							<a href="javascript:void(0);" class="number"><i class="fa fa-hashtag"></i> Number </a>
							<a href="javascript:void(0);" class="select-single"><i class="fa fa-chevron-circle-down"></i> Select </a>
							<a href="javascript:void(0);" class="text"><i class="fa fa-file-text"></i> Text </a>
							<a href="javascript:void(0);" class="note"><i class="fa fa-sticky-note"></i> Note </a>
							<a href="javascript:void(0);" class="date"><i class="fa fa-calendar"></i> Date </a>
							<a href="javascript:void(0);" class="b-group"><i class="fa fa-object-group"></i> Group Questions </a>
							<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-object-group" ></i> Repeat Group </a>
							<a href="javascript:void(0);" class="audio"><i class="fa fa-file-audio-o"></i> Audio </a>
							<a href="javascript:void(0);" class="video"><i class="fa fa-file-video-o"></i> Video </a>
							<a href="javascript:void(0);" class="gps_button"><i class="fa fa-map-marker"></i> GPS-Button </a>
							<a href="javascript:void(0);" class="picture"><i class="fa fa-picture-o"></i> Picture </a>
						  </div>
						</div>
					  
						<div class="d-none ui-draggable" style="">
							<div class="button ques">
								<div class="edit-group"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-group"><i class="fa-solid fa-xmark"></i></div>
							</div>
							
							<div>
								<div>
									<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="end_group">
								</div>
							</div>
						</div>
					
					</fieldset>
				GROUPS;
				
				
				$existingQuestions.=$question_groups;
				$question_groups = '';
			}
			// $alexistingQuestions.=$existingQuestions;
			
			
			
			//// 
			///	begin_repeat GROUP SECTION
			
			if($type==='begin_repeat'){
			
			$checked_reptgrp = '';
			$checked_parameter = '';
			if($appearance == 'onescreen'){
				$checked_reptgrp .= 'checked';
				
			}
			if($parameters == 'multiroster'){
				$checked_parameter .= 'checked';
				
			}
				$repeated_group_ques[] = $name;
				
				$existingQuestions = '';
				$question_groups.=<<<GROUPS
					<fieldset class="dragto ui-sortable">
					  <div class="groups-header">
						<h4 class="group-name">$name<span style="color:red">*</span></h4>
						<div class="button ques">
							<div class="rpedit-group"><i class="fa-solid fa-pen-to-square"></i></div>
							<div class="close-group"><i class="fa-solid fa-xmark"></i></div>
						</div>
						
						<div class="edit-main-container" style="display: none;">
							<div class="edit-wrapper-container">
								<div>
									<label class="form-check-label">
										Repeat Group Name<span style="color:red">*</span>
										<i data-html="true" data-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input type="text" class="form-control mt-1 mb-3 q_name" value="$name" name="group-name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
									<p id="errorMessage" class="error-message" style="color: red"></p>
									<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="begin_repeat">
								</div>
								<div>
									<label class="form-check-label">
										Repeat Count
										<i data-html="true" data-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count" value="$questionObj->repeat_count">
								</div>
								<div>
									<input class="form-check-input mb-3 fieldlist" type="checkbox" name="fieldlist" value="yes" $checked_reptgrp>
									<label class="form-check-label">
										Onescreen
										<i data-html="true" data-toggle="tooltip" data-title="Show group of questions on same screen." class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input class="form-check-input mb-3 parameters" type="checkbox" name="parameters" value="multiroster" $checked_parameter>
									<label class="form-check-label">
										Parameter
										<i data-html="true" data-toggle="tooltip" data-title="Repeat set of questions based on dependent response value" class="fa fa-info-circle tooltip-icon"></i>
									</label>
								</div>
							<!--	<div class="fw">
									<div>
										<label class="form-check-label">
											Multilingual Fields
											<i data-html="true" data-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
										</label>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="multiinputs-div">
												<ul class="countli multi-lang-new-ul">
													<li class="d-none">
														<select name="languages[]" id="languageSelect" class="form-control languageSelect">
														<option value="">Select Language</option>
														</select>
														<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
														<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint"/>
														<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message"/>
														<div class="del-relbtn">
															<i class="fa fa-trash"></i>
														</div>
													</li>

													$multilangHtml
												</ul>
											</div>
										</div>
									</div>
									<div class="add-langfield-option">
										Add Multilingual <i class="fa fa-plus"></i>
									</div>
									<div class="clearfix"></div>
								</div>-->
								<div class="fw hideforfirst">
									<div>
										<label class="form-check-label">
											Relevant
											<i data-html="true" data-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
										</label>
										<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value="$questionObj->relevant" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
									</div>
								<!--	<div class="row">
										<div class="col-md-12">
											<div class="multiinputs-div">
												<ul>
													$relvHtml
												</ul>
											</div>
										</div>
									</div>
									<div class="add-relfield">
										Add Relevant <i class="fa fa-plus"></i>
									</div>
									<div class="clearfix"></div> -->
								</div>
							</div>
						</div>
			
			
					  </div>
					GROUPS;
			}
			
			
			
			if(count($repeated_group_ques)>0 && count($group_ques)>0){
				$question_groups.=$existingQuestions;
				$existingQuestions = '';
			}
			if(count($repeated_group_ques)>0 && count($group_ques)==0){
				$question_groups.=$existingQuestions;
				$existingQuestions = '';
			}
			
			if($type==='end_repeat'){
				// array_pop($repeated_group_ques);
				$repeated_group_ques= [];
				$existingQuestions = '';
				$question_groups.=<<<GROUPS
					
					  
						<div class="add-fields add-fields-new">
						 <h5>Add Types: </h5>
						  <div>
							<a href="javascript:void(0);" class="number"><i class="fa fa-hashtag"></i> Number </a>
							<a href="javascript:void(0);" class="select-single"><i class="fa fa-chevron-circle-down"></i> Select </a>
							<a href="javascript:void(0);" class="text"><i class="fa fa-file-text"></i> Text </a>
							<a href="javascript:void(0);" class="note"><i class="fa fa-sticky-note"></i> Note </a>
							<a href="javascript:void(0);" class="date"><i class="fa fa-calendar"></i> Date </a>
							<a href="javascript:void(0);" class="b-group"><i class="fa fa-object-group"></i> Group Questions </a>
							<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-object-group" ></i> Repeat Group </a>
							<a href="javascript:void(0);" class="audio"><i class="fa fa-file-audio-o"></i> Audio </a>
							<a href="javascript:void(0);" class="video"><i class="fa fa-file-video-o"></i> Video </a>
							<a href="javascript:void(0);" class="gps_button"><i class="fa fa-map-marker"></i> GPS-Button </a>
							<a href="javascript:void(0);" class="picture"><i class="fa fa-picture-o"></i> Picture </a>
						  </div>
						</div>
					  
						<div class="d-none ui-draggable" style="">
							<div class="button ques">
								<div class="edit-group"><i class="fa-solid fa-pen-to-square"></i></div>
								<div class="close-group"><i class="fa-solid fa-xmark"></i></div>
							</div>
							
							<div>
								<div>
									<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="end_repeat">
								</div>
							</div>
						</div>
					
					</fieldset>
				GROUPS;
				
				
				$existingQuestions.=$question_groups;
				$question_groups='';
				
			}
			$alexistingQuestions.=$existingQuestions;
			$existingQuestions='';
			
		}
		
		echo $alexistingQuestions;
	}else{
		//echo "record not found.";
	}
}

?>