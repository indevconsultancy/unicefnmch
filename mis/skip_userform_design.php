<?php include('includes/config_new.php'); ?>
<!DOCTYPE html>
<html>

<head>
	<title>Web Form | MQUAD</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="shortcut icon" href="img/mquad-logo.png">
	<link href="css/bootstrapV5-3.min.css" rel="stylesheet">
	<link href="css/elegant-icons-style.css" rel="stylesheet" />
	<link href="css/font-awesome.min.css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet">
	<link href="css/webform.css" rel="stylesheet">
	<link href="css/style-responsive.css" rel="stylesheet" />
	<script src="js/jquery.js"></script>
	<link href="css/select2.min.css" rel="stylesheet" />
	<link href="css/select2-bootstrap.min.css" rel="stylesheet" />
	<link href="<?= base_url(); ?>css/select2.min.css" rel="stylesheet" />
	<link href="<?= base_url(); ?>css/datepicker.min.css" rel="stylesheet">

	<div id="pre-load" class="loading-indicator">
		<div id="loader" class="loader">
			<div class="loader-container">
				<div class='loader-icon'><img src="https://unicef.indevconsultancy.in/mis/img/mquad-logo.png" alt=""></div>
			</div>
		</div>
	</div>
</head>

<style>
	.chide {
		display: none;
	}

	.copied {
		font-family: 'Montserrat', sans-serif;
		width: 75px;
		margin-top: 30px;
		display: none;
		position: fixed;
		color: #fff;
		padding: 5px 10px;
		background-color: #00000082;
		border-radius: 2px;
		box-shadow: -2px 0px 5px 0px #a6abb1;
		-moz-box-shadow: -2px 0px 5px 0px #a6abb1;
		-webkit-box-shadow: -2px 0px 5px 0px #a6abb1;
	}
    ol, ul {
    padding-left: 0rem!important;
	}	
	.question-title {
		font-weight: 400 !important;
	}
	#msform fieldset:nth-of-type(1) .previous {
		display: none;
	 }
</style>

<body>
	<?php
	session_start();    
	$sessionID= session_id();
	$survey_id = base64_decode($_REQUEST['survey_id']);
	$langId = base64_decode($_REQUEST['langId']);
	//$questionPath = "https://unicef.indevconsultancy.in/mis/api/questions_v3.php?survey_id=".$survey_id; //"questions.js"; // JSON API PATH
	$questionPath = "https://unicef.indevconsultancy.in/mis/api/questionsjson/s" . $survey_id . ".json";
	$questionJsondata = file_get_contents($questionPath);
	$data = json_decode($questionJsondata); // Convert to array

	$languages = $data->language; //Array
	$languagegroups = $data->language_group;

	$groupsQuestions = (array)$data->groups;

	$survey_name = getone($conn, "survey", "survey_name", "id", $survey_id);
	
	
	if (!isset($_COOKIE['cookie_id'])) {
    // Generate a new UUID if not set
		$cookieId = date('dmYhis').time();
		setcookie('cookie_id', $cookieId, time() + (86400 * 30), "/"); // 30-day cookie
	} else {
		// Use the existing UUID
		$cookieId = $_COOKIE['cookie_id'];
	}

	$cookieId;
	

	?>
	<?php
	$isDisabled = "";
	if (isset($langId) && $langId > 0) {
		$languageid = $langId;
		$isDisabled = "disabled";
	}
	function getUserName($default_response)
	{
		if ($default_response == '#name') {
			if (isset($_SESSION['name'])) {
				return $_SESSION['name'];
			} else {
				return "";
			}
		} elseif ($default_response == '#username') {
			if (isset($_SESSION['username'])) {
				return $_SESSION['username'];
			} else {
				return "";
			}
		} else {
			return "";
		}
	}

	function splitValues($input_string)
	{
		// Regular expression to match numbers and split by operators
		preg_match_all('/(\d+)|([<>=!&|]+)/', $input_string, $matches);

		// Extract values and filter out empty matches
		$values = array_filter(array_map('trim', $matches[0]), function ($item) {
			return is_numeric($item);
		});

		// Convert to integers
		//$values = array_map('intval', $values);

		return $values;
	}

	?>
	<?php date_default_timezone_set('Asia/Kolkata');
	$month = date('m');
	$monthName = date('F');
	$day = date('d');
	$dayOfWeek = date('w');
	$year = date('Y');
	$nowtime = date("H:i:s", time());
	$nowdate = $year . '-' . $month . '-' . $day;
	$nowdatetime = $year . '-' . $month . '-' . $day . ' ' . $nowtime;

	$now_month_year = date("Y-m", time());
	$nowdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	$months = [];
	for ($month = 1; $month <= 12; $month++) {
		$month_name = date("F", mktime(0, 0, 0, $month, 1));
		$months[] = $month_name;
	}

	?>

	<div class="container">
		<div class="custom-logo">
			<img src="<?= base_url() ?>/img/mquad-logo.png" alt="sss" style="height: 60px;">
			<h3 class="question-title"> <?= $survey_name; ?></h3>
			<div class=" ">
				<?php if (isset($_SESSION['user_id'])) { ?>
					<a href="javascript:void(0)" onclick="copyToClipboard('<?= base64_encode($survey_id) ?>')" class="btn btn-primary py-1 px-2 rounded-0 tooltips" data-bs-placement="top" data-bs-toggle="tooltip" data-bs-html="true" title="Copy API link to data">
						<p id="<?= base64_encode($survey_id) ?>" class="chide">https://unicef.indevconsultancy.in/mis/ss_skip.php?survey_id=<?= base64_encode($survey_id) ?></p>
						<i class="fa fa-copy cstyle"></i> Copy Link
					</a>
					<div id="copied-success" class="copied">
						<span>Copied!</span>
					</div>
					<!--<a href="survey-list.php" class="btn btn-primary">Back</a>-->
				<?php } ?>

			</div>
		</div>
		<?php 
		
		// echo session_id();
		?>
		<hr style="border-color: #8080804d;margin-top: 10px;margin-bottom: 15px;">

		<div class="start-survey">
			<!--<select name="langId" id="langId" class="form-select mb-0" <?php echo $isDisabled; ?>>
				<option value="">Select Language</option>
				<?php
				/* foreach ($languages as $language) {
					$encodedLangId = base64_encode($language->language_id); ?>
					<option value="<?= $encodedLangId; ?>" <?php if (isset($_REQUEST['langId']) && base64_decode($_REQUEST['langId']) == $language->language_id) {
																echo "selected";
															} ?>><?= $language->language_name; ?></option>
				<?php
				} */
				?>
			</select>
			<a href="javascript:void(0);" id="startinterview" class="btn btn-primary" style="margin-left:10px;" <?php echo $isDisabled; ?>>Start</a>
			-->
			<input type="hidden" name="GPS_latitude_start" id="GPS_latitude_start" />
			<input type="hidden" name="GPS_longitude_start" id="GPS_longitude_start" />
			<input type="hidden" class="form-control" id="start_date_time"/>
			<input type="hidden" class="form-control" id="end_date_time"/>
			<!--<input type="text" class="form-control" id="os_version" value=""/>
			<input type="text" class="form-control" id="device_name" value=""/>
			<input type="text" class="form-control" id="device_id" value=""/>
			
			<input type="text" class="form-control" id="date_time" value="<?= $nowdatetime ?>"/>-->

		</div>
		<div class="survey-form">
			<div class="row">

				<div class="col-md-12">
					<form id="msform">
						<?php if (empty($langId)) { ?>
							<div class="thankyou-page">
								<fieldset>
									<div class="_header">
										<h3>Please select the language to begin the interview. </h3>
										<strong>
											<h4 style="font-size:17px !important;">Once the interview has started, you will not be able to change the language.</h4>
										</strong>
										<div class="row mt-2">
											<div class="col-md-10">
												<select name="langId" id="langId" class="form-select mb-0" <?php echo $isDisabled; ?>>
													<option value="">Select Language</option>
													<?php
													foreach ($languages as $language) {
														$encodedLangId = base64_encode($language->language_id); ?>
														<option value="<?= $encodedLangId; ?>" <?php if (isset($_REQUEST['langId']) && base64_decode($_REQUEST['langId']) == $language->language_id) {
																									echo "selected";
																								} ?>><?= $language->language_name; ?></option>
													<?php
													}
													?>
												</select>
											</div>
											<div class="col-md-2">
												<a href="javascript:void(0);" id="startinterview" class="form-control btn btn-primary py-1 px-2 rounded-0" <?php echo $isDisabled; ?>>Start</a>
											</div>
										</div>
									</div>
								</fieldset>
							</div>
						<?php } ?>

						<h3 class="error_message"></h3>
						<h3 class="error_messaged"></h3>
						<?php
						///////////////Group Details///////////////////////

						$getGroupsscreen = mysqli_query($conn, "SELECT id, group_name FROM questions_group WHERE survey_id='" . $survey_id . "' AND group_type='screen'");
						$allGroupsscreen = mysqli_fetch_all($getGroupsscreen, MYSQLI_ASSOC);
						$scrn = $scrnid = [];
						foreach ($allGroupsscreen as $allGroupsscreen1) {
							$gname = $allGroupsscreen1['group_name'];

							$getFieldnames = mysqli_query($conn, "SELECT field_name FROM questions_language WHERE language_id='1' AND question_name!='' AND survey_id='" . $survey_id . "' AND question_id < (SELECT question_id FROM questions_language WHERE language_id='1' AND field_name='" . $gname . "' AND survey_id='" . $survey_id . "') ORDER BY question_id DESC limit 1;");
							$fnames = mysqli_fetch_object($getFieldnames);
							$scrn[$fnames->field_name] = $gname;
						}

						///////////////Group Details///////////////////////

						$surveyPartial = "SELECT full_json FROM `partial_survey_data` WHERE survey_status='0' AND session_id='" . $cookieId . "' AND survey_name_id='" . $survey_id . "' order by partial_survey_data_id desc limit 0,1";
						$qryPartial = mysqli_query($conn, $surveyPartial);

						if ($qryPartial) {
							$dataPartial = mysqli_fetch_object($qryPartial);
							$full_json_data = json_decode($dataPartial->full_json);
                            //echo "<pre>";
							//print_r($full_json_data)
							$field_name = [];
							$option_value = [];
							if ($full_json_data) {
								$surveyData = $full_json_data->survey_data;
								// echo "<pre>";
								 //print_r($surveyData);
								foreach ($surveyData as $surveydata1) {
									$field_name[] = $surveydata1->field_name;
									$option_id = $surveydata1->option_id;
									$option_values = $surveydata1->option_value;

									if ($option_id != '') {
										$option_value[] = $option_id;
									} else {
										$option_value[] = $option_values;
									}
									//echo "hello";
									if (array_key_exists($surveydata1->field_name, $scrn)) {
										 $groupName = $scrn[$surveydata1->field_name];
										if (isset($full_json_data->$groupName)) {
											$group_data = $full_json_data->$groupName;
											//print_r($group_data);
											foreach ($group_data[0] as $groupdata) {
												$field_name[] = $groupdata->field_name;
												if ($groupdata->option_id != '') {
													$option_value[] = $groupdata->option_id;
												} else {
													$option_value[] = $groupdata->option_value;
												}
											}
										}
									}
								}
								
								$fieldValue = array_combine($field_name, $option_value);
								//print_r($fieldValue);
							} else {
								//echo "JSON data not decoding";
							}
						} else {
							//echo "Data not found";
						}
						?>


						
						<?php

						foreach ($languagegroups as $lk => $lgv) {
							$lvgid = $lgv->language_id;
							$groups = $lgv->group;
							if ($lvgid == $languageid) {
								$i = 0;
								$questions = $groups[0]->screens[0]->questions;

								$grp_id = $groups[$lk]->group_id;
								$singleScreenGroups = [];
								$repeatedScreenGroups = [];
								$data = [];
								foreach ($questions as $question) {

									$question_id = $question->question_id;
									$question_name = $question->question_name;
									$question_input_type = $question->question_input_type;
									$question->question_type;
									$field_name = $question->field_name;
									$question->validation_id;
									$max_limit = $question->max_limit;
									$read_only = $question->read_only;
									$constraints = $question->constraints;
									$constraint_msg = $question->constraint_msg;
									$question->repeat_count;
									$relevant = $question->relevant;
									$default_response = $question->default_response;
									$limit = $question->limit;
									$lookups = $question->lookups;
									$question->required;
									$appearance = $question->appearance;
									$question->default_response;
									$question->question_description;
									$question->paradata;
									$question_options = $question->question_options; //Arr
									$screened = $question->screened; // FOR SINGLE SCREEN MULTIPLE QUESTIONS ====>GROUP ID
									$screened_count = $question->screened_count;
									$singleScreenQuestions = [];

									if ($screened > 0) {
										$position_id = $groupsQuestions[$screened]->position_id;
										$singleScreenGroups[] = $group_name = $groupsQuestions[$screened]->group_name;
										$singleScreenQuestions = $groups[$position_id]->screens[0]->questions;
									}
									///// FOR ROSTER GROUP ====>Written by Khushboo

									$repeated = $question->repeated;
									$repeat_count = $question->repeat_count;
									$repeatedScreenQuestions = [];
									if ($repeated > 0) {
										 $rgroup_name = $groupsQuestions[$repeated]->group_name;
										$position_id = $groupsQuestions[$repeated]->position_id;
										$repeatedScreenGroups[] = $rgroup_name = $groupsQuestions[$repeated]->group_name;
										$repeatedScreenQuestions = $groups[$position_id]->screens[0]->questions;
									}

									//////////////////////////

								?>
									<?php

									if ($default_response == '#name' || $default_response == '#username') {
										$login_user_name = getUserName($default_response); //create a function in this page
									}

									$conresultyear = splitValues($constraints); //splitValues function create this page
									$year_array = array_values($conresultyear);
									$minyear = $year_array[0];
									$maxyear = $year_array[1];

									if ($minyear != '' && $maxyear != '') {
										$constractData = "min='" . $minyear . "' max='" . $maxyear . "'";
									} else {
										$constractData = "";
									}
									?>

									<!----START CREATING FORM----->
									<?php
									if (strtolower($question_input_type) == 'note') {
										$i++; ?>

										<fieldset id="ss<?= $i; ?>">
											<div class="fieldset-box">
												<h2 class="fs-subtitle1"><?= strip_tags($question_name); ?></h2>
												<input type="hidden" id="relevant<?= $i; ?>" data-inputType="note" data-constraints="<?= $constraints; ?>" value="<?= $relevant; ?>" />
											</div>
											<div class="prev-next-btn"><input type="button" name="previous" class="previous action-button" value="Previous" />
												<button type="button" name="next" class="next action-button" value="<?= $i; ?>">Next</button>
											</div>

										</fieldset>
									<?php
										//Lookups value fetch
									} else if (strtolower($lookups) == 'yes' && ((strtolower($question_input_type) == 'integer' || strtolower($question_input_type) == 'number' || strtolower($question_input_type) == 'text'))) {
										$i++; 
										if($question_input_type=='number' || $question_input_type=='integer'){
											$onInputValue1='oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)"';
										}
										?>
										<fieldset id="ss<?= $i; ?>">
											<div class="fieldset-box">
												<h3 class="fs-subtitle"><?= $question_name;  ?></h3>
												<?php 
												if (strtolower($question_input_type) == 'text' && $max_limit >= 100) { ?>
													<textarea class="form-control lookups_value" id="<?= $field_name; ?>" name="<?= $field_name; ?>" maxlength="<?= $max_limit; ?>" required wrap="hard" cols="30" rows="5"><?= $fieldValue[$field_name]; ?></textarea>
												<?php } else { ?>
													<input type="<?= $question_input_type; ?>" class="form-control lookups_value" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?= $fieldValue[$field_name]; ?>" maxlength="<?= $max_limit; ?>" <?= $onInputValue1; ?> placeholder="" required />
												<?php } ?>
												<input type="hidden" id="relevant<?= $i; ?>" data-inputType="<?= strtolower($question_input_type) ?>" data-constraints="<?= $constraints; ?>" value="<?= $relevant; ?>" />
												<input type="hidden" id="default_response<?= $i; ?>" data-inputType="<?= strtolower($question_input_type) ?>" data-field="<?= $field_name; ?>" value="<?= $default_response; ?>" data-respone-name='<?= $login_user_name ?>' />
												
											</div>
											<div class="prev-next-btn"><input type="button" name="previous" class="previous action-button" value="Previous" />
												<button type="button" name="next" class="next action-button lookups_next" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
											</div>

										</fieldset>
									<?php
									} else if ((strtolower($question_input_type) == 'integer' || strtolower($question_input_type) == 'number' || strtolower($question_input_type) == 'text')) {
										$i++;
									if($question_input_type=='number' || $question_input_type=='integer'){
											$onInputValue2='oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)"';
										}
									?>
										<fieldset id="ss<?= $i; ?>">
											<div class="fieldset-box">
												<h3 class="fs-subtitle"><?= $question_name; ?></h3>
												<span class="khushi number"></span>

												<?php if ($question_input_type == 'number' && $constraints != '' && $appearance == 'dropdown') {  
													
													$values = splitValues($constraints);
													$constraintsMin = isset($values[1]) ? $values[1] : null;
													$constraintsMax = isset($values[3]) ? $values[3] : null;

													if ($constraintsMin !== null && $constraintsMax !== null) {
														// Generate the range of values
														$constraintsRange = range($constraintsMin, $constraintsMax);
														//print_r($constraintsRange);
													} 
												
												?>
													
													<select class="form-control" name="<?= $field_name; ?>" data-id="<?= $field_name ?>" id="<?= $field_name; ?>" data-constraints="<?= $constraints; ?>" <?php if (strtolower($read_only) == 'yes') {	echo 'disabled';} ?> required>
														<option value="select option">Select option</option>
														<?php foreach ($constraintsRange as $constraintsValue){ ?>
															<option value="<?php echo $constraintsValue; ?>" <?php if($fieldValue[$field_name]==$constraintsValue){echo "selected";}?> ><?php echo $constraintsValue; ?></option>
														<?php } ?>
													</select>
												<?php	} else{
													if($question_input_type=='text' && $max_limit>=100){ ?>
														<textarea class="form-control" <?= $constractData; ?> id="<?= $field_name; ?>" name="<?= $field_name; ?>" maxlength="<?= $max_limit; ?>" data-question="<?= $question_id; ?>" placeholder="" required wrap="hard" cols="30" rows="5"><?=$fieldValue[$field_name]?></textarea>
													<?php } else { ?>
														<input type="<?= $question_input_type; ?>" <?= $constractData; ?> class="form-control" id="<?= $field_name; ?>" value="<?=$fieldValue[$field_name]?>" name="<?= $field_name; ?>" maxlength="<?= $max_limit; ?>" value="<?=$fieldValue[$field_name]?>" data-question="<?= $question_id; ?>" <?=$onInputValue2?> placeholder="" required />
												<?php }
													?>
												
												<?php } ?>
												<input type="hidden" class="repeated_question<?= $i; ?>" id="<?= $field_name; ?>" value="" data-repeat="<?= $repeated; ?>" data-question="<?= $question_id; ?>" data-inputType="<?= strtolower($question_input_type) ?>" />

												<input type="hidden" id="relevant<?= $i; ?>" data-inputType="<?= strtolower($question_input_type) ?>" data-field="<?= $field_name; ?>" data-constraint-msg="<?= $constraint_msg ?>" data-constraints="<?= $constraints; ?>" value="<?= $relevant; ?>" />
												
												<input type="hidden" id="default_response<?= $i; ?>" data-inputType="<?= strtolower($question_input_type) ?>" data-field="<?= $field_name; ?>" value="<?= $default_response; ?>" data-respone-name='<?= $login_user_name ?>' />

												<!--<div id="<?= $field_name; ?><?= $question_id; ?>"></div>-->
												<!--<div id="repeatTest"></div>-->

											</div>
											<div class="prev-next-btn"><input type="button" name="previous" class="previous action-button" value="Previous" />
												<button type="button" name="next" class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
											</div>

										</fieldset>

									<?php
									} else if ((strtolower($question_input_type) == 'date' || strtolower($question_input_type) == 'time')) {
										$i++; ?>

										<fieldset id="ss<?= $i; ?>">
											<div class="fieldset-box">
												<h3 class="fs-subtitle"><?= $question_name; ?></h3>
												<h3 class="khushboo"></h3>
												<!--------------Appearance date condition start-------------------------->
												<?php if ($appearance == 'days' || $appearance == 'time' || $appearance == 'date' ||  $appearance == 'datetime' || $appearance == 'month-year' || $appearance == 'month' || $appearance == 'year') {

													if ($appearance == 'days') {
												?>
														<select class="form-control" name="<?= $field_name; ?>" id="<?= $field_name; ?>" <?php if (strtolower($read_only) == 'yes') {
																																															echo 'disabled';
																																														} ?> required>
															<option value="Select Days">Select Days</option>
															<?php
															foreach ($nowdays as $key => $nowday) {
																
																$selectedval='';
																
																if($fieldValue[$field_name]==$nowday){
																	$selectedval="selected";
																}else if($fieldValue[$field_name]=='' && $nowday){
																	$selectedval = ($default_response == 'today' && $key == $dayOfWeek) ? "selected" : "";
																}
															?>
																<option value="<?= $nowday ?>" <?= $selectedval ?>><?= $nowday ?></option>
															<?php
															}
															?>
														</select>
													<?php
													} else if ($appearance == 'time') {
														
														$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
														if($fieldValue[$field_name]){	
															$selectedval="value='".$fieldValue[$field_name]."'";
														}else{
															$selectedval = ($default_response == 'today') ? "value='$nowtime'" : "";
														}
													?>
														<input type="time" class="form-control" id="<?= $field_name; ?>" <?= $read_only ?> name="<?= $field_name; ?>" required <?= $selectedval; ?> />

													<?php } else if ($appearance == 'datetime') {
														if ($constraints == '<=today') {
															$DateValue = "max='" . $nowdatetime . "'";
														} else if ($constraints == '>=today') {
															$DateValue = "min='" . $nowdatetime . "'";
														}
														
														$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
														if($fieldValue[$field_name]){	
															$selectedval="value='".$fieldValue[$field_name]."'";
														}else{
															$selectedval = ($default_response == 'today') ? "value='$nowdatetime'" : "";
														}
													?>
														<input type="datetime-local" class="form-control" <?= $DateValue; ?> id="<?= $field_name; ?>" <?= $read_only; ?> name="<?= $field_name; ?>" required <?= $selectedval ?> />

													<?php } else if ($appearance == 'date') {
														if ($constraints == '<=today') {
															$DateValue = "max='" . $nowdate . "'";
														} else if ($constraints == '>=today') {
															$DateValue = "min='" . $nowdate . "'";
														}
														
														$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
														if($fieldValue[$field_name]){
															$selectedval = "value='" . $fieldValue[$field_name] . "'";
														}else{
															$selectedval = ($default_response == 'today') ? "value='$nowdate'" : "";
														}
													?>
														<input type="date" class="form-control" <?= $DateValue; ?> id="<?= $field_name; ?>" <?= $read_only; ?> name="<?= $field_name; ?>" required <?= $selectedval ?> />

													<?php } else if ($appearance == 'month-year') {
														
														$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
														if($fieldValue[$field_name]){
															$selectedval = "value='" . $fieldValue[$field_name] . "'";
														}else{
															$selectedval = ($default_response == 'today') ? "value='$now_month_year'" : "";
														}
													?>
														<input type="month" class="form-control" id="<?= $field_name; ?>" <?= $read_only; ?> value="<?=$fieldValue[$field_name]?>" name="<?= $field_name; ?>" required <?= $selectedval; ?> />

														<?php } else if ($appearance == 'month') {

														$constraintsVal = $constraints;  //constraints value Example =>=8&<=12;
														$conresult = splitValues($constraintsVal);

														$reset_array = array_values($conresult);
														$firstConstraint = $reset_array[0];
														$secondConstraint = $reset_array[1];

														//print_r($reset_array);
														if (count($conresult) > 0) {
															$monthcs = [];

															// Generate month names based on constraints
															for ($p = $firstConstraint; $p <= $secondConstraint; $p++) {
																$month_name = date("F", mktime(0, 0, 0, $p, 1));
																$monthcs[$p] = $month_name;
															}
														}

														if ($appearance == 'month' && $constraints != '') { ?>
															<select class="form-control" name="<?= $field_name; ?>" <?php if (strtolower($read_only) == 'yes') { echo 																'disabled';} ?> id="<?= $field_name; ?>" required>
																<option value="Select Month">Select Month </option>
																<?php
																// if ($default_response == 'today') {
																	
																// $selectedval = $monthName;
																// echo $todayMonth="<option value='".$selectedval."' selected>".$monthName."</option>";
																// } 
																?>
																
																<?php
																foreach ($monthcs as $key => $monthdata) {
																	$selectedval='';
																	
																	 if($fieldValue[$field_name]==$monthdata){
																		 echo $fieldValue[$field_name];
																			$selectedval="selected";
																		}else if($fieldValue[$field_name]=='' && $monthdata){
																			$selectedval = ($default_response == 'today' && $monthdata == $monthName) ? "selected" : "";
																		}
																?>
																	<option value="<?= $monthdata ?>" <?=$selectedval?>><?= $monthdata ?></option>
																<?php
																}
																?>
															</select>
														<?php } else {
														?>
															<select class="form-control" name="<?= $field_name; ?>" <?php if (strtolower($read_only) == 'yes') {
																														echo 'disabled';
																													} ?> id="<?= $field_name; ?>" required>
																<option value="Select Month">Select Month</option>
																<?php
																foreach ($months as $key => $monthdata) {
																	
																	if($fieldValue[$field_name]){
																		 $selectedval = "value='" . $fieldValue[$field_name] . "'";
																	}else{
																		$selectedval = ($default_response == 'today' && $key == $monthName) ? "selected" : "";
																	}
																?>
																	<option value="<?= $monthdata ?>" <?= $selectedval; ?>><?= $monthdata ?></option>
																<?php }
																?>
															</select>
														<?php }
													} else if ($appearance == 'year') {

														
														$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
														if($fieldValue[$field_name]){
																 $selectedval = "value='" . $fieldValue[$field_name] . "'";
															}else{
																$selectedval = ($default_response == 'today') ? "value='$year'" : "";
															}
														?>
														<input class="form-control datepicker" <?= $constractData ?> id="<?= $field_name; ?>" <?= $read_only; ?> name="<?= $field_name; ?>" required <?= $selectedval ?> />
												<?php }
												} ?>
												<!----Appearance date condition end--->
												<!----Constraints date currdate and futuredate start--->

												<?php

												if ($default_response == '' && $appearance != 'date' && $appearance != 'datetime' && $constraints == '') { ?>
													<input type="<?= strtolower($question_input_type) ?>" class="form-control" id="<?= $field_name; ?>" name="<?= $field_name; ?>" placeholder="" required value="<?=$fieldValue[$field_name]?>"/>

												<?php } else if ($default_response == '' && $appearance == '' && ($constraints == '<=today' || $constraints == '>=today')) {
													if ($constraints == '<=today') {
														$DateValue = "max='" . $nowdate . "'";
													} else if ($constraints == '>=today') {
														$DateValue = "min='" . $nowdate . "'";
													}
												?>
													<input type="<?= strtolower($question_input_type) ?>" class="form-control" <?= $DateValue ?> id="<?= $field_name; ?>" <?php if (strtolower($read_only) == 'yes') {
																																																	echo 'readonly';
																																																} ?> name="<?= $field_name; ?>" required value="<?=$fieldValue[$field_name]?>"/>

												<?php } ?>
												<!----Constraints date currdate and futuredate end--->
												<!----default_respons date condition start khushboo------>
												<?php if ($appearance == '' && ($default_response == 'today')) { 
													if($fieldValue[$field_name]){
														$selectedValue=$fieldValue[$field_name];
													}else{
														$selectedValue=$nowdate;
													}
												?>
														<input type="<?= strtolower($question_input_type) ?>" class="form-control" id="<?= $field_name; ?>" <?php if (strtolower($read_only) == 'yes') { echo 'readonly'; } ?> name="<?= $field_name; ?>" required value="<?= $selectedValue ?>" />	
												<?php } ?>
												
												<!----default_respons date condition end---->
												<?php 
													if($appearance == 'month'){ 
														$constraints='';
													}
												?>
												<input type="hidden" id="relevant<?= $i; ?>" data-inputType="<?= strtolower($question_input_type) ?>" data-constraints="<?=$constraints; ?>" value="<?= $relevant; ?>" />

											</div>
											<div class="prev-next-btn">
											<input type="button" name="previous" class="previous action-button" value="Previous" />
												<button type="button" name="next" class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
											</div>

										</fieldset>

									<?php
									} else if (strtolower($question_input_type) == 'select_one') {
										$i++; ?>

										<?php
										if ($appearance == "dropdown") { ?>
											<fieldset id="ss<?= $i; ?>">
												<div class="fieldset-box">
													<h3 class="fs-subtitle"><?= $question_name; ?></h3>
													<select class="form-control" name="<?= $field_name; ?>" <?php if (strtolower($read_only) == 'yes') { echo 'disabled'; } ?> id="<?= $field_name; ?>" required>
														<option value="">Select Option</option>
														<?php
														$steps .= '<span class="step"></span>';
														foreach ($question_options as $questionoption) {
															if($fieldValue[$field_name]==$questionoption->option_id){
																$selectedOptdrop="selected";
															}
														?>
															<option value="<?= $questionoption->option_id; ?>" <?=$selectedOptdrop;?>><?= $questionoption->option_value; ?></option>
														<?php
														}
														?>
													</select>
													<input type="hidden" id="relevant<?= $i; ?>" data-inputType="select_one" data-constraints="<?= $constraints; ?>" value="<?= $relevant; ?>" />
												</div>
												<div class="prev-next-btn"><input type="button" name="previous" class="previous action-button" value="Previous" />
													<button type="button" name="next" class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
												</div>
											</fieldset>
										<?php
										} else  {
											if ($appearance == 'quick') {
												$quickfieldset = "class='quickfieldset'";
												$quickoptionul = "class='quickoptionul'";
												$quickoptionli = "class='quickoptionli'";
											}
										?>
											<fieldset id="ss<?= $i; ?>" <?= $quickfieldset ?>>
												<div class="fieldset-box">
													<h3 class="fs-subtitle"><?= $question_name; ?></h3>
													<input type="text" class="d-none" name="<?= $field_name; ?>" value="<?=$fieldValue[$field_name]?>" <?php if (strtolower($read_only) == 'yes') { echo 'disabled'; } ?> id="<?= $field_name; ?>" />
													<ul class="list-tab" style="text-align:left;" <?= $quickoptionul ?>>
														<?php
														$steps .= '<span class="step"></span>';
														foreach ($question_options as $questionoption) {
															$selectedOpt='';
															$selectedcircle='';
															if($fieldValue[$field_name]==$questionoption->option_id){
																$selectedOpt="active";
																$selectedcircle="fa fa-dot-circle-o";
															}
														?>
															<li <?= $quickoptionli ?> class="<?=$selectedOpt?>" onclick="setRadioValue(<?= $questionoption->option_id; ?>,'<?= $field_name; ?>','<?= $field_name . $questionoption->option_id; ?>')"><i class="fa fa-circle-o <?= $field_name; ?> <?=$selectedcircle?>" id="<?= $field_name . $questionoption->option_id; ?>"></i> <?= $questionoption->option_value; ?> </li>
														<?php
														}
														?>
													</ul>

													<input type="hidden" id="relevant<?= $i; ?>" data-inputType="radio" data-constraints="<?= $constraints; ?>" value="<?= $relevant; ?>" />
												</div>
												<div class="prev-next-btn"><input type="button" name="previous" class="previous action-button" value="Previous" />
													<button type="button" name="next" class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
												</div>
											</fieldset>
										<?php
											$quickfieldset = '';
										}
										?>

									<?php
									} else if (strtolower($question_input_type) == 'select_multiple') {
										$i++; 
										 
										?>

										<fieldset id="ss<?= $i; ?>">
											<div class="fieldset-box">
												<!-- <h2 class="fs-title">Question <?= $i; ?></h2> -->
												<h3 class="fs-subtitle"><?= $question_name; ?></h3>
												<!-- <input type="hidden" value="<?= $question_input_type; ?>" name="qtype[<?= $field_name; ?>]" /> -->
												<!-- <input type="hidden" value="<?= $question_id; ?>" name="questions[<?= $field_name; ?>]" /> -->

												<input type="text" class="d-none <?= $field_name; ?>" value="<?=$fieldValue[$field_name] ?>" name="<?= $field_name; ?>" id="<?= $field_name; ?>" />
												<select class="form-control multiple-select selectoption<?= $field_name; ?>" multiple="multiple" onchange="multiSelect('<?= $field_name; ?>')" <?php if (strtolower($read_only) == 'yes') { echo 'disabled'; } ?> maxlength="<?= $max_limit; ?>" data-placeholder="" multiple required>
													<option value="">select option</option>
													<?php  //echo "hello";
													if($fieldValue[$field_name]!=''){
														$field_namearr=explode(",",$fieldValue[$field_name]);
													}
													foreach ($question_options as $questionoption) {
														$selectedMulti = '';
														if (in_array($questionoption->option_id, $field_namearr)) {
															$selectedMulti = 'selected'; // Set the option as selected
														}
													?>
														<option value="<?= htmlspecialchars($questionoption->option_id); ?>" <?= $selectedMulti; ?>><?= htmlspecialchars($questionoption->option_value); ?></option>
													<?php } ?>
												
												</select>


												<input type="hidden" id="relevant<?= $i; ?>" data-inputType="select_multiple" data-constraints="<?= $constraints; ?>" value="<?= $relevant; ?>" />
											</div>
											<div class="prev-next-btn"><input type="button" name="previous" class="previous action-button" value="Previous" />
												<button type="button" name="next" class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
											</div>

										</fieldset>

									<?php
									}
									?>
									<!----END CREATING FORM---->

									<!---REPEAT GROUP QUESTIONS FORMS START -->
									<?php if ($repeated != '') {
										$i++;
									?>
										<fieldset id="ss<?= $i; ?>">
											<div class="fieldset-box">
												<div id="<?= $field_name; ?><?= $question_id; ?>"></div>
											</div>
											<input type="hidden" id="relevant<?= $i; ?>" value="<?= $relevant; ?>" />
											<div class="prev-next-btn"><input type="button" name="previous" class="previous action-button" value="Previous" />
												<button type="button" name="next" class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
											</div>
										</fieldset>
									<?php } ?>
									<!---REPEAT GROUP QUESTIONS FORMS END -->

									<!---SINGLE SCREEN QUESTIONS FORMS START -->
									<?php
									if (count($singleScreenQuestions) > 0) {
										$i++; ?>
										<fieldset id="ss<?= $i; ?>">
											<div class="fieldset-box">
												<?php
												foreach ($singleScreenQuestions as $question1) {
													$question_id1 = $question1->question_id;
													$question_name1 = $question1->question_name;
													$question_input_type1 = $question1->question_input_type;
													$field_name1 = $question1->field_name;
													$relevant1 = $question1->relevant;
													$appearance1 = $question1->appearance;
													$limit1 = $question1->limit;
													$max_limit1 = $question1->max_limit;
													$question_options1 = $question1->question_options;
													$read_only = $question1->read_only;
													
													$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
													$readonly = (strtolower($read_only) == 'yes') ? "disabled" : "";
													if($question_input_type1=='number' || $question_input_type1=='integer'){
														$onInputValue='oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)"';
													}
												?>
													<?php if (strtolower($question_input_type1) == 'note') { ?>
														<h3 class="fs-subtitle">Note: <?= strip_tags($question_name1); ?></h3>
													
													<?php } else if ((strtolower($question_input_type1) == 'integer' || strtolower($question_input_type1) == 'number' || strtolower($question_input_type1) == 'text')) { ?>
														<h3 class="fs-subtitle"><?= $question_name1; ?></h3>
															
														<?php if($question_input_type1=='text' && $max_limit1>=100){ ?>
															<textarea class="form-control <?= $group_name; ?>" <?=$read_only?>  id="<?= $field_name1; ?>" name="<?= $field_name1; ?>" maxlength="<?= $max_limit1; ?>" data-id="<?= $field_name1; ?>" placeholder="" required wrap="hard" cols="30" rows="5"><?=$fieldValue[$field_name1]?></textarea>
														<?php } else { ?>
															<input type="<?= $question_input_type1; ?>" <?=$read_only?> class="form-control <?= $group_name; ?>" id="<?= $field_name1; ?>" value="<?=$fieldValue[$field_name1]?>" data-id="<?= $field_name1; ?>" maxlength="<?= $max_limit1; ?>" required <?=$onInputValue?>/>
														<?php }
															

														?>
													<?php } else if ((strtolower($question_input_type1) == 'date' || strtolower($question_input_type1) == 'time')) { ?>
														<h3 class="fs-subtitle"><?= $question_name1; ?></h3>
														<input type="<?= strtolower($question_input_type1) ?>" <?=$read_only?> class="form-control <?= $group_name; ?>" id="<?= $field_name1; ?>" value="<?=$fieldValue[$field_name1]?>" data-id="<?= $field_name1; ?>" placeholder="" required />
													
													<?php } else if (strtolower($question_input_type1) == 'select_one') { ?>

														<?php if ($appearance1 == "dropdown") { ?>
															<h3 class="fs-subtitle"><?= $question_name1; ?></h3>

															<select class="form-control <?= $group_name; ?>" <?=$readonly;?> data-id="<?= $field_name1; ?>" id="<?= $field_name1; ?>" required>
																<option value="">Select Option</option>
																<?php
																$steps .= '<span class="step"></span>';
																foreach ($question_options1 as $questionoption1) {
																	if($fieldValue[$field_name1]==$questionoption1->option_id){
																		$selectedOptdrop1="selected";
																	}
																?>
																	<option value="<?= $questionoption1->option_id; ?>" <?=$selectedOptdrop1;?>><?= $questionoption1->option_value; ?></option>
																<?php
																}
																?>
															</select>
														<?php } else { ?>
															<h3 class="fs-subtitle"><?= $question_name1; ?> </h3>
															<input type="text" class="d-none <?= $group_name; ?>" value="<?=$fieldValue[$field_name]?>" <?=$read_only;?> data-id="<?= $field_name1; ?>" id="<?= $field_name1; ?>" />
															<ul style="text-align:left;">
																<?php
																$steps .= '<span class="step"></span>';
																foreach ($question_options1 as $questionoption1) {
																	$selectedOpt1='';
																	$selectedcircle1='';
																	if($fieldValue[$field_name1]==$questionoption1->option_id){
																		$selectedOpt1="active";
																		$selectedcircle1="fa fa-dot-circle-o";
																	}
																?>
																	<li class="<?=$selectedOpt1?>" onclick="setRadioValue(<?= $questionoption1->option_id; ?>,'<?= $field_name1; ?>','<?= $field_name1 . $questionoption1->option_id; ?>')" <?=$readonly?> ><i class="fa fa-circle-o <?= $field_name1; ?> <?=$selectedcircle1?>" id="<?= $field_name1 . $questionoption1->option_id; ?>"></i> <?= $questionoption1->option_value; ?> </li>
						
																<?php
																}
																?>
															</ul>
														<?php
														} ?>

													<?php } else if (strtolower($question_input_type1) == 'select_multiple') { ?>
														<!-- <h2 class="fs-title">Question <?= $i; ?></h2> -->
														<h3 class="fs-subtitle"><?= $question_name1; ?></h3>

														<input type="text" class="d-none <?= $field_name1; ?>" value="<?=$fieldValue[$field_name1] ?>" data-id="<?= $field_name1; ?>" id="<?= $field_name1; ?>" />
														<select class="form-control multiple-select <?= $group_name; ?> selectoption<?= $field_name1; ?>" <?=$readonly?> multiple="multiple" onchange="multiSelect('<?= $field_name1; ?>')" maxlength="<?= $max_limit1; ?>" data-placeholder="" multiple required>
															<option value="">Select Option</option>
															<?php
															if($fieldValue[$field_name1]!=''){
																$field_namearr1=explode(",",$fieldValue[$field_name1]);
															}
															foreach ($question_options1 as $questionoption1) {
																$selectedMulti_new = '';
																if (in_array($questionoption->option_id, $field_namearr1)) {
																	$selectedMulti_new = 'selected'; // Set the option as selected
																}
															?>
																<option value="<?= $questionoption1->option_id; ?>" <?=$selectedMulti_new ?>><?= $questionoption1->option_value; ?></option>
															<?php
															}
															?>
														</select>
													<?php } ?>


												<?php
												}
												?>
												<input type="hidden" id="relevant<?= $i; ?>" value="<?= $relevant1; ?>" />
											</div>
											<!--<input type="hidden" class="repeated_question<?= $i; ?>" id="<?= $field_name1; ?>" value="" data-repeat="<?= $repeated1; ?>" data-question="<?= $question_id1; ?>" data-inputType="<?= strtolower($question_input_type1) ?>" />-->
											<!--<div id="<?= $field_name; ?><?= $question_id; ?>"></div>-->
											<div class="prev-next-btn"><input type="button" name="previous" class="previous action-button" value="Previous" />
												<button type="button" name="next" class="next action-button" data-id="<?= $field_name1; ?>" value="<?= $i; ?>">Next</button>
											</div>

										</fieldset>
									<?php
									}
									?>

						<?php
								}
							}
						}
						?>
					</form>

					<!---SINGLE GROUP QUESTIONS ---->
					<?php $sss = implode(",", $singleScreenGroups); ?>
					<input type="hidden" id="allSingleGroup" value="<?php echo $sss; ?>" />
					
					<!---REPEAT GROUP QUESTIONS ---->
					<?php $repeatgroup = implode(",", $repeatedScreenGroups); ?>
					<input type="hidden" id="allRepeatGroup" value="<?php echo $repeatgroup; ?>" />

				</div>
			</div>
		</div>
	</div>
</body>

</html>
<!-- jQuery -->

<script src="<?= base_url(); ?>js/jquery-3.7.1.js"></script>
<script src="<?= base_url(); ?>js/jQuery-UI-1.13.js"></script>

<script src="<?= base_url(); ?>js/bootstrap.bundleV5-3.min.js"></script>
<!-- jQuery easing plugin -->
<script src="<?= base_url(); ?>js/jquery.easing.min.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>js/select2.min.js"></script>
<script src="<?= base_url(); ?>js/bootstrap-datepicker.min.js"></script>
<script>
	$(".multiple-select").select2({

	});

	function copyToClipboard(encodedId) {
		var element = document.getElementById(encodedId);
		var tempInput = document.createElement("input");

		document.body.appendChild(tempInput);
		tempInput.value = element.textContent;
		tempInput.select();
		document.execCommand("copy");
		document.body.removeChild(tempInput);

		$('#copied-success').fadeIn(800).fadeOut(800);
	}
</script>
<script>
	$(document).ready(function() {
		var minYear = parseInt($('#year').attr('min'));
		var maxYear = parseInt($('#year').attr('max'));

		$(".datepicker").datepicker({
			format: "yyyy",
			viewMode: "years",
			minViewMode: "years",
			autoclose: true,
			startDate: new Date(minYear, 0, 1),
			endDate: new Date(maxYear, 11, 31)
		});
	});
	/* 
		$(".datepicker").datepicker({
			format: "yyyy",
			viewMode: "years",
			minViewMode: "years",
			autoclose: true,
			
		});
	*/
</script>
<script>
	$(".lookups_next").on('click', function() {
		var lookups_value = $(".lookups_value").val();
		var survey_id = <?= $survey_id ?>;

		$.ajax({
			url: "ajax/getfacility.php",
			type: "POST",
			data: {
				lookups_value: lookups_value,
				survey_id: survey_id
			},
			success: function(response) {

				if (typeof response === 'object') {
					var data = response;
				}
				//console.log(data);

				for (var key in data) {
					if (data.hasOwnProperty(key)) {
						var idSelector = "#" + key;
						$(idSelector).val(data[key]);
					}
				}
			},
			error: function(xhr, status, error) {
				console.error("Error DATA:", status, error);
			}
		});
	});
</script>
<script>

	function setRadioValue(selectRadio, setId, iconId) {
		$('#' + setId).val(selectRadio);
		$('.' + setId).removeClass("fa fa-dot-circle-o");
		$('.' + setId).addClass("fa fa-circle-o");
		$('#' + iconId).addClass("fa fa-dot-circle-o");
		$('.list-tab > li').removeClass("active"); // Remove "active" class from all list items
		$('.list-tab').on('click', 'li', function() {
			$('.list-tab > li').removeClass("active"); // Remove "active" class from all list items
			$(this).addClass("active"); // Add "active" class to the clicked list item
		});
	}

	function multiSelect(mselId) {
		var msSlectedVal = $(".selectoption" + mselId).val().join();
		$('.' + mselId).val(msSlectedVal);
	}
</script>
<script>
	// JavaScript to trigger click event on next quick type appereance
	const listquick = document.querySelectorAll('.quickoptionul .quickoptionli');
	listquick.forEach(li => {
		li.addEventListener('click', function() {
			const nextButton = this.closest('.quickfieldset').querySelector('.next');
			nextButton.click();
		});
	});
</script>
<script>
	//GET GPS
	$(document).ready(function() {
		getLocation();
		getStartDateTime();
	});

	function getLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		} else {
			$("#GPS_latitude_start").val("");
			$("#GPS_longitude_start").val("");
		}
	}

	function showPosition(position) {
		$("#GPS_latitude_start").val(position.coords.latitude);
		$("#GPS_longitude_start").val(position.coords.longitude);
	}
	
	function getStartDateTime() {
		var now = new Date();
		
		// Extract components for formatting
		var year = now.getFullYear();
		var month = ("0" + (now.getMonth() + 1)).slice(-2);
		var day = ("0" + now.getDate()).slice(-2);
		var hours = ("0" + now.getHours()).slice(-2);
		var minutes = ("0" + now.getMinutes()).slice(-2);
		var seconds = ("0" + now.getSeconds()).slice(-2);
		
		// Format date and time in Y-m-d H:i:s format
		var formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
		
		$("#start_date_time").val(formattedDateTime);
	}

</script>
<script>
	$(document).ready(function() {
		$("#startinterview").click(function() {
			var langId = $("#langId").val();
			var surId = "<?= base64_encode($survey_id); ?>";
			
			// If langId is selected
			if (langId !== "") {
				var redirectURL = '<?= base_url() ?>' + 'skip_userform_design.php?survey_id=' + surId + '&langId=' + langId;
				window.location.href = redirectURL;
			
			}
		});
	});



	function isString(value) {
		return typeof value === 'string';
	}

	function isNumber(value) {
		return !isNaN(value) && isFinite(value);
	}

	function concatenateValues(firstId, secondId, resultId) {
		let firstValue = $("#" + firstId).val();
		let secondValue = $("#" + secondId).val();
		let concatenatedValue;
		// Check if both values are numbers
		if (isNumber(firstValue) && isNumber(secondValue)) {
			concatenatedValue = firstValue + secondValue;
		} else {
			concatenatedValue = firstValue + ' ' + secondValue;
		}
		$("#" + resultId).val(concatenatedValue);
	}

	function processDefaultResponse(num) {
		let isdefaultResponse = $("#default_response" + num).val();

		if (isdefaultResponse) {
			//console.log(isdefaultResponse);
			let datafield_one = isdefaultResponse;
			let datafield = $("#default_response" + num).attr("data-field");
			let splittedValues = datafield_one.split('#');

			if (splittedValues.length > 2) {
				let firstId = splittedValues[1];
				let secondId = splittedValues[2];
				let resultId = datafield;
				concatenateValues(firstId, secondId, resultId);
			}
		}
	}

	function splitConstraints(constraintsString) {
		let splitConstraints = constraintsString.split(',');
		let minPart = splitConstraints[0].split('=')[1];
		let maxPart = splitConstraints[1].split('=')[1];
		return [parseInt(minPart), parseInt(maxPart)];
	}

	/* current time this function is not use
	function populateMinMaxDropdown(num) {
		let isconstantMinMax = $(".constantMinMax" + num).attr("data-constraints");

		if (isconstantMinMax !== undefined) {
			let constfldName = $(".constantMinMax" + num).attr("data-id");
			//console.log(constfldName);
			let constantfield = isconstantMinMax;

			// Split the string to extract min and max values
			let [minVal, maxVal] = splitConstraints(constantfield);

			//let resultoption = "";
			let resultoption = "<option value='Select Option'>Select Option</option>";
			for (let i = minVal; i <= maxVal; i++) {
				resultoption += "<option value='" + i + "'>" + i + "</option>";
			}
			// Hide the next element and set HTML content to the generated options
			$("#" + constfldName).next().hide();
			$("#" + constfldName).html(resultoption);
		}
	} */

	// Call the function with the desired 'num' value
	//populateDropdown(num);

	function splitMulti(str, tokens) {
		var tempChar = tokens[0]; // We can use the first token as a temporary join character
		for (var i = 1; i < tokens.length; i++) {
			str = str.split(tokens[i]).join(tempChar);
		}
		str = str.split(tempChar);
		return str;
	}

	function releventChecking(releventJson, cRelevants) {
		cRelevants = cRelevants.toLowerCase();
		var alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
		$.each(alpha, function(i, v) {
			v = v.toLowerCase();
			var findStr = '=' + v;
			cRelevants = cRelevants.replace(findStr, '="' + v + '"');

		});

		$.each(releventJson, function(i, j) {
			//console.log("fun-dd" + j);
			i = i.toLowerCase();
			j = j.toLowerCase();
			var m = i;
			var n = j;
			var scheck = false;
			n = n.slice(1, -1);
			var cArr = n.split(',');
			if (cArr.length > 1) {
				m = m.slice(0, -1);
				$.each(cArr, function(index, iv) {
					//console.log(iv);
					iv = '"' + iv + '"';
					var multiCheck = cRelevants.replace(m, iv);
					//console.log('sss-sss-' + multiCheck);
					if (eval(multiCheck)) {
						j = iv;
					}
				});
			}

			i = i.slice(0, -1);
			cRelevants = cRelevants.replace(i, j);
		});
		//console.log("fun-" + cRelevants);
		return cRelevants;
	}


	//jQuery time
	var current_fs, next_fs, previous_fs; //fieldsets
	var left, opacity, scale; //fieldset properties which we will animate
	var animating; //flag to prevent quick multi-click glitches
	var showQuestion;
	let formData = [];
	var qcheckPrev=[];
	$(".next").click(function() {
		//alert('kkkkk');
		if (animating) return false;
		animating = true;

		current_fs = $(this).parent().parent();
		next_fs = $(this).parent().parent().next();

		//console.log(next_fs);
		var btnsn = $(this).val();
		//console.log(btnsn);
		let prevBtn="ss"+btnsn;
		//console.log(prevBtn);
		
		
		
		var fldName = $(this).attr("data-id");
		var enterdata = $("#" + fldName).val();
		
			// SAVE NEXT BUTTON
		let surveyId = "<?= $survey_id; ?>";
		let languageId = "<?= $languageid; ?>";
		let survey_name = "<?= $survey_name; ?>";
		let sessionID = "<?= $cookieId; ?>";
		var GPS_latitude_start = $('#GPS_latitude_start').val();
		var GPS_longitude_start = $('#GPS_longitude_start').val();
		var start_date_time = $('#start_date_time').val();
		var partialData = 'PARTIAL-DATA';
		
		formData.push({ name: fldName, value: enterdata });
		//console.log("Full data-" + JSON.stringify(formData));
		// CREATING SINGLE SCREEN MULTIPLE QUESTION GROUP DATA
			var allSingleGroup = $("#allSingleGroup").val();
			var allsGroup = allSingleGroup.split(",");


			var allsGroupData = {};
			//console.log(allSingleGroup);
			if (allSingleGroup != "") {
				$.each(allsGroup, function(i, v) {
					var sGroupObj = {};
					$('.' + v).each(function() {
						var fname = $(this).data("id");
						var fvalue = $(this).val();
						if ($.isArray(fvalue)) {
							fvalue = fvalue.join(",");
						}
						sGroupObj[fname] = fvalue;
					});
					allsGroupData[v] = sGroupObj;
				});
			}
			//console.log(fdata);
			// console.log("All Group Data: ", allsGroupData);
			var allsGroupDatass = allsGroupData;
			
			//CREATING REPEAT GROUP MULTIPLE QUESTION DATA
		
            var allRepeatGroup = $("#allRepeatGroup").val();
            var allsRepeat = allRepeatGroup.split(",");
            var allsRepeatGroupDatas={};

            if (allRepeatGroup != "") {
                $.each(allsRepeat, function(i, v) {
                    var rGroupArray = [];
                    var elements = $('.' + v);

                    // Initialize array with null values up to the highest index
                    var highestIndex = Math.max(...elements.map(function() {
                        return parseInt($(this).data('index'), 10);
                    }).get());
                    for (var j = 0; j <= highestIndex; j++) {
                        rGroupArray[j] = null;
                    }

                    elements.each(function() {
                        var rGroupObj = {};
                        var index = $(this).data('index');
                        var subElements = $(this).find('input, select');

                        subElements.each(function() {
                            var fname = $(this).data("id");
                            var fvalue = $(this).val();

                            if ($.isArray(fvalue)) {
                                fvalue = fvalue.join(",");
                            }
                            rGroupObj[fname] = fvalue;
                        });

                        rGroupArray[index] = rGroupObj;
                    });
                    // Remove any null entries
                    rGroupArray = rGroupArray.filter(function(entry) {
                        return entry !== null && Object.keys(entry).length > 0;
                    });

                    allsRepeatGroupDatas[v] = rGroupArray;
                });
            }
           // console.log("All Repeat Group Data: ", allsRepeatGroupDatas);
			var allsRepeatGroupDatas = allsRepeatGroupDatas;
			//console.log("Group Data: ", allsRepeatGroupDatas);
	
		
		$.ajax({
				//url: "skip_userform_ajax.php",
				url: "ssajax.php",
				type: "post",
				
				 contentType: "application/json",
				data: JSON.stringify({
					partialData: partialData,
					fData: formData,
					surveyId: surveyId,
					languageId: languageId,
					survey_name: survey_name,
					sessionID: sessionID,
					GPS_latitude_start: GPS_latitude_start,
					GPS_longitude_start:GPS_longitude_start,
					start_date_time:start_date_time,
					allsRepeatGroupDatas: allsRepeatGroupDatas,
					allsGroupData: allsGroupDatass
					
				}),
				success: function(res) {
					//console.log(res);
				}
			});
		//SAVE NEXT BUTTON
		
		var qLenght = $(".next").length;
		var num = parseInt(btnsn) + 1;
		
		//DefaultResponse two string value display start
		let isdefaultResponse = $("#default_response" + num).val();

		let check = isdefaultResponse ? isdefaultResponse.includes("#") : false;

		if (isdefaultResponse !== '' && check && isdefaultResponse != '#name') {
			processDefaultResponse(num); //function create this page
		}
		if (isdefaultResponse == '#name' || isdefaultResponse == '#username') {
			let datafield = $("#default_response" + num).attr("data-field");
			let data_respone_name = $("#default_response" + num).attr("data-respone-name");

			$("#" + datafield).val(data_respone_name);
			//console.log($("#" + datafield).val(data_respone_name));

		}
		/////DefaultResponse two string value display end/////

		//////////////constantMinMax conditions///////////////////////
		/* let isconstantMinMax = $(".constantMinMax" + num).attr("data-constraints");
		let checkcomma = isconstantMinMax ? isconstantMinMax.includes(",") : false;
		if (isconstantMinMax !== undefined && isconstantMinMax !== '' && checkcomma) {
			populateMinMaxDropdown(num); // Function to create dropdown
		} */

		//////////////Khushboo constantMinMax conditions///////////////////////

		var isConstraints = $("#relevant" + btnsn).attr("data-constraints");
		var isConstraintsMsg = $("#relevant" + btnsn).attr("data-constraint-msg");
		let isDefault = $("#default_response" + btnsn).val();
		var inputTypes = $("#relevant" + btnsn).attr("data-inputType");
		// console.log("ss"+isConstraints);
		 
		// var constantErr = '';
		if (isConstraints !== null && isConstraints !== undefined && isConstraints !== '') {
			// Extract numbers using regex
			$("#error_messaged").html('');
			var constantErr = '';
			var regex = /(\d+)/g;
			var matches = isConstraints.match(regex);
			var isDefaultmatches = [];
			if (isDefault !== null && isDefault !== undefined && isDefault !== '') {
				isDefaultmatches = isDefault.match(regex);
				//console.log("sss-"+ isDefaultmatches);
			}
			
			//REGEX VALUE CHECK START
			if(isConstraints.includes('regex')){
				var updatedRegex = isConstraints.replace(/^regex [^ ]+/,isConstraints);
				var newRegex = updatedRegex.replace(/^regex /, "");
				console.log(newRegex);
				if(newRegex!== null && newRegex!==undefined && newRegex!==''){
					var constraintRegex = new RegExp(newRegex);
					var isValid = constraintRegex.test(enterdata);	
					
					  if (isValid) {
						//console.log('SUCCESS');
						$(".error_messaged").html('');
						$(".error_messaged").removeClass("ssanimation");
						
					  } else {
						//console.log('ERROR');
						constantErr = 'false';
					  }
				}
			}
			
			//REGEX VALUE CHECK END
			if (enterdata != "") {
				
				if (matches && matches.length === 2) {
					var minValue = parseInt(matches[0]); // Convert to integer
					var maxValue = parseInt(matches[1]); // Convert to integer

					// console.log('Lower Value: ' + minValue); // Output: 1
					// console.log('Upper Value: ' + maxValue); // Output: 12
					//$("#error_messaged").html('');
					if ((parseInt(enterdata) >= minValue && parseInt(enterdata) <= maxValue) || (isDefaultmatches.includes(enterdata))) {
						console.log('success');
						$(".error_messaged").html('');
						$(".error_messaged").removeClass("ssanimation");

					} else {
						constantErr = 'false';

					}
				}
			}
		}
		//Start Roster data
		let repeated = $(".repeated_question" + btnsn).attr("data-repeat");
		if (repeated !== '' && repeated > 0) {
			let question_id = $(".repeated_question" + btnsn).attr("data-question");
			//alert(repeated+ "-" + question_id);

			let field_name = $(".repeated_question" + btnsn).attr("id");
			let input_value = enterdata; //$(".repeated_question"+btnsn).val();

			let survey_id = "<?= $survey_id; ?>";
			let languageId = "<?= $languageid; ?>";
			if (repeated > 0 && repeated != '') {
				var repeatGroupName = $("#allRepeatGroup").val();
				$.ajax({
					type: 'POST',
					url: 'ajax_page.php',
					data: {
						questionId: question_id,
						input_value: input_value,
						repeat_group_name: repeatGroupName,
						survey_id: survey_id,
						language_id: languageId
					},
					success: function(result) {
						//console.log(result);
						//console.log($('#'+field_name+question_id));
						$('#' + field_name + question_id).html(result);

					}
				});
			}
		}

		//End Roster data
		var isRelevant = $("#relevant" + num).val();
		//console.log('sno'+num);
		// console.log('Relivent-'+isRelevant);
		var showStatus = 0;
		if (isRelevant != "") {
			for (var sn = num; sn <= qLenght; sn++) {
				showQuestion = false;
				var getRelevant = "#relevant" + sn;
				//console.log("ss-" + getRelevant);
				var relevantVal = $(getRelevant).val();
				//console.log("tt-" + relevantVal);
				//console.log("ssnsm",getRelevant);
				var relevantValChk = $(getRelevant).val();
				// let lastbutton=$('#msform button:last').attr('id', 'formSubmit');
				// alert(lastbutton);
				
				if (relevantValChk !== '' || typeof relevantValChk !== "undefined") {
					relevantValChk = relevantValChk.replace(/=/g, '==');
					relevantValChk = relevantValChk.replace(">==", '>=');
					relevantValChk = relevantValChk.replace("<==", '<=');
					relevantValChk = relevantValChk.replace("!==", '!=');



					//console.log(relevantValChk);
					//var relevants = splitMulti(relevantVal, ['&', '&&', '|', '||']);
					var relevants = splitMulti(relevantVal, ['&', '&&', '|', '||', '(', ' ', ')', ' ']);
					// console.log(relevants.length);
					//console.log(relevants);
					var fStatus = "success";
					var replacedVal_sss = '';
					var releventJson = {};
					var relObjKey = 0;
					$.each(relevants, function(key, relevantConditions) {
						//console.log(key + relevantConditions);

						if (relevantConditions != "") {
							relevantConditions = relevantConditions.trim();
							// console.log(relevantConditions);
							var cFildName = splitMulti(relevantConditions, ['!=', '<=', '>=', '<', '>', '=', '!']);
							//console.log(cFildName[0].trim());
							var cFname = cFildName[0].trim();
							var preval = $("#" + cFname).val();
							//console.log("khushboo-"+preval);
							//var preval = prevals.trim();
							//console.log('Trimm---'+preval);
							if (cFildName[1] != undefined) {
								var cFildVal = cFildName[1].trim();
								//console.log(cFname);
								//var preval = $("#"+cFname).val();
								//console.log(preval);
								// if(preval==cFildVal){
								// console.log(sn);
								// }
							}

							cFname = cFname.trim();
							// if(preval!=null){ preval = preval.trim(); }
							if (preval != null) {
								preval = $.trim(preval);
							}

							//text.replace("lollypops", "marshmellows");
							// relevantConditions = relevantConditions.replace("=",'"=="');
							relevantConditions = relevantConditions.replace("=", '"=="');
							relevantConditions = relevantConditions.replace(">==", '">="');
							relevantConditions = relevantConditions.replace("<==", '"<="');
							relevantConditions = relevantConditions.replace("!==", '"!="');
							var replacedVal = '"' + relevantConditions.replace(cFname, preval) + '"';
							//var replacedVal = '`${"'+relevantConditions.replace(cFname, preval)+'"}`';
							var replacedVal = replacedVal.trim();
							//var content = replacedVal;
							releventJson[cFname + relObjKey] = preval ? '"' + preval + '"' : '""';
							relObjKey++;
							//console.log('------');
							// var content=eval(replacedVal);

							// //console.log(content);
							// if(content){
							// 	//console.log(replacedVal);
							// 	// console.log('true ho rha h');
							// 	showQuestion=true;
							// 	false;
							// }else{
							// 	// console.log('false ho rha h');
							// 	showQuestion=false;
							// 	false;
							// }
							// //console.log(showQuestion);
							// if(showQuestion==false){
							// 	//console.log('ffffailed');
							// 	fStatus="failed";
							// }
						}

						//console.log($("#".relevantConditions).val());
					});
				}

				//console.log("Rj-" + releventJson);
				//console.log("Rv-" + relevantValChk); // $("#this_study").val()==1 & $("#q_8").val()!=5
				//console.log($("#this_study").val()==1 & $("#q_8").val()!=5);
				if (relevantValChk != "") {
					replacedVal_sss = releventChecking(releventJson, relevantValChk);
					var content = eval(replacedVal_sss);
					//console.log(content);
					if (content) {
						//console.log(replacedVal);
						// console.log('true ho rha h');
						showQuestion = true;
						//qcheckPrev.push(prevBtn);
						false;
					} else {
						// console.log('false ho rha h');
						showQuestion = false;

						false;
					}
				} else {
					showQuestion = true;

				}

				if (relevantVal == "") {
					//qcheckPrev.push(prevBtn);
					showQuestion = true;
				}

				if (showQuestion == false) {
					fStatus = "failed";
				}

				if (fStatus != "failed") {
					showStatus = sn;
					break;
				} else {
					current_fs.hide();
					
				}
			}
		} else {
			showQuestion = "next";
			//showStatus=num;
		}
		/* if (showQuestion === "next") {
			// console.log(current_fs);
			// console.log(next_fs);
			qcheckPrev.push(prevBtn);
			console.log(qcheckPrev);
			//current_fs.hide();
			next_fs.show();
		} */
		//This condition apply for 15-sep-2024
		if (showQuestion === "next" && enterdata!='') {
			 //console.log(enterdata);
			next_fs.show();
			qcheckPrev.push(prevBtn);
			
		}

		if(showStatus>0 && enterdata!=''){
			qcheckPrev.push(prevBtn);
		}
		//console.log(qcheckPrev);
		//////////////////////////////////
		if (showStatus > 0) {
			//qcheckPrev.push(prevBtn);
			$("#ss" + showStatus).show();
		}


		current_fs.hide();
		animating = false;

		//START DON`T KNOW CONDITIONS [[ CONSTRAINT ]]
		var cRelv = $("#relevant" + btnsn).attr("data-constraints");
		var inputType = $("#relevant" + btnsn).attr("data-inputType");
		if (cRelv != "" && inputType == "select_multiple") {
			//alert(cRelv);

			//[[ CONSTRAINT ]]
			//var ss = "Selected!=5 | count=1";
			var dntKnow = "success";
			var cRelv1 = cRelv.split(' ');
			var cn = cRelv1[0];
			var cnd = cn.toLowerCase();
			//console.log(cRelv1[0]);

			var ss2 = cnd.split('!=');
			var cnd2 = 'selected!="' + ss2[1] + '"';

			var ssval = enterdata; //"1,2,3,4,5";
			//alert(ssval);
			var ssvalArr = ssval; //ssval.split(',');
			if (ssvalArr.length > 1) {
				//console.log(ssvalArr);
				$.each(ssvalArr, function(i, v) {
					var vr = '"' + v.toLowerCase() + '"';
					var cndf = cnd2.replace('selected', vr);
					var fss = eval(cndf);
					if (fss) {
						//console.log('sss');
						//dntKnow = "success";
					} else {
						//console.log('failed');
						//alert('failed');
						dntKnow = "failed";
						// current_fs.show();
						// next_fs.hide();
						// return false;
					}
					//console.log(cndf);
				});
			} else {
				console.log('success');
				dntKnow = "success";
			}
			ssvalArr = "";
		}else{
		//	console.log("check multiple error");
		}


		$('.error_messaged').html('');
		//alert(enterdata);
		if (dntKnow == "failed") {

			$(".error_messaged").html('If you are selecting Do not know then you cannot select other option and if you are selecting other option then cannot select Do not know.');
			$(".error_messaged").addClass("ssanimation");
			current_fs.show();
			next_fs.hide();
			//return false;
		}

		if (constantErr == 'false') {

			$(".error_messaged").html(isConstraintsMsg);
			$(".error_messaged").addClass("ssanimation");
			current_fs.show();
			next_fs.hide();
			//return false;
		}


		//END DON`T KNOW CONDITIONS

		$('.error_message').html('');
		//alert(enterdata);
		if (enterdata == "") {
			
			$(".error_message").html('This field is required.');
			$(".error_message").addClass("ssanimation");
			current_fs.show();
			next_fs.hide();
			$("#ss" + showStatus).hide();
			//qcheckPrev.pop();

		}
	
	});

	$('.error_message').on('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
		$('.error_message').delay(200).removeClass('ssanimation');
		// $('#error_message').delay(200).html('');
	});
	 
	$('.error_messaged').on('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
		$('.error_messaged').delay(200).removeClass('ssanimation');
		// $('#error_message').delay(200).html('');
	});


	
	// PREVIOUSE BUTTON WORK
	$(".previous").click(function() {
		if (animating) return false;
		animating = true;

		current_fs = $(this).parent().parent();
		//previous_fs = $(this).parent().parent().prev();
		//let arr = ["ss1", "ss2"];
		
		let lastValue = qcheckPrev.pop();
		if (lastValue === undefined) {  
			previous_fs = $(this).parent().parent().prev();
		} else {
			previous_fs = $('#' + lastValue);
		}

		//de-activate current step on progressbar
		$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

		//show the previous fieldset
		previous_fs.show();

		current_fs.hide();
		animating = false;
		/*
		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
			step: function(now, mx) {
				//as the opacity of current_fs reduces to 0 - stored in "now"
				//1. scale previous_fs from 80% to 100%
				scale = 0.8 + (1 - now) * 0.2;
				//2. take current_fs to the right(50%) - from 0%
				left = ((1-now) * 50)+"%";
				//3. increase opacity of previous_fs to 1 as it moves in
				opacity = 1 - now;
				current_fs.css({'left': left});
				previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
			}, 
			duration: 500, 
			complete: function(){
				current_fs.hide();
				animating = false;
			}, 
			//this comes from the custom easing plugin
			easing: 'easeOutQuint'
		});
		
		*/
	});
</script>


<script>
	// SUBMIT ALL FORM DATA ON SERVER
	$(document).ready(function() {

		$('#msform button:last').attr('id', 'formSubmit');
		$('#formSubmit').html('Submit');
		$("#formSubmit").on("click", function() {
			var fdata = $('#msform').serializeArray();
			console.log(fdata);
			let sessionID = "<?= $cookieId; ?>";
			var surveyId = "<?= $survey_id; ?>";
			var languageId = "<?= $languageid; ?>";
			var survey_name = "<?= $survey_name; ?>";
			var GPS_latitude_start = $('#GPS_latitude_start').val();
			var GPS_longitude_start = $('#GPS_longitude_start').val();
			var allRepeatGroupNames = $('#allRepeatGroup').val();
			var start_date_time = $('#start_date_time').val();
			
			// CREATING SINGLE SCREEN MULTIPLE QUESTION GROUP DATA
			var allSingleGroup = $("#allSingleGroup").val();
			var allsGroup = allSingleGroup.split(",");


			var allsGroupData = {};
			//console.log(allSingleGroup);
			if (allSingleGroup != "") {
				$.each(allsGroup, function(i, v) {
					var sGroupObj = {};
					$('.' + v).each(function() {
						var fname = $(this).data("id");
						var fvalue = $(this).val();
						if ($.isArray(fvalue)) {
							fvalue = fvalue.join(",");
						}
						sGroupObj[fname] = fvalue;
					});
					allsGroupData[v] = sGroupObj;
				});
			}
			//console.log(fdata);
			
			var allsGroupDatass = allsGroupData;
			
			//CREATING REPEAT GROUP MULTIPLE QUESTION DATA
		
            var allRepeatGroup = $("#allRepeatGroup").val();
            var allsRepeat = allRepeatGroup.split(",");
            var allsRepeatGroupDatas={};

            if (allRepeatGroup != "") {
                $.each(allsRepeat, function(i, v) {
                    var rGroupArray = [];
                    var elements = $('.' + v);

                    // Initialize array with null values up to the highest index
                    var highestIndex = Math.max(...elements.map(function() {
                        return parseInt($(this).data('index'), 10);
                    }).get());
                    for (var j = 0; j <= highestIndex; j++) {
                        rGroupArray[j] = null;
                    }

                    elements.each(function() {
                        var rGroupObj = {};
                        var index = $(this).data('index');
                        var subElements = $(this).find('input, select');

                        subElements.each(function() {
                            var fname = $(this).data("id");
                            var fvalue = $(this).val();

                            if ($.isArray(fvalue)) {
                                fvalue = fvalue.join(",");
                            }
                            rGroupObj[fname] = fvalue;
                        });

                        rGroupArray[index] = rGroupObj;
                    });
                    // Remove any null entries
                    rGroupArray = rGroupArray.filter(function(entry) {
                        return entry !== null && Object.keys(entry).length > 0;
                    });

                    allsRepeatGroupDatas[v] = rGroupArray;
                });
            }
            console.log("All Repeat Group Data: ", allsRepeatGroupDatas);
			var allsRepeatGroupDatas = allsRepeatGroupDatas;
			console.log("Group Data: ", allsRepeatGroupDatas);
			
			//SAVE DATA ON SERVER
			$.ajax({
				url: "ssajax.php",
				type: "post",
				data: {
					formdata: fdata,
					GPS_latitude_start: GPS_latitude_start,
					GPS_longitude_start: GPS_longitude_start,
					surveyId: surveyId,
					languageId: languageId,
					sessionID: sessionID,
					survey_name: survey_name,
					allsRepeatGroupDatas: allsRepeatGroupDatas,
					start_date_time: start_date_time,
					allsGroupData: allsGroupDatass
				},
				beforeSend: function() {
                    $('.loading-indicator').addClass('active');
                },
				success: function(res) {
					console.log(res);
					$('.loading-indicator').removeClass('active');
					 var statusResponse = JSON.parse(res);
					if (statusResponse.success == "1") {
						document.cookie = "cookie_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
						//window.open('https://unicef.indevconsultancy.in/mis/welcome_skip.php', '_blank');
						$('#successModal').modal('show');
					} else {
						console.log(res);
						window.location.reload();
					}   
				}

			});


		});
	})
</script>

<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-keyboard="false" data-bs-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header border-0">
				<a href="https://unicef.indevconsultancy.in/" class="btn-close fs-4"></a>
			</div>
			<div class="modal-body">
				<div class="text-center mb-4">
					<svg xmlns="http://www.w3.org/2000/svg" height="100px" viewBox="0 -960 960 960" width="100px" fill="#8cc63f">
						<path d="m421-298 283-283-46-45-237 237-120-120-45 45 165 166Zm59 218q-82 0-155-31.5t-127.5-86Q143-252 111.5-325T80-480q0-83 31.5-156t86-127Q252-817 325-848.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 82-31.5 155T763-197.5q-54 54.5-127 86T480-80Zm0-60q142 0 241-99.5T820-480q0-142-99-241t-241-99q-141 0-240.5 99T140-480q0 141 99.5 240.5T480-140Zm0-340Z" />
					</svg>
					<h3 class="fw-bold fs-2" style="color: #003051;">Your response has been successfully submitted.</h3>
					<h4 class="mt-4 fs-5 fw-bold text-dark">Thank you for your participation!</h4>
					
					<a href="https://unicef.indevconsultancy.in/" class="btn btn-primary px-4"> Ok</a>
				</div>
			</div>
		</div>
	</div>
</div>