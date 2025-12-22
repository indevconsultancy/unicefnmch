<?php include_once('includes/config.php'); ?>
<?php define("title", "Beneficiary Form Analysis  | UNICEF"); ?>
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
	$qry='';
   if (isset($_REQUEST['search'])) {
		if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$startDate = new DateTime($_REQUEST['fromDate']);
				 $endDate = new DateTime($_REQUEST['toDate']);
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(Date) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
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
			
			//$qry .=" and $qryfield1='".$value1."%'";
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
<title>Beneficiary Form Analysis</title>
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
                        <li class="breadcrumb-item active"><i class="fa fa-calandar"></i>IV Iron Sucrose Implementation Report</li>
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
							$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from a27ycjonhei3rt5h4badxn");
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
							$qryUserType = mysqli_query($conn,"SELECT DISTINCT type_mon FROM a27ycjonhei3rt5h4badxn ORDER BY type_mon ASC");
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
                            <td class="header1" style="font-size: 16px;" colspan="5" data-f-sz="16" data-a-h="center" data-fill-color="F2F2F2" data-f-color="800000" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-b-b-s="thick" data-b-b-c="0000FF" data-f-bold="true">Beneficiary Anemea Analysis(District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" data-f-bold="true" style="text-align: right!important;" data-a-h="right" data-fill-color="F2F2F2">Total Surveys Completed</td>
                            <td colspan="3" data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=$Va1g= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'District!', '', '', '') ?> <?php $totReco= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'District!', '', '', '') ?></td>

                        </tr>
                       
                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">General Information</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Beneficiary Type</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Children (5-9 years)</td>
                            <td data-fill-color="F2F2F2"><?=$cat1= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'children__5_9_years', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($cat1)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Adolescents (10-19 years)</td>
                            <td data-fill-color="F2F2F2"><?=$cat2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'adolescents__10_19_years', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($cat2)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Lactating Woman</td>
                            <td data-fill-color="F2F2F2"><?=$cat3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'lactating_woman', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($cat3)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Pregnant Woman (2nd / 3rd Trimester)</td>
                            <td data-fill-color="F2F2F2"><?=$cat4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($cat4)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2">Women of reproductive age group (20-49 years)</td>
                            <td data-fill-color="F2F2F2"><?=$cat5= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'women_of_reproductive_age_grou', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$V2tot?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($cat5)*100)/($V2tot)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Anemia Status</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Beneficiary Tested for Anemia</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Adolescents (10-19 years)</td>
                            <td data-fill-color="F2F2F2"><?=$as2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'adolescents__10_19_years', '_2_9_Was_your_haemoglobin_teste', 'yes') ?> 
							</td>
                            <td data-fill-color="F2F2F2"><?=$cat2?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($as2)*100)/($cat2)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Lactating Woman</td>
                            <td data-fill-color="F2F2F2"><?=$as3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'lactating_woman', '_3_1_Was_your_haemoglobin_teste', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat3?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($as3)*100)/($cat3)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Pregnant Woman (2nd / 3rd Trimester)</td>
                            <td data-fill-color="F2F2F2"><?=$as4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim','_4_1_Was_your_haemoglobin_teste', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($as4)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2">Women of reproductive age group (20-49 years)</td>
                            <td data-fill-color="F2F2F2"><?=$as5= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'women_of_reproductive_age_grou', '_5_9_Was_your_haemoglobin_teste', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat5?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($as5)*100)/($cat5)),1),1) ?>%</td>
                        </tr>
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">IFA Status</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Beneficiary Provided IFA Tablet/Syrup</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Adolescents (10-19 years)</td>
                            <td data-fill-color="F2F2F2"><?=$af2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'adolescents__10_19_years', '_1_1_Did_you_receive_IFA_Tablet', 'yes') ?></td>
							</td>
                            <td data-fill-color="F2F2F2"><?=$cat2?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($af2)*100)/($cat2)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Lactating Woman</td>
                            <td data-fill-color="F2F2F2"><?=$af3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'lactating_woman', '_3_12_Did_you_receive_IFA_red_t', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat3?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($af3)*100)/($cat3)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Pregnant Woman (2nd / 3rd Trimester)</td>
                            <td data-fill-color="F2F2F2"><?=$af4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', '_4_6_Which_of_these_are_you_tak__ifa_red_tablet', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($af4)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2">Women of reproductive age group (20-49 years)</td>
                            <td data-fill-color="F2F2F2"><?=$af5= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'women_of_reproductive_age_grou', '_5_1_Did_you_receive_IFA_Tablet', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat5?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($af5)*100)/($cat5)),1),1) ?>%</td>
                        </tr>
						
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Awareness and Knowledge of Anemia</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Respondents who can correctly identify anemia symptoms</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Adolescents (10-19 years)</td>
                            <td data-fill-color="F2F2F2"><?=$ias2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'adolescents__10_19_years', '_2_11_Can_respondent_n_symptoms_of_Anemia', 'yes') ?> </td></td>
                            <td data-fill-color="F2F2F2"><?=$cat2?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($ias2)*100)/($cat2)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Lactating Woman</td>
                            <td data-fill-color="F2F2F2"><?=$ias3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'lactating_woman', '_3_15_Can_respondent_n_symptoms_of_Anemia', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat3?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($ias3)*100)/($cat3)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Pregnant Woman (2nd / 3rd Trimester)</td>
                            <td data-fill-color="F2F2F2"><?=$ias4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', '_4_11_Can_respondent_n_symptoms_of_Anemia', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($ias4)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2">Women of reproductive age group (20-49 years)</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" ><?=$ias5= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'women_of_reproductive_age_grou', '_5_12_Can_respondent_n_symptoms_of_Anemia', 'yes') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cat5?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($ias5)*100)/($cat5)),1),1) ?>%</td>
                        </tr>
						
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Respondents who can name five iron-rich foods</td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Adolescents (10-19 years)</td>
                            <td data-fill-color="F2F2F2"><?=$irf2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'adolescents__10_19_years', '_2_12_Can_the_respond_iron_rich_food_items', 'yes') ?> <?php $V2tot= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', '_2_12_Can_the_respond_iron_rich_food_items!', '', '', '') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat2?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($irf2)*100)/($cat2)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Lactating Woman</td>
                            <td data-fill-color="F2F2F2"><?=$irf3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'lactating_woman', '_3_16_Can_the_respond_iron_rich_food_items', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat3?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($irf3)*100)/($cat3)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Pregnant Woman (2nd / 3rd Trimester)</td>
                            <td data-fill-color="F2F2F2"><?=$irf4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', '_4_12_Can_the_respond_iron_rich_food_items', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($irf4)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-b-b-s="thin" data-b-b-c="0000FF" data-fill-color="F2F2F2">Women of reproductive age group (20-49 years)</td>
                            <td data-fill-color="F2F2F2"><?=$irf5= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'women_of_reproductive_age_grou', '_5_13_Can_the_respond_iron_rich_food_items', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat5?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($irf5)*100)/($cat5)),1),1) ?>%</td>
                        </tr>
						
						
						
						
						
						
						<tr>
                            <td class="header1" style="font-size: 16px;" colspan="5" data-f-sz="16" data-a-h="center" data-fill-color="F2F2F2" data-f-color="800000" data-b-r-s="thick" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000" data-b-b-s="thick" data-b-b-c="0000FF" data-f-bold="true">Pregnant Women Behavioural Understandings Report </td>
                        </tr>
						
						
						
                        <tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Awareness and Knowledge of Anemia</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Awareness and Knowledge of Anemia among Pregnant Women </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Pregnant Women have heard of anemia and its risks during pregnancy</td>
                            <td data-fill-color="F2F2F2"><?=$ha=getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim','heard_anaemia', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($ha)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right">Respondents able to identify at least two symptoms of anemia during pregnancy	</td>
                            <td data-fill-color="F2F2F2 " data-b-b-s="thin" data-b-b-c="0000FF"><?=$sya= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_anaemia', 'yes') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($sya)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Common signs, and symptoms of anaemia during pregnancy reported </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Weakness, tiredness, fatigue</td>
                            <td data-fill-color="F2F2F2"><?=$csa1= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_anaemia__1', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($csa1)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Paleness or pallor (Tongue, Skin.) </td>
                            <td data-fill-color="F2F2F2"><?=$csa2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_anaemia__2', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($csa2)*100)/($cat4)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Lethargy, lack of interest in doing anything	</td>
                            <td data-fill-color="F2F2F2"><?=$csa3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_anaemia__3', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($csa3)*100)/($cat4)),0) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Shortness of breath	</td>
                            <td data-fill-color="F2F2F2"><?=$csa4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_anaemia__4', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($csa4)*100)/($cat4)),0) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"  data-a-h="right">Others	</td>
                            <td data-fill-color="F2F2F2"  data-b-b-s="thin" data-b-b-c="0000FF"><?=$csa99= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_anaemia__99', '1') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($csa99)*100)/($cat4)),0) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women confidence in identifying signs of anemia in themselves </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Confident</td>
                            <td data-fill-color="F2F2F2"><?=$idsa1= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_of_anaemia', '4') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($idsa1)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Very confident	</td>
                            <td data-fill-color="F2F2F2"><?=$idsa5= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_of_anaemia', '5') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($idsa5)*100)/($cat4)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Somewhat confident </td>
                            <td data-fill-color="F2F2F2"><?=$idsa2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_of_anaemia', '3') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($idsa2)*100)/($cat4)),0) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Little confident	</td>
                            <td data-fill-color="F2F2F2"><?=$idsa3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_of_anaemia', '2') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($idsa3)*100)/($cat4)),0) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2"  data-b-b-s="thin" data-b-b-c="0000FF">Not confident at all	</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$idsa4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'signs_of_anaemia', '1') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=round(((($idsa4)*100)/($cat4)),0) ?>%</td>
                        </tr>
						
						
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Dietary Practices</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" colspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women able to consume iron-rich foods regularly</td>
                           
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cirfy1= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'consume_iron_rich_food!', '4') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cirfa1= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'consume_iron_rich_food!', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($cirfy1)*100)/($cirfa1)),1),1) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" colspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women who consumed iron-rich foods in the last 24 hours</td>
                           
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cirfy= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'consume_ironrich', 'yes') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cirfa= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'consume_ironrich!', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($cirfy)*100)/($cirfa)),1),1) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" colspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women able to prepare iron-rich food for themselves</td>
                           
                           <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cirfy2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'prepare_iron_rich_food!', '1') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cirfa2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'prepare_iron_rich_food!', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($cirfy2)*100)/($cirfa2)),1),1) ?>%</td>
                        </tr>
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" colspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women who faced pressure to avoid certain foods during pregnancy</td>
                           
                           <td data-fill-color="F2F2F2"><?=$cirfy3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'avoid_foods', 'yes') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cirfa3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'avoid_foods!', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($cirfy3)*100)/($cirfa3)),1),1) ?>%</td>
                        </tr>
						
						
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Accessibility of IFA Supplements</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" colspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women who received iron supplements from ANM/Health Facility</td>
                           
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$aoifas= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'received_ifa_flw', 'yes') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$aoifasAll= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'received_ifa_flw!', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($aoifas)*100)/($cirfa3)),1),1) ?>%</td>
                        </tr>
                        
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="4" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Challenges faced by the Pregnant Women in accessing enough Iron tablets from the government </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Tablets not available</td>
                             <td data-fill-color="F2F2F2"><?=$govtTab= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'ifa_tablet_challenges__1', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$govtTabAll= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'ifa_tablet_challenges!', '') ?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($govtTab)*100)/($govtTabAll)),1),1) ?>%</td>
							
							
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Did not go for health checkup at VHSND / Health Facility </td>
                             <td data-fill-color="F2F2F2"><?=$govtTab1= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'ifa_tablet_challenges__2', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$govtTabAll?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($govtTab1)*100)/($govtTabAll)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Health care providers were not available	</td>
                             <td data-fill-color="F2F2F2"><?=$govtTab2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'ifa_tablet_challenges__3', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$govtTabAll?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($govtTab2)*100)/($govtTabAll)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Others	</td>
                             <td data-fill-color="F2F2F2"><?=$govtTab3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'ifa_tablet_challenges__99', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$govtTabAll?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($govtTab3)*100)/($govtTabAll)),1),1) ?>%</td>
                        </tr>
						
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">IFA Supplement Compliance</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" colspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women who took iron supplements during their current or previous pregnancy</td>
                           
                             <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$ifaCom= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'take_ifa', 'yes') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$ifaComALL=getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'take_ifa!', '')?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($ifaCom)*100)/($ifaComALL)),1),1) ?>%</td>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" colspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant women who consumed more than 7 tablets in last 7 days</td>
                           
                             <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$ifaCom2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'how_many_ifa>', '7') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$ifaCom2ALL=getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'how_many_ifa!', '')?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($ifaCom2)*100)/($ifaCom2ALL)),1),1) ?>%</td>
                        </tr>
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Risk Perception and Initiative</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" colspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women willing to buy and consume iron tablets in the absence of government distribution</td>
                           
                             <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$Rpai= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'would_you_buy_ifa', 'yes') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$RpaiALL=getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'would_you_buy_ifa!', '')?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($Rpai)*100)/($RpaiALL)),1),1) ?>%</td>
                        </tr>
						
						
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Adherence Challenges</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="1" colspan="2" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Pregnant Women who experienced side effects after taking iron supplements</td>
                           
                             <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$ACai= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'experienced_side_effects', 'yes') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$ACaiALL=getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'experienced_side_effects!', '')?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($ACai)*100)/($ACaiALL)),1),1) ?>%</td>
                        </tr>
						
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Reported side effects </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Vomitting</td>
                           <td data-fill-color="F2F2F2"><?=$rsideEfect1= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'experienced_side_effects_details__1', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$rsideEfectALL=getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'experienced_side_effects', 'yes')?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($rsideEfect1)*100)/($rsideEfectALL)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Nausea </td>
                            <td data-fill-color="F2F2F2"><?=$rsideEfect2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'experienced_side_effects_details__2', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$rsideEfectALL?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($rsideEfect2)*100)/($rsideEfectALL)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Constipation	</td>
                            <td data-fill-color="F2F2F2"><?=$rsideEfect3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'experienced_side_effects_details__3', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$rsideEfectALL?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($rsideEfect3)*100)/($rsideEfectALL)),1),1) ?>%</td>
                        </tr>
						 <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Indigestion	</td>
                            <td data-fill-color="F2F2F2"><?=$rsideEfect4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'experienced_side_effects_details__4', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$rsideEfectALL?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($rsideEfect4)*100)/($rsideEfectALL)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2"  data-b-b-s="thin" data-b-b-c="0000FF" >Others	</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$rsideEfect99= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'experienced_side_effects_details__99', '1') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$rsideEfectALL?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($rsideEfect99)*100)/($rsideEfectALL)),1),1) ?>%</td>
                        </tr>
						
						
						
						<tr>
                            <th class="sub-header" colspan="2" data-f-bold="true" data-f-sz="14" data-fill-color="800000" data-f-color="FFFFFFFF">Communication Channels for Dissemination of Information on Anemia Prevention</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Num</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000">Den</th>
                            <th class="sub-header1" data-f-sz="14" data-fill-color="F4C6A1" data-f-color="000000" data-b-r-s="thick" data-b-r-c="0000FF">%</th>
                        </tr>
						
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Sources of Information Regarding Anemia Prevention </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">Radio/ TV</td>
                            <td data-fill-color="F2F2F2"><?=$soiAne1= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_anaemia__1', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($soiAne1)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">Mobile massages/ social media </td>
                            <td data-fill-color="F2F2F2"><?=$soiAne2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_anaemia__2', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($soiAne2)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">IVRS	</td>
                            <td data-fill-color="F2F2F2"><?=$soiAne3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_anaemia__3', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($soiAne3)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
						 <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">IEC/Mid media	</td>
                           <td data-fill-color="F2F2F2"><?=$soiAne4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_anaemia__4', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($soiAne4)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF">Others	</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$soiAne99= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_anaemia__99', '1') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($soiAne99)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
						
						
						<tr data-weight="140">
                            <td style="font-weight: 600; border-bottom: 1px solid black;text-align:left!important;vertical-align:middle;text-align:left!important;vertical-align:middle;" data-b-b-s="thin" data-b-b-c="0000FF" rowspan="5" data-a-v="middle" data-f-bold="true" data-fill-color="F2F2F2">Preferred Sources of Information Regarding Anemia Prevention </td>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right" data-a-h="right">ASHA/AWW</td>
                            <td data-fill-color="F2F2F2"><?=$anemeaPrev1= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_healthy_diet_whom__1', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($anemeaPrev1)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-fill-color="F2F2F2" data-a-h="right">ANM </td>
                            <td data-fill-color="F2F2F2"><?=$anemeaPrev2= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_healthy_diet_whom__2', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($anemeaPrev2)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
                        <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Doctor	</td>
                            <td data-fill-color="F2F2F2"><?=$anemeaPrev3= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_healthy_diet_whom__3', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($anemeaPrev3)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
						 <tr>
                            <td class="tdhead" data-a-h="right" data-fill-color="F2F2F2">Faith Leaders	</td>
                            <td data-fill-color="F2F2F2"><?=$anemeaPrev4= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_healthy_diet_whom__4', '1') ?></td>
                            <td data-fill-color="F2F2F2"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($anemeaPrev4)*100)/($cat4)),1),1) ?>%</td>
                        </tr>
						<tr>
                            <td class="tdhead"  data-b-b-s="thin" data-b-b-c="0000FF" data-a-h="right" data-fill-color="F2F2F2">Others	</td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$anemeaPrev99= getcountAMB($conn, 'a27ycjonhei3rt5h4badxn', 'id', 'Beneficiary_type', 'pregnant_woman__2nd___3rd_trim', 'info_healthy_diet_whom__99', '1') ?></td>
                            <td data-fill-color="F2F2F2" data-b-b-s="thin" data-b-b-c="0000FF"><?=$cat4?></td>
                            <td data-fill-color="F2F2F2"  data-b-b-s="thin" data-b-b-c="0000FF" data-b-r-s="thick" data-b-r-c="0000FF"><?=number_format(round(((($anemeaPrev99)*100)/($cat4)),1),1) ?>%</td>
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
            name: "Beneficiary-Analysis.xlsx", // Set your desired file name here
            sheet: {
                name: "Beneficiary Analysis Report" // Set the sheet name here
            }
        });
    });
</SCRIPT>

</html>