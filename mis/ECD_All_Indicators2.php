<?php include_once('includes/config.php'); ?>
<?php define("title", "ECD_All_Indicators | UNICEF"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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


function getcount_All_Indicators($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
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

function getcountDistrictWise($conn, $tablename, $districtName, $field, $qryfield, $value, $qryfield1 = '', $value1 = '', $qryfield2 = '', $value2 = '')
{
  $qryv = '';
  if ($qryfield1 != '') {
    $qryv = " AND $qryfield1='$value1'";
  }
  if ($qryfield2 != '') {
    $qryv .= " AND $qryfield2='$value2'";
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

  return round(($row->total), 2);
}

function getSum($conn, $tablename, $districtName, $field)
{
  $query = "SELECT SUM($field) AS total FROM $tablename WHERE district = '$districtName'";
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
            <li class="breadcrumb-item active"><i class="fa fa-calandar"></i>ECD ALL Indicators</li>
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
              <a href="ECD_All_Indicators.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
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
      $tablename = 'awmmsfdaueupsgpjtspyeu';
      $tablename1 = 'awmmsfdaueupsgpjtspyeu';
      $tablename2 = 'atsdzoq6fcieu7n7b3nmhc';
      $tablename3 = 'af6d7lsydnt4rgrr9qq9lp';
      $tablename4 = 'actddvhmpscwmcneaqozy2';
      $districtName = 'araria';
      $districtName1 = 'purnea';

      // $totalAlls = getcountAll($conn, $tablename, 'id', 'id>', 0);

      $totalAraria = getcountAll($conn, $tablename1, 'id', 'district', $districtName);
      $totalPurnea = getcountAll($conn, $tablename1, 'id', 'district', $districtName1);

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

          <table id="simpleTable1" data-cols-width="5,40,5,5,10,10,10,10,10," class="report" cellpadding="0" cellspacing="0" role="table" aria-label="All Indicators table">
            <tr>
              <td colspan="11" data-b-a-s="thick" data-fill-color="FFd7a7a7" data-a-h="center" data-f-sz="20" bgcolor="#d7a7a7" data-f-bold="true">
                <div align="center"><b>All Monthly Indicators of the ECD Program Implementation in Araria and Purnea</b></div>
              </td>
            </tr>
            <tr>
              <td colspan="2" data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8"><b>ECD Friendly AWC Indicators</b></td>
              <td colspan="3" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white; text-align:center;"><b>Araria</b></td>
              <td colspan="3" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white; text-align:center;"><b>Purnea</b></td>
              <td colspan="3" data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white; text-align:center;"><b>Total</b></td>
            </tr>
            <tr>
              <td data-fill-color="FF000000" data-b-a-s="thin" bgcolor="#e8e8e8"></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white; text-align: left;"><b>A. Health</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Num</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Den</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total %</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Num</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Den</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total %</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Num</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Den</b></td>
              <td data-fill-color="FF000000" data-b-a-s="thin" data-f-color="FFFFC000" bgcolor="#2dace6ff" data-f-bold="true" style="color: white;"><b>Total %</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">1</td>
              <td data-b-a-s="thin" style="text-align:left;">Monthly Health Checkups for Children (0-6 Years)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_health_checkup_frequency', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_health_checkup_frequency', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">2</td>
              <td data-b-a-s="thin" style="text-align:left;">Vaccination services accessible for your children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_vaccination', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_vaccination', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">3</td>
              <td data-b-a-s="thin" style="text-align:left;">Any RBSK/Medical team visited your center last time for screening of children with health concerns/sickness/disability/deficiency/defects/delays?</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_medical_screening_rbsk', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_medical_screening_rbsk', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="10" data-fill-color="FFcccccc" data-b-a-s="thin" data-f-bold="true" bgcolor="#2dace6ff" style="color: white; text-align: left;"><b>B. Nutrition</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">4</td>
              <td data-b-a-s="thin" style="text-align:left;">Last Growth Monitoring Conducted for Children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_growth_monitoring', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_growth_monitoring', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">5</td>
              <td data-b-a-s="thin" style="text-align:left;">Is hot cooked meals currently being served to the children as per menu ?</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_hot_cooked_meal', '2') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_hot_cooked_meal', '2') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="10" data-fill-color="FFcccccc" data-b-a-s="thin" data-f-bold="true" bgcolor="#2dace6ff" style="color: white; text-align: left;"><b>C. Early Learning</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">6</td>
              <td data-b-a-s="thin" style="text-align:left;">Enrollment Percentage (>=75%): ${Enrollment_percentage} %</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_enrollment_percentage', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_enrollment_percentage', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">7</td>
              <td data-b-a-s="thin" style="text-align:left;">Availability of Educational Materials and Activities</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_educational_materials', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_educational_materials', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">8</td>
              <td data-b-a-s="thin" style="text-align:left;">Engagement Level in ECCE Activities</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_ECCE_activity_engagement', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_ECCE_activity_engagement', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="10" data-fill-color="FFcccccc" data-b-a-s="thin" data-f-bold="true" bgcolor="#2dace6ff" style="color: white; text-align: left;"><b>D. Responsive Caregiving</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">9</td>
              <td data-b-a-s="thin" style="text-align:left;">Frequency of Parent-Teacher Meetings</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_frequency_parent_teacher_meeting', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_frequency_parent_teacher_meeting', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="10" data-fill-color="FFcccccc" data-b-a-s="thin" data-f-bold="true" bgcolor="#2dace6ff" style="color: white; text-align: left;"><b>E. Safety and Protection</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">10</td>
              <td data-b-a-s="thin" style="text-align:left;">Maintenance of Safe and Child-Friendly Environment</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_safe_environment', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_safe_environment', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">11</td>
              <td data-b-a-s="thin" style="text-align:left;">Does the AWC has functional handwashing facility for washing hands before meal and after using toilets?</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_handwashing_facility', '2') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_handwashing_facility', '2') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">12</td>
              <td data-b-a-s="thin" style="text-align:left;">Accessibility for Children with Special Needs (Inclusive Practices)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_inclusive_practices', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_inclusive_practices', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">13</td>
              <td data-b-a-s="thin" style="text-align:left;">Efforts for Community Engagement and ECD Awareness</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_community_outreach', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_community_outreach', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">14</td>
              <td data-b-a-s="thin" style="text-align:left;">Feedback System from Parents and Community</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_feedback_mechanism', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_feedback_mechanism', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="10" data-fill-color="FFcccccc" data-b-a-s="thin" data-f-bold="true" bgcolor="#2dace6ff" style="color: white; text-align: left;"><b>F. Questions to be asked in Household</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">15</td>
              <td data-b-a-s="thin" style="text-align:left;">Parents aware of key nutrition-related practices</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_nutritution_education', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_nutritution_education', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">16</td>
              <td data-b-a-s="thin" style="text-align:left;">AWW provides key messages to the caregivers on childrens individual and emotional need and avoiding harsh language or physical punishment.</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'marks_caregiver_key_message', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename1, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'marks_caregiver_key_message', '3') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename1, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td></td>
              <td data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8" style="text-align:center;"><b>Assessment of Home Visits</b></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="10" data-fill-color="FFcccccc" data-b-a-s="thin" data-f-bold="true" bgcolor="#2dace6ff" style="color: white; text-align: left;"><b>General Visit Information - ASHA & AWW</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">17</td>
              <td data-b-a-s="thin" style="text-align:left;">% of ASHA workers who conducted at least one home visit in the past 3 months</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'asha_visit', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'asha_visit', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">18</td>
              <td data-b-a-s="thin" style="text-align:left;">% of Anganwadi Workers (AWWs) who conducted at least one home visit in the past 3 months</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'aww_visit', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'aww_visit', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">19</td>
              <td data-b-a-s="thin" style="text-align:left;">Sex of Child (Male)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'sex', 'male') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'sex', 'male') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">20</td>
              <td data-b-a-s="thin" style="text-align:left;">Sex of Child (Female)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'sex', 'female') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAraria) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'sex', 'female') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPurnea) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">21</td>
              <td data-b-a-s="thin" style="text-align:left;">Total Children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalsex1 = (getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'sex', 'male') + $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'sex', 'female')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($totalsex1, getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalsex2 = (getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'sex', 'male') + $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'sex', 'female')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($totalsex2, getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalsex1 + $totalsex2 ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= (getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($totalsex1 + $totalsex2), (getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') + getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">22</td>
              <td data-b-a-s="thin" style="text-align:left;">Birth-Weight Available</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'birth_weight>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'birth_weight>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">23</td>
              <td data-b-a-s="thin" style="text-align:left;">LBW</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'birth_weight<', '2500', 'birth_weight>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'birth_weight>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'birth_weight>', '0')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'delivery_type', 'Institutional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'birth_weight>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'birth_weight>', '0')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= (getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'birth_weight>', '0') + getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'birth_weight>', '0')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), (getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'birth_weight>', '0') + getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'birth_weight>', '0'))) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">24</td>
              <td data-b-a-s="thin" style="text-align:left;">Place of Birth (Institutional)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'delivery_type>', 'Institutional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'delivery_type>', 'Institutional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">25</td>
              <td data-b-a-s="thin" style="text-align:left;">% of Children having Birth Certificate</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'birth_registration>', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'birth_registration>', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="10" data-fill-color="FFcccccc" data-b-a-s="thin" data-f-bold="true" bgcolor="#2dace6ff" style="color: white; text-align: left;"><b>Key IYCF Practices</b></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">26</td>
              <td data-b-a-s="thin" style="text-align:left;">% of Children started complementary feeding just after 6 months</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'complementary_feeding', '6') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'complementary_feeding', '6') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">27</td>
              <td data-b-a-s="thin" style="text-align:left;">% of children above 6 months receiving Minimum Dietary DIversity (MDD) in the last 24 hours</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'MDD', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'MDD', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">28</td>
              <td data-b-a-s="thin" style="text-align:left;">% of children above 6 months who met MMF</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'MMF', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'MMF', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">29</td>
              <td data-b-a-s="thin" style="text-align:left;">% of children above 6 months who met MAD</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'MAD', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'MAD', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">30</td>
              <td data-b-a-s="thin" style="text-align:left;">% Children receiving breastfeeding Yesterday (All Age)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'baby_food__breastmilk', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'baby_food__breastmilk', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">31</td>
              <td data-b-a-s="thin" style="text-align:left;">% of under 6 months children on Exclusive Breastfeeding</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'baby_food__formula_milk', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'baby_food__formula_milk', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">32</td>
              <td data-b-a-s="thin" style="text-align:left;">% of children eating Junk Food (All Age)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'baby_food__Junk_items', '1', 'child_age_months>', '7') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'child_age_months>', '7') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'baby_food__Junk_items', '1', 'child_age_months>', '7') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'child_age_months>', '7') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">33</td>
              <td data-b-a-s="thin" style="text-align:left;">% of below 6 months children getting Animal Milk</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'baby_food__cows_milk', '1', 'child_age_months>', '5') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'child_age_months>', '5') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'baby_food__cows_milk', '1', 'child_age_months>', '5') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'child_age_months>', '5') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">34</td>
              <td data-b-a-s="thin" style="text-align:left;">% of below 6 months children getting Formula Milk</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'baby_food__formula_milk', '1', 'child_age_months<', '5') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'child_age_months<', '5') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'baby_food__formula_milk', '1', 'child_age_months<', '5') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'child_age_months<', '5') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">Growth Monitoring of Child</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">35</td>
              <td data-b-a-s="thin" style="text-align:left;">% of family having MCP Card at home</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'mcp_card', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'mcp_card', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">36</td>
              <td data-b-a-s="thin" style="text-align:left;">% of children having their height and weight measured in the last one month</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'height_measured', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'height_measured', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">37</td>
              <td data-b-a-s="thin" style="text-align:left;">% of families with plotted growth monitoring in MCP card by AWW</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'growth_monitoring', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'growth_monitoring', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">Key Activities for Achieving Developmental Milestone</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">38</td>
              <td data-b-a-s="thin" style="text-align:left;">% of Children on track as per age-appropriate activities mentioned in the MCP card</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'age_appropriate_toy', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'age_appropriate_toy', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">39</td>
              <td data-b-a-s="thin" style="text-align:left;">% of families where members (mother, father, grandparents) engage daily in talking or playing with the child</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'caregivers__mother', '1', 'caregivers__father', '1', 'caregivers__grandparents', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'caregivers__mother', '1', 'caregivers__father', '1', 'caregivers__grandparents', '1') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">40</td>
              <td data-b-a-s="thin" style="text-align:left;">% of Families received information about developmental milestones for their child (e.g., smiling, holding the neck, crawling, walking)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'developmental_milestone', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'developmental_milestone', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">41</td>
              <td data-b-a-s="thin" style="text-align:left;">% of families received always key messages from AWWs about addressing childrens individual and emotional needs, and avoiding harsh language or physical punishment?</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'aww_messages', 'always') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'aww_messages', 'always') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">42</td>
              <td data-b-a-s="thin" style="text-align:left;">% of families who attended any Parents Meeting at the AWC in the last one month</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'parents_meeting', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'parents_meeting', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">WASH Component</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">43</td>
              <td data-b-a-s="thin" style="text-align:left;">% of family having access to a improved functional toilet in their household</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = (getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'toilet_type', 'pit_latrine') + getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'toilet_type', 'septic_tank') + getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'toilet_type', 'flush_toilet')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'toilet_type>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = (getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'toilet_type', 'pit_latrine') + getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'toilet_type', 'septic_tank') + getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'toilet_type', 'flush_toilet')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'toilet_type>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">44</td>
              <td data-b-a-s="thin" style="text-align:left;">% of family usually dispose the child feaces in toilet</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'dispose_feaces', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'dispose_feaces', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">45</td>
              <td data-b-a-s="thin" style="text-align:left;">% of households having improved sources of drinking water</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = (getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'drinking_water', 'handpump') + getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'drinking_water', 'tubewell') + getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'drinking_water', 'nal_Jal_Scheme') + getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'drinking_water', 'protected_dug_well') + getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'drinking_water', 'bottled_water')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'dispose_feaces', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">Health Component</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">46</td>
              <td data-b-a-s="thin" style="text-align:left;">Age-appropriate completely vaccinated children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'vaccination_status', 'completely_vaccinated') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'vaccination_status', 'completely_vaccinated') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">47</td>
              <td data-b-a-s="thin" style="text-align:left;">Intake of Vitamin A recorded in MCP card</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'mcp_card', 'yes', 'vitamin_a', 'yes', 'child_age_months>', '10') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'mcp_card', 'yes', 'child_age_months>', '10') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'mcp_card', 'yes', 'vitamin_a', 'yes', 'child_age_months>', '10') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'mcp_card', 'yes', 'child_age_months>', '10') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">48</td>
              <td data-b-a-s="thin" style="text-align:left;">% of MCP card having updated details of biweekly IFA syrup</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'mcp_update_status', 'up_to_date') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename2, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'mcp_update_status', 'up_to_date') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename2, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">Assessment of VHSND</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">49</td>
              <td data-b-a-s="thin" style="text-align:left;">Availability of AWW at VHSND</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'aww_present', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'aww_present', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">50</td>
              <td data-b-a-s="thin" style="text-align:left;">Availability of ASHA at VHSND</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'asha_present', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'asha_present', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">51</td>
              <td data-b-a-s="thin" style="text-align:left;">Facility having Toilet</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'toilet', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'toilet', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">52</td>
              <td data-b-a-s="thin" style="text-align:left;">Facility having Handwash</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'hand_washing', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'hand_washing', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">53</td>
              <td data-b-a-s="thin" style="text-align:left;">Facility having Drinking Water</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'drinking_water', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'drinking_water', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">54</td>
              <td data-b-a-s="thin" style="text-align:left;">Percentage of pregnant women attending the session whose weight was recorded</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ar_sumData1 = getSum($conn, $tablename3, $districtName, 'weight_recorded'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ar_sumData2 = getSum($conn, $tablename3, $districtName, 'pregnant_women_count'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($ar_sumData1, $ar_sumData2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $pr_sumData1 = getSum($conn, $tablename3, $districtName1, 'weight_recorded'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $pr_sumData2 = getSum($conn, $tablename3, $districtName1, 'pregnant_women_count'); ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($pr_sumData1, $pr_sumData2) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ar_sumData1 + $pr_sumData1 ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ar_sumData2 + $pr_sumData2 ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($ar_sumData1 + $pr_sumData1), ($ar_sumData2 + $pr_sumData2)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">55</td>
              <td data-b-a-s="thin" style="text-align:left;">Percentage of VHSND where SAM child was present</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'sam_in_vhsnd', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'sam_in_vhsnd', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">56</td>
              <td data-b-a-s="thin" style="text-align:left;">Screening undertaken in SAM children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'sam_screening', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'sam_in_vhsnd', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'sam_screening', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'sam_in_vhsnd', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">57</td>
              <td data-b-a-s="thin" style="text-align:left;">Caregivers were advised about proper nutrition in case of childs poor weight or growth</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'counselling_nutrition', 'all_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'sam_in_vhsnd', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'counselling_nutrition', 'all_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'sam_in_vhsnd', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">58</td>
              <td data-b-a-s="thin" style="text-align:left;">Percentage of VHSND sessions where caregivers were informed about their children’s developmental milestones</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'developmental_milestone', 'all_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'developmental_milestone', 'all_children') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">59</td>
              <td data-b-a-s="thin" style="text-align:left;">Developmental Delays noticed in atleast one child in VHSND</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'developmental_delay', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'developmental_delay', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">60</td>
              <td data-b-a-s="thin" style="text-align:left;">at least one child referred for further management</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'developmental_delay_management', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename3, $districtName, 'id', 'developmental_delay', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'developmental_delay_management', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename3, $districtName1, 'id', 'developmental_delay', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">61</td>
              <td data-b-a-s="thin" style="text-align:left;">No of children suffering from diarrhoea who were given ors zinc</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ar_sumData1 = getSum($conn, $tablename3, $districtName, 'ors_zink_given'); ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $pr_sumData2 = getSum($conn, $tablename3, $districtName1, 'ors_zink_given'); ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ar_sumData1 + $pr_sumData2 ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:left;">No of children who were fully immunized (12-23 months)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ar_sumData1 = getSum($conn, $tablename3, $districtName, 'full_immunization'); ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $pr_sumData2 = getSum($conn, $tablename3, $districtName1, 'full_immunization'); ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $ar_sumData1 + $pr_sumData2 ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">63</td>
              <td data-b-a-s="thin" style="text-align:left;">No of children with pneumonia who were reffered/treated</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-fill-color="FFe8e8e8" data-b-a-s="thin" data-f-bold="true" bgcolor="#e8e8e8" style="text-align:center;">Monthly Assessment of AWC (Total Assessment)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">Nutrition</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">64</td>
              <td data-b-a-s="thin" style="text-align:left;">Available and Functional Weighing Machine (Adult)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'weighing_machine_adult', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'weighing_machine_adult', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">65</td>
              <td data-b-a-s="thin" style="text-align:left;">Available and Functional Weighing Machine with tray (Baby)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'baby_weighing_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'baby_weighing_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">66</td>
              <td data-b-a-s="thin" style="text-align:left;">Available and Functional Saltering Machine</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'salter_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'salter_machine', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">67</td>
              <td data-b-a-s="thin" style="text-align:left;">Available and Functional Infantometer</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'infantometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'infantometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">68</td>
              <td data-b-a-s="thin" style="text-align:left;">Available and Functional stadiometer</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'stadiometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'stadiometer', 'available_functional') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">69</td>
              <td data-b-a-s="thin" style="text-align:left;">Is hot cooked meals currently being served to the children as per menu ?</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'hcm_status', 'cooked') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'hcm_status', 'cooked') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">Safety and Protection</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">70</td>
              <td data-b-a-s="thin" style="text-align:left;">Maintenance of Safe and Child-Friendly Environment</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'anganwadi_entrance', 'yes', 'cleanliness_of_anganwadi', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id',  'anganwadi_entrance', 'yes', 'cleanliness_of_anganwadi', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">71</td>
              <td data-b-a-s="thin" style="text-align:left;">Accessibility for Children with Special Needs (Inclusive Practices)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'disability_inclusive', 'fully_inclusive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'disability_inclusive', 'fully_inclusive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">Poshan Tracker</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">72</td>
              <td data-b-a-s="thin" style="text-align:left;">Installed</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'pt_installed', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'pt_installed', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">73</td>
              <td data-b-a-s="thin" style="text-align:left;">Updated Growth Data</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'pt_update', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'pt_update', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">74</td>
              <td data-b-a-s="thin" style="text-align:left;">Average no of 0-5 children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName, 'children_registered') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName1, 'children_registered') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">75</td>
              <td data-b-a-s="thin" style="text-align:left;">Average no of Infant(0-6 months)</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName, 'infants_registered') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName1, 'infants_registered') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">76</td>
              <td data-b-a-s="thin" style="text-align:left;">Average children growth measured</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName, 'children_measured') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName1, 'children_measured') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">77</td>
              <td data-b-a-s="thin" style="text-align:left;">Average no of MAM children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName, 'mam_identified') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName1, 'mam_identified') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">78</td>
              <td data-b-a-s="thin" style="text-align:left;">Average no of SAM children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName, 'sam_identified') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName1, 'sam_identified') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">79</td>
              <td data-b-a-s="thin" style="text-align:left;">Average no of SUW children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName, 'suw_identified') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName1, 'suw_identified') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">80</td>
              <td data-b-a-s="thin" style="text-align:left;">No of AWC action taken on SUV/ SAM children</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'action_taken', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'action_taken', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">81</td>
              <td data-b-a-s="thin" style="text-align:left;">Average no. of pregnant women</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName, 'pw_registered') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName1, 'pw_registered') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">82</td>
              <td data-b-a-s="thin" style="text-align:left;">Average no of weight recorded</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName, 'weight_recorded') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName1, 'weight_recorded') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">Skill of Frontline Workers</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">83</td>
              <td data-b-a-s="thin" style="text-align:left;">Correct Weight Monitoring by AWW</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">84</td>
              <td data-b-a-s="thin" style="text-align:left;">Correct Length Monitoring by AWW</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0</td>
              <td data-b-a-s="thin" style="text-align:right;">0.00%</td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">ECCE</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">85</td>
              <td data-b-a-s="thin" style="text-align:left;">Average No of Children Enrolled at AWC</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName, 'children_enrolled') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getAverage($conn, $tablename4, $districtName1, 'children_enrolled') ?></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin"></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">86</td>
              <td data-b-a-s="thin" style="text-align:left;">Availability of all Educational Materials at AWCs</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'educational_materials', 'extensive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'educational_materials', 'extensive') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">87</td>
              <td data-b-a-s="thin" style="text-align:left;">Monthly Parent Teacher Meeting</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'last_parent_teacher_meeting', 'within_30_days') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'last_parent_teacher_meeting', 'within_30_days') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin"></td>
              <td data-b-a-s="thin" colspan="10" bgcolor="#2dace6ff" style="color: white; text-align: left;">WASH</td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">88</td>
              <td data-b-a-s="thin" style="text-align:left;">Availability of Handwashing Facility</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'Handwashing_facility', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'Handwashing_facility', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">89</td>
              <td data-b-a-s="thin" style="text-align:left;">Availability of Functional Toilet</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'toilet', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'toilet', 'yes') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
            </tr>
            <tr>
              <td data-b-a-s="thin" style="text-align:right;">90</td>
              <td data-b-a-s="thin" style="text-align:left;">Access of improved sources of drinking water</td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar = (getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'drinking_water', 'handpump') + getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'drinking_water', 'tubewell') + getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'drinking_water', 'Nal_Jal_Scheme') + getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'drinking_water', 'protected_dug_well') + getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'drinking_water', 'bottled_water')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr = getcountDistrictWise($conn, $tablename4, $districtName, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_ar, $totalAr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_pr = (getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'drinking_water', 'handpump') + getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'drinking_water', 'tubewell') + getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'drinking_water', 'Nal_Jal_Scheme') + getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'drinking_water', 'protected_dug_well') + getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'drinking_water', 'bottled_water')) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalPr = getcountDistrictWise($conn, $tablename4, $districtName1, 'id', 'id>', '0') ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage($num_pr, $totalPr) ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $num_ar + $num_pr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= $totalAr + $totalPr ?></td>
              <td data-b-a-s="thin" style="text-align:right;"><?= getPercentage(($num_ar + $num_pr), ($totalAr + $totalPr)) ?></td>
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
      name: "ECD_All_Indicators.xlsx", // Set your desired file name here
      sheet: {
        name: "ECD All Indicators" // Set the sheet name here
      }
    });
  });
</SCRIPT>

</html>