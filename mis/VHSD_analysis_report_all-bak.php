<?php include_once('includes/config.php'); ?>
<?php define("title", "VHSD Data List | UNICEF"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

$month = date('m'); // October
$year = date('Y');
$mindate = '2024-01';
$maxdate = $year . '-' . $month;

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
						<li class="breadcrumb-item active"><i class="fa fa-calandar"></i>VHSDAssessment Report</li>
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
					<div class="col-lg-3 col-md-3 col-sm-12">
						<div class="form-group">
							<b> Select Month </b>
							<input class="form-control" type="month" id="start" name="reporting_period" min="<?= $mindate ?>" value="<?= $maxdate ?>" />
						</div>
					</div>
					<div class="col-lg-2 col-md-4 mt-4">
						<div class="form-group ">
							<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search">Search</button>
						</div>
					</div>
					<div class="col-lg-1 col-md-4 mt-4">
						<div class="form-group ">
							<a href="VHSD_analysis_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
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
					<table id="simpleTable1" data-cols-width="60,50,10">
						<!-- <tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="header" colspan=14 valign="middle" data-f-sz="20" data-a-h="center" data-fill-color="00B0F0" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true"><b>VHSND Monitoring July-September 2024 Report (Gaya &amp; Purnea)</b></td>
						</tr> -->
						<tr>
							<td class="header1" style="font-size: 20px;" colspan="14" data-f-sz="20" data-a-h="center" data-fill-color="F2F2F2" data-f-color="C0504D" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true"><b>VHSND Monitoring July-September 2024 Report (Gaya &amp; Purnea)</b></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" colspan="2" width="40%" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b>Period</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" colspan="3" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true">Till 31st October</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" colspan="3" data-f-sz="14" data-f-bold="true">July</td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" colspan=2 style="background-color: #FFFF00;font-size: 11px;text-align: left;" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00">Total VHSND visits </td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center" data-f-bold="true" data-a-h="center"><b><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'id>', '0', '', '') ?></b></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" data-f-bold="true" data-a-h="center"><b>Den.</b></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" data-f-bold="true" data-fill-color="C5D9F1">%</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" data-a-h="center">N</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" data-a-h="center">D</td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" align="center" valign=middle bgcolor="#C0504D" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="C0504D" data-f-color="FFFFFF"><b>
									<font size=3 color="#FFFFFF"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#C0504D" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="C0504D" data-f-color="FFFFFF"><b>
									<font size=3 color="#FFFFFF">ASHA present at the session site</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="42" sdnum="1033;" data-a-h="center"><b><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_p', 'yes', '', '') ?></b></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_p!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="91.304347826087" align="center" valign="middle" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="42" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_p', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_p!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="91.304347826087" align="center" valign="middle" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" align="center" valign=middle bgcolor="#C0504D" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="C0504D" data-f-color="FFFFFF"><b>
									<font size=3 color="#FFFFFF"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#C0504D" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="C0504D" data-f-color="FFFFFF"><b>
									<font size=3 color="#FFFFFF">AWW present at the session site</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="42" sdnum="1033;" data-a-h="center"><b><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_p', 'yes', '', '') ?></b></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_p!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="91.304347826087" align="center" valign="middle" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="42" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_p', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_p!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="91.304347826087" align="center" valign="middle" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="42" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_p', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_p!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="91.304347826087" align="center" valign="middle" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="42" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_p', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_p!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="91.304347826087" align="center" valign="middle" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" colspan=2 height="19" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00">Logistics - PW + Children</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-f-bold="true" data-f-sz="11" data-a-h="left" data-fill-color="FFFF00"><br></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-fill-color="FDE9D9">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle style="font-size: 11px;" bgcolor="#FDE9D9">Functional mother-child weighing machine (Adult)</td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="29" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'Func_adult_wm', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'Func_adult_wm!', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'Func_adult_wm', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'Func_adult_wm!', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'Func_adult_wm', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'Func_adult_wm!', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'Func_adult_wm', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'Func_adult_wm!', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=4 height="57" style="text-align: left;" valign=middle bgcolor="#FFFFFF" data-a-v="middle">Functional Hemoglobinometer</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Tallquist Hemoglobin color Scale</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="29" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'tal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'tal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'tal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'tal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'tal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'tal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'tal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'tal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Digital Hemoglobinometer-Functional</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'dig_hf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'dig_hf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'dig_hf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'dig_hf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'dig_hf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'dig_hf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'dig_hf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'dig_hf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Digital Hemoglobinometer-Non Functional</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'dig_hnf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'dig_hnf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'dig_hnf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'dig_hnf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'dig_hnf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'dig_hnf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'dig_hnf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'dig_hnf', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>

						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Not Available</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'na', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'na', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'na', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'na', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'na', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'na', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter', 'na', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemo_meter!', 'na', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-h="left" data-fill-color="FDE9D9">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-h="left" data-fill-color="FDE9D9">IFA Red strips available</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FFFFFF">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Folic Acid Tablet strips available</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_fa>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_fa<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_fa>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_fa<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_fa>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_fa<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_fa>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_fa<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Calcium Tablet strips available</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_cal>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_cal<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_cal>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_cal<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_cal>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_cal<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_cal>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_cal<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FFFFFF">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Functional child weighing machine</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_child_wm', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_child_wm!', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_child_wm', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_child_wm!', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_child_wm', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_child_wm!', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_child_wm', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_child_wm!', 'func', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Functional Infantometer</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_infanto', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_infanto!', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_infanto', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_infanto!', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_infanto', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_infanto!', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_infanto', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_infanto!', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FFFFFF">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Functional Stadiometer</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_stadio', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_stadio!', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_stadio', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_stadio!', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_stadio', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_stadio!', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_stadio', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_stadio!', 'fun', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Functional Thermometer</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_thermo', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_thermo!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_thermo', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_thermo!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_thermo', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_thermo!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_thermo', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'func_thermo!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FFFFFF">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Weight for height/length chart</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'wfh_chart', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'wfh_chart!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'wfh_chart', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'wfh_chart!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'wfh_chart', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'wfh_chart!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'wfh_chart', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'wfh_chart!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">SAM Management Handout</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_handout', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_handout!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_handout', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_handout!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_handout', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_handout!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_handout', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_handout!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;font-size:12px;" valign=middle bgcolor="#FFFFFF" data-a-v="middle">
								CMAM App
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">CMAM App available in ANM mobile</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_anm', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_anm!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_anm', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_anm!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_anm', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_anm!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_anm', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_anm!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">CMAM application available in AWW mobile</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_aww', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_aww!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_aww', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_aww!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_aww', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_aww!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_aww', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_app_aval_aww!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Any SAM case registered on CMAM App</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_child_reg', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_child_reg!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_child_reg', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_child_reg!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_child_reg', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_child_reg!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_child_reg', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cmam_child_reg!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=8 height="152" style="text-align: left;font-size:12px;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">
								Medicine availability
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Vitamin-A</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'vita', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'vita!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'vita', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'vita!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'vita', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'vita!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'vita', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'vita!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Zinc</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'zinc', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'zinc!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'zinc', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'zinc!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'zinc', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'zinc!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'zinc', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'zinc!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">ORS Packets</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ors', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ors!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ors', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ors!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ors', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ors!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ors', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ors!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Amoxicillin Tablet strips (125 mg/250 mg)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_tab>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_tab<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_tab>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_tab<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_tab>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_tab<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_tab>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_tab<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Amoxicillin Syrup bottles (125 mg/250 mg)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_syp>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_syp<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_syp>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_syp<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_syp>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_syp<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_syp>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_amox_syp<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">IFA Syrup bottles</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa_bot>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa_bot<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa_bot>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa_bot<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa_bot>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa_bot<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa_bot>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_ifa_bot<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Albendazole Tablets</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_alb_tab>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_alb_tab<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_alb_tab>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_alb_tab<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_alb_tab>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_alb_tab<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_alb_tab>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_alb_tab<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Multi-vitamin Syp/ Drops bottles</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_multivit_syp>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_multivit_syp<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_multivit_syp>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_multivit_syp<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_multivit_syp>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_multivit_syp<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_multivit_syp>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'no_multivit_syp<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" colspan=2 height="19" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00" data-a-v="middle">Service Delivery &amp; Recording - Pregnant Women</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-fill-color="FFFF00"><br></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FFFFFF" data-a-v="middle">Weighing</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Done and Correct recording (MCP card)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Not Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">Height</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Done and Correct recording (MCP card)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Not Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'weighing!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FFFFFF" data-a-v="middle">Haemoglobin</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Done and Correct recording (MCP card)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Not Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'hemoglobin!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">Blood Pressure</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9">Done and Correct recording (MCP card)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9">Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9">Not Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'bp!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FFFFFF" data-a-v="middle">Abdominal check-up</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Done and Correct recording (MCP card)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Not Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'abd_ckp!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">IFA distribution</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9">Done and Correct recording (MCP card)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9">Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9">Not Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FFFFFF" data-a-v="middle">Calcium distribution</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Done and Correct recording (MCP card)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFFFF">Not Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'cal_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">Albendazole distribution</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9">Done and Correct recording (MCP card)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'corr_rec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9">Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9">Not Done</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'albz_dis!', 'not_done', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>ANC due list available</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'anc_duelist', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'anc_duelist!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'anc_duelist', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'anc_duelist!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'anc_duelist', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'anc_duelist!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'anc_duelist', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'anc_duelist!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">Pregnancy trimester</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">First</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'first', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'first', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'first', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'first', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'first', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'first', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'first', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'first', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Second</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Third</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'third', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'third', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'third', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'third', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'third', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'third', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri', 'third', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'preg_tri!', 'third', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Folic Acid Tablet distribution (if PW in first trimester)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'fa_tab', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'fa_tab!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'fa_tab', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'fa_tab!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'fa_tab', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'fa_tab!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'fa_tab', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'fa_tab!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">LMP Date mentioned in MCP card</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lmp_date', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lmp_date!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lmp_date', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lmp_date!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lmp_date', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lmp_date!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lmp_date', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lmp_date!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Any instruction for consumption of IFA provided</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_ins', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_ins!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_ins', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_ins!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_ins', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_ins!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_ins', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ifa_ins!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Any instruction for consumption of calcium provided</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'calc_ins', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'calc_ins!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'calc_ins', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'calc_ins!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'calc_ins', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'calc_ins!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'calc_ins', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'calc_ins!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" colspan=2 height="19" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-a-v="middle">Service Delivery &amp; Recording - Children with SAM</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>SAM child duelist available at session site</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_duelist', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_duelist!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_duelist', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_duelist!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_duelist', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_duelist!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_duelist', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_duelist!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>SAM children received service at session site</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited>', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited<', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Record available for SAM case(s) received service today on CMAM app</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited_cmam', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited_cmam!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited_cmam', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited_cmam!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited_cmam', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited_cmam!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited_cmam', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_alr_visited_cmam!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Any SAM child available at session site at time of visit</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<!--------Complete 04.11.2024--------------->
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle data-a-v="middle">Type of visit of SAM case(s)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>First checkup</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'f_ckp', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'f_ckp', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'f_ckp', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'f_ckp', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'f_ckp', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'f_ckp', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'f_ckp', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'f_ckp', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Follow-up visit</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'fup_visit', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'fup_visit', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'fup_visit', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'fup_visit', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'fup_visit', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'fup_visit', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'fup_visit', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'fup_visit', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Both First checkup &amp; Follow-up visit cases</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'both', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'both', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'both', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'both', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'both', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'both', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam', 'both', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_sam!', 'both', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=6 height="133" style="text-align: left;" valign=middle data-a-v="middle">Services provided to SAM case</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Weighing</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__wh', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__wh!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__wh', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__wh!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__wh', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__wh!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__wh', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__wh!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Height / Length</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__hl', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__hl!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__hl', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__hl!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__hl', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__hl!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__hl', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__hl!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Treatment- provided medicines/micronutrients as per protocols</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__treatment', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__treatment!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__treatment', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__treatment!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__treatment', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__treatment!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__treatment', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__treatment!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Nutritional counselling</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nc', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nc!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nc', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nc!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nc', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nc!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nc', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nc!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>NRC Referral</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nrc_ref', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nrc_ref!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nrc_ref', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nrc_ref!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nrc_ref', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nrc_ref!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nrc_ref', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__nrc_ref!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>No service provided</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__no_ser', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__no_ser!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__no_ser', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__no_ser!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__no_ser', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__no_ser!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__no_ser', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'serv_sam__no_ser!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=7 height="133" style="text-align: left;" valign=middle data-a-v="middle">Medicines provided in first check-up</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Amoxyicillin syrup/tablet</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__anti', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__anti!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__anti', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__anti!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__anti', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__anti!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__anti', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__anti!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Folic acid tablet</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__foli', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__foli!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__foli', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__foli!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__foli', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__foli!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__foli', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__foli!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Albendazole</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__albe', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__albe!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__albe', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__albe!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__albe', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__albe!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__albe', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__albe!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Vitamin A</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__vita', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__vita!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__vita', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__vita!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__vita', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__vita!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__vita', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__vita!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Iron Folic Acid syrup</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__iron', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__iron!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__iron', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__iron!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__iron', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__iron!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__iron', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__iron!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Multivitamin syrup</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__multi', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__multi!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__multi', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__multi!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__multi', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__multi!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__multi', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__multi!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Not provided</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__no_med', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__no_med!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__no_med', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__no_med!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__no_med', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__no_med!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__no_med', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'med_sam__no_med!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" colspan=2 height="19" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-a-v="middle">Service Delivery - 0-5 month old infant</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle>
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Any 0-5 month infant available at session site at time of visit</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_05_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_05_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_05_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_05_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_05_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_05_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_05_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_05_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle>
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Has AWW/ANM measured Weight of the infant</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_weight', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_weight!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_weight', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_weight!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_weight', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_weight!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_weight', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_weight!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle>
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Has AWW/ANM measured Length of the infant</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_length', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_length!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_length', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_length!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_length', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_length!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_length', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_length!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle>
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Has AWW updated records weight/length in poshan tracker</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_pt_rec', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_pt_rec!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_pt_rec', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_pt_rec!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_pt_rec', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_pt_rec!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_pt_rec', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_pt_rec!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle>
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Growth Monitoring chart in MCP card updated</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_gc', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_gc!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_gc', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_gc!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_gc', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_gc!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_gc', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_gc!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle>
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Is AWW/ ANM informing caregiver about the current nutrition status using mcp card</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_curr_ns', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_curr_ns!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_curr_ns', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_curr_ns!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_curr_ns', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_curr_ns!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_curr_ns', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_curr_ns!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="20" style="text-align: left;" valign=middle>
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Is ANM enquring about current/ recent episode of illness</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_enq', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_enq!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_enq', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_enq!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_enq', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_enq!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_enq', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_enq!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle>
								<font size=3><br>
							</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Is ANM/AWW/ASHA providing counselling on exclusive breastfeeding</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf_coun', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf_coun!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf_coun', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf_coun!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf_coun', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf_coun!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf_coun', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf_coun!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" colspan=2 height="19" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-a-v="middle">Observation and Interview caregivers - 0-5 month old infant</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=2 height="38" style="text-align: left;" valign=middle data-a-v="middle">Infant Sex</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Male</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex', 'male', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex!', 'male', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex', 'male', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex!', 'male', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex', 'male', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex!', 'male', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex', 'male', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex!', 'male', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Female</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex', 'female', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex!', 'female', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex', 'female', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex!', 'female', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex', 'female', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex!', 'female', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex', 'female', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_sex!', 'female', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=2 height="38" style="text-align: left;" valign=middle data-a-v="middle">Infant Delivery Type</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Normal</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del', 'normal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del!', 'normal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del', 'normal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del!', 'normal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del', 'normal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del!', 'normal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del', 'normal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del!', 'normal', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>C-Section</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del', 'c_sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del!', 'c_sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del', 'c_sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del!', 'c_sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del', 'c_sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del!', 'c_sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del', 'c_sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_del!', 'c_sec', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=2 height="38" style="text-align: left;" valign=middle data-a-v="middle">Birth weight</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>&gt;2.5 kg</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw>', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw!', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw>', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw!', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw>', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw!', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw>', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw!', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>&lt;2.5 kg</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw<', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw!', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw<', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw!', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw<', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw!', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw<', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bw!', '2.5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=5 height="95" style="text-align: left;" valign=middle data-a-v="middle">Birth order</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="1" sdnum="1033;">1</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="2" sdnum="1033;">2</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="3" sdnum="1033;">3</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="4" sdnum="1033;">4</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="5" sdnum="1033;">5</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_bo!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=5 height="95" style="text-align: left;" valign=middle data-a-v="middle">When did you first breastfed your child ?</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Within 1 hour of Delivery</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'within_1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'within_1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'within_1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'within_1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'within_1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'within_1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'within_1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'within_1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>1-3 hour of Delivery</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'within1_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'within1_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'within1_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'within1_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'within1_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'within1_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'within1_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'within1_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>After 3 hours same day</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>next day of Delivery</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'gr_3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Can't remember</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'cant_rem', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'cant_rem', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'cant_rem', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'cant_rem', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'cant_rem', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'cant_rem', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf', 'cant_rem', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_eibf!', 'cant_rem', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=7 height="133" style="text-align: left;" valign=middle data-a-v="middle">What did you give your baby in the last 24 hours?</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Only Breastmilk</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'only_bm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'only_bm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'only_bm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'only_bm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'only_bm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'only_bm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'only_bm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'only_bm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Breastmilk &amp; Formula Milk</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Breastmilk &amp; Animal_milk</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Breastmilk &amp; water</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_water', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_water', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_water', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_water', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_water', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_water', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'bm_water', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'bm_water', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Formula Milk</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'fm', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Animal_milk</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'am', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Nothing</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'nothing', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'nothing', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'nothing', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'nothing', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'nothing', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'nothing', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf', 'nothing', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'inf_ebf!', 'nothing', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=5 height="95" style="text-align: left;" valign=middle data-a-v="middle">How many times AWW visited infant in last one month</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="1" sdnum="1033;">1</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="2" sdnum="1033;">2</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="3" sdnum="1033;">3</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="4" sdnum="1033;">4</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="5" sdnum="1033;">5</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'aww_vis!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=5 height="95" style="text-align: left;" valign=middle data-a-v="middle">How many times ASHA visited infant in last one month</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="1" sdnum="1033;">1</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="2" sdnum="1033;">2</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '2', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="3" sdnum="1033;">3</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '3', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="4" sdnum="1033;">4</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '4', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle sdval="5" sdnum="1033;">5</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'asha_vis!', '5', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" colspan=2 height="19" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-a-v="middle">Observation and Interview caregivers - 9-12 month old infant</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Any 9-24 month child available at session site at time of visit</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_924_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_924_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_924_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_924_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_924_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_924_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_924_aval', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'any_924_aval!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=2 height="38" style="text-align: left;" valign=middle data-a-v="middle">Type of immunization Visit of 9-24 months child</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>9 month(Measles Rubella)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm', 'mon_9', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm!', 'mon_9', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm', 'mon_9', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm!', 'mon_9', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm', 'mon_9', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm!', 'mon_9', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm', 'mon_9', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm!', 'mon_9', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>16-24 month(DPT booster)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm', 'mon_16', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm!', 'mon_16', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm', 'mon_16', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm!', 'mon_16', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm', 'mon_16', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm!', 'mon_16', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm', 'mon_16', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'type_imm!', 'mon_16', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Has child received IFA syrup bottle</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Has your child consumed IFA in last one week?</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa_con', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa_con!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa_con', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa_con!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa_con', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa_con!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa_con', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_ifa_con!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Was child weighed during the session</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Weight updated in MCP card</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt_mcp', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt_mcp!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt_mcp', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt_mcp!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt_mcp', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt_mcp!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt_mcp', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ch_924_wt_mcp!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" colspan=2 height="19" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-a-v="middle">ANM Knowledge Interview</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle bgcolor="#FFFFFF"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Have you received any training on management of children with SAM?</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_trg', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_trg!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_trg', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_trg!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_trg', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_trg!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_trg', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_trg!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=4 height="76" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">Knowledge - What is essential to assess SAM/MAM in children at the AWC?</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Weight and length/ height</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'wflh', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'wflh', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'wflh', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'wflh', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'wflh', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'wflh', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'wflh', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'wflh', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">length/ height and age</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'lha', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'lha', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'lha', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'lha', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'lha', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'lha', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'lha', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'lha', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Weight and age</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'wa', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'wa', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'wa', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'wa', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'wa', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'wa', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'wa', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'wa', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Don't know</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_iden!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">Bilateral Pitting Oedema Test</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">AWW able to demonstrate how to check for bilateral pitting oedema</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_blp', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_blp!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_blp', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_blp!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_blp', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_blp!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_blp', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_blp!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle data-a-v="middle">Knowledge - medical treatment for children with SAM if s/he has no illness and with good apetite test</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Aware</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_no_illness', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_no_illness!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_no_illness', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_no_illness!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_no_illness', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_no_illness!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_no_illness', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_no_illness!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=7 height="133" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">ANM knowledge - name the essential medicines/ nutritional supplements given to SAM case treated at the community level</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Antibiotic (Amoxicillin syrup)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__anti', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__anti!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__anti', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__anti!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__anti', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__anti!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__anti', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__anti!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Folic acid tablet</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__foli', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__foli!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__foli', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__foli!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__foli', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__foli!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__foli', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__foli!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Albendazole</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__albe', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__albe!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__albe', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__albe!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__albe', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__albe!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__albe', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__albe!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Vitamin A</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__vita', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__vita!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__vita', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__vita!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__vita', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__vita!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__vita', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__vita!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Iron Folic Acid syrup</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__iron', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__iron!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__iron', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__iron!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__iron', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__iron!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__iron', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__iron!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Multivitamin syrup</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__multi', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__multi!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__multi', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__multi!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__multi', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__multi!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__multi', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__multi!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Not aware</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_treatment_med__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle data-a-v="middle">Knowledge - medical treatment shall a SAM case will receive if he/she has illness or with poor appetite</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Aware</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_illness', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_illness!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_illness', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_illness!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_illness', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_illness!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_illness', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_illness!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=4 height="76" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">ANM knowledge - how is SAM child's progress followed up during the programme period?</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Weekly/ fortnightly home visit</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__week', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__week!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__week', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__week!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__week', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__week!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__week', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__week!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Monthly VHSND/HWC visit</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__mont', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__mont!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__mont', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__mont!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__mont', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__mont!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__mont', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__mont!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">AWC based session</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__awc_session', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__awc_session!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__awc_session', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__awc_session!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__awc_session', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__awc_session!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__awc_session', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__awc_session!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Don't know</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_prog__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=4 height="76" style="text-align: left;" valign=middle data-a-v="middle">ANM knowledge - action if the child does not gain weight or loses weight in two consecutive follow-ups visits</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>VHSND session for community management</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'vhsnd_session', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'vhsnd_session', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'vhsnd_session', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'vhsnd_session', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'vhsnd_session', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'vhsnd_session', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'vhsnd_session', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'vhsnd_session', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>NRC</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Nearest health facility (PHC/ CHC)/ RBSK</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'chc_phc_rbsk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'chc_phc_rbsk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'chc_phc_rbsk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'chc_phc_rbsk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'chc_phc_rbsk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'chc_phc_rbsk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'chc_phc_rbsk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'chc_phc_rbsk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Don't know</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_gain!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=8 height="152" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">ANM knowledge - mention activities are being done during a follow-up session (VHSND or Home visit) of child with SAM?</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Weighing</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__weighing', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__weighing!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__weighing', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__weighing!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__weighing', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__weighing!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__weighing', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__weighing!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Monitor weight improvement</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__moni_wt', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__moni_wt!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__moni_wt', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__moni_wt!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__moni_wt', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__moni_wt!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__moni_wt', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__moni_wt!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Checking for signs of illness</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_ill_sign', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_ill_sign!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_ill_sign', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_ill_sign!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_ill_sign', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_ill_sign!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_ill_sign', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_ill_sign!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Checking compliance for medicine</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_med_comp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_med_comp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_med_comp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_med_comp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_med_comp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_med_comp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_med_comp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_med_comp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Checking compliance for recommended diet</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_rd', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_rd!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_rd', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_rd!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_rd', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_rd!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_rd', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__check_rd!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Counselling - diet and hygiene</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__couns_hyg_diet', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__couns_hyg_diet!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__couns_hyg_diet', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__couns_hyg_diet!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__couns_hyg_diet', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__couns_hyg_diet!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__couns_hyg_diet', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__couns_hyg_diet!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Maintaining record</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__rec_maintain', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__rec_maintain!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__rec_maintain', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__rec_maintain!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__rec_maintain', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__rec_maintain!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__rec_maintain', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__rec_maintain!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Don't know</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle bgcolor="#FFFFFF"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>AWW knowledge - time period for a SAM child to be followed up and continued in programme</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup_dura', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup_dura!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup_dura', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup_dura!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup_dura', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup_dura!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup_dura', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_fup_dura!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9" data-a-v="middle">AWW knowledge - Action if a child continues to remain in SAM category even after 3 months follow up period in CMAM<br>program?</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Refer to NRC/Health Facility</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'refer_nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'refer_nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'refer_nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'refer_nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'refer_nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'refer_nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'refer_nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'refer_nrc', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Continue in the program with intensive follow-up</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'continue_int_fup', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'continue_int_fup', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'continue_int_fup', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'continue_int_fup', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'continue_int_fup', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'continue_int_fup', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'continue_int_fup', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'continue_int_fup', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FDE9D9" data-a-h="left" data-fill-color="FDE9D9">Don’t know</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'sam_not_res!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" colspan=2 height="19" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00" data-a-v="middle">ASHA Knowledge Interview</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle bgcolor="#FFFF00" data-fill-color="FFFF00"><br></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=4 height="76" style="text-align: left;" valign=middle data-a-v="middle">According to you when will be a newborn considered as Low Birth Weight(LBW)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>&lt; 1800 Gms</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_1800', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_1800', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_1800', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_1800', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_1800', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_1800', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_1800', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_1800', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>&lt; 2000 Gms</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_2000', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_2000', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_2000', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_2000', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_2000', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_2000', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_2000', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_2000', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>&lt; 2500 Gms</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_2500', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_2500', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_2500', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_2500', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_2500', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_2500', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'less_2500', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'less_2500', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Don't Know</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_confirm!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=7 height="133" style="text-align: left;" valign=middle data-a-v="middle">What services do you provide during HBNC visit</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Breastfeeding support</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__bf_support', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__bf_support!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__bf_support', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__bf_support!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__bf_support', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__bf_support!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__bf_support', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__bf_support!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>KMC</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__kmc', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__kmc!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__kmc', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__kmc!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__kmc', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__kmc!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__kmc', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__kmc!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Temperature</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__temp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__temp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__temp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__temp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__temp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__temp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__temp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__temp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Additional visit</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__add_visit', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Weighing</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__weighing', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__weighing!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__weighing', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__weighing!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__weighing', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__weighing!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__weighing', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__weighing!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Referral if required</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__referral', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__referral!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__referral', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__referral!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__referral', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__referral!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__referral', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__referral!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Not aware</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__not_aware', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__not_aware!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__not_aware', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__not_aware!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__not_aware', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__not_aware!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__not_aware', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'lbw_services__not_aware!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=7 height="133" style="text-align: left;" valign=middle data-a-v="middle">What services do you provide to an infant aged six months during HBYC visit</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Breastfeeding support</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__bf_supp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__bf_supp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__bf_supp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__bf_supp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__bf_supp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__bf_supp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__bf_supp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__bf_supp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Handwashing</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__hw', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__hw!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__hw', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__hw!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__hw', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__hw!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__hw', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__hw!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Parenting support</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__ps', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__ps!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__ps', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__ps!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__ps', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__ps!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__ps', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__ps!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Asssess developmental milestones</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__adm', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__adm!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__adm', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__adm!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__adm', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__adm!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__adm', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__adm!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Looking for signs of illness (Pneumonia &amp; Diaorrhea)</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__sign_illness', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__sign_illness!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__sign_illness', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__sign_illness!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__sign_illness', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__sign_illness!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__sign_illness', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__sign_illness!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Show growth chart on MCP card</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__gc_mcp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__gc_mcp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__gc_mcp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__gc_mcp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__gc_mcp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__gc_mcp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__gc_mcp', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__gc_mcp!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Not aware</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__dnk', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ser_inf__dnk!', '1', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" rowspan=3 height="57" style="text-align: left;" valign=middle data-a-v="middle">To whom is IFA syrup provided?</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>All children aged 6-59 months</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'all_child', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'all_child', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'all_child', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'all_child', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'all_child', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'all_child', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'all_child', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'all_child', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Only SAM child</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'only_sam', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'only_sam', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'only_sam', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'only_sam', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'only_sam', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'only_sam', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'only_sam', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'only_sam', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Not aware</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'ben_ifa!', 'dnk', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="19" style="text-align: left;" valign=middle data-a-v="middle">What are the doses of IFA syrup in children</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Aware</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'dose_ifa', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'dose_ifa!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'dose_ifa', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'dose_ifa!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'dose_ifa', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'dose_ifa!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'dose_ifa', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'dose_ifa!', 'aware', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
						</tr>
						<tr>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" height="38" style="text-align: left;" valign=middle data-a-v="middle">Has ASHA organised any mother's meeting (MAA) in the previous mont</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" style="text-align: left;" valign=middle>Yes</td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', '`maa-meet`', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="46" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', '`maa-meet`!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="63.0434782608696" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', '`maa-meet`', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', '`maa-meet`!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', '`maa-meet`', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', '`maa-meet`!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>

							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="4" sdnum="1033;" data-a-h="center"><?= $n = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', '`maa-meet`', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head" sdval="6" sdnum="1033;" data-a-h="center"><?= $d = getcountVHSD($conn, 'amt67t3phss69dfpvrxqbf', 'id', '`maa-meet`!', 'yes', '', '') ?></td>
							<td data-b-a-s="thin" data-b-a-c="000000" class="td_head thead" data-f-bold="true" data-fill-color="C5D9F1" data-a-h="center" sdval="66.6666666666667" sdnum="1033;0;0.0"><?= round((($n * 100) / $d), 0) ?></td>
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
			name: "VHSD_analysis_report.xlsx", // Set your desired file name here
			sheet: {
				name: "VHSD Analysis Report" // Set the sheet name here
			}
		});
	});
</SCRIPT>

</html>