<?php
include_once('../includes/config.php');
define("title", "Add Data | MQUAD");
include_once('../includes/header.php');
include_once('../includes/left-sidebar.php');
include_once('../includes/functions1.php');

$dataset_id = $_GET['dataset_id'];
$datasetId = "D" . $dataset_id;

if (isset($_POST['submit'])) {
	$digits = 4;
	$unique_id = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);

	$userId = $_SESSION['user_id'];
	$description = sanitizeInput($_POST['description'], $conn);
	$dataset_name = sanitizeInput($_POST['dataset_name'], $conn);
	$data_access = $_POST['data_access'];
	$dataformat = $_POST['data_formet'];
	$use_of_term = $_POST['use_of_term'];

	if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
		$data_formate_names = $_FILES['file_upload']['name'];
		$tempname = $_FILES['file_upload']['tmp_name'];
		$ext = strtolower(pathinfo($data_formate_names, PATHINFO_EXTENSION));
		$filename_without_ext = pathinfo($data_formate_names, PATHINFO_FILENAME);
		$file_nameC = $filename_without_ext . "-" . $unique_id . '.' . $ext;

		// Define allowed file extensions
		$allowed_extensions = ['xls', 'xlsx', 'dta', 'sav', 'sas7bdat', 'sas', 'json'];

		if (in_array($ext, $allowed_extensions)) {
			$locationC = "../upload_data_file/dataset/" . $datasetId . "/";

			if (!is_dir($locationC)) {
				mkdir($locationC, 0755, true); // Create directory if it does not exist
			}

			if (move_uploaded_file($tempname, $locationC . $file_nameC)) {
				$sqlInsertDataset = "INSERT INTO project_dataformat_file (dataset_name, dataformat_name, dataformat_fie, user_id, data_access, description, dataset_id, use_of_term) VALUES ('$dataset_name', '$dataformat', '$file_nameC', '$userId', '$data_access', '$description', '$dataset_id', '$use_of_term')";
				$result = mysqli_query($conn, $sqlInsertDataset);

				if ($result) {
					$_SESSION['status'] = "Your data has been successfully submitted";
					$_SESSION['status_code'] = "success";
					echo "<script>window.location.href='dataset-list.php'</script>";
				} else {
					$_SESSION['status_error'] = "Something went wrong!!";
					$_SESSION['status_error_code'] = "error";
				}
			} else {
				$_SESSION['status_error'] = "File upload failed!!";
				$_SESSION['status_error_code'] = "error";
			}
		} else {
			$_SESSION['error'] = 'Invalid file format. Allowed formats: Excel, STATA, SPSS, SAS, JSON';
		}
	} else {
		$_SESSION['status_error'] = "No file uploaded or there was an error uploading the file.";
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
	}

	@media (max-width: 768px) {
		.field_set {
			border: none !important;
		}
	}


	#more {
		display: none;
	}
</style>
<link href="<?= base_url(); ?>css/select2.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>css/datepicker.min.css" rel="stylesheet">

<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>List Dataset(s)</li>
						<li class="breadcrumb-item active" aria-current="page"><i class="fa fa-plus"></i>Add Data</li>
					</ol>
				</nav>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">Add Data</header>
					<div class="panel-body">
						<div class="form">
							<form class="form-validate form-horizontal" id="myForm" method="post"
								enctype="multipart/form-data">
								<div class="row mb-3">
									<label for="cname" class="control-label col-lg-2">Title: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input class="form-control" id="dataset_name" required name="dataset_name"
											type="text">
									</div>
								</div>
								<div class="row">
									<label for="cname" class="control-label col-lg-2">Description: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<textarea class="summernote" placeholder="" name="description"
											rows="5"></textarea>
									</div>
								</div>
								<div class="row mb-3">
									<label for="cname" class="control-label col-lg-2">Data Access: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<select class="form-select" required name="data_access">
											<option value="">Select Data Type</option>
											<option value="Public">Public</option>
											<option value="Private">Private</option>
										</select>
									</div>
								</div>
								<div class="row mb-3">
									<label for="cname" class="control-label col-lg-2">Select Format: <span
											style="color:red;">*</span></label>
									<div class="col-lg-10">
										<select class="form-control form-select" required name="data_formet"
											id="formatSelect">
											<option value="">Select Format</option>
											<option value="Excel">Excel</option>
											<option value="SAS">SAS</option>
											<option value="STATA">STATA</option>
											<option value="JSON">JSON</option>
											<option value="SPSS">SPSS</option>
										</select>
									</div>
								</div>
								<div id="uploadOption" style="display: none;" class="mb-3">
									<div class="row">
										<label for="cname" class="control-label col-lg-2">Upload File: <span
												style="color:red;">*</span></label>
										<div class="col-lg-10">
											<input type="file" class="form-control-file form-control" name="file_upload"
												id="fileUpload">
										</div>
									</div>
								</div>
								<div class="row mb-3" style="margin-top: 17px;">
									<label for="cname" class="control-label col-lg-2">Terms of Data Use: </span></label>

									<div class="col-lg-10">
										<textarea cols="30" rows="5" class="summernote" name="use_of_term"
											id="use_of_term">
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
											<span class="con-title">ACKNOWLEDGMENT:</span>
											<p>You will cite the data as the data source in all reports, presentations, and publications based on the data. Citations will appear in footnotes or in the reference section of any manuscripts.</p>
											<br>
											<span class="con-title">PUBLICATIONS:</span>
											<p>At the conclusion of the proposed research, you will provide a copy of any publication or report based in whole or in part on the data to the data owner.</p>
											<br>
											<span class="con-title">LOSS OF PRIVILEGE TO USE DATA:</span>
											<p>If the data owner determines that you are in violation of these terms and conditions, you will destroy the dataset, and any derivative data files, upon request.</p>
  											</textarea>
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-lg-offset-2 col-lg-12 text-end">
										<button class="btn btn-primary" type="submit" id="submit"
											name="submit">Submit</button>
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

<script>
	$(".multiple-select").select2();
	$("#datepicker").datepicker({
		format: "yyyy",
		viewMode: "years",
		minViewMode: "years",
		autoclose: true // to close picker once year is selected
	});

	$('.ss input:checkbox').on('click', function() {
		$(this).closest('.ss').find('.ch_for').toggle();
	});

	document.getElementById("formatSelect").addEventListener("change", function() {
		var selectedFormat = this.value;
		var uploadOption = document.getElementById("uploadOption");
		var fileInput = document.getElementById("fileUpload");

		fileInput.value = '';

		switch (selectedFormat) {
			case "Excel":
				fileInput.accept = ".xlsx";
				break;
			case "SAS":
				fileInput.accept = ".sas7bdat,.sd2,.sd7,.sas,.sas7bcat,.sas7bdat,.sas7bdax,.sas7bndx,.sas7bpgm,.sas7bvew,.sas7mdb,.sas7sdmx,.sas7sxat,.sas7xdat,.sasbdat,.sasbndx,.sascat,.sasdat,.sasidx,.sasxport,.sastdcat,.sastdidx";
				break;
			case "STATA":
				fileInput.accept = ".dta";
				break;
			case "JSON":
				fileInput.accept = ".json";
				break;
			case "SPSS":
				fileInput.accept = ".sav";
				break;
			default:
				fileInput.accept = "";
		}

		if (selectedFormat !== "") {
			uploadOption.style.display = "block";
		} else {
			uploadOption.style.display = "none";
		}
	});

	document.getElementById("fileUpload").addEventListener("change", function() {
		var selectedFormat = document.getElementById("formatSelect").value;
		var selectedFile = this.files[0];

		if (selectedFormat !== "") {
			var allowedExtensions = document.getElementById("fileUpload").accept.split(',');
			var fileExtension = selectedFile.name.split('.').pop();

			if (!allowedExtensions.includes("." + fileExtension)) {
				alert("Please select a file with the appropriate extension for the chosen format.");
				this.value = '';
			} else {
				console.log("Selected file:", selectedFile);
			}
		} else {
			alert("Please select a format before choosing a file.");
			this.value = '';
		}
	});
</script>