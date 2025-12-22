<?php include_once('includes/config.php'); ?>
<?php define("title", "HBYC Assessment Analysis | UNICEF"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

$month = date('m'); // October
$year = date('Y');
$mindate = '2024-01';
$maxdate = $year . '-' . $month;

if(isset($_REQUEST['fromDate']))
{
$fromDate = new DateTime($_REQUEST['fromDate']); 
$toDate = new DateTime($_REQUEST['toDate']);     

$MonthCount1 = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m'))+1;
$MonthCount = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m')) + 1;
$m=$MonthCount1;
$months = [];
$fullfilterText=[];
$fullfilter=[];
for ($i = 0; $i <= $MonthCount; $i++) {
    $months[] = $fromDate->format('m');
	$fullfilterText[] = $fromDate->format('F').'-'.$fromDate->format('Y');
	$fullfilter[] = $fromDate->format('Y').'-'.$fromDate->format('m');
    $fromDate->modify('+1 month');     
}
$first = $fullfilterText[0];
$last = $fullfilterText[count($fullfilterText)-2];
$monthsName = "'" . implode("', '", $months) . "'"; 
}
else {
	//echo "chala";
$sstdate=date('Y-m-01');
$eetdate=date('Y-m-d');
$fromDate = new DateTime($sstdate); 
$toDate = new DateTime($eetdate);     

$MonthCount1 = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m'))+1;
$MonthCount = (($toDate->format('Y') - $fromDate->format('Y')) * 12) + ($toDate->format('m') - $fromDate->format('m')) + 1;
$m=$MonthCount1;
$months = []; 
$fullfilterText=[];
$fullfilter=[];
for ($i = 0; $i <= $MonthCount; $i++) {
    $months[] = $fromDate->format('m');
	$fullfilterText[] = $fromDate->format('F').'-'.$fromDate->format('Y');
	$fullfilter[] = $fromDate->format('Y').'-'.$fromDate->format('m');
    $fromDate->modify('+1 month');     
}
$first = $fullfilterText[0];
$last = $fullfilterText[count($fullfilterText)-2];
$monthsName = "'" . implode("', '", $months) . "'";
}

$districtText='';
	if (isset($_REQUEST['district_code']) && !empty($_REQUEST['district_code'])) {
			$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
			if (!empty($districtType)) {
				$districtText=implode(", ", $districtType);
			}	
		}



// Get the number of days in the selected month
$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

function getcountHBYC($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{

    // Get the number of days in the selected month
   if (isset($_REQUEST['search'])) {
		if($qryfield1=='')
		{
			if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$startDate = new DateTime($_REQUEST['fromDate']);
				 $endDate = new DateTime($_REQUEST['toDate']);
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		}
		else {
			
			$qry .=" and date(dom) like'".$qryfield1."%'";
		}
		
		if (isset($_REQUEST['district_code'])) {
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
	}
    else {
		$startDate = new DateTime(date('Y-m-01'));
		$endDate = new DateTime(date('Y-m-d'));
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
		$qry .= " AND date(dom) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
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
<title>HBYC Assessment Analysis</title>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-bottom: 20px !important;
        border-radius: 1px solid black !important;
    }

    th,
    td {
        border: 1px solid #ddd !important;
        padding: 8px !important;
        text-align: center !important;
        font-size: 14px !important;
    }

    th {
        background-color: #4CAF50 !important;
        color: white !important;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2 !important;
    }

    tr:hover {
        background-color: #ddd !important;
    }

    .header1 {
        background-color: #F2F2F2 !important;
        color: #C0504D !important;
        text-align: center !important;
        padding: 4px !important;
        font-weight: 700 !important;
        font-size: 20px !important;
        border-top: unset;
        margin-top: -8px !important;

    }

    .sub-header {
        background-color: #800000 !important;
        color: white !important;
        text-align: left !important;
        width: 75% !important;
        font-size: 14px !important;
    }

    .sub-header1 {
        background-color: #F4C6A1 !important;
        color: black !important;
    }

    .tdhead {
        text-align: right !important;
    }
</style>

<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Report</li>
                        <li class="breadcrumb-item active"><i class="fa fa-calandar"></i>HBYC Assessment Report</li>
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
							
							<select class="form-select select2" id="district_ids" name="district_code[]" multiple >
							<option value="" <?= (empty($_REQUEST['district_code']) || in_array("", $_REQUEST['district_code'])) ? 'selected' : '' ?>> All</option>
							<?php 
							$allDistricts='';
							$selected=''; $kl=1;
							$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from avreg2bbpszdgvsxxzwbw2");
								while ($dataDistrictType = mysqli_fetch_object($qryDistrictType)) {
									if($kl>1)
									{
										$allDistricts.=', ';
									}
									$allDistricts.=ucfirst($dataDistrictType->district);
									
								if(isset($_REQUEST['district_code']))
								{
								$selected = (!empty($_REQUEST['district_code']) && in_array($dataDistrictType->district, $_REQUEST['district_code'])) ? 'selected' : '';
								}
								else {
									$selected='';
								}
								
								?>
								<option value="<?=$dataDistrictType->district?>" <?=$selected?> > <?=ucfirst($dataDistrictType->district)?> </option>
							<?php $kl++; } ?>
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
							<select class="form-select select2" id="uType" name="uType[]" multiple >
							<option value="" <?= (empty($_REQUEST['uType']) || in_array("", $_REQUEST['uType'])) ? 'selected' : '' ?>> All</option>
							<?php 
							$selected='';
							$qryUserType = mysqli_query($conn,"SELECT DISTINCT type_mon FROM avreg2bbpszdgvsxxzwbw2 ORDER BY type_mon ASC");
							while($dataUserType = mysqli_fetch_object($qryUserType)) {
								if(isset($_REQUEST['uType']))
								{
								$selected = (!empty($_REQUEST['uType']) && in_array($dataUserType->type_mon, $_REQUEST['uType'])) ? 'selected' : '';
								}
								else {
									$selected='';
								}
								
								?>
								<option value="<?=$dataUserType->type_mon?>" <?=$selected?> > <?=$dataUserType->type_mon?> </option>
							<?php } ?>
							<option value="piramal" > piramal</option>
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
							<a href="HBYC_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
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
                        <tr>
                            <td class="header1" style="font-size: 20px;" colspan="5" data-f-sz="20" data-a-h="center" data-fill-color="F2F2F2" data-f-color="800000" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true">HBYC Assessment Analysis Bihar (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" data-f-bold="true" style="text-align: right!important;" data-a-h="right" data-fill-color="F2F2F2">HBYC home visit Sessions Monitored</td>
                            <td colspan="3" data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', '_status!', '', '', '') ?></td>

                        </tr>
                        <tr>
                            <td colspan="2" data-f-bold="true" style="text-align: left!important;" data-a-h="left" data-fill-color="F2F2F2">AWW accompanying ASHA for this home visit</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_acc', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_acc!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">ASHA Practice during HBYC Home Visit</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;" data-b-b-s="thin" data-b-b-c="0000FF" colspan="2" data-fill-color="F2F2F2" data-a-h="left" data-f-bold="true">ASHA provided ORS to the family</td>
                            <td data-fill-color="F2F2F2" data-text-center='true' data-vertical-align='middle' data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_ors', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-text-center='true' data-vertical-align='middle' data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_ors!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-text-center='true' data-vertical-align='middle' data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>

                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;" data-a-h="left" data-b-b-s="thin" data-b-b-c="0000FF" colspan="2" data-f-bold="true">ASHA carrying blank MCP card with her</td>
                            <td data-fill-color="F2F2F2" data-text-center='true' data-vertical-align='middle' data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_carry_mcp', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-text-center='true' data-vertical-align='middle' data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_carry_mcp!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-text-center='true' data-vertical-align='middle' data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>

                        </tr>
                        <tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="3" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Baby's weight plotted in MCP card with the family?</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'wt_plotted_mcp', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'wt_plotted_mcp!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'wt_plotted_mcp', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'wt_plotted_mcp!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2">MCP Card not available</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'wt_plotted_mcp', 'mcp_not_aval', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'wt_plotted_mcp!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------Start Section: 2------------------->
                        <tr>
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">ASHA is recording the HBYC visit details by</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">VHIR/ASHA Diary</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'inf_rec_reg', 'vhir', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'inf_rec_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Separate register</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'inf_rec_reg', 'sep_reg', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'inf_rec_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">HBYC home visit format</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'inf_rec_reg', 'hbyc_hv_format', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'inf_rec_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Not recording</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'inf_rec_reg', 'dnk', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'inf_rec_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 2------------------->
                        <!-------Start Section: 3------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" width:30% rowspan="4" data-a-v="middle" data-f-bold="true">Did ASHA check appropriateness of weight according to the age</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_wt', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_wt!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_wt', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_wt!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Weight not recorded in MCP Card</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_wt', 'wt_not_rec_mcp', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_wt!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">MCP card not Available</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_wt', 'mcp_not_aval', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_wt!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 3------------------->
                        <!-------Start Section: 4------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true">Did ASHA check the following in the newborn</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Child had any illess/was sick</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked__nb_wh', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Development delays</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked__nb_tem', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Immunization status</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked__nb_res', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Nothing checked</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 4------------------->

                        <!-------Start Section: 5------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="6" data-a-v="middle" data-f-bold="true">Assessment of Breastfeeding by ASHA </td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Enquiry on whether breast-feeding is continued</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf__en_bf_con', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Enquiry on frequency of breast-feeding</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf__en_bf_freq', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Enquiry on Exclusive breast-feeding</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf__en_bf_exc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Enquiry on any challenges related to breast-feeding</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf__en_bf_chall', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Observation of positioning and attachment</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf__en_bf_pos_att', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">None of the above</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_assess_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 5------------------->
                        <!-------Start Section: 6------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="9" data-a-v="middle" data-f-bold="true">Did ASHA counsel the mother and family on </td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Importance of exclusive breastfeeding</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001__imp_bf', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Importance of breastfeeding 7-8 times a day</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001__imp_bf_freq', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Correct positioning and attachment</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001__corr_pos_att', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Importance of weight and regular growth monitoring</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001__imp_weight', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Introduction of CF at completion of 6 months</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001__int_cf', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">IFA syrup to infants</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001__infant_ifa', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Hand washing</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001__hw', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Developmental milestones</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001__dd', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Didn't counsel</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 6------------------->

                        <!-------Start Section: 7------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true">Did ASHA counsel the mother on regular uptake of</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">IFA</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ass_of_bf__ifa', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Calcium</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ass_of_bf__cal', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Importance of healthy diets during lactation period</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ass_of_bf__imp_healthy_diet', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Didn't counsel</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ass_of_bf__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 7------------------->

                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">ASHA Knowledge on HBYC Visits</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
                        <!-------Start Section: 1------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="6" data-a-v="middle" data-f-bold="true">How ASHA know the child is not growing well</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Weight is not increasing</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw__wt_not_inc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Height is not increasing</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw__ht_not_inc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Height & Weight both not increasing</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw__both_not_inc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Child is not taking feeds properly</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw__child_not_feed', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Child falls sick very frequently</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw__child_sick_freq', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Don't Know</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 1------------------->

                        <!-------Start Section: 2------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true">Why is the regular monitoring of infant's weight is important</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Tells us if infant is feeding well</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg__inf_feed_well', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Helps us to take timely action & referral</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg__take_action', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Helps us in understanding overall health status of child</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg__overall_health', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Helps us in early identification of risks in infant</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg__id_risk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Don't Know</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>


                        <!-------End Section: 2------------------->
                        <!-------Start Section: 3------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true">Which infants are at risk of early growth faltering</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">LBW</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf__lbw', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Pre term babies</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf__pre_term', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Sudden Weight loss between two consecutive visits</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf__sudden_wl', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Infant with any illness</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf__inf_illness', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Don't Know</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 3------------------->
                        <!-------Start Section: 4------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true">What action do ASHA take if baby is not growing well ?</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Refer to PHC/CHC for further examination & management</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth__ref_chc_phc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Refer to ANM</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth__ref_anm', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Refer to NRC</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth__ref_nrc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Assess the breastfeeding issues, cousel and correct it</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth__ass_bf_issues', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Don't Know</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 4------------------>
                        <!-------Start Section: 5------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="6" data-a-v="middle" data-f-bold="true">ASHA know Correct positioning for Breast-feeding?</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Mother relaxed and comfort-able</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr__mot_rel_comfort', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Mother sit straight and back supported</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr__mother_sit_st_supported', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Baby's body close to mother's body and facing breast</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr__baby_close_mother', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Baby's neck and body straight</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr__baby_neck_body_straigth', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Baby's whole body supported</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr__baby_body_supp', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Don't Know</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 5------------------>
                        <!-------Start Section: 6------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true">ASHA know Correct attachment during Breastfeeding?</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Baby's chin touching the breast</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr__baby_chin_touch_breast', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Mouth wide and open</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr__baby_mouth_open', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Lower lip turned outward</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr__baby_lower_lip_out', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">More areola is seen above the baby's mouth</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr__more_arola', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Don't Know</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 6------------------>
                        <tr>
                            <td data-fill-color="F2F2F2" colspan="2" data-f-bold="true" style="text-align: right!important;"> ASHA aware Correct frequency of Breastfeeding for infant</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'freq_corr', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'freq_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" colspan="2" data-b-b-s="thin" data-b-b-c="0000FF" data-f-bold="true" style="text-align: right!important;"> ASHA aware on expression of breastmilk</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'app_used', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'app_used!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">AWW Knowledge & Practice during HBYC Home Visit</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" colspan="2" data-f-bold="true" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle; text-align: right!important;" data-b-b-s="thin" data-b-b-c="0000FF">AWW accompanying ASHA for this home visit</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_acc', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_acc!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" colspan="2" data-f-bold="true" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle; text-align: left!important;" data-a-h="left" data-b-b-s="thin" data-b-b-c="0000FF">Child's name recorded in Poshan Tracker</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ch_rec_pt', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'ch_rec_pt!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------Start Section: 1------------------->
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="3" data-a-v="middle" data-f-bold="true">Following updated till last month in Poshan Tracker</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Weight of the child</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'p_tracker_updated__wt_child', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'p_tracker_updated!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Height of the child</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'p_tracker_updated__h_child', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'p_tracker_updated!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF">Not updated</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'p_tracker_updated__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'p_tracker_updated!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 1------------------>
                        <tr>
                            <td data-fill-color="F2F2F2" colspan="2" data-b-b-s="thin" data-b-b-c="0000FF" data-f-bold="true" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle; text-align: left!important;" data-a-h="left" data-b-b-s="thin" data-b-b-c="0000FF">Has AWW checked the child for developmental delay</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_dd', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_dd!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------Start Section: 2------------------>
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true">Did AWW counsel mother on any of the below</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Exclusive breastfeeding</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot__ebf', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Usage of THR</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot__use_thr', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Initiation of complemen-tary feeding(for 6months old babies)</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot__ini_cf', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Regular growth monitoring</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot__reg_grow_mon', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Didn't counsel</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_coun_mot!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 2------------------>
                        <!-------Start Section: 3------------------>
                        <tr>
                            <td data-fill-color="F2F2F2" style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;" data-b-b-s="thick" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true">AWW know following about Breastfeeding</td>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Correct positioning and attachment</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_know_bf__corr_pos', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_know_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Adequacy of breastfeeding for infant</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_know_bf__adq_bf', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_know_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right">Frequency of breastfeeding for infant</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_know_bf__freq_bf', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_know_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-b-b-s="thick" data-b-b-c="0000FF" data-a-h="right">Don't Know</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_know_bf__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBYC($conn, 'avreg2bbpszdgvsxxzwbw2', 'id', 'aww_know_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 3------------------>
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
            name: "HBYC-Analysis.xlsx", // Set your desired file name here
            sheet: {
                name: "Analysis Report" // Set the sheet name here
            }
        });
    });
</SCRIPT>

</html>