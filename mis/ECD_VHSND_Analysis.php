<?php include_once('includes/config.php'); ?>
<?php define("title", "ECD VHSND Analysis | UNICEF"); ?>
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
			$qry .= " AND date(_submission_time) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
		}
	} else {

		$qry .= " and date(_submission_time) like'" . $qryfield1 . "%'";
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
	$qry .= " AND date(_submission_time) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
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
				$qry .= " AND date(_submission_time) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		} else {

			$qry .= " and date(_submission_time) like'" . $qryfield1 . "%'";
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
		$qry .= " AND date(_submission_time) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
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
				$qry .= " AND date(_submission_time) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		} else {

			$qry .= " and date(_submission_time) like'" . $qryfield1 . "%'";
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
		$qry .= " AND date(_submission_time) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
	}

	$qryv = '';
	if ($qryfield1 != '') {
		$qryv = " AND $qryfield1='$value1'";
	}
  
  $query = 'SELECT COUNT('.$field.') as total FROM '.$tablename.' WHERE '.$qryfield.'="'.$value.'" and district="'.$districtName.'" '.$qryv.' '.$qry;
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_object($result);

  return $row->total;
}

function getPercentage($numerator, $demonminator)
{
  $perTe = round((($numerator * 100) / $demonminator), 2);

  return $perTe . '%';
}

function getAverage($conn, $tablename, $districtName, $field)
{
  $qry = "";
	if (isset($_REQUEST['search'])) {
		if ($qryfield1 == '') {
			if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$startDate = new DateTime($_REQUEST['fromDate']);
				$endDate = new DateTime($_REQUEST['toDate']);
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(_submission_time) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		} else {

			$qry .= " and date(_submission_time) like'" . $qryfield1 . "%'";
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
		$qry .= " AND date(_submission_time) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
	}

	$qryv = '';
	if ($qryfield1 != '') {
		$qryv = " AND $qryfield1='$value1'";
	}
	
  $query = "SELECT AVG($field) as total FROM $tablename WHERE district='$districtName' $qryv $qry";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_object($result);
  $resultsAvg = round($row->total, 2);
  return $resultsAvg;
}

function getSum($conn, $tablename, $districtName, $field)
{
  $tablename = mysqli_real_escape_string($conn, $tablename);
  $field = mysqli_real_escape_string($conn, $field);
  $districtName = mysqli_real_escape_string($conn, $districtName);

  if (!empty($districtName)) {
    $query = "SELECT SUM($field) AS total FROM $tablename WHERE district = '$districtName'";
  } else {
    $query = "SELECT SUM($field) AS total FROM $tablename";
  }

  $result = mysqli_query($conn, $query);
  if (!$result) {
    return 0;
  }

  $row = mysqli_fetch_object($result);
  $totalSum = round($row->total ?? 0, 2);

  return $totalSum;
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
            <li class="breadcrumb-item active"><i class="fa fa-calandar"></i>ECD VSHND Analysis</li>
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
								$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from af6d7lsydnt4rgrr9qq9lp");
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
								$qryUserType = mysqli_query($conn, "SELECT DISTINCT monitor_name as type_mon FROM af6d7lsydnt4rgrr9qq9lp ORDER BY type_mon ASC");
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
      $tablename = 'af6d7lsydnt4rgrr9qq9lp';
      $districtName = 'araria';
      $districtName1 = 'purnea';

      $totalAlls = getcountAlls($conn, $tablename, 'id', 'id>', 0);
      $totalAraria = getcountAlls($conn, $tablename, 'id', 'district', $districtName);
      $totalPurnea = getcountAlls($conn, $tablename, 'id', 'district', $districtName1);

      $totalSampleinfo = array();
      $totalSampleinfo1 = array();


      $sqlQrySample = mysqli_query($conn, "select district,center_type,count(*) as total from awmmsfdaueupsgpjtspyeu where center_type in('Intervention','Non Intervention') group by district,center_type order by district,center_type asc");
      while ($dataQrySample = mysqli_fetch_object($sqlQrySample)) {
        $totalSampleinfo[$dataQrySample->district][$dataQrySample->center_type] = $dataQrySample->total;
      }

      $sqlQrySample1 = mysqli_query($conn, "select district,center_type,count(*) as total from awmmsfdaueupsgpjtspyeu where ecd_friendly='Yes' and center_type in('Intervention','Non Intervention') group by district,center_type order by district,center_type asc");
      while ($dataQrySample1 = mysqli_fetch_object($sqlQrySample1)) {
        $totalSampleinfo1[$dataQrySample1->district][$dataQrySample1->center_type] = $dataQrySample1->total;
      }
      ?>

      <section class="panel p-1">
        <div class="table-responsive">

          <table id="simpleTable1" data-cols-width="5,50,10,10,10,5,10" class="report" cellpadding="0" cellspacing="0" role="table" aria-label="SNCU monitoring table">
            <tr>
              <td colspan="8" data-b-a-s="thick" data-fill-color="FFd7a7a7" data-a-h="center" data-f-sz="20" bgcolor="#d7a7a7" data-f-bold="true">
                <div align="center"><b>Analysis of VHSND (Village Health Sanitation and Nutrition Day)</b></div>
              </td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>total</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin">No of entries</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAlls) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalAlls) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ctotal = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($ctotal, $totalAlls) ?></td>
            </tr>
            <tr>
              <td colspan="8" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8"><b>Item availability details at VHSND Site</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Item Name</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% availability in purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% availability in araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total Number of sites</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Availability Percentage</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">mcp_card</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__mcp_card', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__mcp_card', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">ifa_tab</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__ifa_tab', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__ifa_tab', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">calcium_tab</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__calcium_tab', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__calcium_tab', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">ifa_syrup</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__ifa_syrup', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__ifa_syrup', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">hemoglobinometer</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__hemoglobinometer', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__hemoglobinometer', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">ors</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__ors', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__ors', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">zinc</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__zinc', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__zinc', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">antibiotic</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__antibiotic', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__antibiotic', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">baby_weighing_scale</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__baby_weighing_scale', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__baby_weighing_scale', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">adult_weighing_scale</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__adult_weighing_scale', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__adult_weighing_scale', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">stadiometer</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__stadiometer', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__stadiometer', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">bp_machine</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__bp_machine', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__bp_machine', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">thermometer</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__thermometer', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__thermometer', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">vaccines</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__vaccines', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__vaccines', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">vitamin_syrup</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__vitamin_syrup', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__vitamin_syrup', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">deworming_tab</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__deworming_tab', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__deworming_tab', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">strips</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__strips', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__strips', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">lancets</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'item_available__lancets', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'item_available__lancets', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td colspan="8" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8"><b>Availability of frontline worker</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Anganwadi worker</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of availability in purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>%of availability In araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total number of sites</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Availability Percentage</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Anganwadi</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'aww_present', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'aww_present', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Asha</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'asha_present', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'asha_present', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td colspan="8" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8"><b>Availability of facilities during the session</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Facility</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of availability in purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>%of availability In araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total number of sites</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Availability Percentage</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Toilet</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'toilet', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'toilet', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Handwash</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'hand_washing', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'hand_washing', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Drinking Water</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td colspan="8" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Pregnant Women Analysis</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average total population coverage</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getAverage($conn, $tablename, $districtName1, 'total_population') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getAverage($conn, $tablename, $districtName, 'total_population') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((($num_pr + $num_ar) / 2), 2) ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average of number of pregnant women</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr1 = getAverage($conn, $tablename, $districtName1, 'total_pregnant_women') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar1 = getAverage($conn, $tablename, $districtName, 'total_pregnant_women') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_avg1 = round((($num_pr1 + $num_ar1) / 2), 2) ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">average number Pregnant women attended VHSND</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr2 = getAverage($conn, $tablename, $districtName1, 'pregnant_women_count') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr2, $num_pr1) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getAverage($conn, $tablename, $districtName, 'pregnant_women_count') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $num_ar1) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_avg = round((($num_pr2 + $num_ar) / 2), 2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_avg, $num_avg1) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average number of pregnant women who attended had MCP cards</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr1 = getAverage($conn, $tablename, $districtName1, 'mcp_card') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr1, $num_pr2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar1 = getAverage($conn, $tablename, $districtName, 'mcp_card') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar1, $num_ar) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_avg1 = round((($num_pr1 + $num_ar1) / 2), 2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_avg1, $num_avg) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average count of Weight recorded of pregnant women who attended the session</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr1 = getAverage($conn, $tablename, $districtName1, 'weight_recorded') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr1, $num_pr2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar1 = getAverage($conn, $tablename, $districtName, 'weight_recorded') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar1, $num_ar) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_avg1 = round((($num_pr1 + $num_ar1) / 2), 2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_avg1, $num_avg) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average number of IFA Tablets given to pregnant women/ tablet given per women</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr1 = getAverage($conn, $tablename, $districtName1, 'ifa_given_pregnant_prev') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round(($num_pr1 / getAverage($conn, $tablename, $districtName1, 'pregnant_women_count')), 2); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar1 = getAverage($conn, $tablename, $districtName, 'ifa_given_pregnant_prev') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round(($num_ar1 / getAverage($conn, $tablename, $districtName, 'pregnant_women_count')), 2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_avg1 = round((($num_pr1 + $num_ar1) / 2), 2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((($num_pr1 + $num_ar1) / (getAverage($conn, $tablename, $districtName1, 'pregnant_women_count') + getAverage($conn, $tablename, $districtName, 'pregnant_women_count'))), 2) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average number of calcium Tablets given to pregnant women/ tablet given per women</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr1 = getAverage($conn, $tablename, $districtName1, 'calcium_given_pregnant_prev') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round(($num_pr1 / getAverage($conn, $tablename, $districtName1, 'pregnant_women_count')), 2); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar1 = getAverage($conn, $tablename, $districtName, 'calcium_given_pregnant_prev') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round(($num_ar1 / getAverage($conn, $tablename, $districtName, 'pregnant_women_count')), 2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_avg1 = round((($num_pr1 + $num_ar1) / 2), 2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((($num_pr1 + $num_ar1) / (getAverage($conn, $tablename, $districtName1, 'pregnant_women_count') + getAverage($conn, $tablename, $districtName, 'pregnant_women_count'))), 2) ?></td>
            </tr>
            <tr>
              <td colspan="8" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8"><b>SAM data</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% in purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% in araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total number of sites</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total %</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">SAM Presnet in VHSND</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_vsnd, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="font-weight: bold; text-align:left;">Screening undertaken in SAM children</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">All SAM childern</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_screening', 'all_sam_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_screening', 'all_sam_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes'))), 2) ?>%</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'sam_screening', 'all_sam_children'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'sam_screening', 'all_sam_children'), getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Few Sam Children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_screening', 'few_sam_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_screening', 'few_sam_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes'))), 2) ?>%</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'sam_screening', 'few_sam_children'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'sam_screening', 'few_sam_children'), getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Not Done</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_screening', 'not_done') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_screening', 'few_sam_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes'))), 2) ?>%</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'sam_screening', 'few_sam_children'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'sam_screening', 'few_sam_children'), getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="font-weight: bold; text-align:left;">Other signs (Odema) assessed in SAM children</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">All SAM childern</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'other_symptoms', 'all_sam_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'other_symptoms', 'all_sam_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes'))), 2) ?>%</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'other_symptoms', 'all_sam_children'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'other_symptoms', 'all_sam_children'), getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Few Sam Children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'other_symptoms', 'few_sam_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'other_symptoms', 'few_sam_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes'))), 2) ?>%</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'other_symptoms', 'few_sam_children'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'other_symptoms', 'few_sam_children'), getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Not Done</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'other_symptoms', 'not_done') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'other_symptoms', 'not_done') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes'))), 2) ?>%</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'other_symptoms', 'not_done'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'other_symptoms', 'not_done'), getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="font-weight: bold; text-align:left;">Caregivers were advised about proper nutrition in case of childs poor weight or growth</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">All Children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'counselling_nutrition', 'all_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'counselling_nutrition', 'all_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes'))), 2) ?>%</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'counselling_nutrition', 'all_children'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'counselling_nutrition', 'all_children'), getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Few Children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'counselling_nutrition', 'few_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'counselling_nutrition', 'few_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes'))), 2) ?>%</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'counselling_nutrition', 'few_children'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'counselling_nutrition', 'few_children'), getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Not Done</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'counselling_nutrition', 'not_done') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'counselling_nutrition', 'not_done') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= round((getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'sam_in_vhsnd', 'yes'))), 2) ?>%</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'counselling_nutrition', 'not_done'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'counselling_nutrition', 'not_done'), getcountAlls($conn, $tablename, 'id', 'sam_in_vhsnd', 'yes')) ?></td>
            </tr>
            <tr>
              <td colspan="8" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8"><b>Developemental Milestone</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% in purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% in araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total number of sites</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total %</b></td>
            </tr>
            <tr>
              <td colspan="8" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8" style="text-align:left;"><b>Caregivers were informed about developmental milestones of their children</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">All caregivers</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'developmental_milestone', 'all_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'developmental_milestone', 'all_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'developmental_milestone', 'all_children'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'developmental_milestone', 'all_children'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Few caregivers</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'developmental_milestone', 'few_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'developmental_milestone', 'few_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'developmental_milestone', 'few_children'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'developmental_milestone', 'few_children'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">None</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'developmental_milestone', 'not_done') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'developmental_milestone', 'not_done') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'developmental_milestone', 'not_done'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'developmental_milestone', 'not_done'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" colspan="7" bgcolor="#e8e8e8" style="text-align:left;">Developmental delays</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Developmental Delays noticed in atleast one child in VHSND</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'developmental_delay', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'developmental_delay', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'developmental_delay', 'yes'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'developmental_delay', 'yes'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">at least one child referred for further management</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'developmental_delay_management', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'developmental_delay', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'developmental_delay_management', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'developmental_delay', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'developmental_delay_management', 'yes'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'developmental_delay_management', 'yes'), getcountAlls($conn, $tablename, 'id', 'developmental_delay', 'yes')) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">No child referred</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'developmental_delay_management', 'no') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'developmental_delay', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'developmental_delay_management', 'no') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, getcountDistrictWise($conn, $tablename, $districtName, 'id', 'developmental_delay', 'yes')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'developmental_delay_management', 'no'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'developmental_delay_management', 'no'), getcountAlls($conn, $tablename, 'id', 'developmental_delay', 'yes')) ?></td>
            </tr>
            <tr>
              <td colspan="8" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8" style="text-align:left;"><b>IEC Material availbility at VHSND site</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>IEC Material</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% availability in purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% availability in araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total Number of sites</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Availability Percentage</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">AMB</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'iec_materials_available__amb', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'iec_materials_available__amb', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'iec_materials_available__amb', '1'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'iec_materials_available__amb', '1'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">CMAM</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'iec_materials_available__cmam', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'iec_materials_available__cmam', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'iec_materials_available__cmam', '1'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'iec_materials_available__cmam', '1'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">ECD</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'iec_materials_available__ecd', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'iec_materials_available__ecd', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'iec_materials_available__ecd', '1'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'iec_materials_available__ecd', '1'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">IYCF</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'iec_materials_available__iycf', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'iec_materials_available__iycf', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'iec_materials_available__iycf', '1'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'iec_materials_available__iycf', '1'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td colspan="8" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8"><b>Haemoglobin Testing Method at VHSND site</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Method</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% availability in purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% availability in araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total Number of sites</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Availability Percentage</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Haemoglobinometer</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'hemoglobin_testing_method', 'haemoglobinometer') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'hemoglobin_testing_method', 'haemoglobinometer') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'hemoglobin_testing_method', 'haemoglobinometer'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'hemoglobin_testing_method', 'haemoglobinometer'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Colored Scale</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'hemoglobin_testing_method', 'colored_scale') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'hemoglobin_testing_method', 'colored_scale') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'hemoglobin_testing_method', 'colored_scale'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'hemoglobin_testing_method', 'colored_scale'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Sahli's method</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'hemoglobin_testing_method', "sahli's_method") ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'hemoglobin_testing_method', "sahli's_method") ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'hemoglobin_testing_method', "sahli's_method"); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'hemoglobin_testing_method', "sahli's_method"), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Not Done</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'hemoglobin_testing_method', 'not_done') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'hemoglobin_testing_method', 'not_done') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_vsnd = getcountAlls($conn, $tablename, 'id', 'hemoglobin_testing_method', 'not_done'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(getcountAlls($conn, $tablename, 'id', 'hemoglobin_testing_method', 'not_done'), (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td colspan="7" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8"><b>ACTIVITIES</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>VHSND SITE ACTIVITIES</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% in Purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% in Araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;" colspan="2"><b>Total</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">No of children suffering from diarrhoea who were given ors zinc</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getSum($conn, $tablename, $districtName1, 'ors_zink_given') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getSum($conn, $tablename, '', 'ors_zink_given')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getSum($conn, $tablename, $districtName, 'ors_zink_given') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, getSum($conn, $tablename, '', 'ors_zink_given')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;" colspan="2"><?= $total_vsnd = getSum($conn, $tablename, '', 'ors_zink_given'); ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">No of children who were fully immunized (12-23 months)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getSum($conn, $tablename, $districtName1, 'full_immunization') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getSum($conn, $tablename, '', 'full_immunization')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getSum($conn, $tablename, $districtName, 'full_immunization') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, getSum($conn, $tablename, '', 'full_immunization')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;" colspan="2"><?= $total_vsnd = getSum($conn, $tablename, '', 'full_immunization'); ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">No of children with pneumonia who were reffered/treated</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getSum($conn, $tablename, $districtName1, 'pneumonia_treatment') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getSum($conn, $tablename, '', 'pneumonia_treatment')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getSum($conn, $tablename, $districtName, 'pneumonia_treatment') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, getSum($conn, $tablename, '', 'pneumonia_treatment')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;" colspan="2"><?= $total_vsnd = getSum($conn, $tablename, '', 'pneumonia_treatment'); ?></td>
            </tr>
          </table>
        </div>
      </section>
    </div>
  </section>
</section>
<!-- ************************************************************************** -->
<?php include_once('includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/table-to-excel@1.1.0/dist/tableToExcel.min.js"></script>

<SCRIPT>
  let button = document.querySelector("#button-excel");

  button.addEventListener("click", (e) => {
    let table = document.querySelector("#simpleTable1");
    TableToExcel.convert(table, {
      name: "ECD_VHSND_Analysis.xlsx", // Set your desired file name here
      sheet: {
        name: "ECD VHSND Analysis" // Set the sheet name here
      }
    });
  });
</SCRIPT>

</html>