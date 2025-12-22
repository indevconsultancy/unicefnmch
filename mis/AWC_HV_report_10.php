<?php include_once('includes/config.php'); ?>
<?php define("title", "AWC & HV Analysis Report | UNICEF"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

$month = date('m'); // October
$year = date('Y');
$mindate = '2024-01';
$maxdate = $year . '-' . $month;

$fromDate = new DateTime($_REQUEST['fromDate']); 
$toDate = new DateTime($_REQUEST['toDate']);     

$MonthCount1 = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m'));
$MonthCount = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m')) - 1;
$m=$MonthCount1;
$months = [];
$fullfilterText=[];
$fullfilter=[];
for ($i = 0; $i <= $MonthCount; $i++) {
    $months[] = $fromDate->format('m');
	$fullfilterText[] = $fromDate->format('F').'-'.$fromDate->format('Y');
	$fullfilter[] = $fromDate->format('m').'-'.$fromDate->format('Y');
    $fromDate->modify('+1 month');     
}

$monthsName = "'" . implode("', '", $months) . "'"; 
 echo $monthsName; 


$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

function getcountAWCHV($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '') {
    $qry = "";
    if (isset($_REQUEST['search'])) {
        if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
            $startDate = new DateTime($_REQUEST['fromDate']);
			 $endDate = new DateTime($_REQUEST['toDate']);
            //$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
			$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
        }
        if (isset($_REQUEST['district_code']) && $_REQUEST['district_code'] != '') {
            $qry .= " AND district='" . $_REQUEST['district_code'] . "'";
        }
    }

    $qryv = '';
    if ($qryfield1 != '') {
        $qryv = " AND $qryfield1='$value1'";
    }

    $query = "SELECT COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' $qryv $qry";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->total;
}

// function getcountAWCHV($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
// {

// 	$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
// 	$qry = "";
// 	if (isset($_REQUEST['search'])) {
// 		// if (isset($_REQUEST['reporting_period']) && $_REQUEST['reporting_period'] != '') {
// 		// 	$month = date('m', strtotime($_REQUEST['reporting_period'])); // October
// 		// 	$year = date('Y', strtotime($_REQUEST['reporting_period']));
// 		// 	$mindate = '2024-01';
// 		// 	$maxdate = $year . '-' . $month;
// 		// 	$qry .= " AND dom like'" . $_REQUEST['reporting_period'] . "%'";
// 		// }
// 		if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
// 			$startDate = new DateTime($_REQUEST['fromDate']);
// 			$endDate = new DateTime($_REQUEST['toDate']);
// 			$qry .= " AND MONTH(dom) IN ($monthsName)";
// 			//$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
// 			// $ssdate = $startDate->format('d-m-Y');
// 			// $esdate = $endDate->format('d-m-Y');
// 		}
// 		if (isset($_REQUEST['district_code']) && $_REQUEST['district_code'] != '') {
// 			$qry .= " AND district='" . $_REQUEST['district_code'] . "'";
// 		}
// 	} else {
// 		//	$qry = " and dom like'" . $maxdate . "%'";
// 	}

// 	$qryv = '';
// 	if ($qryfield1 != '') {
// 		$qryv = " AND $qryfield1='$value1'";
// 	}
// 	//$query = "SELECT  COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' $qryv $qry";
// 	$query = "SELECT COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' $qryv $qry";
// 	$result = mysqli_query($conn, $query);
// 	$row = mysqli_fetch_object($result);

// 	return $row->total;
// }


?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

<title>AWC & HV Analysis</title>

<style type="text/css">
	body {
		font-family: "Calibri";
		/* font-size: x-small;
		margin: 0;
		padding: 0;
		 width: 100%; 
		overflow-x: hidden;  */

	}

	table {
		/* width: 70%; */
		/* border-collapse: collapse;
		table-layout: fixed;
		border-radius: 1px solid black; */
	}

	.header1 {
		border-left: 1px solid #000000;
		min-height: 38px;
		text-align: center;
		vertical-align: middle;
		background-color: #00B0F0 !important;
		color: #FFFFFF !important;
		font-size: 22px;
		font-weight: 700;
		width: 100%;
	}

	.header1s {
		border-bottom: 1px solid #000000;
		border-left: 1px solid #000000;
		min-height: 20px;
		font-size: 11px;
		font-weight: 700;
		text-align: center;
		vertical-align: middle;
		background-color: #FFFF00 !important;
	}

	.header1ss {
		text-align: center !important;
		vertical-align: middle;
		background-color: #00B0F0 !important;
		color: #FFFFFF !important;
		font-size: 14px;
		font-weight: 900;
	}

	th,
	td {
		border: 1px solid black;
		padding: 8px;
		text-align: center;
		overflow: hidden;
	}

	tr:hover {
		background-color: #ddd;
	}

	.td_head {
		border: 1px solid #000000;
		text-align: center;
		vertical-align: middle;
		font-size: 12px;
	}

	.thead {
		background-color: #C5D9F1 !important;
		font-weight: 700;
	}

	.header-main {
		border: 1px solid #000000;
		text-align: center !important;
		vertical-align: middle;
		text-align: left;
		background-color: #FDE9D9 !important;
		font-size: 12px;
	}

	.header-submain {
		border: 1px solid #000000;
		text-align: left !important;
		vertical-align: middle;
		text-align: left;
		background-color: #FDE9D9 !important;
		font-size: 11px;
	}

	.header-submain1 {
		border: 1px solid #000000;
		text-align: center !important;
		vertical-align: middle !important;
		text-align: left !important;
		font-size: 12px;
	}
</style>

<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Report</li>
						<li class="breadcrumb-item active"><i class="fa fa-calandar"></i>HBNCAssessment Report</li>
					</ol>

				</nav>
			</div>
		</div>
		<div class="container-fluid1">
			<form method="GET">
				<div class="row filter_css clearfix g-1">
					<div class="col-lg-4 col-md-4 col-sm-12">
						<div class="form-group">
							<b>Select District</b>
							<select class="form-select" id="formID" name="district_code">
								<option value="" selected> All Districts</option>
								<?php $qryUserType1 = mysqli_query($conn, "SELECT local_name,district_name FROM `districts` where district_code in(select distinct(district_id) from survey_data_monitoring where survey_name_id=20)");
								while ($dataUserType1 = mysqli_fetch_object($qryUserType1)) {
									$selected1 = '';
									if ($dataUserType1->local_name == $_REQUEST['district_code']) {
										$selected1 = 'selected';
									}
								?>
									<option value="<?= $dataUserType1->local_name ?>" <?= $selected1 ?>> <?= $dataUserType1->district_name ?> </option>
								<?php } ?>
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
					<div class="col-lg-1 col-md-4 mt-4">
						<div class="form-group ">
							<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search">Search</button>
						</div>
					</div>
					<div class="col-lg-1 col-md-4 mt-4">
						<div class="form-group ">
							<a href="AWC_HV_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
						</div>
					</div>
					<div class="col-lg-2 col-md-4 mt-4">
						<div class="form-group ">
							<button class="btn btn-success width-md waves-effect waves-light form-control" id="button-excel"><i class="fas fa-file-excel"></i> Export to excel</button>
						</div>
					</div>
				</div>
			</form>
			<section class="panel p-1">
				<div class="table-responsive">
					<table cellspacing="0" id="simpleTable1" data-cols-width="60,50,10">
						<!-- <colgroup span="14" width="64"></colgroup> -->
						<tr>
							<td class="header1" colspan="<?= 5 + (3 * $m) ?>" data-f-sz="22" data-a-h="center" data-b-a-s="thin" data-b-a-c="000000" data-f-color="FFFFFF" data-fill-color="00B0F0" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true"><b>AWC Monitoring July-September 2024 Bihar (Gaya &amp; Purnea)</b></td>
						</tr>
						<tr>
							<td colspan=2 rowspan=2 width="40%" class="header1s" data-f-sz="11" data-a-v="middle" data-b-a-s="thin" data-b-a-c="000000" data-fill-color="FFFF00" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true"><b>Total AWC monitoring and home visits</b></td>
							<td colspan=3 class="header1ss" data-f-color="FFFFFF" data-fill-color="00B0F0" data-b-a-s="thin" data-b-a-c="000000" data-f-sz="14" data-f-bold="true"><b>

									<?php $sqlperiod = mysqli_query($conn, "select max(dom) as tilldate from aujyspbgxt8m3j9tjmv62c limit 0,1");
									$dataperiod = mysqli_fetch_object($sqlperiod);  ?>
									Till <?= date('d-M-Y', strtotime($dataperiod->tilldate)) ?>
								</b>
							</td>
							<? php // for ($i = 0; $i < $m; $i++) { 
							?>
							<!--<td colspan=3 class="header1ss" data-f-color="FFFFFF" data-fill-color="00B0F0" data-b-a-s="thin" data-b-a-c="000000" data-f-sz="14" data-f-bold="true"><b>July</b></td>-->
							<? php // } 
							?>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td colspan=3 class='header1ss' data-f-color='FFFFFF' data-fill-color='00B0F0' data-b-a-s='thin' data-b-a-c='000000' data-f-sz='14' data-f-bold='true'><b><?=$fullfilterText[$i];?></b></td>
							<?php }
							?>
						</tr>

						<tr>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center"><b>N</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center"><b>D</b></td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" data-fill-color="C5D9F1"><b>%</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center"><b>N</b></td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center"><b>D</b></td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" data-fill-color="C5D9F1"><b>%</b></td>
							<?php } ?>
						</tr>
						<tr>
							<td class="header-main" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="12" data-f-sz="12" rowspan=7>
								Logistics
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Functional baby weighing scale
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_child_wm', 'fun', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_child_wm!', 'fun', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?></b>
							</td>

							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_child_wm', 'fun', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_child_wm!', 'fun', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?></b>
								</td>
							<?php } ?>


						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Functional adult weighing machine
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'Func_adult_wm', 'fun', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'Func_adult_wm!', 'fun', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?></b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'Func_adult_wm', 'fun', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'Func_adult_wm!', 'fun', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?></b>
								</td>
							<?php } ?>
						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Functional Infantometer
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_infanto', 'fun', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_infanto!', 'fun', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?></b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_infanto', 'fun', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_infanto!', 'fun', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?></b>
								</td>
							<?php } ?>
						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Functional Stadiometer
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_stadio', 'fun', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_stadio!', 'fun', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?></b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_stadio', 'fun', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'func_stadio!', 'fun', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?></b>
								</td>
							<?php } ?>
						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								SAM Management Handout
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_handout', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_handout!', 'fun', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?></b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_handout', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_handout!', 'fun', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?></b>
								</td>
							<?php } ?>
						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								SAM Management Protocol (CMAM) displayed
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'CMAM_manual', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'CMAM_manual!', 'fun', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?></b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'CMAM_manual', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'CMAM_manual!', 'fun', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?></b>
								</td>
							<?php } ?>
						</tr>
						<tr>
							<td class="header-submain" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								AWW having smart Mobile phone with Poshan Tracker installed
							</td>

							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'smart_m_pt', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'smart_m_pt!', 'fun', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?></b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'smart_m_pt', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'smart_m_pt!', 'fun', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?></b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=5>
								Counseling material
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Has Katori Chamach
							</td>

							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="26" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__kat_cham', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">

								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__kat_cham!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?></b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="8" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__kat_cham', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__kat_cham!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="80" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?></b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Has counselling cards
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="8" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__coun_card', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__coun_card!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>

							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="3" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__coun_card', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__coun_card!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Has Poshan kit
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="2" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__pos_kit', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__pos_kit!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__pos_kit', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__pos_kit!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Has MCP card
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="17" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__mcp_car', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__mcp_car!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="42.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__mcp_car', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__mcp_car!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Not available
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="8" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__na', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__na!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>

							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__na', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'coun_mat__na!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-a-wrap="true" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FFFF00" data-f-sz="11" style="background-color:#FFFF00 !important;font-weight:900" colspan=2><b>
									AWW Knowledge - wasting screening &amp; management
								</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00"><b>
									<br>
								</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
								<br>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00"><b>
									<br>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00"><b>
										<br>
									</b></td>

							<?php } ?>

						</tr>
						<tr>
							<td class="header-main" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="12" rowspan=5>
								Knowledge - most severe form of malnutrition
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								SAM
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="31" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'sam', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'sam', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="77.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'sam', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'sam', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								MAM
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="1" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'mam', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'mam', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="2.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'mam', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'mam', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								SUW (Atikuposhit/ gambhir alpvazan)
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="1" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'suw', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'suw', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="2.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'suw', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'suw', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								UW (Alpvazan)
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="0" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'uw', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'uw', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'uw', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'uw', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Don’t know
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="7" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'dnk', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'dnk', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="17.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="2" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal', 'dnk', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_mal!', 'dnk', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left">
								<br>
							</td>
							<td class="td_head" style="text-align:left !important; background-color:red;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Knowledge - name of annual campaign run by ICDS department for management of SAM cases
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="17" sdnum="1033;">
								17
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								40
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="42.5" sdnum="1033;0;0.0"><b>
									42.5
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="4" sdnum="1033;">
									4
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									10
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="40" sdnum="1033;0;0.0"><b>
										40.0
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-main" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="12" rowspan=4>
								Knowledge - What is essential to assess SAM/MAM in children at the AWC?
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weight and length/ height
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="32" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam', 'wt_hl', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam!', 'wt_hl', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="80" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="8" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam', 'wt_hl', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam!', 'wt_hl', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="80" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								length/ height and age
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="0" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam', 'l_h_age', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam!', 'l_h_age', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam', 'l_h_age', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam!', 'l_h_age', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weight and age
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="6" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam', 'w_age', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam!', 'w_age', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="15" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="2" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam', 'w_age', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam!', 'w_age', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Don't know
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="2" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam', 'dnk', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam!', 'dnk', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam', 'dnk', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_screen_sam!', 'dnk', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=4>
								Based on weight &amp; height AWW approach to know the SAM/MAM category
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Poshan tracker
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="31" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__pt', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__pt!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="77.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="9" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__pt', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__pt!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="90" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Weight - length/height reference charts
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="13" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__wlh_ref_chart', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__wlh_ref_chart!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="32.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="4" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__wlh_ref_chart', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__wlh_ref_chart!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="40" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								MCP card- Growth chart
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="9" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__mcp_card_gc', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__mcp_card_gc!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="22.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="3" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__mcp_card_gc', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__mcp_card_gc!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Not aware
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="4" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__not_aw', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__not_aw!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__not_aw', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_sam_identify__not_aw!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-main" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="12">
								Bilateral Pitting Oedema Test
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								AWW able to demonstrate how to check for bilateral pitting oedema
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="6" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_bl_pitting', 'aware', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_bl_pitting!', 'aware', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="15" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="2" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_bl_pitting', 'aware', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_aware_bl_pitting!', 'aware', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=6>
								Awarness - steps for management of child with SAM
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Step 1 - Screening for identification of SAM (ICDS)
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="17" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_1', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_1!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="42.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_1', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_1!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Step 2 - Appetite test by AWW (ICDS)
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="6" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_2', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_2!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="15" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="4" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_2', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_2!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="40" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Step 3 - Medical management of SAM at VHSND / Health facility (HEALTH)
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="15" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_3', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_3!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="37.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_3', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_3!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Step 4 - Nutritional Management by AWW through home visits and THR distribution
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="10" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_4', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_4!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="25" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="4" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_4', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_4!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="40" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Step 5 - Follow up at monthly VHSND and fortnightly Home visit (Health &amp; ICDS)
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="5" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_5', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_5!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="12.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="2" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_5', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__st_5!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Don't know
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__dnk', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__dnk!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__dnk', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mgmt_steps__dnk!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>

						<!---------------------------- Start Khushboo 02.11.2024 ------------------------------>
						<tr>
							<td class="header-main" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="12" rowspan=5>
								Awarness - place of mobilization of children with SAM for first time
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								VHSND session for community management
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="12" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'vhsnd', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'vhsnd', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="3" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'vhsnd', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'vhsnd', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								NRC
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'nrc', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'nrc', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="2" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'nrc', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'nrc', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Nearest health facility (PHC/ CHC)/ RBSK
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="5" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'chc_phc_rbsk', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'chc_phc_rbsk', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="12.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'chc_phc_rbsk', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'chc_phc_rbsk', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Manage at AWC level only
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="2" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'awc', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'awc', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'awc', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'awc', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								don't know
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="10" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'dnk', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'dnk', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="25" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="3" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref', 'dnk', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_first_ref!', 'dnk', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Knowledge - medical treatment for children with SAM if s/he has no illness and with good apetite test
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Aware
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="15" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mt', 'aware', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mt!', 'aware', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="37.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="3" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mt', 'aware', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_mt!', 'aware', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-a-v="middle" data-a-wrap="true" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" rowspan=7 align="left" valign=middle bgcolor="#FDE9D9" rowspan=7>
								AWW knowledge - name the essential medicines/ nutritional supplements given to SAM case treated at the community level
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Antibiotic (Amoxicillin syrup)
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="2" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__anti', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__anti!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__anti', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__anti!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Folic acid tablet
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="3" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__foli', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__foli!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="7.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__foli', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__foli!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Albendazole
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="7" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__albe', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__albe!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="17.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__albe', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__albe!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Vitamin A
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="9" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__vlta', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__vlta!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="22.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="2" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__vlta', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__vlta!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Iron Folic Acid syrup
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="16" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__iron', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__iron!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="40" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="2" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__iron', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__iron!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Multivitamin syrup
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="4" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__multivit', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__multivit!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__multivit', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__multivit!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Not aware
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="24" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__dnk', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__dnk!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="8" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__dnk', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_med__dnk!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="80" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-a-v="middle" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left">
								AWW knowledge - medical treatment for a SAM case having illness or with poor appetite
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Aware
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="15" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'mt_poor_app', 'aware', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'mt_poor_app!', 'aware', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="37.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="3" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'mt_poor_app', 'aware', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'mt_poor_app!', 'aware', '', '') ?>
								</td>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-main" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="12" rowspan=5>
								AWW knowledge - steps taken by AWW for improving the nutritional status (i.e. SAM to Normal) of the SAM case
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weekly/ fortnightly home visit
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="12" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__week', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__week!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__week', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__week!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Counselling &amp; demonstration for home based energy &amp; nutrient enhancement of diet
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="18" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__coun', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__coun!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="45" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__coun', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__coun!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								AWC based session
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="3" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__awc_sess', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__awc_sess!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="7.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__awc_sess', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__awc_sess!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Provide Take Home Ration (THR)
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="15" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__prov_thr', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__prov_thr!', '1', '', '') ?>

							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="37.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="3" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__prov_thr', '1', '', '') ?>

								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__prov_thr!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>

									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								don't know
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="17" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__dnk', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__dnk!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="42.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="4" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__dnk', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'improve_nutri__dnk!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="40" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=10>
								AWW knowledge - key messages AWW give to the family of SAM case for enhancing the energy and nutrient of daily diet
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Adding Ghee/ Oil to meal
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="16" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__add_ghee', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__add_ghee!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="40" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__add_ghee', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__add_ghee!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								At least 3 meals &amp; 2 snacks daily
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="16" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__three_m_2_sn', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__three_m_2_sn!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="40" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__three_m_2_sn', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__three_m_2_sn!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Adding Roasted groundnut/Soyabean/Sattu powder in meal
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__add_ground_soya_sattu', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__add_ground_soya_sattu!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="4" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__add_ground_soya_sattu', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__add_ground_soya_sattu!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="40" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Milk
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="31" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__milk', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__milk!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="77.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__milk', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__milk!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="100" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Eggs
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__egg', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__egg!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="8" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__egg', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__egg!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="80" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								One seasonal fruit
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="20" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__sea_fruit', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__sea_fruit!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__sea_fruit', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__sea_fruit!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								One green leafy or Yellow vegetable in diet
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="26" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__green_yellow_veg', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__green_yellow_veg!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="65" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="7" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__green_yellow_veg', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__green_yellow_veg!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="70" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								THR based recipe/snacks
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="7" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__thr_snack', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__thr_snack!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="17.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__thr_snack', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__thr_snack!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Biweekly IFA syrup
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="13" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__bi_ifa', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__bi_ifa!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="32.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="3" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__bi_ifa', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__bi_ifa!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Not aware
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="7" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__dnk', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__dnk!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="17.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__dnk', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'nuri_msg__dnk!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-main" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="12" rowspan=4>
								AWW knowledge - how is SAM child's progress followed up during the programme period?
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weekly/ fortnightly home visit
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="12" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__week', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__week!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__week', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__week!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Monthly VHSND/HWC visit
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="8" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__mont', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__mont!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="20" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__mont', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__mont!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								AWC based session
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="1" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__awc_session', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__awc_session!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="2.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__awc_session', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__awc_session!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Don't know
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="25" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__dnk', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__dnk!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="62.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__dnk', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'sam_progress_fup__dnk!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan="4">
								AWW knowledge - action if the child does not gain weight or loses weight in two consecutive follow-ups visits
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								VHSND session for community management
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="2" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp', 'vhsnd_session', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp!', 'vhsnd_session', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp', 'vhsnd_session', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp!', 'vhsnd_session', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								NRC
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="2" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp', 'nrc', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp!', 'nrc', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp', 'nrc', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp!', 'nrc', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Nearest health facility (PHC/ CHC)/ RBSK
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="2" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp', 'chc_phc_rbsk', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp!', 'chc_phc_rbsk', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp', 'chc_phc_rbsk', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp!', 'chc_phc_rbsk', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Don't know
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="2" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp', 'dnk', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp!', 'dnk', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp', 'dnk', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_not_resp!', 'dnk', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-main" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="12" rowspan=8>
								AWW knowledge - mention activities are being done during a follow-up session (VHSND or Home visit) of child with SAM?
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weighing
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="18" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__weighing', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__weighing!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="45" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__weighing', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__weighing!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Monitor weight improvement
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="18" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__moni_wt', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__moni_wt!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="45" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__moni_wt', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__moni_wt!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Checking for signs of illness
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="18" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_ill_sign', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_ill_sign!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="45" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_ill_sign', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_ill_sign!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Checking compliance for medicine
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="18" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_med_comp', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_med_comp!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="45" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_med_comp', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_med_comp!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Checking compliance for recommended diet
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="18" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_rd', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_rd!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="45" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_rd', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__check_rd!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Counselling - diet and hygiene
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="18" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__couns_hyg_diet', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__couns_hyg_diet!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="45" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__couns_hyg_diet', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__couns_hyg_diet!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Maintaining record
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="18" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__rec_maintain', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__rec_maintain!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="45" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__rec_maintain', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__rec_maintain!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Don't know
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="18" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__dnk', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__dnk!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="45" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="6" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__dnk', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_activites__dnk!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="60" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								AWW knowledge - time period for a SAM child to be followed up and continued in programme
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								<br>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="6" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_duration', 'aware', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_duration!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="15" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_duration', 'aware', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'fup_duration!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="10" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-main" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="12" rowspan=3
								AWW knowledge - Action if a child continues to remain in SAM category even after 3 months follow up period in CMAM<br>program?
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Refer to NRC/Health Facility
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="17" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon', 'refer_nrc', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon!', 'refer_nrc', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="42.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon', 'refer_nrc', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon!', 'refer_nrc', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Continue in the program with intensive follow-up
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="17" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon', 'continue_int_fup', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon!', 'continue_int_fup', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="42.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon', 'continue_int_fup', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon!', 'continue_int_fup', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Don’t know
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="17" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon', 'dnk', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon!', 'dnk', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="42.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon', 'dnk', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'what_done_aft_3mon!', 'dnk', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="50" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FFFF00" data-f-sz="11" colspan=2 style="background-color:#FFFF00 !important;font-weight:900" <b>AWW Anthropometry knowledge Length Measurement</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00"><b>
									<br>
								</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
								<br>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00" sdnum="1033;0;0.0"><b>
									<br>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00" sdnum="1033;0;0.0"><b>
										<br>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=7>
								Knowledge - Length measurement
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Child below two years
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_below_2r', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_below_2r!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_below_2r', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_below_2r!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Infantometer placed on Firm, Flat surface
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__firm_place', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__firm_place!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__firm_place', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__firm_place!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Head is touching the head piece(looking vertically upward)
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__head_corr', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__head_corr!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__head_corr', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__head_corr!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Child's body position - legs, back &amp; head are in a straight line
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_body_p', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_body_p!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_body_p', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_body_p!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Both foot are touching flat on foot piece
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_foot_corr', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_foot_corr!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_foot_corr', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__child_foot_corr!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Support of any additional person taken while measurement
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__supp_add_person', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__supp_add_person!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__supp_add_person', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__supp_add_person!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Child less than 2 year not available
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__sam_less2_na', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__sam_less2_na!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__sam_less2_na', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_infanto__sam_less2_na!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" style="text-align:left !important;" data-a-wrap="true" data-a-v="middle" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" rowspan=5>
								Knowledge - weight measurement using salter scale
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weighing scale hanged properly
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__scale_hanged_corr', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__scale_hanged_corr!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__scale_hanged_corr', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__scale_hanged_corr!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Pointer calibrated to zero
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__cal_zero', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__cal_zero!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__cal_zero', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__cal_zero!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weighing done in minimal clothing
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__min_cloth', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__min_cloth!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__min_cloth', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__min_cloth!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weight recorded when pointer is static
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', '`aww_skill_salter__pointer-static`', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', '`aww_skill_salter__pointer-static`!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', '`aww_skill_salter__pointer-static`', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', '`aww_skill_salter__pointer-static`!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Child less than 1 year not available
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__sam_less2_na', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__sam_less2_na!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__sam_less2_na', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_salter__sam_less2_na!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class=".header-submain1" style="text-align:left !important;" data-a-v="middle" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" align="center" valign=middle rowspan=8 ">
								Knowledge - height measurement using stadiometer
							</td>
							<td class=" td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Child above two years
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_gr_2yr', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_gr_2yr!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_gr_2yr', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_gr_2yr!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Stadiometer placed on Firm, Flat surface
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__firm_place', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__firm_place!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__firm_place', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__firm_place!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Child's feet position - both feet touching each other
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_feet_p', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_feet_p!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_feet_p', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_feet_p!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Child's body position - heels, buttocks, back &amp; head touching stadiometer in straight line
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_body_p', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_body_p!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_body_p', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__child_body_p!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Stadiometer head piece touching child's head
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__stadio_touch_head', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__stadio_touch_head!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__stadio_touch_head', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__stadio_touch_head!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								AWW's eye level is at the level of head piece while recording height
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__corr_eye_pos', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__corr_eye_pos!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__corr_eye_pos', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__corr_eye_pos!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Support of any additional person taken while measurement
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__supp_add_person', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__supp_add_person!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__supp_add_person', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__supp_add_person!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Child greater than 2 year not available
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__sam_gr2_na', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__sam_gr2_na!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__sam_gr2_na', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_stadio__sam_gr2_na!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class=".header-submain1" style="text-align:left !important;" data-a-v="middle" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" rowspan=5>
								Knowledge - weight measurement using adult weighing scale
							</td>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Kept on flat and even surface
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__flat_sur', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__flat_sur!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__flat_sur', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__flat_sur!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weighing scale tared to zero
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__tared_zero', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__tared_zero!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__tared_zero', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__tared_zero!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Weighing done in minimal clothing
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__min_cloth', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__min_cloth!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__min_cloth', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__min_cloth!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Child standing straight at centre of weighing scale
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__child_centre_ws', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__child_centre_ws!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__child_centre_ws', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__child_centre_ws!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FDE9D9" data-f-sz="11">
								Child greater than 2 year not available
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__sam_gr2_na', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__sam_gr2_na!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="21" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__sam_gr2_na', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_skill_dws__sam_gr2_na!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="52.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FFFF00" data-f-sz="11" style="background-color:#FFFF00 !important;font-weight:900" colspan=2 <b>
								SAM Status
								</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00"><b>
									<br>
								</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
								<br>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00" sdnum="1033;0;0.0"><b>
									<br>
								</b></td>
							<!-- <td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
								<br>
							</td> -->
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00" sdnum="1033;0;0.0"><b>
										<br>
									</b></td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" align="left" valign=middle>
								<br>
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Have you received any training on management of children with SAM?
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="17" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_trg_sam', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_trg_sam!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="42.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="17" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_trg_sam', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_trg_sam!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="42.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" align="left" valign=middle>
								<br>
							</td>
							<td class="td_head" style="text-align:left !important; background-color:red;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								0-5 years children registred on POSHAN Tracker
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="3023" sdnum="1033;">
								3023
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
								<br>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
									<br>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="866" sdnum="1033;">
									866
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
										<br>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" align="left" valign=middle>
								<br>
							</td>
							<td class="td_head" style="text-align:left !important;background-color:red;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								0-5 years children whose growth monitoring was reported on POSHAN Tracker
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="2768" sdnum="1033;">
								2768
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
								<br>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
									<br>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="763" sdnum="1033;">
									763
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
										<br>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" align="left" valign=middle>
								<br>
							</td>
							<td class="td_head" style="text-align:left !important;background-color:red;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Total children with SAM as per POSHAN Tracker
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="68" sdnum="1033;">
								68
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
								<br>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
									<br>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="4" sdnum="1033;">
									4
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
										<br>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" align="left" valign=middle>
								<br>
							</td>
							<td class="td_head" style="text-align:left !important;background-color:red;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Total children with MAM as per POSHAN Tracker
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="119" sdnum="1033;">
								119
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
								<br>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
									<br>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="20" sdnum="1033;">
									20
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
										<br>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" align="left" valign=middle>
								<br>
							</td>
							<td class="td_head" style="text-align:left !important;background-color:red;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-wrap="true">
								Duelist maintained for children with SAM
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="10" sdnum="1033;">
								10
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
								<br>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
									<br>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="4" sdnum="1033;">
									4
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdnum="1033;0;0.0"><b>
										<br>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FFFF00" data-f-sz="11" style="background-color:#FFFF00 !important;font-weight:900" colspan=2 <b>
								Home Visit - 0-5 months infants
								</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00"><b>
									<br>
								</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
								<br>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00" sdnum="1033;0;0.0"><b>
									<br>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00" sdnum="1033;0;0.0"><b>
										<br>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left">
								<br>
							</td>
							<td class="header-submain1" style="background-color:red;" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								infants whose home visit was done
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="40" sdnum="1033;">
								40
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								<br>
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								<br>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" style="text-align:left !important;" data-a-v="middle" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" rowspan="2">
								Gender
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Male
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="29" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_sex', 'male', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_sex!', 'male', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="72.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="29" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_sex', 'male', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_sex!', 'male', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="72.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Female
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="29" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_sex', 'female', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_sex!', 'female', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="72.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="29" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_sex', 'female', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_sex!', 'female', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="72.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" style="text-align:left !important;" data-a-v="middle" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" rowspan="2">
								Infant Delivery Type
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Normal
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="29" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_del', 'normal', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_del!', 'normal', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="72.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="29" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_del', 'normal', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_del!', 'normal', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="72.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">

								C-Section
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="29" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_del', 'c_sec', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_del!', 'c_sec', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="72.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="29" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_del', 'c_sec', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_del!', 'c_sec', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="72.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class=".header-submain1" style="text-align:left !important;background-color:red;" data-a-v="middle" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" rowspan="2">
								Birth weight
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								&gt;2.5 kg
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="29" sdnum="1033;">
								29
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								40
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="72.5" sdnum="1033;0;0.0"><b>
									72.5
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								&lt;2.5 kg
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="186" sdnum="1033;">
								186
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								40
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="465" sdnum="1033;0;0.0"><b>
									465.0
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<br>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class=".header-submain1" style="text-align:left !important;" data-a-v="middle" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" rowspan="4">
								Birth order
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="1" sdnum="1033;">
								1
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="2" sdnum="1033;">
								2
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo', '2', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo!', '2', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo', '2', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo!', '2', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="3" sdnum="1033;">
								3
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo', '3', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo!', '3', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo', '3', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo!', '3', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								&gt;3
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo', 'gr_3', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo!', 'gr_3', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="11" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo', 'gr_3', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_bo!', 'gr_3', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="27.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=5>
								When did you first breastfed your child ?
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Within 1 hour of Delivery
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'within_1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'within_1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'within_1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'within_1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								1-3 hour of Delivery
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'within1_3', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'within1_3', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'within1_3', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'within1_3', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								After 3 hours same day
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'after_3', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'after_3', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'after_3', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'after_3', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								next day of Delivery
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'next_day', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'next_day', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'next_day', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'next_day', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Can't remember
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'cant_rem', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'cant_rem', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf', 'cant_rem', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_eibf!', 'cant_rem', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=7>
								What did you give your baby in the last 24 hours?
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Only Breastmilk
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'only_bm', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'only_bm', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'only_bm', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'only_bm', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Breastmilk &amp; Formula Milk
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'bm_fm', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'bm_fm', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'bm_fm', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'bm_fm', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Breastmilk &amp; Animal_milk
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'bm_am', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'bm_am', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'bm_am', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'bm_am', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Breastmilk &amp; water
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'bm_water', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'bm_water', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'bm_water', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'bm_water', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Formula Milk
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'fm', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'fm', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'fm', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'fm', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Animal_milk
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'am', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'am', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'am', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'am', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Nothing
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'nothing', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'nothing', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf', 'nothing', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_ebf!', 'nothing', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<!---------------->
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=3>
								If child is on mixed or no breastfeeding, how are you feeding the child?
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Bottle
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="2" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode', 'bottle', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="13" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode!', 'bottle', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="15.3846153846154" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="2" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode', 'bottle', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="13" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode!', 'bottle', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="15.3846153846154" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Tumbler
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode', 'tumbler', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode!', 'tumbler', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode', 'tumbler', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode!', 'tumbler', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Katori-Chamach
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode', 'katori_c', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode!', 'katori_c', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="19" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode', 'katori_c', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'feed_mode!', 'katori_c', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="47.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>

						<!-------Complete 02.11.2024-------------->

						<tr>
							<td class="header-submain1" style="text-align:left !important;" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-a-h="center" rowspan="4">
								Has anyone counselled or supported you for promoting only breastfeeding?
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								AWW
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="9" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__aww', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__aww!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="22.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="9" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__aww', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__aww!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="22.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>

							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								ANM
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="1" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__anm', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__anm!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="2.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="1" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__anm', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__anm!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="2.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>

							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								ASHA
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="9" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__asha', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__asha!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="22.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="9" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__asha', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__asha!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="22.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>

							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left">
								No support received
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="0" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__no_supp', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__no_supp!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="0" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__no_supp', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'support_bf__no_supp!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="0" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" style="text-align:left !important;" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-a-h="center">
								Infant's growth monitoring records available on Poshan Tracker
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Yes
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="14" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_gm_records_pt', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_gm_records_pt!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="35" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="14" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_gm_records_pt', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_gm_records_pt!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="35" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Infant's growth monitoring details updated on MCP card
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Yes
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="5" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_gm_records_mcp', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_gm_records_mcp!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="12.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="5" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_gm_records_mcp', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_gm_records_mcp!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="12.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Caregivers aware about the nutrition status of the infant
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Yes
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="12" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_caregiver_aware_ns', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_caregiver_aware_ns!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="12" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_caregiver_aware_ns', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_caregiver_aware_ns!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="30" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=6>
								How many times AWW visited infant in last one month
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="0" sdnum="1033;">
								0
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '0', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '0', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '0', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '0', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="1" sdnum="1033;">
								1
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="2" sdnum="1033;">
								2
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '2', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '2', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '2', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '2', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="3" sdnum="1033;">
								3
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '3', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '3', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '3', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '3', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="4" sdnum="1033;">
								4
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '4', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '4', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '4', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '4', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="5" sdnum="1033;">
								5
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '5', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '5', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit', '5', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_aww_visit!', '5', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=6>
								How many times ASHA visited infant in last one month
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="0" sdnum="1033;">
								0
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '0', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '0', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '0', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '0', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="1" sdnum="1033;">
								1
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '1', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '1', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '1', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '1', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="2" sdnum="1033;">
								2
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '2', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '2', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '2', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '2', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="3" sdnum="1033;">
								3
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '3', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '3', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '3', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '3', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="4" sdnum="1033;">
								4
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '4', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '4', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '4', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '4', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" sdval="5" sdnum="1033;">
								5
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '5', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '5', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="208" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit', '5', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_asha_visit!', '5', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain" data-b-a-s="thin" data-a-wrap="true" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-fill-color="FFFF00" data-f-sz="11" style="background-color:#FFFF00!important;font-weight:900" colspan="2"> <b>
									Home Visit - 6-8 months infants
								</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00"><b>
									<br>
								</b></td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
								<br>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00" sdnum="1033;0;0.0"><b>
									<br>
								</b></td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00">
									<br>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFF00" data-fill-color="FFFF00" sdnum="1033;0;0.0"><b>
										<br>
									</b></td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" align="left" valign=middle>
								<br>
							</td>
							<td class="header-submain1" style="background-color:red;" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								infants whose home visit was done
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="40" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_caregiver_aware_ns', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_caregiver_aware_ns!', '', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="40" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_caregiver_aware_ns', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'infant_caregiver_aware_ns!', '', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="520" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=2>
								Gender
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Male
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="23" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_sex', 'male', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_sex!', 'male', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="57.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="23" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_sex', 'male', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_sex!', 'male', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="57.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Female
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="23" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_sex', 'female', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_sex!', 'female', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="57.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="23" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_sex', 'female', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_sex!', 'female', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="57.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Has AWW visited your home in last one month to counsel the caregiver health, cleanliness and nutrition?
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Yes
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_hw_coun_health_clean', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_hw_coun_health_clean!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_hw_coun_health_clean', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_hw_coun_health_clean!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Have you attended any annaprasan ceremony at the AWC in last three months?
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Yes
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'attended_ann', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'attended_ann!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'attended_ann', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'attended_ann!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Was your child weight measured in last one month?
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Yes
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_wm_last_m', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_wm_last_m!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_wm_last_m', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_wm_last_m!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Was your child height/length measured in last one month?
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Yes
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_hl_m', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_hl_m!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_hl_m', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'child_hl_m!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Has AWW informed you about the child's current nutrition status?
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Yes
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_informed_cns', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_informed_cns!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_informed_cns', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'aww_informed_cns!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" align="left" valign=middle>
								Has the child been introduced to Complementary Feeding
							</td>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12">
								Yes
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'cf_started_68', 'yes', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'cf_started_68!', 'yes', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'cf_started_68', 'yes', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'cf_started_68!', 'yes', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="header-submain1" data-a-wrap="true" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left" data-a-v="middle" data-f-sz="12" rowspan=4>
								How many meals child consumed in last 24 hours (except snacks)
							</td>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left">
								One
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68', 'one', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68!', 'one', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68', 'one', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68!', 'one', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left">
								Two
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68', 'two', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68!', 'two', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68', 'two', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68!', 'two', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left">
								Three or more
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68', 'three', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68!', 'three', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68', 'three', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68!', 'three', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
						<tr>
							<td class="td_head" style="text-align:left !important;" data-b-a-s="thin" data-b-a-c="000000" data-a-h="left">
								No meal received
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
								<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68', 'zero', '', '') ?>
							</td>
							<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
								<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68!', 'zero', '', '') ?>
							</td>
							<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
									<?= round((($n * 100) / $d), 0) ?>
								</b>
							</td>
							<?php for ($i = 0; $i < $m; $i++) { ?>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" bgcolor="#FFFFFF" sdval="37" sdnum="1033;">
									<?= $n = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68', 'zero', '', '') ?>
								</td>
								<td class="td_head" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-a-h="center" sdval="40" sdnum="1033;">
									<?= $d = getcountAWCHV($conn, 'aujyspbgxt8m3j9tjmv62c', 'id', 'food_freq_68!', 'zero', '', '') ?>
								</td>
								<td class="td_head thead" data-b-a-s="thin" data-b-a-c="000000" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="92.5" sdnum="1033;0;0.0"><b>
										<?= round((($n * 100) / $d), 0) ?>
									</b>
								</td>
							<?php } ?>

						</tr>
					</table>
				</div>
			</section>
		</div>
	</section>
</section>
<?php include_once('includes/footer.php'); ?>
<SCRIPT>
	let button = document.querySelector("#button-excel");

	button.addEventListener("click", (e) => {
		let table = document.querySelector("#simpleTable1");
		TableToExcel.convert(table, {
			name: "AWC_HV_Analysis.xlsx", // Set your desired file name here
			sheet: {
				name: "AWC & HV Analysis Report" // Set the sheet name here
			}
		});
	});
</SCRIPT>

</html>