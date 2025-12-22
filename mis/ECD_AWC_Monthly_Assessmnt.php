<?php include_once('includes/config.php'); ?>
<?php define("title", "ECD AWC Monthly Assessmnt | UNICEF"); ?>
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


// function getcountAWC($conn, $tablename, $field, $qryfield, $value)
// {
//   $qry = "";


//   if (isset($_REQUEST['search'])) {
//     if ($qryfield1 == '') {
//       if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
//         $startDate = new DateTime($_REQUEST['fromDate']);
//         $endDate = new DateTime($_REQUEST['toDate']);
//         //$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
//         $qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
//       }
//     } else {

//       $qry .= " and date(dom) like'" . $qryfield1 . "%'";
//     }

//     if (isset($_REQUEST['district_code']) && !empty($_REQUEST['district_code'])) {
//       $districtType = array_filter($_REQUEST['district_code']); // Remove empty values
//       if (!empty($districtType)) {
//         $districtTypeList = "'" . implode("','", $districtType) . "'"; // Convert to integer to prevent SQL injection
//         $qry .= " AND district IN ($districtTypeList)";
//       }
//     }
//     if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {

//       $userTypes = array_filter($_REQUEST['uType']); // Remove empty values
//       //print_r($userTypes);
//       if (!empty($userTypes)) {
//         $userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
//         $qry .= " AND type_mon IN ($userTypeList)";
//       }
//     }
//   } else {
//     $startDate = new DateTime(date('Y-m-01'));
//     $endDate = new DateTime(date('Y-m-d'));
//     //$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
//     $qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
//   }

//   $qryv = '';
//   /*if ($qryfield1 != '') {
//         $qryv = " AND $qryfield1='$value1'";
//     }*/

//   $query = "SELECT COUNT(DISTINCT($field)) as total FROM $tablename WHERE $qryfield='$value' $qry";
//   $result = mysqli_query($conn, $query);
//   $row = mysqli_fetch_object($result);

//   return $row->total;
// }

function getcountAWC($conn, $tablename, $field, $qryfield, $value)
{
  // Build simple query: count distinct values
  $query = "SELECT COUNT(DISTINCT($field)) AS total 
              FROM $tablename 
              WHERE $qryfield = '$value'";

  $result = mysqli_query($conn, $query);

  if (!$result) {
    die("Query failed: " . mysqli_error($conn));
  }

  $row = mysqli_fetch_object($result);

  return isset($row->total) ? $row->total : 0;
}


function getcountDistrictWise($conn, $tablename, $districtName, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{
  $qryv = '';
  if ($qryfield1 != '') {
    $qryv = " AND $qryfield1='$value1'";
  }
  $query = "SELECT COUNT($field) as total FROM $tablename WHERE $qryfield='$value' and district='$districtName' $qryv";
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

  $query = "SELECT AVG($field) as total FROM $tablename WHERE district='$districtName'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_object($result);

  return round($row->total, 2);
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
            <li class="breadcrumb-item active"><i class="fa fa-calandar"></i>ECD AWC Monthly Assessmnt</li>
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
                $qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from amt67t3phss69dfpvrxqbf");
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
                $qryUserType = mysqli_query($conn, "SELECT DISTINCT type_mon FROM amt67t3phss69dfpvrxqbf ORDER BY type_mon ASC");
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
              <a href="ECD_AWC_Monthly_Assessmnt.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
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
      $tablename = 'actddvhmpscwmcneaqozy2';
      $districtName = 'araria';
      $districtName1 = 'purnea';

      $totalAlls = getcountAll($conn, $tablename, 'id', 'id>', 0);
      $totalAraria = getcountAll($conn, $tablename, 'id', 'district', $districtName);
      $totalPurnea = getcountAll($conn, $tablename, 'id', 'district', $districtName1);

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

          <table id="simpleTable1" data-cols-width="30,30,10,10,10,10,10" class="report" cellpadding="0" cellspacing="0" role="table" aria-label="AWC Dashboard table" width="100%">
            <tr>
              <td colspan="7" data-b-a-s="thick" data-fill-color="FFd7a7a7" data-a-h="center" data-f-sz="20" bgcolor="#d7a7a7" data-f-bold="true">
                <div align="center"><b>Dashboard - Monthly Assessment of Anganwadi Centers (AWC)</b></div>
              </td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>purnea</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>%</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>araria</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>%</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>%</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin">Total Entries</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalAlls) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAlls) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ctotal = $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($ctotal, $totalAlls) ?></td>
            </tr>
            <tr>
              <td colspan="7" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#f1edf1ff"><b>Growth Monitoring Devices</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Weighing Machine (Adult)</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total availble machines</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_pr_avl = (getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_adult', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_adult', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_pr_avl, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_ar_avl = (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_adult', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_adult', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_ar_avl, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr_avl = (getcountAWC($conn, $tablename, 'id', 'weighing_machine_adult', 'available_functional') + getcountAWC($conn, $tablename, 'id', 'weighing_machine_adult', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr_avl, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_adult', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_adult', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'weighing_machine_adult', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">non-functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_adult', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_adult', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'weighing_machine_adult', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">not available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_adult', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_adult', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'weighing_machine_adult', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Weighing Machine (Baby)</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total availble machines</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_pr_avl = (getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_baby', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_baby', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_pr_avl, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_ar_avl = (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_baby', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_baby', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_ar_avl, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr_avl = (getcountAWC($conn, $tablename, 'id', 'weighing_machine_baby', 'available_functional') + getcountAWC($conn, $tablename, 'id', 'weighing_machine_baby', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr_avl, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_baby', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_baby', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'weighing_machine_baby', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">non-functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_baby', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_baby', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'weighing_machine_baby', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">not available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'weighing_machine_baby', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'weighing_machine_baby', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'weighing_machine_baby', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Saltering Machine</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total availble machines</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_pr_avl = (getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'salter_machine', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'salter_machine', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_pr_avl, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_ar_avl = (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'salter_machine', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'salter_machine', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_ar_avl, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr_avl = (getcountAWC($conn, $tablename, 'id', 'salter_machine', 'available_functional') + getcountAWC($conn, $tablename, 'id', 'salter_machine', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'salter_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'salter_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'salter_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">non-functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'salter_machine', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'salter_machine', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'salter_machine', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">not available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'salter_machine', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'salter_machine', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'salter_machine', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Baby weighing with tray</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total availble machines</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_pr_avl = (getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'baby_weighing_machine', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'baby_weighing_machine', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_pr_avl, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_ar_avl = (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'baby_weighing_machine', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'baby_weighing_machine', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_ar_avl, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr_avl = (getcountAWC($conn, $tablename, 'id', 'baby_weighing_machine', 'available_functional') + getcountAWC($conn, $tablename, 'id', 'baby_weighing_machine', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'baby_weighing_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'baby_weighing_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'baby_weighing_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">non-functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'baby_weighing_machine', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'baby_weighing_machine', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'baby_weighing_machine', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">not available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'baby_weighing_machine', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'baby_weighing_machine', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'baby_weighing_machine', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Infantometer</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total availble machines</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_pr_avl = (getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'infantometer', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'infantometer', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_pr_avl, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_ar_avl = (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'infantometer', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'infantometer', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_ar_avl, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr_avl = (getcountAWC($conn, $tablename, 'id', 'infantometer', 'available_functional') + getcountAWC($conn, $tablename, 'id', 'infantometer', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'infantometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'infantometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'infantometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">non-functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'infantometer', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'infantometer', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'infantometer', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">not available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'infantometer', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'infantometer', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'infantometer', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Stadiometer</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total WM</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#2dace6ff"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>% of total availble machines</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_pr_avl = (getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'stadiometer', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'stadiometer', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_pr_avl, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_ar_avl = (getcountDistrictWise($conn, $tablename, $districtName, 'id', 'stadiometer', 'available_functional') + getcountDistrictWise($conn, $tablename, $districtName, 'id', 'stadiometer', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_ar_avl, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr_avl = (getcountAWC($conn, $tablename, 'id', 'stadiometer', 'available_functional') + getcountAWC($conn, $tablename, 'id', 'stadiometer', 'non_functional')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'stadiometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'stadiometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'stadiometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">non-functional</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'stadiometer', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'stadiometer', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'stadiometer', 'non_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($total_arpr, $totalAlls) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">not available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'stadiometer', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'stadiometer', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $total_arpr = getcountAWC($conn, $tablename, 'id', 'stadiometer', 'not_available') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;" colspan="7">Aanganwadi</td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin">% of Total Centers</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin">% of Total Centers</td>
              <td data-b-a-s="thin">Total Centers</td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Safe Entrance</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'anganwadi_entrance', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'anganwadi_entrance', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Clean Indoor Area</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'cleanliness_of_anganwadi', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'cleanliness_of_anganwadi', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="font-weight: bold; text-align: left;">Hot Meal</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Cooked</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'hcm_status', 'cooked') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'hcm_status', 'cooked') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">not cooked</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'hcm_status', 'not_cooked') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'hcm_status', 'not_cooked') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" style="font-weight: bold; text-align: left;">Aanganwadi Condition</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Unsafe</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'awc_condition', 'unsafe') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'awc_condition', 'unsafe') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Moderately Safe</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'awc_condition', 'moderate') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'awc_condition', 'moderate') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Safe and Secure</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'awc_condition', 'safe') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'awc_condition', 'safe') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="font-weight: bold; text-align: left;">Disability practices</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Not Inclusive</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'disability_inclusive', 'not_inclusive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'disability_inclusive', 'not_inclusive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Partially Inclusive</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'disability_inclusive', 'partially_inclusive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'disability_inclusive', 'partially_inclusive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Fully Inclusive</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'disability_inclusive', 'fully_inclusive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'disability_inclusive', 'fully_inclusive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;" colspan="7">POSHAN TRACKER</td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin">%of total centers</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin">%of total centers</td>
              <td data-b-a-s="thin">Total Centers</td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Installed</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'pt_installed', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'pt_installed', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Updated Growth Data</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'pt_update', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'pt_update', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average no of 0-5 children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'children_registered') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'children_registered') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average no of Infant(0-6 months)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'infants_registered') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'infants_registered') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average children measured</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'children_measured') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'children_measured') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average no of MAM children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'mam_identified') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'mam_identified') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average no of SAM children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'sam_identified') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'sam_identified') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average no of SUW children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'suw_identified') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'suw_identified') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Action Taken on SUV/ SAM children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'action_taken', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'action_taken', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average no. of pregnant women</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'pw_registered') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'pw_registered') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average no of weight recorded</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'weight_recorded') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'weight_recorded') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average no. of lactating mothers</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'lm_registered') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'lm_registered') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;" colspan="7">Growth Monitoring Validation</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Correct Weight Monitoring by AWW</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Correct Length Monitoring by AWW</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;" colspan="7">ECCE</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Average No of Children Enrolled</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName1, 'children_enrolled') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename, $districtName, 'children_enrolled') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Education Material</td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin" style="text-align:right;"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Limited</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'educational_materials', 'limited') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'educational_materials', 'limited') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Moderate</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'educational_materials', 'moderate') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'educational_materials', 'moderate') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Extensive</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'educational_materials', 'extensive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'educational_materials', 'extensive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Parent Teacher Meeting</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">30 Days</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'last_parent_teacher_meeting', 'within_30_days') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'last_parent_teacher_meeting', 'within_30_days') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">1 to 3 months</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'last_parent_teacher_meeting', '1_3_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'last_parent_teacher_meeting', '1_3_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">>3 months</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'last_parent_teacher_meeting', 'older_3_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'last_parent_teacher_meeting', 'older_3_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;" colspan="7">WASH</td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin">% of Total Centers</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin">% of Total Centers</td>
              <td data-b-a-s="thin">Total Centers</td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Handwashing Facility</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'Handwashing_facility', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'Handwashing_facility', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Functional Toilet</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'toilet', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'toilet', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Source of Drinking Water</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Handpump</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'handpump') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'handpump') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Tap Water from Nal-Jal Scheme</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'nal_Jal_Scheme') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'nal_Jal_Scheme') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Tubewell</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'tubewell') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'tubewell') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Bottled water</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'bottled_water') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'bottled_water') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Protected Dug Well</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'protected_dug_well') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'protected_dug_well') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Unprotected Dug Well</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'unprotected_dug_well') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'unprotected_dug_well') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Surface water from River or Pond</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'surface_water') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'surface_water') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Water from boring for irrigation</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'drinking_water', 'boring_for_irrigation') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'drinking_water', 'boring_for_irrigation') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Access of improved sources of drinking water</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;" colspan="7">Aanganwadi Worker</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Last Training on ECD</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin">% of total centers</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin">% of total centers</td>
              <td data-b-a-s="thin">Total Centers</td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">0-6 months</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'training_received', 'less_6_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'training_received', 'less_6_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">6-12 months</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'training_received', 'six_12_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'training_received', 'six_12_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">>1 year</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'training_received', 'older_1_year') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'training_received', 'older_1_year') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">Last RBSK visit</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">0-6 months</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'rbsk_last_visit', 'less_6_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'rbsk_last_visit', 'less_6_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">6-12 months</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'rbsk_last_visit', 'six_12_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'rbsk_last_visit', 'six_12_months') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:left;">>1 year</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename, $districtName1, 'id', 'rbsk_last_visit', 'older_1_year') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename, $districtName, 'id', 'rbsk_last_visit', 'older_1_year') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr + $num_ar ?></td>
              <td data-b-a-s="thin"></td>
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
      name: "ECD_AWC_Monthly_Assessmnt.xlsx", // Set your desired file name here
      sheet: {
        name: "ECD AWC Monthly Assessment" // Set the sheet name here
      }
    });
  });
</SCRIPT>

</html>