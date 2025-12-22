<?php include_once('includes/config.php'); ?>
<?php define("title", "Update Tool Archives | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
if ($_REQUEST['eid'] && $_REQUEST['eid'] != '') {
	$sqlsurveytool = "SELECT id,survey_title,description,questionnaire_type,category_id,uploaded_questionnaire,tool_access,client_name,uploaded_questionnaire,published_date,tool_study_year_from,tool_study_year_to,source FROM `survey_bank` where status=0 and id='" . $_REQUEST['eid'] . "' ";
	$qrydata = mysqli_query($conn, $sqlsurveytool);
	$data = mysqli_fetch_array($qrydata);
	$category_id = $data['category_id'];
}

?>
<?php
$questionnaire_type = "";
if (isset($_REQUEST['upload'])) {
	$questionnaire_type = $data['questionnaire_type'];

	$client_id = $_SESSION['client_id'];
	$clientId = "C" . $client_id;
	$user_id = $_SESSION['user_id'];
	$survey_title = $_REQUEST['survey_title'];
	$survey_bank_id = $_REQUEST['survey_bank_id'];
	$published_date = $_REQUEST['published_date'];
	$client_name = $_REQUEST['client_name'];
	// $category_id=$_REQUEST['category_id'];
	// $category_id=implode(",",$category_id);
	$description = $_REQUEST['description'];
	$tool_access = $_REQUEST['tool_access'];
	$questionnaire_type = $_POST['questionnaire_type'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$category_id = $_REQUEST['category_id'];
	$category_id = implode(",", $category_id);

	if ($questionnaire_type != "Link") {
		$filename = $_FILES['uploaded_questionnaire']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$filename = "" . $filename . "_" . date('his') . "." . $ext;
		$tempname = $_FILES['uploaded_questionnaire']['tmp_name'];
		$file_size = $_FILES['uploaded_questionnaire']['size'];
		$questionnaire_file = str_replace(" ", "_", $filename);
		$location = "upload_data_file/tools_archive_datafile/" . $clientId . "/";
		if (!file_exists($location)) {
			if (!mkdir($location, 0777, true)) {
				$error = 'Somthing Went wrong !!';
			} else {
				//echo "create";
				mkdir($location, 0777, true);
			}
		}
		move_uploaded_file($tempname, $location . $questionnaire_file);
	} else {
		$questionnaire_file = $_POST['questionnaire_link'];
	}


	$insertcontribute = mysqli_query($conn, "update survey_bank SET survey_title='" . $survey_title . "',description='" . $description . "',tool_access='" . $tool_access . "',client_name='" . $client_name . "',user_id='" . $user_id . "',tool_archive_status='0',tool_study_year_from= '" . $from_date . "',tool_study_year_to='" . $to_date . "',category_id='" . $category_id . "',uploaded_questionnaire='" . $questionnaire_file . "',questionnaire_type='" . $questionnaire_type . "'  where id='" . $survey_bank_id . "'");

	if ($insertcontribute) {
		$_SESSION['status'] = "Your data has been updated successfully";
		$_SESSION['status_code'] = "success";
		echo "<script>window.location.href='my_tool.php'</script>";
	} else {
		$_SESSION['status_error'] = "Something went wrong!!";
		$_SESSION['status_error_code'] = "warning";
	}
}
?>
<style>
	.star {
		color: red;
	}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>assets/sweetalerts/sweetalert2.all.min.js"></script>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-12 text-center">
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
					</div>
				</div>
				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Tool Archives</li>
					<li><i class="fa fa-plus"></i>Update Tool Archives</li>
				</ol>
			</div>
		</div>
		<!-- page start-->

		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">Tool Archive </header>
					<div class="panel-body">
						<div class="form">
							<form class="form-horizontal" id="myForm" method="post" enctype="multipart/form-data">
								<input type="hidden" name="survey_bank_id" value="<?= $_REQUEST['eid'] ?>" />
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Name: <span class="star">*</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="survey_name" name="survey_title" required type="text" value="<?= $data['survey_title']; ?>" />
									</div>
								</div>
								<div class="form-group ">
									<label class="form-label control-label col-lg-2" for="from_date">Year of Study: <span style="color:red; ">*</span></label>
									<div class="col-lg-10">
										<div class="row m-0">
											<div class="col-sm-6">
												<div class="form-group d-flex align-items-start ">
													<label class="form-label" for="from_date">From:</label>
													<input class="form-control" id="from_datepicker" required name="from_date" value="<?= $data['tool_study_year_from'];    ?>" type="text" />

												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group d-flex align-items-start  ">
													<label class="form-label" for="to_date">To:</label>
													<input class="form-control" id="to_datepicker" required name="to_date" value="<?= $data['tool_study_year_to'];    ?> " type=" text" />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Thematic Area: <span class="star">*</span></label>
									<div class="col-lg-10">
										<div class="form-check">

											<select name="category_id[]" class="form-control select2" multiple required data-placeholder="Select Thematic Area">
												<?php
												$category_id_array = explode(',', $category_id);

												$categorysql = mysqli_query($conn, "SELECT category_id, category_name FROM categories WHERE status='0'");

												while ($category = mysqli_fetch_array($categorysql)) {
													$selected = in_array($category['category_id'], $category_id_array) ? 'selected' : '';
												?>
													<option value="<?php echo $category['category_id']; ?>" <?php echo $selected; ?>>
														<?php echo $category['category_name']; ?>
													</option>
												<?php } ?>
											</select>


										</div>
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Institution/Organization: <span class="star">*</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="survey_name" name="client_name" type="text" required value="<?= $data['client_name']; ?>" />
									</div>
								</div>

								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Tool Access: <span style="color:red; ">*</span></label>
									<div class="col-lg-10">
										<select class="form-control" name="tool_access" required>
											<option value="">Select Tool Type</option>
											<option value="Public" <?php if ($data['tool_access'] == 'Public') {
																		echo "selected";
																	} ?>>Public</option>
											<option value="Private" <?php if ($data['tool_access'] == 'Private') {
																		echo "selected";
																	} ?>>Private</option>
										</select>
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Description: <span class="star">*</span></label>
									<div class="col-lg-10">
										<textarea class="form-control" required="" placeholder="" name="description" required rows="5"><?= $data['description']; ?></textarea>
									</div>
								</div>

								<div class="form-group">
									<label for="questionnaire_type" class="control-label col-lg-2">Questionnaire Type: <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<?php $savedValue = $data['questionnaire_type']; ?>
										<input type="radio" id="type1" required name="questionnaire_type" value="Link" <?php echo ($savedValue == 'Link') ? 'checked' : ''; ?>>
										<label for="type1"> Link</label>
										<input type="radio" id="type2" required name="questionnaire_type" value="File" <?php echo ($savedValue == 'File') ? 'checked' : ''; ?>>
										<label for="type2"> File</label>
									</div>
								</div>


								<div id="questionnaire_link_field" style="display:none;">
									<div class="form-group">

										<label for="questionnaire_link" class="control-label col-lg-2">Questionnaire Link:<span style="color:red;">*</span></label>
										<div class="col-lg-10">
											<?php
											$questionnaire_type = $data['questionnaire_type'];

											if ($questionnaire_type == "Link") {
												$values = $data['uploaded_questionnaire'];
											} ?>
											<input class="form-control" id="questionnaire_link" name="questionnaire_link" value="<?= $values ?> 
											
											" type="text" />
										</div>
									</div>
								</div>


								<div id="upload_questionnaire_field" style="display:none;">
									<div class="form-group">

										<label for="fileInput" class="control-label col-lg-2">Upload Questionnaire:<span style="color:red;">*</span> </label>
										<div class="col-lg-10">
											<input class="form-control" id="fileInput" name="uploaded_questionnaire" type="file" accept=".pdf,.xls,.xlsx,.doc,.docx,.csv" title="Accepted file types: .pdf, .xls, .xlsx, .doc, .docx, .csv" />
											<span id="file_error" style="color:red;"></span>
											<span>

												<?php

												$questionnaire_type = $data['questionnaire_type'];

												if ($questionnaire_type != "Link") {
													echo $data['uploaded_questionnaire'];
												}
												?></span>


										</div>
									</div>
								</div>





								<div class="col-lg-offset-2 col-lg-10 mt-xl  text-right">
									<button class="btn btn-primary" type="submit" id="submit" value="submit" name="upload">Submit</button>
								</div>
						</div>
						</form>
					</div>
			</div>
	</section>
	</div>
	</div>
	<!-- page end-->
</section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
	$("#datepicker").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		autoclose: true
	});
</script>
<script>
	$(".multiple-select").select2({
		//maximumSelectionLength: 2
	});
</script>

</script>
<script type="text/javascript">
	var currentYear = new Date().getFullYear();
	$("#from_datepicker").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		autoclose: true,
		endDate: new Date(currentYear, 11, 31)
	}).on("changeDate", function(selected) {
		var minDate = new Date(selected.date.valueOf());
		$("#to_datepicker").datepicker("setStartDate", minDate);
	});

	$("#to_datepicker").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		autoclose: true
	});

	$("#to_datepicker").on("changeDate", function() {
		var fromDate = $("#from_datepicker").datepicker("getDate");
		var toDate = $("#to_datepicker").datepicker("getDate");

		if (fromDate && toDate && toDate < fromDate) {
			$("#to_datepicker").datepicker("setDate", fromDate);
		}
	});
</script>
<script>
	function toggleQuestionnaireFields() {
		var linkField = document.getElementById('questionnaire_link_field');
		var fileField = document.getElementById('upload_questionnaire_field');
		var questionnaireLink = document.getElementById('questionnaire_link');
		var fileInput = document.getElementById('fileInput');

		if (document.getElementById('type1').checked) {
			linkField.style.display = 'block';
			fileField.style.display = 'none';
		} else if (document.getElementById('type2').checked) {
			linkField.style.display = 'none';
			fileField.style.display = 'block';
		}
	}

	document.addEventListener("DOMContentLoaded", function() {
		toggleQuestionnaireFields();

		document.getElementById('type1').addEventListener('change', toggleQuestionnaireFields);
		document.getElementById('type2').addEventListener('change', toggleQuestionnaireFields);
	});
</script>