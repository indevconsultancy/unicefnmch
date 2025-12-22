<?php include_once('includes/config.php'); ?>
<?php include_once('includes/header.php'); ?>
<?php //include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php
// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

function dirSize($dir)
{
	$dirSize = 0;
	if(!is_dir($dir)){return false;};
	$files = scandir($dir);if(!$files){return false;}
	$files = array_diff($files, array('.','..'));

	foreach ($files as $file) {
		if(is_dir("$dir/$file")){
			 $dirSize += dirSize("$dir/$file");
		}else{
			$dirSize += filesize("$dir/$file");
		}
	}
	return $dirSize;
}

?>


<style>
    .panel-heading {
    background: #394a59;
    color: white;
    font-weight: unset;
	}
	.btn:not(:disabled):not(.disabled) {
		cursor: pointer;
	}
	.add-button-bg a {
		position: fixed;
		bottom: 54px;
		right: 50px;
		background: rgb(57,74,89);
		z-index: 99999;
		border-radius: 50%;
		width: 60px;
		height: 60px;
		color: #fff;
		line-height: 46px;
		font-size: 22px;
		transition: all .3s ease-in-out;
	}
	.add-button-bg a:hover{
		background: rgb(4 39 60);
		color: #ffffff;
		-webkit-transform: rotate(90deg);
		transform: rotate(90deg);
		box-shadow: 1px 1px 1px 17px rgb(255 192 192 / 28%);
		
	}
	.dir{
		background-color: #e8f1db !important;
	}
</style>
    <!--main content start-->
	<section id="main-content">
		<section class="wrapper">
			<div class="add-button-bg">
				<a href="" class="btn btn-fixed-circle" title="Add Category"  data-toggle="modal" data-target="#exampleModal" data-backdrop="static" data-keyboard="false" data-whatever="@fat" style="border-radius: 40px;"><i class="fa fa-plus"></i></a>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<ol class="breadcrumb">
						<li><i class="fa fa-cog" aria-hidden="true"></i>Setting</li>
						<li><i class="fa fa-list"></i>Client Information</li>
					</ol>
				</div>
			</div>
			<!-- page start-->
			<div class="row">
				<div class="col-sm-12">   
					<section class="panel">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="">Sl.No</th>
									<th class="">Information</th>
									<th class="">Storage (MB)</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Projects</td>
									<td>
										<?php
											$clientId = $_SESSION['client_id'];
											$totalStoredData = 0;
											$getProjectSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS `Size_MB` from ( select  SUM(char_length(project_id)+ char_length(project_name)+ char_length(client_id)+ COALESCE(char_length(description),0)+ char_length(status)+ char_length(created_at)) as row_size  from projects where client_id='".$clientId."'  ) AS projectSize ");
											$projectSize = mysqli_fetch_object($getProjectSize);
											echo $totProjectSize = $projectSize->Size_MB;
											$totalStoredData+= $totProjectSize;
										?>
									</td>
								</tr>
								<tr>
									<td>2</td>
									<td>Form</td>
									<td>
										<?php
											$getSurveySize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS `Size_MB`
															from (
															  select 
																SUM(char_length(id)+
																char_length(survey_name)+
																COALESCE(char_length(questinnour_file),0)+
																COALESCE(char_length(questionnaire_pdf),0)+
																char_length(created_at)+
																char_length(del_action)+
																char_length(status)+
																char_length(otp)+
																char_length(user_id)+
																char_length(client_id)+
																COALESCE(char_length(secret_key),0)+
																char_length(unique_id)+
																COALESCE(char_length(form_version),0)+
																char_length(category_id)+
																char_length(project_id))
															  as row_size 
															  from survey WHERE client_id='".$clientId."'  ) AS surveySize ");
											$surveySize = mysqli_fetch_object($getSurveySize);
											echo $totSurveySize = $surveySize->Size_MB;
											$totalStoredData+= $totSurveySize;
										?>
									</td>
								</tr>
								
								<tr>
									<td>3</td>
									<td>User</td>
									<td>
										<?php
											$getUserSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS `Size_MB`
												from (
												  select 
													SUM(char_length(user_id)+
													char_length(name)+
													COALESCE(char_length(mobile),0)+
													char_length(username)+
													char_length(password)+
													char_length(orignal_password)+
													char_length(role_id)+
													COALESCE(char_length(user_code),0)+
													char_length(status)+
													COALESCE(char_length(email),0)+
													char_length(created_at)+
													COALESCE(char_length(firebase_token),0)+
													COALESCE(char_length(company_name),0)+
													char_length(client_id)+
													char_length(registered_as)+
													COALESCE(char_length(otp),0)+
													char_length(del_action)+
													char_length(device_id))
												  as row_size 
												  from users WHERE client_id='".$clientId."'  ) AS userSize; ");
											$userSize = mysqli_fetch_object($getUserSize);
											echo $totUserSize = $userSize->Size_MB;
											$totalStoredData+= $totUserSize;
										?>
									</td>
								</tr>
								
								<tr>
									<td>4</td>
									<td>Question Language</td>
									<td>
										<?php
											$getQLangSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS `Size_MB`
												from (
												  select 
													SUM(char_length(question_lang_id )+
													char_length(language_id)+
													char_length(question_id)+
													COALESCE(char_length(question_name),0)+
													COALESCE(char_length(dictionary_label),0)+
													char_length(encrpt)+
													COALESCE(char_length(question_description),0)+
													COALESCE(char_length(questions_type_id),0)+
													COALESCE(char_length(question_input_type_id),0)+
													char_length(status)+
													char_length(prefill)+
													char_length(max_input)+
													char_length(screen_no)+
													char_length(group_id)+
													char_length(group_relation_id)+
													char_length(sequence_no)+
													char_length(field_name)+
													char_length(ref_table)+
													char_length(validation_id)+
													char_length(required)+
													char_length(title)+
													char_length(category_name)+
													char_length(survey_id)+
													char_length(input_field_type)+
													char_length(relevant)+
													char_length(constraints)+
													char_length(constraint_msg)+
													char_length(parameters)+
													char_length(read_only)+
													char_length(calculation)+
													COALESCE(char_length(repeat_count),0)+
													char_length(choice_filter)+
													char_length(appearance)+
													char_length(choice_relation)+
													COALESCE(char_length(default_response),0)+
													char_length(repeated)+
													char_length(normal_group_id)+
													COALESCE(char_length(paradata),0)+
													char_length(unique_id)+
													COALESCE(char_length(preserve),0)+
													char_length(lookups)+
													char_length(media_file))
												  as row_size 
												  from questions_language WHERE survey_id IN(SELECT id FROM survey WHERE client_id='".$clientId."' ) ) AS qSize; ");
											$qLSize = mysqli_fetch_object($getQLangSize);
											echo $totQuestionSize = $qLSize->Size_MB;
											$totalStoredData+= $totQuestionSize;
										?>
									</td>
								</tr>
								
								<tr>
									<td>4</td>
									<td>Options Language</td>
									<td>
										<?php
											$getOLangSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS 'Size_MB'
											from (select 
												sum(char_length(options_language_id)+
												char_length(option_id)+
												char_length(question_id)+
												char_length(survey_id)+
												char_length(language_id)+
												COALESCE(char_length(option_name),0)+
												char_length(status)+
												COALESCE(char_length(option_sequence),0)+
												char_length(is_terminate)+
												COALESCE(char_length(option_type),0)+
												char_length(serial_no_for_app)+
												COALESCE(char_length(category_name),0)+
												COALESCE(char_length(choice_filter_parent),0)+
												COALESCE(char_length(likert_img),0)+
												COALESCE(char_length(media_file),0)+
												COALESCE(char_length(option_constraint),0)
												
												
												 )
											  as row_size 
											  from options_language  WHERE survey_id IN(SELECT id FROM survey WHERE client_id='".$clientId."' )  ) AS oldata; ");
											$oLSize = mysqli_fetch_object($getOLangSize);
											echo $totOptionSize = $oLSize->Size_MB;
											$totalStoredData+= $totOptionSize;
										?>
									</td>
								</tr>
								
								<tr>
									<td>5</td>
									<td>Survey Data Monitoring </td>
									<td>
										<?php
											$getSurveydmSize = mysqli_query($conn, "select sum((row_size) / 1024 / 1024) AS 'Size_MB'
												from (select 
													sum(
													char_length(survey_data_monitoring_id)+
													COALESCE(char_length(survey_data_id),0)+
													COALESCE(char_length(survey_form_id),0)+
													char_length(survey_id)+
													char_length(user_id)+
													char_length(survey_data_json)+
													char_length(full_json)+
													char_length(survey_status)+
													char_length(user_type)+
													COALESCE(char_length(termination_reason),0)+
													char_length(created_on)+
													char_length(survey_name)+
													char_length(survey_name_id)+
													char_length(client_id)+
													char_length(latitude)+
													char_length(longitude)+
													COALESCE(char_length(para_data),0)+
													COALESCE(char_length(hint_record),0)+
													COALESCE(char_length(error_record),0)+
													char_length(language_id)+
													char_length(survey_data_json_export)+
													char_length(survey_data_json_coded) 
														
														
													 )
												  as row_size 
												  from survey_data_monitoring  WHERE client_id='".$clientId."' ) AS sdatam; ");
											$sdmSize = mysqli_fetch_object($getSurveydmSize);
											echo $totSurveydmSize = $sdmSize->Size_MB;
											$totalStoredData+= $totSurveydmSize;
										?>
									</td>
								</tr>
								
								<!---DIRECTORY---->
								<tr>
									<td class="dir">6</td>
									<td class="dir">Media Lookups</td>
									<td class="dir">
										<?php
											$directory = 'medialookups/C'.$clientId;
											$bytes = dirSize($directory);
											echo $sizeinMB = $bytes / 1048576; 
											$totalStoredData+= $sizeinMB;
										?>
									</td>
								</tr>
								
								<tr>
									<td class="dir">7</td>
									<td class="dir">Tool Archive</td>
									<td class="dir">
										<?php
											$directoryTA = 'upload_data_file/tools_archive_datafile/C'.$clientId;
											$bytesTA = dirSize($directoryTA);
											echo $sizeinMBTA = $bytesTA / 1048576; 
											$totalStoredData+= $sizeinMBTA;
										?>
									</td>
								</tr>
								
								
								<tr>
									<td class="dir">8</td>
									<td class="dir">Data Reposotory (Codebook)</td>
									<td class="dir">
										<?php
											$directoryDRCB = 'upload_data_file/upload_codebook/C'.$clientId;
											$bytesDACB = dirSize($directoryDRCB);
											echo $sizeinDACB = $bytesDACB / 1048576; 
											$totalStoredData+= $sizeinDACB;
										?>
									</td>
								</tr>
								
								<tr>
									<td class="dir">9</td>
									<td class="dir">Data Reposotory (Data Formate File)</td>
									<td class="dir">
										<?php
											$directoryDRDff = 'upload_data_file/upload_dataformate_file/C'.$clientId;
											$bytesDRDff = dirSize($directoryDRDff);
											echo $sizeinDRDff = $bytesDRDff / 1048576; 
											$totalStoredData+= $sizeinDRDff;
										?>
									</td>
								</tr>
								
								
								
								<tr>
									<th></th>
									<th>Total</th>
									<th><?=$totalStoredData;?></th>
								</tr>
								
							</tbody>
						</table>
						
						<?php
							mysqli_query($conn,"update clients set datasize_mb='".$totalStoredData."' where id='".$clientId."' ");
						?>
						
					</section>
				</div>
			</div>
			<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
				<div class="col-md-10">
					<div class="d-flex align-items-center justify-content-between" id="pagination">

					</div>
				</div>

				<div class=" col-md-2 " style="margin-bottom: 0rem!important; padding-top: 10px">
					<!--<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>-->
				</div>
			</div>
		<!-- page end-->
		</section>
	</section>
    <!--main content end-->

<?php include_once('includes/footer.php'); ?>

