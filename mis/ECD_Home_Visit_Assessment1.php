<?php include_once('includes/config.php'); ?>
<?php define("title", "ECD Home Visit Assessment | UNICEF"); ?>
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



function getcountDistrictWise($conn, $tablename, $districtName, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
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

	$query = "SELECT COUNT($field) as total FROM $tablename WHERE $qryfield='$value' and district='$districtName' $qryv $qry";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_object($result);

	return $row->total;
}

function getPercentage($numerator, $demonminator)
{
	$perTe = round((($numerator * 100) / $demonminator), 0);

	return $perTe . '%';
}

function getAverage($conn, $tablename, $districtName, $field)
{

	$query = "SELECT AVG($field) as total FROM $tablename WHERE district='$districtName'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_object($result);

	return $row->total;
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
<title>VHSD Analysis</title>
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
</style>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Report</li>
						<li class="breadcrumb-item active"><i class="fa fa-calandar"></i>ECD Home Visit Assessment</li>
					</ol>

				</nav>
			</div>
		</div>
		<div class="container-fluid1">
			<form method="GET">
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
								$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from atsdzoq6fcieu7n7b3nmhc");
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
					<!-- <div class="col-lg-3 col-md-3 col-sm-12">
						<div class="form-group">
							<b> Select Month </b>
							<input class="form-control" type="month" id="start" name="reporting_period" min="<?= $mindate ?>" value="<?= $maxdate ?>" />
						</div>
					</div> -->
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
								$qryUserType = mysqli_query($conn, "SELECT DISTINCT monitor_name as type_mon FROM atsdzoq6fcieu7n7b3nmhc ORDER BY type_mon ASC");
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
							<a href="ECD_Home_Visit_Assessment.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
						</div>
					</div>

					<div class="col-lg-2 col-md-4 mt-4">
						<div class="form-group ">
							<button class="btn btn-success width-md waves-effect waves-light form-control" id="button-excel"><i class="fas fa-file-excel"></i> Export to excel</button>
						</div>
					</div>
				</div>
			</form>
			<?php
			$tablename = 'atsdzoq6fcieu7n7b3nmhc';
			$districtName = 'araria';
			$districtName1 = 'purnea';
			$totalSampleinfo = array();
			$totalSampleinfo1 = array();
			$sqlQrySample = mysqli_query($conn, "select district,center_type,count(*) as total from awmmsfdaueupsgpjtspyeu where center_type in('Intervention','Non Intervention') $qry group by district,center_type order by district,center_type asc");
			while ($dataQrySample = mysqli_fetch_object($sqlQrySample)) {
				$totalSampleinfo[$dataQrySample->district][$dataQrySample->center_type] = $dataQrySample->total;
			}

			$sqlQrySample1 = mysqli_query($conn, "select district,center_type,count(*) as total from awmmsfdaueupsgpjtspyeu where ecd_friendly='Yes' and center_type in('Intervention','Non Intervention') $qry group by district,center_type order by district,center_type asc");
			while ($dataQrySample1 = mysqli_fetch_object($sqlQrySample1)) {
				$totalSampleinfo1[$dataQrySample1->district][$dataQrySample1->center_type] = $dataQrySample1->total;
			}
			?>

			<section class="panel p-1">
				<div class="table-responsive">

					<table id="simpleTable1" data-cols-width="5,30,10,10,10,5,10,10" class="report" cellpadding="0" cellspacing="0" role="table" aria-label="SNCU monitoring table">
						<tr>
							<td colspan="11" data-b-a-s="thick" data-fill-color="FFd7a7a7" data-a-h="center" data-f-sz="12" bgcolor="#d7a7a7" data-f-bold="true">
								<div align="center"><b>Analysis of Assessment of Home Visit under ECD <?php if ($first == $last) { ?> <?= date('F-Y', strtotime($last)) ?> <?php } else { ?> <?= date('F Y', strtotime($first)) ?> - <?= date('F Y', strtotime($last)) ?> <?php } ?> for (<?php if ($districtText != '') {
																																																																								echo ucwords($districtText);
																																																																							} else {
																																																																								echo $allDistricts;
																																																																							}  ?>)</b></div>
							</td>
						</tr>
						<tr>
							<td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;"> # </td>
							<td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;"><b> Home visit Sessions under the ECD Initiative Monitored</b></td>
							<td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;" data-f-bold="true" data-a-h="center"><b>Araria </b></td>
							<td data-a-h="center" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;"></td>
							<td  data-a-h="center" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;" style="text-align:center;" data-f-bold="true"><b><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') ?></b></td>
							<td  data-a-h="center" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;" data-f-bold="true"><b>Purnea</b></td>
							<td  data-a-h="center" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;" style="text-align:center;" data-f-bold="true"><b><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0') ?></b></td>
							<td  data-a-h="center" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;" data-f-bold="true"><b>Total </b></td>
							<td  data-a-h="center" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;" style="text-align:center;" data-f-bold="true" data-b-a-c="#FFDE00"><b><?= $num_ar + $num_pr ?></b></td>
							<td  data-a-h="center" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;" data-f-bold="true"><b></b></td>
							<td  data-a-h="center" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#000000" data-f-bold="true" style="color: yellow;" data-f-bold="true"><b></b></td>
						</tr>
						<tr>
							<td></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b></b></td>
							<td colspan="3" data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Araria</b></td>

							<td colspan="3" data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Purnea</b></td>

							<td colspan="3" data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total</b></td>

						</tr>
						<tr>
							<td></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>General Visit Information - ASHA &amp; AWW</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
						</tr>
						<tr>
							<td style="text-align:center;">1</td>
							<td style="text-align:left;">% of ASHA workers who conducted at least one home visit in the past 3 months</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'asha_visit', 'yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'asha_visit!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'asha_visit', 'yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'asha_visit!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">2</td>
							<td style="text-align:left;">% of Anganwadi Workers (AWWs) who conducted at least one home visit in the past 3 months</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'aww_visit', 'yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'aww_visit!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'aww_visit', 'yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'aww_visit!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">3</td>
							<td style="text-align:left;">Sex of Child (Male)</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sex', 'male') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sex!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sex', 'male') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sex!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">4</td>
							<td style="text-align:left;">Sex of Child (Female)</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sex', 'female') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sex!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sex', 'female') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sex!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">5</td>
							<td style="text-align:left;">Birth-weight available</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_weight>', '0') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_weight!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_weight>', '0') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_weight!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">6</td>
							<td style="text-align:left;">LBW</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id','birth_weight>=0 and birth_weight<', '2.4') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_weight!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_weight>=0 and birth_weight<', '2.4') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_weight!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">7</td>
							<td style="text-align:left;">Place of Birth (Institutional)</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'delivery_type', 'institutional') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'delivery_type!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'delivery_type', 'institutional') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'delivery_type!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">8</td>
							<td style="text-align:left;">% of Children having Birth Certificate</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration', 'yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration', 'yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">9</td>
							<td style="text-align:left;">Average number of children (0-5 years) in the household</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getAverage($conn, $tablename, $districtName, 'total_children') ?></td>
							<td style="text-align:center;"></td>
							<td style="text-align:center;"></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getAverage($conn, $tablename, $districtName1, 'total_children') ?></td>
							<td style="text-align:center;"></td>
							<td style="text-align:center;"></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $totalAVG = (($num_ar + $num_pr) / 2) ?></td>
							<td style="text-align:center;"></td>
							<td data-f-bold="true" style="text-align:center;"><b></b></td>
						</tr>

						<tr>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Key IYCF Practices</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
						</tr>

						<tr>
							<td style="text-align:center;">10</td>
							<td style="text-align:left;">% of Children started complementary feeding just after 6 months</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'complementary_feeding', '6') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'complementary_feeding', '6') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">11</td>
							<td style="text-align:left;">% of children who attended any Annaprashan Ceremony at the AWC</td>
							<!--Araria--------->
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'annaprashan_ceremony', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'annaprashan_ceremony', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">12</td>
							<td style="text-align:left;">% of caregivers who received information on the right age to start complementary foods from ASHA/AWW</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'right_age_complementary', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'right_age_complementary', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">13</td>
							<td style="text-align:left;">% of children above 6 months receiving Minimum Dietary DIversity (MDD) in the last 24 hours</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months>=6 and child_age_months<=23 and MDD', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months>=6 and child_age_months<', '23') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id','child_age_months>=6 and child_age_months<=23 and MDD', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id','child_age_months>=6 and child_age_months<', '23') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">14</td>
							<td style="text-align:left;">% of children above 6 months who met MMF</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months>=6 and child_age_months<=23 and MMF', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months>=6 and child_age_months<', '23') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months>=6 and child_age_months<=23 and MMF', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months>=6 and child_age_months<', '23') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">15</td>
							<td style="text-align:left;">% of children above 6 months who met MAD</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months>=6 and child_age_months<=23 and MAD', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months>=6 and child_age_months<', '23') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months>=6 and child_age_months<=23 and MAD', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months>=6 and child_age_months<', '23') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">16</td>
							<td style="text-align:left;">% of caregivers received information on the right quantity of complementary foods for your child from ASHA/AWW?</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'dispose_feaces', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'dispose_feaces', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">17</td>
							<td style="text-align:left;">% Children receiving breastfeeding Yesterday (All Age)</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'baby_food__breastmilk', '1') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'baby_food__breastmilk', '1') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">18</td>
							<td style="text-align:left;">% of under 6 months children on Exclusive Breastfeeding</td>
							<td style="text-align:center;">
							
							<?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months<=5 and exclusive_Breastfeeding', '1') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months<', '5') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months<=5 and exclusive_Breastfeeding', '1') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months<', '5') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">19</td>
							<td style="text-align:left;">% of children eating Junk Food (All Age)</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months>=7 and baby_food__Junk_items','1') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months>', '7') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months>=7 and baby_food__Junk_items', '1') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months>', '7') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">20</td>
							<td style="text-align:left;">% of below 6 months children getting Animal Milk</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months<=5 and baby_food__cows_milk', '1') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months<', '5') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months<=5 and baby_food__cows_milk', '1') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months<', '5') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">21</td>
							<td style="text-align:left;">% of below 6 months children getting Formula Milk</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months<=5 and baby_food__formula_milk', '1') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months<', '5') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months<=5 and baby_food__formula_milk', '1') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months<', '5') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">22</td>
							<td style="text-align:left;">% of MCP card having updated details of biweekly IFA syrup</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'mcp_update_status', 'up_to_date') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'mcp_update_status', 'up_to_date') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">23</td>
							<td style="text-align:left;">% of caregivers awared of IFA correct dose</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'ifa_dose_awareness', 'Aware') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'ifa_dose_awareness', 'Aware') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>

						<tr>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Growth Monitoring of Child</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
						</tr>
						<tr>
							<td style="text-align:center;">24</td>
							<td style="text-align:left;">% of family having MCP Card at home</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'mcp_card', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'mcp_card', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">25</td>
							<td style="text-align:left;">% of children having their height and weight measured in the last one month</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weight_measured', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weight_measured', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">26</td>
							<td style="text-align:left;">% of families with plotted growth monitoring in MCP card by AWW</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'growth_monitoring', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'growth_monitoring', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">27</td>
							<td style="text-align:left;">% of families who received any advice after growth monitoring of their baby by AWW</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'growth_counselling', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'growth_counselling', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">28</td>
							<td style="text-align:left;">% of family awared about the nutritional condition of their child</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'nutritional_condition', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'nutritional_condition', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Key Activities for Achieving Developmental Milestone</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
						</tr>
						<tr>
							<td style="text-align:center;">29</td>
							<td style="text-align:left;">% of Children on track as per age-appropriate activities mentioned in the MCP card</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'activities', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'activities', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">30</td>
							<td style="text-align:left;">% of families where members (mother, father, grandparents) engage daily in talking or playing with the child</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'caregivers__father=1 and caregivers__grandparents=1 and caregivers__mother', '1') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'caregivers__father=1 and caregivers__grandparents=1 and caregivers__mother', '1') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">31</td>
							<td style="text-align:left;">% of Families received information about developmental milestones for their child (e.g., smiling, holding the neck, crawling, walking)</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'developmental_milestone', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'developmental_milestone', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">32</td>
							<td style="text-align:left;">% of families received always key messages from AWWs about addressing childrens individual and emotional needs, and avoiding harsh language or physical punishment?</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'aww_messages', 'Always') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'aww_messages', 'Always') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">33</td>
							<td style="text-align:left;">% of families who attended any Parents Meeting at the AWC in the last one month</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'parents_meeting', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'parents_meeting', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">34</td>
							<td style="text-align:left;">% of families aware of the age at which a childs brain develops the most</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'brain_development', 'In_utero_and_first_two_years') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'brain_development', 'In_utero_and_first_two_years') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">35</td>
							<td style="text-align:left;">% of children who have access to age-appropriate toys for play</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'age_appropriate_toy', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'age_appropriate_toy', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>

						<tr>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>WASH Component</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
						</tr>
						<tr>
							<td style="text-align:center;">36</td>
							<td style="text-align:left;">% of family having access to a improved functional toilet in their household</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'toilet', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'toilet', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">37</td>
							<td style="text-align:left;">% of family usually dispose the child feaces in toilet</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'dispose_feaces', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'dispose_feaces', 'Yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">38</td>
							<td style="text-align:left;">% of households having improved sources of drinking water</td>
							<td style="text-align:center;"><?= $num_ar = (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'handpump') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'tubewell') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'nal_Jal_Scheme') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'bottled_water') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'protected_dug_well')) ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = (getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'handpump') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'tubewell') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'nal_Jal_Scheme') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'bottled_water') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'protected_dug_well')) ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>

						<tr>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Health Component</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Num</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Den</b></td>
							<td data-f-bold="true" data-fill-color="FF92C7E0" data-b-a-s="thin" data-f-color="FF000000" bgcolor="#92C7E0" data-f-bold="true" style="color: black;"><b>Total %</b></td>
						</tr>
						<tr>
							<td style="text-align:center;">39</td>
							<td style="text-align:left;">Age-appropriate completely vaccinated children</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'vaccination_status', 'completely_vaccinated') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'mcp_card', 'yes') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'vaccination_status', 'completely_vaccinated') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'mcp_card', 'yes') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">40</td>
							<td style="text-align:left;">Intake of Vitamin A recorded in MCP card</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'child_age_months>=10 and vitamin_a="yes" and mcp_card', 'yes') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!-------- Purnia --------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'child_age_months>=10 and vitamin_a="yes" and mcp_card', 'yes') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'birth_registration!', '') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!-------- Total --------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
						<tr>
							<td style="text-align:center;">41</td>
							<td style="text-align:left;">% of MCP card having updated details of biweekly IFA syrup</td>
							<td style="text-align:center;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'mcp_update_status', 'up_to_date') ?></td>
							<td style="text-align:center;"><?= $den_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') ?></td>
							<td style="text-align:center;"><?= $total_ar = getPercentage($num_ar, $den_ar) ?></td>
							<!--Purnia--------->
							<td style="text-align:center;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'mcp_update_status', 'up_to_date') ?></td>
							<td style="text-align:center;"><?= $den_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0') ?></td>
							<td style="text-align:center;"><?= $total_pr = getPercentage($num_pr, $den_pr) ?></td>
							<!--Total--------->
							<td style="text-align:center;"><?= $total_num = $num_ar + $num_pr ?></td>
							<td style="text-align:center;"><?= $total_den = $den_ar + $den_pr ?></td>
							<td data-f-bold="true" style="text-align:center;"><b><?= $total_all = getPercentage($total_num, $total_den) ?></b></td>
						</tr>
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
			name: "ECD_Home_Visit_assessment.xlsx", // Set your desired file name here
			sheet: {
				name: "ECD Home Visit assessment" // Set the sheet name here
			}
		});
	});
</SCRIPT>

</html>