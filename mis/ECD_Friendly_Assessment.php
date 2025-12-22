<?php include_once('includes/config.php'); ?>
<?php define("title", "ECD Friendly Assessment | UNICEF"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php


$month = date('m'); // October
$year = date('Y');
$mindate = '2024-01';
$maxdate = $year . '-' . $month;
if (isset($_REQUEST['fromDate'])) {
	$fromDate = new DateTime($_REQUEST['fromDate']);
	$toDate = new DateTime($_REQUEST['toDate']);

	$MonthCount1 = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m')) + 1;
	$MonthCount = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m')) + 1;
	$m = $MonthCount1;
	$months = [];
	$fullfilterText = [];
	$fullfilter = [];
	for ($i = 0; $i <= $MonthCount; $i++) {
		$months[] = $fromDate->format('m');
		$fullfilterText[] = $fromDate->format('F') . '-' . $fromDate->format('Y');
		$fullfilter[] = $fromDate->format('Y') . '-' . $fromDate->format('m');
		$fromDate->modify('+1 month');
	}
	$first = $fullfilterText[0];
	$last = $fullfilterText[count($fullfilterText) - 2];
	$monthsName = "'" . implode("', '", $months) . "'";
} else {
	//echo "chala";
	$sstdate = date('Y-m-01');
	$eetdate = date('Y-m-d');
	$fromDate = new DateTime($sstdate);
	$toDate = new DateTime($eetdate);

	$MonthCount1 = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m')) + 1;
	$MonthCount = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m')) + 1;
	$m = $MonthCount1;
	$months = [];
	$fullfilterText = [];
	$fullfilter = [];
	for ($i = 0; $i <= $MonthCount; $i++) {
		$months[] = $fromDate->format('m');
		$fullfilterText[] = $fromDate->format('F') . '-' . $fromDate->format('Y');
		$fullfilter[] = $fromDate->format('Y') . '-' . $fromDate->format('m');
		$fromDate->modify('+1 month');
	}
	$first = $fullfilterText[0];
	$last = $fullfilterText[count($fullfilterText) - 2];
	$monthsName = "'" . implode("', '", $months) . "'";
}

$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$districtText = '';
if (isset($_REQUEST['district_code']) && !empty($_REQUEST['district_code'])) {
	$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
	if (!empty($districtType)) {
		$districtText = implode(", ", $districtType);
	}
}

$qry = "";
if (isset($_REQUEST['search'])) {
	if ($qryfield1 == '') {
		if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
			$startDate = new DateTime($_REQUEST['fromDate']);
			$endDate = new DateTime($_REQUEST['toDate']);
			//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
			$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
		}
	} else {

		$qry .= " and date(dom) like'" . $qryfield1 . "%'";
	}

	if (isset($_REQUEST['district_code']) && !empty($_REQUEST['district_code'])) {
		$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
		if (!empty($districtType)) {
			$districtTypeList = "'" . implode("','", $districtType) . "'"; // Convert to integer to prevent SQL injection
			$qry .= " AND district IN ($districtTypeList)";
		}
	}
	if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {

		$userTypes = array_filter($_REQUEST['uType']); // Remove empty values
		//print_r($userTypes);
		if (!empty($userTypes)) {
			$userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
			$qry .= " AND monitor_name IN ($userTypeList)";
		}
	}
} else {
	$startDate = new DateTime(date('2024-01-01'));
	$endDate = new DateTime(date('Y-m-d'));
	//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
	$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
}




function getcountVHSD($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{
	$qry = "";


	if (isset($_REQUEST['search'])) {
		if ($qryfield1 == '') {
			if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$startDate = new DateTime($_REQUEST['fromDate']);
				$endDate = new DateTime($_REQUEST['toDate']);
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		} else {

			$qry .= " and date(dom) like'" . $qryfield1 . "%'";
		}

		if (isset($_REQUEST['district_code']) && !empty($_REQUEST['district_code'])) {
			$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
			if (!empty($districtType)) {
				$districtTypeList = "'" . implode("','", $districtType) . "'"; // Convert to integer to prevent SQL injection
				$qry .= " AND district IN ($districtTypeList)";
			}
		}
		if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {

			$userTypes = array_filter($_REQUEST['uType']); // Remove empty values
			//print_r($userTypes);
			if (!empty($userTypes)) {
				$userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
				$qry .= " AND type_mon IN ($userTypeList)";
			}
		}
	} else {
		$startDate = new DateTime(date('Y-m-01'));
		$endDate = new DateTime(date('Y-m-d'));
		//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
		$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
	}

	$qryv = '';
	/*if ($qryfield1 != '') {
        $qryv = " AND $qryfield1='$value1'";
    }*/

	$query = "SELECT COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' $qry";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_object($result);

	return $row->total;
}

function getcountDistrictWise($conn, $tablename, $districtName, $center_type, $field, $qryfield, $operator, $value, $qryfield1 = '', $value1 = '')
{

	$qry = "";
	if (isset($_REQUEST['search'])) {
		if ($qryfield1 == '') {
			if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$startDate = new DateTime($_REQUEST['fromDate']);
				$endDate = new DateTime($_REQUEST['toDate']);
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		} else {

			$qry .= " and date(dom) like'" . $qryfield1 . "%'";
		}

		if (isset($_REQUEST['district_code']) && !empty($_REQUEST['district_code'])) {
			$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
			if (!empty($districtType)) {
				$districtTypeList = "'" . implode("','", $districtType) . "'"; // Convert to integer to prevent SQL injection
				$qry .= " AND district IN ($districtTypeList)";
			}
		}
		if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {

			$userTypes = array_filter($_REQUEST['uType']); // Remove empty values
			//print_r($userTypes);
			if (!empty($userTypes)) {
				$userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
				$qry .= " AND monitor_name IN ($userTypeList)";
			}
		}
	} else {
		$startDate = new DateTime(date('2024-01-01'));
		$endDate = new DateTime(date('Y-m-d'));
		//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
		$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
	}

	$qryv = '';
	if ($qryfield1 != '') {
		$qryv = " AND $qryfield1='$value1'";
	}
	$query = "SELECT COUNT($field) as total FROM $tablename WHERE $qryfield $operator='$value' and district='$districtName' and center_type='$center_type' $qryv $qry";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_object($result);

	return $row->total;
}

function getPercentage($numerator, $demonminator)
{
	$perTe = round((($numerator * 100) / $demonminator), 0);

	return $perTe;
}




///////////////////////////
/*
$month = date('m'); // October
$year = date('Y');
$mindate = '2024-01';
$maxdate = $year . '-' . $month;
$m = 0;
$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

function getcountVHSD($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{

	$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	$qry = "";
	if (isset($_REQUEST['search'])) {
		if (isset($_REQUEST['reporting_period']) && $_REQUEST['reporting_period'] != '') {
			$month = date('m', strtotime($_REQUEST['reporting_period'])); // October
			$year = date('Y', strtotime($_REQUEST['reporting_period']));
			$mindate = '2024-01';
			$maxdate = $year . '-' . $month;
			$qry .= " AND dom like'" . $_REQUEST['reporting_period'] . "%'";
		}
		if (isset($_REQUEST['district_code']) && $_REQUEST['district_code'] != '') {
			$qry .= " AND district='" . $_REQUEST['district_code'] . "'";
		}
	} else {
		$qry = " and dom like'" . $maxdate . "%'";
	}

	$qryv = '';
	if ($qryfield1 != '') {
		$qryv = " AND $qryfield1='$value1'";
	}
	$query = "SELECT  COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' $qryv $qry";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_object($result);

	return $row->total;
}

function sumGetcountVHSD($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{

	$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	$qry = "";
	if (isset($_REQUEST['search'])) {
		if (isset($_REQUEST['reporting_period']) && $_REQUEST['reporting_period'] != '') {
			$month = date('m', strtotime($_REQUEST['reporting_period'])); // October
			$year = date('Y', strtotime($_REQUEST['reporting_period']));
			$mindate = '2024-01';
			$maxdate = $year . '-' . $month;
			$qry .= " AND dom like'" . $_REQUEST['reporting_period'] . "%'";
		}
		if (isset($_REQUEST['district_code']) && $_REQUEST['district_code'] != '') {
			$qry .= " AND district='" . $_REQUEST['district_code'] . "'";
		}
	} else {
		$qry = " and dom like'" . $maxdate . "%'";
	}

	$qryv = '';
	if ($qryfield1 != '') {
		$qryv = " AND $qryfield1='$value1'";
	}
	$query = "SELECT  COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' $qryv $qry";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_object($result);

	return $row->total;
}

$month = date('m'); // October
$year = date('Y');
$mindate = '2024-01';
$maxdate = $year . '-' . $month;

if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['reporting_period']) && $_REQUEST['reporting_period'] != '') {
		$month = date('m', strtotime($_REQUEST['reporting_period'])); // October
		$year = date('Y', strtotime($_REQUEST['reporting_period']));
		$mindate = '2024-01';
		$maxdate = $year . '-' . $month;
		$qry .= " AND start_date_time like'" . $_REQUEST['reporting_period'] . "%'";
	}
	if (isset($_REQUEST['district_code']) && $_REQUEST['district_code'] != '') {
		$qry .= " AND district_id='" . $_REQUEST['district_code'] . "'";
	}
} else {
	$qry = " and date(dom) like'" . $maxdate . "%'";
}
*/
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<title>ECD Friendly Assessment</title>
<style type="text/css">
	body {
		font-family: "Calibri";
		/* 	font-size: x-small;
		margin: 0;
		padding: 0;
		width: 100%;
		overflow-x: hidden; */

	}

	table {
		/* width: 100%;
		border-collapse: collapse;
		table-layout: fixed; */
	}

	a.comment-indicator:hover+comment {
		background: #ffd;
		position: absolute;
		display: block;
		border: 1px solid black;
		padding: 0.5em;
	}

	th,
	td {
		border: 1px solid black;
		padding: 8px;
		text-align: center;
		overflow: hidden;
		/* Ensures content doesn't overflow cells */
	}

	tr:hover {
		background-color: #ddd;
	}

	.header1 {
		border-left: 1px solid #000000;
		font-size: 20px;
		background-color: #00B0F0;
		font-weight: 900;
		text-align: center;
		padding: 4px;
	}

	.td_head {
		border: 1px solid #000000;
		text-align: center;
		vertical-align: middle;

	}

	.td_head_main {
		border: 1px solid #000000;
		text-align: center;
		vertical-align: middle;
		background-color: #FFFFFF;
		font-weight: 700;
		font-size: 11px
	}

	.thead {
		background-color: #C5D9F1;
		font-weight: 700;
	}

	/* Flexbox for button alignment */
	#export-container {
		display: flex;
		justify-content: flex-left;
		margin-bottom: 10px;
	}
	.left { text-align:left; }
	.center { text-align:center; }
</style>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-10">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Report</li>
						<li class="breadcrumb-item active"><i class="fa fa-calandar"></i>ECD Friendly Assessment</li>
					</ol>

				</nav>
			</div>
			<div class="col-lg-2">
			<button class="btn btn-success width-md waves-effect waves-light form-control" id="button-excel"><i class="fas fa-file-excel"></i> Export to excel</button>
			</div>
		</div>
		<div class="container-fluid1">
			<!--<form method="GET">
				<div class="row filter_css clearfix g-1">
				    
					<div class="col-lg-2 col-md-4 col-sm-12">
						<div class="form-group">
							<b>Select District</b>

							<select class="form-select select2" id="district_ids" name="district_code[]" multiple>
								<option value="" <?= (empty($_REQUEST['district_code']) || in_array("", $_REQUEST['district_code'])) ? 'selected' : '' ?>> All</option>
								<?php
								$allDistricts = '';
								$selected = '';
								$kl = 1;
								$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from awmmsfdaueupsgpjtspyeu");
								while ($dataDistrictType = mysqli_fetch_object($qryDistrictType)) {
									if ($kl > 1) {
										$allDistricts .= ', ';
									}
									$allDistricts .= ucfirst($dataDistrictType->district);

									if (isset($_REQUEST['district_code'])) {
										$selected = (!empty($_REQUEST['district_code']) && in_array($dataDistrictType->district, $_REQUEST['district_code'])) ? 'selected' : '';
									} else {
										$selected = '';
									}

								?>
									<option value="<?= $dataDistrictType->district ?>" <?= $selected ?>> <?= ucfirst($dataDistrictType->district) ?> </option>
								<?php $kl++;
								} ?>
							</select>

						</div>
					</div>
					
					<div class="col-lg-2 col-md-3 col-sm-12">
						<div class="form-group">
							<b>From Date</b>
							<input class="form-control" type="date" id="fromDate" name="fromDate" value="<?= isset($_REQUEST['fromDate']) ? $_REQUEST['fromDate'] : date('Y-m-01') ?>" required style="border-radius: 4px; padding-bottom: 7px; padding-right: 5px; padding-top: 9px;" />
						</div>
					</div>

					<div class="col-lg-2 col-md-3 col-sm-12">
						<div class="form-group">
							<b>To Date</b>
							<input class="form-control" type="date" id="toDate" name="toDate" value="<?= isset($_REQUEST['toDate']) ? $_REQUEST['toDate'] : date('Y-m-d') ?>" required style="border-radius: 4px; padding-bottom: 7px; padding-right: 5px; padding-top: 9px;" />
						</div>
					</div>
					<div class="col-lg-2 col-md-3 col-sm-12">
						<div class="form-group">
							<b>Users Type</b>
							<select class="form-select select2" id="uType" name="uType[]" multiple>
								<option value="" <?= (empty($_REQUEST['uType']) || in_array("", $_REQUEST['uType'])) ? 'selected' : '' ?>> All</option>
								<?php
								$selected = '';
								$qryUserType = mysqli_query($conn, "SELECT DISTINCT monitor_name as type_mon FROM awmmsfdaueupsgpjtspyeu ORDER BY monitor_name ASC");
								while ($dataUserType = mysqli_fetch_object($qryUserType)) {
									if (isset($_REQUEST['uType'])) {
										$selected = (!empty($_REQUEST['uType']) && in_array($dataUserType->type_mon, $_REQUEST['uType'])) ? 'selected' : '';
									} else {
										$selected = '';
									}

								?>
									<option value="<?= $dataUserType->type_mon ?>" <?= $selected ?>> <?= $dataUserType->type_mon ?> </option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-lg-1 col-md-4 mt-4">
						<div class="form-group ">
							<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search">Search</button>
						</div>
					</div>
					<div class="col-lg-1 col-md-4 mt-4">
						<div class="form-group ">
							<a href="VHSND_analysis_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
						</div>
					</div> 

					<div class="col-lg-2 col-md-4 mt-4">
						<div class="form-group ">
							<button class="btn btn-success width-md waves-effect waves-light form-control" id="button-excel"><i class="fas fa-file-excel"></i> Export to excel</button>
						</div>
					</div>
				</div>
			</form> -->
			<?php
			$tablename = 'awmmsfdaueupsgpjtspyeu';
			$districtName = 'araria';
			$districtName1 = 'purnea';
			$totalSampleinfo = array();
			$totalSampleinfo1 = array();
			$sqlQrySample = mysqli_query($conn, "select district,center_type,count(*) as total from awmmsfdaueupsgpjtspyeu where center_type in('Intervention','Non Intervention') $qry group by district,center_type order by district,center_type asc");
			while ($dataQrySample = mysqli_fetch_object($sqlQrySample)) {
				$totalSampleinfo[$dataQrySample->district][$dataQrySample->center_type] = $dataQrySample->total;
			}


			$sqlQrySample1 = mysqli_query($conn, "select district,center_type,count(*) as total from awmmsfdaueupsgpjtspyeu where ecd_friendly='Yes' and center_type in('Intervention','Non Intervention') $qry group by district,center_type order by district,center_type asc ");
			while ($dataQrySample1 = mysqli_fetch_object($sqlQrySample1)) {
				$totalSampleinfo1[$dataQrySample1->district][$dataQrySample1->center_type] = $dataQrySample1->total;
			}
			?>

			<section class="panel p-1">
				<div class="table-responsive">


					<table id="simpleTable1" data-cols-width="5,30,10,10,10,5,10,10" class="report" cellpadding="0" cellspacing="0" role="table" aria-label="SNCU monitoring table">
						<!-- Header row 1: big groups -->
						<tr>
							<td colspan="11" class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-f-sz="20" data-a-h="center" bgcolor="#FFF2CC">
								<h3>ECD Friendly Indicators Report for Year 2025 <?php /* if ($first == $last) { ?> <?= date('F-Y', strtotime($last)) ?> <?php } else { ?> <?= date('F Y', strtotime($first)) ?> - <?= date('F Y', strtotime($last)) ?> <?php } ?> for (<?php if ($districtText != '') {
																																																															echo ucwords($districtText);
																																																														} else {
																																																															echo $allDistricts;
																																																														}  */?></h3>
							</td>
						</tr>
						<tr>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" rowspan="2" data-f-sz="11" data-a-h="center" bgcolor="#FFFFFF" style="width:36px;">
								#
							</td>

							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-f-sz="11" rowspan="2" data-a-h="left" bgcolor="#FFFFFF" style="text-align:left;">
								<b>Description</b>
							</td>

							<!-- ARARIA header group -->
							<td class="td_head" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-b-a-s="thin" data-b-a-c="000000" colspan="3" data-a-h="center" bgcolor="#FFC000">
								<b>Araria</b>
							</td>

							<!-- PURNEA header group -->
							<td class="td_head" data-fill-color="9fd3f0" data-f-bold="true" colspan="3" data-f-sz="11" data-a-h="center" bgcolor="#92C7E0">
								<b>Purnea</b>
							</td>

							<!-- TOTAL header group -->
							<td class="td_head" data-fill-color="9edd7a" data-f-bold="true" data-f-sz="11" data-b-a-s="thin" data-b-a-c="000000" colspan="3" data-f-bold="true" data-f-sz="11" data-a-h="center" bgcolor="#8cc63f">
								<b>Total</b>
							</td>
						</tr>

						<!-- Header row 2: sub-columns for each group (5 each) -->
						<tr>
							<!-- Araria subcolumns -->
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="FFFF00" bgcolor="#FFF2CC" >Intervention</td>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="FFFF00" bgcolor="#FFF2CC">Non Intervention</td>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="FFFF00" bgcolor="#FFF2CC">Total (%)</td>

							<!-- Purnea subcolumns -->
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="9fd3f0" bgcolor="#D6EEF9">Intervention</td>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="9fd3f0" bgcolor="#D6EEF9">Non Intervention</td>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="9fd3f0" bgcolor="#D6EEF9">Total (%)</td>

							<!-- Total subcolumns -->
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="9edd7a" bgcolor="#DFF0D8">Intervention</td>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="9edd7a" bgcolor="#DFF0D8">Non Intervention</td>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="9edd7a" bgcolor="#DFF0D8">Total (%)</td>
						</tr>

						<!-- Example data rows -->
						<tr>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">1</td>
							<td class="left" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" style="text-align:left;">Sample</td>

							<!-- Araria values -->
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $arSumInt_sample = $totalSampleinfo[$districtName]['Intervention'] ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $arSumNon_sample = $totalSampleinfo[$districtName]['Non Intervention'] ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $arSumall_sample = $arSumInt_sample + $arSumNon_sample ?></td>

							<!-- Purnea values -->
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $prSumInt_sample = $totalSampleinfo[$districtName1]['Intervention'] ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $prSumNon_sample = $totalSampleinfo[$districtName1]['Non Intervention'] ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $prSumall_sample = $prSumInt_sample + $prSumNon_sample ?></td>

							<!-- Total values -->
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $sumallint = $arSumInt_sample + $prSumInt_sample ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $arSumNon_sample + $prSumNon_sample ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $artotalSample = $arSumInt_sample + $prSumInt_sample + $arSumNon_sample + $prSumNon_sample ?></td>
						</tr>

						<!-- Another example: indicator row -->
						<tr>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">2</td>
							<td class="left" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true">ECD Friendly AWC out of total assessed</td>

							<!-- Araria values -->
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $arSumInt_sample1 = $totalSampleinfo1[$districtName]['Intervention'] ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $arSumNon_sample1 = $totalSampleinfo1[$districtName]['Non Intervention'] ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= getPercentage(($arSumInt_sample1 + $arSumNon_sample1), $arSumall_sample); ?>%</td>

							<!-- Purnea values -->
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $prSumInt_sample1 = $totalSampleinfo1[$districtName1]['Intervention'] ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $prSumNon_sample1 = $totalSampleinfo1[$districtName1]['Non Intervention'] ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= getPercentage(($prSumInt_sample1 + $prSumNon_sample1), $prSumall_sample); ?>%</td>

							<!-- Total values -->
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $allSumInt_sample1 = $arSumInt_sample1 + $prSumInt_sample1 ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= $allSumNon_sample1 = $arSumNon_sample1 + $prSumNon_sample1 ?></td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= getPercentage(($allSumInt_sample1 + $allSumNon_sample1), $artotalSample); ?>%</td>
						</tr>

						<!-- Add more rows as needed -->

						<!-- Another example: indicator row -->
						<tr>
							<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">3</td>
							<td class="left" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true">ECD Friendly AWC out of total intervention centres assessed</td>

							<!-- Araria values -->
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">-</td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">-</td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= getPercentage($arSumInt_sample1, $arSumInt_sample); ?>%</td>

							<!-- Purnea values -->
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">-</td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">-</td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= getPercentage($prSumInt_sample1, $prSumInt_sample); ?>%</td>

							<!-- Total values -->
							<td class="center" data-a-h="center"  data-b-a-s="thin" data-b-a-c="000000">-</td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">-</td>
							<td class="center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000"><?= getPercentage($allSumInt_sample1, $sumallint); ?>%</td>
						</tr>
						<tr>
							<td colspan="11" class="td_head"> </td>
						</tr>
						<tr>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" rowspan="2" data-f-sz="11" data-a-h="center" bgcolor="#FFFFFF" style="width:36px;">#</td>

							<td class="td_head left" data-b-a-s="thin" data-b-a-c="000000" colspan="1" rowspan="2" data-f-bold="true" data-f-sz="11" data-a-h="left" bgcolor="#FFFFFF" style="text-align:left;">
								<b>Description</b>
							</td>

							<!-- ARARIA header group -->
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" colspan="5" data-f-bold="true" data-f-sz="11" data-a-h="center" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" bgcolor="#FFC000">
								<b>Araria</b>
							</td>

							<!-- PURNEA header group -->
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" colspan="5" data-f-bold="true" data-f-sz="11" data-a-h="center" data-fill-color="9fd3f0" data-f-bold="true" data-f-sz="11" bgcolor="#92C7E0">
								<b>Purnea</b>
							</td>

							<!-- PURNEA header group -->
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" colspan="5" data-f-bold="true" data-f-sz="11" data-a-h="center" data-fill-color="9edd7a" data-f-bold="true" data-f-sz="11" bgcolor="#8cc63f">
								<b>Total</b>
							</td>
						</tr>


						<!-- Sub-headers for each district -->
						<tr bgcolor="#a6c77c">



							<!-- Araria subcols -->
							<td class="td_head" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center" data-fill-color="bdbf6b">Intervention</td>
							<td class="td_head" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center" data-fill-color="bdbf6b">Non Intervention</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Intervention %</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Non Intervention %</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Total %</td>

							<!-- Purnea subcols -->
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Intervention</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Non Intervention</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Intervention %</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Non Intervention %</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Total %</td>

							<!-- Total subcols -->
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Intervention</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Non Intervention</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Intervention %</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Non Intervention %</td>
							<td class="td_head" data-fill-color="bdbf6b" data-b-a-s="thin" data-f-bold="true" data-b-a-c="000000" data-a-h="center">Total %</td>
						</tr>



						<?php
						$awc_marks_labels = [
							'marks_health_checkup_frequency'        => 'Monthly Health Checkup at AWC',
							'marks_vaccination'                     => 'Monthly Vaccination at AWC',
							'marks_medical_screening_rbsk'          => 'Medical Screening by RBSK in last 6 months',
							'marks_growth_monitoring'               => 'Growth monitoring in last month',
							'marks_hot_cooked_meal'                 => 'AWC serving Hot cooked meal',
							'marks_enrollment_percentage'           => '>=75% enrolled for ECCE',
							'marks_educational_materials'           => 'Extensive Educational materials available at AWC',
							'marks_ECCE_activity_engagement'        => 'Extensive and diverse ECCE activity and engagement observed',
							'marks_frequency_parent_teacher_meeting' => 'Parent teacher meeting in last 1 month',
							'marks_safe_environment'                => 'AWC is safe and secure',
							'marks_handwashing_facility'            => 'Functional Handwashing facility',
							'marks_inclusive_practices'             => 'Fully accessible to children with disability',
							'marks_community_outreach'              => 'Regular and structured community outreach programs in place',
							'marks_feedback_mechanism'              => 'Comprehensive Feedback mechanism with evidence of action taken available',
							'marks_nutritution_education'           => 'Parent aware of key Nutrition messages',
							'marks_caregiver_key_message'           => 'AWW always provides key messages on children\'s individual and emotional need and avoiding harsh language or physical punishment',
						];
						?>


						<!-- Row 1 -->
						<?php $ik = 1;
						foreach ($awc_marks_labels as $key => $label) { 
						if($key=="marks_handwashing_facility" || $key=="marks_hot_cooked_meal") { ?>
							<tr>
								<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center"><?= $ik ?></td>
								<td class="desc left" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="left" data-f-sz="11"><?= $label ?></td>

								<!-- Araria -->
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<b><?= $aind1int = getcountDistrictWise($conn, $tablename, $districtName, 'Intervention', ' id', $key, '>', '2') ?></b>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $aind1non = getcountDistrictWise($conn, $tablename, $districtName, 'Non Intervention', ' id', $key, '>', '2') ?>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_aind1int = getPercentage($aind1int, $totalSampleinfo[$districtName]['Intervention']) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_aind1non = getPercentage($aind1non, $totalSampleinfo[$districtName]['Non Intervention']) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $totalA = getPercentage(($aind1int + $aind1non), $arSumall_sample) ?>%
								</td>

								<!-- Purnea -->
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<b><?= $pind1int = getcountDistrictWise($conn, $tablename, $districtName1, 'Intervention', ' id', $key, '>', '2') ?></b>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $pind1non = getcountDistrictWise($conn, $tablename, $districtName1, 'Non Intervention', ' id', $key, '>', '2') ?>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_pind1int = getPercentage($pind1int, $totalSampleinfo[$districtName1]['Intervention']) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_pind1non = getPercentage($pind1non, $totalSampleinfo[$districtName1]['Non Intervention']) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $totalP = getPercentage(($pind1int + $pind1non), $prSumall_sample) ?>%
								</td>

								<!-- Total -->
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<b><?= $totalAllint = $aind1int + $pind1int ?></b>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $totalAllnon = $aind1non + $pind1non ?>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_all_int = getPercentage($totalAllint, ($totalSampleinfo[$districtName]['Intervention'] + $totalSampleinfo[$districtName1]['Intervention'])) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_all_non = getPercentage($totalAllnon, ($totalSampleinfo[$districtName]['Non Intervention'] + $totalSampleinfo[$districtName1]['Non Intervention'])) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $totalAll = getPercentage(($totalAllint + $totalAllnon), ($arSumall_sample + $prSumall_sample)) ?>%
								</td>

							</tr>
							
						<?php } else { ?>
                            <tr>
								<td class="td_head center" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center"><?= $ik ?></td>
								<td class="desc left" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="left" data-f-sz="11"><?= $label ?></td>

								<!-- Araria -->
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<b><?= $aind1int = getcountDistrictWise($conn, $tablename, $districtName, 'Intervention', ' id', $key, '>', '3') ?></b>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $aind1non = getcountDistrictWise($conn, $tablename, $districtName, 'Non Intervention', ' id', $key, '>', '3') ?>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_aind1int = getPercentage($aind1int, $totalSampleinfo[$districtName]['Intervention']) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_aind1non = getPercentage($aind1non, $totalSampleinfo[$districtName]['Non Intervention']) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $totalA = getPercentage(($aind1int + $aind1non), $arSumall_sample) ?>%
								</td>

								<!-- Purnea -->
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<b><?= $pind1int = getcountDistrictWise($conn, $tablename, $districtName1, 'Intervention', ' id', $key, '>', '3') ?></b>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $pind1non = getcountDistrictWise($conn, $tablename, $districtName1, 'Non Intervention', ' id', $key, '>', '3') ?>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_pind1int = getPercentage($pind1int, $totalSampleinfo[$districtName1]['Intervention']) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_pind1non = getPercentage($pind1non, $totalSampleinfo[$districtName1]['Non Intervention']) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $totalP = getPercentage(($pind1int + $pind1non), $prSumall_sample) ?>%
								</td>

								<!-- Total -->
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<b><?= $totalAllint = $aind1int + $pind1int ?></b>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $totalAllnon = $aind1non + $pind1non ?>
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_all_int = getPercentage($totalAllint, ($totalSampleinfo[$districtName]['Intervention'] + $totalSampleinfo[$districtName1]['Intervention'])) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $per_all_non = getPercentage($totalAllnon, ($totalSampleinfo[$districtName]['Non Intervention'] + $totalSampleinfo[$districtName1]['Non Intervention'])) ?>%
								</td>
								<td class="td_head center" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000">
									<?= $totalAll = getPercentage(($totalAllint + $totalAllnon), ($arSumall_sample + $prSumall_sample)) ?>%
								</td>

							</tr>
						<?php }	$ik++;
						} ?>

					</table>
				</div>
			</section>
		</div>
	</section>
</section>
<!-- ************************************************************************** -->
<?php include_once('includes/footer.php'); ?>

<SCRIPT>
	let button = document.querySelector("#button-excel");

	button.addEventListener("click", (e) => {
		let table = document.querySelector("#simpleTable1");
		TableToExcel.convert(table, {
			name: "ECD_Friendly_Assessment.xlsx", // Set your desired file name here
			sheet: {
				name: "ECD Friendly Assessment" // Set the sheet name here
			}
		});
	});
</SCRIPT>

</html>