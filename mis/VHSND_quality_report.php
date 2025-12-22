<?php include_once('includes/config.php'); ?>
<?php define("title", "VHSND Quality Report | UNICEF"); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/header.php'); ?>
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


//$column1=$column2=$column3=$column4=$column5=$column6=$column7=$column8=$column9=[];
$weightage1=1;
$weightage2=1;
$weightage3=0.5;
$weightage4=0.5;
$weightage5=1;
$weightage6=1;
$weightage7=2;
$weightage8=1;
$weightage9=2;
$row=[];
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





function getcountAWCHVQR($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{
	// Get the number of days in the selected month
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
		//	$qry = " and dom like'" . $maxdate . "%'";
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
$sqlVHSNDvisit = mysqli_query($conn, "select * from amt67t3phss69dfpvrxqbf where 1=1 $qry");
$totalRecord= mysqli_num_rows($sqlVHSNDvisit);
								
									$weightage1tot=$weightage1*$totalRecord;
									$weightage2tot=$weightage2*$totalRecord;
									$weightage3tot=$weightage3*$totalRecord;
									$weightage4tot=$weightage4*$totalRecord;
									$weightage5tot=$weightage5*$totalRecord;
									$weightage6tot=$weightage6*$totalRecord;
									$weightage7tot=$weightage7*$totalRecord;
									$weightage8tot=$weightage8*$totalRecord;
									$weightage9tot=$weightage9*$totalRecord; 
							    ?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<title>VHSD Quality Parameter Analysis</title>
<style type="text/css">
	body {
		font-family: "Calibri";

	}

	.thead {
		border: 1px solid #000000;
		text-align: center;

	}

	.tsubhead {
		text-align: right;
		vertical-align: bottom;
		background-color: #92D050;

	}

	.tsubhead1 {
		text-align: center;
		vertical-align: middle;
		background-color: #FFFFFF;

	}

	.tsubhead3 {
		text-align: center;
		vertical-align: bottom;
		background-color: #FFFFFF;

	}

	.thead1 {
		border-top: 1px solid #000000;
		border-left: 1px solid #000000;
		border-right: 1px solid #000000;
		text-align: left;
		vertical-align: middle;
		background-color: #FFFFFF;
	}

	.thead2 {
		border-top: 1px solid #000000;
		border-bottom: 1px solid #000000;
		border-left: 1px solid #000000;
		border-right: 1px solid #000000;
		text-align: center;
		vertical-align: middle;

	}

	.thead3 {
		border-bottom: 1px solid #000000;
		border-left: 1px solid #000000;
		border-right: 1px solid #000000;
	}

	.thead4 {
		max-height: 20x;
		border-top: 1px solid #000000;
		border-bottom: 1px solid #000000;
		border-left: 1px solid #000000;
		border-right: 1px solid #000000;
		text-align: left;
		vertical-align: bottom;
	}

	.thead5 {
		text-align: center;
		vertical-align: middle;
		background-color: #F2F2F2;
	}

	.thead6 {
		text-align: left;
		vertical-align: middle;
		background-color: #F2F2F2;

	}

	.thead7 {
		border-top: 1px solid #000000;
		border-bottom: 1px solid #000000;
		border-left: 1px solid #000000;
		border-right: 1px solid #000000;
		text-align: center;
		vertical-align: middle;
	}

	/* #export-container {
		display: flex;
		justify-content: flex-left;
		margin-bottom: 10px;
	} */
</style>

<!-- <div id="export-container">
	<button id="button-excel">Export to Excel</button>
</div> -->

////////////////////////////////////////////////////
<?php
$tablevalues=''; 
						$i=0; //Row
						$j=0; //Column
						
						
						while ($dataVHSNDvisit = mysqli_fetch_object($sqlVHSNDvisit)) { 
							$tablevalues.='<tr>
								<td class="thead4">
									'.ucfirst($dataVHSNDvisit->district).'
								</td>
								<td class="thead4">
									'.ucfirst($dataVHSNDvisit->block).'
								</td>
								<td class="thead4">
									'.ucfirst($dataVHSNDvisit->village).'
								</td>
								<td class="thead4">
									'.ucfirst($dataVHSNDvisit->awc_name).'
								</td>
								<td class="thead4">
									'.ucfirst($dataVHSNDvisit->code_awc).'
								</td>
								<td class="thead" align="center" valign=middle bgcolor="#FFFFFF">
									<br>
								</td>
								<td class="thead tsubhead" align="center" style="text-align:center; valign=middle;" sdval="0" sdnum="1033;">
									';
									
									

									$sam_treatment = $dataVHSNDvisit->sam_treatment_med__anti+$dataVHSNDvisit->sam_treatment_med__foli+$dataVHSNDvisit->sam_treatment_med__albe+$dataVHSNDvisit->sam_treatment_med__vita+$dataVHSNDvisit->sam_treatment_med__iron+$dataVHSNDvisit->sam_treatment_med__multi;
									if($sam_treatment>=5){
										$c1='1';
									}else{
										$c1='0';
									}
									$column1[$i]=$c1;
									
								$tablevalues.= $c1.'</td>
								<td class="thead tsubhead" align="center"  style="text-align:center; valign=middle;" sdval="0" sdnum="1033;">';
									$sam_illness = $dataVHSNDvisit->sam_illness;
									if($sam_illness=='aware'){
										$c2='1';
									}else{
										$c2='0';
									}
									$column2[$i]=$c2;
								$tablevalues.= $c2.'</td>
								<td class="thead tsubhead" align="center"  style="text-align:center; valign=middle;" sdval="0.5" sdnum="1033;">';
								
									$sam_not_gain = $dataVHSNDvisit->sam_not_gain;
									if($sam_not_gain=='nrc'){
										$c3='0.5';
									}else{
										$c3='0';
									}
									$column3[$i]=$c3;
									
									$tablevalues.= $c3.'</td>
								<td class="thead tsubhead" align="center" style="text-align:center; valign=middle;" sdval="0" sdnum="1033;">';
								
								$sam_not_res = $dataVHSNDvisit->sam_not_res;
								if($sam_not_res=='refer_nrc'){
										$c4='0.5';
									}else{
										$c4='0';
									}
									$column4[$i]=$c4;							
								$tablevalues.= $c4.'</td>
								<td class="thead tsubhead" style="text-align:center; valign=middle;" sdval="0" sdnum="1033;">';
								
								$sam_duelist = $dataVHSNDvisit->sam_duelist;
									if($sam_duelist=='yes'){
										$c5='1';
									}else{
										$c5='0';
									}
									$column5[$i]=$c5;
									
								$tablevalues.=$c5.'</td>
								<td class="thead tsubhead" align="center"  style="text-align:center" sdval="0" sdnum="1033;">';
								//$serv_sam = $dataVHSNDvisit->serv_sam__wh+$dataVHSNDvisit->serv_sam__hl+$dataVHSNDvisit->serv_sam__treatment+$dataVHSNDvisit->serv_sam__nc+$dataVHSNDvisit->serv_sam__nrc_ref;  
								$serv_sam = $dataVHSNDvisit->sam_alr_visited;  
								
								if($serv_sam>0){
								$c6= "1";
								}else{
								$c6= "0";
								}
								 $column6[$i]=$c6;	
									
								$tablevalues.= $c6.'</td>
								

								<td class="thead tsubhead" align="center"  style="text-align:center; " sdval="2" sdnum="1033;">';
									
									$availMed=0;
									$availMedTotal=0;
									
									
									/*if($dataVHSNDvisit->zinc=='yes')
									{
										$availMedTotal=$availMedTotal+1;
									}
									*/
									if($dataVHSNDvisit->vita=='yes')
									{
										$availMedTotal=$availMedTotal+1;
									}
									
									if($dataVHSNDvisit->no_fa>0)
									{
										$availMedTotal=$availMedTotal+1;
									}
									
									if($dataVHSNDvisit->no_ifa_bot>0)
									{
										$availMedTotal=$availMedTotal+1;
									}
									if($dataVHSNDvisit->no_alb_tab>0)
									{
										$availMedTotal=$availMedTotal+1;
									}
									if($dataVHSNDvisit->no_multivit_syp>0)
									{
										$availMedTotal=$availMedTotal+1;
									}
									
									if($availMedTotal==5)
									{
										$c7=2;
									}
									else{
										$c7=0;
									}
									$column7[$i]=$c7;
									/*
									$serv_sam_medArr1=[];
									$sam_treatment_med = $dataVHSNDvisit->sam_treatment_med;  
									if($sam_treatment_med!='')
									{
								// $sam_treatment_med1 = str_replace(' ', ',', $sam_treatment_med); 
								$serv_sam_medArr1 = explode(' ', $sam_treatment_med);
								$serv_sam_medArr1 = array_diff($serv_sam_medArr1, ["dnk"]);
								
								//print_r($serv_sam_medArr1);
										if(count($serv_sam_medArr1)>=3)
										{
											$c7=2;
										}
										else {
											$c7=0;
										}
									}
									else {
										$c7=0;
									}
									echo $column7[$i]=$c7;
									*/
									//$txts=$dataVHSNDvisit->zinc.",".$dataVHSNDvisit->vita.",".$dataVHSNDvisit->no_amox_tab.",".$dataVHSNDvisit->no_ifa_bot.",".$dataVHSNDvisit->no_alb_tab.",".$dataVHSNDvisit->no_multivit_syp."-".$availMedTotal;
								   $tablevalues.=$c7.'</td>
								<td class="thead" align="center" valign=middle bgcolor="#92D050" sdval="0" sdnum="1033;">';
									if($dataVHSNDvisit->type_sam=='f_ckp' || $dataVHSNDvisit->type_sam=='both')
									{
									$no_amox_tab = $dataVHSNDvisit->med_sam__foli+$dataVHSNDvisit->med_sam__albe+$dataVHSNDvisit->med_sam__vita+$dataVHSNDvisit->med_sam__iron+$dataVHSNDvisit->med_sam__multi;
									if($no_amox_tab==5){
										$c8='1';
									}else{
										$c8='0';
									}
									$column8Den[]=$c8;
									}
									else
						            {
										$c8='0';
										
									}
									$column8[$i]=$c8;
									
								 $tablevalues.=$c8.'</td>
								<td class="thead tsubhead" align="center"  style="text-align:center" sdval="0" sdnum="1033;">';
								    if($dataVHSNDvisit->sam_aval=='yes')
									{
									$serv_sam__nc = $dataVHSNDvisit->serv_sam__nc;
									if($serv_sam__nc=='1'){
										$c9='2';
									}else{
										$c9='0';
									}
									$column9Den[]=$c9;
									}
									else {
										$c9='0';
									}
									$column9[$i]=$c9;
									
								$tablevalues.=$c9.'</td>
								<td class="thead3"  style="text-align:center" align="center" valign=bottom bgcolor="#A9D18E" sdval="2.5" sdnum="1033;0;0.0"><b>';
								$row[$i]=$c1+$c2+$c3+$c4+$c5+$c6+$c7+$c8+$c9; $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=0; 
								$tablevalues.=$row[$i].'</b>
								</td>
							</tr>';
							$i++; $j++;  }
						
						$tablevalues.= '<tr style="background-color:#1cabe2; font-weight:700">
								<td class="thead4" style="text-align:center" colspan=6>
									Total
								</td>
								<td class="thead4" style="text-align:center">
									'.array_sum($column1).' / '.$weightage1tot.'
								</td>
								<td class="thead4" style="text-align:center">
								'.array_sum($column2).' / '.$weightage2tot.'	
								</td>
								<td class="thead4" style="text-align:center">
								'.array_sum($column3).' / '.$weightage3tot.'	
								</td>
								<td class="thead4" style="text-align:center">
								'.array_sum($column4).' / '.$weightage4tot.'
								</td>
								<td class="thead4" style="text-align:center">
								'.array_sum($column5).' / '.$weightage5tot.'
								</td>
								<td class="thead4" style="text-align:center">
								'.array_sum($column6).' / '.$weightage6tot.'	
								</td>
								<td class="thead4" style="text-align:center">
								'.array_sum($column7).' / '.$weightage7tot.'
								</td>
								<td class="thead4" style="text-align:center">
								'.array_sum($column8).' / '.(count($column8Den)*1).'
								</td>
								<td class="thead4" style="text-align:center">
								'.array_sum($column9).' / '.(count($column9Den)*2).'	
								</td>
								<td class="thead4" style="text-align:center">
								'.array_sum($row).'	
								</td>
							</tr>';
							?>
///////////////////////////////////////////////////





<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Report</li>
						<li class="breadcrumb-item active"><i class="fa fa-calandar"></i>VHSND Quality Report</li>
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
							$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from amt67t3phss69dfpvrxqbf");
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
							$qryUserType = mysqli_query($conn,"SELECT DISTINCT type_mon FROM amt67t3phss69dfpvrxqbf ORDER BY type_mon ASC");
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
							<a href="VHSND_quality_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
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
					<table align="left" cellspacing="0" border="1" id="simpleTable1" data-cols-width="60,50,10">
						<colgroup width="149"></colgroup>
						<colgroup width="113"></colgroup>
						<colgroup width="171"></colgroup>
						<colgroup width="100"></colgroup>
						<colgroup width="128"></colgroup>
						<colgroup span="2" width="110"></colgroup>
						<colgroup width="122"></colgroup>
						<colgroup width="143"></colgroup>
						<colgroup width="106"></colgroup>
						<colgroup width="122"></colgroup>
						<colgroup width="142"></colgroup>
						<colgroup width="119"></colgroup>
						<colgroup width="113"></colgroup>
						<tr>
							<td colspan=16 height="34" align="left" valign=middle bgcolor="#806000"><b>
									<font face="Arial Rounded MT Bold" size=5 color="#FFFFFF">Categorization- VHSND SAM Quality Services (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?>
								</b></td>
						</tr>
						<tr>
							<td colspan=15 height="34" class="thead5"><b>
									<font face="Arial Rounded MT Bold" size=5 color="#FFFFFF"><br>
								</b></td>
							<td class="thead5"><b>
									<font face="Arial Rounded MT Bold" size=5 color="#FFFFFF"><br>
								</b></td>
						</tr>
						<tr>
							<td colspan=2 height="25" align="right" valign=bottom bgcolor="#F2F2F2"><b>
									<font face="Aptos Display" size=2 color="#000000">Total VHSND Sessions visited
								</b></td>
							<td align="center" valign=bottom bgcolor="#F2F2F2" sdval="46" sdnum="1033;"><b>
									<font size=2 color="#000000"><?=$totalRecord?>
								</b>
								
								
								</td>
							<td bgcolor="#F2F2F2">
								<font size=2 color="#000000"><br>
							</td>
							<td colspan=4 rowspan=4 class="thead5"><b>
									<font size=2 color="#1F4E79">Quality parameters %
								</b></td>
							<td class="thead6"><b>
									<font color="#806000">ANM aware of Medical Protocols
								</b></td>
							<td class="thead6">
								<br>
							</td>
							<td class="thead6" sdval="0.282608695652174" sdnum="1033;0;0%">
								<?=round(((array_sum($column1)*100)/$weightage1tot),1)?>%
							</td>
							<td class="thead6"><b>
									<font color="#806000">ANM dispensing drug to a SAM child
								</b></td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead6" sdval="0.0217391304347826" sdnum="1033;0;0%">
								<?=round((((array_sum($column8))*100)/(count($column8Den))),0)?>%
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
						</tr>
						<tr>
							<td colspan=2 height="25" align="right" valign=bottom bgcolor="#F2F2F2"><b>
									<font face="Aptos Display" size=2 color="#000000">Front runner (&gt;7 out of 10)
								</b></td>
							<td align="center" valign=bottom bgcolor="#F2F2F2" sdval="2" sdnum="1033;"><b>
									<font size=2 color="#000000">
									<?=$frontRunner=count(array_filter($row, function($value) {
											return $value > 7; 
									  }));?>
								</b></td>
							<td bgcolor="#F2F2F2" sdval="0.0434782608695652" sdnum="1033;0;0%"><b>
									<font size=2 color="#00B050"><?= round((($frontRunner * 100) / $totalRecord), 0)?>%
								</b></td>
							<td class="thead6"><b>
									<font color="#806000">Appropriate referral taking place
								</b></td>
							<td class="thead6">
								<br>
							</td>
							<td class="thead6" sdval="0.391304347826087" sdnum="1033;0;0%">
								<?=round((((array_sum($column2)+array_sum($column3)+array_sum($column4))*100)/($weightage2tot+$weightage3tot+$weightage4tot)),0)?>%
							</td>
							<td class="thead6"><b>
								<font color="#806000">Nutrition counselling being given (SAM)</b>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead6" sdval="0.0434782608695652" sdnum="1033;0;0%">
								<?=round((((array_sum($column9))*100)/(count($column9Den)*2)),0)?>%
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
						</tr>
						<tr>
							<td colspan=2 height="25" align="right" valign=bottom bgcolor="#F2F2F2"><b>
									<font face="Aptos Display" size=2 color="#000000">Performer (5-7 out of 10)
								</b></td>
							<td align="center" valign=bottom bgcolor="#F2F2F2" sdval="9" sdnum="1033;0;0"><b>
									<font size=2 color="#000000"><?=$performer=count(array_filter($row, function($value) {
											return $value >= 5 && $value <=7; 
									  }));?>
								</b></td>
							<td bgcolor="#F2F2F2" sdval="0.195652173913044" sdnum="1033;0;0%"><b>
									<font size=2 color="#00B0F0"><?= round((($performer * 100) / $totalRecord), 0)?>%
								</b></td>
							<td class="thead6"><b>
									<font color="#806000">AWW mobilized malnourished children
								</b></td>
							<td class="thead6">
								<br>
							</td>
							<td class="thead6" sdval="0.0434782608695652" sdnum="1033;0;0%">
								<?=round((((array_sum($column5)+array_sum($column6))*100)/($weightage5tot+$weightage6tot)),0)?>%
							</td>
							<td class="thead6" sdnum="1033;0;0%">
								<br>
							</td>
							<td class="thead6">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
						</tr>
						<tr>
							<td colspan=2 height="25" align="right" valign=bottom bgcolor="#F2F2F2"><b>
									<font face="Aptos Display" size=2 color="#000000">Aspirent(&lt;5 out of 10)
								</b></td>
							<td align="center" valign=bottom bgcolor="#F2F2F2" sdval="35" sdnum="1033;"><b>
									<font size=2 color="#000000"><?=$aspirent=count(array_filter($row, function($value) {
											return $value <5; 
									  }));?>
								</b></td>
							<td bgcolor="#F2F2F2" sdval="0.760869565217391" sdnum="1033;0;0%"><b>
									<font size=2 color="#385724"><?= round((($aspirent * 100) / $totalRecord), 0)?>%
								</b></td>
							<td class="thead6"><b>
									<font color="#806000">All five drug available
								</b></td>
							<td class="thead6">
								<br>
							</td>
							<td class="thead6" sdval="0.391304347826087" sdnum="1033;0;0%">
								<?=round((((array_sum($column7))*100)/($weightage7tot)),0)?>%
							</td>
							<td class="thead6" sdnum="1033;0;0%">
								<br>
							</td>
							<td class="thead6">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
						</tr>
						<tr>
							<td height="25" align="right" valign=bottom bgcolor="#F2F2F2"><b>
									<font face="Aptos Display" size=2 color="#000000"><br>
								</b></td>
							<td align="right" valign=bottom bgcolor="#F2F2F2"><b>
									<font face="Aptos Display" size=2 color="#000000"><br>
								</b></td>
							<td align="center" valign=bottom bgcolor="#F2F2F2"><b>
									<font size=2 color="#000000"><br>
								</b></td>
							<td bgcolor="#F2F2F2" sdnum="1033;0;0%"><b>
									<font size=2 color="#385724"><br>
								</b></td>
								</b></td>
							<td bgcolor="#F2F2F2" sdnum="1033;0;0%"><b>
									<font size=2 color="#385724"><br>
								</b></td>
								</b></td>
							<td bgcolor="#F2F2F2" sdnum="1033;0;0%"><b>
									<font size=2 color="#385724"><br>
								</b></td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead6"><b>
									<font size=2 color="#1F4E79"><br>
								</b></td>
							<td class="thead6"><b>
									<font size=2 color="#1F4E79"><br>
								</b></td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
							<td class="thead5">
								<br>
							</td>
						</tr>
						<tr>
							<td class="thead1" height="80"><b>
									<font size=3 color="#000000">District
								</b></td>
							<td class="thead1"><b>
									<font size=3 color="#000000">Block
								</b></td>
							<td class="thead1"><b>
									<font size=3 color="#000000">Village Name
								</b></td>
							<td class="thead1"><b>
									<font size=3 color="#000000">AWC Name
								</b></td>
							<td class="thead1"><b>
									<font size=3 color="#000000">AWC 11 digits code
								</b></td>
							<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font color="#1F4E79">Parameters
								</b></td>
							<td class="thead2" bgcolor="#FFFFCC"><b>
									<font color="#1F4E79">ANM aware of Medical Protocols
								</b></td>
							<td class="thead2" colspan=3 align="center" valign=middle bgcolor="#E2F0D9"><b>
									<font color="#1F4E79">Appropriate referral taking place
								</b></td>
							<td class="thead2" colspan=2 align="center" valign=middle bgcolor="#DAE3F3"><b>
									<font color="#1F4E79">AWW has mobilized malnourished children for health checkup
								</b></td>
							<td class="thead2" align="center" valign=middle bgcolor="#FFD5FF"><b>
									<font color="#1F4E79">All five drug available at session site
								</b></td>
							<td class="thead2" align="center" valign=middle bgcolor="#EDEDED"><b>
									<font color="#1F4E79">ANM dispensing drug to a SAM child
								</b></td>
							<td class="thead2" bgcolor="#FFFFCC"><b>
									<font color="#1F4E79">Nutrition counselling being given to a child with SAM
								</b></td>
							<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#FFFFFF"><b>
									Total Score out of 10
								</b></td>
						</tr>
						<tr>
							<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" height="77" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td style="border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="center" valign=middle bgcolor="#FFFFFF">
								Monitoring Indicators
							</td>


							<td class="thead7" bgcolor="#FFFFCC">
								ANM aware of all six essential medicines used for SAM child
							</td>

							<td class="thead7" bgcolor="#E2F0D9">
								ANM aware of treatment protocol for SAM with illness and/or poor appetite
							</td>
							<td class="thead7" bgcolor="#E2F0D9">
								ANM aware what to do If the child does not gain weight or loses weight in two consecutive follow-ups visits
							</td>
							<td class="thead7" bgcolor="#E2F0D9">
								ANM aware about the referral &amp; management protocol of a non responder SAM case
							</td>
							<td class="thead7" bgcolor="#DAE3F3">
								SAM child duelist available at session site
							</td>
							<td class="thead7" bgcolor="#DAE3F3">
								Services provided to SAM case during VHSND
							</td>
							<td class="thead7" bgcolor="#FFD5FF">
								All 5 drugs
							</td>
							<td class="thead7" bgcolor="#EDEDED">
								All 5 drugs
							</td>
							<td class="thead7" bgcolor="#FFFFCC">
								Nutrition related counselling
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<br>
								</b></td>
						</tr>
						<tr>
							<td class="thead3" height="30" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td class="thead3" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td class="thead3" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td class="thead3" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td class="thead3" align="left" valign=middle bgcolor="#FFFFFF"><b>
									<font size=3 color="#000000"><br>
								</b></td>
							<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000" align="right" valign=bottom bgcolor="#FFFFFF">
								Weightage
							</td>
							<td class="thead7" bgcolor="#FFFFCC" sdval="1" sdnum="1033;">
								1
							</td>
							<td class="thead7" bgcolor="#E2F0D9" sdval="1" sdnum="1033;">
								1
							</td>
							<td class="thead7" bgcolor="#E2F0D9" sdval="0.5" sdnum="1033;">
								0.5
							</td>
							<td class="thead7" bgcolor="#E2F0D9" sdval="0.5" sdnum="1033;">
								0.5
							</td>
							<td class="thead7" bgcolor="#DAE3F3" sdval="1" sdnum="1033;">
								1
							</td>
							<td class="thead7" bgcolor="#DAE3F3" sdval="1" sdnum="1033;">
								1
							</td>
							<td class="thead7" bgcolor="#FFD5FF" sdval="2" sdnum="1033;">
								2
							</td>
							<td class="thead7" bgcolor="#EDEDED" sdval="1" sdnum="1033;">
								1
							</td>
							<td class="thead7" bgcolor="#FFFFCC" sdval="2" sdnum="1033;">
								2
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#FFFFFF" sdval="10" sdnum="1033;">
								10
							</td>
						</tr>
						<?php 
						echo $tablevalues;
						?>
					</table>
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
				name: "VHSD_quality_report.xlsx", // Set your desired file name here
				sheet: {
					name: "VHSD Quality Report" // Set the sheet name here
				}
			});
		});
	</SCRIPT>