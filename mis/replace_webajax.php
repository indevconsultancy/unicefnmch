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
define('BASE_URL', "https://unicef.indevconsultancy.in/mis/");
function base_url()
{
	return "https://unicef.indevconsultancy.in/mis/";
} */
if (empty($_SESSION['user_id'])) {
	echo "access denied!";
	exit();
}

?>

<?php
/// CREATE QUESTIONAIRE FROM USING WEB FORM


if (isset($_POST['questions']) && $_POST['questions'] != "" && $_POST['surveyId'] != "" && $_POST['process'] == 'WEBFORM') {

	// echo $_POST['questions']; die;

	//require realpath(dirname(__FILE__)) . '/create-questionnaire.php';
	$questionnaireArr = [];
	// $questions = $_POST['questions'];
	// $choices = $_POST['choices'];
	$surveyId = mysqli_real_escape_string($conn, $_POST['surveyId']);
	$userId = $_SESSION['user_id'];
	$clientId = $_SESSION['client_id'];
	$questionnaireArr['questions'] = json_decode($_POST['questions']);
	$questionnaireArr['choices'] = json_decode($_POST['choices']);
	$jsondata = mysqli_real_escape_string($conn, json_encode($questionnaireArr));
	// print_r($questionnaireArr['choices']);die;
	// echo $jsondata;
	$cdate = date('Y-m-d H:i:s');
	//echo createExcelSheet($jsondata);
	$getQuestionnaire = mysqli_query($conn, "select count(id) as totq from questionnaires where survey_id='" . $surveyId . "' ");
	$resQuestionnaire = mysqli_fetch_object($getQuestionnaire);

	if ($resQuestionnaire->totq > 0) {
		$insert  = mysqli_query($conn, "update questionnaires set client_id='" . $clientId . "', user_id='" . $userId . "', question_json='" . $jsondata . "', updated_at='" . $cdate . "' where survey_id='" . $surveyId . "' ");
		if ($insert) {
			$resArr = array("status" => 1, "msg" => "success");
		} else {
			$resArr = array("status" => 0, "msg" => "failed");
		}
	} else {
		$sql = "insert into questionnaires set survey_id='" . $surveyId . "', client_id='" . $clientId . "', user_id='" . $userId . "', question_json='" . $jsondata . "', created_at='" . $cdate . "' ";
		$insert  = mysqli_query($conn, $sql);
		if ($insert) {
			$resArr = array("status" => 1, "msg" => "success");
		} else {
			$resArr = array("status" => 0, "msg" => "failed");
		}
	}
	echo json_encode($resArr);
}

if (isset($_POST['process']) && $_POST['process'] == 'downloadQuestionnaire' && $_POST['surveyId'] != "") {
	require realpath(dirname(__FILE__)) . '/create-questionnaire.php';
	//require realpath(dirname(__FILE__)) . '/create-questionnaire_v1.php';
	$surveyId = mysqli_real_escape_string($conn, $_POST['surveyId']);
	//$userId = $_SESSION['user_id']; 
	//$clientId = $_SESSION['client_id'];
	$getFormNameSQL = mysqli_query($conn, "select survey_name,unique_id from survey where id='" . $surveyId . "' ");
	$resFormName = mysqli_fetch_object($getFormNameSQL);
	$surveyFormName = $resFormName->survey_name;
	$surveyUniqueId = $resFormName->unique_id;
	$getQuestionnaire = mysqli_query($conn, "select question_json from questionnaires where survey_id='" . $surveyId . "' ");
	$resQuestionnaire = mysqli_fetch_object($getQuestionnaire);
	$jsondata = $resQuestionnaire->question_json;
	echo createExcelSheet($jsondata,$surveyFormName,$surveyUniqueId); //createExcelSheet function create in create-questionnaire.php

}
//PUBLISH WEB FORM KHUSHBOO
if (isset($_POST['process']) && $_POST['process'] == 'publishWebForm' && $_POST['surveyId'] != "") {
	require realpath(dirname(__FILE__)) . '/create-questionnaire.php';
	$surveyId = mysqli_real_escape_string($conn, $_POST['surveyId']);
	//$userId = $_SESSION['user_id']; 
	//$clientId = $_SESSION['client_id'];
	$getFormNameSQL = mysqli_query($conn, "select survey_name,unique_id from survey where id='" . $surveyId . "' ");
	$resFormName = mysqli_fetch_object($getFormNameSQL);
	$surveyFormName = $resFormName->survey_name;
	$surveyUniqueId = $resFormName->unique_id;
	$getQuestionnaire = mysqli_query($conn, "select question_json from questionnaires where survey_id='" . $surveyId . "' ");
	$resQuestionnaire = mysqli_fetch_object($getQuestionnaire);
	$jsondata = $resQuestionnaire->question_json;
	// echo $jsondata;
	echo createExcelSheet($jsondata,$surveyFormName,$surveyUniqueId); //createExcelSheet function create in create-questionnaire.php
	//echo "hello";
}
/// CHECK WEB FORM IS ALREADY EXIST

if (isset($_POST['surveyId']) && $_POST['surveyId'] != "" && $_POST['process'] == "WEBFORMEXIST") {

	function findData($array, $search)
	{
		$result = array();
		foreach ($array as $key => $value) {
			foreach ($search as $k => $v) {
				if (!isset($value[$k]) || $value[$k] != $v) {
					continue 2;
				}
			}
			$result[] = $key;
		}
		return $result;
	}



	$surveyId = mysqli_real_escape_string($conn, $_POST['surveyId']);
	$getQuestionnaire = mysqli_query($conn, "select `id`, `survey_id`, `client_id`, `user_id`, `question_json` from questionnaires where survey_id='" . $surveyId . "' ");
	$totRows = mysqli_num_rows($getQuestionnaire);
	if ($totRows > 0) {
		$resQuestionnaire = mysqli_fetch_object($getQuestionnaire);
		$questionJsonArr = json_decode($resQuestionnaire->question_json);
		$existingQuestions = '';
		$isChecked = ['' => '', 'yes' => 'checked', 'Yes' => 'checked'];
		$allChoices = json_decode(json_encode($questionJsonArr->choices), true);

		function getChoices($fieldName, $optionLangCode)
		{
			$search = array("list_name" => $fieldName);
			$choices = $GLOBALS['allChoices'];
			$findChoices = findData($choices, $search);
			$options = [];

			if (count($findChoices) > 0) {
				foreach ($findChoices as $choiceKey) {
					$choicArr['list_name'] = $choices[$choiceKey]['list_name'];
					$choicArr['value'] = $choices[$choiceKey]['value'];
					$choicArr['label'] = $choices[$choiceKey]['label'];
					$choicArr['choice_filter_parent'] = $choices[$choiceKey]['choice_filter_parent'];
					$choicArr['constraint'] = $choices[$choiceKey]['constraint'];
					$choicArr['media_file'] = $choices[$choiceKey]['media_file'];
					foreach ($optionLangCode as $codeVal) {
						if ($choices[$choiceKey][$codeVal] != '') {
							$choicArr[$codeVal] = $choices[$choiceKey][$codeVal];
						}
					}
					$options[] = $choicArr;
				}
			}
			return $options;
		}

		$group_ques = [];
		$repeated_group_ques = [];
		$question_groups = '';
		$repeated_question_groups = '';
		// echo "<pre>";
		// print_r($questionJsonArr->questions); die;
		foreach ($questionJsonArr->questions as $key => $questionObj) {


			$type = $questionObj->type;
			$name = $questionObj->name;
			$label = safe_var($conn, $questionObj->label);
			$dictionary_label = safe_var($conn, $questionObj->dictionary_label);
			$limit = $questionObj->limit;
			$constraint = $questionObj->constraint;
			$constraint_message = safe_var($conn, $questionObj->constraint_message);
			$hint = safe_var($conn, $questionObj->hint);
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
			$lookups = $questionObj->lookups;
			$media_files = $questionObj->media_file;
			$parameters = $questionObj->parameters;
			$questionObj->choice_relation;
			if (strpos($questionObj->relevant, '\"') !== false) {
				$relevant = html_entity_decode($questionObj->relevant, ENT_QUOTES, 'UTF-8');
				$editRelevant = str_replace('\"', '"', $relevant);
			} else {
				$editRelevant = html_entity_decode($questionObj->relevant, ENT_QUOTES, 'UTF-8');
			}
			$questionObj->relevant;
			$relevant_for_form = $questionObj->relevant_for_form;
			$hint =	htmlspecialchars(stripslashes($hint), ENT_QUOTES);
			$label = htmlspecialchars(stripslashes($label), ENT_QUOTES);
			$dictionary_label =	htmlspecialchars(stripslashes($dictionary_label), ENT_QUOTES);
			$constraint_message =	htmlspecialchars(stripslashes($constraint_message), ENT_QUOTES);
			$questionCode = array();
			$languagemaster = array();
			$langsql = mysqli_query($conn, "select language_code,language_name from languages where status = 0 and language_id not in(1)");
			$i = 0;

			$paradataArr = [];
			if ($paradata != '') {
				$paradataArr = explode(',', $paradata);
			}

			$checkedTimeStamp = '';
			$checkedGPS = '';
			$checkedKeyStroke = '';
			$checkedAudio = '';

			if (in_array('T', $paradataArr)) {
				$checkedTimeStamp = "checked";
			}
			if (in_array('G', $paradataArr)) {
				$checkedGPS = "checked";
			}
			if (in_array('K', $paradataArr)) {
				$checkedKeyStroke = "checked";
			}
			if (in_array('A', $paradataArr)) {
				$checkedAudio = "checked";
			}

			while ($result =	mysqli_fetch_object($langsql)) {

				$lanname = $result->language_name;
				$code = strtoupper($result->language_code);

				$langlabel = "label::" . $code;
				$langhint = "hint::" . $code;
				$langconstraint = "constraint_message::" . $code;
				if (array_key_exists($langlabel, $questionObj)) {
					$questionCode[$i]['lanname'] = $lanname;
					$questionCode[$i]['code'] = $code;
					$questionCode[$i]['label'] = $questionObj->$langlabel;
				}
				if (array_key_exists($langhint, $questionObj)) {
					$questionCode[$i]['hint'] = $questionObj->$langhint;
				}
				if (array_key_exists($langconstraint, $questionObj)) {
					$questionCode[$i]['constraint'] = $questionObj->$langconstraint;
				}
				$i++;
			}
			/// Multilingual
			$multilangHtml = '';
			$multilangHtmlOption = '';
			$countli = 1;
			// echo "<pre/>";
			// print_r($questionCode); 
			foreach ($questionCode as $key => $value) {

				// $disabled_multilang_two = '';
				// $disabled_multilang_two_option = '';
				// if($hint == ''){
				// $disabled_multilang_two = 'disabled';
				// $disabled_multilang_two_option = 'disabled';
				// }

				// $disabled_multilang_three = '';
				// $disabled_multilang_three_option = '';
				// if($constraint_message == ''){
				// $disabled_multilang_three = 'disabled';
				// $disabled_multilang_three_option = 'disabled';
				// }			
				$val_label = '';
				$val_hint = '';
				$val_contraint = '';
				if ($value['label'] != '') {
					$val_label = safe_var($conn, $value['label']);
				}
				if ($value['hint'] != '') {
					$val_hint = safe_var($conn, $value['hint']);
				}
				if ($value['constraint'] != '') {
					$val_contraint = safe_var($conn, $value['constraint']);
				}

				// $valcode = $value['code'];
				// $vallanname = $value['lanname'];

				/* $multilangHtml.="
						<li>
							<select name='languages[]' id='languageSelect' class='form-control languageSelect'>
								<option value='".$valcode."'>'.$vallanname.'</option>
								
							</select>
							<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*" value='$val_label'/>
							<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint*" value='$val_hint' '.$disabled_multilang_two.' />
							<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message*" value='$val_contraint' '.$disabled_multilang_three.' />
						<!--	<div class="del-relbtn">
								<i class="fa fa-trash"></i>
							</div>-->
						</li>"; */

				$multilangHtml .= '
						<li>
							<select name="languages[]" id="languageSelect" class="form-control languageSelect">
								<option value="' . $value['code'] . '">' . $value['lanname'] . '</option>
								
							</select>
							<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*" value="' . htmlspecialchars(stripslashes($val_label), ENT_QUOTES) . '"/>
							
							<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint*" value="' . htmlspecialchars(stripslashes($val_hint), ENT_QUOTES, 'UTF-8') . '" />
							<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message*" value="' . htmlspecialchars(stripslashes($val_contraint), ENT_QUOTES) . '" />
							<div class="del-relbtn">
								<i class="fa fa-trash"></i>
							</div>
						</li>';

				$multilangHtmlOption .= '
						<li>
							<select name="languages[]" id="languageSelectOne" class="form-control languageSelectOne">
								<option value="' . $value['code'] . '">' . $value['lanname'] . '</option>
								
							</select>
							<input type="hidden" class="form-control count_li" name="licount" value="' . $countli . '">
							<input type="text" class="form-control multi_language multi-one-option red-star-required"  name="lang-label[]" placeholder="Label*" value="' . htmlspecialchars(stripslashes($val_label), ENT_QUOTES) . '"/>
							<input type="text" class="form-control multi_language multi-two-option"  name="lang-hint[]" placeholder="Hint*" value="' . htmlspecialchars(stripslashes($val_hint), ENT_QUOTES) . '" />
							<input type="text" class="form-control multi_language multi-three-option" name="lang-constraint[]" placeholder="Constraint message*" value="' . htmlspecialchars(stripslashes($val_contraint), ENT_QUOTES) . '" />
							<div class="del-multioptionbtn">
								<i class="fa fa-trash"></i>
							</div>
						</li>';

				$countli++;
			}


			/// RELEVANTS
			$relvHtml = '';
			if (!empty($relevant_for_form)) {
				foreach ($relevant_for_form as $relevantForForm) {
					$relvHtml .= <<<RELV
						<li>
							<select name="relevant[]" class="form-control rel_qnames">
								<option value="$relevantForForm->qname">$relevantForForm->qname</option>
							</select>
							<select name="operator[]" class="form-control rel_operators">
								<option value="">Select Operator</option>
								<option value="$relevantForForm->operator" selected > $relevantForForm->operator </option>
							</select>
							<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt" value="$relevantForForm->relevant_value">
							<select name="condition1[]" class="form-control rel_andOr">
								<option value="">Select Condition</option>
								<option value="$relevantForForm->rel_and_or" selected >$relevantForForm->rel_and_or </option>
								<option value="&">& (AND)</option>
								<option value="|">| (OR)</option>
							</select>
							<div class="del-relbtn">
								<i class="fa fa-trash"></i>
							</div>
						</li>
					RELV;
				}
			}

			/// NUMBER TYPE QUESTIONS 
			if ($type === 'number') {
				$appearance_opt = getAppearance($conn, "number", $appearance);
				$dafault_response = trim($dafault_response, '{}');
				$existingQuestions = <<<SSS
						<div class="number-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
							<!-- <div class="close-form"><i class="fa-solid fa fa-trash"></i></div>-->
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Numeric Open Ended Question</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<input class="form-control ques" name="number-$key" type="hidden" id="number-$key" placeholder="Type: Number" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<input type="hidden" name="modified_ques" class="modified_ques" value="0">
										<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" disabled>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="number" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" data-limit="$limit" name="limit" value="$limit" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div>
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" oninput="validateDefaultResponse(this)">
										</div>
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-langfield mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

			/// DATE TYPE QUESTIONS 
			if ($type === 'date') {
				$appearance_opt = getAppearance($conn, "date", $appearance);
				$option1 = $option2 = $option3 = $option4 =$option5 = '';
				
			
				if ($dafault_response == 'today') {
					$option1 = 'selected';
				} elseif ($dafault_response == '>=6&<=10') {
					$option2 = 'selected';
				} elseif ($dafault_response == '>=today') {
					$option3 = 'selected';
				} elseif ($dafault_response == '>=today +10') {
					$option4 = 'selected';
				} elseif ($dafault_response == '<=today -10') {
					$option5 = 'selected';
				} 
				
				$existingQuestions = <<<SSS
						<div class="date-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
							<!-- <div class="close-form"><i class="fa-solid fa fa-trash"></i></div>-->
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Date</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<input class="form-control ques" name="date-$key" type="hidden" placeholder="Type: Date" id="date-$key" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
									<input type="hidden" name="modified_ques" class="modified_ques" value="0">
									<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" disabled>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="date" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" name="limit" data-limit="$limit" value="$limit" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div>
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											

											<select name="dafault_response" class="form-control mt-1 mb-3 q_dafault_response">
												<option value="">Select an option</option>
												<option value="today" $option1>today</option>
												<option value=">=6&<=10" $option2>>=6&<=10</option>
												<option value=">=today" $option3>>=today</option>
												<option value=">=today +10" $option4>>=today +10</option>
												<option value="<=today -10" $option5><=today -10</option>
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-langfield mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
													<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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


			/// DATE TYPE QUESTIONS 
			if ($type === 'note') {
				$existingQuestions = <<<SSS
						<div class="note-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
							<!-- <div class="close-form"><i class="fa-solid fa fa-trash"></i></div>-->
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Note</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<textarea class="form-control ques  d-none" name="note-$key" id="note-$key" placeholder="Type: Note" spellcheck="false" readonly"></textarea>
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<input type="hidden" name="modified_ques" class="modified_ques" value="0">
										<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" disabled>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="note" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
									<!--	<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" name="limit" data-limit="$limit" value="0" >
										</div>-->
										<input type="hidden" class="form-control mt-1 mb-3 q_limit" name="limit" data-limit="$limit" value="0" >
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div> 
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_appearance" name="appearance" value="$appearance">
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
															<!--<li class="d-none">
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
											<div class="add-langfield mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if (($type === 'text')) {
				// print_r($repeated_group_ques); die;
				$appearance_opt = getAppearance($conn, "text", $appearance);
				$dafault_response = trim($dafault_response, '{}');
				$existingQuestions = <<<SSS
						<div class="text-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
							<!-- <div class="close-form"><i class="fa-solid fa fa-trash"></i></div>-->
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Text Open Ended Question</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<input class="form-control ques" name="number-$key" type="hidden" id="number-$key" placeholder="Type: Text" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<input type="hidden" name="modified_ques" class="modified_ques" value="0">
										<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" disabled>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="text" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" data-limit="$limit" name="limit" value="$limit" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div>
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" oninput="validateDefaultResponse(this)">
										</div>
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-langfield mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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


			///AUDIO TYPE QUESTIONS
			if ($type === 'audio') {
				// $appearance_opt = getAppearance($conn,"audio",$appearance); 
				$existingQuestions = <<<SSS
						<div class="audio-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
							<!--	<div class="close-form"><i class="fa-solid fa fa-trash"></i></div> -->
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Audio</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<input class="form-control ques" name="audio-$key" type="hidden" id="audio-$key" placeholder="Type: Audio" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<input type="hidden" name="modified_ques" class="modified_ques" value="0">
										<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)" disabled>
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="audio" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" name="limit" data-limit="$limit" value="$limit" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div>
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-langfield mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div> 
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'video') {
				// $appearance_opt = getAppearance($conn,"video",$appearance); 
				$existingQuestions = <<<SSS
						<div class="video-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
								<!--<div class="close-form"><i class="fa-solid fa fa-trash"></i></div>-->
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Video</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<input class="form-control ques" name="video-$key" type="hidden" id="video-$key" placeholder="Type: Video" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<input type="hidden" name="modified_ques" class="modified_ques" value="0">
										<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)" disabled>
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="video" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" name="limit" data-limit="$limit" value="$limit" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div>
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-langfield mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'gps_button') {
				// $appearance_opt = getAppearance($conn,"gps_button",$appearance); 
				$existingQuestions = <<<SSS
						<div class="gps-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
							<!--	<div class="close-form"><i class="fa-solid fa fa-trash"></i></div> -->
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Location</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<input class="form-control ques" name="gps_button-$key" type="hidden" id="gps_button-$key" placeholder="Type: GPS-Button" readonly>
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<input type="hidden" name="modified_ques" class="modified_ques" value="0">
										<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)" disabled>
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="gps_button" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" name="limit" data-limit="$limit" value="$limit" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div>
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="multiinputs-div">
														<ul class="countli multi-lang-new-ul">
															<!--<li class="d-none">
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
											<div class="add-langfield mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'picture') {
				$appearance_opt = getAppearance($conn, "picture", $appearance);
				$existingQuestions = <<<SSS
						<div class="picture-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
								<!--<div class="close-form"><i class="fa-solid fa fa-trash"></i></div>-->
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Image</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<input class="form-control ques" name="picture-$key" type="hidden" id="picture-$key" placeholder="Type: Picture" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<input type="hidden" name="modified_ques" class="modified_ques" value="0">
										<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)" disabled>
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="picture" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" name="limit" data-limit="$limit" value="$limit" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div>
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>-->
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-langfield mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'select_one') {
				
				$fieldNameArr[] = $questionObj->name;
				$tempfieldNameArr = $fieldNameArr;
				$arrIndex = array_search($questionObj->name, $tempfieldNameArr);
				unset($tempfieldNameArr[$arrIndex]);
				
				$choicerelation = '';
				$choicerelation = '<option value="">Select an option</option>';
				if(!empty($tempfieldNameArr)){
					foreach($tempfieldNameArr as $value){
						$selected = '';
						if($questionObj->choice_filter == $value){
							$selected = "selected";
						}
						$choicerelation .='<option value="' .$value. '" ' .$selected. '>' .$value. '</option>';
					}
				}
				
				$appearance_opt = getAppearance($conn, "select_one", $appearance);
				$langsql = mysqli_query($conn, "select language_code,language_name from languages where status = 0");
				$optionLangCode = array();
				while ($result =	mysqli_fetch_object($langsql)) {

					$code = strtoupper($result->language_code);
					$qwerty = "label::" . $code;
					$optionLangCode[] = $qwerty;
				}
				$qoptions = getChoices($questionObj->choice_relation, $optionLangCode);
				$optdec = '';
				$opt = '';
				// echo "<pre/>";
				// print_r($qoptions);

				foreach ($qoptions as $qoption) {
					$qoptn = (object) $qoption;
					//print_r($qoptn); 
					$multioptionlabels = '';
					$valInc = 1;
					foreach ($optionLangCode as $codeVal) {

						$labelVal = $qoptn->$codeVal;

						$qoptn_label = '';
						if ($qoptn->label != '') {
							$removequotesstring = safe_var($conn, $qoptn->label);
							$qoptn_label = htmlspecialchars(stripslashes($removequotesstring), ENT_QUOTES);
						}
						if ($labelVal != '') {
							$labelVal = safe_var($conn, $labelVal);

							$multioptionlabels .= '<input type="text" class="form-control   option-language-label option-lang-val' . $valInc . '" name="option-lang-val[]" placeholder="' . $codeVal . '" value="' . htmlspecialchars(stripslashes($labelVal), ENT_QUOTES) . '" >';
							$valInc++;
						}
					}
					
					$choice_filter = '';
						if($qoptn->choice_filter_parent != '') {
							
							$choice_filter .= '<input type="text" class="form-control choice-filter-val" name="choice-filter-val[]" placeholder="'.$questionObj->choice_filter.' value" value="'.$qoptn->choice_filter_parent.'">';
						}

					$optdec .= <<<OPTDEC
							<li>
								<input type="radio" class="option-selected" checked="checked" class="form-checkbox">
								<input type="text" class="form-control nameOption" name="option-name[]" placeholder="Option Name" value='$qoptn_label'>
								<input type="text" class="form-control valueOption" name="option-value[]" placeholder="Option Value" value="$qoptn->value">
								<input type="text" class="form-control choice_constraint" name="choice-constraint[]" placeholder="Constraint" value="$qoptn->constraint">
								<input type="text" class="form-control choice_mediafile" name="choice-mediafile[]" placeholder="Media File" value="$qoptn->media_file">
								$multioptionlabels
								$choice_filter
							</li>
							OPTDEC;
					$opt .= <<<OPT
							<option value="$qoptn->value">$qoptn->label</option>
						OPT;
				}
				$existingQuestions = <<<SSS
						<div class="select-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
							<!-- <div class="close-form"><i class="fa-solid fa fa-trash"></i></div>-->
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
							</div>
							<!--  <div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> -->
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Choice Based Question</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								 <select class="form-control ques d-none" name="$name" id="select-$key" disabled>	
									<option class="select-placeholder" value="">Select One Option</option>
									$opt
								</select>
							<!--	<input type="text" class="form-control" placeholder="Type: Select One Option" readonly> -->
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<input type="hidden" name="modified_ques" class="modified_ques" value="0">
										<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" disabled>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="select_one" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
											<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" data-limit="$limit" name="limit" value="$limit" >
										</div>
										<div>
											<label class="form-check-label">
											Choice Relation<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Choice Relation" class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<input type="text" class="form-control mt-1 mb-3 q_choice_relation" name="q_choice_relation" value="$questionObj->choice_relation" >
										</div>
										
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div>
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">-->
											<select class="form-control mt-1  q_choice_filter" name="choice_filter">
												$choicerelation 
											</select>
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>												
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>
										<div>
											<input class="form-check-input mb-3 q_ismultiple " type="checkbox" value="yes" name="multiple-selects">
											<label class="form-check-label">
												Allow Multiple Selection
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Capture more than one response in the current field." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										</div>-->
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-langfield-option mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<div class="fw">
											<div>
												<label class="form-check-label">
													Options
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add Choices to the question" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-field mt-2" id="add-field-option" style="pointer-events: none">
												Add Options	<i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										 
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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




			if ($type === 'select_multiple') {
				$appearance_opt = getAppearance($conn, "select_multiple", $appearance);
				$langsql = mysqli_query($conn, "select language_code,language_name from languages where status = 0");
				$optionLangCode = array();
				while ($result =	mysqli_fetch_object($langsql)) {

					$code = strtoupper($result->language_code);
					$qwerty = "label::" . $code;
					$optionLangCode[] = $qwerty;
				}
				$qoptions = getChoices($questionObj->choice_relation, $optionLangCode);
				$optdec = '';
				$opt = '';
				// echo "<pre/>";
				// print_r($qoptions); die;

				foreach ($qoptions as $qoption) {
					$qoptn = (object) $qoption;
					// print_r($qoptn); die;
					$multioptionlabels = '';
					$valInc = 1;
					foreach ($optionLangCode as $codeVal) {

						$labelVal = $qoptn->$codeVal;
						$qoptn_label = '';
						if ($qoptn->label != '') {
							$removequotesstring = safe_var($conn, $qoptn->label);
							$qoptn_label = htmlspecialchars(stripslashes($removequotesstring), ENT_QUOTES);
						}
						if ($labelVal != '') {
							$labelVal = safe_var($conn, $labelVal);
							$multioptionlabels .= '<input type="text" class="form-control   option-language-label option-lang-val' . $valInc . '" name="option-lang-val[]" placeholder="' . $codeVal . '" value="' . htmlspecialchars(stripslashes($labelVal), ENT_QUOTES) . '" >';
							$valInc++;
						}
					}

					$optdec .= <<<OPTDEC
							<li>
								<input type="radio" class="option-selected" checked="checked" class="form-checkbox">
								<input type="text" class="form-control nameOption" name="option-name[]" placeholder="Option Name" value='$qoptn_label' >
								<input type="text" class="form-control valueOption" name="option-value[]" placeholder="Option Value" value="$qoptn->value" >
								<input type="text" class="form-control choice_constraint" name="choice-constraint[]" placeholder="Constraint" value="$qoptn->constraint">
								<input type="text" class="form-control choice_mediafile" name="choice-mediafile[]" placeholder="Media File" value="$qoptn->media_file">
								$multioptionlabels
							</li>
							OPTDEC;
					$opt .= <<<OPT
							<option value="$qoptn->value" disabled>$qoptn->label</option>
						OPT;
				}
				$existingQuestions = <<<SSS
						<div class="select-seaction">
						<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
							<div class="button">
							<!-- <div class="close-form"><i class="fa-solid fa fa-trash"></i></div>-->
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
							</div>
							<!--  <div class="label"><span class="label-name">$name: </span><span class="label-text">$label</span><span class="text-danger required_star">*</span></div> -->
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Choice Based Question</i></h6>
								<span class="label-name">[$name]:</span>
								<span class="label-text">[$label]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<select class="form-control ques d-none" name="$name" id="select-$key">	
									<option class="select-placeholder" value="">Select Multiple Option</option>
									$opt
								</select> 
							<!--	<input type="text" class="form-control" placeholder="Type: Select Multiple Option" readonly>-->
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
										<input type="hidden" name="modified_ques" class="modified_ques" value="0">
										<div class="w-100 d-flex  checkbox-align">
											<span>
												<input class="form-check-input   q_required" type="checkbox" name="required" value="yes" $isChecked[$required]  >
												<label class="form-check-label">
													Required 
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_read_only" type="checkbox" name="read_only" value="yes" $isChecked[$read_only] >
												<label class="form-check-label">
													Read Only
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_unique_id" type="checkbox" name="unique_id" value="yes" $isChecked[$unique_id] >
												<label class="form-check-label">
													Unique Id
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_preserve" type="checkbox" name="preserve" value="yes" $isChecked[$preserve] >
												<label class="form-check-label">
													Preserve
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_deidentify" type="checkbox" name="deidentify" value="yes" $isChecked[$deidentify] >
												<label class="form-check-label">
													Deidentify
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Response value of the field in the exported data will be replaced with ***" class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
											<span>
												<input class="form-check-input   q_ismultiple " type="checkbox" value="yes" name="multiple-selects" checked="checked">
												<label class="form-check-label">
													Allow Multiple Selection
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Capture more than one response in the current field." class="fa fa-info-circle tooltip-icon"></i>
												</label>
											</span>
										</div>
										<div>
											<label class="form-check-label">
												Name<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_name" name="name" value="$name" disabled>
											<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="select_multiple" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
											<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_label" name="label" value='$label'>
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dictionary_label" name="dictionary_label" value='$dictionary_label'>
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_hint" name="hint" value='$hint' >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="number" class="form-control mt-1 mb-3 q_limit" data-limit="$limit" name="limit" value="$limit" >
										</div>
										<div>
											<label class="form-check-label">
											Choice Relation<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Choice Relation" class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<input type="text" class="form-control mt-1 mb-3 q_choice_relation" name="q_choice_relation" value="$questionObj->choice_relation" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)" >
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_constraint_message" name="constraint_message" value='$constraint_message'>
										</div>
										<div>
											<label class="form-check-label mb-2">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
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
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select class="form-control mt-1 mb-3 q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>												
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1 mb-3 q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>
										<div>
											<input class="form-check-input mb-3 q_ismultiple " type="checkbox" value="yes" name="multiple-selects">
											<label class="form-check-label">
												Allow Multiple Selection
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Capture more than one response in the current field." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										</div>-->
										<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
										<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-langfield-option mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<div class="fw">
											<div>
												<label class="form-check-label">
													Options
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add Choices to the question" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-field mt-2" id="add-field-option" style="pointer-events: none">
												Add Options	<i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<div class="fw hideforfirst">
											<div>
												<label class="form-check-label">
													Relevant
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
												</label>
												<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'begin_group') {
				$checked_group = '';
				if ($appearance == 'onescreen') {
					$checked_group .= 'checked';
				}
				$group_ques[] = $name;
				$existingQuestions = '';
				$question_groups .= <<<GROUPS
					<fieldset class="dragto ui-sortable">
					<span class="message-container">
						<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
						<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
					</span>
					  <div class="groups-header">
						<h4 class="group-name">[$name]<span style="color:red">*</span></h4>
						<div class="button ques">
							<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
							
						</div>
						
						<div class="edit-main-container" style="display: none;">
							<div class="edit-wrapper-container">
								<div class="edit-group-section-new">
									<input type="hidden" name="modified_ques" class="modified_ques" value="0">
									<label class="form-check-label">
										Group Name<span style="color:red">*</span>
										<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input type="text" class="form-control mt-1 mb-3 q_name" value="$name" name="group-name" disabled>
									<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="begin_group">
								</div>
								<input class="form-check-input mb-3 fieldlist form-check-input-group-css" type="checkbox" name="fieldlist" value="yes" $checked_group>
								<label class="form-check-label">
									Onescreen
									<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
								</label>
								<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
									<!--	<div class="fw">
											<div>
												<label class="form-check-label">
													Multilingual Fields
													<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
											<div class="add-langfield mt-2">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>-->
								<div class="fw hideforfirst">
									<div>
										<label class="form-check-label">
											Relevant
											<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
										</label>
										<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
									</div>
								<!--<div class="row">
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
									<div class="clearfix"></div>-->
								</div>
							</div>
						</div>
			
			
					  </div>
					GROUPS;
			}

			// echo "<pre/>";
			// print_r($group_ques);
			if (count($group_ques) > 0) {
				$question_groups .= $existingQuestions;
				$existingQuestions = '';
			}

			if ($type === 'end_group') {
				$group_ques = [];
				$existingQuestions = '';
				$question_groups .= <<<GROUPS
					
					  
						<div class="d-none ui-draggable" style="">
							<div class="button ques">
							<!--<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>-->
								<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
							</div>
							
							<div>
								<div>
									<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="end_group">
								</div>
							</div>
						</div>
					
					</fieldset>
				GROUPS;


				$existingQuestions .= $question_groups;
				$question_groups = '';
			}
			//$alexistingQuestions.=$existingQuestions;

			///	begin_repeat GROUP SECTION

			if ($type === 'begin_repeat') {

				$checked_reptgrp = '';
				$checked_parameter = '';
				if ($appearance == 'onescreen') {
					$checked_reptgrp .= 'checked';
				}
				if ($parameters == 'multiroster') {
					$checked_parameter .= 'checked';
				}
				$repeated_group_ques[] = $name;
				$existingQuestions = '';
				$question_groups .= <<<GROUPS
					<fieldset class="dragto ui-sortable">
					<span class="message-container">
							<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
							<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
						</span>
					  <div class="groups-header">
						<h4 class="group-name">[$name]<span style="color:red">*</span></h4>
						<div class="button ques">
						<!--<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>-->
							<div class="rpedit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
						</div>
						
						<div class="edit-main-container" style="display: none;">
							<div class="edit-wrapper-container">
								<div class="edit-repgroup-section-new">
								<input type="hidden" name="modified_ques" class="modified_ques" value="0">
									<label class="form-check-label">
										Repeat Group Name<span style="color:red">*</span>
										<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input type="text" class="form-control mt-1 mb-3 q_name" value="$name" name="group-name" disabled>
									<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="begin_repeat">
								</div>
								<div>
									<label class="form-check-label">
										Repeat Count
										<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input type="text" class="form-control mt-1 mb-3 q_repeat_count" name="repeat_count" value="$questionObj->repeat_count">
								</div>
								<div>
									<input class="form-check-input mb-3 fieldlist" type="checkbox" name="fieldlist" value="yes" $checked_reptgrp>
									<label class="form-check-label">
										Onescreen
									<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
									</label>
									
									<input class="form-check-input mb-3 parameters" type="checkbox" name="parameters" value="multiroster" $checked_parameter>
									<label class="form-check-label">
										Parameter
										<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Repeat set of questions based on dependent response value" class="fa fa-info-circle tooltip-icon"></i>
									</label>
								</div>
								<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="$media_files">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="$lookups">
										</div>
							<!--	<div class="fw">
									<div>
										<label class="form-check-label">
											Multilingual Fields
											<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Add field in Multiple languages" class="fa fa-info-circle tooltip-icon"></i>
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
									<div class="add-langfield-option mt-2">
										Add Multilingual <i class="fa fa-plus"></i>
									</div>
									<div class="clearfix"></div>
								</div>-->
								<div class="fw hideforfirst">
									<div>
										<label class="form-check-label">
											Relevant
											<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls whether a question is displayed based on previous responses or conditions." class="fa fa-info-circle tooltip-icon"></i>
										</label>
										<input type="text" class="form-control mt-1 mb-3 manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
									</div>
								<!--<div class="row">
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
									<div class="clearfix"></div>-->
								</div>
							</div>
						</div>
			
			
					  </div>
					GROUPS;
			}

			if (count($repeated_group_ques) > 0 && count($group_ques) > 0) {
				$question_groups .= $existingQuestions;
				$existingQuestions = '';
			}
			if (count($repeated_group_ques) > 0 && count($group_ques) == 0) {
				$question_groups .= $existingQuestions;
				$existingQuestions = '';
			}

			if ($type === 'end_repeat') {
				$repeated_group_ques = [];
				$existingQuestions = '';
				$question_groups .= <<<GROUPS
					
					  
				
					  
						<div class="d-none ui-draggable" style="">
							<div class="button ques">
							<!--	<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>-->
								<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
							</div>
							
							<div>
								<div>
									<input type="hidden" class="form-control mt-1 mb-3 q_type" name="type" value="end_repeat">
								</div>
							</div>
						</div>
					
					</fieldset>
				GROUPS;


				$existingQuestions .= $question_groups;
				$question_groups = '';
			}



			$alexistingQuestions .= $existingQuestions;
			$existingQuestions = '';
		}
		echo $alexistingQuestions;
	} else {
		//echo "record not found.";
	}
}

?>