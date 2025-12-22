<?php include_once('includes/config.php'); ?>
<?php define("title", "HBNC Report | UNICEF"); ?>
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


function getcountHBNC($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{



    // Get the number of days in the selected month
    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $qry = "";
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
/*
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

<title>HBNC Assessment Analysis</title>
<style>
    body {
        font-family: Calibri;
        /* background-color: #F2F2F2;
            ; */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        border-radius: 1px solid black;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
        font-size: 14px;
    }

    /* 
        th {
            background-color: #f2f2f2;
        } */

    tr {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    h1 {
        color: #800000;
        text-align: center;
        font-size: 24px;
    }

    .highlight-header {
        background-color: #0F243E;
        color: #FFC000;
        text-align: left;
        padding: 10px;
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

    .sub-header2 {
        background-color: #0f243e !important;
        color: #ffc000 !important;
        font-size: 14px !important;
        font-weight: bold !important;
        margin-right: 10px !important;
        width: 75% !important;
    }

    .header-info {
        text-align: center;
        font-size: 20px;
        color: #800000;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .tdhead {
        text-align: right;
    }

    .sub-header1 {
        background-color: #31869B !important;
        color: #F4F8FA !important;
        font-size: 14px !important;
        text-align: left !important;
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
					<div class="col-lg-2 col-md-4 col-sm-12">
						<div class="form-group">
							<b>Select District</b>
							
							<select class="form-select select2" id="district_ids" name="district_code[]" multiple >
							<option value="" <?= (empty($_REQUEST['district_code']) || in_array("", $_REQUEST['district_code'])) ? 'selected' : '' ?>> All</option>
							<?php 
							$allDistricts='';
							$selected=''; $kl=1;
							$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from atjqsqcrh8zlms8bklc2eb");
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
							$qryUserType = mysqli_query($conn,"SELECT DISTINCT type_mon FROM atjqsqcrh8zlms8bklc2eb ORDER BY type_mon ASC");
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
							<a href="HBNC_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
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
                            <td class="header1" style="font-size: 20px;" colspan="5" data-f-sz="20" data-a-h="center" data-fill-color="F2F2F2" data-f-color="C0504D" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true">HBNC Assessment Analysis Bihar (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;font-size: 14px;" data-fill-color="F2F2F2">HBNC home visit Sessions Monitored</td>
                            <td colspan="3" style="background-color:#F2F2F2;text-align:center;" data-fill-color="F2F2F2" data-a-h="center"><?= getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', '_status!', '', '', '') ?></td>

                        </tr>
                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;" data-fill-color="F2F2F2"></td>
                            <td class="sub-header1" data-f-bold="true" data-fill-color="F2F2F2"><b>Num</b></td>
                            <td class="sub-header1" data-f-bold="true" data-fill-color="F2F2F2"><b>Den</b></td>
                            <td class="sub-header1" data-f-bold="true" data-fill-color="F2F2F2"><b>%</b></td>
                        </tr>
                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;  font-size: 14px;" data-fill-color="F2F2F2">AWW accompanying ASHA for this home visit</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'aww_acc', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'aww_acc!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right; font-size: 14px;" data-fill-color="F2F2F2">Do ASHA share details of LBW cases with AWW</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'share_lbw', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'share_lbw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;  font-size: 14px;" data-fill-color="F2F2F2">ASHA recorded information of today's visit in HBNC format</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'inf_rec_reg', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'inf_rec_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;font-size: 14px;" data-fill-color="F2F2F2">ASHA's VHIR (HBNC section) filled</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'vhir_filled', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'vhir_filled!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;  font-size: 14px;" data-fill-color="F2F2F2">HBNC visits due and conducted columns updated in VHIR?</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'due_con_col_updated', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'due_con_col_updated!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>


                        <tr>
                            <th colspan="2" class="sub-header2" data-f-bold="true" data-f-sz="14" data-fill-color="0f243e" data-f-color="ffc000" data-f-bold="true" data-f-sz="14" data-fill-color="0f243e" data-f-color="ffc000">Logistics available with ASHA during Home Visit
                            </th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="31869B" data-f-color="F4F8FA" data-f-bold="true">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="31869B" data-f-color="F4F8FA" data-f-bold="true">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="31869B" data-f-color="F4F8FA" data-f-bold="true">%</th>
                        </tr>

                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;font-size: 14px;" data-fill-color="F2F2F2">Newborn weighing scale</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'nb_ws', 'func', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'nb_ws!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;font-size: 14px;" data-fill-color="F2F2F2">Digital thermo-meter</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'dig_thermo', 'func', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'dig_thermo!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;font-size: 14px;" data-fill-color="F2F2F2">Digital watch</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'dig_watch', 'func', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'dig_watch!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;font-size: 14px;" data-fill-color="F2F2F2">Printed home visit format used
                            </td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'printed_hw_format', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'printed_hw_format!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>


                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;font-size: 14px;" data-fill-color="F2F2F2">Blank MCP cards carried
                            </td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'blank_mcp', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'blank_mcp!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td colspan="2" data-a-h="right" style="text-align: right;font-size: 14px;" data-fill-color="F2F2F2">Is she using the VHIR (Village Health Index Register)/ ASHA Diary?
                            </td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'vhir_used', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'vhir_used!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <th colspan="2" class="sub-header2" data-f-bold="true" data-f-sz="14" data-fill-color="0f243e" data-f-color="ffc000">ASHA Practice during Home Visit
                            </th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="31869B" data-f-color="F4F8FA" data-f-bold="true">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="31869B" data-f-color="F4F8FA" data-f-bold="true">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="31869B" data-f-color="F4F8FA" data-f-bold="true">%</th>
                        </tr>

                        <tr>
                            <td colspan="2" style="font-weight: 600; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2">Baby's weight plotted in MCP card with the family?
                            </td>
                            <td data-fill-color="F2F2F2" style="border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'wt_plotted_mcp', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'wt_plotted_mcp!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>

                        </tr>

                        <!-------Start Section: 2------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="7" data-f-bold="true" data-fill-color="F2F2F2">Did ASHA check the following in the newborn</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">Weight</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked__nb_wh', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Temperature</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked__nb_tem', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right" style="font-size:14px">Respiratory rate</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked__nb_res', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                       
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right" style="font-size:14px">Pus on umbilicus</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked__nb_pus', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
						 <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right" style="font-size:14px">Eyes</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked__nb_eye', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right" style="font-size:14px">Urine frequency</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked__nb_uf', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td data-fill-color="F2F2F2" class="tdhead" data-a-h="right" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">None of the above</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <!-------End Section: 2------------------->


                        <!-------Start Section: 3------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="6" data-f-bold="true" data-fill-color="F2F2F2">
                                Assessment of Breastfeeding by ASHA
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">Enquiry on whether breast-feeding is continued</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf__en_bf_con', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Enquiry on frequency of breast-feeding</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf__en_bf_freq', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Enquiry on exclusive breast-feeding?</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf__en_bf_exc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Enquiry on any challenges related to breast-feeding</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf__en_bf_chall', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Observation of positioning and attachment?</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf__en_bf_pos_att', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">None of the above</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>


                        <!-------End Section: 3------------------->


                        <!-------Start Section: 4------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="6" data-f-bold="true" data-fill-color="F2F2F2">
                                Did she examine/ask mother for the following
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">Temprature</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined__temp', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Having fits or speaking abnormally</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined__fits', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Foul discharge</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined__foul_dis', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">If mother perceives breastmilk to be less</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined__bm_less', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Cracked nipples/ paiful/ engorged breast</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined__crack_nipp', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">None of the above</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_examined!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>


                        <!-------End Section: 4------------------->


                        <!-------Start Section: 5------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="7" data-f-bold="true" data-fill-color="F2F2F2">
                                Did ASHA counsel the mother and family on any of the below areas related to newborn
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">Importance of exclusive breast-feeding</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001__imp_bf', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Importance of breastfeeding 7-8 times a day (inc.night time)</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001__imp_bf_freq', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Correct positioning and attachment</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001__corr_pos_att', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Importance of weight and regular growth monitoring</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001__imp_weight', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Danger signs in baby</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001__danger_signs', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">/KMC</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001__kmc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Didn't counsel</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'asha_checked_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 5------------------->

                        <!-------Start Section: 6------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px;border-bottom: 1px solid black;  data-b-b-s=" thin" data-b-b-c="0000FF"" rowspan=" 5" data-f-bold="true" data-fill-color="F2F2F2">
                                Did ASHA counsel the mother on regular uptake of
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">IFA</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001__ifa', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Calcium</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001__cal', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Importance of healthy diets during lactation period</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001__imp_healthy_diet', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Adequacy of breast-feeding for infant </td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001__adq_bf', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Didn't counsel</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'ass_of_bf_001!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 6------------------->

                        <tr>
                            <th colspan="2" class="sub-header2" data-f-bold="true" data-f-sz="14" data-fill-color="0f243e" data-f-color="ffc000">ASHA Knowledge on HBNC Visits
                            </th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="31869B" data-f-color="F4F8FA" data-f-bold="true">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="31869B" data-f-color="F4F8FA" data-f-bold="true">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="31869B" data-f-color="F4F8FA" data-f-bold="true">%</th>
                        </tr>

                        <!-------Start Section: 7------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-f-bold="true" data-fill-color="F2F2F2">
                                How ASHA know the child is not growing well
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">Weight is not increasing</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'how_know_gw__wt_not_inc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Child is not taking feeds properly</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'how_know_gw__child_not_feed', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Child falls sick very frequently</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'how_know_gw__child_sick_freq', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Don't Know</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'how_know_gw__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'how_know_gw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 7------------------->

                        <!-------Start Section: 8------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-f-bold="true" data-fill-color="F2F2F2">
                                Why is the regular monitoring of infant's weight is important
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">Tells us if infant is feeding well</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg__inf_feed_well', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Helps us to take timely action & referral</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg__take_action', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Helps us in understanding overall health status of child</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg__overall_health', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Helps us in early identification of risks in infant </td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg__id_risk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Don't Know</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'mon_reg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 8------------------->

                        <!-------Start Section: 8------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="7" data-f-bold="true" data-fill-color="F2F2F2">
                                Which infants are at risk of early growth faltering
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">LBW</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf__lbw', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Pre term babies</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf__pre_term', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Sudden Weight loss between two consecutive visits</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf__sudden_wl', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Babies discharged from SNCU </td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf__dis_sncu', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Infant with any illness</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf__inf_illness', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Mother related health and mental issues</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf__mother_health', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Don't Know</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'risk_egf!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 8------------------->

                        <!-------Start Section: 9------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-f-bold="true" data-fill-color="F2F2F2">
                                How do ASHA identify poor weight gain in a newborn ?
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">If the newborn's wt after two weeks is less than birth wt</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg__less_wt_2weeks', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Infant is in Yellow/Red zone of growth monitoring chart</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg__inf_yell_red', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Sudden weight loss between two cconsecutive visits</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg__sudden_wl', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Static weight between two consecutive visits </td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg__stat_wt', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                       

                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Don't Know</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'iden_poor_wg!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 9------------------->

                        <!-------Start Section: 10------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-f-bold="true" data-fill-color="F2F2F2">
                                What action do ASHA take if baby is not growing well ?
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">Refer to PHC/CHC for further examination & management</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth__ref_chc_phc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Refer to ANM</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth__ref_anm', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Refer to NRC</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth__ref_nrc', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Assess the breastfeeding issues, cousel and correct it</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth__ass_bf_issues', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        

                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Don't Know</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'action_growth!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 10------------------->

                        <!-------Start Section: 11------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="6" data-f-bold="true" data-fill-color="F2F2F2">
                                ASHA know Correct positioning for Breast-feeding?
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">Mother relaxed and comfort-able</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr__mot_rel_comfort', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Mother sit straight and back supported</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr__mother_sit_st_supported', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Baby's body close to mother's body and facing breast</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr__baby_close_mother', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">baby's neck and body straight</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr__baby_neck_body_straigth', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Baby's whole body supported</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr__baby_body_supp', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Don't Know</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'pos_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 11------------------->

                        <!-------Start Section: 12------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-f-bold="true" data-fill-color="F2F2F2">
                                ASHA know Correct attachment during Breastfeeding?
                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px">baby's chin touching the breast</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr__baby_chin_touch_breast', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Mouth wide and open</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr__baby_mouth_open', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">Lower lip turned outward</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr__baby_lower_lip_out', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px">More areola is seen above the baby's mouth</td>
                            <td data-fill-color="F2F2F2"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr__more_arola', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Don't Know</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr__dnk', '1', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'att_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>

                        <!-------End Section: 12------------------->

                        <!-------Start Section: 13------------------->
                        <tr>
                            <td style="font-weight: 600; font-size: 14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" data-f-bold="true" data-fill-color="F2F2F2">

                            </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">Did she know Correct frequency of Breastfeeding for infant?</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'freq_corr', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'freq_corr!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" data-f-bold="true" data-fill-color="F2F2F2">

                            </td>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF">When newborn called as low birth weight</td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $n = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'aware_lbw', 'Aware', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= $d = getcountHBNC($conn, 'atjqsqcrh8zlms8bklc2eb', 'id', 'aware_lbw!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" style="font-size:14px; border-bottom: 1px solid black;" data-b-b-s="thin" data-b-b-c="0000FF"><?= round((($n * 100) / $d), 0) ?>%</td>
                        </tr>


                        <!-------end Section: 13------------------->
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
            name: "HBNC-Analysis.xlsx", // Set your desired file name here
            sheet: {
                name: "Analysis Report" // Set the sheet name here
            }
        });
    });
</SCRIPT>

</html>