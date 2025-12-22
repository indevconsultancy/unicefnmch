<?php include_once('../includes/config.php'); ?>
<?php define("title", "Add Dataset(s) | MQUAD"); ?>
<?php include_once('../includes/header.php'); ?>
<?php include_once('../includes/left-sidebar.php'); ?>
<?php include_once('../includes/functions1.php'); ?>

<?php

if (isset($_POST['submit'])) {

	$user_id = $_SESSION['user_id'];

	$title = sanitizeInput($_POST['title'], $conn);

	$description = sanitizeInput($_POST['description'], $conn);
	$type_of_study = $_POST['study_type_id'];
	$other_specify = sanitizeInput($_POST['other_specify'], $conn);
	$thematic_id = $_POST['category_id'];
	$keywords_array = $_POST['keywords'];
	$institution_array = $_POST['institution_company'];
	$authors_array = $_POST['author'];
	$contact_person_name = sanitizeInput($_POST['contact_person_name'], $conn);
	$contact_person_email = sanitizeInput($_POST['contact_person_email'], $conn);
	if (!filter_var($contact_person_email, FILTER_VALIDATE_EMAIL)) {
		$_SESSION['status_error'] = "Invalid Email ID";
		$_SESSION['status_error_code'] = "error";
	}
	$state_id = $_POST['state_id'];
	$from_date = $_POST['from_date'];
	$to_date = $_POST['to_date'];
	$use_of_term = $_POST['use_of_term'];

	$related_publication = mysqli_real_escape_string($conn, $_POST['related_publication']);
	$data_type = $_POST['data_type'];
	$country_id = $_POST['country_id'];
	$dataset_status = '';
	if ($_SESSION['role_id'] == '1') {
		$dataset_status = "dataset_status='1',";
	} else {
		$dataset_status = "dataset_status='0',";
	}

	$keywords_id = [];
	foreach ($keywords_array as $keywords_array) {
		$keyword = mysqli_real_escape_string($conn, $keywords_array);
		$check_keyword_query = mysqli_query($conn, "SELECT keywords_id FROM `keywords` WHERE keyword_name='$keyword'");
		$row_keyword = mysqli_fetch_assoc($check_keyword_query);
		if ($row_keyword) {
			$keywords_id[] = $row_keyword['keywords_id'];
		} else {
			mysqli_query($conn, "INSERT INTO keywords (keyword_name) VALUES ('$keyword')");
			$keywords_id[] = mysqli_insert_id($conn);
		}
	}
	$keywords_id_str = implode(",", $keywords_id);

	$authors_id = [];
	foreach ($authors_array as $authors_arrays) {
		$author = mysqli_real_escape_string($conn, $authors_arrays);
		$check_author_query = mysqli_query($conn, "SELECT authors_id FROM `authors` WHERE author_name='$author'");
		$row_author = mysqli_fetch_assoc($check_author_query);
		if ($row_author) {
			$authors_id[] = $row_author['authors_id'];
		} else {
			mysqli_query($conn, "INSERT INTO authors (author_name) VALUES ('$author')");
			$authors_id[] = mysqli_insert_id($conn);
		}
	}
	$author = implode(",", $authors_id);

	$institution_id = [];
	foreach ($institution_array as $institution_arrays) {
		$institution = mysqli_real_escape_string($conn, $institution_arrays);
		$check_institution_query = mysqli_query($conn, "SELECT institution_id FROM `institution` WHERE institution_name='$institution'");
		$row_institution = mysqli_fetch_assoc($check_institution_query);
		if ($row_institution) {
			$institution_id[] = $row_institution['institution_id'];
		} else {
			mysqli_query($conn, "INSERT INTO institution (institution_name) VALUES ('$institution')");
			$institution_id[] = mysqli_insert_id($conn);
		}
	}
	$institution = implode(",", $institution_id);
	$thematic_area_str = implode(",", $thematic_id);
	$stateId = implode(",", $state_id);

	$insert_query = "INSERT INTO project_datasets set $dataset_status title='" . $title . "', description='" . $description . "', type_of_study_id='" . $type_of_study . "', type_of_study_other='" . $other_specify . "', thematic_area_id='" . $thematic_area_str . "', keywords_id='" . $keywords_id_str . "', authors_id='" . $author . "', institution_id='" . $institution . "', contact_person_name='" . $contact_person_name . "', contact_person_email='" . $contact_person_email . "', collaboration_id='" . $collaboration_id . "', user_id='" . $user_id . "', related_publication='" . $related_publication . "', data_type='" . $data_type . "', country_id='" . $country_id . "', state_id='" . $stateId . "', project_id='" . $project_id . "',use_of_term='" . $use_of_term . "',from_date='" . $from_date . "',to_date='" . $to_date . "'";

	$result = mysqli_query($conn, $insert_query);
	$lastId = mysqli_insert_id($conn);
	$folderName = "D" . $lastId;
	$location = "../upload_data_file/dataset/" . $folderName . "/";
	if (!file_exists($location)) {
		if (!mkdir($location, 0777, true)) {
		} else {
		}
	}

	if ($result) {
		$_SESSION['status'] = "Dataset has been successfully submitted";
		$_SESSION['status_code'] = "success";
		echo "<script>window.location.href='dataset-list.php'</script>";
	} else {
		$_SESSION['status_error'] = "Something went wrong!!";
		$_SESSION['status_error_code'] = "error";
	}
}
?>
<style>
	.field_set {

		border: 1px groove #ddd !important;
		padding-top: 10px;
		padding-right: 5px;
		padding-bottom: 5px;
		margin: 0 0 1.5em 0 !important;
		-webkit-box-shadow: 0px 0px 0px 0px #000;
		box-shadow: 0px 0px 0px 0px #000;

		#more {
			display: none;
		}
	}

	.breadcrumb {
		-webkit-border-radius: 0px;
		-moz-border-radius: 0px;
		border-radius: 0px;
		height: auto;
		position: relative;
		margin: 0 0 19px 0;
		overflow: hidden;
		background-color: #747474 !important;
		color: #fff !important;
		padding: 8px 15px;
	}

	@media (max-width: 768px) {
		.field_set {
			border: none !important;
		}
	}

	.note-editor .note-toolbar {
		background: #dedede !important;
	}
</style>

<link href="<?= base_url(); ?>css/select2.min.css" rel="stylesheet" />
<link href="<?= base_url(); ?>css/datepicker.min.css" rel="stylesheet">

<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Dataset(s)</li>
						<li class="breadcrumb-item" aria-current="page"><i class="fa fa-plus"></i>Add Dataset(s)</li>
					</ol>
				</nav>
			</div>
		</div>
		<!-- page start-->

		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">Add Dataset(s)
					</header>
					<div class="panel-body">
						<div class="form">
							<form class="form-validate form-horizontal" id="myForm" method="post" enctype="multipart/form-data">
								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Title: <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="title" required name="title" type="text" />

									</div>
								</div>
								<div class="row">
									<label for="cname" class="control-label col-lg-2">Description: <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<textarea class="summernote" required placeholder="" name="description" rows="5"></textarea>
									</div>
								</div>

								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Type of Study: <span style="color:red; ">*</span></label>
									<div class="col-lg-10">
										<select class="form-control form-select " name="study_type_id" onchange="GetOtherField(this)">
											<option value="" hidden>Select Type of Study</option>
											<?php
											$sql = "SELECT study_type_id,study_name FROM study_types ";
											$getstuday = mysqli_query($conn, $sql);
											while ($row = mysqli_fetch_array($getstuday)) { ?>
												<option value="<?php echo $row['study_type_id']; ?>"><?php echo $row['study_name']; ?></option>
											<?php }  ?>
										</select>
									</div>
								</div>
								<div class="mb-3 row">
									<label class="form-label control-label col-lg-2" for="from_date">Year of Study <span style="color:red; ">*</span></label>
									<div class="col-lg-10">
										<div class="row">
											<div class="col-sm-6">
												<div class="mb-3 rowd-flex align-items-start ">
													<label class="form-label" for="from_date">From:</label>
													<input class="form-control" id="from_datepicker" required name="from_date" type="text" readonly />

												</div>
											</div>
											<div class="col-sm-6">
												<div class="mb-3 rowd-flex align-items-start  ">
													<label class="form-label" for="to_date">To:</label>
													<input class="form-control" id="to_datepicker" required name="to_date" type="text" readonly />
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Thematic Area(s): <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<select class="form-control select2" multiple name="category_id[]" id="category_id" required>
										<option value="" disabled>Select Thematic Area(s)</option>
										<?php
										$getCategoryname = mysqli_query($conn, "SELECT category_id, category_name FROM `categories` WHERE status='0' ORDER BY sequence ASC");
										while ($categoryid = mysqli_fetch_array($getCategoryname)) { ?>
											<option value="<?php echo $categoryid['category_id']; ?>"><?php echo $categoryid['category_name']; ?></option>
										<?php } ?>
									</select>
									</div>
								</div>
								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Keyword(s): <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<div class="form-check p-0">
											<select class="form-control form-select select2" multiple="multiple" required id="keywords" data-tags="true" name="keywords[]">
											</select>
										</div>
									</div>
								</div>
								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Institution(s)/Company(s): <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<div class="form-check p-0">
											<select class="form-control form-select select2" multiple="multiple" id="institution_company" required="" data-tags="true" name="institution_company[]">
											</select>
										</div>
									</div>
								</div>


								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Data Type: <span style="color:red; ">*</span></label>
									<div class="col-lg-10">
										<select class="form-control form-select" id="data_type_select" name="data_type">
											<option value="" hidden>Select Data Type</option>
											<option value="Quantitative">Quantitative</option>
											<option value="Qualitative">Qualitative</option>
											<option value="Other">Other</option>
										</select>
									</div>
								</div>
								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Country: <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<select class="form-control form-select select2" name="country_id" onchange="getState(this.value)">
											<option value="">Select Country</option>
											<?php
											$sql = "SELECT country_id, country_name FROM country";
											$getcountry = mysqli_query($conn, $sql);
											while ($row = mysqli_fetch_array($getcountry)) {
											?>
												<option value="<?php echo $row['country_id']; ?>"><?php echo $row['country_name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">State/Province: <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<div class="form-check p-0">
											<select class="form-control form-select" id="state_id" name="state_id[]" multiple>
												<option value="">Select State</option>
											</select>
										</div>
									</div>
								</div>


								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Author(s): <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<div class="form-check p-0">
											<select class="form-control select2" multiple="multiple" required id="author" data-tags="true" name="author[]">
											</select>
										</div>
									</div>
								</div>

								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Contact Person Name: <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input class="form-control " id="contact_person_name" required name="contact_person_name" type="text" />

									</div>
								</div>
								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Contact Person Email: <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input class="form-control " id="contact_person_email" required name="contact_person_email" type="text" />

									</div>
								</div>
								<div class="mb-3 row">
									<label for="cname" class="control-label col-lg-2">Recent Publication(s):</label>
									<div class="col-lg-10">
										<textarea class="summernote" required placeholder="" id="related_publication" name="related_publication" rows="3"></textarea>
									</div>
								</div>
								<div class="mb-3 row">
									<label class="control-label col-lg-2" for="use_of_term">Terms of Data Use <span style="color:red;">*</span></label>
									<div class="col-lg-10">

										<textarea cols="30" rows="5" class="summernote" required name="use_of_term" id="use_of_term">
 										<span class="con-title">CONFIDENTIALITY:</span>
										<p>You will not attempt to identify individual study participants. You will not report information that could directly or by inference identify individual study participants.</p>
										<br>
										<span class="con-title">ETHICS:</span>
										<p>You will obtain relevant approval prior to using the data.</p>
										<br>
										<span class="con-title">ACCESS:</span>
										<p>Access to the data will be limited to the personnel named in the Data Application form. You will not distribute nor permit others to distribute any of the data to a person who is not listed. If new staff join the research team, you will submit an additional copy of the Application.</p>
										<br>
										<span class="con-title">SECURITY:</span>
										<p>You will store and analyze the data in a secure computing environment.</p>
										<br>
										<span class="con-title"><b>ACKNOWLEDGMENT:</b></span>
										<p>You will cite the data as the data source in all reports, presentations, and publications based on the data. Citations will appear in footnotes or in the reference section of any manuscripts.</p>
										<br>
										<span class="con-title">PUBLICATIONS:</span>
										<p>At the conclusion of the proposed research, you will provide a copy of any publication or report based in whole or in part on the data to the data owner.</p>
										<br>
										<span class="con-title">LOSS OF PRIVILEGE TO USE DATA:</span>
										<p>If the data owner determines that you are in violation of these terms and conditions, you will destroy the dataset, and any derivative data files, upon request.</p>
										<br>
 									        </textarea>
									</div>
								</div>

								<div class="mb-3 row">
									<div class="text-end">
										<button class="btn btn-primary" type="submit" id="submit" name="submit">Submit</button>
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

<?php include_once('../includes/footer.php'); ?>

<?php include_once('../includes/summernote.php'); ?>
<script src="<?= base_url(); ?>js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url(); ?>js/select2.min.js"></script>



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
} ?>

<?php
$keyword_query = mysqli_query($conn, "SELECT keywords_id,keyword_name FROM `keywords` where status='1'");
$keyword_name = '';
while ($keyword_data = mysqli_fetch_array($keyword_query)) {
	$keyword_name .= "{ id: '" . $keyword_data['keyword_name'] . "', text: '" . $keyword_data['keyword_name'] . "' },";
}
?>
<?php
$author_query = mysqli_query($conn, "SELECT authors_id,author_name FROM `authors` where status='1'");
$author_name = '';
while ($author_data = mysqli_fetch_array($author_query)) {
	$author_name .= "{ id: '" . $author_data['author_name'] . "', text: '" . $author_data['author_name'] . "' },";
}
?>
<?php
$institution_query = mysqli_query($conn, "SELECT institution_id,institution_name FROM `institution` where status='1'");
$institution_name = '';
while ($institution_data = mysqli_fetch_array($institution_query)) {
	$institution_name .= "{ id: '" . $institution_data['institution_name'] . "', text: '" . $institution_data['institution_name'] . "' },";
}
?>
<script>
	$(document).ready(function() {
		$(".codebookFile").change(function() {
			var codebookFile = $(this);
			var filePath = codebookFile.val();
			var file_error = $("#file_error");
			var allowedExtensions = /(\.xlsx|\.xls|\.pdf|\.doc|\.docx|\.csv)$/i;

			if (!allowedExtensions.test(filePath)) {
				//file_error.html("Please upload a valid file (xlsx, xls, pdf, doc, docx, csv)");
				alert("Please upload a valid file (xlsx, xls, pdf, doc, docx, csv).");
				//return false;
				codebookFile.val(''); // Clear the file input field
			}

		});
	});
</script>

<script type="text/javascript">
	$("#category_id").select2({
		//maximumSelectionLength: 2
	});
	$("#category_id").select2({
		//maximumSelectionLength: 2
	});
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
	$(document).ready(function() {
		$('.ss input:checkbox').on('click', function() {
			$(this).closest('.ss').find('.ch_for').toggle();
		})
	});
</script>
<script>
	var sss = 1;

	function myFunction(val) {
		var readmoreid = 1;
		//alert(sss);
		$.ajax({
			type: 'post',
			url: 'add_databank_ajax.php',
			data: {
				readmoreid: readmoreid,
				sss: sss
			},
			success: function(responsedata) {
				// alert(responsedata);
				$('#readId').append(responsedata);
			}
		});
		sss++;
	}

	$(document).on("click", ".remv", function() {
		$(this).parent().remove();
	});
</script>
<script>
	$('#institution_company').select2({
		minimumInputLength: 1,
		data: [<?= $institution_name ?>
			/*   {
        id: 'Study',
        text: 'Study'
      }, {
        id: 'Environment',
        text: 'Environment'
      }, */
		]
	});
</script>
<script>
	$('#keywords').select2({
		minimumInputLength: 1,
		data: [<?= $keyword_name ?>
			/*   {
        id: 'Study',
        text: 'Study'
      }, {
        id: 'Environment',
        text: 'Environment'
      }, */
		]
	});
</script>
<script>
	$('#author').select2({
		minimumInputLength: 1,
		data: [<?= $author_name ?>
			/*   {
        id: 'Study',
        text: 'Study'
      }, {
        id: 'Environment',
        text: 'Environment'
      }, */
		]
	});
</script>
<script>
	$(document).ready(function() {
		$('#state_id').select2();
	});

	function getState(country_id) {
		// alert(country_id)
		$.ajax({
			url: "../ajax/get_ajax.php",
			type: "post",
			data: {
				country_id_mul: country_id
			},
			success: function(appppp) {
				console.log(appppp);
				$("#state_id").html(appppp);
			}
		});
	}
</script>
<!-- new java script add by neeraj bora -->
<script>
	$(document).ready(function() {
		$('#sidebar > ul > li.sub-menu').click(function() {
			// Toggle 'open' class on clicked item
			$(this).toggleClass('open');

			// Toggle 'show' class on its child '.sub' element
			$(this).find('.sub').toggleClass('show');

			// Toggle 'active' class on its anchor element
			$(this).find('a').toggleClass('active');

			// Remove classes from other '#sidebar > ul > li.sub' items
			$('#sidebar > ul > li.sub-menu').not(this).removeClass('open');
			$('#sidebar > ul > li.sub-menu .sub').not($(this).find('.sub')).removeClass('show');
			$('#sidebar > ul > li.sub-menu a').not($(this).find('a')).removeClass('active');
		});
	});
</script>