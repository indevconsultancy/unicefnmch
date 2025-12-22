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


/* if (in_array(3, $functional_role_id) || in_array(12, $functional_role_id)) {
    echo "identify"; 
}  if(in_array(3, $functional_role_id) || in_array(7, $functional_role_id)){
    echo "Deidentify";
} */
?>
<style>
	#main-content .wrapper .row {
		margin-bottom: 0px;
	}

	.panel {
		margin-bottom: 20px;
	}

	/* .panel .panel-heading {
		margin-top: -10px;
	} */
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
			<div class="row">
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
					<div class="info-box twoColor">
						<i class="fa fa-check-circle" aria-hidden="true"></i>
						<div class="count">
							<?php
							$surveyverify = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as acc_total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_status='1' and survey_name_id='" . $survey_id . "'");
							$survey_verify = mysqli_fetch_array($surveyverify);
							echo $survey_verify['acc_total'];

							?>
							<div class="title"><span style="color:white;">Number of entries verified</span></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="info-box threeColor">
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
				<div class="col-md-12">
					<div class="panel panel-default custom-panel ">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwoS" style="font-size: 20px;"> Export data to multiple formats
									<i class="fa fa-angle-down rotate-icon pull-right" style="margin-top: 4px;font-size: 30px;border: none;"></i>
								</a>
							</h4>
						</div>

						<div id="collapseTwoS" class="panel-collapse collapse in">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-3">
										<a href="" data-toggle="modal" data-target="#ModalExportwithout" onclick="exportwithoutlabel_excel(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">
											<div class="info-box main-bg export-thumb">
												<div class="thumb-icon">
													<i class="fa fa-file-excel-o"></i>
												</div>
												<div class="count">
													<div class="title"><a href="" data-toggle="modal" data-target="#ModalExportwithout" onclick="exportwithoutlabel_excel(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">Excel without Labels</a></div>
												</div>
											</div>
										</a>
									</div>
									<div class="col-md-3">
										<a href="" data-toggle="modal" data-target="#ModalExportstata" onclick="exportstata_zip(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">
											<div class="info-box main-bg export-thumb">
												<div class="thumb-icon">
													<i class="fa fa-file-excel-o"></i>
												</div>
												<div class="count">
													<div class="title"><a href="" data-toggle="modal" data-target="#ModalExportstata" onclick="exportstata_zip(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">Stata</a></div>
												</div>
											</div>
										</a>
									</div>
									<!--<div class="col-md-3">
										<a href="javascript:void(0)" class="expSPSS" data-id="exportspss.php?survey_id=<?= $survey_id; ?>">
											<div class="info-box main-bg export-thumb">
												<div class="thumb-icon">
													<i class="fa fa-file-o"></i>
												</div>
												<div class="count">
													<div class="title"><a href="javascript:void(0)" class="expSPSS" data-id="exportspss.php?survey_id=<?= $survey_id; ?>">SPSS</a></div>
												</div>
											</div>
										</a>
									</div>-->
									<div class="col-md-3">
										<a href="" data-toggle="modal" data-target="#ModalExportspass" onclick="exportspass_zip(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">
											<div class="info-box main-bg export-thumb">
												<div class="thumb-icon">
													<i class="fa fa-file-o"></i>
												</div>
												<div class="count">
													<div class="title"><a href="" data-toggle="modal" data-target="#ModalExportspass" onclick="exportspass_zip(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">SPSS</a></div>
												</div>
											</div>
										</a>
									</div> 
									<div class="col-md-3">
										<a href="jsonexport.php?survey_id=<?= $survey_id; ?>">
											<div class="info-box main-bg export-thumb">
												<div class="thumb-icon">
													<i class="fa fa-list"></i>
												</div>
												<div class="count">
													<div class="title"><a href="jsonexport.php?survey_id=<?= $survey_id; ?>">JSON</a></div>
												</div>
											</div>
										</a>
									</div>
									<div class="col-md-3">
										<a href="" data-toggle="modal" data-target="#ModalExport" onclick="exportlabel_excel(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">
											<div class="info-box main-bg export-thumb">
												<div class="thumb-icon">
													<i class="fa fa-file-excel-o"></i>
												</div>
												<div class="count">
													<div class="title"><a href="" data-toggle="modal" data-target="#ModalExport" onclick="exportlabel_excel(<?= $survey_id; ?>)" ; data-backdrop="static" data-keyboard="false" data-whatever="@fat">Excel with Data Labels</a></div>
												</div>
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="panel panel-default custom-panel ">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" style="font-size: 20px;"> Export Paradata
									<i class="fa fa-angle-down rotate-icon pull-right" style="margin-top: 4px;font-size: 30px;border: none;"></i>
								</a>
							</h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse in">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-3">
										<a href="javascript:void(0)" class="expExcelParadata" data-id="export_paradata.php?survey_id=<?= $survey_id; ?>">
											<div class="info-box main-bg export-thumb">
												<div class="thumb-icon">
													<i class="fa fa-file-excel-o"></i>
												</div>
												<div class="count">
													<div class="title"><a href="javascript:void(0)" class="expExcelParadata" data-id="export_paradata.php?survey_id=<?= $survey_id; ?>">Paradata</a></div>
												</div>
											</div>
										</a>
									</div>
									<div class="col-md-3">
										<a href="" data-toggle="modal" data-target="#exportMedia" data-backdrop="static" data-keyboard="false" data-whatever="@fat">
											<div class="info-box main-bg export-thumb">
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
		<!--------------------Excel without lebel export---------------------------------->
		<div class="modal fade" id="ModalExportwithout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title" id="exampleModalLabel"><span>Export Data</span></h1>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form action="" method="POST" enctype="multipart/form-data">
						<div class="modal-body" style="height: 160px;">
							<input type="hidden" name="surveyid" id="surveyid" class="form-control" value="<?php echo $survey_id; ?>" />


							<div class="col-lg-12">
								<div class="form-group" style="margin-top:12px">
									<span>Export Type</span><br><br>
									<select class="form-control" name="export_type" id="export_type" required>
										<option value="">Select Type</option>
										<?php if (in_array(3, $functional_role_id) || in_array(12, $functional_role_id)) { ?>
										<option value="identify">Identify</option>
										<?php }
										if(in_array(3, $functional_role_id) || in_array(7, $functional_role_id)){ ?>
											<option value="deidentify">Deidentify</option>
										<?php } ?>
										
									</select>
								</div>
							</div>


							<div class="col-lg-12">

								<div class="form-group">
									<button type="button" class="btn btn-secondary pull-right mb-2 " id="Excelwithoutlabel" name="download_data" style="margin-top:6px;">Download</button>
									
								</div>
							</div>
						</div>
						<div class="modal-footer">
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--------------------End Excel without lebel export---------------------------------->

		<!-------------------- Export stata---------------------------------->
		<div class="modal fade" id="ModalExportstata" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title" id="exampleModalLabel"><span>Export Data</span></h1>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form action="" method="POST" enctype="multipart/form-data">
						<div class="modal-body" style="height: 160px;">
							<input type="hidden" name="survey_ids" id="survey_ids" class="form-control" value="<?php echo $survey_id; ?>" />


							<div class="col-lg-12">
								<div class="form-group" style="margin-top:12px">
									<span>Export Type</span><br><br>
									<select class="form-control" name="export_types_data" id="export_types_data" required>
									<option value="">Select Type</option>
										<?php if (in_array(3, $functional_role_id) || in_array(12, $functional_role_id)) { ?>
										<option value="identify">Identify</option>
										<?php }
										if(in_array(3, $functional_role_id) || in_array(7, $functional_role_id)){ ?>
											<option value="deidentify">Deidentify</option>
										<?php } ?>
										
									</select>
								</div>
							</div>


							<div class="col-lg-12">

								<div class="form-group">
									<button type="button" class="btn btn-secondary pull-right mb-2 " id="Excelwithoutstata" name="download_data" style="margin-top:6px;">Download</button>
									<!-- <button  class="btn btn-secondary pull-right mb-2 expExcelwithlabel" data-id="export_multisheet_multilingual.php?survey_id=<?= $survey_id; ?>" name="download_data" style="margin-top:-7px;">Download</button> -->
								</div>
							</div>
						</div>
						<div class="modal-footer">
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--------------------End Export stata---------------------------------->

		<!-------------------- Export SPSS---------------------------------->
		<div class="modal fade" id="ModalExportspass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title" id="exampleModalLabel"><span>Export Data</span></h1>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="" method="POST" enctype="multipart/form-data">
						<div class="modal-body" style="height: 160px;">
							<input type="hidden" name="survey_idss" id="survey_idss" class="form-control" value="<?php echo $survey_id; ?>" />
							<div class="col-lg-12">
								<div class="form-group" style="margin-top:12px">
									<span>Export Type</span><br><br>
									<select class="form-control" name="spss_export_types" id="spss_export_types" required>
										<option value="">Select Type</option>
										<?php if (in_array(3, $functional_role_id) || in_array(12, $functional_role_id)) { ?>
										<option value="identify">Identify</option>
										<?php }
										if(in_array(3, $functional_role_id) || in_array(7, $functional_role_id)){ ?>
											<option value="deidentify">Deidentify</option>
										<?php } ?>
										
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<button type="button" class="btn btn-secondary pull-right mb-2 " id="Excelwithoutspss" name="download_data" style="margin-top:6px;">Download</button>
								</div>
							</div>
						</div>
						<div class="modal-footer">
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--------------------End Export Spss---------------------------------->

		<!--------------------Excel with lebel export---------------------------------->
		<div class="modal fade" id="ModalExport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
						<div class="modal-body" style="height: 160px;">
							<input type="hidden" name="survey_id" id="survey_id" class="form-control" value="<?php echo $survey_id; ?>" />
							<div class="col-lg-12">
								<div class="form-group">

									<span>Note: Select the language and download your data in excel with label format.</span></br></br>
									<select class="form-control" name="language_id" id="language_id" required>
										<option value="">Select Language</option>
									</select>
								</div>
							</div>

							<div class="col-lg-12">
								<div class="form-group" style="margin-top:12px">
									<span>Export Type:</span><br><br>
									<select class="form-control" name="identification_type" id="identification_type" required>
										<option value="">Select Type</option>
										<?php if (in_array(3, $functional_role_id) || in_array(12, $functional_role_id)) { ?>
										<option value="identify">Identify</option>
										<?php }
										if(in_array(3, $functional_role_id) || in_array(7, $functional_role_id)){ ?>
											<option value="deidentify">Deidentify</option>
										<?php } ?>
										
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
		$("#survey_id").attr("value", val);
		$.ajax({
			type: 'post',
			url: 'survey_list_ajax.php',
			data: 'survey_ID=' + val,
			success: function(responsedata) {
				$('#language_id').html(responsedata);
			}

		});
	}

	function exportwithoutlabel_excel(val) {
		$("#surveyid").attr("value", val);
	}

	$('#Excelwithoutlabel').on('click', function() {
		var surveyid = $("#surveyid").val();
		var export_type = $("#export_type").val();

		$.ajax({
			type: 'post',
			url: 'export_multisheet_coded.php',
			data: {
				'survey_id': surveyid,
				'export_type': export_type
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(ress) {
				var res = JSON.parse(ress);
				//console.log(res);
				if (res.status == 200) {
					//window.location.href = 'https://mquad.org/mis/' + res.fname;
					//$('.loading-indicator').removeClass('active');
					var link = document.createElement('a');
					link.href = res.path;
					link.download = res.fname;
					link.click();
					$('.loading-indicator').removeClass('active');
					location.reload();
					$("#ModalExportwithout").hide();

				} else {
					console.log(res.message);
				}
			}
		});
	});
	function exportstata_zip(val) {
		$("#survey_ids").attr("value", val);
	}
	$('#Excelwithoutstata').on('click', function() {
		var surveyid = $("#survey_ids").val();
		var export_types_data = $("#export_types_data").val();
		
		$.ajax({
			type: 'post',
			url: 'exportstatawithexcel.php',
			data: {
				'survey_id': surveyid,
				'export_types_data': export_types_data
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(ress) {
				var res = JSON.parse(ress);
				 console.log(ress);
				if (res.status == 200) {
					window.location.href = 'https://mquad.org/mis/' + res.file_name;
					$('.loading-indicator').removeClass('active');
					$("#ModalExportstata").hide();
					setTimeout(function() {
						location.reload();
					}, res.time_diff*1000);
				} else {
					console.log(ress.message);
				}
			}
		});
	});

	function exportspass_zip(val) {
		$("#survey_idss").attr("value", val);
	}
	$('#Excelwithoutspss').on('click', function() {
		var survey_id = $("#survey_idss").val();
		var spss_export_types = $("#spss_export_types").val();
		$.ajax({
			type: 'post',
			url: 'exportspss.php',
			data: {
				'survey_id': survey_id,
				'spss_export_types': spss_export_types
			},
			beforeSend: function() {
				$('.loading-indicator').addClass('active');
			},
			success: function(result_data) {
				var res = JSON.parse(result_data);
				// console.log(ress);
				if (res.status == 200) {
					window.location.href = 'https://mquad.org/mis/' + res.file_name;
					$('.loading-indicator').removeClass('active');
					$("#ModalExportspass").hide();

					setTimeout(function() {
						location.reload();
					}, res.time_diff*1000);
				} else {
					console.log(res.message);
				}
			}
		});
	});

	$('#expExcelwithlabel').on('click', function(e) {
		e.preventDefault();
		var language_id = $("#language_id").val();
		var survey_id = $("#survey_id").val();
		var identification_type = $("#identification_type").val();
	
		$.ajax({
			type: 'post',
			url: 'export_multisheet_multilingual.php',
			dataType: 'json',
			data: {
				'language_id': language_id,
				'survey_id': survey_id,
				'identification_type': identification_type
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
					$("#ModalExport").hide();

				} else {
					console.log(res.message);
				}
			},
			error: function(xhr, status, error) {
				console.error(xhr.responseText);
				// Handle error - you can log to console or display an alert
			}

		})
	});

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

				// window.location.href = 'https://mquad.org/mis/' + res.fname;

				$('.loading-indicator').removeClass('active');
			}
		})
	});

</script>


