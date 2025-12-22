<?php
define('hostname', 'localhost'); //'65.1.180.162'
define('username', 'unicef_db');
define('password', 'unicef_dblean@!pA');
define('database', 'unicef_db');

$conn = mysqli_connect(hostname, username, password, database) or die(mysqli_error());
mysqli_set_charset($conn, "utf8");
if (!$conn) {
	echo "Connection failed.......!";
} ?>

<?php
if (isset($_POST['bulk_report'])) {
	echo "<script>alert('Sorry the work is not completed yet!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>MAA Programme Reporting</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" rel="stylesheet">

	<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


	<style type="text/css">
		table {
			width: 100%;
		}

		body,
		div,
		table,
		thead,
		tbody,
		tfoot,
		tr,
		th,
		td,
		p {
			font-family: "Calibri";
			font-size: 16px;
		}

		a.comment-indicator:hover+comment {
			background: #ffd;
			position: absolute;
			display: block;
			border: 1px solid black;
			padding: 0.5em;
		}

		a.comment-indicator {
			background: red;
			display: inline-block;
			border: 1px solid black;
			width: 0.5em;
			height: 0.5em;
		}

		comment {
			display: none;
		}

		.form-control {
			display: block;
			width: 100%;
			padding: .375rem .75rem;
			font-size: 1rem;
			font-weight: 400;
			line-height: 1.5;
			color: var(--bs-body-color);
			background-color: #f7f0f0 !important;
			background-clip: padding-box;
			border: var(--bs-border-width) solid #707376 !important;
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
			border-radius: var(--bs-border-radius);
		}
	</style>
</head>

<body>
	<!-- Header Section -->
	<header class="bg-primary text-white text-center py-3">
		<img src="bnmch.PNG" alt="Logo" style="max-height: 60px;"> <span style="font-size:25px; font-weight:700; width:200px;">State Resource Center, NMCH</span>
	</header>

	<div class="container mt-3">
		<form id="iycf-form" method="post">
			<table cellspacing="0" border="0" cellpadding="5">
				<colgroup width="20%"></colgroup>
				<colgroup width="30%"></colgroup>
				<colgroup width="20%"></colgroup>
				<colgroup width="30%"></colgroup>
				<tr>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=4 height="58" align="center" valign=middle bgcolor="#F2F2F2" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;Mothers' Absolute Affection (MAA) Programme Monthly Reporting &quot;}"><b>
							<font face="Noto Sans" size=5 color="#000000">Mothers' Absolute Affection (MAA) Programme Monthly Reporting</font>
						</b></td>
				</tr>
				<tr>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="42" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;माह:&quot;}">
						<font face="Noto Sans" color="#000000">District Name</font>
					</td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}"><select class="form-select" id="district" name="district" required="">
							<option value="">-- Select District --</option>
							<?php $sqlDistrict = mysqli_query($conn, "select * from districts where state_code='10'");
							while ($dataDistrict = mysqli_fetch_object($sqlDistrict)) {								 ?>
								<option value="<?= $dataDistrict->district_code ?>" <?php if ($dataDistrict->district_code == $_REQUEST['district']) {
																						echo "Selected";
																					} ?>><?= $dataDistrict->district_name ?></option>
							<?php } ?>
						</select></td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;IYCF परामर्श केन्द्र का नाम:&quot;}">
						<font face="Noto Sans" color="#000000">Block Name:</font>
					</td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}">
						<select class="form-select" id="block" name="block">
							<option value="">-- Select Block--</option>
							<?php $sqlIYCF = mysqli_query($conn, "select * from blocks where district_code='" . $_REQUEST['district'] . "'");
							while ($dataIYCF = mysqli_fetch_object($sqlIYCF)) {								 ?>
								<option value="<?= $dataIYCF->block_code ?>" <?php if ($dataIYCF->block_code == $_REQUEST['block']) {
																					echo "Selected";
																				} ?>><?= $dataIYCF->block_name ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>

				<tr>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="42" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;माह:&quot;}">
						<font face="Noto Sans" color="#000000">Reporting Month: </font>
					</td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}">
						<select class="form-select" id="reporting_period" name="reporting_period" required="">
							<option value="">-- Select Month --</option>
							<?php
							$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
							$monthsint = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
							for ($i = 2024; $i <= date('Y'); $i++) {
								for ($j = 0; $j < 12; $j++) {
									$dds = $i . '-' . $monthsint[$j] . '-01';
							?>
									<option value="<?= $dds ?>" <?php if ($dds == $_REQUEST['reporting_period']) {
																	echo "selected";
																} ?>><?= $months[$j] ?>-<?= $i ?></option>
							<?php
								}
							} ?>

						</select>
					</td>
					<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle bgcolor="#E7E6E6" colspan="2"> <button type="submit" class="btn btn-primary" name="search" value="search">Search</button>
						<button type="reset" class="btn btn-secondary">Reset</button>
					</td>

				</tr>
			</table>
		</form>
		<?php if (isset($_REQUEST['message']) && $_REQUEST['message'] == 'success') { ?>
			<br />
			<div class="alert alert-dismissible alert-success">
				<strong>Success!</strong> Report added sucessfuly.
				<button type="button" class="btn-close"
					data-bs-dismiss="alert">
				</button>
			</div>

		<?php } ?>
		<?php
		$qry = '';
		$tableTitle = '';
		if (isset($_REQUEST['district']) && $_REQUEST['district'] != '') {

			if ($_REQUEST['block'] != '') {
				$qry .= " and block='" . $_REQUEST['block'] . "'";
			}
			if ($_REQUEST['reporting_period'] != '') {
				$qry .= " and reporting_period='" . $_REQUEST['reporting_period'] . "'";
			}
			// echo "select *  from maa_monthly_report where district='" . $_REQUEST['district'] . "' $qry";
			$sqlReporting = mysqli_query($conn, "select *  from maa_monthly_report where district='" . $_REQUEST['district'] . "' $qry");
			$tableTitle = "Total Records";
		} else {
			$sqlReporting = mysqli_query($conn, "select * from maa_monthly_report order by creation_date desc");
			$tableTitle = "Recently Added";
		} ?>
		<div class="row mt-3">

			<div class="col-md-6 col-sm-6 col-lg-6">
				<h4><span style="font-weight:600" class="text-bold"><?= $tableTitle ?></span>: <?= mysqli_num_rows($sqlReporting) ?> Report</h4>
			</div>
			<div class="col-md-6 col-sm-6 col-lg-6 text-right" style="float:right; text-align:right">
				<a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulk">Add Bulk Report</a>
				<a class="btn btn-success " href="add-maa-reporting.php?district=<?= $_REQUEST['district'] ?>&block=<?= $_REQUEST['block'] ?>&reporting_period=<?= $_REQUEST['reporting_period'] ?>"> Add New Report</a>
			</div>

		</div>
		<hr>
		</hr>
		<div class="row">
			<div class="col-lg-12">
				<table id="example" class="display" style="width:100%">
					<thead>
						<tr>
							<th>District Name</th>
							<th>Block Name</th>
							<th>Reporting Month</th>
							<th style="text-align: left;">Total ASHA</th>
							<th>Reported On</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($dataReporting = mysqli_fetch_object($sqlReporting)) { ?>
							<tr>
								<td><?= $dataReporting->district_name ?></td>
								<td><?= $dataReporting->block_name ?></td>
								<td><?= date('M-Y', strtotime($dataReporting->reporting_period)) ?></td>
								<td style="text-align: left;"><?= $dataReporting->total_nos_asha ?></td>
								<td><?= date('d-m-Y H:i:s', strtotime($dataReporting->creation_date)) ?></td>
								<td><a class="btn btn-primary " href="view-maa-reporting.php?rid=<?= $dataReporting->id ?>" target="_blank"> View Report</a></td>
							</tr>
						<?php } ?>

					</tbody>

				</table>
			</div>
		</div>


		<div class="modal fade" id="bulk" tabindex="-1" aria-labelledby="uploadModalbulkLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="uploadModalbulkLabel">Upload Bulk Report (CSV)</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form method="post" enctype="multipart/form-data">
							<input type="hidden" name="district" value="<?= $_REQUEST['district'] ?>">
							<input type="hidden" name="block" value="<?= $_REQUEST['block'] ?>">
							<input type="hidden" name="reporting_period" value="<?= $_REQUEST['reporting_period'] ?>">

							<div class="mb-3">
								<label for="csvFile" class="form-label">Select CSV File:</label>
								<input type="file" class="form-control" name="csvFile" id="csvFile" accept=".csv" required>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<a href="sample.csv" download class="text-primary text-decoration-none fw-bold">
									Download Sample <i class="bi bi-download"></i>
								</a>
								<div class="text-end">
									<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
									<button type="submit" name="bulk_report" class="btn btn-success">Upload</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
		<script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
		<script>
			$(document).ready(function() {
				// AJAX call to update the IYCF centers based on selected district
				$('#district').on('change', function() {
					let districtId = $(this).val();

					if (districtId) {
						$.ajax({
							url: 'get_blocks.php',
							type: 'POST',
							data: {
								district_id: districtId
							},
							success: function(data) {
								$('#block').html(data);
							},
							error: function() {
								alert('Error fetching Blocks.');
							}
						});
					} else {
						$('#block').html('<option value="">-- Select Block --</option>');
					}
				});
			});
		</script>

		<!-- ************************************************************************** -->
		<script>
			new DataTable('#example');
		</script>
</body>

</html>