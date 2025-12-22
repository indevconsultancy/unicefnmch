  <?php session_start(); ?>
  <?php include('includes/config.php'); ?>
  <?php include('includes/functions.php'); ?>
  <?php include('includes/headers.php'); ?>

  <!-- Upload CSV Code -->
  <?php
	$csvMimes = array(
		'text/x-comma-separated-values',
		'text/comma-separated-values',
		'application/octet-stream',
		'application/vnd.ms-excel',
		'application/x-csv',
		'text/x-csv',
		'text/csv',
		'application/csv',
		'application/excel',
		'application/vnd.msexcel',
		'text/plain'
	);

	$invalidValues = ['#NAME?', '#VALUE!', '#REF!', '#DIV/0!', '#NUM!', '#N/A', 'N/A', 'NULL', 'INF', '-INF', '-Infinity'];
	function cleanExcelValue($val, $conn)
	{
		$val = trim($val);
		global $invalidValues;
		return (in_array(strtoupper($val), $invalidValues) || $val === '') ? '' : mysqli_real_escape_string($conn, $val);
	}

	if (isset($_POST['upld_csv'])) {
		if (!empty($_FILES['csv_file']['name']) && in_array($_FILES['csv_file']['type'], $csvMimes)) {
			if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {

				$csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');
				if (!$csvFile) {
					echo "<script>alert('Unable to read file.'); window.location.href='intergrowth_monitoring.php';</script>";
					exit;
				}

				// Optional: skip header row
				// fgetcsv($csvFile);

				while (($line = fgetcsv($csvFile)) !== FALSE) {
					// Defensive: Ensure minimum column count
					if (count($line) < 12) continue;

					// Valdate id is valid or blank
					$id_raw = trim($line[0]);
					if (!ctype_digit($id_raw) || (int)$id_raw <= 0) continue;
					$id = (int)$id_raw;

					// Escape & sanitize
					$LengthZScore = cleanExcelValue(trim($line[4]), $conn);
					$LengthCentile = cleanExcelValue(trim($line[5]), $conn);
					$WeightZScore = cleanExcelValue(trim($line[7]), $conn);
					$WeightCentile = cleanExcelValue(trim($line[8]), $conn);
					$HeadCircumferenceZScore = cleanExcelValue(trim($line[10]), $conn);
					$HeadCircumferenceCentile = cleanExcelValue(trim($line[11]), $conn);

					// Determine growth classification
					if ($WeightCentile < 10) {
						$growth_class = 'SGA';
					} elseif ($WeightCentile <= 90) {
						$growth_class = 'AGA';
					} else {
						$growth_class = 'LGA';
					}

					// Only update if any relevant value is available
					if ($LengthZScore !== '' || $WeightZScore !== '' || $growth_class !== '') {
						$query = "
                        UPDATE monitoring_data 
                        SET 
                            intergrowth_lenage = '$LengthZScore',
                            intergrowth_per_lenage = '$LengthCentile',
                            intergrowth_wtage = '$WeightZScore',
                            intergrowth_per_wtage = '$WeightCentile',
                            intergrowth_head_circum = '$HeadCircumferenceZScore',
                            intergrowth_head_circum_per = '$HeadCircumferenceCentile',
                            intergrowth_classification = '$growth_class',
                            growth_status_intergrowth = '1'
                        WHERE id = $id";
						mysqli_query($conn, $query);
					}
				}

				fclose($csvFile);

				echo "<script>alert('Upload data successfully.'); window.location.href='intergrowth_monitoring.php';</script>";
			} else {
				echo "<script>alert('Upload failed. Please try again.'); window.location.href='intergrowth_monitoring.php';</script>";
			}
		} else {
			echo "<script>alert('Invalid file. Please upload a valid CSV file.'); window.location.href='intergrowth_monitoring.php';</script>";
		}
	}
	?>


  <!-- Enable/Disable Export -->
  <?php $showExport = !empty($_GET['type']); ?>

  <!-- Search Code -->
  <?php
	$filter = '';
	$extraParams = [];
	if (isset($_REQUEST['search'])) {
		$type = $_GET['type'] ?? '';

		if (!empty($type) && $type == 'nbvp') {
			$filter .= " AND reg.gestational_age_LBW>=24 and reg.gestational_age_LBW<33";
			$extraParams['type'] = $type;
		}
		if (!empty($type) && $type == 'nbp') {
			$filter .= " AND reg.gestational_age_LBW>=33 and reg.gestational_age_LBW<43";
			$extraParams['type'] = $type;
		}
		$extraParams['search'] = $_GET['search'];
	}
	?>

  <?php
	$limit = 10;
	// Get current pages
	$child_page = isset($_GET['child_page']) ? (int)$_GET['child_page'] : 1;
	$childOffset = ($child_page - 1) * $limit;

	$childQuery = "SELECT md.id,md.registration_id,md.admission_weight,md.admission_length,md.admission_head_circumference,reg.sex,DATEDIFF(md.date_of_admission,reg.baby_date_of_birth) AS age_months,reg.gestational_age_LBW from monitoring_data md,registration_form reg where md.registration_id=reg.id and md.type_of_monitoring!='Mother and Newborn at MNCU' and md.admission_weight>0 $filter and md.growth_status_intergrowth=0 LIMIT $limit OFFSET $childOffset";
	$childResult = mysqli_query($conn, $childQuery);

	$total_child = mysqli_fetch_row(mysqli_query($conn, "SELECT count(md.id) from monitoring_data md,registration_form reg where md.registration_id=reg.id and md.type_of_monitoring!='Mother and Newborn at MNCU' and md.admission_weight > 0 and md.growth_status_intergrowth=0 $filter"))[0];
	$totalchildPages = ceil($total_child / $limit);
	?>

  <div class="main-content">

  	<div class="page-content">
  		<div class="container-fluid">

  			<!-- start page title -->
  			<!-- <div class="row">
  				<div class="col-12">
  					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
  						<h4 class="mb-sm-0">Intergrowth Data List</h4>

  						<div class="page-title-right">
  							<ol class="breadcrumb m-0">
  								<li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
  								<li class="breadcrumb-item">Intergrowth Data</li>
  								<li class="breadcrumb-item active">Monitroing</li>
  							</ol>
  						</div>

  					</div>
  				</div>
  			</div> -->
  			<div class="row">
  				<div class="col-12">
  					<div class="page-title-box d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between">
  						<h4 class="mb-2 mb-sm-0">Intergrowth Data List</h4>
  						<div class="page-title-right">
  							<ol class="breadcrumb m-0">
  								<li class="breadcrumb-item"><a href="javascript:void(0);">Listing</a></li>
  								<li class="breadcrumb-item">Intergrowth Data</li>
  								<li class="breadcrumb-item active">Monitoring</li>
  							</ol>
  						</div>
  					</div>
  				</div>
  			</div>

  			<!-- end page title -->
  			<div class="row">
  				<div class="col-lg-12">
  					<div class="card">
  						<div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
  							<h4 class="card-title mb-0">Monitoring List</h4>
  							<div class="d-flex flex-wrap gap-2">
  								<a class="btn btn-warning" href="https://intergrowth21.ndog.ox.ac.uk/" target="_blank">
  									Generate Growth Status <i class="ri-links-fill"></i>
  								</a>
  								<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploaddata">
  									Upload Data
  								</button>
  							</div>
  						</div>

  						<div class="card-body">
  							<form method="GET">
  								<div class="row g-3 align-items-center mb-3">
  									<div class="col-12 col-md-auto">
  										<h5 class="mb-0">Total Records: <span><?php echo $total_child ?></span></h5>
  									</div>
  									<div class="col-12 col-md">
  										<select name="type" id="type" class="form-control">
  											<option value="">-- select type --</option>
  											<option value="nbvp" <?= (isset($_GET['type']) && $_GET['type'] == 'nbvp') ? 'selected' : '' ?>>Newborn Size for Very Preterm Infants (24 to 33 weeks)</option>
  											<option value="nbp" <?= (isset($_GET['type']) && $_GET['type'] == 'nbp') ? 'selected' : '' ?>>Newborn Size (33 and 43 weeks)</option>
  										</select>
  									</div>
  									<div class="col-6 col-md-auto">
  										<button type="submit" name="search" id="searchBtn" class="btn btn-secondary w-100" disabled>
  											Search
  										</button>
  									</div>
  									<div class="col-6 col-md-auto">
  										<button type="reset" name="reset" onclick="resetSearch()" class="btn btn-danger w-100">
  											Reset
  										</button>
  									</div>
  									<div class="col-12 col-md-auto <?= $showExport ? '' : 'd-none' ?>">
  										<a href="includes/export.php?filename=intergrowth-data" class="btn btn-success w-100">
  											Export to CSV
  										</a>
  									</div>
  								</div>
  							</form>

  							<div class="table-responsive table-card mt-3 mb-1">
  								<table class="table align-middle table-nowrap" id="intergrowth_minitor">
  									<thead class="table-light">
  										<tr>
  											<th>Id</th>
  											<th>Baby Id</th>
  											<th>SNCU Name</th>
  											<th>Mother Name</th>
  											<th>Type of Monitoring</th>
  											<th>Gender</th>
  											<th>Gestational Age</th>
  											<th>Weight</th>
  											<th>Length</th>
  											<th>Headcircumference</th>
  										</tr>
  									</thead>
  									<tbody class="list form-check-all">
  										<?php
											$_SESSION['db_column'] = 'mon_id,sex,GA,admission_weight,admission_length,admission_head_circumference';
											$_SESSION['header_column'] = 'ID,Sex,GA,Weight,Length,Headcircumference';

											$sqlfacilitator = mysqli_query($conn, "SELECT reg.monitor_name, reg.unique_id_of_body, reg.sncu_id, reg.boby_of_mothers_name, reg.sex, reg.gestational_age_LBW, DATEDIFF(md.date_of_admission, reg.baby_date_of_birth) AS age_in_days, md.id AS mon_id, md.type_of_monitoring, md.admission_weight, md.admission_length, md.admission_head_circumference FROM registration_form AS reg JOIN monitoring_data AS md ON reg.id = md.registration_id WHERE reg.status='1' and type_of_monitoring != 'Mother and Newborn at MNCU' and md.admission_weight > 0 and md.growth_status_intergrowth=0  $filter ORDER BY reg.id DESC LIMIT $limit OFFSET $childOffset");

											while ($datafacilitator = mysqli_fetch_object($sqlfacilitator)) {
												$sncuname1 = ($datafacilitator->sncu_id == 1) ? 'SNCU Gaya' : 'SNCU Purnea';
												$mon_type = '';
												$ga = '';

												if ($datafacilitator->type_of_monitoring == 'Date of Admission') {
													$mon_type = 'Admission';
													$ga = $datafacilitator->gestational_age_LBW * 7;
												} elseif ($datafacilitator->type_of_monitoring == 'Discharge Day') {
													$mon_type = 'Discharge';
													$ga = ($datafacilitator->gestational_age_LBW * 7) + $datafacilitator->age_in_days;
												}
											?>
  											<tr>
  												<td><?= $datafacilitator->mon_id ?></td>
  												<td><?= $datafacilitator->unique_id_of_body ?></td>
  												<td><?= $sncuname1 ?></td>
  												<td><?= $datafacilitator->boby_of_mothers_name ?></td>
  												<td><?= $mon_type ?></td>
  												<td><?= $datafacilitator->sex ?></td>
  												<td><?= $ga ?></td>
  												<td><?= $datafacilitator->admission_weight ?></td>
  												<td><?= $datafacilitator->admission_length ?></td>
  												<td><?= $datafacilitator->admission_head_circumference ?></td>
  											</tr>
  										<?php } ?>
  									</tbody>
  								</table>
  							</div>

  							<?php echo pagination1($child_page, $totalchildPages, 'child_page', $extraParams); ?>
  						</div>
  					</div>
  				</div>
  			</div>

  		</div>
  		<!-- container-fluid -->
  	</div>
  	<!-- End Page-content -->

  	<!-- Open Edit Modal -->
  	<div class="modal fade" id="uploaddata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  		<div class="modal-dialog  modal-mg">
  			<div class="modal-content">
  				<div class="modal-header">
  					<h5 class="modal-title" id="staticBackdropLabel">Upload Data</h5>
  					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  				</div>
  				<form method="post" enctype="multipart/form-data">
  					<div class="modal-body">
  						<div>
  							<input type="file" name="csv_file" id="csv_file" accept=".csv" required><br>
  							<label class="d-none" id="al_msg" style="color: red;">Please select a CSV file.</label>
  						</div>
  					</div>
  					<div class="modal-footer">
  						<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
  						<!-- <button type="submit" class="btn btn-success" name="" onclick="return file_validate()">Update</button> -->
  						<button type="submit" class="btn btn-success" name="upld_csv" onclick="return file_validate()">Update</button>
  					</div>
  				</form>
  			</div>
  		</div>
  	</div>
  	<!-- End Edit Modal -->

  	<?php include('includes/footers.php'); ?>
  	<!-- prismjs plugin -->
  	<script src="assets/libs/prismjs/prism.js"></script>
  	<script src="assets/libs/list.js/list.min.js"></script>
  	<script src="assets/libs/list.pagination.js/list.pagination.min.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  	<!-- listjs init -->
  	<script src="assets/js/pages/listjs.init.js"></script>
  	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  	<!-- Sweet Alerts js -->
  	<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

  	<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />


  	<!-- Search Button -->
  	<script>
  		const inputs = document.querySelectorAll('#type');
  		const searchBtn = document.getElementById('searchBtn');

  		function toggleSearchButton() {
  			let enable = false;
  			inputs.forEach(input => {
  				if (input.value.trim() !== '') {
  					enable = true;
  				}
  			});
  			searchBtn.disabled = !enable;
  		}

  		inputs.forEach(input => {
  			input.addEventListener('input', toggleSearchButton);
  		});

  		toggleSearchButton();
  	</script>

  	<!-- Reset Button -->
  	<script>
  		function resetSearch() {
  			window.location.href = window.location.pathname; // removes all GET parameters
  		}
  	</script>

  	<!-- Validate file -->
  	<script>
  		function file_validate() {
  			const fileInput = document.getElementById('csv_file');
  			const filePath = fileInput.value;
  			const msgElement = document.getElementById('al_msg');

  			if (!filePath) {
  				msgElement.classList.remove('d-none');

  				setTimeout(() => {
  					msgElement.classList.add('d-none');
  				}, 3000);

  				return false;
  			}

  			const allowedExtension = /(\.csv)$/i;
  			if (!allowedExtension.exec(filePath)) {
  				msgElement.classList.remove('d-none');

  				setTimeout(() => {
  					msgElement.classList.add('d-none');
  				}, 3000);

  				fileInput.value = '';
  				return false;
  			}

  			return true;
  		}
  	</script>


  	<!-- data export code
  	<script>
  		let button = document.querySelector("#growth_export");
  		button.addEventListener("click", (e) => {
  			let table = document.querySelector("#intergrowth_minitor");
  			TableToExcel.convert(table, {
  				name: "InterGrowth Data.xls",
  				sheet: {
  					name: "InterGrowth Monitor"
  				}
  			});
  		});
  	</script> -->