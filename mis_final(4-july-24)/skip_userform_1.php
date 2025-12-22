<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$API_KEY=$_ENV['SUPER_ADMIN_KEY'];
$API_URL=$_ENV['SUPER_ADMIN_URL'];


// Secret key for JWT
$jwtSecretKey = $_ENV['JWT_SECRET_KEY'];
// Secret key for refresh token
$refreshTokenSecretKey = $_ENV['REFRESH_TOKEN_SECRET_KEY']; 

define('hostname','65.0.119.62');
define('username','Mqm_22');
define('password','Mquad@22');
//define('database','new_mquad');
define('database','new_mquad');


define('API_KEY', $API_KEY);
define('API_URL', $API_URL);

define('JWT_SECRET_KEY_THIRD', $jwtSecretKey);
define('REFRESH_TOKEN_SECRET_KEY_THIRD', $refreshTokenSecretKey);

$conn = mysqli_connect(hostname,username,password,database) or die(mysqli_error());
mysqli_set_charset($conn,"utf8");
if(!$conn)
{
    echo "Connection failed.......!";
}

error_reporting(0);
function safe_var($conn,$string){
	$string = trim(str_replace("'","",$string));
    return $clean = mysqli_real_escape_string($conn,$string);
}
define('BASE_URL', "https://mquad.org/mis/");
function base_url(){
    return "https://mquad.org/mis/";
}


function getone($conn, $tablename, $field, $qryfeild, $value)
{
	//echo  "select $field from $tablename where $qryfeild='".$value."'";
	$sn = mysqli_query($conn, "select $field from $tablename where $qryfeild='" . $value . "'") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn->$field);
}

function getMulticolumns($conn, $tablename, $fields, $qryfeild, $value)
{
	$sn = mysqli_query($conn, "select $fields from $tablename where $qryfeild='" . $value . "'") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn);
}

function getdata($conn, $tablename, $field, $where)
{
	// echo "select $field from $tablename where $qryfeild='".$value."'";
	$sn = mysqli_query($conn, "select $field from $tablename $where ") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn->$field);
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Web Form | MQUAD</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="shortcut icon" href="img/mquad-logo.png">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-theme.css" rel="stylesheet">
	<link href="css/elegant-icons-style.css" rel="stylesheet" />
	<link href="css/font-awesome.min.css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet">
	<link href="css/style-responsive.css" rel="stylesheet" />
	<script src="js/jquery.js"></script>
	<link href="css/select2.min.css" rel="stylesheet" />
	<link href="css/select2-bootstrap.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
	<style>
		/*custom font*/
		@import url(https://fonts.googleapis.com/css?family=Open+Sans);
		@primary-color: #63a2cb;
		@secondary-color: #67d5bf;

		/*basic reset*/
		* {
			margin: 0;
			padding: 0;
		}

		html {
			height: 100%;
			background: #0e0e0e;
		}

		body {
			font-family: "Open Sans", arial, verdana;
		}

		/*form styles*/
		#msform {
			/*width: 600px;*/
			min-height: 555px;
			margin: 50px auto;
			text-align: center;
			position: relative;
		}

		#msform fieldset {
			/*background: white;*/
			border: 0 none;
			border-radius: 3px;
			box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
			padding: 20px 30px;
			box-sizing: border-box;
			width: 80%;
			margin: 0 10%;
			min-height: 350px;
			/*stacking fieldsets above each other*/
			/*position: absolute;*/
			background-color:white;
			margin-top:-28px;
		}

		/*Hide all except first fieldset*/
		#msform fieldset:not(:first-of-type) {
			display: none;
		}

		/*inputs*/
		#msform input,
		/*#msform textarea*/ {
			padding: 15px;
			border: 1px solid #ccc;
			border-radius: 3px;
			margin-bottom: 10px;
			width: 100%;
			box-sizing: border-box;
			font-family: montserrat;
			color: #2C3E50;
			font-size: 13px;
		}
		.select2-container {
			display: block;
			margin: 0;
			margin: 0 auto;
			width: 100% !important;
		}
		/*buttons*/
		#msform .action-button {
			width: 100px;
			background: #003b64;
			font-weight: bold;
			color: white;
			border: 0 none;
			border-radius: 1px;
			cursor: pointer;
			padding: 10px 5px;
			margin: 10px 5px;
		}

		#formSubmit{
			background-color: green !important;
		}

		#msform .action-button:hover,
		#msform .action-button:focus {
			box-shadow: 0 0 0 2px white, 0 0 0 3px #5f5fb9;
		}

		/*headings*/
		.fs-title {
			font-size: 16px;
			text-transform: uppercase;
			color: @primary-color;
			margin-bottom: 10px;

			/* background: lightblue; */
			padding: 8px;
			border-radius: 5px;
			color: black;
			font-weight: bold;

		}

		.fs-subtitle {
			font-weight: normal;
			font-size: 15px;
			color: #666;
			margin-bottom: 20px;
			text-align: justify;
		}

		/*progressbar*/
		#progressbar {
			margin-bottom: 30px;
			overflow: hidden;
			/*CSS counters to number the steps*/
			counter-reset: step;
		}

		#progressbar li {
			list-style-type: none;
			color: white;
			text-transform: uppercase;
			font-size: 9px;
			width: 10%;
			float: left;
			position: relative;
		}

		#progressbar li:before {
			content: counter(step);
			counter-increment: step;
			width: 20px;
			line-height: 20px;
			display: block;
			font-size: 10px;
			color: #333;
			background: white;
			border-radius: 3px;
			margin: 0 auto 5px auto;
		}

		/*progressbar connectors*/
		#progressbar li:after {
			content: '';
			width: 100%;
			height: 2px;
			background: white;
			position: absolute;
			left: -50%;
			top: 9px;
			z-index: -1;
			/*put it behind the numbers*/
		}

		#progressbar li:first-child:after {
			/*connector not needed before the first step*/
			content: none;
		}

		/*marking active/completed steps green*/
		/*The number of the step and the connector before it = green*/
		#progressbar li.active:before,
		#progressbar li.active:after {
			background: @secondary-color;
			color: white;
		}

		.help-block {
			font-size: .8em;
			color: #7c7c7c;
			text-align: left;
			margin-bottom: .5em;
		}
		#error_message{
			color: red;
			font-weight: bold;
			font-style: italic;
			height: 7px;
		}
		#error_messaged{
			color: red;
			font-weight: bold;
			font-style: italic;
			height: 7px;
		}

		/* ERROR MESSAGE ANIMATION CSS */
		.ssanimation {
			-webkit-animation: sss_animate 0.4s 1 linear;
			-moz-animation: sss_animate 0.4s 1 linear;
			-o-animation: sss_animate 0.4s 1 linear;
		}
		@-webkit-keyframes sss_animate {
			0% { -webkit-transform: translate(30px); }
			20% { -webkit-transform: translate(-30px); }
			40% { -webkit-transform: translate(15px); }
			60% { -webkit-transform: translate(-15px); }
			80% { -webkit-transform: translate(8px); }
			100% { -webkit-transform: translate(0px); }
		}
		@-moz-keyframes sss_animate {
			0% { -moz-transform: translate(30px); }
			20% { -moz-transform: translate(-30px); }
			40% { -moz-transform: translate(15px); }
			60% { -moz-transform: translate(-15px); }
			80% { -moz-transform: translate(8px); }
			100% { -moz-transform: translate(0px); }
		}
		@-o-keyframes sss_animate {
			0% { -o-transform: translate(30px); }
			20% { -o-transform: translate(-30px); }
			40% { -o-transform: translate(15px); }
			60% { -o-transform: translate(-15px); }
			80% { -o-transform: translate(8px); }
			100% { -o-origin-transform: translate(0px); }
		}
	.legend {
	   width: max-content;
		padding: 0px 4px;
		margin-bottom: 0;
		font-size: 18px;
		letter-spacing: .02em;
		font-weight: 500;
		line-height: normal;
		margin-top: -12px;
		background: #fff;
	   color: #003b64;
		display: block;
	}
	.fieldset {
		min-width: auto;
		padding: 0px 20px 20px;
		margin: 2rem 0px;
		position: relative;
		border: 1px solid #d8e2ef !important;
		border-radius: 0.25rem;
	}
	</style>
	<style>
	body  {
		  background-image: url("img/form3.png");
		  background-repeat: no-repeat;
		 background-attachment: fixed;
		 background-size: cover;
		 
		}
		.thankyou-page ._header {
		padding: 45px 30px;
		padding: 46px;
    text-align: center;
    background: #003b64;
    min-height: 297px;
    border-radius: 4px;
    display: flex;
    flex-direction: column;
    justify-content: center;
		}
		.thankyou-page ._header h3{
			margin-bottom: 20px;
		}
		.d-none{
			display:none;
		}
		ul > li{
			cursor:pointer;
		}
		.custom-logo{
			display: flex;
			align-items: center;
			margin-top: 15px;
			justify-content: space-between;
		}
		.custom-logo h3{
			margin: 0;
			
			font-weight: 800;
		}
		.start-survey{
			display: flex;
			max-width: 80%;
			justify-content: center;
			margin: 0 auto;
		}
		@media (max-width:991px){
			#msform fieldset{
				width: 100% !important;
				margin: 0  !important;
			}
			.start-survey{
				max-width: 100%;
			}
			#msform {
				margin-top: 17px;
			}
			.thankyou-page ._header {
				padding: 23px;
				display: flex;
				height: 100%;
				flex-direction: column;
				justify-content: center;
				min-height: 300px;
			}
			.thankyou-page ._header h3{
				margin-bottom: 15px;
			}
		}
	</style>
	
<div id="pre-load" class="loading-indicator">
		<div id="loader" class="loader">
			<div class="loader-container">
				<div class='loader-icon'><img src="https://mquad.org/mis/img/mquad-logo.png" alt=""></div>
			</div>
	   </div>              
	</div>	
	
</head>

<body>
<?php

$survey_id = $_REQUEST['survey_id'];
//$questionPath = "https://mquad.org/mis/api/questions_v3.php?survey_id=".$survey_id; //"questions.js"; // JSON API PATH
$questionPath = "https://mquad.org/mis/api/questionsjson/s".$survey_id.".json";
$questionJsondata = file_get_contents($questionPath);
$data = json_decode($questionJsondata); // Convert to array

$languages = $data->language; //Array
$languagegroups = $data->language_group;

$groupsQuestions = (array)$data->groups;

$survey_name = getone($conn, "survey", "survey_name", "id", $survey_id);

?>
<?php
	$isDisabled = "";
	if(isset($_REQUEST['langId']) && $_REQUEST['langId']>0)
	{
		$languageid = $_REQUEST['langId'];
		$isDisabled = "disabled"; 
	} 
function getUserName($default_response) {
    if ($default_response == '#name') {
        if (isset($_SESSION['name'])) {
            return $_SESSION['name'];
        } else {
            return "Name not found";
        }
    } elseif ($default_response == '#username') {
        if (isset($_SESSION['username'])) {
            return $_SESSION['username'];
        } else {
            return "Username Not Found";
        }
    } else {
        return "Invalid Value";
    }
}

	
?>

	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<div class="custom-logo">					
					<img src="https://mquad.org/mis/img/mquad-logo.png" alt="sss" style="height: 80px;">
					<h3><?=$survey_name;?></h3>
					<a href="survey-list.php" class="btn btn-primary" style="margin-right:-82% !important;">Back</a>
				</div>
			</div>
			<div class="col-sm-6">
			</div>
		</div>
		<hr style="border-color: #8080804d;">
		<div class="row">
		
			<div class="col-sm-12"> 
				<div class="start-survey">
					<select name="langId" id="langId" class="form-control" <?php echo $isDisabled; ?> >
						<option value="">Select Language</option>
						<?php
							foreach($languages as $language){ ?>
								<option value="<?=$language->language_id;?>" <?php if($_REQUEST['langId']==$language->language_id){ echo "selected";} ?> ><?=$language->language_name;?></option>
							<?php
							}
						?>
					</select>
					<a href="javascript:void(0);" id="startinterview" class="btn btn-primary" style="margin-left:10px;" <?php echo $isDisabled; ?> >Start</a>
					<input  type="hidden" name="GPS_latitude_start" id="GPS_latitude_start" />  <input  type="hidden" name="GPS_longitude_start" id="GPS_longitude_start" />
				</div>
				
				
				<form id="msform">
					<?php if(empty($_REQUEST['langId'])){ ?>
					<div class="thankyou-page">
						<fieldset>
						<div class="_header">
							<h3 style="color: #fff; font-weight:bold;">Please select the language and start the interview.</h3>
							<strong><h4 style="color: #fff; font-weight:bold;">Once start the interview you can not change the language.</h4></strong>
						</div>
						</fieldset>
					</div>	
					<?php } ?>
					<h3 id="error_message"></h3>
					<h4 id="error_messaged"></h4>
					<?php
					
					// echo "<pre>";
					// print_r($languagegroups);
					foreach ($languagegroups as $lk => $lgv) {
						$lvgid = $lgv->language_id;
						$groups = $lgv->group;
						// echo "<pre>";
						// print_r($groups);
						if ($lvgid == $languageid) {
							
							$i = 0;
							$questions = $groups[0]->screens[0]->questions;
							// echo "<pre>";
							// print_r($questions);
							$grp_id = $groups[$lk]->group_id;
							$singleScreenGroups = [];
							$repeatedScreenGroups = [];
							foreach ($questions as $question) {
								$question_id = $question->question_id;
								$question_name = $question->question_name;
								$question_input_type = $question->question_input_type;
								$question->question_type;
								$field_name = $question->field_name;
								$question->validation_id;
								$max_limit = $question->max_limit;
								$read_only=$question->read_only;
								$constraints = $question->constraints;
								$question->constraint_msg;
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
								if($screened>0){
									$position_id = $groupsQuestions[$screened]->position_id;
									$singleScreenGroups[] = $group_name = $groupsQuestions[$screened]->group_name;
									$singleScreenQuestions = $groups[$position_id]->screens[0]->questions;
								}
								///// FOR ROSTER GROUP ====>Written by Khushboo
								//echo "hello";
								$repeated = $question->repeated; 
								$repeat_count = $question->repeat_count;
								$repeatedScreenQuestions = [];
								if($repeated>0){
								$position_id = $groupsQuestions[$repeated]->position_id;
								$repeatedScreenGroups[] = $group_name = $groupsQuestions[$repeated]->group_name;
								$repeatedScreenQuestions = $groups[$position_id]->screens[0]->questions;
								}
								//print_r($repeatedScreenGroups);
								//////////////////////////
								
								?>
								<?php 
									date_default_timezone_set('Asia/Kolkata');
									$month = date('m');
									$monthName = date('F');
									$day = date('d');
									$dayOfWeek = date('w');
									$year = date('Y');
									$nowtime = date("H:i:s", time());
									$nowdate = $year . '-' . $month . '-' . $day;
									$nowdatetime = $year . '-' . $month . '-' . $day.' '.$nowtime;
									
									$now_month_year = date("Y-m", time());
									$nowdays=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
									$months = [];
									for ($month = 1; $month <= 12; $month++) {
										$month_name = date("F", mktime(0, 0, 0, $month, 1));
										$months[] = $month_name;
									}
									if($default_response=='#name' || $default_response=='#username'){
										$login_user_name = getUserName($default_response); //create a function in this page
									}
									
								?>
								
								<!----START CREATING FORM----->
								<?php
								if (strtolower($question_input_type) == 'note') {
									$i++; ?>

									<fieldset id="ss<?= $i; ?>">
										<!--<h2 class="fs-title">Question <?= $i; ?> -><?= $field_name; ?></h2> -->
										<h3 class="fs-subtitle">Note: <?= strip_tags($question_name); ?></h3>
										<!-- <input type="hidden" id="<?= $field_name; ?>" value="note" /> -->

										<input type="hidden" id="relevant<?= $i; ?>" data-inputType="note" data-constraints="<?=$constraints;?>" value="<?= $relevant; ?>" />
										<input type="button" name="previous" class="previous action-button" value="Previous" />
										<button type="button" name="next" class="next action-button" value="<?= $i; ?>">Next</button>
									</fieldset>
								<?php
								//Lookups value fetch
								} else if (strtolower($lookups)=='yes' && ((strtolower($question_input_type) == 'integer' || strtolower($question_input_type) == 'number' || strtolower($question_input_type) == 'text'))) {
									$i++; ?>
									<fieldset id="ss<?= $i; ?>">
										<h3 class="fs-subtitle"><?= $question_name;  ?></h3>
										<input type="<?= $question_input_type; ?>" class="form-control lookups_value" id="<?= $field_name; ?>" name="<?= $field_name; ?>" maxlength="<?= $max_limit; ?>" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="<?= $question_name; ?>" required />

										<input type="hidden" id="relevant<?= $i; ?>" data-inputType="<?= strtolower($question_input_type) ?>" data-constraints="<?= $constraints; ?>" value="<?= $relevant; ?>" />
										<input type="hidden" id="default_response<?= $i; ?>" data-inputType="<?=strtolower($question_input_type)?>" data-field="<?= $field_name; ?>" value="<?= $default_response; ?>" data-respone-name='<?=$login_user_name ?>' />
										<input type="button" name="previous" class="previous action-button" value="Previous" />
										<button type="button" name="next" class="next action-button lookups_next" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
									</fieldset>
								<?php 
								} else if ((strtolower($question_input_type) == 'integer' || strtolower($question_input_type) == 'number' || strtolower($question_input_type) == 'text')) {
									$i++; 
									
									?>
									<fieldset id="ss<?= $i; ?>">
										<h3 class="fs-subtitle"><?= $question_name; ?></h3>
										
										<?php if($question_input_type=='number' && $constraints!='' && $appearance =='dropdown'){  ?>
											<select class="form-control constantMinMax<?= $i; ?>" name="<?= $field_name; ?>" data-id="<?=$field_name?>" id="<?= $field_name; ?>" data-constraints="<?=$constraints;?>" <?php if(strtolower($read_only)=='yes'){ echo 'disabled';} ?> required>
												
											</select>
										<?php	} ?>
										<?php if($question_input_type=='number' && $constraints!='' && $calculation!=''){  ?>
											<input type="<?=$question_input_type;?>" class="form-control calculation<?= $i; ?>" name="<?= $field_name; ?>" data-id="<?=$field_name?>" id="<?= $field_name; ?>" data-calculation="<?=$calculation;?>"  data-constraints="<?=$constraints;?>" <?php if(strtolower($read_only)=='yes'){ echo 'disabled';} ?> required />
												
										<?php	} ?>
										<input type="<?=$question_input_type;?>"  class="form-control repeated_question" id="<?= $field_name; ?>" name="<?= $field_name; ?>"  maxlength="<?=$max_limit;?>" data-repeat="<?=$repeated;?>" data-question="<?=$question_id;?>" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="<?= $question_name; ?>" required />
										<input type="hidden" id="relevant<?= $i; ?>" data-inputType="<?=strtolower($question_input_type)?>" data-constraints="<?=$constraints;?>" value="<?= $relevant; ?>" />
										<input type="hidden" id="default_response<?= $i; ?>" data-inputType="<?=strtolower($question_input_type)?>" data-field="<?= $field_name; ?>" value="<?= $default_response; ?>" data-respone-name='<?=$login_user_name ?>' />
										
										<div id="<?= $field_name; ?><?= $question_id; ?>"></div>
										<!--<div id="repeatTest"></div>-->
										
										<input type="button" name="previous" class="previous action-button" value="Previous" />
										<button type="button" name="next" class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
									</fieldset>

								<?php
								} else if ((strtolower($question_input_type) == 'date' || strtolower($question_input_type) == 'time')) {
									$i++; ?>

									<fieldset id="ss<?= $i; ?>">
										<h3 class="fs-subtitle"><?= $question_name; ?></h3>
										<h3 class="khushboo"></h3>
										<!--------------Appearance date condition start-------------------------->
										<?php if($appearance == 'days' || $appearance == 'time' || $appearance == 'datetime' || $appearance == 'month-year' || $appearance == 'month' || $appearance == 'year'){
										
											if ($appearance == 'days') { 
												?>
												<select class="form-control" name="<?= $field_name; ?>" id="<?= $field_name; ?>" <?php if(strtolower($read_only)=='yes'){ echo 'disabled';} ?> required>
													<option value="Select Days">Select Days</option>
												<?php 
													foreach($nowdays as $key => $nowday) { 
														$selectedval = ($default_response == 'nowdays' && $key == $dayOfWeek) ? "selected" : ""; 
														?>
														<option value="<?= $nowday ?>" <?= $selectedval ?>><?= $nowday ?></option>
												<?php 
													} 
												?>
												</select>
											<?php 
											} else if($appearance == 'time'){ 
												$selectedval = ($default_response == 'nowtime') ? "value='$nowtime'" : "";
												$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
											?>
												<input type="time" class="form-control" id="<?= $field_name; ?>" <?=$read_only?> name="<?= $field_name; ?>" required  <?=$selectedval;?>/>
											
											<?php }else if($appearance =='datetime'){ 
												$selectedval = ($default_response == 'nowdate') ? "value='$nowdatetime'" : "";
												$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
											?>
												<input type="datetime-local" class="form-control" id="<?= $field_name; ?>" <?=$read_only;?> name="<?= $field_name; ?>" required <?=$selectedval?>/>
										
										<?php } else if($appearance =='month-year'){ 
												$selectedval = ($default_response == 'nowmonth-year') ? "value='$now_month_year'" : "";
												$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
											?>
												<input type="month" class="form-control" id="<?= $field_name; ?>" <?=$read_only;?> name="<?= $field_name; ?>" required  <?=$selectedval;?>/>
										
										<?php }else if($appearance =='month'){ ?>
												<select class="form-control" name="<?= $field_name; ?>" <?php if(strtolower($read_only)=='yes'){ echo 'disabled';}?> id="<?= $field_name; ?>" required>
												  <option value="Select Month">Select Month</option>
												  <?php 
												  foreach($months as $key=>$monthdata){ 
													$selectedval = ($default_response == 'nowmonth' && $key == $monthName) ? "selected" : ""; 
												  ?>
												  <option value="<?=$monthdata?>" <?=$selectedval;?>><?=$monthdata?></option>
												  <?php }
												  ?>
												</select>
										<?php }else if($appearance =='year'){
												$selectedval = ($default_response == 'nowyear') ? "value='$year'" : "";
												$read_only = (strtolower($read_only) == 'yes') ? "readonly" : "";
											?>
												<input class="form-control datepicker" id="<?= $field_name; ?>" <?=$read_only;?> name="<?= $field_name; ?>" required <?=$selectedval?>/>
										
										<?php } } ?>
											<!----Appearance date condition end--->
											<!----Constraints date currdate and futuredate start--->
									
										<?php 
										
										if($default_response=='' && $appearance !='datetime' && $constraints==''){ ?>
											<input type="<?=strtolower($question_input_type)?>" class="form-control" id="<?= $field_name; ?>" name="<?= $field_name; ?>" placeholder="<?= $question_name; ?>" required />
										
										<?php }else if($default_response=='' && $appearance =='' && ($constraints=='currdate' || $constraints=='futuredate')){ 
												if($constraints=='currdate'){
													$DateValue="max='".$nowdate."'";
												}else if($constraints=='futuredate'){
													$DateValue="min='".$nowdate."'";
												}
										?>
											<input type="<?=strtolower($question_input_type)?>" class="form-control" <?=$DateValue?> id="<?= $field_name; ?>" <?php if(strtolower($read_only)=='yes'){ echo 'readonly';}?> name="<?= $field_name; ?>" required />
										
										<?php } ?>
										<!----Constraints date currdate and futuredate end--->
										<!----default_respons date condition start------>
										<?php if($appearance=='' && ($default_response=='nowdate' || $default_response=='nowtime' || $default_response=='nowmonth-year' || $default_response=='nowmonth' || $default_response=='nowyear' || $default_response=='nowdays')){
											
										 if($default_response=='nowdate'){ //done ?>
											<input type="<?=strtolower($question_input_type)?>" class="form-control" id="<?= $field_name; ?>" <?php if(strtolower($read_only)=='yes'){ echo 'readonly';}?> name="<?= $field_name; ?>" required value="<?=$nowdate?>"/>
										
										<?php } 
										 else if($default_response=='nowtime'){ //done ?>
											<input type="time" class="form-control" id="<?= $field_name; ?>" <?php if(strtolower($read_only)=='yes'){ echo 'readonly';}?> name="<?= $field_name; ?>" required  value="<?=$nowtime; ?>"/>
										
										<?php }
										else if($default_response=='nowmonth-year'){ //done ?>
											<input type="month" class="form-control" id="<?= $field_name; ?>" <?php if(strtolower($read_only)=='yes'){ echo 'readonly';}?> name="<?= $field_name; ?>" required  value="<?=$now_month_year; ?>"/>
										
										<?php } 
										 else if($default_response=='nowmonth'){ //done?>
										<select class="form-control" name="<?= $field_name; ?>" <?php if(strtolower($read_only)=='yes'){ echo 'disabled';}?> id="<?= $field_name; ?>" required>
										  <option value="Select Month">Select Month</option>
										  <?php 
										  foreach($months as $key=>$monthdata){ //done?>
										  
										  <option value="<?=$monthdata?>" <?php if($monthdata==$monthName){ echo "selected"; }?>><?=$monthdata?></option>
										  <?php }
										  ?>
										</select>
										<?php }
										else if($default_response=='nowyear'){ //done?>
											<input class="form-control datepicker" id="<?= $field_name; ?>" <?php if(strtolower($read_only)=='yes'){ echo 'disabled';}?> name="<?= $field_name; ?>" required  value="<?=$year; ?>"/>
										
										<?php }
										else if($default_response=='nowdays'){ //done?>
											<select class="form-control" name="<?= $field_name; ?>" id="<?= $field_name; ?>" required>
												<option value="Select Days">Select Days</option>
											<?php 
												foreach($nowdays as $key=>$nowday){
													//done?>
													<option value="<?=$nowday?>" <?=($key == $dayOfWeek) ? "selected" : ""?>><?=$nowday?></option>
											<?php } ?>
											</select>
										<?php }
										
										}
										
										?>
										<!----default_respons date condition end---->
										<input type="hidden" id="relevant<?= $i; ?>" data-inputType="<?=strtolower($question_input_type)?>" data-constraints="<?=$constraints;?>" value="<?= $relevant; ?>" />
										<input type="button" name="previous" class="previous action-button" value="Previous" />
										<button type="button" name="next" class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
									</fieldset>

								<?php
								} else if (strtolower($question_input_type) == 'select_one') {
									$i++; ?>

									<?php 
										if($appearance=="dropdown"){ ?>
											<fieldset id="ss<?= $i; ?>">
												<h3 class="fs-subtitle"><?= $question_name; ?></h3>
												<select class="form-control" name="<?= $field_name; ?>" id="<?= $field_name; ?>" required>
													<option value="">Select Option</option>
													<?php
													$steps .= '<span class="step"></span>';
													foreach ($question_options as $questionoption) {
														// echo $questionoption->option_value;
														// echo $questionoption->option_id; 
														// echo $questionoption->likert_img;
														// echo $questionoption->option_type;
													?>
														<option value="<?= $questionoption->option_id; ?>"><?= $questionoption->option_value; ?></option>
													<?php
													}
													?>
												</select>


												<input type="hidden" id="relevant<?= $i; ?>" data-inputType="select_one" data-constraints="<?=$constraints;?>" value="<?= $relevant; ?>" />
												<input type="button" name="previous" class="previous action-button" value="Previous" />
												<button type="button" name="next"  class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
											</fieldset>
										<?php	
										}else{
											if($appearance =='quick'){ 
												$quickfieldset= "class='quickfieldset'";
												$quickoptionul="class='quickoptionul'";
												$quickoptionli="class='quickoptionli'";
											}
											?>
											<fieldset id="ss<?= $i; ?>" <?=$quickfieldset?>>
												<h3 class="fs-subtitle"><?= $question_name; ?></h3>
												<input type="text" class="d-none" name="<?= $field_name; ?>" id="<?= $field_name; ?>" />
												<ul style="text-align:left;" <?=$quickoptionul?>>
												<?php
													$steps .= '<span class="step"></span>';
													foreach ($question_options as $questionoption) {
														
													?>
														<li <?=$quickoptionli?> onclick="setRadioValue(<?= $questionoption->option_id; ?>,'<?= $field_name; ?>','<?= $field_name.$questionoption->option_id; ?>')"  ><i class="fa fa-circle-o <?= $field_name; ?>" id="<?= $field_name.$questionoption->option_id; ?>"></i> <?= $questionoption->option_value; ?> </li>
													<?php
													}
												?>
												</ul>
												
												<input type="hidden" id="relevant<?= $i; ?>" data-inputType="radio" data-constraints="<?=$constraints;?>" value="<?= $relevant; ?>" />
												<input type="button" name="previous" class="previous action-button" value="Previous" />
												<button type="button" name="next"  class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
											</fieldset>
										<?php
											$quickfieldset='';
										}
									?>

								<?php
								} else if (strtolower($question_input_type) == 'select_multiple') {
									$i++; ?>

									<fieldset id="ss<?= $i; ?>">
										<!-- <h2 class="fs-title">Question <?= $i; ?></h2> -->
										<h3 class="fs-subtitle"><?= $question_name; ?></h3>
										<!-- <input type="hidden" value="<?= $question_input_type; ?>" name="qtype[<?= $field_name; ?>]" /> -->
										<!-- <input type="hidden" value="<?= $question_id; ?>" name="questions[<?= $field_name; ?>]" /> -->
										
										<input type="text" class="d-none" name="<?= $field_name; ?>" id="<?= $field_name; ?>" />
										<select class="form-control multiple-select" multiple="multiple" onchange="multiSelect('<?= $field_name; ?>')" id="satyendra<?= $field_name; ?>" maxlength="<?=$max_limit;?>" data-placeholder="<?= $question_name; ?>" multiple required>
											<option value="">Select Option</option>
											<?php
											foreach ($question_options as $questionoption) {
											?>
												<option value="<?= $questionoption->option_id; ?>"><?= $questionoption->option_value; ?></option>
											<?php
											}
											?>
										</select>


										<input type="hidden" id="relevant<?= $i; ?>" data-inputType="select_multiple" data-constraints="<?=$constraints;?>" value="<?= $relevant; ?>" />
										<input type="button" name="previous" class="previous action-button" value="Previous" />
										<button type="button" name="next"  class="next action-button" data-id="<?= $field_name; ?>" value="<?= $i; ?>">Next</button>
									</fieldset>

								<?php
								}
								?>
								<!----END CREATING FORM---->
								
								
								<!---SINGLE SCREEN QUESTIONS FORMS START -->
								<?php
									if(count($singleScreenQuestions)>0){ $i++; ?>
										<fieldset id="ss<?= $i; ?>">
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
											?>
											<?php if (strtolower($question_input_type1) == 'note') { ?>
												<h3 class="fs-subtitle">Note: <?= strip_tags($question_name1); ?></h3>
											<?php }else if ((strtolower($question_input_type1) == 'integer' || strtolower($question_input_type1) == 'number' || strtolower($question_input_type1) == 'text')) { ?>
												<h3 class="fs-subtitle"><?= $question_name1; ?></h3>
												<input type="<?=$question_input_type1;?>"  class="form-control <?=$group_name;?>" id="<?= $field_name1; ?>" data-id="<?= $field_name1; ?>"  maxlength="<?=$max_limit1;?>" placeholder="<?= $question_name1; ?>" required />
											<?php }else if ((strtolower($question_input_type1) == 'date' || strtolower($question_input_type1) == 'time')) { ?>
												<h3 class="fs-subtitle"><?= $question_name1; ?></h3>
												<input type="<?=strtolower($question_input_type1)?>" class="form-control <?=$group_name;?>" id="<?= $field_name1; ?>" data-id="<?= $field_name1; ?>" placeholder="<?= $question_name1; ?>" required />
											<?php }else if (strtolower($question_input_type1) == 'select_one') { ?>
												
												<?php if($appearance1=="dropdown"){ ?>
													<h3 class="fs-subtitle"><?= $question_name1; ?></h3>
													
													<select class="form-control <?=$group_name;?>" data-id="<?= $field_name1; ?>" id="<?= $field_name1; ?>" required>
														<option value="">Select Option</option>
														<?php
														$steps .= '<span class="step"></span>';
														foreach ($question_options1 as $questionoption1) {
															// echo $questionoption->option_value;
															// echo $questionoption->option_id; 
															// echo $questionoption->likert_img;
															// echo $questionoption->option_type;
														?>
															<option value="<?= $questionoption1->option_id; ?>"><?= $questionoption1->option_value; ?></option>
														<?php
														}
														?>
													</select>
												<?php }else{ ?>
													<h3 class="fs-subtitle"><?= $question_name1; ?>  </h3>
													<input type="text" class="d-none <?=$group_name;?>" data-id="<?= $field_name1; ?>"  id="<?= $field_name1; ?>" />
													<ul style="text-align:left;">
													<?php
														$steps .= '<span class="step"></span>';
														foreach ($question_options1 as $questionoption1) {
															// echo $questionoption->option_value;
															// echo $questionoption->option_id; 
															// echo $questionoption->likert_img;
															// echo $questionoption->option_type;
														?>
															<li onclick="setRadioValue(<?= $questionoption1->option_id; ?>,'<?= $field_name1; ?>','<?= $field_name1.$questionoption1->option_id; ?>')"  ><i class="fa fa-circle-o <?= $field_name1; ?>" id="<?= $field_name1.$questionoption1->option_id; ?>"></i> <?= $questionoption1->option_value; ?> </li>
															<!--<input type="radio" name="<?= $field_name1.$i; ?>"  value="<?= $questionoption1->option_id; ?>" onclick="setRadioValue(this.value,<?= $field_name1; ?>)" /> <?= $questionoption1->option_value; ?> <br>-->
														<?php
														}
													?>
													</ul>
												<?php	
												} ?>
												
											<?php }else if (strtolower($question_input_type1) == 'select_multiple') { ?>
												<!-- <h2 class="fs-title">Question <?= $i; ?></h2> -->
												<h3 class="fs-subtitle"><?= $question_name1; ?></h3>
												
												<input type="text" class="d-none" data-id="<?= $field_name1; ?>" id="<?= $field_name1; ?>" />
												<select class="form-control multiple-select <?=$group_name;?>" multiple="multiple" onchange="multiSelect('<?= $field_name1; ?>')" id="satyendra<?= $field_name1; ?>"  maxlength="<?=$max_limit1;?>" data-placeholder="<?=$question_name1;?>" multiple required>
													<option value="">Select Option</option>
													<?php
													foreach ($question_options1 as $questionoption1) {
													?>
														<option value="<?= $questionoption1->option_id; ?>"><?= $questionoption1->option_value; ?></option>
													<?php
													}
													?>
												</select>
											<?php } ?>
											
												
											<?php	
											} 
										?>
											<input type="hidden" id="relevant<?= $i; ?>" value="<?= $relevant1; ?>" />
											<input type="button" name="previous" class="previous action-button" value="Previous" />
											<button type="button" name="next" class="next action-button" data-id="<?= $field_name1; ?>" value="<?= $i; ?>">Next</button>
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
				<?php $sss=implode(",",$singleScreenGroups);?>
				<input type="hidden" id="allSingleGroup" value="<?php echo $sss;?>" />
				
			</div>
		</div>
	</div>
</body>

</html>
<!-- jQuery -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>-->
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<!-- jQuery easing plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
	
<!----//REPEATED GROUP QUESTION DISPLAY START------------------->
<script>
	$(".repeated_question").keyup(function(e){
		let repeated=$(this).attr("data-repeat");
		let question_id=$(this).attr("data-question");
		let field_name=$(this).attr("id");
		let input_value = $(this).val();
		let survey_id="<?=$_REQUEST['survey_id'];?>";
		if(repeated>0 && repeated!=''){
			$.ajax({
			  type:'POST',
			  url:'ajax_page.php',
			   data: {
					questionId: question_id,
					input_value: input_value,
					survey_id: survey_id
				},
			  success:function(result){
				$('#'+field_name+question_id).html(result);
				 //console.log($('#'+field_name+question_id));
			  }
			});
		}
		
	});
</script>
<!----//REPEATED GROUP QUESTION DISPLAY END------------------->
<script>

	$(".multiple-select").select2({
	
	});
</script>
<script>

 $(".datepicker").datepicker({
    format: "yyyy",
    viewMode: "years", 
    minViewMode: "years",
    autoclose:true //to close picker once year is selected
});
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
				console.log(data);

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
	function setRadioValue(selectRadio,setId,iconId){
		$('#'+setId).val(selectRadio);
		
		$('.'+setId).removeClass("fa fa-dot-circle-o");
		$('.'+setId).addClass("fa fa-circle-o");
		$('#'+iconId).addClass("fa fa-dot-circle-o");
	}
	
	function multiSelect(mselId){
		var msSlectedVal = $("#satyendra"+mselId).val().join();
		$('#'+mselId).val(msSlectedVal);
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
$(document).ready(function(){
	getLocation()
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
</script>
<script>

	$(document).ready(function(){
		// $("#langId").on("change", function(){
		$("#startinterview").click(function(){
			
			var langId = $("#langId").val();
			var surId = "<?=$_REQUEST['survey_id'];?>";
			
			var redirectURL = 'https://mquad.org/mis/skip_userform_1.php?survey_id=' + surId + '&langId=' + langId;
			//alert(redirectURL);
			 if(langId!=""){
				 window.location.href=redirectURL;
				//$("#langId").attr("readonly",true);
				//$("#startinterview").attr("disabled",true);
			}
		});
	});
	//Default Response set value function start
	function concatenateValues(firstId, secondId, resultId) {
		let firstValue = $("#" + firstId).val();
		let secondValue = $("#" + secondId).val();
		let concatenatedValue = firstValue + secondValue;
		$("#" + resultId).val(concatenatedValue);
	}
	function processDefaultResponse(num) {
		let isdefaultResponse = $("#default_response" + num).val();
		if (isdefaultResponse) {
			let datafield_one = isdefaultResponse;
			let datafield = $("#default_response" + num).attr("data-field");
			let splittedValues = datafield_one.split('#');

			let firstId = splittedValues[1];
			let secondId = splittedValues[2];
			let resultId = datafield;

			concatenateValues(firstId, secondId, resultId);
		}
	}
	//End Functon
	
	function splitConstraints(constraintsString) {
		let splitConstraints = constraintsString.split(',');
		let minPart = splitConstraints[0].split('=')[1];
		let maxPart = splitConstraints[1].split('=')[1];
		return [parseInt(minPart), parseInt(maxPart)];
	}
	//Default Response set value function start
	function populateMinMaxDropdown(num) {
		let isconstantMinMax = $(".constantMinMax" + num).attr("data-constraints");

		if (isconstantMinMax !== undefined) {
			let constfldName = $(".constantMinMax" + num).attr("data-id");
			console.log(constfldName);
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
	}

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
			console.log("fun-dd" + j);
			i = i.toLowerCase();
			j = j.toLowerCase();
			
			//start 26-09-2022
			var m=i;
			var n=j;
			var scheck = false; 
			n = n.slice(1, -1);
			var cArr = n.split(',');
			if(cArr.length>1){
				m = m.slice(0, -1);
				$.each(cArr, function(index, iv){
					console.log(iv);
					iv = '"' + iv + '"';
					var multiCheck = cRelevants.replace(m, iv);
					console.log('sss-sss-' + multiCheck);
					if(eval(multiCheck)){
						j=iv;
					}
				});
			}
			
			//end 26-09-2022


			i = i.slice(0, -1);
			cRelevants = cRelevants.replace(i, j);
		});
		console.log("fun-" + cRelevants);
		return cRelevants;
	}


	//jQuery time
	var current_fs, next_fs, previous_fs; //fieldsets
	var left, opacity, scale; //fieldset properties which we will animate
	var animating; //flag to prevent quick multi-click glitches
	var showQuestion;

	$(".next").click(function() {
		if (animating) return false;
		animating = true;

		current_fs = $(this).parent();
		next_fs = $(this).parent().next();

		// $("#error_message").html('');
		// $("#error_message").removeClass("ssanimation");
		// alert('ssss');
		//sss
		//next_fs = $(this).parent().find('fieldset[data-id="q_3"]');
		//console.log(next_fs);
		var btnsn = $(this).val();
		
		var fldName = $(this).attr("data-id");
		//console.log(fldName);
		var enterdata = $("#" + fldName).val();
		
		var qLenght = $(".next").length;
		var num = parseInt(btnsn) + 1;
		
		//DefaultResponse two string value display start
		let isdefaultResponse = $("#default_response" + num).val();
		
		let check = isdefaultResponse ? isdefaultResponse.includes("#") : false;

		if (isdefaultResponse !== '' && check && isdefaultResponse!='#name') {
			processDefaultResponse(num); //function create this page
		}
		if(isdefaultResponse=='#name' || isdefaultResponse=='#username'){
			let datafield = $("#default_response" + num).attr("data-field");
			let data_respone_name = $("#default_response" + num).attr("data-respone-name");
			
			$("#" + datafield).val(data_respone_name);
			//console.log($("#" + datafield).val(data_respone_name));
			
		} 
		//DefaultResponse two string value display end/////
		
		//Number calculation start
		let isconstantMinMax = $(".constantMinMax" + num).attr("data-id");
		
		
		//Number calculation End
		//////////////constantMinMax conditions///////////////////////
		let isconstantMinMax = $(".constantMinMax" + num).attr("data-constraints");
		let checkcomma = isconstantMinMax ? isconstantMinMax.includes(",") : false;
		if (isconstantMinMax !== undefined && isconstantMinMax !== '' && checkcomma) {
			populateMinMaxDropdown(num); // Function to create dropdown
		}
		
		//////////////constantMinMax conditions///////////////////////
		
		
		
		var isRelevant = $("#relevant" + num).val();
		//console.log('sno'+num);
		// console.log('Relivent-'+isRelevant);
		var showStatus = 0;
		if (isRelevant != "") {
			for (var sn = num; sn <= qLenght; sn++) {
				showQuestion = false;
				var getRelevant = "#relevant" + sn;
				var relevantVal = $(getRelevant).val();
				console.log(relevantVal);
				//console.log("ssnsm",getRelevant);
				var relevantValChk = $(getRelevant).val();
				relevantValChk = relevantValChk.replace(/=/g, '==');
				relevantValChk = relevantValChk.replace(">==", '>=');
				relevantValChk = relevantValChk.replace("<==", '<=');
				relevantValChk = relevantValChk.replace("!==", '!=');
				
				
				
				//console.log(relevantValChk);
				var relevants = splitMulti(relevantVal, ['&', '&&', '|', '||']);
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


				//console.log(releventJson);
				//console.log(relevantValChk); // $("#this_study").val()==1 & $("#q_8").val()!=5
				//console.log($("#this_study").val()==1 & $("#q_8").val()!=5);
				if (relevantValChk != "") {
					replacedVal_sss = releventChecking(releventJson, relevantValChk);
					var content = eval(replacedVal_sss);
					//console.log(content);
					if (content) {
						//console.log(replacedVal);
						// console.log('true ho rha h');
						showQuestion = true;
						false;
					} else {
						// console.log('false ho rha h');
						showQuestion = false;
						false;
					}
				} else {
					showQuestion = true;
				}

				// if(relevantValChk==null){ showQuestion=true; }

				//console.log("ssnsm",relevantVal);
				
				if(relevantVal==""){
					showQuestion = true;
				}
				
				if (showQuestion == false) {
					//console.log('ffffailed');
					fStatus = "failed";
				}

				//console.log(fStatus)
				// var sssn = sn+1;
				if (fStatus != "failed") {
					showStatus = sn;
					//console.log('sdfghjkl;lkjhxzsdfghj-'+showStatus);
					break;
				} else {
					//console.log('consndhsvgfsga');
					current_fs.hide();
					// next_fs.show();
					// break;
				}
			}
		} else {
			showQuestion = "next";
			//showStatus=num;
		}

		//console.log(showQuestion);

		// $.ajax({
		// url:"questions.js",
		// type:"post",
		// data:{survey_id:survey_id},
		// success:function(data){
		// console.log(data);
		// formBuilder.actions.setData(data);
		// }
		// });

		//sss
		//activate next step on progressbar using the index of next_fs
		//$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

		if (showQuestion === "next") {
			// console.log(showStatus);
			next_fs.show();
		}

		if (showStatus > 0) {
			$("#ss" + showStatus).show();
		}

		current_fs.hide();
		animating = false;
		
		//START DON`T KNOW CONDITIONS [[ CONSTRAINT ]]
		var cRelv = $("#relevant" + btnsn).attr("data-constraints");
		var inputType = $("#relevant" + btnsn).attr("data-inputType");
		if(cRelv!="" && inputType=="select_multiple"){
			//alert(cRelv);
			
			//[[ CONSTRAINT ]]
			//var ss = "Selected!=5 | count=1";
			var dntKnow = "success";
			var cRelv1= cRelv.split(' ');
			var cn= cRelv1[0];
			var cnd = cn.toLowerCase();
			//console.log(cRelv1[0]);
			
			var ss2= cnd.split('!=');
			var cnd2= 'selected!="'+ss2[1]+'"';

			var ssval = enterdata; //"1,2,3,4,5";
			//alert(ssval);
			var ssvalArr = ssval; //ssval.split(',');
			if(ssvalArr.length>1){
				//console.log(ssvalArr);
				$.each(ssvalArr, function(i, v){
					var vr = '"'+v.toLowerCase()+'"';
					var cndf = cnd2.replace('selected', vr);
					var fss = eval(cndf);
					if(fss){
						//console.log('sss');
						//dntKnow = "success";
					}else{
						//console.log('failed');
						//alert('failed');
						dntKnow = "failed";
						// current_fs.show();
						// next_fs.hide();
						// return false;
					}
					//console.log(cndf);
				});
			}
			else{
				console.log('success');
				dntKnow = "success";
			}
			ssvalArr="";
			
			
		}
		
		
		$('#error_messaged').html('');
		//alert(enterdata);
		if(dntKnow=="failed"){
			
			$("#error_messaged").html('If you are selecting Do not know then you cannot select other option and if you are selecting other option then cannot select Do not know.');
			$("#error_messaged").addClass("ssanimation");
			current_fs.show();
			next_fs.hide();
			//return false;
		}
		
		//END DON`T KNOW CONDITIONS
		
		$('#error_message').html('');
		//alert(enterdata);
		if(enterdata==""){
			
			$("#error_message").html('This fields are required.');
			$("#error_message").addClass("ssanimation");
			current_fs.show();
			next_fs.hide();
			$("#ss" + showStatus).hide();
			//return false;
		}

	});

	$('#error_message').on('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e){
		$('#error_message').delay(200).removeClass('ssanimation');
		// $('#error_message').delay(200).html('');
	});
	
	$('#error_messaged').on('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e){
		$('#error_messaged').delay(200).removeClass('ssanimation');
		// $('#error_message').delay(200).html('');
	});



	// PREVIOUSE BUTTON WORK
	$(".previous").click(function() {
		if (animating) return false;
		animating = true;

		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();

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
	$(document).ready(function(){
		
		$('#msform button:last').attr('id','formSubmit');
		$('#formSubmit').html('Submit');
		$("#formSubmit").on("click", function(){
			var fdata = $('#msform').serializeArray();
			var surveyId = "<?=$survey_id;?>";
			var languageId = "<?=$languageid;?>";
			var survey_name = "<?=$survey_name;?>";
			var GPS_latitude_start = $('#GPS_latitude_start').val();
			var GPS_longitude_start = $('#GPS_longitude_start').val();
		
			
			// CREATING SINGLE SCREEN MULTIPLE QUESTION GROUP DATA
			var allSingleGroup = $("#allSingleGroup").val();
			var allsGroup =  allSingleGroup.split(",");
			
			
			var allsGroupData = {};
			//console.log(allSingleGroup);
			if(allSingleGroup!=""){
				$.each(allsGroup, function(i,v){
					var sGroupObj = {};
					$('.'+v).each(function () {
						var fname = $(this).data("id");
						var fvalue = $(this).val();
						if($.isArray(fvalue)){
							fvalue = fvalue.join(",");
						}
						sGroupObj[fname] = fvalue; 
					});
					allsGroupData[v]=sGroupObj;
				});
			}
			

			//console.log(fdata);
			
			
			var allsGroupDatass = allsGroupData;
			//console.log(allsGroupData);
			//SYNC DATA ON SERVER
			$.ajax({
				url:"ssajax.php",
				type:"post",
				data:{formdata:fdata,GPS_latitude_start:GPS_latitude_start,GPS_longitude_start:GPS_longitude_start,surveyId:surveyId,languageId:languageId,survey_name:survey_name,allsGroupData:allsGroupDatass},
				success:function(res){
					//alert(res);
					console.log(res);
					
					var statusResponse = JSON.parse(res);
					if(statusResponse.success=="1"){
						//window.location.href='https://mquad.org/mis/welcome_skip.php';
						window.open('https://mquad.org/mis/welcome_skip.php', '_blank');
					}else{
						alert('Something Went Wrong');
						window.location.reload();
					} 
					 
				}
			});
			

		});
	})
</script>

<!-- https://www.programiz.com/javascript/online-compiler/ -->