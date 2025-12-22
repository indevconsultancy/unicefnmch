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

// echo var_dump($_POST); 
if (isset($_POST['questions']) && $_POST['questions'] != "" && $_POST['surveyId'] != "" && isset($_POST['process']) && $_POST['process'] == "WEBFORM") {
	// $questions = $_POST['questions'];
	// echo "<pre/>";
	// print_r($questions); die;
	// echo "qwerty"; die;
	//require realpath(dirname(__FILE__)) . '/create-questionnaire.php';
	$questionnaireArr = [];
	// $choices = $_POST['choices'];
	$surveyId = mysqli_real_escape_string($conn, $_POST['surveyId']);
	$userId = $_SESSION['user_id'];
	$clientId = $_SESSION['client_id'];
	$questionnaireArr['questions'] = $_POST['questions'];
	$questionnaireArr['choices'] = $_POST['choices'];
	$jsondata = mysqli_real_escape_string($conn, json_encode($questionnaireArr));
	// print_r($questionnaireArr['choices']);die;
	$cdate = date('Y-m-d H:i:s');
	// echo $jsondata;
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
	$jsonn = json_decode($jsondata);
	if (!empty($jsonn->questions)) {
		echo createExcelSheet($jsondata,$surveyFormName,$surveyUniqueId); //createExcelSheet function create in create-questionnaire.php
	} else {
		$resArr = 0;
		echo $resArr;
	}
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
			// return $choices;

			if (count($findChoices) > 0) {
				foreach ($findChoices as $choiceKey) {
					$choicArr['list_name'] = $choices[$choiceKey]['list_name'];
					$choicArr['value'] = $choices[$choiceKey]['value'];
					$choicArr['label'] = $choices[$choiceKey]['label'];
					$choicArr['choice_filter_parent'] = $choices[$choiceKey]['choice_filter_parent'];
					$choicArr['constraint'] = $choices[$choiceKey]['constraint'];
					$choicArr['media_file'] = $choices[$choiceKey]['media_file'];
					foreach ($optionLangCode as $codeVal) {
						// if ($choices[$choiceKey][$codeVal] != '') {
						if (array_key_exists($codeVal, $choices[$choiceKey])) {
							$choicArr[$codeVal] = $choices[$choiceKey][$codeVal];
						}
					}
					$options[] = $choicArr;
				}
			}
			return $options;
		}

		function getLanguages($fieldName, $optionLangCode)
		{
			$allQuesKeys = [];
			foreach ($value as $key2 => $val) {
				$allQuesKeys[] = $key2;
			}
			foreach ($allQuesKeys as $allQuesKey) {
				$search = 'label::';
				if (preg_match("/{$search}/i", $allQuesKey)) {
					$lcode[] = str_replace("label::", "", $allQuesKey);
				}
			}
			$langCodes = "'" . implode("','", $lcode) . "'";
			$getLaguages = mysqli_query($conn, "SELECT language_id FROM languages WHERE status='0' and language_code in($langCodes) order by language_id asc");
			$language_masters = [];
			$language_masters = array('1');
			while ($getLangArr = mysqli_fetch_object($getLaguages)) {
				$language_masters[] = $getLangArr->language_id;
			}
		}

		$group_ques = [];
		$repeated_group_ques = [];
		$question_groups = '';
		$repeated_question_groups = '';
		// echo "<pre>";
		// print_r($questionJsonArr->questions);die;
		$fieldNameArr = [];
		$CurrFieldNameArr = [];
		foreach ($questionJsonArr->questions as $key => $questionObj) {

			$type = $questionObj->type;
			$name = $questionObj->name;
			// $label = $questionObj->label;
			$label = safe_var($conn, $questionObj->label);
			// $dictionary_label = $questionObj->dictionary_label;
			$dictionary_label = safe_var($conn, $questionObj->dictionary_label);
			$limit = $questionObj->limit;
			$constraint = $questionObj->constraint;
			// $constraint_message = $questionObj->constraint_message;
			$constraint_message = safe_var($conn, $questionObj->constraint_message);
			// $hint = $questionObj->hint;
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
			$langsql = mysqli_query($conn, "select language_code,language_name from languages where status = 0 and language_id not in(1) order by language_name asc");
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

			$existLanguageCode = array();
			while ($result =	mysqli_fetch_object($langsql)) {

				$lanname = $result->language_name;
				$code = strtoupper($result->language_code);
				$code2 = $result->language_code;

				$langlabel = "label::" . $code;
				$langhint = "hint::" . $code;
				$langconstraint = "constraint_message::" . $code;
				if (array_key_exists($langlabel, $questionObj)) {
					$existLanguageCode[] = $code2;
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

				/* $disabled_multilang_two = '';
				$disabled_multilang_two_option = '';
				if ($hint == '') {
					$disabled_multilang_two = 'disabled';
					$disabled_multilang_two_option = 'disabled';
				}

				$disabled_multilang_three = '';
				$disabled_multilang_three_option = '';
				if ($constraint_message == '') {
					$disabled_multilang_three = 'disabled';
					$disabled_multilang_three_option = 'disabled';
				} */


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

				$multilangHtml .= '
						<li>
							<select name="languages[]" id="languageSelect" class="form-control languageSelect">
								<option value="' . $value['code'] . '">' . $value['lanname'] . '</option>
								
							</select>
							<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*" value="' . htmlspecialchars(stripslashes($val_label), ENT_QUOTES) . '"/>
							<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint" value="' . htmlspecialchars(stripslashes($val_hint), ENT_QUOTES) . '" />
							<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message" value="' . htmlspecialchars(stripslashes($val_contraint), ENT_QUOTES) . '" />
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
							<input type="text" class="form-control multi_language multi-two-option"  name="lang-hint[]" placeholder="Hint" value="' . htmlspecialchars(stripslashes($val_hint), ENT_QUOTES) . '"  />
							<input type="text" class="form-control multi_language multi-three-option" name="lang-constraint[]" placeholder="Constraint message" value="' . htmlspecialchars(stripslashes($val_contraint), ENT_QUOTES) . '" />
							<div class="del-multioptionbtn">
								<i class="fa fa-trash"></i>
							</div>
						</li>';

				$countli++;
			}


			$relvHtml = '';
			if (!empty($relevant_for_form)) {
				foreach ($relevant_for_form as $relevantForForm) {

					$allOpr = '';
					$operators = ['<', '>', '<=', '>=', '=', '!='];
					foreach ($operators as $op) {
						if ($op !== $relevantForForm->operator) {
							$allOpr .= '<option value="' . $op . '">' . $op . '</option>';
						}
					}

					$allConditions = '';
					$conditonsArr = ['&', '|'];
					if ($relevantForForm->rel_and_or == '&') {
						$allConditions .= '<option value=' . $relevantForForm->rel_and_or . ' selected>& (AND)</option>';
					} else {
						if ($relevantForForm->rel_and_or !== '') {
							$allConditions .= '<option value=' . $relevantForForm->rel_and_or . ' selected>| (OR)</option>';
						}
					}
					foreach ($conditonsArr as $ca) {
						if ($ca !== $relevantForForm->rel_and_or) {
							if ($ca == '&') {
								$ca_value = "& (AND)";
							} else {
								$ca_value = "| (OR)";
							}
							$allConditions .= '<option value="' . $ca . '">' . $ca_value . '</option>';
						}
					}

					// $allConditions.='<option value="$relevantForForm->rel_and_or" selected>$relevantForForm->rel_and_or</option>';

					$relvHtml .= <<<RELV
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
			if ($type === 'number') {
				$appearance_opt = getAppearance($conn, "number", $appearance);
				$dafault_response = trim($dafault_response, '{}');
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
				$existingQuestions = <<<SSS
						<div class="number-seaction">
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
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Numeric Open Ended Question</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<input class="form-control ques" name="number-$key" type="hidden" id="number-$key" placeholder="Type: Number" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
											<input type="text" class="form-control mt-1   q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="number" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
									<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dafault_response" name="dafault_response" value="$dafault_response" oninput="validateDefaultResponse(this)">
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield">
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'date') {
				$appearance_opt = getAppearance($conn, "date", $appearance);
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
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
								<div class="edit-form">
									<button  type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Date</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<input class="form-control ques" name="date-$key" type="hidden" placeholder="Type: Date" id="date-$key" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
											<input type="text" class="form-control mt-1   q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="date" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
									<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<select name="dafault_response" class="form-control mt-1   q_dafault_response">
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield">
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'note') {
				//$appearance_opt = getAppearance($conn,"note",$appearance); 
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
				$existingQuestions = <<<SSS
						<div class="date-seaction">
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
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Note</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div> 
							<div class="input-seaction">
								<textarea class="form-control ques d-none" name="note-$key" id="note-$key" placeholder="Type: Note" spellcheck="false" readonly></textarea>
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
											<input type="text" class="form-control mt-1   q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="note" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
									<!--	<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										</div>-->
											<input type="hidden" class="form-control mt-1   q_limit" name="limit" value="0" onkeypress="return isNumberKey(event);">
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												
											</select>
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
									<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield">
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'text') {
				$appearance_opt = getAppearance($conn, "text", $appearance);
				$dafault_response = trim($dafault_response, '{}');
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
				$existingQuestions = <<<SSS
						<div class="number-seaction">
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
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Text Open Ended Question</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div>  
							<div class="input-seaction">
								<input class="form-control ques" name="number-$key" type="hidden" id="number-$key" placeholder="Type: Text" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
											<input type="text" class="form-control mt-1   q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="text" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dafault_response" name="dafault_response" value="$dafault_response" oninput="validateDefaultResponse(this)">
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield">
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'audio') {
				// $appearance_opt = getAppearance($conn,"audio",$appearance);
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
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
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Audio</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div>
							<div class="input-seaction">
								<input class="form-control ques" name="audio-$key" type="hidden" id="audio-$key" placeholder="Type: Audio" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
											<input type="text" class="form-control mt-1   q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="audio" >
										</div>
										<div class="d-block"
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												
											</select>
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield">
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
				//$appearance_opt = getAppearance($conn,"video",$appearance); 
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
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
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Video</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div>
							<div class="input-seaction">
								<input class="form-control ques" name="video-$key" type="hidden" id="video-$key" placeholder="Type: Video" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
											<input type="text" class="form-control mt-1   q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="video" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												
											</select>
										</div>-->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield">
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
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
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Location</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div>
							<div class="input-seaction">
								<input class="form-control ques" name="gps_button-$key" type="hidden" id="gps_button-$key" placeholder="Type: Location" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
											<input type="text" class="form-control mt-1   q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="gps_button" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												
											</select>
										</div> -->
										<input type="hidden" name="appearance" class="form-control q_appearance">
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
									<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield">
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
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
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Image</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div>
							<div class="input-seaction">
								<input class="form-control ques" name="picture-$key" type="hidden" id="picture-$key" placeholder="Type: Picture" readonly>
								
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
											<input type="text" class="form-control mt-1   q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="picture" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
										</div>
										<div>
											<label class="form-check-label">
												Constraint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>	
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield">
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
				$choiceRelationName = $questionObj->choice_relation;
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
				$langsql = mysqli_query($conn, "select language_code,language_name from languages where status = 0 and language_id not in(1) order by language_name asc");
				$optionLangCode = array();
				while ($result = mysqli_fetch_object($langsql)) {

					// $codeforoption[] = strtoupper($result->language_code);
					$code = strtoupper($result->language_code);
					$qwerty = "label::" . $code;
					$optionLangCode[] = $qwerty;
				}
				// print_r($optionLangCode); die;
				
				
				$qoptions = getChoices($choiceRelationName, $optionLangCode);
				$optdec = '';
				$opt = '';
				// echo "<pre/>";
				// print_r($qoptions); 
				if (empty($qoptions)) {
					$latestArr = array_unique($existLanguageCode);
					// print_r($latestArr); die;
					$totLanguage = count($latestArr);
					$multioptionlabels = '';
					$valInc = 1;
					foreach ($existLanguageCode as $val) {
						$multioptionlabels .= '<input type="text" class="form-control   option-language-label option-lang-val' . $valInc . '" name="option-lang-val[]" placeholder="Label ' . $val . '*" value="" >';
					}
					$valInc++;

					$optdec .= <<<OPTDEC
								<li>
									<input type="radio" class="option-selected" checked="checked" class="form-checkbox">
									<input type="text" class="form-control nameOption" name="option-name[]" placeholder="Option Name" value='' >
									<input type="text" class="form-control valueOption" name="option-value[]" placeholder="Value" value="" >
									<input type="text" class="form-control choice_constraint" name="choice-constraint[]" placeholder="Constraint" value="">
									<input type="text" class="form-control choice_mediafile" name="choice-mediafile[]" placeholder="Media File" value="">
									$multioptionlabels
									<div class="del-btn">
										<i class="fa fa-trash"></i>
									</div>
								</li>
								OPTDEC;
				} else {
					// print_r($qoptions);
					foreach ($qoptions as $qoption) {
						$qoptn = (object) $qoption;
						// echo "<pre/>";
						// print_r($qoptn); die;
						$multioptionlabels = '';
						$valInc = 1;
						foreach ($optionLangCode as $codeVal) {
							$existsLabel = property_exists($qoptn, $codeVal);
							$labelVal = '';
							if ($existsLabel) {
								// echo "check ";
								$labelVal = $qoptn->$codeVal ? $qoptn->$codeVal : ' ';
							}
							// echo $labelVal; 

							$qoptn_label = '';
							if ($qoptn->label != '') {
								$removequotesstring = safe_var($conn, $qoptn->label);
								$qoptn_label = htmlspecialchars(stripslashes($removequotesstring), ENT_QUOTES);
							}

							if ($labelVal != '') {
								$labelVal = safe_var($conn, $labelVal);
								$multioptionlabels .= '<input type="text" class="form-control   option-language-label option-lang-val' . $valInc . '" name="option-lang-val[]" placeholder="' . $codeVal . '*" value="' . htmlspecialchars(stripslashes($labelVal), ENT_QUOTES) . '" >';
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
									<input type="text" class="form-control nameOption" name="option-name[]" placeholder="Option Name" value='$qoptn_label' >
									<input type="text" class="form-control valueOption" name="option-value[]" placeholder="Value" value="$qoptn->value" >
									<input type="text" class="form-control choice_constraint" name="choice-constraint[]" placeholder="Constraint" value="$qoptn->constraint">
									<input type="text" class="form-control choice_mediafile" name="choice-mediafile[]" placeholder="Media File" value="$qoptn->media_file">
									$multioptionlabels
									$choice_filter
									<div class="del-btn">
										<i class="fa fa-trash"></i>
									</div>
								</li>
								OPTDEC;
						$opt .= <<<OPT
								<option value="$qoptn->value" disabled>$qoptn->label</option>
							OPT;
					}
				}
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
				$existingQuestions = <<<SSS
						<div class="number-seaction">
						<span class="message-container">
									<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
									<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
								</span>
							<div class="button"> 
								<div class="edit-form">
									<button type="button" class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Question">
										<i class="fa-solid fa fa-pencil-square-o fs-6 text-white"></i>
									</button>
								</div>
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Choice Based Question</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div>
							<div class="input-seaction">
								<select class="form-control ques d-none" name="$name" id="select-$key" disabled>	
									<option class="select-placeholder" value="">Type: Select One</option>
									$opt
								</select>
							<!--	<input type="text" class="form-control" placeholder="Type: Select One Option" readonly>-->
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
												<i data-data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
												
											</label>
											<input type="text" class="form-control mt-1 q_name select_qname" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="select_one" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
											<i data-bs-html="true" data-bs-toggle="tooltip" data-data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
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
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter"> -->
											<select class="form-control mt-1  q_choice_filter" name="choice_filter">
												$choicerelation
											</select>
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>												
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
								<!--		<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
										</div>
										<div>
											<input class="form-check-input   q_ismultiple " type="checkbox" value="yes" name="multiple-selects">
											<label class="form-check-label">
												Allow Multiple Selection
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Check this to allow select multiple options." class="fa fa-info-circle tooltip-icon"></i>
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield-option">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<div class="fw">
											<div>
												<label class="form-check-label">
													Options<span style="color:red">*</span>
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
											<div class="add-field" id="add-field-option" >
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
			if ($type === 'select_multiple') {
				$choiceRelationName = $questionObj->choice_relation;
				$appearance_opt = getAppearance($conn, "select_multiple", $appearance);
				$langsql = mysqli_query($conn, "select language_code,language_name from languages where status = 0 and language_id not in(1) order by language_name asc");
				$optionLangCode = array();
				while ($result = mysqli_fetch_object($langsql)) {

					$code = strtoupper($result->language_code);
					$qwerty = "label::" . $code;
					$optionLangCode[] = $qwerty;
				}
				$qoptions = getChoices($choiceRelationName, $optionLangCode);
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
							$multioptionlabels .= '<input type="text" class="form-control   option-language-label option-lang-val' . $valInc . '" name="option-lang-val[]" placeholder="Label ' . $codeVal . '*" value="' . htmlspecialchars(stripslashes($labelVal), ENT_QUOTES) . '" >';
							$valInc++;
						}
					}

					$optdec .= <<<OPTDEC
							<li>
								<input type="radio" class="option-selected" checked="checked" class="form-checkbox">
								<input type="text" class="form-control nameOption" name="option-name[]" placeholder="Option Name" value='$qoptn_label' >
								<input type="text" class="form-control valueOption" name="option-value[]" placeholder="Value" value="$qoptn->value" >
								<input type="text" class="form-control choice_constraint" name="choice-constraint[]" placeholder="Constraint" value="$qoptn->constraint">
								<input type="text" class="form-control choice_mediafile" name="choice-mediafile[]" placeholder="Media File" value="$qoptn->media_file">
								$multioptionlabels
								<div class="del-btn">
									<i class="fa fa-trash"></i>
								</div>
							</li>
							OPTDEC;
					$opt .= <<<OPT
							<option value="$qoptn->value" disabled>$qoptn->label</option>
						OPT;
				}
				$nameDesign = $name ? $name : 'Field Name';
				$labelDesign = $label ? $label : 'Label';
				$existingQuestions = <<<SSS
						<div class="number-seaction">
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
								<div class="close-form">
									<button class="btn btn-sm p-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Delete Question">
										<i class="fa-solid fa fa-trash fs-6 text-white"></i>
									</button>
								</div> 
							</div>
							<div class="label text-start">
								<h6 style="font-size: 14px"><i>Choice Based Question</i></h6>
								<span class="label-name">[$nameDesign]:</span>
								<span class="label-text">[$labelDesign]</span>
								<span class="text-danger required_star">*</span>
							</div>
							<div class="input-seaction">
								<select class="form-control ques d-none" name="$name" id="select-$key" disabled>	
									<option class="select-placeholder" value="">Type: Select Multiple</option>
									$opt
								</select>
							<!--	<input type="text" class="form-control" placeholder="Type: Select Multiple Option" readonly>-->
								
								<div class="edit-main-container" style="display: none;">
									<div class="edit-wrapper-container">
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
												<i data-data-title="An identifier for each question or field in the survey form." class="fa fa-info-circle tooltip-icon"></i>
												
											</label>
											<input type="text" class="form-control mt-1   q_name" name="name" value="$name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
											<p id="errorMessage" class="error-message" style="color: red"></p>
											<input type="hidden" class="form-control mt-1   q_type" name="type" value="select_multiple" >
										</div>
										<div class="d-block">
											<label class="form-check-label">
												Label<span style="color:red">*</span>
											<i data-bs-html="true" data-bs-toggle="tooltip" data-data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_label" name="label" value="$label">
										</div>
									<!--	<div class="d-block">
											<label class="form-check-label">
												Short Label
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dictionary_label" name="dictionary_label" value="$dictionary_label">
										</div>-->
										<div>
											<label class="form-check-label">
												Hint
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_hint" name="hint" value="$hint" >
										</div>
										<div>
											<label class="form-check-label">
												Limit<span style="color:red">*</span>
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_limit" name="limit" value="$limit" onkeypress="return isNumberKey(event);">
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
											<input type="text" class="form-control mt-1   q_constraint" name="constraint" value="$constraint" onkeydown="blockBackspaceKey(event)">
										</div>
										<div>
											<label class="form-check-label">
												Constraint Message
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_constraint_message" name="constraint_message" value="$constraint_message">
										</div>
										<div>
											<label class="form-check-label">
												Paradata
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
											</label>
										<!--	<input type="text" class="form-control mt-1   q_paradata" name="paradata" value="T,G,K" value="$paradata">-->
											<div>
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="T" $checkedTimeStamp>
												<label class="form-check-label">
													Timestamp
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="G" $checkedGPS>
												<label class="form-check-label">
													GPS
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="K" $checkedKeyStroke>
												<label class="form-check-label">
													Key-Stroke
												</label>
												
												<input class="form-check-input   q_paradata" type="checkbox" name="paradata" value="A" $checkedAudio> 
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
											<select class="form-control mt-1   q_appearance" name="appearance">
												$appearance_opt
											</select>
										</div>
										<div>
											<label class="form-check-label">
												Choice Filter
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_choice_filter" name="choice_filter" value="$questionObj->choice_filter">
										</div>
										<!--	<div>
											<label class="form-check-label">
												Repeat Count
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count">
										</div>-->
										
										<input type="hidden" class="q_repeat_count" name="repeat_count">
										<div>
											<label class="form-check-label">
												Calculation
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>												
											</label>
											<input type="text" class="form-control mt-1   q_calculation" name="calculation" value="$calculation" onkeydown="blockBackspaceKey(event)">
										</div>
										<input type="hidden" class="q_dafault_response" name="dafault_response" value="$dafault_response">
									<!--	<div>
											<label class="form-check-label">
												Default Response
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1   q_dafault_response" name="dafault_response" value="$dafault_response" disabled>
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield-option">
												Add Multilingual <i class="fa fa-plus"></i>
											</div>
											<div class="clearfix"></div>
										</div>
										
										<div class="fw">
											<div>
												<label class="form-check-label">
													Options<span style="color:red">*</span>
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
											<div class="add-field" id="add-field-option" >
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
												<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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
				$nameDesign = $name ? $name : 'Field Name';

				$existingQuestions = '';
				$question_groups .= <<<GROUPS
					<fieldset class="dragto ui-sortable">
					<span class="message-container">
						<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
						<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
					</span>
					  <div class="groups-header">
						<h6 style="font-size: 14px" class="mt-1 mb-0"><i>Create Group</i></h6>
						<h4 class="group-name">[$nameDesign]<span style="color:red">*</span></h4>
						<div class="button ques">
							<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
							<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
							
						</div>
						
						<div class="edit-main-container" style="display: none;">
							<div class="edit-wrapper-container">
								<div>
									<label class="form-check-label">
										Group Name<span style="color:red">*</span>
										<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input type="text" class="form-control mt-1   q_name" value="$name" name="group-name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
									<p id="errorMessage" class="error-message" style="color: red"></p>
									<input type="hidden" class="form-control mt-1   q_type" name="type" value="begin_group">
								</div> 
								<div>
								<input class="form-check-input   fieldlist" type="checkbox" name="fieldlist" value="yes" $checked_group>
								<label class="form-check-label">
									Onescreen
									<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show group of questions on same screen." class="fa fa-info-circle tooltip-icon"></i>
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
												<label class="form-check-label fw-bold">
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
											<div class="add-langfield">
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
										<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

			if (count($group_ques) > 0) {
				$question_groups .= $existingQuestions;
				$existingQuestions = '';
			}


			if ($type === 'end_group') {
				// array_pop($group_ques);
				$group_ques = [];
				$existingQuestions = '';
				$question_groups .= <<<GROUPS
					
					  
						<div class="add-fields add-fields-new">
						 <h5>Add Types: </h5>
						  <div>
							<a href="javascript:void(0);" class="select-single"><i class="fa fa-chevron-circle-down"></i> Choice Based Question </a>
										<a href="javascript:void(0);" class="number"><i class="fa fa-hashtag"></i> Numeric Open Ended Question </a>

										<a href="javascript:void(0);" class="text"><i class="fa fa-file-text"></i> Text Open Ended Question </a>
										<a href="javascript:void(0);" class="note"><i class="fa fa-sticky-note"></i> Note </a>
										<a href="javascript:void(0);" class="date"><i class="fa fa-calendar"></i> Date </a>
										<a href="javascript:void(0);" class="audio"><i class="fa fa-file-audio-o"></i> Audio </a>
										<a href="javascript:void(0);" class="video"><i class="fa fa-file-video-o"></i> Video </a>
										<a href="javascript:void(0);" class="picture"><i class="fa fa-picture-o"></i> Image </a>
										<a href="javascript:void(0);" class="gps_button"><i class="fa fa-map-marker"></i> Location</a>
										<a href="javascript:void(0);" class="b-group"><i class="fa fa-object-group"></i> Create Group </a>
										<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-object-group"></i>Create Roster </a>
						  </div>
						</div>
					  
						<div class="d-none ui-draggable" style="">
							<div class="button ques">
								<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
								<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
							</div>
							
							<div>
								<div>
									<input type="hidden" class="form-control mt-1   q_type" name="type" value="end_group">
								</div>
							</div>
						</div>
					
					</fieldset>
				GROUPS;


				$existingQuestions .= $question_groups;
				$question_groups = '';
			}
			// $alexistingQuestions.=$existingQuestions;



			//// 
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
				$nameDesign = $name ? $name : 'Field Name';
				$existingQuestions = '';
				$question_groups .= <<<GROUPS
					<fieldset class="dragto ui-sortable">
					<span class="message-container">
						<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
						<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
					</span>
					  <div class="groups-header">
					  <h6 style="font-size: 14px" class="mt-1 mb-0"><i>Create Repeat</i></h6>
						<h4 class="group-name">[$nameDesign]<span style="color:red">*</span></h4>
						<div class="button ques">
							<div class="rpedit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
							<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
						</div>
						
						<div class="edit-main-container" style="display: none;">
							<div class="edit-wrapper-container">
								<div>
									<label class="form-check-label">
										Repeat Group Name<span style="color:red">*</span>
										<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input type="text" class="form-control mt-1   q_name" value="$name" name="group-name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
									<p id="errorMessage" class="error-message" style="color: red"></p>
									<input type="hidden" class="form-control mt-1   q_type" name="type" value="begin_repeat">
								</div>
								<div>
									<label class="form-check-label">
										Repeat Count
										<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input type="text" class="form-control mt-1   q_repeat_count" name="repeat_count" value="$questionObj->repeat_count">
								</div>
								<div>
									<input class="form-check-input   fieldlist" type="checkbox" name="fieldlist" value="yes" $checked_reptgrp>
									<label class="form-check-label">
										Onescreen
										<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show group of questions on same screen." class="fa fa-info-circle tooltip-icon"></i>
									</label>
									<input class="form-check-input   parameters" type="checkbox" name="parameters" value="multiroster" $checked_parameter>
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
										<label class="form-check-label fw-bold">
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
									<div class="add-langfield-option">
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
										<input type="text" class="form-control mt-1   manual_relevant_txt" name="manual_relevant_txt" value='$editRelevant' placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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



			if (count($repeated_group_ques) > 0 && count($group_ques) > 0) {
				$question_groups .= $existingQuestions;
				$existingQuestions = '';
			}
			if (count($repeated_group_ques) > 0 && count($group_ques) == 0) {
				$question_groups .= $existingQuestions;
				$existingQuestions = '';
			}

			if ($type === 'end_repeat') {
				// array_pop($repeated_group_ques);
				$repeated_group_ques = [];
				$existingQuestions = '';
				$question_groups .= <<<GROUPS
					
					  
						<div class="add-fields add-fields-new">
						 <h5>Add Types: </h5>
						  <div>
							<a href="javascript:void(0);" class="select-single"><i class="fa fa-chevron-circle-down"></i> Choice Based Question </a>
										<a href="javascript:void(0);" class="number"><i class="fa fa-hashtag"></i> Numeric Open Ended Question </a>

										<a href="javascript:void(0);" class="text"><i class="fa fa-file-text"></i> Text Open Ended Question </a>
										<a href="javascript:void(0);" class="note"><i class="fa fa-sticky-note"></i> Note </a>
										<a href="javascript:void(0);" class="date"><i class="fa fa-calendar"></i> Date </a>
										<a href="javascript:void(0);" class="audio"><i class="fa fa-file-audio-o"></i> Audio </a>
										<a href="javascript:void(0);" class="video"><i class="fa fa-file-video-o"></i> Video </a>
										<a href="javascript:void(0);" class="picture"><i class="fa fa-picture-o"></i> Image </a>
										<a href="javascript:void(0);" class="gps_button"><i class="fa fa-map-marker"></i> Location</a>
										<a href="javascript:void(0);" class="b-group"><i class="fa fa-object-group"></i> Create Group </a>
										<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-object-group"></i>Create Roster </a>
						  </div>
						</div>
					  
						<div class="d-none ui-draggable" style="">
							<div class="button ques">
								<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
								<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
							</div>
							
							<div>
								<div>
									<input type="hidden" class="form-control mt-1   q_type" name="type" value="end_repeat">
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