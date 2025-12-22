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

$month = date('m'); // October
$year = date('Y');
$mindate = '2024-01';
$maxdate = $year . '-' . $month;
/*
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
    $qry = " and date(Date) like'" . $maxdate . "%'";
}
*/
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

	<style type="text/css">
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:Arial, sans-serif; font-size:15px; }
		a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  } 
		a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  } 
		comment { display:none;  } 
	</style>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>	
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
         </div>
<table cellspacing="0" border="0" id="simpleTable1" data-cols-width="5,20,10,5,5,10,5,35,7,7">
	<colgroup width="85"></colgroup>
	<colgroup span="2" width="92"></colgroup>
	<colgroup width="76"></colgroup>
	<colgroup width="112"></colgroup>
	<colgroup width="40"></colgroup>
	<tr>
	  <td colspan="10" height="25" align="center" valign="middle" bgcolor="#E59EDD" 
		  data-f-sz="15" data-a-h="center" data-fill-color="E59EDD" data-f-color="000000" 
		  data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font size="4" color="#000000">Analysis - AMB Supportive Supervision at VHSND  (<?php if($first==$last) { ?> <?=date('M-Y',strtotime($last))?> <?php } else { ?> <?=date('M',strtotime($first))?> - <?=date('M Y',strtotime($last))?> <?php } ?>)</font></b>
	  </td>
	</tr>

	<tr>
		<td height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><b><font color="#000000"><br></font></b></td>
		<td align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><br></font></td>
		<td align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><b><font color="#000000"><br></font></b></td>
	</tr>
	<tr>
	  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
		  height="20" align="center" valign="middle" bgcolor="#E59EDD" 
		  data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
		  data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">A</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
		  colspan="6" align="left" valign="middle" bgcolor="#E59EDD" 
		  data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
		  data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">General Information</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
		  bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
		  data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">District</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
		  bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
		  data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">n</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
		  align="right" valign="middle" bgcolor="#F2AA84" data-fill-color="F2AA84" 
		  data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" 
		  data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">%   </font></b>
	  </td>
	</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">A.1</font></b></td>
		<td style="border-bottom: 2px double #000000; border-left: 2px solid #000000" colspan=6 rowspan=2 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Number of Supportive Supervision Visit Completed</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="21" sdnum="1033;"><font color="#000000"><?=$Va1g= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'District!', '', '', '') ?> <?php $totReco= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'District!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.525" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Va1g)*100)/($totReco)),0) ?>%</font></b></td>
	</tr>
	
	<tr>
		<td style="border-bottom: 2px double #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><b><font color="#000000"><br></font></b></td>
		<td style="border-bottom: 2px double #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Total</font></td>
		<td style="border-bottom: 2px double #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="40" sdnum="1033;"><font color="#000000"><?=$totReco?></font></td>
		<td style="border-bottom: 2px double #000000; border-right: 2px solid #000000" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><b><font color="#000000"><br></font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td style="border-left: 2px solid #000000" colspan=6 rowspan=3 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Designation of Respondent</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">ANM</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="33" sdnum="1033;"><font color="#000000"><?=$V2anm= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'Designation', 'anm', '', '') ?> <?php $V2tot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'Designation!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.825" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($V2anm)*100)/($V2tot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">A.2</font></b></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">AWW</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="5" sdnum="1033;"><font color="#000000"><?=$V2aww= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'Designation', 'aww', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.125" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($V2aww)*100)/($V2tot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">ASHA</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="2" sdnum="1033;"><font color="#000000"><?=$V2asha= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', 'Designation', 'asha', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.05" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($V2asha)*100)/($V2tot)),0) ?>%</font></b></td>
	</tr>
	<tr>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
      height="20" align="center" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">B</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
      colspan="6" align="left" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Training Status</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Yes (n)</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Den</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
      align="right" valign="middle" bgcolor="#F2AA84" data-fill-color="F2AA84" 
      data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" 
      data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">%   </font></b>
  </td>
</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">B.1</font></b></td>
		<td style="border-left: 2px solid #000000" colspan=6 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Received any training on AMB in last three years</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="16" sdnum="1033;"><font color="#000000"><?=$Vb1y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_1_1_Have_you_receive_in_last_three_years', 'yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="24" sdnum="1033;"><font color="#000000"><?=$Vb1n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_1_1_Have_you_receive_in_last_three_years!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.4" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vb1y)*100)/($Vb1n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#E59EDD"><b><font color="#000000">C</font></b></td>
		<td style="border-top: 2px solid #000000; border-left: 2px solid #000000" colspan=6 align="left" valign=middle bgcolor="#E59EDD"><b><font color="#000000">Anemia Screening Method</font></b></td>
		<td style="border-top: 2px solid #000000" align="right" valign=middle bgcolor="#F2AA84"><b><font color="#000000">Status</font></b></td>
		<td style="border-top: 2px solid #000000" align="right" valign=middle bgcolor="#F2AA84"><b><font color="#000000">n</font></b></td>
		<td style="border-top: 2px solid #000000; border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2AA84"><b><font color="#000000">%   </font></b></td>
	</tr>
	<tr>
		<td style="border-bottom: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" rowspan=4 height="81" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">C.1</font></b></td>
		<td style="border-left: 2px solid #000000" colspan=6 rowspan=4 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Method is used to test/screen for anemia</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Color coded card</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="26" sdnum="1033;"><font color="#000000"><?=$Vc1= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te', 'color_coded_card', '', '') ?> <?php $Vctot= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.666666666666667" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vc1)*100)/($Vctot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Sahli's method</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0" sdnum="1033;"><font color="#000000"><?=$Vc2= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te', 'sahli_s_method', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vc2)*100)/($Vctot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Digital Hemoglobinometer</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="10" sdnum="1033;"><font color="#000000"><?=$Vc3= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te', 'digital_hemoglobinometer', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.256410256410256" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vc3)*100)/($Vctot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Others</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="3" sdnum="1033;"><font color="#000000"><?=$Vc4= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_3_1_Which_method_is_used_to_te', 'others', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.0769230769230769" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vc4)*100)/($Vctot)),0) ?>%</font></b></td>
	</tr>
	<tr>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
      height="20" align="center" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">D</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
      colspan="6" align="left" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Pregnant Women status</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Status</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">n</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
      align="right" valign="middle" bgcolor="#F2AA84" data-fill-color="F2AA84" 
      data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" 
      data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">%   </font></b>
  </td>
</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td style="border-bottom: 2px double #000000; border-left: 2px solid #000000" colspan=6 rowspan=4 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Pregnant Women status</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Currently registered</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="393" sdnum="1033;"><font color="#000000"><?=$Vp1= getcountAMBSum($conn, 'afbocca6aqmgbuffdpstlq', '_4_3_1_Currently_registered_count', '_4_3_1_Currently_registered_count>', '0', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
	</tr>
	<!--<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Average PW/VHSND site registered</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="9.825" sdnum="1033;0;0.0"><font color="#000000"><?=round(($Vp1/$totReco),1)?></font></td>
		<td style="border-right: 2px solid #000000" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
	</tr>-->
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">D.1</font></b></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Screened anemic</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="39" sdnum="1033;"><font color="#000000"><?=$Vp3= getcountAMBSum($conn, 'afbocca6aqmgbuffdpstlq', '_4_3_2_Screened_anemic_count', '_4_3_2_Screened_anemic_count>', '0', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.820512820512821" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vp3)*100)/($Vp1)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"> On treatment</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="32" sdnum="1033;"><font color="#000000"><?=$Vp4= getcountAMBSum($conn, 'afbocca6aqmgbuffdpstlq', '_4_3_3_On_treatment_count', '_4_3_3_On_treatment_count>', '0', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.820512820512821" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vp4)*100)/($Vp3)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-bottom: 2px double #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Severely anemic referred</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0" sdnum="1033;"><font color="#000000"><?=$Vp5= getcountAMBSum($conn, 'afbocca6aqmgbuffdpstlq', '_4_3_4_Severely_anemic_referred_count', '_4_3_4_Severely_anemic_referred_count>', '0', '', '') ?></font></td>
		<td style="border-bottom: 2px double #000000; border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vp5)*100)/($Vp3)),0) ?>%</font></b></td>
	</tr>
	<tr>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
      height="21" align="center" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">E</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
      colspan="6" align="left" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">IEC/BCC</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Yes (n)</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Den</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
      align="right" valign="middle" bgcolor="#F2AA84" data-fill-color="F2AA84" 
      data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" 
      data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">%   </font></b>
  </td>
</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">E.1</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Any IEC material is displayed at the VHND/AWC</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="1" sdnum="1033;"><font color="#000000"><?=$Ve1y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_1_Whether_any_IEC_ayed_at_the_VHND_AWC', 'yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="39" sdnum="1033;"><font color="#000000"><?=$Ve1n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_1_Whether_any_IEC_ayed_at_the_VHND_AWC!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.025" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Ve1y)*100)/($Ve1n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">E.2</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">FLW give advice on key messages to beneficiary</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="25" sdnum="1033;"><font color="#000000"><?=$Ve2y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_3_Did_the_FLW_give_ion_deworming_diet', 'yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="13" sdnum="1033;"><font color="#000000"><?=$Ve2n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_3_Did_the_FLW_give_ion_deworming_diet!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.657894736842105" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Ve2y)*100)/($Ve2n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">E.3</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-bottom: 2px solid #000000; border-left: 2px solid #000000"><font color="#000000">Was any T3 camp organized in last three months in her catchment area</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="3" sdnum="1033;"><font color="#000000"><?=$Ve3y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_4_Was_any_T3_camp_your_catchment_area', 'yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="36" sdnum="1033;"><font color="#000000"><?=$Ve3n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_5_4_Was_any_T3_camp_your_catchment_area!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.0769230769230769" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Ve3y)*100)/($Ve3n)),0) ?>%</font></b></td>
	</tr>
	<tr>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
      height="20" align="center" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">F</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
      colspan="6" align="left" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">IFAs Supplies Status</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Yes (n)</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Den</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
      align="right" valign="middle" bgcolor="#F2AA84" data-fill-color="F2AA84" 
      data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" 
      data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">%   </font></b>
  </td>
</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">F.1</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Stock-out of IFA occurred in last 3 months</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="4" sdnum="1033;"><font color="#000000"><?=$Vf1y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_3_1_In_last_3_months', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="36" sdnum="1033;"><font color="#000000"><?=$Vf1n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_3_1_In_last_3_months!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.1" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vf1y)*100)/($Vf1n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">F.2</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Enough stock of IFA for next 3 months</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="19" sdnum="1033;"><font color="#000000"><?=$Vf2y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_4_1_Next_3_months', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="6" sdnum="1033;"><font color="#000000"><?=$Vf2n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_4_1_Next_3_months!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.76" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vf2y)*100)/($Vf2n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">F.3</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Available IFA within expiry date</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="19" sdnum="1033;"><font color="#000000"><?=$Vf3y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_5_Is_the_available_xpiry_date_Observe', 'yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="20" sdnum="1033;"><font color="#000000"><?=$Vf3n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_6_5_Is_the_available_xpiry_date_Observe!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.487179487179487" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vf3y)*100)/($Vf3n)),0) ?>%</font></b></td>
	</tr>
	<tr>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
      height="20" align="center" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">G</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
      colspan="6" align="left" valign="middle" bgcolor="#E59EDD" 
      data-fill-color="E59EDD" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Knowledge</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Yes (n)</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#F2AA84" data-fill-color="F2AA84" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Den</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
      align="right" valign="middle" bgcolor="#F2AA84" data-fill-color="F2AA84" 
      data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" 
      data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">%   </font></b>
  </td>
</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="20" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">G.1</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Respondent tell the common symptoms of Anemia</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="36" sdnum="1033;"><font color="#000000"><?=$Vg1y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_1_ANM', 'yes', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_2_ASHA', 'yes', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_3_AWW', 'yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="4" sdnum="1033;"><font color="#000000"><?=$Vg1n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_1_ANM!', '', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_2_ASHA!', '', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_1_3_AWW!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.9" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vg1y)*100)/($Vg1n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td data-b-b-s="thick" data-b-b-c="000000" style="border-bottom: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">G.2</font></b></td>
		<td data-b-b-s="thick" data-b-b-c="000000" colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-bottom: 2px solid #000000; border-left: 2px solid #000000"><font color="#000000">Respondent tell name of 5 iron rich food items</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td data-b-b-s="thick" data-b-b-c="000000" style="border-bottom: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="38" sdnum="1033;"><font color="#000000"><?=$Vg2y= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_1_ANM', 'yes', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_2_ASHA', 'yes', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_3_AWW', 'yes', '', '') ?></font></td>
		<td data-b-b-s="thick" data-b-b-c="000000" style="border-bottom: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="2" sdnum="1033;"><font color="#000000"><?=$Vg2n= getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_1_ANM!', '', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_2_ASHA!', '', '', '')+getcountAMB($conn, 'afbocca6aqmgbuffdpstlq', 'id', '_8_2_3_AWW!', '', '', '') ?></font></td>
		<td data-b-b-s="thick" data-b-b-c="000000" style="border-bottom: 2px solid #000000; border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.95" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($Vg2y)*100)/($Vg2n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td height="20" align="center" valign=middle><b><font color="#000000"><br></font></b></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="right" valign=middle><font color="#000000"><br></font></td>
		<td align="right" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><b><font color="#000000"><br></font></b></td>
	</tr>
	<tr>
		<td height="20" align="center" valign=middle><b><font color="#000000"><br></font></b></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><font color="#000000"><br></font></td>
		<td align="right" valign=middle><font color="#000000"><br></font></td>
		<td align="right" valign=middle><font color="#000000"><br></font></td>
		<td align="left" valign=middle><b><font color="#000000"><br></font></b></td>
	</tr>
	<tr>
		<td colspan=10 height="28" align="center" valign=middle bgcolor="#61CBF4" data-f-sz="15" data-a-h="center" data-fill-color="61CBF4" data-f-color="000000"><b><font size=4 color="#000000">Analysis - AMB Supportive Supervision in Schools (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>)</font></b></td>
	</tr>
	<tr>
		<td width="28" height="21" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-a-h="center" data-fill-color="F2F2F2" data-f-color="000000"><b><font color="#000000"><br></font></b></td>
		<td width="451" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"  data-a-h="center" data-fill-color="F2F2F2" data-f-color="000000"><font color="#000000"><br></font></td>
		<td width="1" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"  data-a-h="center" data-fill-color="F2F2F2" data-f-color="000000"><font color="#000000"><br></font></td>
		<td width="1" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"  data-a-h="center" data-fill-color="F2F2F2" data-f-color="000000"><font color="#000000"><br></font></td>
		<td width="1" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"  data-a-h="center" data-fill-color="F2F2F2" data-f-color="000000"><font color="#000000"><br></font></td>
		<td width="1" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"  data-a-h="center" data-fill-color="F2F2F2" data-f-color="000000"><font color="#000000"><br></font></td>
		<td width="3" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"  data-a-h="center" data-fill-color="F2F2F2" data-f-color="000000"><font color="#000000"><br></font></td>
		<td colspan="3" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"  data-a-h="center" data-fill-color="F2F2F2" data-f-color="000000"><strong>(<?php if($first==$last) { ?> <?=date('M-Y',strtotime($last))?> <?php } else { ?> <?=date('M',strtotime($first))?> - <?=date('M Y',strtotime($last))?> <?php } ?>)</strong></td>
		</tr>
	<tr>
		<td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#B4E5A2" data-fill-color="B4E5A2" data-f-color="000000" 
        data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true" ><b><font color="#000000">A</font></b></td>
		<td style="border-top: 2px solid #000000; border-left: 2px solid #000000" colspan=6 align="left" valign=middle bgcolor="#B4E5A2" data-fill-color="B4E5A2" data-f-color="000000" 
        data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true"><b><font color="#000000">General Information</font></b></td>
		<td width="234" align="right" valign=middle bgcolor="#61CBF4" style="border-top: 2px solid #000000" data-fill-color="61CBF4" data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true"><b><font color="#000000">District</font></b></td>
		<td width="85" align="right" valign=middle bgcolor="#61CBF4" style="border-top: 2px solid #000000" data-fill-color="61CBF4" data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true"><b><font color="#000000">n</font></b></td>
		<td width="200" align="right" valign=middle bgcolor="#61CBF4" style="border-top: 2px solid #000000; border-right: 2px solid #000000" data-fill-color="61CBF4" data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true" ><b><font color="#000000">%   </font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">A.1</font></b></td>
		<td style="border-left: 2px solid #000000" colspan=6 rowspan=2 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Number of Supportive Supervision Completed</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000"><?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="21" sdnum="1033;"><font color="#000000"><?=$a1g= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', 'District!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.488372093023256" sdnum="1033;0;0%"><b><font color="#000000"><?=round((($a1g*100)/getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', 'District!', '', '', '')),0)?>%</font></b></td>
	</tr>
	
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><b><font color="#000000"><br></font></b></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Total</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="43" sdnum="1033;"><font color="#000000"><?= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', 'District!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><b><font color="#000000"><br></font></b></td>
	</tr>
	<tr>
	  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
		  height="26" align="center" valign="middle" bgcolor="#B4E5A2" 
		  data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
		  data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">B</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
		  colspan="6" align="left" valign="middle" bgcolor="#B4E5A2" 
		  data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
		  data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">Nodal Teacher designated for AMB and Training status</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
		  bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
		  data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">Yes (n)</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
		  bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
		  data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">Den</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
		  align="right" valign="middle" bgcolor="#61CBF4" data-fill-color="61CBF4" 
		  data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">%   </font></b>
	  </td>
	</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">B.1</font></b></td>
		<td style="border-left: 2px solid #000000" colspan=6 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Nodal teacher been designated for AMB in the school</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="35" sdnum="1033;"><font color="#000000"><?=$b1y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_1_Has_a_nodal_teac_or_AMB_in_the_school', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="8" sdnum="1033;"><font color="#000000"><?=$b1n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_1_Has_a_nodal_teac_or_AMB_in_the_school!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.813953488372093" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($b1y)*100)/($b1n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">B.2</font></b></td>
		<td style="border-left: 2px solid #000000" colspan=6 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Nodal teacher received training on AMB in last three years</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="22" sdnum="1033;"><font color="#000000"><?=$b2y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_2_Has_the_nodal_te_in_last_three_years', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="21" sdnum="1033;"><font color="#000000"><?=$b2n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_1_2_Has_the_nodal_te_in_last_three_years!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.511627906976744" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($b2y)*100)/($b2n)),0) ?>%</font></b></td>
	</tr>
	<tr>
	  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
		  height="26" align="center" valign="middle" bgcolor="#B4E5A2" 
		  data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
		  data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">C</font></b>
	  </td>
	  <td colspan="6" align="left" valign="middle" bgcolor="#B4E5A2" 
		  style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
		  data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
		  data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">Anemia Screening and Supplementation status</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
		  bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
		  data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">Yes (n)</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
		  bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
		  data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">Den</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
		  align="right" valign="middle" bgcolor="#61CBF4" data-fill-color="61CBF4" 
		  data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">%   </font></b>
	  </td>
	</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">C.1</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Anaemia Screening conducted in the School</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="5" sdnum="1033;"><font color="#000000"><?=$c1y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_3_2_Is_Anaemia_Scree_ducted_in_the_School', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="38" sdnum="1033;"><font color="#000000"><?=$c1n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_3_2_Is_Anaemia_Scree_ducted_in_the_School!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.116279069767442" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($c1y)*100)/($c1n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">C.2</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Weekly IFA been given to children in last 3 months every week</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="28" sdnum="1033;"><font color="#000000"><?=$c2y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_1_Was_weekly_IFA_b_3_months_every_week', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="15" sdnum="1033;"><font color="#000000"><?=$c2n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_1_Was_weekly_IFA_b_3_months_every_week!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.651162790697675" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($c2y)*100)/($c2n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">C.3</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-bottom: 2px solid #000000; border-left: 2px solid #000000"><font color="#000000">Last biannual deworming round conducted in the school</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="38" sdnum="1033;"><font color="#000000"><?=$c3y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_4_Was_the_last_bia_ducted_in_the_school', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="5" sdnum="1033;"><font color="#000000"><?=$c3n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_4_4_Was_the_last_bia_ducted_in_the_school!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.883720930232558" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($c3y)*100)/($c3y+$c3n)),0) ?>%</font></b></td>
	</tr>
	<tr>
	  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
		  height="26" align="center" valign="middle" bgcolor="#B4E5A2" 
		  data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
		  data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">D</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
		  colspan="6" align="left" valign="middle" bgcolor="#B4E5A2" 
		  data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
		  data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">IEC/BCC</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
		  bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
		  data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">Yes (n)</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
		  bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
		  data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">Den</font></b>
	  </td>
	  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
		  align="right" valign="middle" bgcolor="#61CBF4" data-fill-color="61CBF4" 
		  data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
		<b><font color="#000000">%   </font></b>
	  </td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">D.1</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-bottom: 2px solid #000000; border-left: 2px solid #000000"><font color="#000000">IEC material is displayed at the school</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="2" sdnum="1033;"><font color="#000000"><?=$d1y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_5_1_Whether_any_IEC_played_at_the_school', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="41" sdnum="1033;"><font color="#000000"><?=$d1n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_5_1_Whether_any_IEC_played_at_the_school!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.0465116279069767" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($d1y)*100)/($d1n)),0) ?>%</font></b></td>
	</tr>
	<tr>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
      height="26" align="center" valign="middle" bgcolor="#B4E5A2" 
      data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">E</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
      colspan="6" align="left" valign="middle" bgcolor="#B4E5A2" 
      data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">IFAs Supplies Status</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Yes (n)</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Den</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
      align="right" valign="middle" bgcolor="#61CBF4" data-fill-color="61CBF4" 
      data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">%   </font></b>
  </td>
</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">E.1</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Stock-out of IFA occurred in last 3 months</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="17" sdnum="1033;"><font color="#000000"><?=$e1y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_3_Whether_any_stock_out_of_I', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="26" sdnum="1033;"><font color="#000000"><?=$e1n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_3_Whether_any_stock_out_of_I!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.395348837209302" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($e1y)*100)/($e1n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">E.2</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Enough stock of IFA for next 3 months</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="13" sdnum="1033;"><font color="#000000"><?=$e2y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_4_Is_there_enough_stock_of_I', 'yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="30" sdnum="1033;"><font color="#000000"><?=$e2n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_4_Is_there_enough_stock_of_I!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.302325581395349" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($e2y)*100)/($e2n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">E.3</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Available IFA within expiry date</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="12" sdnum="1033;"><font color="#000000"><?=$e3y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_5_Is_the_available_IFA_withi', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="31" sdnum="1033;"><font color="#000000"><?=$e3n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_5_Is_the_available_IFA_withi!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.27906976744186" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($e3y)*100)/($e3n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-bottom: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">E.4</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-bottom: 2px solid #000000; border-left: 2px solid #000000"><font color="#000000">IFA stored correctly as per the dry stock storage guidelines</font><font color="#000000"><br></font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td style="border-bottom: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="28" sdnum="1033;"><font color="#000000"><?=$e4y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_6_Is_the_IFA_store_guidelines_Observe', 'Yes', '', '') ?></font></td>
		<td style="border-bottom: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="15" sdnum="1033;"><font color="#000000"><?=$e4n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_6_6_Is_the_IFA_store_guidelines_Observe!', '', '', '') ?></font></td>
		<td style="border-bottom: 2px solid #000000; border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.651162790697675" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($e4y)*100)/($e4n)),0) ?>%</font></b></td>
	</tr>
	<tr>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
      height="26" align="center" valign="middle" bgcolor="#B4E5A2" 
      data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">F</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
      colspan="6" align="left" valign="middle" bgcolor="#B4E5A2" 
      data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Recording</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Status</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">n</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
      align="right" valign="middle" bgcolor="#61CBF4" data-fill-color="61CBF4" 
      data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">%   </font></b>
  </td>
</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td style="border-bottom: 2px double #000000; border-left: 2px solid #000000" colspan=6 rowspan=3 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Updated stock register was available and updated</font></td>
		<td align="right" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Not available</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="30" sdnum="1033;"><font color="#000000"><?=$f11= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_2_Is_updated_stock_r_available_Observe', 'not_available', '', '') ?> <?php $f1tot=getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_2_Is_updated_stock_r_available_Observe!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.697674418604651" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($f11)*100)/($f1tot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">F.1</font></b></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Available and not updated</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="10" sdnum="1033;"><font color="#000000"><?=$f12= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_2_Is_updated_stock_r_available_Observe', 'available_and_not_updated', '', '') ?>  </font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.232558139534884" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($f12)*100)/($f1tot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-bottom: 2px double #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td style="border-bottom: 2px double #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Available and Updated</font></td>
		<td style="border-bottom: 2px double #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="3" sdnum="1033;"><font color="#000000"><?=$f13= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_2_Is_updated_stock_r_available_Observe', 'available_and_updated', '', '') ?></font></td>
		<td style="border-bottom: 2px double #000000; border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.0697674418604651" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($f13)*100)/($f1tot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td style="border-left: 2px solid #000000" colspan=6 rowspan=3 align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Updated distribution register was available and updated</font></td>
		<td align="right" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Not available</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="24" sdnum="1033;"><font color="#000000"><?=$f21= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_3_Is_updated_distr_r_available_Observe', 'not_available', '', '') ?> <?php $f2tot=getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_3_Is_updated_distr_r_available_Observe!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.558139534883721" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($f21)*100)/($f2tot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">F.2</font></b></td>
		<td align="right" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Available and not updated</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="10" sdnum="1033;"><font color="#000000"><?=$f22= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_3_Is_updated_distr_r_available_Observe', 'available_and_not_updated', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.232558139534884" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($f22)*100)/($f2tot)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000"><br></font></b></td>
		<td align="right" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2"><font color="#000000">Available and Updated</font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="9" sdnum="1033;"><font color="#000000"><?=$f23= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_7_3_Is_updated_distr_r_available_Observe', 'available_and_updated', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.209302325581395" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($f23)*100)/($f2tot)),0) ?>%</font></b></td>
	</tr>
	<tr>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" 
      height="26" align="center" valign="middle" bgcolor="#B4E5A2" 
      data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">G</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-left: 2px solid #000000" 
      colspan="6" align="left" valign="middle" bgcolor="#B4E5A2" 
      data-fill-color="B4E5A2" data-f-color="000000" data-b-t-s="thick" 
      data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Knowledge</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Yes (n)</font></b>
  </td>
  <td style="border-top: 2px solid #000000" align="right" valign="middle" 
      bgcolor="#61CBF4" data-fill-color="61CBF4" data-f-color="000000" 
      data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">Den</font></b>
  </td>
  <td style="border-top: 2px solid #000000; border-right: 2px solid #000000" 
      align="right" valign="middle" bgcolor="#61CBF4" data-fill-color="61CBF4" 
      data-f-color="000000" data-b-t-s="thick" data-b-t-c="000000" data-f-bold="true" data-a-v="middle" data-s-wrap="true">
    <b><font color="#000000">%   </font></b>
  </td>
</tr>

	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">G.1</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Respondent tell the common symptoms of Anemia</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="26" sdnum="1033;"><font color="#000000"><?=$g1y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_1_Can_respondent_t_n_symptoms_of_Anemia', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="17" sdnum="1033;"><font color="#000000"><?=$g1n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_1_Can_respondent_t_n_symptoms_of_Anemia!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.604651162790698" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($g1y)*100)/($g1n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td style="border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">G.2</font></b></td>
		<td colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-left: 2px solid #000000"><font color="#000000">Respondent tell name of 5 iron rich food items</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="31" sdnum="1033;"><font color="#000000"><?=$g2y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_2_Can_the_responde_iron_rich_food_items', 'Yes', '', '') ?></font></td>
		<td align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="12" sdnum="1033;"><font color="#000000"><?=$g2n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_2_Can_the_responde_iron_rich_food_items!', '', '', '') ?></font></td>
		<td style="border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.72093023255814" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($g2y)*100)/($g2n)),0) ?>%</font></b></td>
	</tr>
	<tr>
		<td data-b-b-s="thick" data-b-b-c="000000" style="border-bottom: 2px solid #000000; border-left: 2px solid #000000; border-right: 2px solid #000000" height="26" align="center" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdnum="1033;0;0%"><b><font color="#000000">G.3</font></b></td>
		<td data-b-b-s="thick" data-b-b-c="000000" colspan="6" align="left" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" style="border-bottom: 2px solid #000000; border-left: 2px solid #000000"><font color="#000000">Respondent know about benefits of IFA prophylaxis</font><font color="#000000"><br></font><font color="#000000"><br></font></td>
		<td data-b-b-s="thick" data-b-b-c="000000" style="border-bottom: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="31" sdnum="1033;"><font color="#000000"><?=$g3y= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_3_Do_the_responden_s_of_IFA_prophylaxis', 'Yes', '', '') ?></font></td>
		<td data-b-b-s="thick" data-b-b-c="000000" style="border-bottom: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="12" sdnum="1033;"><font color="#000000"><?=$g3n= getcountAMB($conn, 'alyuvdbzgkrn32dndgkebw', 'id', '_8_3_Do_the_responden_s_of_IFA_prophylaxis!', '', '', '') ?></font></td>
		<td data-b-b-s="thick" data-b-b-c="000000" style="border-bottom: 2px solid #000000; border-right: 2px solid #000000" align="right" valign=middle bgcolor="#F2F2F2" data-fill-color="F2F2F2" sdval="0.72093023255814" sdnum="1033;0;0%"><b><font color="#000000"><?=round(((($g3y)*100)/($g3n)),0) ?>%</font></b></td>
	</tr>
	
	
</table>
<!-- ************************************************************************** -->
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
