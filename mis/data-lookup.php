<?php include_once('includes/config.php'); ?>
<?php define("title", "Data Lookup | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
header('Content-Type: text/csv; charset=UTF-8');
//error_reporting(1);
if (isset($_POST['submit'])) {
	$survey_id = $_REQUEST['survey_id'];
	
	$filename = $_FILES['lookupfile']['name'];
	$tmp_name = $_FILES['lookupfile']['tmp_name'];
	$allowedExtensions = ['xlsx', 'xls'];
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	
	if ($survey_id != "") {
		if(in_array($ext, $allowedExtensions)){
			set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
			include 'PHPExcel/Classes/PHPExcel/IOFactory.php';
			$file = $tmp_name; //'/media/sf_E_DRIVE/om/newData.xlsx';
			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($file);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			$CurrentWorkSheetIndex = 0;
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				
				$title_name = $worksheet->getTitle();
				$sheetSno = $objPHPExcel->getIndex($worksheet); //, PHP_EOL;
				$highestRow = $worksheet->getHighestDataRow();
				$highestColumn = $worksheet->getHighestDataColumn();
				$headings = $worksheet->rangeToArray('A1:' . $highestColumn . 1, NULL, TRUE, FALSE);
				//echo "<pre>";

				//LOOCKUPS
				$lookups_arr_name = $title_name;
				if ($sheetSno >= 0) {
					
					for ($row = 2; $row <= $highestRow; $row++) {
						$rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
						$rowData[0] = array_combine($headings[0], $rowData[0]);
						foreach ($rowData as $keylkup => $rowvalue) {
							
							$l = 0;
							foreach ($rowvalue as $lkey => $lookupVal) {
								
								if ($l == 0) {
									$fsudatas[$lkey] = $lookupVal;
								}

								$fsu_data[$lkey] = $lookupVal;
								$l++;
							}
							$fsudatas["data"][] = $fsu_data;
							$fsu_data = array();
							$fsudata[] = $fsudatas;
							$fsudatas = array();
						}
					}
					$fsudataArr[$lookups_arr_name] = $fsudata;
					$fsudata = array();
					$fsudataArrArr["lookups"][] = $fsudataArr;
					$fsudataArr = array();

				}
			}

			$lookups_json = json_encode($fsudataArrArr,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            //echo "INSERT INTO survey_loockups SET survey_id='" . $survey_id . "', loockup_data='" . $lookups_json . "' ";
			//die();
			$insert = mysqli_query($conn, "INSERT INTO survey_loockups SET survey_id='" . $survey_id . "', loockup_data='$lookups_json'");
			if ($insert != '') {
				$_SESSION['status'] = "Lookups Data Uploaded Successfully";
				$_SESSION['status_code'] = "success";
				echo "<script>window.location.href='survey-list.php'</script>";
			} else {
				$_SESSION['status_error'] = "Something went wrong!!";
				$_SESSION['status_error_code'] = "error";
			}
		}else{
			$_SESSION['status_error'] = "Invalid file type!!";
			$_SESSION['status_error_code'] = "error";
		}
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
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Form</li>
						<!--<a href=""survey-list.php></a> -->
						<li class="breadcrumb-item active" aria-current="page"><i class="fa fa-upload"></i>Data Lookup</li>
					</ol>
				</nav>
			</div>
		</div>
		<!-- page start-->


		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">
						Data Lookup
					</header>

					<div class="panel-body">
						<div class="form">
							<form class="form-validate form-horizontal " id="register_form" method="post" enctype="multipart/form-data">
								
								<div class="mb-3 row">
									<label for="fileUpload" class="col-sm-2 col-form-label text-end">Lookup File: <span style="color:red;">*</span></label>
									<div class="col-sm-10">
										<input type="file" id="fileUpload" name="lookupfile" required class="form-control" accept=".xlsx,.xls," />
										<span id="file_error" style="color:red;"></span>
									</div>
								</div>

								<div class="mb-3 row">
									<div class="col-sm-10 offset-sm-2 text-end">
										<button class="btn btn-primary" type="submit" id="btnUpload" name="submit">Submit</button>
									</div>
								</div>
							</form>
						</div>
				</section>
			</div>
		</div>
		<!-- page end-->
	</section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>
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
	//Data lookups file upload
	$("body").on("click", "#btnUpload", function() {
		var allowedFiles = [".xlsx", ".xls"];
		var fileUpload = $("#fileUpload");
		var file_error = $("#file_error");
		var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
		if (!regex.test(fileUpload.val().toLowerCase())) {
			file_error.html("Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.");
			return false;
		}
		file_error.html('');
		return true;
	});
</script>