<?php include_once('includes/config.php'); ?>
<?php define("title", "AMB School & VHSND Analysis | UNICEF"); ?>
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

function getcountAMB($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{

    // Get the number of days in the selected month
   if (isset($_REQUEST['search'])) {
		if($qryfield1=='')
		{
			if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$startDate = new DateTime($_REQUEST['fromDate']);
				 $endDate = new DateTime($_REQUEST['toDate']);
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(Date) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		}
		else {
			
			$qry .=" and date(Date) like'".$qryfield1."%'";
		}
		
		if (isset($_REQUEST['district_code'])) {
			$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
			if (!empty($districtType)) {
				
				 $districtTypeList = "'" . implode("','", $districtType) . "'"; // Convert to integer to prevent SQL injection
				$qry .= " AND District IN ($districtTypeList)";
			}	
		}
		if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {
		
        $userTypes = array_filter($_REQUEST['uType']); // Remove empty values
		//print_r($userTypes);
        if (!empty($userTypes)) {
             $userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
			$qry .= " AND Organization IN ($userTypeList)";
        }
		
		
    }
	}
    else {
		$startDate = new DateTime(date('Y-m-01'));
		$endDate = new DateTime(date('Y-m-d'));
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
		$qry .= " AND date(Date) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
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

function getcountAMBSum($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{

    // Get the number of days in the selected month
   if (isset($_REQUEST['search'])) {
		if($qryfield1=='')
		{
			if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$startDate = new DateTime($_REQUEST['fromDate']);
				 $endDate = new DateTime($_REQUEST['toDate']);
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(Date) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		}
		else {
			
			$qry .=" and date(Date) like'".$qryfield1."%'";
		}
		
		if (isset($_REQUEST['district_code'])) {
			$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
			if (!empty($districtType)) {
				
				 $districtTypeList = "'" . implode("','", $districtType) . "'"; // Convert to integer to prevent SQL injection
				$qry .= " AND District IN ($districtTypeList)";
			}	
		}
		if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {
		
        $userTypes = array_filter($_REQUEST['uType']); // Remove empty values
		//print_r($userTypes);
        if (!empty($userTypes)) {
             $userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
			$qry .= " AND Organization IN ($userTypeList)";
        }
		
		
    }
	}
    else {
		$startDate = new DateTime(date('Y-m-01'));
		$endDate = new DateTime(date('Y-m-d'));
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
		$qry .= " AND date(Date) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
	}

    $qryv = '';
    if ($qryfield1 != '') {
        $qryv = " AND $qryfield1='$value1'";
    }
    $query = "SELECT  sum($field) as total FROM $tablename WHERE $qryfield='$value' $qryv $qry";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_object($result);

    return $row->total;
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
                        <li class="breadcrumb-item active"><i class="fa fa-calandar"></i>AMB School & VHSND Analysis</li>
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
							$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from alyuvdbzgkrn32dndgkebw");
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
							<a href="AMB_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
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
                            <td class="header1" style="font-size: 16px;" colspan="5" data-f-sz="16" data-a-h="center" data-fill-color="F2F2F2" data-f-color="800000" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-b-b-s="thick" data-b-b-c="0000FF" data-f-bold="true">Analysis - AMB Supportive Supervision at VHSND (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" data-f-bold="true" style="text-align: right!important;" data-a-h="right" data-fill-color="F2F2F2">Number of Supportive Supervision Visit Completed</td>
                            <td colspan="3" data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=$Va1g= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'District!', '', '', '') ?> <?php $totReco= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'District!', '', '', '') ?></td>

                        </tr>
                       
                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">General Information</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="3" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Designation of Respondent</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">ANM</td>
                            <td data-fill-color="F2F2F2"><?=$V2anm= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'Designation', 'anm', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'Designation!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($V2anm)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">AWW</td>
                            <td data-fill-color="F2F2F2"><?=$V2aww= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'Designation', 'aww', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($V2aww)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2">ASHA</td>
                            <td data-fill-color="F2F2F2"><?=$V2asha= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'Designation', 'asha', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($V2asha)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Training Status</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Received any training on AMB in last three years</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vb1y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_1_1_Have_you_receive_in_last_three_years', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vb1tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_1_1_Have_you_receive_in_last_three_years!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vb1y)*100)/($Vb1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2"><?=$Vb1n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_1_1_Have_you_receive_in_last_three_years', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vb1tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vb1n)*100)/($Vb1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Anemia Status</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Method is used to test/screen for anemia</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Color coded card</td>
                            <td data-fill-color="F2F2F2"><?=$Vc1= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te', 'color_coded_card', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc1)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Sahli's method	</td>
                            <td data-fill-color="F2F2F2"><?=$Vc2= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te', 'sahli_s_method', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc2)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right"  data-fill-color="F2F2F2">Digital Hemoglobinometer	</td>
                            <td data-fill-color="F2F2F2"><?=$Vc3= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te', 'digital_hemoglobinometer', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc3)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Others</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vc4= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te', 'others', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vctot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc4)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women status
</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Currently registered</td>
                            <td data-fill-color="F2F2F2"><?=$Vp1= getcountAMBSum($conn, 'afbocca6aqmgbuffdpstlq', '_4_3_1_Currently_registered_count', '_4_3_1_Currently_registered_count>', '0', '', '') ?></td>
                            <td data-fill-color="F2F2F2">-</td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF">-</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Screened anemic </td>
                            <td data-fill-color="F2F2F2"><?=$Vp3= getcountAMBSum($conn, 'afbocca6aqmgbuffdpstlq', '_4_3_2_Screened_anemic_count', '_4_3_2_Screened_anemic_count>', '0', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vp1?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vp3)*100)/($Vp1)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">On treatment	</td>
                            <td data-fill-color="F2F2F2"><?=$Vp4= getcountAMBSum($conn, 'afbocca6aqmgbuffdpstlq', '_4_3_3_On_treatment_count', '_4_3_3_On_treatment_count>', '0', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vp3?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vp4)*100)/($Vp3)),0) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"  data-a-h="right">Severely anemic referred	</td>
                            <td data-fill-color="F2F2F2"><?=$Vp5= getcountAMBSum($conn, 'afbocca6aqmgbuffdpstlq', '_4_3_4_Severely_anemic_referred_count', '_4_3_4_Severely_anemic_referred_count>', '0', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vp3?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vp5)*100)/($Vp3)),0) ?>%</td>
                        </tr>
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">IEC/BCC</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Any IEC material is displayed at the VHND/AWC</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Ve1y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_1_Whether_any_IEC_ayed_at_the_VHND_AWC', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Ve1tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_1_Whether_any_IEC_ayed_at_the_VHND_AWC!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve1y)*100)/($Ve1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"  data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Ve1n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_1_Whether_any_IEC_ayed_at_the_VHND_AWC', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Ve1tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve1n)*100)/($Ve1tot)),0) ?>%</td>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="3" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">FLW give advice on key messages to beneficiary</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Ve2y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_3_Did_the_FLW_give_ion_deworming_diet', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Ve2tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_3_Did_the_FLW_give_ion_deworming_diet!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve2y)*100)/($Ve2tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2"  data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" ><?=$Ve2n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_3_Did_the_FLW_give_ion_deworming_diet', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" ><?=$Ve2tot?></td>
                            <td data-fill-color="F2F2F2"  data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve2n)*100)/($Ve2tot)),0) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"  data-a-h="right">NA</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Ve2na= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_3_Did_the_FLW_give_ion_deworming_diet', 'na', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Ve2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve2na)*100)/($Ve2tot)),0) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="3" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Was any T3 camp organized in last three months in her catchment area</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Ve3y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_4_Was_any_T3_camp_your_catchment_area', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Ve3tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_4_Was_any_T3_camp_your_catchment_area!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve3y)*100)/($Ve3tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right"  >No</td>
                            <td data-fill-color="F2F2F2" ><?=$Ve3n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_4_Was_any_T3_camp_your_catchment_area', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" ><?=$Ve3tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve3n)*100)/($Ve3tot)),0) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" >NA</td>
                            <td data-fill-color="F2F2F2" ><?=$Ve3na= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_4_Was_any_T3_camp_your_catchment_area', 'na', '', '') ?></td>
                            <td data-fill-color="F2F2F2" ><?=$Ve3tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve3na)*100)/($Ve3tot)),0) ?>%</td>
                        </tr>
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">IFAs Supplies Status </th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Stock-out of IFA occurred in last 3 months
</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vf1y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_3_1_In_last_3_months', 'Yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vf1tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_3_1_In_last_3_months!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vf1y)*100)/($Vf1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"  data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vf1n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_3_1_In_last_3_months', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vf1tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vf1n)*100)/($Vf1tot)),0) ?>%</td>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Enough stock of IFA for next 3 months
</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Ve2y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_4_1_Next_3_months', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Ve2tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_4_1_Next_3_months!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve2y)*100)/($Ve2tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" >No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Ve2n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_4_1_Next_3_months', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Ve2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve2n)*100)/($Ve2tot)),0) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="3" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Available IFA within expiry date </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Ve3y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_5_Is_the_available_xpiry_date_Observe', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Ve3tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_5_Is_the_available_xpiry_date_Observe!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve3y)*100)/($Ve3tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right"  >No</td>
                            <td data-fill-color="F2F2F2" ><?=$Ve3n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_5_Is_the_available_xpiry_date_Observe', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Ve3tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve3n)*100)/($Ve3tot)),0) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" >NA</td>
                            <td data-fill-color="F2F2F2" ><?=$Ve3na= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_5_Is_the_available_xpiry_date_Observe', 'na', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Ve3tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve3na)*100)/($Ve3tot)),0) ?>%</td>
                        </tr>
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Knowledge </th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Respondent tell the common symptoms of Anemia </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2" data-a-h="center"><?=$Vg1y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_1_ANM', 'yes', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_2_ASHA', 'yes', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_3_AWW', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2" ><?=$Vg1tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_1_ANM!', '', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_2_ASHA!', '', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_3_AWW!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vg1y)*100)/($Vg1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" >No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vg1n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_1_ANM', 'no', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_2_ASHA', 'no', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_3_AWW', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vf1tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vg1n)*100)/($Vg1tot)),0) ?>%</td>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thick" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Respondent tell name of 5 iron rich food items </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vg2y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_1_ANM', 'yes', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_2_ASHA', 'yes', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_3_AWW', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vg2tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_1_ANM!', '', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_2_ASHA!', '', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_3_AWW!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vg2y)*100)/($Vg2tot)),0) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-b-b-s="thick" data-b-b-c="0000FF" >No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thick" data-b-b-c="0000FF"><?=$Vg2n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_1_ANM', 'no', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_2_ASHA', 'no', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_3_AWW', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thick" data-b-b-c="0000FF"><?=$Vg2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thick" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vg2n)*100)/($Vg2tot)),0) ?>%</td>
                        </tr>
						
						<tr><td colspan="4">&nbsp;</td></tr> 
						<tr><td colspan="4">&nbsp;</td></tr> 
						
						<tr>
                            <td class="header1" style="font-size: 16px;" colspan="5" data-f-sz="16" data-a-h="center" data-fill-color="F2F2F2" data-f-color="800000" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-b-b-s="thick" data-b-b-c="0000FF" data-f-bold="true">Analysis - AMB Supportive Supervision in Schools (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" data-f-bold="true" style="text-align: right!important;" data-a-h="right" data-fill-color="F2F2F2">Number of Supportive Supervision Completed</td>
                            <td colspan="3" data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=$Va1g= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', 'District!', '', '', '') ?> <?php $totReco= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', 'District!', '', '', '') ?></td>

                        </tr>
                       
                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Nodal Teacher designated for AMB and Training status</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Nodal teacher been designated for AMB in the school</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$V2anm=getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_1_Has_a_nodal_teac_or_AMB_in_the_school', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot=getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_1_Has_a_nodal_teac_or_AMB_in_the_school!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($V2anm)*100)/($V2tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$V2aww= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_1_Has_a_nodal_teac_or_AMB_in_the_school', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($V2aww)*100)/($V2tot)),0) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Nodal teacher received training on AMB in last three years</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$V2anmy=getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_2_Has_the_nodal_te_in_last_three_years', 'yes', '', '')?></td>
							<td data-fill-color="F2F2F2"><?=$V2tot1=getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_2_Has_the_nodal_te_in_last_three_years!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($V2anmy)*100)/($V2tot1)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2"><?=$V2awwn= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_2_Has_the_nodal_te_in_last_three_years', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot1?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($V2awwn)*100)/($V2tot1)),0) ?>%</td>
                        </tr>
                        
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Anemia Screening and Supplementation status</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Anaemia Screening conducted in the School</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vb1y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_3_2_Is_Anaemia_Scree_ducted_in_the_School', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vb1tot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_3_2_Is_Anaemia_Scree_ducted_in_the_School!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vb1y)*100)/($Vb1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vb1n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_3_2_Is_Anaemia_Scree_ducted_in_the_School', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vb1tot?></td>
                            <td data-fill-color="F2F2F2"  data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vb1n)*100)/($Vb1tot)),0) ?>%</td>
                        </tr>
						
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Weekly IFA been given to children in last 3 months every week</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vb1y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_1_Was_weekly_IFA_b_3_months_every_week', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vb1tot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_1_Was_weekly_IFA_b_3_months_every_week!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vb1y)*100)/($Vb1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vb1n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_1_Was_weekly_IFA_b_3_months_every_week', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vb1tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vb1n)*100)/($Vb1tot)),0) ?>%</td>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Last biannual deworming round conducted in the school</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vb1y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_4_Was_the_last_bia_ducted_in_the_school', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vb1tot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_4_Was_the_last_bia_ducted_in_the_school!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vb1y)*100)/($Vb1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2"><?=$Vb1n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_4_Was_the_last_bia_ducted_in_the_school', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vb1tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vb1n)*100)/($Vb1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">IEC/BCC</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">IEC material is displayed at the school</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vc1= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_5_1_Whether_any_IEC_played_at_the_school', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_5_1_Whether_any_IEC_played_at_the_school!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc1)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2"><?=$Vc2= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_5_1_Whether_any_IEC_played_at_the_school', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc2)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">IFAs Supplies Status</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Stock-out of IFA occurred in last 3 months</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vc1= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_3_Whether_any_stock_out_of_I', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_3_Whether_any_stock_out_of_I!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc1)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vc2= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_3_Whether_any_stock_out_of_I', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vctot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc2)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Enough stock of IFA for next 3 months</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vc1= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_4_Is_there_enough_stock_of_I', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_4_Is_there_enough_stock_of_I!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc1)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vc2= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_4_Is_there_enough_stock_of_I', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vctot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc2)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Available IFA within expiry date</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vc1= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_5_Is_the_available_IFA_withi', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_5_Is_the_available_IFA_withi!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc1)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"  data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" ><?=$Vc2= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_5_Is_the_available_IFA_withi', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" ><?=$Vctot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"  data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc2)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">IFA stored correctly as per the dry stock storage guidelines</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vc1= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_6_Is_the_IFA_store_guidelines_Observe', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_6_Is_the_IFA_store_guidelines_Observe!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc1)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2"><?=$Vc2= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_6_Is_the_IFA_store_guidelines_Observe', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vctot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Vc2)*100)/($Vctot)),1),1) ?>%</td>
                        </tr>
                        
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Recording Status</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="3" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Updated distribution register was available and updated</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Not available</td>
                            <td data-fill-color="F2F2F2"><?=$V2anm= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_3_Is_updated_distr_r_available_Observe', 'not_available', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_3_Is_updated_distr_r_available_Observe!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($V2anm)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Available and not updated</td>
                            <td data-fill-color="F2F2F2"><?=$V2aww= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_3_Is_updated_distr_r_available_Observe', 'available_and_not_updated', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($V2aww)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right"  data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF">Available and Updated</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$V2asha= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_3_Is_updated_distr_r_available_Observe', 'available_and_updated', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($V2asha)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="3" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Updated stock register was available and updated</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Not available</td>
                            <td data-fill-color="F2F2F2"><?=$V2anm= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_2_Is_updated_stock_r_available_Observe', 'not_available', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_2_Is_updated_stock_r_available_Observe!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($V2anm)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Available and not updated</td>
                            <td data-fill-color="F2F2F2"><?=$V2aww= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_2_Is_updated_stock_r_available_Observe', 'available_and_not_updated', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($V2aww)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2">Available and Updated</td>
                            <td data-fill-color="F2F2F2"><?=$V2asha= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_2_Is_updated_stock_r_available_Observe', 'available_and_updated', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($V2asha)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
						
						
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Knowledge </th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Respondent tell the common symptoms of Anemia
</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Vf1y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_1_Can_respondent_t_n_symptoms_of_Anemia', 'Yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Vf1tot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_1_Can_respondent_t_n_symptoms_of_Anemia!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vf1y)*100)/($Vf1tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"  data-a-h="right">No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vf1n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_1_Can_respondent_t_n_symptoms_of_Anemia', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Vf1tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Vf1n)*100)/($Vf1tot)),0) ?>%</td>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Respondent tell name of 5 iron rich food items
</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Ve2y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_2_Can_the_responde_iron_rich_food_items', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Ve2tot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_2_Can_the_responde_iron_rich_food_items!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve2y)*100)/($Ve2tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" >No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Ve2n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_2_Can_the_responde_iron_rich_food_items', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Ve2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve2n)*100)/($Ve2tot)),0) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thick" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Respondent know about benefits of IFA prophylaxis </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Yes</td>
                            <td data-fill-color="F2F2F2"><?=$Ve3y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_3_Do_the_responden_s_of_IFA_prophylaxis', 'yes', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$Ve3tot= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_3_Do_the_responden_s_of_IFA_prophylaxis!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve3y)*100)/($Ve3tot)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thick" data-b-b-c="0000FF" data-a-h="right"  >No</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thick" data-b-b-c="0000FF" ><?=$Ve3n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_3_Do_the_responden_s_of_IFA_prophylaxis', 'no', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thick" data-b-b-c="0000FF"><?=$Ve3tot?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thick" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($Ve3n)*100)/($Ve3tot)),0) ?>%</td>
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
            name: "AMB-Analysis.xlsx", // Set your desired file name here
            sheet: {
                name: "Analysis Report" // Set the sheet name here
            }
        });
    });
</SCRIPT>

</html>