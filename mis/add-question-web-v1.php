<?php include('includes/config.php'); ?>
<?php define("title", "Add New Question | MQUAD"); ?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>
<?php include('includes/functions.php'); ?>
<?php
$survey_id = $_GET['survey_id'];
?>
<link rel="stylesheet" href="css/form-builder.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" /> -->
<style>
	.qField>input[type="checkbox"] {
		margin: 0px;
	}

	.edit-main-container>.edit-wrapper-container>div {
		min-width: 48.6%;
		margin: 7px 5px;
		display: inline-block;
	}

	.fw {
		width: 100%;
	}

	.d-none {
		display: none !important;
	}

	.error {
		border: 1px solid red;
		box-shadow: 0px 0px 3px 0px red;
	}

	.breadcrumb {
		margin-bottom: 0px;
	}

	.panel {
		margin-bottom: 00px;
	}

	#ssid {
		max-height: 495px;
		overflow: auto;
	}

	#downloadExcel {
		margin-left: 5px;
	}

	#publishwebform {
		margin-right: 5px;
	}



	.close-form,
	.edit-form,
	.close-group,
	.rpedit-group {

		font-size: 14px;
		padding: 5px;
		width: 25px;
		height: 25px;
		line-height: 14px;
		margin: 2px;
		text-align: center;
		border-radius: 4px;
		cursor: pointer;
	}

	.multiinputs-div .del-btn {
		order: 4;
	}

	.nicescroll-rails {
		display: none !important;
	}

	#sidebar {
		z-index: 2;
	}

	.tgka_margin {
		margin-left: 10px;
	}

	.edit-wrapper-container .form-check-input {
		margin-left: 25px;
		margin-right: 5px;
		margin-top: 2px;
		border: 1.5px solid #797979;
	}

	.form-check-label {
		color: #797979
	}

	.fa-circle-info:before,
	.fa-info-circle:before {
		font-size: 11px;
	}

	.edit-wrapper-container .form-check-input:first-child {
		margin-left: 0px;
		margin-right: 5px;
	}

	p {
		margin-bottom: 0;
	}

	#appendfromdetail {
		width: 100%;
		margin-top: 20px;
		display: none;
	}

	.credits.pfixed {
		z-index: 1;
	}

	.form-error {
		position: absolute;
		right: 10%;
		top: 6px;
		z-index: 1;
		color: #dc3545;
		display: none;
	}

	.form-success-notice {
		position: absolute;
		right: 10%;
		top: 6px;
		z-index: 1;
		display: none;
	}

	input::placeholder {
		color: #9f9f9f !important;
		opacity: 1;
	}

	.form-control,
	.select2-container .select2-selection--multiple,
	.form-check-label,
	.form-select {
		color: #414040;
	}
	
	.label-text {
		display: inherit;          
		white-space: normal;     
		word-wrap: break-word;   
		word-break: break-word;  
	}
</style>
<?php 


?>
<div id="pre-load" class="loading-indicator">
	<div id="loader" class="loader">
		<div class="loader-container">
			<div class='loader-icon'><img src="https://mquad.org/mis/img/mquad-logo.png" alt="">
			</div>
		</div>
	</div>
</div>

<section id="main-content">
	<section class="wrapper">

		<div class="row mb-3">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<!-- <li class="breadcrumb-item"><i class="icon_document_alt"></i>List Form </li> -->
						<li class="breadcrumb-item" aria-current="page"><i class="fa fa-globe"></i> Web Form</li>
					</ol>
				</nav>
			</div>
		</div>

		<div class="row mb-0">
			<div class="col-md-12">
				<section class="panel">
					<div class="">

						<?php
						$surveyqry = mysqli_query($conn, "SELECT survey.status as form_status,survey_name,clients.name as client_name FROM `survey` left join clients on survey.client_id=clients.id where survey.id='" . $survey_id . "'");
						$surveydata = mysqli_fetch_array($surveyqry);
						$survey_name = $surveydata['survey_name'];
						$client_name = $surveydata['client_name'];
						$form_status = $surveydata['form_status'];
						if($form_status == '1'){
							$updateSQL = mysqli_query($conn,"update survey set status = 0 WHERE id='" . $survey_id . "'");
						}

						$sqlquery = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data from survey_data_monitoring where survey_name_id = '" . $survey_id . "' ");
						$surveycount = mysqli_fetch_object($sqlquery);
						$totalSurvey = $surveycount->total_survey_data;
						?>

						<!--|| Client Name: <?= $client_name ?>  -->


					</div>
				</section>
			</div>
		</div>
		<div class="row g-2">
			<?php /*?><div class="col-md-3">
			<section class="panel">
				<div class="card">
					<header class="panel-heading">Questions</header>
					
				</div>
				</section>
			</div><?php */ ?>
			<div class="col-md-12">
				<section class="panel">
					<header class="panel-heading py-0">
						<div class=" row align-items-center">
							<div class="col-md-3">
								Form Name: <?php echo $survey_name; ?>
							</div>
							<div class="col-md-9">
								<a href="javascript:void(0)" class="btn btn-success pull-right btn-sm  my-1 downloadExcel" id="downloadExcel"> Download Excel Form</a>
								<a href="javascript:void(0)" class="btn btn-success pull-right btn-sm my-1 savequesbtn" id="savequesbtn" data-id="<?= $totalSurvey; ?>">Validate Form</a>
								<a href="javascript:void(0)" class="btn btn-success pull-right btn-sm my-1 publishwebform" id="publishwebform">Generate Form</a>
							</div>
						</div>
					</header>
					<input type="hidden" id="surveyId" value="<?= @$_GET['survey_id']; ?>" />
					<div class="panel-body" id="ssid">

						<div id="questionArea">
							<div class="question-area-container">


							</div>
						</div>
						<div>
							<div class="w-100 d-flex align-items-center   mb-3">
								<div>
									<h4 class="fw-bold me-3 mb-0">Add New Question</h4>
								</div>
								<div>
									<button class="btn btn-success btn-sm" id="toggleButton" style="border-radius: 50%; width: 35px; height: 35px;">
										<i class="fa fa-plus pe-2 pt-1"></i>
									</button>
								</div>
							</div>

							<div id="appendfromdetail">
								<div class="add-fields add-fields-new">
									<a href="javascript:void(0);" class="select-single addsavequesbtn"><i class="fa fa-chevron-circle-down"></i> Choice Based Question </a>
									<a href="javascript:void(0);" class="number addsavequesbtn"><i class="fa fa-hashtag"></i> Numeric Open Ended Question </a>
									<a href="javascript:void(0);" class="text addsavequesbtn"><i class="fa fa-file-text"></i> Text Open Ended Question </a>
									<a href="javascript:void(0);" class="note addsavequesbtn"><i class="fa fa-sticky-note"></i> Note </a>
									<a href="javascript:void(0);" class="date addsavequesbtn"><i class="fa fa-calendar"></i> Date </a>
									<a href="javascript:void(0);" class="audio addsavequesbtn"><i class="fa fa-file-audio-o"></i> Audio </a>
									<a href="javascript:void(0);" class="video addsavequesbtn"><i class="fa fa-file-video-o"></i> Video </a>
									<a href="javascript:void(0);" class="picture addsavequesbtn"><i class="fa fa-picture-o"></i> Image </a>
									<a href="javascript:void(0);" class="gps_button addsavequesbtn"><i class="fa fa-map-marker"></i> Location</a>
									<a href="javascript:void(0);" class="b-group addsavequesbtn"><i class="fa fa-object-group"></i> Create Group </a>
									<a class="b-regroup addsavequesbtn" href="javascript:void(0);"><i class="fa fa-table"></i>Create Roster </a>
								</div>
							</div>
							<div class=" row align-items-center">
								<div class="col-md-12">
									<a href="javascript:void(0)" class="btn btn-success pull-right btn-sm  my-1 downloadExcel" id="downloadExcel"> Download Excel Form</a>
									<a href="javascript:void(0)" class="btn btn-success pull-right btn-sm my-1 savequesbtn" id="savequesbtn" data-id="<?= $totalSurvey; ?>">Validate Form</a>
									<a href="javascript:void(0)" class="btn btn-success pull-right btn-sm my-1 publishwebform" id="publishwebform">Generate Form</a>
								</div>
							</div>
						</div>

					</div>
				</section>
			</div>
		</div>
	</section>
</section>
</div>

<!-- <div class="col-md-2">
				<section class="panel">
					<div class="border-0">
						<header class="panel-heading py-0">Types</header>
						<div class="list-group">
							<div class="panel-body p-2">
								<a class="list-group-item number" href="javascript:void(0);"><i class="fa fa-hashtag"></i> Number</a>
								<a class="list-group-item select-single" href="javascript:void(0);"><i class="fa fa-chevron-circle-down"></i> Choice Based Question</a>
								<a class="list-group-item text" href="javascript:void(0);"><i class="fa fa-file-text"></i> Text</a>
								<a class="list-group-item note" href="javascript:void(0);"><i class="fa fa-sticky-note"></i> Note</a>
								<a class="list-group-item date" href="javascript:void(0);"><i class="fa fa-calendar"></i> Date</a>
								<a class="list-group-item b-group" href="javascript:void(0);"><i class="fa fa-object-group"></i> Create Group</a>
								<a class="list-group-item b-regroup" href="javascript:void(0);"><i class="fa fa-object-group"></i> Create Roster  </a>
								<a class="list-group-item audio" href="javascript:void(0);"><i class="fa fa-file-audio-o"></i> Audio</a>
								<a class="list-group-item video" href="javascript:void(0);"><i class="fa fa-file-video-o"></i> Video</a>
								<a class="list-group-item gps_button" href="javascript:void(0);"><i class="fa fa-map-marker"></i> Location</a>
								<a class="list-group-item picture" href="javascript:void(0);"><i class="fa fa-picture-o"></i> Image</a>
								<!--	<a class="list-group-item select-single" href="javascript:void(0);"><i class="fa fa-camera" ></i> Camera</a>
						<a class="list-group-item select-single" href="javascript:void(0);"><i class="fa fa-clock-o" ></i> Time</a>
						<a class="list-group-item select-single" href="javascript:void(0);"><i class="fa fa-calendar" ></i> Datetime</a>
						<a class="list-group-item select-single" href="javascript:void(0);"><i class="fa fa-eye-slash" ></i> Hidden</a>
						<a class="list-group-item select-single" href="javascript:void(0);"><i class="fa fa-calculator" ></i> Calculate</a>
						<a class="list-group-item select-single" href="javascript:void(0);"><i class="fa fa-desktop" ></i> Single-Screen Group</a>
							</div>
						</div>
					</div>
				</section>
			</div>
	</div>-->
</section>
<!--main content end-->

<?php include_once('includes/footer.php'); ?>

<script>
	function blockBackspaceKey(event) {
		const charr = event.key;
		if (charr === ' ') {
			event.preventDefault();
			return;
		}
	}


	function blockInvalidKeys(event) {
		const char = event.key;
		const value = event.target.value;
		const isFirstChar = value.length === 0;

		const allowedKeys = [
			'Backspace', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'
		];

		if (allowedKeys.includes(char)) {
			clearErrorMessage();
			return;
		}

		if (char === ' ') {
			displayErrorMessage('Space(s) are not allowed.');
			event.preventDefault();
			return;
		}

		/* getModifierState not defined */
		// if (event.getModifierState('CapsLock')) {
		// displayErrorMessage('Caps Lock is on. Only lowercase letters are allowed.');
		// event.preventDefault();
		// return;
		// }

		if (char >= 'A' && char <= 'Z') {
			displayErrorMessage('Only lowercase letters are allowed.');
			event.preventDefault();
			return;
		}

		if (isFirstChar && char >= '0' && char <= '9') {
			displayErrorMessage('First character cannot be a number.');
			event.preventDefault();
			return;
		}

		if (!(char >= 'a' && char <= 'z') && !(char >= '0' && char <= '9') && !(char == '_')) {
			displayErrorMessage('Only lowercase letters,underscore and numbers(except first) are allowed.');
			event.preventDefault();
		} else {
			clearErrorMessage();
		}
	}

	function validateInput(inputField) {
		const value = inputField.value.trim();
		inputField.value = value.toLowerCase();
	}

	function displayErrorMessage(message) {
		const errorMessageElement = document.getElementById('errorMessage');
		errorMessageElement.textContent = message;
	}

	function clearErrorMessage() {
		const errorMessageElement = document.getElementById('errorMessage');
		errorMessageElement.textContent = '';
	}

	$(document).ready(function() {

		var message = '<?= $_REQUEST['res'] ?>';
		if (message == "success") {
			customAlert('Question saved successfully', icon = 'success');
		}
		if (message == "warning") {
			customAlert('Please add a question', icon = 'warning');
		}
		if (typeof window !== 'undefined') {
			var urlParams = new URLSearchParams(window.location.search);
			urlParams.delete('res');
			var newUrl = window.location.pathname + '?' + urlParams.toString();
			window.history.replaceState({}, document.title, newUrl);
		}

		$(".question-area-container, .question-area-container > fieldset").sortable({
			revert: true
		});



		var edit_number = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div class="w-100 d-flex  checkbox-align">
						<span>
							<input class="form-check-input webform-checkbox   q_required" type="checkbox" name="required" value="yes">
							<label class="form-check-label">
								Required 
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_read_only" type="checkbox" name="read_only" value="yes">
							<label class="form-check-label">
								Read Only
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_unique_id" type="checkbox" name="unique_id" value="yes">
							<label class="form-check-label">
								Unique Id
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_preserve" type="checkbox" name="preserve" value="yes">
							<label class="form-check-label">
								Preserve
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_deidentify" type="checkbox" name="deidentify" value="yes">
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
						<input type="text" class="form-control mt-1  q_name" name="name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>

						<input type="hidden" class="form-control mt-1  q_type" name="type" value="number">
					</div>
					<div class="d-block">
						<label class="form-check-label">
							Label<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_label" name="label">
					</div>
				<!--	<div class="d-block">
						<label class="form-check-label">
							Short Label
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_dictionary_label" name="dictionary_label">
					</div>-->
					<div>
						<label class="form-check-label">
							Hint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_hint" name="hint">
					</div>
					<div>
						<label class="form-check-label">
							Limit<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);">
					</div>
					<div>
						<label class="form-check-label">
							Constraint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint" name="constraint" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Constraint Message
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint_message" name="constraint_message">
					</div>
					<div>
						<label class="form-check-label mb-2">
							Paradata
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_paradata" name="paradata" value="T,G,K">
						-->
						<div>
							<input class="form-check-input webform-checkbox   q_paradata" type="checkbox" name="paradata" value="T">
							<label class="form-check-label">
								Timestamp
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="G">
							<label class="form-check-label">
								GPS
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="K">
							<label class="form-check-label">
								Key-Stroke
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="A"> 
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
						<select class="form-control mt-1  q_appearance" name="appearance">
							<?php echo getAppearance($conn, "number"); ?>
						</select>
					</div>
					<div>
						<label class="form-check-label">
							Choice Filter
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_choice_filter" name="choice_filter">
					</div>
				<!--	<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>-->
					<input type="hidden" class="q_repeat_count" name="repeat_count">
					<div>
						<label class="form-check-label">
							Calculation
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_calculation" name="calculation" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Default Response
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_dafault_response" name="dafault_response" oninput="validateDefaultResponse(this)">
					</div>
					<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
						<!--	<label class="form-check-label">
								Manual 
							</label>
							<input class="form-check-input webform-checkbox   q_manual_relevant" type="checkbox" name="manual_relevant" value="yes">-->
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
						</div>
					<!-- <div class="row">
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
									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div>-->
					</div>
				</div>
			</div>`;
		var edit_text = `<div class="edit-main-container">
                <div class="edit-wrapper-container"> 
					<div class="w-100 d-flex  checkbox-align">
						<span>
							<input class="form-check-input webform-checkbox   q_required" type="checkbox" name="required" value="yes">
							<label class="form-check-label">
								Required 
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_read_only" type="checkbox" name="read_only" value="yes">
							<label class="form-check-label">
								Read Only
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_unique_id" type="checkbox" name="unique_id" value="yes">
							<label class="form-check-label">
								Unique Id
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_preserve" type="checkbox" name="preserve" value="yes">
							<label class="form-check-label">
								Preserve
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_deidentify" type="checkbox" name="deidentify" value="yes">
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
						<input type="text" class="form-control mt-1  q_name" name="name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="text">
					</div>
					<div  class="d-block">
						<label class="form-check-label">
							Label<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_label" name="label">
					</div>
				<!--	<div class="d-block">
						<label class="form-check-label">
							Short Label
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>

						</label>
						<input type="text" class="form-control mt-1  q_dictionary_label" name="dictionary_label">
					</div>-->
					<div>
						<label class="form-check-label">
							Hint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_hint" name="hint">
					</div>
					<div>
						<label class="form-check-label">
							Limit<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);">
					</div>
					<div>
						<label class="form-check-label">
							Constraint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint" name="constraint" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Constraint Message
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint_message" name="constraint_message">
					</div>
					<div>
						<label class="form-check-label">
							Paradata
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_paradata" name="paradata" value="T,G,K">-->
						
						<div class="checkbox-align">
							<span>
								<input class="form-check-input webform-checkbox   q_paradata" type="checkbox" name="paradata" value="T">
								<label class="form-check-label">
									Timestamp
								</label>
							</span>
							<span>
								<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="G">
								<label class="form-check-label">
									GPS
								</label>
							</span>
							<span>
								<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="K">
								<label class="form-check-label">
									Key-Stroke
								</label>
							</span>
							<span>
								<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="A"> 
								<label class="form-check-label">
									Audio
								</label>
							</span>
						</div>
					</div>
					<div>
						<label class="form-check-label">
							Appearance
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<select class="form-control mt-1  q_appearance" name="appearance">
							<?php echo getAppearance($conn, "text"); ?>
						</select>
					</div>
					<div>
						<label class="form-check-label">
							Choice Filter
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_choice_filter" name="choice_filter">
					</div>
				<!--	<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>-->
					<input type="hidden" class="q_repeat_count" name="repeat_count">
					<div>
						<label class="form-check-label">
							Calculation
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_calculation" name="calculation" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Default Response
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Allow to enter pre-defined special response value. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_dafault_response" name="dafault_response" oninput="validateDefaultResponse(this)">
					</div>
					<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;


		var edit_audio = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div class="w-100 d-flex  checkbox-align">
						<span>
							<input class="form-check-input webform-checkbox   q_required" type="checkbox" name="required" value="yes">
							<label class="form-check-label">
								Required 
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_read_only" type="checkbox" name="read_only" value="yes">
							<label class="form-check-label">
								Read Only
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_unique_id" type="checkbox" name="unique_id" value="yes">
							<label class="form-check-label">
								Unique Id
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_preserve" type="checkbox" name="preserve" value="yes">
							<label class="form-check-label">
								Preserve
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_deidentify" type="checkbox" name="deidentify" value="yes">
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
						<input type="text" class="form-control mt-1  q_name" name="name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="audio">
					</div>
					<div class="d-block">
						<label class="form-check-label">
							Label<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_label" name="label">
					</div>
				<!--	<div class="d-block">
						<label class="form-check-label">
							Short Label
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>

						</label>
						<input type="text" class="form-control mt-1  q_dictionary_label" name="dictionary_label">
					</div>-->
					<div>
						<label class="form-check-label">
							Hint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_hint" name="hint">
					</div>
					<div>
						<label class="form-check-label">
							Limit<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);">
					</div>
					<div>
						<label class="form-check-label">
							Constraint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint" name="constraint" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Constraint Message
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint_message" name="constraint_message">
					</div>
					<div>
						<label class="form-check-label mb-2">
							Paradata
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_paradata" name="paradata" value="T,G,K">-->
						
						<div>
							<input class="form-check-input webform-checkbox   q_paradata" type="checkbox" name="paradata" value="T">
							<label class="form-check-label">
								Timestamp
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="G">
							<label class="form-check-label">
								GPS
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="K">
							<label class="form-check-label">
								Key-Stroke
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="A"> 
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
						<select class="form-control mt-1  q_appearance" name="appearance">
							
						</select>
					</div>-->
					<div>
						<label class="form-check-label">
							Choice Filter
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_choice_filter" name="choice_filter">
					</div>
				<!--	<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>-->
					<input type="hidden" class="q_repeat_count" name="repeat_count">
					<div>
						<label class="form-check-label">
							Calculation
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_calculation" name="calculation" onkeydown="blockBackspaceKey(event)">
					</div>
					<input type="hidden" class="q_dafault_response" name="dafault_response" value="">
				<!--	<div>
						<label class="form-check-label">
							Default Response
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_dafault_response" name="dafault_response" disabled>
					</div>-->
					<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;


		var edit_video = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div class="w-100 d-flex  checkbox-align">
						<span>
							<input class="form-check-input webform-checkbox   q_required" type="checkbox" name="required" value="yes">
							<label class="form-check-label">
								Required 
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_read_only" type="checkbox" name="read_only" value="yes">
							<label class="form-check-label">
								Read Only
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_unique_id" type="checkbox" name="unique_id" value="yes">
							<label class="form-check-label">
								Unique Id
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_preserve" type="checkbox" name="preserve" value="yes">
							<label class="form-check-label">
								Preserve
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_deidentify" type="checkbox" name="deidentify" value="yes">
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
						<input type="text" class="form-control mt-1  q_name" name="name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="video">
					</div>
					<div class="d-block">
						<label class="form-check-label">
							Label<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_label" name="label">
					</div>
				<!--	<div class="d-block">
						<label class="form-check-label">
							Short Label
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>

						</label>
						<input type="text" class="form-control mt-1  q_dictionary_label" name="dictionary_label">
					</div>-->
					<div>
						<label class="form-check-label">
							Hint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_hint" name="hint">
					</div>
					<div>
						<label class="form-check-label">
							Limit<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);">
					</div>
					<div>
						<label class="form-check-label">
							Constraint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint" name="constraint" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Constraint Message
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint_message" name="constraint_message">
					</div>
					<div>
						<label class="form-check-label mb-2">
							Paradata
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_paradata" name="paradata" value="T,G,K">-->
						
						<div>
							<input class="form-check-input webform-checkbox   q_paradata" type="checkbox" name="paradata" value="T">
							<label class="form-check-label">
								Timestamp
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="G">
							<label class="form-check-label">
								GPS
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="K">
							<label class="form-check-label">
								Key-Stroke
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="A"> 
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
						<select class="form-control mt-1  q_appearance" name="appearance">
							
						</select>
					</div>-->
					<div>
						<label class="form-check-label">
							Choice Filter
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_choice_filter" name="choice_filter">
					</div>
				<!--	<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>-->
					<input type="hidden" class="q_repeat_count" name="repeat_count">
					<div>
						<label class="form-check-label">
							Calculation
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_calculation" name="calculation" onkeydown="blockBackspaceKey(event)">
					</div>
					<input type="hidden" class="q_dafault_response" name="dafault_response" value="">
				<!--	<div>
						<label class="form-check-label">
							Default Response
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<input type="text" class="form-control mt-1  q_dafault_response" name="dafault_response" disabled>
					</div>-->
					<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;

		var edit_gps_button = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div class="w-100 d-flex  checkbox-align">
						<span>
							<input class="form-check-input webform-checkbox   q_required" type="checkbox" name="required" value="yes">
							<label class="form-check-label">
								Required 
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_read_only" type="checkbox" name="read_only" value="yes">
							<label class="form-check-label">
								Read Only
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_unique_id" type="checkbox" name="unique_id" value="yes">
							<label class="form-check-label">
								Unique Id
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_preserve" type="checkbox" name="preserve" value="yes">
							<label class="form-check-label">
								Preserve
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_deidentify" type="checkbox" name="deidentify" value="yes">
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
						<input type="text" class="form-control mt-1  q_name" name="name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="gps_button">
					</div>
					<div class="d-block">
						<label class="form-check-label">
							Label<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_label" name="label">
					</div>
				<!--	<div class="d-block">
						<label class="form-check-label">
							Short Label
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>

						</label>
						<input type="text" class="form-control mt-1  q_dictionary_label" name="dictionary_label">
					</div>-->
					<div>
						<label class="form-check-label">
							Hint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_hint" name="hint">
					</div>
					<div>
						<label class="form-check-label">
							Limit<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);">
					</div>
					<div>
						<label class="form-check-label">
							Constraint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint" name="constraint" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Constraint Message
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint_message" name="constraint_message">
					</div>
					<div>
						<label class="form-check-label mb-2">
							Paradata
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_paradata" name="paradata" value="T,G,K">-->
					
						
						<div>
							<input class="form-check-input webform-checkbox   q_paradata" type="checkbox" name="paradata" value="T">
							<label class="form-check-label">
								Timestamp
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="G">
							<label class="form-check-label">
								GPS
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="K">
							<label class="form-check-label">
								Key-Stroke
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="A"> 
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
						<select class="form-control mt-1  q_appearance" name="appearance">
							
						</select>
					</div>-->
					<div>
						<label class="form-check-label">
							Choice Filter
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_choice_filter" name="choice_filter">
					</div>
				<!--	<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>-->
					<input type="hidden" class="q_repeat_count" name="repeat_count">
					<div>
						<label class="form-check-label">
							Calculation
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_calculation" name="calculation" onkeydown="blockBackspaceKey(event)">
					</div>
					<input type="hidden" class="q_dafault_response" name="dafault_response" value="">
				<!--	<div>
						<label class="form-check-label">
							Default Response
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<input type="text" class="form-control mt-1  q_dafault_response" name="dafault_response" disabled>
					</div>-->
					<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;


		var edit_picture = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div class="w-100 d-flex  checkbox-align">
						<span>
							<input class="form-check-input webform-checkbox   q_required" type="checkbox" name="required" value="yes">
							<label class="form-check-label">
								Required 
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_read_only" type="checkbox" name="read_only" value="yes">
							<label class="form-check-label">
								Read Only
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_unique_id" type="checkbox" name="unique_id" value="yes">
							<label class="form-check-label">
								Unique Id
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_preserve" type="checkbox" name="preserve" value="yes">
							<label class="form-check-label">
								Preserve
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_deidentify" type="checkbox" name="deidentify" value="yes">
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
						<input type="text" class="form-control mt-1  q_name" name="name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="picture">
					</div>
					<div class="d-block">
						<label class="form-check-label">
							Label<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_label" name="label">
					</div>
				<!--	<div class="d-block">
						<label class="form-check-label">
							Short Label
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>

						</label>
						<input type="text" class="form-control mt-1  q_dictionary_label" name="dictionary_label">
					</div>-->
					<div>
						<label class="form-check-label">
							Hint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_hint" name="hint">
					</div>
					<div>
						<label class="form-check-label">
							Limit<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);">
					</div>
					<div>
						<label class="form-check-label">
							Constraint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint" name="constraint" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Constraint Message
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint_message" name="constraint_message">
					</div>
					<div>
						<label class="form-check-label mb-2">
							Paradata
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_paradata" name="paradata" value="T,G,K">-->
					
						
						<div>
							<input class="form-check-input webform-checkbox   q_paradata" type="checkbox" name="paradata" value="T">
							<label class="form-check-label">
								Timestamp
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="G">
							<label class="form-check-label">
								GPS
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="K">
							<label class="form-check-label">
								Key-Stroke
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="A"> 
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
						<select class="form-control mt-1  q_appearance" name="appearance">
							<?php echo getAppearance($conn, "picture"); ?>
						</select>
					</div>
					<div>
						<label class="form-check-label">
							Choice Filter
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_choice_filter" name="choice_filter">
					</div>
				<!--	<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>-->
					<input type="hidden" class="q_repeat_count" name="repeat_count">
					<div>
						<label class="form-check-label">
							Calculation
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_calculation" name="calculation" onkeydown="blockBackspaceKey(event)">
					</div>
					<input type="hidden" class="q_dafault_response" name="dafault_response" value="">
				<!--	<div>
						<label class="form-check-label">
							Default Response
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<input type="text" class="form-control mt-1  q_dafault_response" name="dafault_response" disabled>
					</div>-->
					<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;

		var edit_note = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div class="w-100 d-flex  checkbox-align">
						<span>
							<input class="form-check-input webform-checkbox   q_required" type="checkbox" name="required" value="yes">
							<label class="form-check-label">
								Required 
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_read_only" type="checkbox" name="read_only" value="yes">
							<label class="form-check-label">
								Read Only
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_unique_id" type="checkbox" name="unique_id" value="yes">
							<label class="form-check-label">
								Unique Id
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_preserve" type="checkbox" name="preserve" value="yes">
							<label class="form-check-label">
								Preserve
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_deidentify" type="checkbox" name="deidentify" value="yes">
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
						<input type="text" class="form-control mt-1  q_name" name="name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="note">
					</div>
					<div class="d-block">
						<label class="form-check-label">
							Label<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_label" name="label">
					</div>
				<!--	<div class="d-block">
						<label class="form-check-label">
							Short Label
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_dictionary_label" name="dictionary_label">
					</div>-->
					<div>
						<label class="form-check-label">
							Hint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_hint" name="hint">
					</div>
					<!--<div>
						<label class="form-check-label">
							Limit<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);">
					</div>-->
					<input type="hidden" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);" value="0">
					<div>
						<label class="form-check-label">
							Constraint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint" name="constraint" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Constraint Message
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>

						</label>
						<input type="text" class="form-control mt-1  q_constraint_message" name="constraint_message">
					</div>
					<div>
						<label class="form-check-label mb-2">
							Paradata
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_paradata" name="paradata" value="T,G,K">-->
					
						<div>
							<input class="form-check-input webform-checkbox   q_paradata" type="checkbox" name="paradata" value="T">
							<label class="form-check-label">
								Timestamp
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="G">
							<label class="form-check-label">
								GPS
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="K">
							<label class="form-check-label">
								Key-Stroke
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="A"> 
							<label class="form-check-label">
								Audio
							</label>
						</div>
					</div>
					<!--<div>
						<label class="form-check-label">
							Appearance
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Controls visual presentation of questions." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<select class="form-control mt-1  q_appearance" name="appearance">
							
						</select>
					</div>-->
					<div>
						<label class="form-check-label">
							Choice Filter
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_choice_filter" name="choice_filter">
					</div>
					<!--<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>-->
					<input type="hidden" class="q_repeat_count" name="repeat_count">
					<div>
						<label class="form-check-label">
							Calculation
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_calculation" name="calculation" onkeydown="blockBackspaceKey(event)">
					</div>
					<input type="hidden" class="q_dafault_response" name="dafault_response" value="">
				<!--	<div>
						<label class="form-check-label">
							Default Response
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_dafault_response" name="dafault_response" disabled>
					</div>-->
					<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;
			
			
		<?php
			$sql = mysqli_query($conn,"SELECT question_json FROM questionnaires WHERE survey_id = '".$survey_id."'");
			$data = mysqli_fetch_object($sql);
			$jsondata = $data->question_json;
			$choicerelation = '<option value="">Select an option</option>';
			if($jsondata != '[]'){
				$jsonn = json_decode($jsondata);
				$fieldNameArr = array();
				foreach ($jsonn->questions as $key => $questionObj) {
					
					$type = $questionObj->type;
					
					if($type == 'select_one' || $type == 'select_multiple'){
						$fieldNameArr[] = $questionObj->name;
					}
				}
				
				if(!empty($fieldNameArr)){
					foreach($fieldNameArr as $value){
						$choicerelation .='<option value="' .$value. '">' .$value. '</option>';
					}
				}
			}
		?>

		var edit_select_single = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div class="w-100 d-flex  checkbox-align">
						<span>
							<input class="form-check-input webform-checkbox   q_required" type="checkbox" name="required" value="yes">
							<label class="form-check-label">
								Required 
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_read_only" type="checkbox" name="read_only" value="yes">
							<label class="form-check-label">
								Read Only
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_unique_id" type="checkbox" name="unique_id" value="yes">
							<label class="form-check-label">
								Unique Id
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_preserve" type="checkbox" name="preserve" value="yes">
							<label class="form-check-label">
								Preserve
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_deidentify" type="checkbox" name="deidentify" value="yes">
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
						<input type="text" class="form-control mt-1  q_name select_qname" name="name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="select_one">
					</div>
					<div class="d-block">
						<label class="form-check-label">
							Label<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_label" name="label">
					</div>
				<!--	<div class="d-block">
						<label class="form-check-label">
							Short Label
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_dictionary_label" name="dictionary_label">
					</div>-->
					<div>
						<label class="form-check-label">
							Hint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_hint" name="hint">
					</div>
					<div>
						<label class="form-check-label">
							Limit<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);">
					</div>
					<div>
						<label class="form-check-label">
							Constraint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon" ></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint" name="constraint" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Constraint Message
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint_message" name="constraint_message">
					</div>
					<div>
						<label class="form-check-label">
							Paradata
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_paradata" name="paradata" value="T,G,K">-->
						<div>
							<input class="form-check-input webform-checkbox   q_paradata" type="checkbox" name="paradata" value="T">
							<label class="form-check-label">
								Timestamp
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="G">
							<label class="form-check-label">
								GPS
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="K">
							<label class="form-check-label">
								Key-Stroke
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="A"> 
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
						<select class="form-control mt-1  q_appearance" name="appearance">
							<?php echo getAppearance($conn, "select_one"); ?>
						</select>
					</div>
					<div>
						<label class="form-check-label">
							Choice Filter
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<!--<input type="text" class="form-control mt-1  q_choice_filter" name="choice_filter">-->
						<select class="form-control mt-1  q_choice_filter" name="choice_filter">
							
						</select>
					</div>
				<!--	<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>-->
					<input type="hidden" class="q_repeat_count" name="repeat_count">
					<div>
						<label class="form-check-label">
							Calculation
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_calculation" name="calculation" onkeydown="blockBackspaceKey(event)">
					</div>
					<input type="hidden" class="q_dafault_response" name="dafault_response" value="">
				<!--	<div>
						<label class="form-check-label">
							Default Response
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_dafault_response" name="dafault_response" disabled>
					</div>-->
					
				<!--	<div>
						<input class="form-check-input webform-checkbox   q_ismultiple " type="checkbox" value="yes" name="multiple-selects">
						<label class="form-check-label">
							Allow Multiple Selection
						</label>
					</div>-->
					<div>
						<label class="form-check-label">
							Media File
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
					</div>
					<div>
						<label class="form-check-label">
							Lookup
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
										<!--<li class="d-none">
											<select name="languages[]" id="languageSelectone" class="form-control languageSelectOne">
											<option value="">Select Language</option>
											</select>
											<input type="text" class="form-control multi_language multi-one-option red-star-required"  name="lang-label[]" placeholder="Label*">
											<input type="text" class="form-control multi_language multi-two-option"  name="lang-hint[]" placeholder="Hint"/>
											<input type="text" class="form-control multi_language multi-three-option" name="lang-constraint[]" placeholder="Constraint message"/>
										</li>-->

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
										<li>
											<input type="radio" class="option-selected" checked="checked" class="form-checkbox">
											<input type="text" class="form-control nameOption" name="option-name[]" placeholder="Option Name">
											<input type="text" class="form-control valueOption" name="option-value[]" placeholder="Value">
											<input type="text" class="form-control choice_constraint" name="choice-constraint[]" placeholder="Constraint">
											<input type="text" class="form-control choice_mediafile" name="choice-mediafile[]" placeholder="Media File">
											
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="add-field" id="add-field-option">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;

		var edit_date = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div class="w-100 d-flex  checkbox-align">
						<span>
							<input class="form-check-input webform-checkbox   q_required" type="checkbox" name="required" value="yes">
							<label class="form-check-label">
								Required 
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Ensures that a question must be answered before proceeding." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_read_only" type="checkbox" name="read_only" value="yes">
							<label class="form-check-label">
								Read Only
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Displays information that cannot be edited by the data collector." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_unique_id" type="checkbox" name="unique_id" value="yes">
							<label class="form-check-label">
								Unique Id
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show current field name value below each Unique case ID number (Application display)." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_preserve" type="checkbox" name="preserve" value="yes">
							<label class="form-check-label">
								Preserve
								<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Keeps certain data consistent across multiple uses of the survey form." class="fa fa-info-circle tooltip-icon"></i>
							</label>
						</span>
						<span>
							<input class="form-check-input webform-checkbox   q_deidentify" type="checkbox" name="deidentify" value="yes">
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
						<input type="text" class="form-control mt-1  q_name" name="name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="date">
					</div>
					<div class="d-block">
						<label class="form-check-label">
							Label<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="The main question text visible to data collector." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_label" name="label">
					</div>
				<!--	<div class="d-block">
						<label class="form-check-label">
							Short Label
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="A short description of the text displayed to the data collector in the question label." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_dictionary_label" name="dictionary_label">
					</div>-->
					<div>
						<label class="form-check-label">
							Hint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Additional information or guidance to help data collector answer the question." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_hint" name="hint">
					</div>
					<div>
						<label class="form-check-label">
							Limit<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Restrictions on the input data, such as minimum and maximum values." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_limit" name="limit" onkeypress="return isNumberKey(event);">
					</div>
					<div>
						<label class="form-check-label">
							Constraint
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Logical conditions that the response must satisfy." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint" name="constraint" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Constraint Message
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Error message displayed when the response violates a constraint." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_constraint_message" name="constraint_message">
					</div>
					<div>
						<label class="form-check-label">
							Paradata
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Metadata about the survey process." class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_paradata" name="paradata" value="T,G,K">-->
						
						
						<div>
							<input class="form-check-input webform-checkbox   q_paradata" type="checkbox" name="paradata" value="T">
							<label class="form-check-label">
								Timestamp
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="G">
							<label class="form-check-label">
								GPS
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="K">
							<label class="form-check-label">
								Key-Stroke
							</label>
							
							<input class="form-check-input webform-checkbox   q_paradata tgka_margin" type="checkbox" name="paradata" value="A"> 
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
						<select class="form-control mt-1  q_appearance" name="appearance">
							<?php echo getAppearance($conn, "date"); ?>
						</select>
					</div>
					<div>
						<label class="form-check-label">
							Choice Filter
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Filters available choices based on previous responses." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_choice_filter" name="choice_filter">
					</div>
				<!--	<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>-->
					<input type="hidden" class="q_repeat_count" name="repeat_count">
					<div>
						<label class="form-check-label">
							Calculation
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Performs automatic computations within the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_calculation" name="calculation" onkeydown="blockBackspaceKey(event)">
					</div>
					<div>
						<label class="form-check-label">
							Default Response
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Default Response refers to pre-selected value that is automatically displayed in an input field when the form is initially loaded" class="fa fa-info-circle tooltip-icon"></i>
						</label>
					<!--	<input type="text" class="form-control mt-1  q_dafault_response" name="dafault_response">-->
						<select name="dafault_response" class="form-control mt-1  q_dafault_response">
							<option value="">Select an option</option>
							<option value="today">today</option>
							<option value=">=6&<=10">>=6&<=10</option>
							<option value=">=today">>=today</option>
							<option value=">=today +10">>=today +10</option>
							<option value="<=today -10"><=today -10</option>
						</select>
					</div>
					<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;

		var edit_group = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div>
						<label class="form-check-label">
							Group Name<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_name" name="group-name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="begin_group">
					</div>
					<div>
						<input class="form-check-input webform-checkbox   fieldlist" type="checkbox" name="fieldlist" value="yes">
						<label class="form-check-label">
							Onescreen
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show group of questions on same screen. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
					</div>
					<div>
											<label class="form-check-label">
												Media File
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Media File" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;


		var rpedit_group = `<div class="edit-main-container">
                <div class="edit-wrapper-container">
					<div>
						<label class="form-check-label">
							Repeat Group Name<span style="color:red">*</span>
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="An identifier for each group/repeat group in the survey form." class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_name" name="group-name" id="inputField" onkeydown="blockInvalidKeys(event)" oninput="validateInput(this)">
						<p id="errorMessage" class="error-message" style="color: red"></p>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="begin_repeat"> 
					</div>
					<div>
						<label class="form-check-label">
							Repeat Count
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Provide variable name which determines the number of times a set of questions is repeated. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						<input type="text" class="form-control mt-1  q_repeat_count" name="repeat_count">
					</div>
					<div>
						<input class="form-check-input webform-checkbox   fieldlist" type="checkbox" name="fieldlist" value="yes">
						<label class="form-check-label">
							Onescreen
							<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Show group of questions on same screen. " class="fa fa-info-circle tooltip-icon"></i>
						</label>
						
						<input class="form-check-input webform-checkbox   parameters" type="checkbox" name="parameters" value="multiroster">
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
											<input type="text" class="form-control mt-1  Media-File q_media_file" name="Media File" value="">
										</div>
										<div>
											<label class="form-check-label">
												Lookup
												<i data-bs-html="true" data-bs-toggle="tooltip" data-title="Lookup" class="fa fa-info-circle tooltip-icon"></i>
											</label>
											<input type="text" class="form-control mt-1  Lookup q_lookup" name="Lookup" value="">
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
									<ul class="multi-lang-new-ul">
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
							<input type="text" class="form-control mt-1  manual_relevant_txt" name="manual_relevant_txt" placeholder="Enter the relevant manually" onkeydown="blockBackspaceKey(event)">
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

									</ul>
								</div>
							</div>
						</div>
						<div class="add-relfield">
							Add Relevant	<i class="fa fa-plus"></i>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>`;

		var x = 0;
		var x1 = 0; //Initial field counter is 1
		var x2 = 0;
		var x3 = 0;
		var x4 = 0;
		var x5 = 0;
		var x6 = 0;
		var x7 = 0;

		var x8 = 0;
		var x9 = 0;
		var x10 = 0;
		var x11 = 0;
		var x12 = 0;

		var w = 0;
		var w1 = 0; //Initial field counter is 1
		var w2 = 0;
		var w3 = 0;
		var w4 = 0;
		var w5 = 0;
		var w6 = 0;
		var w7 = 0;
		var w8 = 0;
		var w9 = 0;

		var w10 = 0;
		var w11 = 0;
		var w12 = 0;
		var w13 = 0;


		$(document).on("click", ".number", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			if (parent_div == 'FIELDSET') {
				w++
				var numbersec = `<div class="number-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="number-` + w + `" type="hidden" id="number-` + w + `" placeholder="Type: Number" readonly>
				</div>
			</div>`;
				$(this).parent().parent().parent().find('> .add-fields').before(numbersec);
			} else {
				x++;
				var numbersec = `<div class="number-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="number-` + x + `" type="hidden" id="number-` + x + `" placeholder="Type: Number" readonly>
				</div>
			</div>`;
				$(".question-area-container").append(numbersec);
			}
		});

		/*$(".number").click(function(){
		var parent_div = $(this);//.parent().parent().parent();
		console.log(parent_div);
    });*/

		$(document).on("click", ".edit-form", function() {
			if ($(this).parent().parent().hasClass('number-seaction')) {
				if (!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0) {
					$(this).parent().parent().find('.input-seaction').append(edit_number);
				} else {
					$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
				}

			} else if ($(this).parent().parent().hasClass('select-seaction')) {
				
				let allQuestions = $(".select_qname").map(function() {
					return this.value;
				}).get();
				let numberOfQuestions = allQuestions.length;
				let q_list = '';
				for (let i = 0; i < numberOfQuestions; i++) {
					q_list += '<option value="' + allQuestions[i] + '">' + allQuestions[i] + '</option>';
				}

				var list = `<option value="">Select an option</option>
								` + q_list + ``;
				
				if (!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0) {

					$(this).parent().parent().find('.input-seaction').append(edit_select_single);
					
					$(this).parent().next().next().find('.q_choice_filter').append(list);
				} else {
					$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
				}
			} else if ($(this).parent().parent().hasClass('text-seaction')) {
				if (!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0) {

					$(this).parent().parent().find('.input-seaction').append(edit_text);
				} else {
					$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
				}
			} else if ($(this).parent().parent().hasClass('note-seaction')) {
				if (!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0) {

					$(this).parent().parent().find('.input-seaction').append(edit_note);
				} else {
					$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
				}
			} else if ($(this).parent().parent().hasClass('date-seaction')) {
				if (!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0) {

					$(this).parent().parent().find('.input-seaction').append(edit_date);
				} else {
					$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
				}
			} else if ($(this).parent().parent().hasClass('audio-seaction')) {
				if (!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0) {

					$(this).parent().parent().find('.input-seaction').append(edit_audio);
				} else {
					$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
				}
			} else if ($(this).parent().parent().hasClass('video-seaction')) {
				if (!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0) {

					$(this).parent().parent().find('.input-seaction').append(edit_video);
				} else {
					$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
				}
			} else if ($(this).parent().parent().hasClass('gps-seaction')) {
				if (!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0) {

					$(this).parent().parent().find('.input-seaction').append(edit_gps_button);
				} else {
					$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
				}
			} else if ($(this).parent().parent().hasClass('picture-seaction')) {
				if (!$(this).parent().parent().find('.input-seaction').find('.edit-main-container').length > 0) {

					$(this).parent().parent().find('.input-seaction').append(edit_picture);
				} else {
					$(this).parent().parent().find('.input-seaction').find('.edit-main-container').slideToggle('slow');
				}
			}

		});


		/*$(".select-single").click(function(){
		
    });  */


		$(document).on("click", ".select-single", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			

			if (parent_div == 'FIELDSET') {
				w1++
				var select_single = `<div class="select-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<select class="form-control ques d-none" name="select-` + w1 + `" id="select-` + w1 + `" disabled  >	
						<option class="select-placeholder" value="">Type: Select</option>

					</select>
				<!--	<input type="text" class="form-control ques" placeholder="Type: Select One Option" readonly>-->
				</div>
			</div>`;
				$(this).parent().parent().parent().find('> .add-fields').before(select_single);
			} else {
				x1++; //Increment field counter
				var select_single = `<div class="select-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<select class="form-control ques d-none" name="select-` + x1 + `" id="select-` + x1 + `" disabled>	
						<option class="select-placeholder" value="">Type: Select</option>

					</select>
				<!--	<input type="text" class="form-control ques" placeholder="Type: Select One Option" readonly>-->
				</div>
			</div>`;
				$(".question-area-container").append(select_single)
			}
		});


		$(document).on("click", ".text", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			//alert(parent_div);
			if (parent_div == 'FIELDSET') {
				w3++; //Increment field counter
				var textsec = `<div class="text-seaction">
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
					<h6 style="font-size: 14px"><i>Text Opened Ended Question</i></h6> 
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="text-` + w3 + `" type="hidden" id="text-` + w3 + `" placeholder="Type: Text" readonly>
				
				</div>
			</div>`;
				$(this).parent().parent().parent().find('> .add-fields').before(textsec);
			} else {
				x3++; //Increment field counter
				var textsec = `<div class="text-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="text-` + x3 + `" type="hidden" id="text-` + x3 + `" placeholder="Type: Text" readonly>
					
				</div>
			</div>`;
				$(".question-area-container").append(textsec);
			}
		});

		/*$(".text").click(function(){
		
    });
	   */
		/*$(".note").click(function(){
		
    });*/

		$(document).on("click", ".note", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			//alert(parent_div);
			if (parent_div == 'FIELDSET') {
				w5++; //Increment field counter
				var notesec = `<div class="note-seaction">
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
					<h6 style="font-size: 14px"><i> Note</i></h6> 
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<textarea class="form-control d-none ques" name="note-` + w5 + `" id="note-` + w5 + `" placeholder="Type: Note" readonly></textarea>
				</div>
			</div>`;
				$(this).parent().parent().parent().find('> .add-fields').before(notesec);
			} else {
				x5++; //Increment field counter
				var notesec = `<div class="note-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<textarea class="form-control d-none ques" name="note-` + x5 + `" id="note-` + x5 + `" placeholder="Type: Note" readonly></textarea>
				</div>
			</div>`;
				$(".question-area-container").append(notesec);
			}
		});



		/*$(".date").click(function(){
		 x6++; //Increment field counter
		var datesec = `<div class="date-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div>  
			<div class="input-seaction">
				<input class="form-control ques" name="date-`+x6+`" type="hidden" id="date-`+x6+`" placeholder="Type: Date" readonly>
			</div>
		</div>`;
        $(".question-area-container").append(datesec);
    }); 
	*/

		$(document).on("click", ".date", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			//alert(parent_div);
			if (parent_div == 'FIELDSET') {
				w6++; //Increment field counter
				var datesec = `<div class="date-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="date-` + w6 + `" type="hidden" id="date-` + w6 + `" placeholder="Type: Date" readonly>
				</div>
			</div>`;
				$(this).parent().parent().parent().find('> .add-fields').before(datesec);
			} else {
				x6++; //Increment field counter
				var datesec = `<div class="date-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="date-` + x6 + `" type="hidden" id="date-` + x6 + `" placeholder="Type: Date" readonly>
				</div>
			</div>`;
				$(".question-area-container").append(datesec);
			}
		});

		$(document).on("click", ".close-form", function() {
			if (confirm('Are you sure?') == true) {
				$(this).parent().parent().remove();
			}
		});
		$(document).on("click", ".close-group", function() {
			if (confirm('Are you sure?') == true) {
				$(this).parent().parent().parent().remove();
			}
		});

		/*$(".b-group").click(function(){
		
    });*/


		$(document).on("click", ".b-group", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			//alert(parent_div);
			if (parent_div == 'FIELDSET') {
				w7++; //Increment field counter
				var datagroup = `<fieldset class="dragto">
				<span class="message-container">
						<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
						<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
					</span>
				  <div class="groups-header">
					<!-- <h4 class="group-name">Group - ` + w7 + `<span style="color:red">*</span></h4> --> 
						<h6 style="font-size: 14px" class="mt-1 mb-0"><i>Create Group</i></h6>
						<h4 class="group-name">[Field Name]<span style="color:red">*</span></h4>
						<span class="text-danger required_star">*</span> 
					<div class="button ques">
					<input type="hidden" class="form-control q_type" name="type" value="begin_group">
						<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
						<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
					</div>
				  </div>
				  
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
										<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-table"></i>Create Roster </a>
					  </div>

				  </div>
				<div class="d-none">
					<div class="button ques">
						<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
						<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
					</div>
					
					<div>
						<div>
							<input type="hidden" class="form-control mt-1  q_type" name="type" value="end_group">
						</div>
					</div>
				</div>
			  </fieldset>`;
				$(this).parent().parent().parent().find('> .add-fields').before(datagroup);

				$('.dragto').sortable({
					revert: true
				});
				$(".dragto > div:last-child, .dragto > div:first-child").draggable({
					opacity: 0.7,
					helper: "clone"
				});
			} else {
				x7++; //Increment field counter

				var datagroup = `<fieldset class="ui-sortable">
					<span class="message-container">
						<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
						<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
					</span>
				  <div class="groups-header">
					<!-- <h4 class="group-name">Group - ` + x7 + `<span style="color:red">*</span></h4> -->
						<h6 style="font-size: 14px" class="mt-1 mb-0"><i>Create Group</i></h6>
						<h4 class="group-name">[Field Name]<span style="color:red">*</span></h4>
						<span class="text-danger required_star">*</span> 
					<div class="button ques">
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="begin_group">
						<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
						<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
					</div>
					
				  </div>
				  
					
					
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
										<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-table"></i>Create Roster </a>
					  </div>
				  </div>
				  
					<div class="d-none">
					<div class="button ques">
						<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
						<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
					</div>
					
					<div>
					<div>
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="end_group">
					</div>
					</div>
					</div>
					
			  </fieldset>`;
				$(".question-area-container").append(datagroup);
				$("fieldset").sortable({
					revert: true
				});
				$("fieldset > div:first-child, fieldset > div:last-child").draggable({
					opacity: 0.7,
					helper: "clone"
				});
			}
		});

		$(document).on("click", ".b-regroup", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			//alert(parent_div);
			if (parent_div == 'FIELDSET') {
				w8++; //Increment field counter
				var datagroup = `<fieldset class="dragto">
				<span class="message-container">
						<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
						<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
					</span>
				  <div class="groups-header">
					<!-- <h4 class="group-name">Repeat Group - ` + w8 + `<span style="color:red">*</span></h4> -->
						<h6 style="font-size: 14px" class="mt-1 mb-0"><i>Create Roster</i></h6>
						<h4 class="group-name">[Field Name]<span style="color:red">*</span></h4>
						<span class="text-danger required_star">*</span> 
					<div class="button ques">
						<input type="hidden" class="form-control mt-1  q_type" name="type" value="begin_repeat">
						<div class="rpedit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
						<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
					</div>
				  </div>




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
										<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-table"></i>Create Roster </a>
					  </div>

				  </div>
				  <div class="d-none">
					<div class="button ques">
						<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
						<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
					</div>
					
					<div>
						<div>
							<input type="hidden" class="form-control mt-1  q_type" name="type" value="end_repeat">
						</div>
					</div>
				</div>
			  </fieldset>`;
				$(this).parent().parent().parent().find('> .add-fields').before(datagroup);

				$('.dragto').sortable({
					revert: true
				});
				$(".dragto > div:last-child, .dragto > div:first-child").draggable({
					opacity: 0.7,
					helper: "clone"
				});
			} else {
				w8++; //Increment field counter

				var datagroup = `<fieldset class="ui-sortable">
				<span class="message-container">
						<p class="form-error  bg-danger text-white px-2 mb-0"><i class="fa fa-exclamation" aria-hidden="true" style="padding: 5px"></i>Validation Error</p>
						<p class="form-success-notice bg-success text-white px-2"><i class="fa-solid fa-check " aria-hidden="true" style="width: 22px; height: 22px; padding:6px 5px"></i> Validated</p>
					</span>
				  <div class="groups-header">
					<!-- <h4 class="group-name">Repeat Group - ` + w8 + `<span style="color:red">*</span></h4> -->
						<h6 style="font-size: 14px" class="mt-1 mb-0"><i>Create Roster</i></h6>
						<h4 class="group-name">[Field Name]<span style="color:red">*</span></h4>
						<span class="text-danger required_star">*</span> 
					<div class="button ques">
					<input type="hidden" class="form-control mt-1  q_type" name="type" value="begin_repeat">
						<div class="rpedit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
						<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
					</div>
				  </div>




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
										<a class="b-regroup" href="javascript:void(0);"><i class="fa fa-table"></i>Create Roster </a>
					  </div>
					
				  </div>
				  
					<div class="d-none">
						<div class="button ques">
							<div class="close-group"><i class="fa-solid fa fa-trash"></i></div>
							<div class="edit-group"><i class="fa-solid fa fa-pencil-square-o"></i></div>
						</div>
						
						<div>
						<div>
							<input type="hidden" class="form-control mt-1  q_type" name="type" value="end_repeat">
						</div>
						</div>
					</div>
					
			  </fieldset>`;
				$(".question-area-container").append(datagroup);
				$("fieldset").sortable({
					revert: true
				});
				$("fieldset > div:first-child, fieldset > div:last-child").draggable({
					opacity: 0.7,
					helper: "clone"
				});
			}
		});

		/* New Question Type Implementation */

		$(document).on("click", ".audio", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			//alert(parent_div);
			if (parent_div == 'FIELDSET') {
				w9++; //Increment field counter
				var audiosec = `<div class="audio-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="audio-` + w9 + `" type="hidden" id="audio-` + w9 + `" placeholder="Type: Audio" readonly>
				
				</div>
			</div>`;
				$(this).parent().parent().parent().find('> .add-fields').before(audiosec);
			} else {
				x9++; //Increment field counter
				var audiosec = `<div class="audio-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="audio-` + x9 + `" type="hidden" id="audio-` + x9 + `" placeholder="Type: Audio" readonly>
					
				</div>
			</div>`;
				$(".question-area-container").append(audiosec);
			}
		});

		$(document).on("click", ".video", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			//alert(parent_div);
			if (parent_div == 'FIELDSET') {
				w10++; //Increment field counter
				var videosec = `<div class="video-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div>  
				<div class="input-seaction">
					<input class="form-control ques" name="video-` + w10 + `" type="hidden" id="video-` + w10 + `" placeholder="Type: Video" readonly>
				
				</div>
			</div>`;
				$(this).parent().parent().parent().find('> .add-fields').before(videosec);
			} else {
				x10++; //Increment field counter
				var videosec = `<div class="video-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="video-` + x10 + `" type="hidden" id="video-` + x10 + `" placeholder="Type: Video" readonly>
					
				</div>
			</div>`;
				$(".question-area-container").append(videosec);
			}
		});

		$(document).on("click", ".gps_button", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			//alert(parent_div);
			if (parent_div == 'FIELDSET') {
				w11++; //Increment field counter
				var gpssec = `<div class="gps-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="gps_button-` + w11 + `" type="hidden" id="gps_button-` + w11 + `" placeholder="Type: GPS-Button" readonly>
				
				</div>
			</div>`;
				$(this).parent().parent().parent().find('> .add-fields').before(gpssec);
			} else {
				x11++; //Increment field counter
				var gpssec = `<div class="gps-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="gps_button-` + x11 + `" type="hidden" id="gps_button-` + x11 + `" placeholder="Type: GPS-Button" readonly>
					
				</div>
			</div>`;
				$(".question-area-container").append(gpssec);
			}
		});

		$(document).on("click", ".picture", function() {
			var parent_div = $(this).parent().parent().parent().get(0).tagName;
			//alert(parent_div);
			if (parent_div == 'FIELDSET') {
				w12++; //Increment field counter
				var picturesec = `<div class="picture-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div>  
				<div class="input-seaction">
					<input class="form-control ques" name="picture-` + w12 + `" type="hidden" id="picture-` + w12 + `" placeholder="Type: Picture" readonly>
				
				</div>
			</div>`;
				$(this).parent().parent().parent().find('> .add-fields').before(picturesec);
			} else {
				x12++; //Increment field counter
				var picturesec = `<div class="picture-seaction">
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
					<span class="label-name">[Field Name]:</span>
					<span class="label-text">[Label]</span>
					<span class="text-danger required_star">*</span>
				</div> 
				<div class="input-seaction">
					<input class="form-control ques" name="picture-` + x12 + `" type="hidden" id="picture-` + x12 + `" placeholder="Type: Picture" readonly>
					
				</div>
			</div>`;
				$(".question-area-container").append(picturesec);
			}
		});

		$(document).on("click", ".rpedit-group", function() {
			if ($(this).parent().parent().hasClass('groups-header')) {
				if (!$(this).parent().parent().find('.edit-main-container').length > 0) {
					$(this).parent().parent().append(rpedit_group);
				} else {
					$(this).parent().parent().find('.edit-main-container').slideToggle('slow');
				}
			}
		});

		$(document).on("click", ".edit-group", function() {
			if ($(this).parent().parent().hasClass('groups-header')) {
				if (!$(this).parent().parent().find('.edit-main-container').length > 0) {
					$(this).parent().parent().append(edit_group);
				} else {
					$(this).parent().parent().find('.edit-main-container').slideToggle('slow');
				}
			}
		});



		$(document).on("keyup", "input[name='placeholder']", function() {
			var new_value = $(this).val();
			$(this).parent().parent().parent().parent().find('> input').attr('placeholder', new_value);
			$(this).parent().parent().parent().parent().find('> textarea').attr('placeholder', new_value);
			if (new_value.length > 0) {
				$(this).parent().parent().parent().parent().find('> select .select-placeholder').html(new_value); //.attr('placeholder', new_value);//.append("<option>"+new_value+"</option>");

			} else {
				$(this).parent().parent().parent().parent().find('> select .select-placeholder').html('Select Option');
			}

		});
		$(document).on("click", ".add-field", function() {

			var labelCount = $(this).closest('.fw').find('.option-language-label').length;
			var choiceFilterCount = $(this).closest('.fw').find('.choice-filter-val').length;
			if (labelCount == 0) {
				x2++; //Increment field counter
				var default_input = 'radio';
				if ($(this).parent().prev().find('input[name="multiple-selects"]').prop('checked') == true) {
					default_input = 'checkbox';
				}
				var appendChoiceFilterField = '';
				if(choiceFilterCount > 0){
					var placeholderValue = $(this).closest('.fw').find('.choice-filter-val').first().attr('placeholder');

					var appendChoiceFilterField = `<input type="text" class="form-control choice-filter-val" name="choice-filter-val[]" placeholder="${placeholderValue}">`;
				}

				var list = `<li>
						<input type="` + default_input + `" class="option-selected" checked="checked" class="form-checkbox">
						<input type="text" class="form-control nameOption" name="option-name[]" placeholder="Option Name">
						<input type="text" class="form-control valueOption" name="option-value[]" placeholder="Value">
						<input type="text" class="form-control choice_constraint" name="choice-constraint[]" placeholder="Constraint">
						<input type="text" class="form-control choice_mediafile" name="choice-mediafile[]" placeholder="Media File">
						` + appendChoiceFilterField + `
						<div class="del-btn">
							<i class="fa fa-trash"></i>
						</div>
					</li>`;
				$(this).prev().find('.multiinputs-div ul').append(list);
				$(this).parent().parent().parent().parent().find('> select').append("<option value='' selected=''> Option " + (x2 + 1) + "</option>");

			} else {

				var lastLi = $(this).closest('.fw').find(".multi-option-new-ul li:last-child").clone();
				var placeholderIndex = $(this).closest('.fw').find(".multi-option-new-ul li").length + 1;
				lastLi.find("input[name='option-name[]']").attr("placeholder", "Option Name");
				lastLi.find("input[name='option-value[]']").attr("placeholder", "Value");
				//lastLi.find("input[type='text']").attr("value", "" );
				lastLi.find("input[type='text']").val("");
				lastLi.appendTo($(this).closest('.fw').find(".multi-option-new-ul"));

				$(this).parent().parent().parent().parent().find('> select').append("<option value='' selected=''> Option " + placeholderIndex + "</option>");
			}

		});
		$(document).on("change", "input[name='multiple-selects']", function() {
			//alert('hi...');
			if ($(this).prop('checked') == true) {
				$(this).parent().next().find('.multiinputs-div ul li').each(function() {
					$(this).find('.option-selected').attr('type', 'checkbox');
				})
			} else {
				$(this).parent().next().find('.multiinputs-div ul li').each(function() {
					$(this).find('.option-selected').attr('type', 'radio');
				})
			}
		});
		
		$(document).on("change", "select[name='choice_filter']", function() {
			let $currentSelect = $(this);
			let allQuestions = $currentSelect.val();
			
			let lastMultiinputsDiv = $currentSelect.closest('.edit-wrapper-container').find('.multiinputs-div').last();
			let listItems = lastMultiinputsDiv.find('ul.multi-option-new-ul > li');
			
			let addInput = `<input type="text" class="form-control choice-filter-val" name="choice-filter-val[]" placeholder="${allQuestions} value">`;

			
			listItems.each(function() {
				$(this).find('input[name="choice-filter-val[]"]').remove();
			});

			
			if (allQuestions !== '') {
				listItems.each(function() {
					$(this).append(addInput);
				});
			}
			
		});
		

		$(document).on("click", ".del-btn", function() {
			//alert('hi...')
			//$(this).parent().remove();
			//var listItem = $(this).parent();
			//index_num = listItem.index( "li" );
			var index_num = $(this).parent().index();
			//console.log($(this).parent().parent().children().length);
			//console.log($(this).parent().index());
			$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option:eq(' + (index_num + 1) + ')').remove();
			$(this).parent().remove();
		});

		$(document).on("keyup", "input[name='hint']", function() {
			var $container = $(this).closest('.edit-wrapper-container');
			var hintValue = $(this).val().trim();
			// if (hintValue.length > 0) {
			// $container.find('.multi-two').prop("disabled", false);
			// $container.find('.multi-two-option').prop("disabled", false);
			// } else {
			// $container.find('.multi-two').prop("disabled", true);
			// $container.find('.multi-two').val("");
			// $container.find('.multi-two-option').prop("disabled", true);
			// $container.find('.multi-two-option').val("");
			// }
		});

		$(document).on("keyup", "input[name='constraint_message']", function() {
			var $container = $(this).closest('.edit-wrapper-container');
			var constraintMsgValue = $(this).val().trim();
			// if (constraintMsgValue.length > 0) {
			// $container.find('.multi-three').prop("disabled", false);
			// $container.find('.multi-three-option').prop("disabled", false);
			// } else {
			// $container.find('.multi-three').prop("disabled", true);
			// $container.find('.multi-three').val("");
			// $container.find('.multi-three-option').prop("disabled", true);
			// $container.find('.multi-three-option').val("");
			// }
		});

		$(document).on("click", ".add-langfield", function() {

			var list = `<li>
						<select name="languages[]" id="languageSelect" class="form-control languageSelect">
							<option value="">Select Language</option>
							<?php
							$language_sql = mysqli_query($conn, "select language_code,language_name from languages where status = 0 and language_id not in(1) order by language_name asc");
							while ($result =	mysqli_fetch_object($language_sql)) { ?>
										<option value="<?= $result->language_code ?>"><?= $result->language_name ?></option>
							<?php } ?>
						</select>
						<input type="text" class="form-control multi_language multi-one red-star-required"  name="lang-label[]" placeholder="Label*">
						<input type="text" class="form-control multi_language multi-two"  name="lang-hint[]" placeholder="Hint">
						<input type="text" class="form-control multi_language multi-three" name="lang-constraint[]" placeholder="Constraint message">
						<div class="del-relbtn">
							<i class="fa fa-trash"></i>
						</div>
					</li>`;
			var $newListItem = $(list);
			$(this).prev().find('.multiinputs-div ul').append($newListItem);

			if ($(this).parent().parent().find('.q_hint')) {
				var ques_hint_val = $(this).parent().parent().find('.q_hint').val();
				// if (ques_hint_val == '') {
				// $newListItem.find(".multi-two").attr("disabled", true);
				// }
			}

			if ($(this).parent().parent().find('.q_constraint_message')) {
				var ques_con_msg_val = $(this).parent().parent().find('.q_constraint_message').val();
				// if (ques_con_msg_val == '') {
				// $newListItem.find(".multi-three").attr("disabled", true);
				// }
			}

		});



		$(document).on("click", ".add-langfield-option", function() {
			var countlength = $(this).prev().find('.count_li').length;
			var licount = countlength > 0 ? countlength : 0;
			licount++;
			var list = `<li>
						<select name="languages[]" id="languageSelectOne" class="form-control languageSelectOne">
							<option value="">Select Language</option>
							<?php
							$language_sql = mysqli_query($conn, "select language_code,language_name from languages where status = 0 and language_id not in(1) order by language_name asc");
							while ($result =	mysqli_fetch_object($language_sql)) { ?>
										<option value="<?= $result->language_code ?>"><?= $result->language_name ?></option>
							<?php } ?>
						</select>
						<input type="hidden" class="form-control count_li" id="${licount}" name="licount" value="${licount}"/>
						<input type="text" class="form-control multi_language multi-one-option red-star-required"  name="lang-label[]" placeholder="Label*">
						<input type="text" class="form-control multi_language multi-two-option"  name="lang-hint[]" placeholder="Hint"/>
						<input type="text" class="form-control multi_language multi-three-option" name="lang-constraint[]" placeholder="Constraint message"/>
						<div class="del-multioptionbtn">
							<i class="fa fa-trash"></i>
						</div>
					</li>`;

			//$(this).prev().find('.multiinputs-div ul').append(list);

			var $newListItem = $(list);
			$(this).prev().find('.multiinputs-div ul').append($newListItem);

			if ($(this).parent().parent().find('.q_hint')) {
				var ques_hint_val = $(this).parent().parent().find('.q_hint').val();
				// if (ques_hint_val == '') {
				// $newListItem.find(".multi-two-option").attr("disabled", true);
				// }
			}

			if ($(this).parent().parent().find('.q_constraint_message')) {
				var ques_con_msg_val = $(this).parent().parent().find('.q_constraint_message').val();
				// if (ques_con_msg_val == '') {
				// $newListItem.find(".multi-three-option").attr("disabled", true);
				// }
			}

			var list2 = `<input type="text" class="form-control option-language-label option-lang-val${licount}" name="option-lang-val[]" placeholder="Label*"/>`;

			$(this).parent().next().find('.multiinputs-div ul li').append(list2);
			//$('.add-field').css('pointer-events','none');


		});

		$(document).on("click", ".add-relfield", function() {
			x4++; //Increment field counter

			let allQuestions = $(".q_name").map(function() {
				return this.value;
			}).get();
			let q_list = '';
			let nof_ques = $(".add-relfield").index(this);
			for (let i = 0; i < nof_ques; i++) {
				q_list += '<option value="' + allQuestions[i] + '">' + allQuestions[i] + '</option>';
			}

			var list = `<li>
						<select name="relevant[]" class="form-control rel_qnames">
							<option value="">Select Question</option>
							` + q_list + `
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
						<input type="text" class="form-control rel_values" name="rel-value[]" placeholder="Value txt">
						<select name="condition1[]" class="form-control rel_andOr">
							<option value="">Select Condition</option>
							<option value="&">& (AND)</option>
							<option value="|">| (OR)</option>
						</select>
						<div class="del-relbtn">
							<i class="fa fa-trash"></i>
						</div>
					</li>`;
			$(this).prev().find('.multiinputs-div ul').append(list);


		});
		$(document).on("click", ".del-relbtn", function() {
			$(this).parent().remove();
		});


		$(document).on("click", ".del-multioptionbtn", function() {
			var $this = $(this);
			var delbtn = $this.parent().parent().find('.del-multioptionbtn').length;
			// alert(delbtn);
			if (delbtn == 1) {
				//	$('.add-field').css('pointer-events','auto');
			}
			var del_id = $(this).prev().prev().prev().prev().val();
			$(this).parent().remove();
			$('.option-lang-val' + del_id + '').remove();


		});

		$(document).on("keyup", "input[name='option-name[]']", function() {

			var entered_value = $(this).val();

			var listItem = $(this).parent();
			index_num = listItem.index();

			var total_list = $(this).parent().parent().find('li').length;

			var select_length = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option').length;
			//alert(total_list);
			//alert(index_num);
			//alert(select_length);
			if ((select_length == 1)) {
				var optioni = "<option value='' selected=''>" + entered_value + "</option>";
				$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select').append(optioni);

			} else if ((select_length == total_list)) {
				if (index_num == 0) {
					var optioni = "<option value='' selected=''>" + entered_value + "</option>";
					$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option:eq(0)').after(optioni);
				} else {
					var optioni = "<option value=''  selected=''> Option 1 </option>";
					$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option:eq(0)').after(optioni);
				}

			} else {
				$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option').eq((index_num + 1)).html(entered_value);
			}

		});
		$(document).on("keyup", "input[name='option-value[]']", function() {

			var entered_value = $(this).val();

			var listItem = $(this).parent();
			index_num = listItem.index();

			var total_list = $(this).parent().parent().find('li').length;

			var select_length = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option').length;
			//alert(total_list);

			//alert(index_num);
			//alert(select_length);
			//alert(total_list);
			//console.log();
			//var prev_value = $(this).prev().val();

			//alert(select_length);
			//alert(total_list);


			if ((select_length == 1)) {
				if (total_list == 1 && (!$(this).prev().val())) {
					var optioni = "<option class='select-placeholder' selected='' value='" + entered_value + "'>  Option 1 </option>";
					$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select').append(optioni);
					//alert(prev_value);
				}
			} else {
				$(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().find('> select option').eq(index_num + 1).attr('value', entered_value);
				//alert('hi...');
			}

		});
		$(document).on("change", "input[name='required']", function() {
			var new_value = $(this).parent().parent().parent().parent().prev().html();
			if ($(this).prop('checked') == true) {
				$(this).parent().parent().parent().parent().prev().find('.required_star').show();
			} else {
				$(this).parent().parent().parent().parent().prev().find('.required_star').hide();
			}
		});

		// $(document).on("change", "input[name='manual_relevant']", function () {
		// var parentContainer = $(this).closest('.fw');

		// if($(this).prop('checked')==true){
		// parentContainer.find('.manual_relevant_txt').show();
		// parentContainer.find('.multiinputs-div').hide();
		// parentContainer.find('.add-relfield').hide();
		// }else{
		// parentContainer.find('.manual_relevant_txt').hide();
		// parentContainer.find('.multiinputs-div').show();
		// parentContainer.find('.add-relfield').show();
		// }
		// }); 

		$(document).on("keyup", "input[name='label']", function() {
			var new_value = $(this).val();
			$(this).parent().parent().parent().parent().prev().find('.label-text').html(new_value);
		});
		$(document).on("keyup", "input[name='minimum']", function() {
			var new_value = $(this).val();
			$(this).parent().parent().parent().parent().find('> input').attr('min', new_value);
		});
		$(document).on("keyup", "input[name='maximum']", function() {
			var new_value = $(this).val();
			$(this).parent().parent().parent().parent().find('> input').attr('max', new_value);
		});
		$(document).on("keyup", "input[name='name']", function() {
			var new_value = $(this).val();
			var concateValue = new_value + ': ';

			$(this).parent().parent().parent().parent().prev().find('.label-name').html(concateValue);
			$(this).parent().parent().parent().parent().find('> input').attr('name', new_value);
			$(this).parent().parent().parent().parent().find('> select').attr('name', new_value);
			$(this).parent().parent().parent().parent().find('> textarea').attr('name', new_value);
		});

		$(document).on("keyup", "input[name='group-name']", function() {
			var new_value = $(this).val();
			$(this).parent().parent().parent().parent().find('.group-name').html(new_value);
		});

	});
</script>

<script src="webv1.js"></script>

<script>
	$(document).ready(function() {
		$("#toggleButton").click(function() {
			$("#appendfromdetail").toggle();
		});
	});
</script>

<script>
 

	document.getElementById('toggle-sidebar').addEventListener('click', function() { 
		document.getElementById('sidebar').style.marginLeft = '0px';
		document.getElementById('sidebar').style.transition = 'all 0.3s';
	});

	function closeSidebar() {
		document.getElementById('sidebar').style.marginLeft = '-250px';
		document.body.classList.add("sidebar-closed");
	}

	window.addEventListener('load', function() {
		closeSidebar();
	});
</script>