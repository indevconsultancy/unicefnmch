<?php include_once('includes/config.php'); ?>
<?php include_once('s3bucket/function_Upload_file.php'); ?>
<?php define("title", "Review Data | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('mycrypt.php'); ?>
<?php
$_SESSION['enckey'];

$user_id = $_SESSION['user_id'];
if ($_SESSION['enckey'] === 0) { ?>
	<section id="main-content">
		<section class="wrapper">
			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-sm-12">

							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								You are not authorized to see the survey data
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>

						</div>
					</div>
				</div>
			</div>
		</section>
	</section>

	<?php include_once('includes/footer.php'); ?>
<?php
	die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);

$client_id = $_SESSION['client_id'];

$id = $_REQUEST['id'];
error_reporting(1);
function getAnswer($conn, $tablename, $field, $optstr, $question_id)
{
	$sn = mysqli_query($conn, "select GROUP_CONCAT($field) as $field from $tablename where option_sequence in($optstr) and question_id='" . $question_id . "' ");
	$dn = mysqli_fetch_object($sn);
	return ($dn->$field ?: str_replace("'", "", $optstr));
}

function getonce($conn, $tablename, $field, $qryfeild, $value, $survey_id)
{
	$sn = mysqli_query($conn, "select $field from $tablename where $qryfeild='" . $value . "' and survey_id='" . $survey_id . "' ") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn->$field);
}
?>


<style>
	.btn-sm,
	.btn-xs {
		float: right;
		margin-right: 1rem;
	}

	.btn-danger:hover,
	.btn-danger:focus,
	.btn-danger:active,
	.btn-danger.active,
	.open .dropdown-toggle.btn-danger {
		color: #fff;
		border-color: #ff2d55;
		background: #003b64;
	}

	.qreview {
		width: 90%;
		display: none;
	}

	.qreviewsend {
		display: block;
	}

	.wrapper .row {
		margin-bottom: 0px !important;
	}

	.tooltip-inner {
		background-color: black !important;
		/* Background color */
		color: white !important;
		/* Text color */
	}

	.tooltip-arrow::before {
		border-top-color: black !important;
		/* Arrow color */
	}


	.panel table tr th {
		background: #394a59;
		color: #fff !important;
	}

	.highlight-row td {
		background: #449a97 !important;
		color: #fff !important;
		border-color: #fff !important;
		font-family: 'Lato';
		font-weight: 700;
	}

	.repated-ques {
		color: #386685;
	}
</style>
<?php
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
	//return $result;
	$opt_name = '';
	if (isset($result[0])) {
		$opt_name = isset($array[$result[0]]['option_name']) ? $array[$result[0]]['option_name'] : "";
	}
	return $opt_name;
}

$details_sql = "SELECT sdm.survey_name_id, sdm.client_id, sdm.survey_id, sdm.survey_data_json, sdm.survey_data_monitoring_id, sdm.full_json, sdm.survey_name, sdm.created_on, users.firebase_token,users.name  FROM `survey_data_monitoring` as sdm inner join users on sdm.user_id=users.user_id  where survey_data_monitoring_id='" . $id . "'";
$details_query = mysqli_query($conn, $details_sql);
$details_data = mysqli_fetch_object($details_query);
$survey_id = $details_data->survey_name_id;
$client_id = $details_data->client_id;
// echo "<pre>";
// echo "hello"; 
$DecryptedJson = $mcrypt->decrypt($details_data->full_json);
$full_json = json_decode($DecryptedJson);
// echo "<pre>";
// print_r($full_json);
$survey_data = $full_json->survey_data;
$sequence_unique_id = $full_json->sequence_unique_id;
$surveyid = $survey_id;

$getAllQuestions = mysqli_query($conn, "SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1'   AND question_name!='' and field_name!='submit' AND survey_id='" . $surveyid . "'");
$allQuestions = [
	'uniqueid' => 'Survey ID',
	'username' => 'Username'
];
while ($question = mysqli_fetch_object($getAllQuestions)) {
	$allQuestions[$question->field_name] = $question->question_name;
	$field_names[] = $question->field_name;
	$field_input_types[$question->field_name] = $question->input_field_type;
}

$groupQuestions = [];
$getGroups = mysqli_query($conn, "SELECT id, group_name FROM questions_group WHERE survey_id='" . $surveyid . "'");
while ($groups = mysqli_fetch_object($getGroups)) {
	$getGroupQuestions = mysqli_query($conn, "SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1'   AND question_name!='' AND survey_id='" . $surveyid . "' AND group_id='" . $groups->id . "'");
	$q = mysqli_fetch_all($getGroupQuestions, MYSQLI_ASSOC);
	$groupQuestions[$groups->group_name] = $q;
}

//$getGroupsscreen = mysqli_query($conn,"SELECT id, group_name FROM questions_group WHERE survey_id='".$surveyid."' AND group_type='screen' ");
$getGroupsscreen = mysqli_query($conn, "SELECT id, group_name,group_type FROM questions_group WHERE survey_id='" . $surveyid . "' ");
$allGroupsscreen = mysqli_fetch_all($getGroupsscreen, MYSQLI_ASSOC);
$scrn = $grpkey = [];
$groupRecords = [];
foreach ($allGroupsscreen as $allGroupsscreen1) {
	$gname = $allGroupsscreen1['group_name'];
	$group_type = $allGroupsscreen1['group_type'];
	$getFieldnames = mysqli_query($conn, "SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1' AND question_name!='' AND survey_id='" . $surveyid . "' AND question_id< (SELECT question_id FROM questions_language WHERE language_id='1' AND field_name='" . $gname . "' AND survey_id='" . $surveyid . "') ORDER BY question_id DESC limit 1;");
	$fnames = mysqli_fetch_object($getFieldnames);
	if ($group_type == 'screen') {
		$scrn[$fnames->field_name] = $gname;
	} else {
		$grpkey[] = $fnames->field_name;
		$field_name[] = $fnames->field_name;
		$groupRecords[$fnames->field_name] = $gname;
	}
}

// print_r($scrn);	
//die();	
///////////////Roster Group Details///////////////////////
$getGroups = mysqli_query($conn, "SELECT id, group_name FROM questions_group WHERE survey_id='" . $surveyid . "' AND group_type='group' ");
$allGroups = mysqli_fetch_all($getGroups, MYSQLI_ASSOC);

foreach ($allGroups as $allGroup) {
	$group_name = $allGroup['group_name'];
	$group_id = $allGroup['id'];

	$getFieldnames = mysqli_query($conn, "SELECT question_lang_id, question_name,dictionary_label, field_name,question_id, input_field_type, max_input, normal_group_id,repeat_count,repeated,default_response,group_id FROM questions_language WHERE language_id='1' AND question_name!='' AND survey_id='" . $surveyid . "' AND question_id< (SELECT question_id FROM questions_language WHERE language_id='1' AND field_name='" . $group_name . "' AND survey_id='" . $surveyid . "') ORDER BY question_id DESC limit 1");
	$rnames = mysqli_fetch_object($getFieldnames);
	$roster_gname[$rnames->field_name] = $group_name;
}
///////////////Roster Group Details///////////////////////
// print_r($grpkey);
//echo "<pre>";
//print_r($survey_data);
// print_r($roster_gname);
//die();
$getOptions = mysqli_query($conn, "SELECT options_language_id, option_id, question_id, language_id, option_name, option_sequence, category_name FROM options_language WHERE survey_id='" . $surveyid . "' and language_id='1' ");
$allOptions = mysqli_fetch_all($getOptions, MYSQLI_ASSOC);
// echo "<pre>";
// print_r($allOptions);

//$jsn_data['uniqueid'] = $details_data->survey_id;
$jsn_data['uniqueid'] = ($sequence_unique_id != '') ? $sequence_unique_id : $details_data->survey_id;

$jsn_data['username'] = $details_data->name;
//$field_namerepArr=$para_datarepArr=$key_stokerepArr=[];
foreach ($survey_data as $seqKey => $surveydata1) {
	//PARA DATA
	$field_name = $surveydata1->field_name;
	$para_data = $surveydata1->para_data;
	$questionid = $surveydata1->question_id;
	$key_stoke = $surveydata1->key_stroke ?? "";

	$field_nameArr[] = $field_name;
	$para_dataArr[] = $para_data;
	$key_stokeArr[] = $key_stoke;
	$question_ids[] = $questionid;
	$seqKeys[] = $seqKey;
	//PARA DATA
	$opt_value = $surveydata1->option_value;
	if ($surveydata1->option_id != "") {
		$search = array("question_id" => $surveydata1->question_id, "option_sequence" => $surveydata1->option_id, "language_id" => 1);

		 $optdata = findData($allOptions, $search);
		//Multiple Option fetch
		/* $allSelChoices=explode(",",$surveydata1->option_id);
			$optionData=[];
			foreach($allSelChoices as $allSelChoice){
				$search = array("question_id"=>$surveydata1->question_id,"option_sequence"=>$allSelChoice, "language_id"=>1);
				$optdata = findData($allOptions, $search);
				$optionData[]=$optdata;
			}
			$opt_value=implode(", ",$optionData); */

		$opt_value = $optdata ? $optdata : $surveydata1->option_id;
	}

	/* if(in_array($surveydata1->field_name,$field_names)){
				
				if($field_input_types[$surveydata1->field_name]=='date'){
					echo "ddddd";
					echo dateformate($opt_value);
					$opt_value = dateformate($opt_value);
				}
			}   */
	$opt_value = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
	$jsn_data[$surveydata1->field_name] = $opt_value;
	if (array_key_exists($surveydata1->field_name, $scrn)) {
		$groupName = $scrn[$surveydata1->field_name];
		$group_data = $full_json->$groupName;



		if (!empty($group_data)) {
			foreach ($group_data[0] as $groupdata) {
				$opt_value = $groupdata->option_value;

				//PARA GROUP DATA
				$field_name = $groupdata->field_name;
				$para_data = $groupdata->para_data;
				$key_stroke = $groupdata->key_stroke;

				$field_nameArr[] = $field_name;
				$para_dataArr[] = $para_data;
				$key_stokeArr[] = $key_stroke;
				$question_ids[] = $questionid;
				$seqKeys[] = $seqKey;
				//PARA GROUP DATA
				if ($groupdata->option_id != "") {
					$search = array("question_id" => $groupdata->question_id, "option_sequence" => $groupdata->option_id, "language_id" => 1);
					$optdata = findData($allOptions, $search);
					//$opt_value = $optdata; //str_replace(",","",$groupdata->option_id);

					$opt_value = $optdata ? $optdata : $groupdata->option_id;
				}

				$fnm = $groupdata->field_name;
				$fnm1 = $groupdata->field_name;
				$jsn_data[$fnm] = str_replace(array("-/", "/"), array("-", "-"), $opt_value);

				////////////Group under question open in roster data/////////////
				if (array_key_exists($fnm1, $groupRecords)) {
					$groupName = $groupRecords[$fnm1];
					$group_data = $full_json->$groupName;
					$grpdata = [];
					if (!empty($group_data)) {
						$grpdata1 = [];
						//$field_namerepArr=[];

						$gr = array();
						foreach ($group_data as $sk => $group_datas) {
							$gr = array();
							//field_namerepArr
							$par_field = array();
							$para_data_rg = $key_stroke_rg = array();
							foreach ($group_datas as $rgkey => $groupdata) {
								//PARA GROUP REPEAT DATA
								$field_name = $groupdata->field_name;
								$para_data = $groupdata->para_data;
								$key_stroke = $groupdata->key_stroke;

								$par_field[] = $field_name . "_" . ($sk + 1);
								$para_data_rg[] = $para_data;
								//echo "hello";
								$key_stroke_rg[] = $key_stroke;

								//PARA GROUP REPEAT DATA
								$opt_value = $groupdata->option_value;
								if ($groupdata->option_id != "") {

									$search = array("question_id" => $groupdata->question_id, "option_sequence" => $groupdata->option_id, "language_id" => 1);
									$optdata = findData($allOptions, $search);

									$opt_value = $optdata ? $optdata : $groupdata->option_id;
								}
								$fnm2 = $groupdata->field_name;
								$gr[$fnm2] = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
							}

							//print_r($grpdata1);
							$grpdata1[] = $gr;
							$grpdata = $grpdata1;
							$field_namerepArr[] = $par_field;
							$para_datarepArr[] = $para_data_rg;
							$key_stokerepArr[] = $key_stroke_rg;
							//$field_namerepArr=$sk;
						}
						// print_r($key_stokerepArr);
						// die();


					} else {
						$grpdata1 = [];
						$group_data = $groupQuestions[$groupName];
						foreach ($group_data as $groupdata) {
							$fnm2 = $groupdata['field_name'];
							$gr[$fnm2] = '';
						}
						$grpdata1[] = $gr;
						$grpdata = $grpdata1;
					}

					$jsn_data[$groupName] = $grpdata;
				}

				////////////////////////////

			}
		} else {
			$group_data = $groupQuestions[$groupName];
			foreach ($group_data as $groupdata) {
				$fnm = $groupdata['field_name'];
				$jsn_data[$fnm] = '';
			}
		}
	}

	// print_r($field_namerepArr);

	if (in_array($surveydata1->field_name, $grpkey)) {
		$groupName = $groupRecords[$surveydata1->field_name];

		$group_data = $full_json->$groupName;
		$grpdata = [];
		if (!empty($group_data)) {
			$grpdata1 = [];
			$gr = array();
			foreach ($group_data as $gkeys => $group_datas) {
				$gr = array();
				$par_field = $para_data_rg = $key_stroke_rg = array();

				foreach ($group_datas as $gkey => $groupdata) {
					$opt_value = $groupdata->option_value;

					//PARA DATA REPEAT GROUP
					$field_name = $groupdata->field_name;
					$para_data = $groupdata->para_data;
					$key_stroke = $groupdata->key_stroke;

					$par_field[] = $field_name . "_" . ($gkeys + 1);
					$para_data_rg[] = $para_data;
					$key_stroke_rg[] = $key_stroke;
					//PARA DATA REPEAT GROUP

					if ($groupdata->option_id != "") {

						$search = array("question_id" => $groupdata->question_id, "option_sequence" => $groupdata->option_id, "language_id" => 1);
						$optdata = findData($allOptions, $search);

						$opt_value = $optdata ? $optdata : $groupdata->option_id;
					}
					$fnm = $groupdata->field_name;
					$gr[$fnm] = str_replace(array("-/", "/"), array("-", "-"), $opt_value);
				}
				$grpdata1[] = $gr;
				$field_namerepArr[] = $par_field;
				$para_datarepArr[] = $para_data_rg;
				$key_stokerepArr[] = $key_stroke_rg;
				$grpdata = $grpdata1;
			}
			//print_r($field_namerepArr);
			//die();


		} else {
			$grpdata1 = [];
			$group_data = $groupQuestions[$groupName];
			foreach ($group_data as $groupdata) {
				$fnm = $groupdata['field_name'];
				$gr[$fnm] = '';
			}
			$grpdata1[] = $gr;
			$grpdata = $grpdata1;
		}

		$jsn_data[$groupName] = $grpdata;
	}
}

$finalParadataArr = array_combine($field_nameArr, $para_dataArr);
// echo "hhhh";
// print_r($finalParadataArr);
// die();
$finalkeystokeArr = array_combine($field_nameArr, $key_stokeArr);
$questionidArr = array_combine($field_nameArr, $question_ids);
$seqKeyArr = array_combine($field_nameArr, $seqKeys);

// $finalParadatarepArr = array_combine($field_namerepArr, $para_datarepArr);
// $finalkeystokerepArr = array_combine($field_namerepArr, $key_stokerepArr);

$finalParadatarepArr = $finalkeystokerepArr = [];

foreach ($field_namerepArr as $index => $fieldNames) {
	foreach ($fieldNames as $subIndex => $fieldName) {
		$finalParadatarepArr[$fieldName] = $para_datarepArr[$index][$subIndex];
		$finalkeystokerepArr[$fieldName] = $key_stokerepArr[$index][$subIndex];
	}
}

//HINT AND ERROR RECORD 
foreach ($full_json as $fjsonkey => $fulljsondata) {

	if (is_array($fulljsondata)) {
		/* hint_record functionalities is disabled */
		/* if($fjsonkey=="hint_record"){  
				$hint_record=count($fulljsondata);
			} */
		if ($fjsonkey == "error_record") {

			$error_record = count($fulljsondata);
		}
	}
}

?>
<?php
$success_rejerror = '';
$errorrej = '';
if (isset($_POST['reject_data'])) {
	$staus = $_POST['reject_data'];
	$updateRej = mysqli_query($conn, "UPDATE survey_data_monitoring SET survey_status='" . $staus . "' WHERE survey_data_monitoring_id='" . $_REQUEST['id'] . "'  ");
	if ($updateRej) {
		$_SESSION['status'] = "Survey has been rejected successfully";
		$_SESSION['status_code'] = "success";
	} else {
		$_SESSION['status_error'] = "Something went wrong!!";
		$_SESSION['status_error_code'] = "error";
	}
}
?>
<?php


if (isset($_POST['reviewed_data_new'])) {
	$staus = $_POST['reviewed_data_new'];
	$tempsql = mysqli_query($conn, "select * from review_survey_data_monitoring where sdm_id = '" . $id . "' and status=0");
	$tot_counts = mysqli_num_rows($tempsql);

	if ($tot_counts > 0) {
		$tempdata = mysqli_fetch_object($tempsql);
		$newjson = $tempdata->dc_full_json;

		$updatedful_json =  $mcrypt->encrypt($newjson);
		// echo "UPDATE survey_data_monitoring SET full_json='".$updatedful_json."', survey_status='".$staus."' WHERE survey_data_monitoring_id='".$_REQUEST['id']."'"; die;
		$updateSurvey = mysqli_query($conn, "UPDATE survey_data_monitoring SET full_json='" . $updatedful_json . "', survey_status='" . $staus . "' WHERE survey_data_monitoring_id='" . $_REQUEST['id'] . "'");

		if ($updateSurvey) {
			mysqli_query($conn, "delete from review_survey_data_monitoring where sdm_id = '" . $id . "'");

			$statuss = sendNotification($firebase_token, $activitis, $message, $json);
			// echo $statuss;
			// print_r($statuss); die;
			$_SESSION['status'] = "Survey has been reviewed and feedback sent to the user";
			$_SESSION['status_code'] = "success";
		} else {
			$_SESSION['status_error'] = "Something went wrong!!";
			$_SESSION['status_error_code'] = "error";
		}
	}
}

if (isset($_POST['reviewed_data'])) {
	$allGroups = $_POST['allGroups'];
	$staus = $_POST['reviewed_data'];
	$ques_id = $_POST['ques_id'];
	$reviewdata = $_POST['reviewdata'];

	foreach ($ques_id as $quesidk => $quesid) {
		$found_key = array_search($quesid, array_column($survey_data, 'question_id'));
		$rviewdata = $reviewdata[$quesidk];
		$survey_data[$found_key]['remarks'] = $rviewdata;
	}

	if ($allGroups != "") {
		$allGroups = explode(",", $allGroups);
		foreach ($allGroups as $allGroup) {
			$gdataQVar = $allGroup . "_ques_id";
			$gdataQArr = $_POST[$gdataQVar];

			$gdataVar = $allGroup . "_reviewdata";
			$gdataArr = $_POST[$gdataVar];
			$found_grArr = $full_json_data[$allGroup];
			foreach ($found_grArr as $ggk => $found_grArrAr) {
				foreach ($gdataQArr[$ggk] as $gkey => $gdataQdata) {
					$found_grkey = array_search($gdataQdata, array_column($found_grArrAr, 'question_id'));
					$rvdata = $gdataArr[$ggk][$gkey];
					$found_grArrAr[$found_grkey]['remarks'] = $rvdata;
				}
				$full_json_data[$allGroup][$ggk] = $found_grArrAr;
				$found_grArrAr = array();
			}
		}
	}
	sendNotification($firebase_token, $activitis, $message, $json);


	// $send=file_get_contents($path);
	// die();
	$full_json_data["survey_data"] = $survey_data;
	//$updatedful_json =  json_encode($full_json_data);
	$updatedful_json =  $mcrypt->encrypt(json_encode($full_json_data));

	$updateSurvey = mysqli_query($conn, "UPDATE survey_data_monitoring SET full_json='" . $updatedful_json . "', survey_status='" . $staus . "' WHERE survey_data_monitoring_id='" . $_REQUEST['id'] . "'  ");

	if ($updateSurvey) {
		$_SESSION['status'] = "Survey has been reviewed and feedback sent to the user";
		$_SESSION['status_code'] = "success";
	} else {
		$_SESSION['status_error'] = "Something went wrong!!";
		$_SESSION['status_error_code'] = "error";
	}
}
?>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Form</a></li>
						<li class="breadcrumb-item" aria-current="page"><i class="fa fa-bars"></i>Form data list</li>
					</ol>
				</nav>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<section class="panel" style="padding:15px;">
					<h4 style="font-weight: bold !important ;padding: 5px; font-size: 16px  ">
						<?= $details_data->survey_name; ?>, Date: <?= date("d M, Y", strtotime($details_data->created_on)); ?>
					</h4>
					<hr>
					<!--approve reject--->
					<form method="post">

						<section class="panel">
							<div class="panel-body">
								<div class="table-responsive">
									<div class="mb-4"> <?= $details_data->survey_id; ?>
										<a href="javascript:void(0);"><span class="label label-success pull-right" style="margin-left:5px;" id="sendReview">Start Review</span></a>
										<!--<span class="label label-primary pull-right" style="margin-left:5px;margin-bottom: 2px;" >Hint Count: <?= $hint_record ?></span>-->
										<span class="label label-primary pull-right" style="margin-bottom: 2px;">Error Count: <?= $error_record ?></span>
									</div>
									<table class="table">
										<thead style="background-color:#449a97;">
											<tr>
												<th style="color:white;width:60%;">Name: Question</th>
												<th style="color:white;">Responses</th>
											</tr>
										</thead>
										<tbody>

											<?php
											// echo "<pre>";
											// print_r($jsn_data);
											foreach ($jsn_data as $key => $jsndata) {

												$info = $finalParadataArr[$key];
												$keystroke = $finalkeystokeArr[$key];

												if (is_array($jsndata)) {

													$bgcolor = '386685'; //substr(str_shuffle('abcdef0123456789'),0,6);
													$rows = 1;
													echo '<tr class="highlight-row"><td colspan="2"> GROUP: ' . ucwords(str_replace("_", " ", $key)) . '</td></tr>';

													foreach ($jsndata as $jsndata1) {

														echo '<tr><td colspan="2" class="fs-6 fw-bold"> Row: ' . $rows . '</td></tr>';
														$i = 0;
														//print_r($jsndata1);
														foreach ($jsndata1 as $skey => $jsndata2) {
															// echo $skey;
															$repeatinfo = $finalParadatarepArr[$skey . '_' . $rows];
															$repeatkeystroke = $finalkeystokerepArr[$skey . '_' . $rows];
															if (!array_key_exists($skey, $allQuestions)) {
																continue;
															}
															$i++;

											?>
															<tr>
															
																<td><b class="repated-ques"><?= $skey . '_' . $rows ?>:</b> <span class="repated-ques"><?= $allQuestions[$skey]; ?></span></td>
																<td><span class="repated-ques"><?php if (empty($jsndata2) && $jsndata2 != "0") {
																									echo 'Not Applicable';
																								} else {
																									echo $jsndata2;
																								} ?> <?php //$jsndata2 ? $jsndata2 : 'Not Applicable';
																										?>

																		<?php if ($repeatinfo != '') {
																			$pararepArr = explode('$', $repeatinfo);
																			$infoRepdata = "";
																			foreach ($pararepArr as $pararepVal) {
																				$repeat_date = str_replace(array("-/", "/"), array("-", "-"), $pararepVal);
																				$infoRepdata .= date('d-M-Y H:i:s', strtotime($repeat_date)) . "<br>";
																			}
																			$infoRepdata .= $repeatkeystroke; ?>
																			<a href="javascript:" data-bs-placement="left" data-bs-toggle="tooltip" class="btn-sm btn-success tooltips pull-right" title=" <?= $infoRepdata; ?> " data-bs-html="true"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
																		<?php }  ?>
																	</span>
																</td>

															</tr>
													<?php
														}
														$rows++;
													}
												} else {
													if (!array_key_exists($key, $allQuestions)) {
														continue;
													}
													$filepaths = "C" . $client_id . "/" . $survey_id;

													$imgFile = $jsndata;
													$imgpathkey = "media_survey/" . $filepaths . "/" . $imgFile;
													$ext = pathinfo($jsndata, PATHINFO_EXTENSION);
													if (strtolower($ext) == "png") {

														$jsndata = '<a href="javascript:void(0)" class="media-show" data-url="' . fileDisplay($s3Client, $imgpathkey) . '" ><img src="' . fileDisplay($s3Client, $imgpathkey) . '" style="height: 50px;"></a>';
													} else if (strtolower($ext) == "mp3") {

														$jsndata = '<audio controls style="height: 26px;">
															<source id="audio" style="display:none" src="' . fileDisplay($s3Client, $imgpathkey) . '" type="audio/ogg">
														</audio>';
													} else if (strtolower($ext) == "mp4") {

														$jsndata = '<video controls style="height: 120px;width:180px;">
													<source src="' . fileDisplay($s3Client, $imgpathkey) . '" type="video/mp4">
													<source src="' . fileDisplay($s3Client, $imgpathkey) . '" type="video/ogg">
												</video>';
													}
													$fields = "client_id, media_file";
													$audioParadata = Multicolumns($conn, 'survey_document', $fields, 'field_name', $key, 'survey_data_monitoring_id', $id, 'status', 0); //Create function in includes/function.php page
													$media_file = $audioParadata->media_file;
													$filepath = "C" . $audioParadata->client_id . "/" . $survey_id;
													$imgpathkey = "media_survey/" . $filepath . "/" . $media_file;
													if ($media_file != '') {
														$tooltip_text = "Paradata Audio";
														$jsndatas = '<audio controls style="height: 26px;width: 215px;margin-left: 117px;" class="tooltips" data-bs-placement="top" data-bs-toggle="tooltip" data-bs-html="true" title="' . $tooltip_text . '">
													<source id="audio" style="display:none" src="' . fileDisplay($s3Client, $imgpathkey) . '" type="audio/ogg">
												</audio>';
													}
													?>
													<tr>
														<td><b><?= $key ?>:</b> <?= $allQuestions[$key]; ?></td>
														<td>
															<?php if (empty($jsndata) && $jsndata != "0") {
																echo 'Not Applicable';
															} else {
																echo $jsndata;
															} ?> <?php //$jsndata ? $jsndata : 'Not Applicable';
																?>

															<?= ($audioParadata != '' && $audioParadata > 0) ? $jsndatas : ''; ?>

															<?php if ($info != '') {
																$paraArr = explode('$', $info);
																$info_data = "";
																foreach ($paraArr as $paraVal) {
																	$datereplace = str_replace(array("-/", "/"), array("-", "-"), $paraVal);
																	$info_data .= date('d-M-Y H:i:s', strtotime($datereplace)) . "<br>";
																}
																$info_data .= $keystroke; ?>
																<a href="javascript:" data-bs-placement="left" data-bs-toggle="tooltip" class="btn-sm btn-success tooltips pull-right" title=" <?= $info_data; ?> " data-bs-html="true"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
															<?php } ?>
															<input type="hidden" class="form-control qreview" name="ques_id[]" value="<?= $questionidArr[$key]; ?>" />
															<input type="text" id="<?= $questionidArr[$key]; ?>" onclick="data_change('<?= $questionidArr[$key]; ?>')" onchange="data_on_change('<?= $questionidArr[$key]; ?>','<?= $id; ?>','<?= $seqKeyArr[$key]; ?>')" class="form-control qreview" name="reviewdata[]" readonly />
														</td>
													</tr>
											<?php
												}
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</section>
						<button type="submit" class="btn btn-success" name="reviewed_data_new" value="4">Send for review</button>
						<button type="submit" class="btn btn-danger" name="reject_data" value="7">Reject</button>
					</form>
				</section>

			</div>
			<!--<div class="row">
		<div class=""> 
		<div class="col-md-12"> 
		<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export_survey_data.php">
		<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
		</div>
		</div>
	</div>-->
			<!-- page end-->
	</section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>


<?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') { ?>
	<script>
		swal.fire({
			title: "<?php echo $_SESSION['status']; ?>",
			icon: "<?php echo $_SESSION['status_code']; ?>",
			confirmButtonColor: '#449A97',
			confirmButtonText: 'Ok'
		}).then(function() {
			// Reload the current page
			<?php unset($_SESSION['status']); ?>
			window.location.href = "<?= base_url(); ?>view_survey.php?id=<?= @$_GET['id']; ?>"
			//location.reload();
		});
	</script>
<?php  }  ?>

<?php if (isset($_SESSION['status_error']) && $_SESSION['status_error'] != '') { ?>
	<script>
		swal.fire({
			title: "<?php echo $_SESSION['status_error']; ?>",
			icon: "<?php echo $_SESSION['status_error_code']; ?>",
			confirmButtonColor: '#449A97',
			confirmButtonText: 'Ok'
		});
	</script>
<?php unset($_SESSION['status_error']);
}  ?>
<script>
	$("#sendReview").on("click", function() {
		//alert('hello');
		$(".qreview").toggle();
	});
</script>
<script>
	$(".media-show").on("click", function() {
		var path = $(this).data("url");

		var imgUrl = path;
		console.log(imgUrl);
		Swal.fire({
			showCancelButton: false,
			showConfirmButton: false,
			width: 480,
			imageUrl: imgUrl, //"https://placeholder.pics/svg/700x500",
			//imageWidth: 490,
			// imageHeight: 490,
			imageAlt: "A tall image",
		});
	})
</script>

<script>
	function data_change(id) {
		// alert('first');
		$('#' + id).prop('readonly', false);

	}

	function data_on_change(qid, id, seq) {

		var qid = qid;
		var sdm_id = id;
		var key_value = $('#' + qid).val();
		var seq_no = seq;

		$.ajax({
			url: "ajax/update_json_remarks.php",
			type: "POST",
			data: {
				'sdm_id': sdm_id,
				'key_value': key_value,
				'seq_no': seq_no
			},
			success: function(res) {
				console.log(res);
				$.toast({
					heading: 'Success',
					text: 'Remark Updated! Please click on the "Send for review" for final submit.',
					showHideTransition: 'slide',
					position: 'top-right',
					bgColor: '#8cc63f',
					icon: 'success'
				})
			}
		})
	}
</script>
<script>
	$(document).ready(function() {
		$('[data-bs-toggle="tooltip"]').tooltip();
	});
</script>