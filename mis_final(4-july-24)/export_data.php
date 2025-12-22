<?php include('includes/config.php'); ?>
<?php define("title", "Export Data | MQUAD"); ?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>
<?php include('includes/functions.php'); ?>
<?php
$survey_id = $_GET['survey_id'];
?>
<?php
$qry = '';
if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != '') {
		$qry .= " AND survey_id='" . $_REQUEST['survey_id'] . "'";
	}
}
?>
<?php
$client_qry1 = "";
if ($_SESSION['functional_role_id'] == "3") {
	$client_qry1 = " and survey.client_id='" . $_SESSION['client_id'] . "' ";
}
if ($_SESSION['functional_role_id'] != 3 && $_SESSION['functional_role_id'] != 1) {
	$user_id = $_SESSION['user_id'];
	$client_qry1 = " and survey_data_monitoring.survey_name_id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='" . $user_id . "') ";
}
?>
<?php
$functional_role_id = explode(',', $_SESSION['functional_role_id']);
 if (in_array(3, $functional_role_id) || in_array(12, $functional_role_id)) {
    echo "identify"; 
}  if(in_array(3, $functional_role_id) || in_array(7, $functional_role_id)){
    echo "Deidentify";
} 
?>
<style>
	#main-content .wrapper .row {
		margin-bottom: 0px;
	}

	.panel {
		margin-bottom: 8px;
	}

	/* .panel .panel-heading {
		margin-top: -10px;
	} */

	/*============ CSS FOR BOX SIZE (ANJNAI) =================*/
	.no-gutters [class*="col-"] {
		padding-right: 5px;
		padding-left: 5px;
	}

	.export-thumb {
		padding: 10px;
		margin-bottom: 5px !important;
		border: none;
		background: #f0f0f087;
	}

	.export-thumb .thumb-icon i {
		height: 45px;
		width: 45px;
		margin-right: 15px;
		padding: 0px;
		border-radius: 50%;
		font-size: 20px;
		line-height: 2.2;
	}

	.info-box.export-thumb .title {
		font-size: 14px;
	}

	.info-box1 {
		min-height: 70px;
	}


	.info-box1 .title {
		margin-top: 0px !important;

	}

	.thumb-icon {
    background: #61b7b8;
    height: 45px;
    width: 45px;
    margin-right: 15px;
    padding: 0px;
    border-radius: 50%;
}

.thumb-icon img{
	height: 20px;
    width: 20px;
    margin: 13px;
}

	/*============ CSS FOR BOX SIZE (ANJNAI) =================*/
</style>
<div id="pre-load" class="loading-indicator">
	<div id="loader" class="loader">
		<div class="loader-container">
			<div class='loader-icon'><img src="https://mquad.org/mis/img/mquad-logo.png" alt=""></div>
		</div>
	</div>
</div>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="fa fa-file-excel-o"></i>Export Data</li>
				</ol>
			</div>
		</div>
		<!--------filter start------->
		<div class="container-fluid">
			<form class="form-inline" method="get" role="form">
				<div class="row filter_css clearfix">
					<div class="form-group col-md-10 col-sm-8" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
						<select class="form-control select2" name="survey_id" id="survey_id">
							<option value="">Select Form</option>
							<?php
							$sqlservey = "SELECT DISTINCT(id),survey.survey_name,created_at FROM survey inner join survey_data_monitoring on survey_data_monitoring.survey_name_id=survey.id where survey.del_action='N' $client_qry1 order by survey.id DESC";
							$selectservey = mysqli_query($conn, $sqlservey);
							while ($surveydata = mysqli_fetch_array($selectservey)) { ?>
								<option value="<?php echo $surveydata['id']; ?>" <?php if ($surveydata['id'] == $_REQUEST['survey_id']) {
																						echo "selected";
																					} ?>><?php echo "" . $surveydata['survey_name'] . " (" . date("j-F-Y", strtotime($surveydata['created_at'])) . ")"; ?></option>
							<?php }  ?>
						</select>
					</div>
					<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
						<button type="submit" class="btn btn-secondary width-md waves-effect waves-light form-control" name="search">Load</button>
					</div>
				</div>
			</form>
		</div>
		<?php if ($survey_id != '') { ?>
			<div class="row ">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="info-box oneColor">
						<i class="icon_documents_alt"></i>
						<div class="count">
							<?php
							$surveycollect = mysqli_query($conn, "SELECT count(*) total FROM `survey_data_monitoring`where survey_name_id='" . $survey_id . "'");
							$survey_result = mysqli_fetch_array($surveycollect);
							echo $survey_result['total'];

							?>
							<div class="title"><span style="color:white;">Number of entries collected</span></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="info-box  twoColor">
						<i class="fa fa-check-circle" aria-hidden="true"></i>
						<div class="count">
							<?php
							$accsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as acc_total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_status in (1,6) and survey_name_id='" . $survey_id . "'");
							$survey_accept = mysqli_fetch_array($accsql);
							//echo $survey_accept['acc_total'];
							
							$accsqlsend = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as send_for_review FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_status in (4) and survey_name_id='" . $survey_id . "'");
							$survey_send = mysqli_fetch_array($accsqlsend);
							//echo $survey_send['send_for_review'];
							
							$accsqlter = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as tot_terminated FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_status in (3) and survey_name_id='" . $survey_id . "'");
							$survey_terminited = mysqli_fetch_array($accsqlter);
							//echo $survey_terminited['tot_terminated'];
							echo '<span class="text-left tooltips" data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="<span style=\'color:#449a97;font-weight: normal;max-width: 300px;\'>Terminated: '.$survey_terminited['tot_terminated'].'</span><br><span style=\'color:#449a97;font-weight: normal;max-width: 300px;\'>Sent for review: '.$survey_send['send_for_review'].'</span>">'.
								$survey_accept['acc_total'].
								 '</span>';
							?>
							<div class="title"><span style="color:white;">Number of entries verified</span></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="info-box  threeColor">
						<i class="fa fa-users" aria-hidden="true"></i>
						<div class="count">
							<?php
							$usersql = mysqli_query($conn, "SELECT count(DISTINCT(user_id)) as total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_name_id='" . $survey_id . "'");
							$data = mysqli_fetch_array($usersql);
							echo $data['total'];
							?>
							<div class="title"><span style="color:white;">Total Users</span></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="info-box fourColor">
						<i class="icon_documents_alt"></i>
						<div class="count">
							<?php
							$today = date('y-m-d');
							$todaysurvey = mysqli_query($conn, "SELECT count(survey_data_monitoring_id) as total_date  FROM `survey_data_monitoring` where survey_name_id='" . $survey_id . "' and date(created_on)='" . $today . "' ");
							$data = mysqli_fetch_array($todaysurvey);
							echo $data['total_date'];
							?>
							<div class="title"><span style="color:white;">Number of entries collected today</span></i></div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if ($survey_id != '') { ?>
			<div class="row">
				<div class="col-md-12">
					<section class="panel">
						<div class="card">
							<?php
							$surveyqry = mysqli_query($conn, "SELECT survey_name,questinnour_file,clients.name as client_name FROM `survey` left join clients on survey.client_id=clients.id where survey.id='" . $survey_id . "' ");
							$surveydata = mysqli_fetch_array($surveyqry);
							$survey_name = $surveydata['survey_name'];
							$client_name = $surveydata['client_name'];

							?>
							</header>
							<header class="panel-heading">
								Form Name: <?php echo $survey_name; ?> <?php if ($_SESSION['role_id'] != '3') { ?>|| Client Name: <?php echo $client_name; ?> <?php } ?>
							</header>
						</div>
					</section>
				</div>
				<!---------Identify Data export------------->
				<?php
				if (in_array(3, $functional_role_id) || in_array(12, $functional_role_id)) { ?>
					<div class="col-md-12">
						<div class="panel panel-default custom-panel ">
							<div class="panel-heading">
								<h4 class="panel-title" style="line-height: 34px; !important">
									<!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwoS" style="font-size: 20px;"> Export data to multiple formats-->
									<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwoS" style="font-size: 20px;"> Export Data (Identify)
										<i class="fa fa-angle-down rotate-icon pull-right" style="margin-top: 4px;font-size: 25px;border: none;"></i>
									</a>
								</h4>
							</div>

							<div id="collapseTwoS" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="row no-gutters">
										<div class="col-md-3">
											<a href="javascript:void(0)" class="expwithoutlevelidentify">
												<div class="info-box info-box1  main-bg export-thumb">
													<div class="thumb-icon">
													<i class="fa fa-file-excel-o"></i>
													</div>
													<div class="count">
														<input type="hidden" name="survey_id" class="survey_id" value="<?php echo $survey_id; ?>" />
														<input type="hidden" id="identify" value="identify">
														<div class="title"><a href="javascript:void(0)" class="expwithoutlevelidentify">Excel without Labels</a></div>
													</div>
												</div>
											</a>
										</div>
										<div class="col-md-3">
											<a href="" data-toggle="modal" data-target="#ModalExportIdentify" onclick="exportlabel_excel(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">
												<div class="info-box info-box1  main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-file-excel-o"></i>
													</div>
													<div class="count">
														<div class="title"><a href="" data-toggle="modal" data-target="#ModalExportIdentify" onclick="exportlabel_excel(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">Excel with Data Labels</a></div>
													</div>
												</div>
											</a>
										</div>
										<div class="col-md-2">
											<a href="javascript:void(0)" class="expStataIdentify">
												<div class="info-box info-box1  main-bg export-thumb">
													<div class="thumb-icon">
													<img src="../mis/img/icons/stata-icon.png" alt="">
													</div>
													<div class="count">
														<input type="hidden" name="survey_id" class="survey_id" value="<?php echo $survey_id; ?>" />
														<input type="hidden" id="identify" value="identify">
														<div class="title"><a href="javascript:void(0)" class="expStataIdentify">Stata</a></div>
													</div>
												</div>
											</a>
										</div>
										<div class="col-md-2">
											<a href="javascript:void(0)" class="expSPSSIdentify">
												<div class="info-box info-box1  main-bg export-thumb">
													<!--<div class="thumb-icon">
														<i class="fa fa-file-o"></i>
													</div>-->
													<div class="thumb-icon thumb-icon-s">
														<img src="<?=base_url()?>/assets/spss.png">
													</div>
													<div class="count">
														<input type="hidden" name="survey_id" class="survey_id" value="<?php echo $survey_id; ?>" />
														<input type="hidden" id="identify" value="identify">
														<div class="title"><a href="javascript:void(0)" class="expSPSSIdentify">SPSS</a></div>
													</div>
												</div>
											</a>
										</div>
										<div class="col-md-2">
											<a href="jsonexport.php?survey_id=<?= $survey_id; ?>">
												<div class="info-box info-box1  main-bg export-thumb">
													<div class="thumb-icon">
													<img src="../mis/img/icons/json-icon.png" alt="">
													</div>
													<div class="count">
														<div class="title"><a href="jsonexport.php?survey_id=<?= $survey_id; ?>">JSON</a></div>
													</div>
												</div>
											</a>
										</div>
										
									</div>

								</div>
							</div>
						</div>
					</div>
				<?php }
				if (in_array(3, $functional_role_id) || in_array(7, $functional_role_id)) {
				?>
					<!---------Deidentify Data export------------->
					<div class="col-md-12">
						<div class="panel panel-default custom-panel ">
							<div class="panel-heading">
								<h4 class="panel-title" style="line-height: 34px; !important">
									<a data-toggle="collapse" data-parent="#accordion" aria-expanded="false" href="#collapseDeidentify" style="font-size: 20px;"> Export Data (Deidentify)
										<i class="fa fa-angle-down rotate-icon pull-right" style="margin-top: 4px;font-size: 25px;border: none;"></i>
									</a> 
								</h4>
							</div>
							<div id="collapseDeidentify" class="panel-collapse collapse">

								<div class="panel-body">
									<?php  $encrptValue = getcount($conn, 'questions_language', 'encrpt', 'survey_id', $survey_id,'encrpt','yes'); ?>
									<?php if ($encrptValue==1 && $encrptValue>0) { ?>
										<div class="row no-gutters">
											<div class="col-md-3">
												<a href="javascript:void(0)" class="expwithoutleveldeidentify">
													<div class="info-box info-box1  main-bg export-thumb">
														<div class="thumb-icon"> 
															<i class="fa fa-file-excel-o"></i>
														</div>
														<div class="count">
															<input type="hidden" name="survey_id" class="survey_id" value="<?php echo $survey_id; ?>" />
															<input type="hidden" id="deidentify" value="deidentify">
															<div class="title"><a href="javascript:void(0)" class="expwithoutleveldeidentify">Excel without Labels</a></div>
														</div>
													</div>
												</a>
											</div>
											<div class="col-md-3">
												<a href="" data-toggle="modal" data-target="#ModalExportDeidentify" onclick="exportlabel_excel(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">
													<div class="info-box info-box1  main-bg export-thumb">
														<div class="thumb-icon">
															<i class="fa fa-file-excel-o"></i>
														</div>
														<div class="count">
															<div class="title"><a href="" data-toggle="modal" data-target="#ModalExportDeidentify" onclick="exportlabel_excel(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">Excel with Data Labels</a></div>
														</div>
													</div>
												</a>
											</div>
											<div class="col-md-2">
												<a href="javascript:void(0)" class="expStataDeidentify">
													<div class="info-box info-box1  main-bg export-thumb">
														<div class="thumb-icon">
														<img src="../mis/img/icons/stata-icon.png" alt="">
														</div>
														<div class="count">
															<input type="hidden" name="survey_id" class="survey_id" value="<?php echo $survey_id; ?>" />
															<input type="hidden" id="deidentify" value="deidentify">
															<div class="title"><a href="javascript:void(0)" class="expStataDeidentify">Stata</a></div>
														</div>
													</div>
												</a>
											</div>
											<div class="col-md-2">
												<a href="javascript:void(0)" class="expSPSSDeidentify">
													<div class="info-box info-box1  main-bg export-thumb">
														<!--<div class="thumb-icon">
															<i class="fa fa-file-o"></i>
														</div>-->
														<div class="thumb-icon thumb-icon-s">
														<img src="<?=base_url()?>/assets/spss.png">
													</div>
														<div class="count">
															<input type="hidden" name="survey_id" class="survey_id" value="<?php echo $survey_id; ?>" />
															<input type="hidden" id="deidentify" value="deidentify">
															<div class="title"><a href="javascript:void(0)" class="expSPSSDeidentify">SPSS</a></div>
														</div>
													</div>
												</a>
											</div>

											<div class="col-md-2">
												<a href="jsonexport.php?survey_id=<?= $survey_id; ?>">
													<div class="info-box info-box1  main-bg export-thumb">
														<div class="thumb-icon">
														<img src="../mis/img/icons/json-icon.png" alt="">
														</div>
														<div class="count">
															<div class="title"><a href="jsonexport.php?survey_id=<?= $survey_id; ?>">JSON</a></div>
														</div>
													</div>
												</a>
											</div>
											
										</div>
									<?php } else { ?>
										<div class="col-md-12">Deidentify variable not created in the form!</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<!---------Para Data export------------->
				<div class="col-md-12">
					<div class="panel panel-default custom-panel ">
						<div class="panel-heading">
							<h4 class="panel-title" style="line-height: 34px; !important">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" style="font-size: 20px;"> Export Paradata
									<i class="fa fa-angle-down rotate-icon pull-right" style="margin-top: 4px;font-size: 25px;border: none;"></i>
								</a>
							</h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse">
							<div class="panel-body">
								<div class="row no-gutters">
									<div class="col-md-2">
										<a href="javascript:void(0)" class="expExcelParadata" data-id="export_paradata.php?survey_id=<?= $survey_id; ?>">
											<div class="info-box info-box1  main-bg export-thumb">
												<div class="thumb-icon">
													<i class="fa fa-file-excel-o"></i>
												</div>
												<div class="count">
													<div class="title"><a href="javascript:void(0)" class="expExcelParadata" data-id="export_paradata.php?survey_id=<?= $survey_id; ?>">Paradata</a></div>
												</div>
											</div>
										</a>
									</div>
									<div class="col-md-2">
										<a href="" data-toggle="modal" data-target="#exportMedia" data-backdrop="static" data-keyboard="false" data-whatever="@fat">
											<div class="info-box info-box1  main-bg export-thumb">
												<div class="thumb-icon">
													<i class="fa fa-file-archive-o"></i>
												</div>
												<div class="count">
													<div class="title"><a href="" data-toggle="modal" data-target="#exportMedia" data-backdrop="static" data-keyboard="false" data-whatever="@fat">Media File</a></div>
												</div>
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } else { ?>

		<?php } ?>

		<!--------------------Excel with lebel export---------------------------------->
		<div class="modal fade " id="ModalExportDeidentify" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title" id="exampleModalLabel"><span>Export Data</span></h1>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<!--<form action="export_multisheet_multilingual.php" method="POST" enctype="multipart/form-data">-->
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="modal-body" style="height: 107px;">
							<input type="hidden" name="survey_id" id="" class="form-control survey_id" value="<?php echo $survey_id; ?>" />
							<input type="hidden" id="deidentify" value="deidentify">
							<div class="col-lg-12">
								<div class="form-group">

									<span>Note: Select the language and download your data in excel with label format.</span></br></br>
									<select class="form-control language_ids" name="language_id" id="language_id2" required>
										<option value="">Select Language</option>
									</select>
									
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<button class="btn btn-secondary pull-right mb-2 " id="expExcelwithlabel" name="download_data" style="margin-top:6px;">Download</button>
								</div>
							</div>
						</div>
						<div class="modal-footer" style="margin-top: 56px;">
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="modal fade " id="ModalExportIdentify" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title" id="exampleModalLabel"><span>Export Data</span></h1>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<!--<form action="export_multisheet_multilingual.php" method="POST" enctype="multipart/form-data">-->
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="modal-body" style="height: 107px;">
							<input type="hidden" name="survey_id" id="" class="form-control survey_id" value="<?php echo $survey_id; ?>" />
							<input type="hidden" id="identify" value="identify">
							<div class="col-lg-12">
								<div class="form-group">

									<span>Note: Select the language and download your data in excel with label format.</span></br></br>
									<select class="form-control language_ids" name="language_id" id="language_id1" required>
										<option value="">Select Language</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<button class="btn btn-secondary pull-right mb-2 " id="expExcelwithlabels" name="download_data" style="margin-top:6px;">Download</button>
								</div>
							</div>
						</div>
						<div class="modal-footer" style="margin-top: 56px;">
						</div>
					</form>
				</div>
			</div>
		</div>
	
		<!--------------------End Excel with lebel export---------------------------------->

		<!--------------------Media FIle export---------------------------------->
		<div class="modal fade" id="exportMedia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content" style="width: 75%;">
					<div class="modal-header">
						<h2 class="modal-title" id="exampleModalLabel"><span>Media Data</span></h2>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form id="exportForm" method="POST" enctype="multipart/form-data">
						<div class="modal-body" style="height: 120px;">
							<input type="hidden" name="survey_id" id="survey_id" class="form-control" value="<?php echo $survey_id; ?>" />
							<!--<span>Note: Export the media file/Paradata file</span></br></br>-->

							<div class="row">
								<a href="export_media_question.php?survey_id=<?= $survey_id ?>" class="btn btn-secondary pull-center mb-2 export-btn" data-type="media_file" style="margin-left: 3%;padding: 2%;width: 37%;">Survey Media</a>
							</div>
							<div class="row">
								<a href="export_media_paradata.php?survey_id=<?= $survey_id ?>" class="btn btn-secondary pull-center mb-2 export-btn" data-type="paradata_media" style="margin-left: 3%;padding: 2%;margin-top: 5px;width: 37%;">Paradata Media</a>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!--------------------Media FIle export---------------------------------->

	</section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>
<script>
	$(document).ready(function() {
		$('.export-btn').on('click', function() {
			var fileType = $(this).data('type');
			var surveyid = $("#survey_id").val();
			var downloadUrl = '';

			if (fileType === 'media_file') {
				downloadUrl = "export_media_question.php?survey_id=" + surveyid;
			} else if (fileType === 'paradata_media') {
				downloadUrl = "export_media_paradata.php?survey_id=" + surveyid;
			}
			$('.loading-indicator').show();
			// Trigger the download
			var link = document.createElement('a');
			link.href = downloadUrl;
			//link.target = "_blank"; // Open in a new tab
			link.click();
			setTimeout(function() {
				$('#exportMedia').modal('hide');
				$('.loading-indicator').hide();
			}, 1000); // Adjust the delay in milliseconds
		});
	});
</script>
<script>
	function exportlabel_excel(val) {
		//alert(val);
		$(".survey_id").attr("value", val);
		$.ajax({
			type: 'post',
			url: 'survey_list_ajax.php',
			data: 'survey_ID=' + val,
			success: function(responsedata) {
				console.log(responsedata);
				$('.language_ids').html(responsedata);
			}

		});
	}

	$('.expExcelParadata').on('click', function() {
		var expExcelParadata = $(this).data("id");
		let url = "<?= base_url(); ?>" + expExcelParadata;
		//window.location.href=url;
		$.ajax({
			type: 'post',
			url: expExcelParadata,
			dataType: 'json',
			data: {},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(res) {
				console.log(res);
				// window.location.href = url;
				var link = document.createElement('a');
				link.href = res.path;
				link.download = res.fname;
				link.click();

				$('.loading-indicator').removeClass('active');
			}
		})
	});
	$(document).on("click", ".expStataIdentify", function() {
		var identify = $("#identify").val();
		var surveyid = $(".survey_id").val();
		// var export_type=$(".export_type").val();
		//alert(identify);
		$.ajax({
			type: 'post',
			url: 'exportstatawithexcel.php',
			data: {
				'survey_id': surveyid,
				'export_types_data': identify
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(res) {
				var res = JSON.parse(res);
				//console.log(res);
				window.location.href = window.location.origin + '/mis/' + res.file_name;
				$('.loading-indicator').removeClass('active');
			}

		});
	})
	$(document).on("click", ".expStataDeidentify", function() {
		var deidentify = $("#deidentify").val();
		var surveyid = $(".survey_id").val();
		//alert(deidentify);
		$.ajax({
			type: 'post',
			url: 'exportstatawithexcel.php',
			data: {
				'survey_id': surveyid,
				'export_types_data': deidentify
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(res) {
				var res = JSON.parse(res);
				//console.log(res);
				window.location.href = window.location.origin + '/mis/' + res.file_name;
				$('.loading-indicator').removeClass('active');
			}

		});
	})
	$(document).on("click", ".expSPSSIdentify", function() {
		var identify = $("#identify").val();
		var surveyid = $(".survey_id").val();
		//alert(identify);
		$.ajax({
			type: 'post',
			//url: 'exportspss.php',
			url: 'exportspss_roster.php',
			data: {
				'survey_id': surveyid,
				'spss_export_types': identify
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(res) {
				console.log(res);
				var res = JSON.parse(res);
				window.location.href = window.location.origin + '/mis/' + res.file_name;
				$('.loading-indicator').removeClass('active');
			}

		});
	})
	$(document).on("click", ".expSPSSDeidentify", function() {
		var deidentify = $("#deidentify").val();
		var surveyid = $(".survey_id").val();
		//alert(deidentify);
		$.ajax({
			type: 'post',
			//url: 'exportspss.php',
			url: 'exportspss_roster.php',
			data: {
				'survey_id': surveyid,
				'spss_export_types': deidentify
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(res) {
				var res = JSON.parse(res);
				//console.log(res);
				window.location.href = window.location.origin + '/mis/' + res.file_name;
				$('.loading-indicator').removeClass('active');
			}

		});
	})

	$('#expExcelwithlabel').on('click', function(e) {
		e.preventDefault();
		var language_id = $("#language_id2").val();
		var survey_id = $(".survey_id").val();
		var deidentify = $("#deidentify").val();

		$.ajax({
			type: 'post',
			url: 'export_multisheet_multilingual.php',
			dataType: 'json',
			data: {
				'language_id': language_id,
				'survey_id': survey_id,
				'identification_type': deidentify
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(res) {
				console.log(res);
				if (res.status == 200) {
					var link = document.createElement('a');
					link.href = res.path;
					link.download = res.fname;

					link.click();
					$('.loading-indicator').removeClass('active');
					location.reload();
					$("#ModalExportDeidentify").hide();

				} else {
					console.log(res.message);
				}
			},

		})
	});
	$(document).on("click", "#expExcelwithlabels", function(e) {
	//$('#expExcelwithlabels').on('click', function(e) {
		e.preventDefault();

		var language_id = $("#language_id1").val();
		var survey_id = $(".survey_id").val();
		var identify = $("#identify").val();
		$.ajax({
			type: 'post',
			//url: 'export_multisheet_multilingual_old.php',
			url: 'export_multisheet_multilingual.php',
			dataType: 'json',
			data: {
				'language_id': language_id,
				'survey_id': survey_id,
				'identification_type': identify
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(res) {
				console.log(res);
				if (res.status == 200) {
					var link = document.createElement('a');
					link.href = res.path;
					link.download = res.fname;

					link.click();
					$('.loading-indicator').removeClass('active');
					location.reload();
					$("#ModalExportIdentify").hide();

				} else {
					console.log(res.message);
				}
			},

		})
	});

	$('.expwithoutlevelidentify').on('click', function(e) {
		e.preventDefault();
		var survey_id = $(".survey_id").val();
		var identify = $("#identify").val();
		//alert(identify);
		$.ajax({
			type: 'post',
			url: 'export_multisheet_coded.php',
			dataType: 'json',
			data: {
				'survey_id': survey_id,
				'export_type': identify
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(res) {
				console.log(res);
				if (res.status == 200) {
					var link = document.createElement('a');
					link.href = res.path;
					link.download = res.fname;

					link.click();
					$('.loading-indicator').removeClass('active');
					location.reload();
				} else {
					console.log(res.message);
				}
			},

		})
	});

	$('.expwithoutleveldeidentify').on('click', function(e) {
		e.preventDefault();
		var survey_id = $(".survey_id").val();
		var deidentify = $("#deidentify").val();
		alert(deidentify);
		$.ajax({
			type: 'post',
			url: 'export_multisheet_coded.php',
			dataType: 'json',
			data: {
				'survey_id': survey_id,
				'export_type': deidentify
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(res) {
				console.log(res);
				if (res.status == 200) {
					var link = document.createElement('a');
					link.href = res.path;
					link.download = res.fname;

					link.click();
					$('.loading-indicator').removeClass('active');
					location.reload();
				} else {
					console.log(res.message);
				}
			},

		})
	});
</script>