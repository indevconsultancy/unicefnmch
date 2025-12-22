<?php
define('hostname', 'localhost'); //'65.1.180.162'
define('username', 'root');
define('password', 'indev@123');
define('database', 'unicef_db');

$conn = mysqli_connect(hostname, username, password, database) or die(mysqli_error());
mysqli_set_charset($conn, "utf8");
if (!$conn) {
	echo "Connection failed.......!";
}
function getone($conn, $tablename, $field, $qryfeild, $value)
{
	//echo  "select $field from $tablename where $qryfeild='".$value."'";
	$sn = mysqli_query($conn, "select $field from $tablename where $qryfeild='" . $value . "'") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn->$field);
}

?>

<?php if (isset($_REQUEST['rid']) && $_REQUEST['rid'] > 0) {
	$sqlResult = mysqli_query($conn, "select * from maa_monthly_report where id='" . $_REQUEST['rid'] . "'");
	$dataResult = mysqli_fetch_object($sqlResult);
	$reportTitle = 'MAA-Monthly-Report' . '-' . date('M-Y', strtotime($dataResult->reporting_period)) . '.xlsx';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>MAA Monthly Reporting</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

	<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
	<style type="text/css">
		td {
			border-top: 1px solid #000000;
			border-bottom: 1px solid #000000;
			border-left: 1px solid #000000;
			border-right: 1px solid #000000
		}

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
			font-size: 16px !important;
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
			background-color: #ffffff !important;
			background-clip: padding-box;
			border: var(--bs-border-width) solid #707376 !important;
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
			border-radius: var(--bs-border-radius);
	</style>
</head>

<body>
	<!-- Header Section -->
	<header class="bg-primary text-white text-center py-3">
		<img src="bnmch.PNG" alt="Logo" style="max-height: 60px;"> <span style="font-size:25px; font-weight:700; width:200px;">State Resource Center, NMCH</span>
	</header>

	<div class="container mt-3">

		<table cellspacing="0" border="0" cellpadding="5">
			<colgroup width="80"></colgroup>
			<colgroup width="154"></colgroup>
			<colgroup width="187"></colgroup>
			<colgroup width="454"></colgroup>
			<colgroup span="2" width="100"></colgroup>
			<colgroup width="112"></colgroup>
			<tr>
				<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=7 height="58" align="center" valign=middle bgcolor="#F2F2F2" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;आई. वाई. सी. एफ. मासिक प्रतिवेदन प्रपत्र ( जिला एवं IYCF Counselling केन्द्र हेतु )&quot;}"><b>
						<font face="Noto Sans" size=5 color="#000000">MAA Programme Monthly Reporting for District and Block</font>
					</b></td>
			</tr>
			<tr>
				<td style="" colspan=2 align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;जिला का नामः&quot;}">
					<font face="Noto Sans" color="#000000"><b>District Name</b></font>
				</td>
				<td style="" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}">
					<?= $dataResult->district_name ?>
				</td>

				<td style="" align="left" valign=middle bgcolor="#E7E6E6">
					<div style="vertical-align:middle">
						<div style="float:left; padding-top: 6px; width:25%">
							<font face="Noto Sans" color="#000000"><b>Block Name:</b></font>
						</div>
						<div style="float:left; width:75%; padding-top: 6px; ">
							<?= $dataResult->block_name ?>
						</div>
					</div>
				</td>
				<td style="" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}">
					<font face="Noto Sans" color="#000000"><b> Reporting Month:</b></font>
				</td>
				<td style="" colspan="2" height="42" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;माह:&quot;}">
					<?php
					echo date('M-Y', strtotime($dataResult->reporting_period)) ?>
				</td>
			</tr>
			<tr>
				<td style="" colspan=3 align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;जिला का नामः&quot;}">
					<font face="Noto Sans" color="#000000"><b>Name of MAA Program Nodal Officer</b></font>:
				</td>
				<td style="" colspan=1 align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;जिला का नामः&quot;}">
					<font color="#000000"><?= $dataResult->nodal_officer_name ?></font>
				</td>

				<td style="" align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;जिला का नामः&quot;}">
					<font face="Noto Sans" color="#000000"><b>Contact</b></font>:
				</td>
				<td style="" colspan=2 align="left" valign=middle bgcolor="#E7E6E6" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;जिला का नामः&quot;}">
					<font color="#000000"><?= $dataResult->nodal_officer_contact ?></font>
				</td>
			</tr>
			<tr>
				<td height="13" colspan=7 align="left" valign=bottom bgcolor="#4472C4" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;&quot;}">
					<font color="#000000"><br></font>
				</td>

			</tr>
			<tr>
				<td style="" colspan=1 height="40" align="left" valign=middle bgcolor="#F4B183" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;क्रमांक&quot;}"><b>
						<font face="Noto Sans" color="#000000">Sl No.</font>
					</b></td>
				<td style="" colspan=4 align="left" valign=middle bgcolor="#F4B183" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;लाभार्थी विवरणी&quot;}"><b>
						<font face="Noto Sans" color="#000000">Activity</font>
					</b></td>
				<td style="" colspan=2 align="center" valign=middle bgcolor="#F4B183" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;कुल संख्या &quot;}"><b>
						<font face="Noto Sans" color="#000000">Total</font>
					</b></td>
			</tr>
			<tr>
				<td colspan=1 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">1</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Total number of ASHAs </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->total_nos_asha ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">2</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Number of ASHAs oriented on IYCF during block meetings </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->ashas_oriented_iycf ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">3</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Number of ASHAs having infokit/flipchart </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->ashas_with_infokit ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">4</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Number of ASHAs conducted Mothers' meetings </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->ashas_conducted_meetings ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">5</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Number of ASHAs received incentive for meetings </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->ashas_received_incentive ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 rowspan=3 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">6</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Total number of Pregnant & Lactating mothers per month = (a+b) </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->total_mothers ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">a) Pregnant </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->pregnant_mothers ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">b) Lactating </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->lactating_mothers ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">7</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000"> Number of pregnant & lactating mothers participated in mother's meeting </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->mothers_participated_meeting ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">8</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Number of IYCF master trainers in the district </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->iycf_master_trainers ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">9</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Number of mothers meetings held in the month </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->mothers_meetings_held ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 rowspan=2 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">10</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Number of staffs trained in one day sensitization program in reporting month </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->staff_sensitized ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Number of delivery points for which one day sensitization covered in reporting month </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->delivery_points_sensitized ?></font>
				</td>
			</tr>
			<tr>
				<td colspan=1 height="39" align="center" valign=bottom sdval="1" sdnum="1033;">
					<font color="#000000">11</font>
				</td>
				<td colspan=4 align="left" valign=middle>
					<font face="Noto Sans" color="#000000">Number of facilities given MAA Award </font>
				</td>
				<td style="text-align:center" colspan=2 align="center" valign=bottom colspan="3">
					<font color="#000000"><?= $dataResult->facilities_given_maa_award ?></font>
				</td>
			</tr>
			<tr>
				<td style="" colspan=7 height="40" align="center" valign=middle bgcolor="#F4B183" data-sheets-value="{ &quot;1&quot;: 2, &quot;2&quot;: &quot;क्रमांक&quot;}"><b>
						<font face="Noto Sans" color="#000000">Training Details</font>
					</b></td>
			</tr>


		</table>
		<table cellspacing="0" border="0" cellpadding="5">
			<thead>
				<tr>
					<th height="40" align="left" valign=middle bgcolor="#F4B183">SI.No</th>
					<th height="40" align="left" valign=middle bgcolor="#F4B183">Category</th>
					<th height="40" align="left" valign=middle bgcolor="#F4B183">Medical Officer</th>
					<th height="40" align="left" valign=middle bgcolor="#F4B183">Staff Nurse</th>
					<th height="40" align="left" valign=middle bgcolor="#F4B183">ANM</th>
					<th height="40" align="left" valign=middle bgcolor="#F4B183">MAMTA</th>
					<th height="40" align="left" valign=middle bgcolor="#F4B183">RMNCH+A Counsellors</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td height="39">A</td>
					<td>Posted</td>
					<td><?= $dataResult->posted_medical_officer ?></td>
					<td><?= $dataResult->posted_staff_nurse ?></td>
					<td><?= $dataResult->posted_anm ?></td>
					<td><?= $dataResult->posted_mamta ?></td>
					<td><?= $dataResult->posted_rmncha_counsellors ?></td>
				</tr>
				<tr>
					<td height="39">B</td>
					<td>Total trained up to March <?= date('Y') ?></td>
					<td><?= $dataResult->trained_upto_march22_medical_officer ?></td>
					<td><?= $dataResult->trained_upto_march22_staff_nurse ?></td>
					<td><?= $dataResult->trained_upto_march22_anm ?></td>
					<td><?= $dataResult->trained_upto_march22_mamta ?></td>
					<td><?= $dataResult->trained_upto_march22_rmncha_counsellors ?></td>
				</tr>
				<tr>
					<td height="39">C</td>
					<td>Number of trained in the current month</td>
					<td><?= $dataResult->trained_current_month_medical_officer ?></td>
					<td><?= $dataResult->trained_current_month_staff_nurse ?></td>
					<td><?= $dataResult->trained_current_month_anm ?></td>
					<td><?= $dataResult->trained_current_month_mamta ?></td>
					<td><?= $dataResult->trained_current_month_rmncha_counsellors ?></td>
				</tr>
				<!--<tr bgcolor="#F4B183">
            <td height="39"><b>D</b></td>
            <td><b>Total Trained (B+C)</b></td>
            <td><input type="number"  name="total_trained_medical_officer" class="form-control total_count" ></td>
            <td><input type="number" name="total_trained_staff_nurse" class="form-control total_count" ></td>
            <td><input type="number"  name="total_trained_anm" class="form-control total_count" ></td>
            <td><input type="number" name="total_trained_mamta" class="form-control total_count" ></td>
            <td><input type="number"  name="total_trained_rmncha_counsellors" class="form-control total_count" ></td>
          </tr>-->
			</tbody>
		</table>

		</br>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script>
		document.addEventListener("input", function(e) {
			if (e.target.matches(".girls_count, .boys_count")) {
				const column = e.target.closest("td");
				const girlsCount = parseInt(column.querySelector(".girls_count").value) || 0;
				const boysCount = parseInt(column.querySelector(".boys_count").value) || 0;
				const totalCount = girlsCount + boysCount;
				coummn.querySelector(".total_count").value = totalCount;
			}
		});
	</script>

	<SCRIPT>
		let button = document.querySelector("#button-excel");

		button.addEventListener("click", (e) => {
			let table = document.querySelector("#simpleTable1");
			TableToExcel.convert(table, {
				name: "Beneficiary-Analysis.xlsx", // Set your desired file name here
				sheet: {
					name: "Beneficiary Analysis Report" // Set the sheet name here
				}
			});
		});
	</SCRIPT>

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
							alert('Error fetching Block.');
						}
					});
				} else {
					$('#block').html('<option value="">-- Select Block --</option>');
				}
			});
		});
	</script>

	<!-- ************************************************************************** -->
</body>

</html>