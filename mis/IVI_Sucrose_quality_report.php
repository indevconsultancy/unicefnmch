<?php include_once('includes/config.php'); ?>
<?php define("title", "AWC Quality Report | UNICEF"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php

function standardDeviation($values) {
    $mean = array_sum($values) / count($values);
    $sumOfSquares = 0;

    foreach ($values as $value) {
        $sumOfSquares += pow($value - $mean, 2);
    }

    $variance = $sumOfSquares / (count($values) - 1); // Sample variance (n-1)
    return sqrt($variance);
}




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

$weightage1=0.5;
$weightage2=0.5;
$weightage3=0.5;
$weightage4=0.5;
$weightage5=1.0;
$weightage6=0.5;
$weightage7=0.5;
$weightage8=0.5;
$weightage9=0.5;
$weightage10=1.0;
$weightage11=1.0;
$weightage12=1.0;
$weightage13=0.5;
$weightage14=0.5;
$weightage15=0.5;
$weightage16=0.5;
$weightage17=1.0;
$weightage18=1.0;
$weightage19=2.0;
$weightage20=1.0;
$weightage21=0.5;
$weightage22=1.0;
$weightage23=2.0;
$weightage24=2.0;
$weightage25=0.5;
$weightage26=1.0;
$weightage27=2.0;

// Get the number of days in the selected month
$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);


$qry = "";
	//////////////////////////////////////////////////////////
	if (isset($_REQUEST['search'])) {
		if($qryfield1=='')
		{
			if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$startDate = new DateTime($_REQUEST['fromDate']);
				 $endDate = new DateTime($_REQUEST['toDate']);
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(dov) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		}
		else {
			
			$qry .=" and date(dov) like'".$qryfield1."%'";
		}
		
		if (isset($_REQUEST['district_code'])) {
			$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
			if (!empty($districtType)) {
				
				 $districtTypeList = "'" . implode("','", $districtType) . "'"; // Convert to integer to prevent SQL injection
				$qry .= " AND _District IN ($districtTypeList)";
			}	
		}
		if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {
		
        $userTypes = array_filter($_REQUEST['uType']); // Remove empty values
		//print_r($userTypes);
        if (!empty($userTypes)) {
             $userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
			$qry .= " AND assessor_design IN ($userTypeList)";
        }
		
		
    }
	}
	


function getcountAWCHVQR($conn, $tablename, $field, $qryfield, $value, $qryfield1 = '', $value1 = '')
{
	// Get the number of days in the selected month
	$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	$qry = "";
	//////////////////////////////////////////////////////////
	if (isset($_REQUEST['search'])) {
		if($qryfield1=='')
		{
			if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$startDate = new DateTime($_REQUEST['fromDate']);
				 $endDate = new DateTime($_REQUEST['toDate']);
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(dov) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
			}
		}
		else {
			
			$qry .=" and date(dov) like'".$qryfield1."%'";
		}
		
		if (isset($_REQUEST['district_code'])) {
			$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
			if (!empty($districtType)) {
				
				 $districtTypeList = "'" . implode("','", $districtType) . "'"; // Convert to integer to prevent SQL injection
				$qry .= " AND _District IN ($districtTypeList)";
			}	
		}
		if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {
		
        $userTypes = array_filter($_REQUEST['uType']); // Remove empty values
		//print_r($userTypes);
        if (!empty($userTypes)) {
             $userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
			$qry .= " AND assessor_design IN ($userTypeList)";
        }
		
		
    }
	}
	
	
	
	
	/////////////////////////////////////////////////////////
	
	/*if (isset($_REQUEST['search'])) {
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
     */
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
//echo "select * from a27ycjonhei3rt5h4badxn where 1=1 $qry group by hf_name order by _District,dov";
$sqlVHSNDvisit = mysqli_query($conn, "select * from a27ycjonhei3rt5h4badxn where 1=1 $qry group by hf_name order by _District,dov");
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
									$weightage10tot=$weightage10*$totalRecord;
									$weightage11tot=$weightage11*$totalRecord;
									$weightage12tot=$weightage12*$totalRecord;
									$weightage13tot=$weightage13*$totalRecord;
									$weightage14tot=$weightage14*$totalRecord;
									$weightage15tot=$weightage15*$totalRecord;
									$weightage16tot=$weightage16*$totalRecord;
									$weightage17tot=$weightage17*$totalRecord;
									$weightage18tot=$weightage18*$totalRecord;
									$weightage19tot=$weightage19*$totalRecord;
									$weightage20tot=$weightage20*$totalRecord;
									$weightage21tot=$weightage21*$totalRecord;
									$weightage22tot=$weightage22*$totalRecord;
									$weightage23tot=$weightage23*$totalRecord;
									$weightage24tot=$weightage24*$totalRecord;
									$weightage25tot=$weightage25*$totalRecord;
									$weightage26tot=$weightage26*$totalRecord;
									$weightage27tot=$weightage27*$totalRecord;



?>

<?php 
                        $tablevalues=''; 
						$i=0; //Row
						$j=0; //Column
						//echo "select a27ycjonhei3rt5h4badxn.*,ivis_facilities.districts,ivis_facilities.ccenter_name,ivis_facilities.blocks from a27ycjonhei3rt5h4badxn,ivis_facilities  where a27ycjonhei3rt5h4badxn.hf_name=ivis_facilities.ccenter_name $qry group by hf_name order by _District,dov";
						$sqlAWCvisit = mysqli_query($conn, "select a27ycjonhei3rt5h4badxn.*,ivis_facilities.districts,ivis_facilities.ccenter_name,ivis_facilities.blocks from a27ycjonhei3rt5h4badxn,ivis_facilities  where a27ycjonhei3rt5h4badxn.hf_name=ivis_facilities.center_code $qry group by hf_name order by _District,dov");
						while ($dataAWCvisit = mysqli_fetch_object($sqlAWCvisit)) { 
							$bgc='';
							$chkService='';
							if($dataAWCvisit->is_admin=='yes')
							{
								$chkService='yes';
								 $bgc='';
							}
							$tablevalues.=' <tr>
							    <td class="td_head4" height="20" align="left" valign=bottom  data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.($i+1).'
								</td>
								<td class="td_head4" height="20" align="left" valign=bottom  data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->districts).'
								</td> 
								<td class="td_head3" align="left" valign=bottom  data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->blocks).'
								</td>
								<td class="td_head3" align="left" valign=bottom data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->ccenter_name).'
								</td>
								<td class="td_head3" align="left" valign=bottom data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.date('d-m-Y',strtotime($dataAWCvisit->dov)).'
								</td>';
								if($chkService!='')
									{
									 $bgc='F9BB87';
									 $c1 = 0;
									
									$np = $dataAWCvisit->amp_aval;
									if ($np=='yes') {
										$c1 = 0.5;
										$bgc='CCFF66';
									}
									} else { $c1 = 'NA'; $bgc='FFFFFF'; }
								$tablevalues.= '
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0.5" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
                                     
                                    $column1[$i]=$c1;
									$tablevalues.=$c1;
									/////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									if ($dataAWCvisit->erp_disp=='yes') {
										$c2 = 0.5;
										$bgc='CCFF66';
									} else {
										$c2 = 0;
									}
									} else { $c2 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0.5" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
								    
									$column2[$i]=$c2;
									$tablevalues.=$c2;
									////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
								
									if ($dataAWCvisit->am_coun_mat=='yes') {
										$c3 = 0.5;
										$bgc='CCFF66';
									} else {
										$c3 = 0;
									}
									} else { $c3 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
									
									$column3[$i]=$c3;
									$tablevalues.=$c3;
									//////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									if ($dataAWCvisit->am_coun_mat_002=='yes') {
										$c4 = 0.5;
										$bgc='CCFF66';
									} else {
										$c4 = 0;
									}
									} else { $c4 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0.5" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
									
									$column4[$i]=$c4;
									$tablevalues.=$c4;
									///////////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c5 = 0;
									if ($dataAWCvisit->bsu_func!='' && $dataAWCvisit->bsu_func=='yes') {
										$c5 = 1;
										$bgc='CCFF66';
									} else {
										$c5 = 0;
									}
									} else { $c5 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
								   
									$column5[$i]=$c5;
									$tablevalues.=$c5;
									/////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c6 = 0;
									if ($dataAWCvisit->register_type!='' && $dataAWCvisit->register_type=='reg_printed') {
										$c6 = 0.5;
										$bgc='CCFF66';
									} else {
										$c6 = 0;
									}
									} else { $c6 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
								
									$column6[$i]=$c6;
									$tablevalues.=$c6;
									///////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c7 = 0;
									if ($dataAWCvisit->ben_rec_aval_reg!='' && $dataAWCvisit->ben_rec_aval_reg== 'yes') {
										$c7 = 0.5;
										$bgc='CCFF66';
									} else {
										$c7 = 0;
									}
									} else { $c7 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column7[$i]=$c7;
									$tablevalues.=$c7;
									//////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c8 = 0;
									if ($dataAWCvisit->con_ben_reg!='' && $dataAWCvisit->con_ben_reg== 'yes') {
										$c8 = 0.5;
										$bgc='CCFF66';
									} else {
										$c8 = 0;
									}
									} else { $c8 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '

								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
								
									$column8[$i]=$c8;
									$tablevalues.=$c8;
									////////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
								    $c9=0;
									if ($dataAWCvisit->con_asha_reg!='' && $dataAWCvisit->con_asha_reg=='yes') {
										$c9 = 0.5;
										$bgc='CCFF66';
									} else {
										$c9 = 0;
									}
									} else { $c9 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '

								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0.5" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
								
									$column9[$i]=$c9;
									$tablevalues.=$c9;
									//////////////////////////////////
									if($dataAWCvisit->is_admin!='')
									{
									 $bgc='F9BB87';
									 
									$c10=0;
									if ($dataAWCvisit->mo_invol_an_mgmt==$dataAWCvisit->mo_trg_am_mgmt) {
										$c10 = 1;
										$bgc='CCFF66';
									} else {
										$c10 = 0;
									}
									if($dataAWCvisit->is_admin=='')
									{
										$bgc='FFFFFF';
									}
									} else { $c10 = 'NA'; $bgc='FFFFFF';  }
									if($chkService==''){ $bgc='FFFFFF'; }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
                                   
									$column10[$i]=$c10;
									$tablevalues.=$c10;
									////////////////////////////////////
									if($dataAWCvisit->is_admin!='')
									{
									 $bgc='F9BB87';
									$c11=0;
									if ($dataAWCvisit->sn_invol_an_mgmt==$dataAWCvisit->sn_trg_am_mgmt) {
										$c11 = 1;
										$bgc='CCFF66';
									} else {
										$c11 = 0;
									}
									if($dataAWCvisit->is_admin=='')
									{
										$bgc='FFFFFF';
									}
									} else { $c11 = 'NA'; $bgc='FFFFFF';  }
									if($chkService==''){ $bgc='FFFFFF'; }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="1" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
									<font color="#000000">';
									// Example usage:
									
									$column11[$i]=$c11;
									$tablevalues.=$c11;
									////////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c12=0;
									if ($dataAWCvisit->corr_formula!='' && $dataAWCvisit->corr_formula=='yes') {
										$c12 = 1;
										$bgc='CCFF66';
									} else {
										$c12 = 0;
									}
									} else { $c12 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '</font>
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
									<font color="#000000">';
									// Example usage:corr_formula
									
									$column12[$i]=$c12;
									$tablevalues.=$c12;
									/////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c13=0;
									if ($dataAWCvisit->max1-iviron=='yes') {
										$c13 = 0.5;
										$bgc='CCFF66';
									} else {
										$c13 = 0;
									}
									} else { $c13 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '</font>
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
									<font color="#000000">';
									// Example usage:
									
									$column13[$i]=$c13;
									$tablevalues.=$c13;
									/////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c14=0;
									if ($dataAWCvisit->is_dil!='' && $dataAWCvisit->is_dil=='yes') {
										$c14 = 0.5;
										$bgc='CCFF66';
									} else {
										$c14 = 0;
									}
									} else { $c14 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '</font>
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column14[$i]=$c14;
									$tablevalues.=$c14;
									//////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c15 = 0;
									if ($dataAWCvisit->ivis_ins_time!='' && $dataAWCvisit->ivis_ins_time=='30_60m') {
										$c15 = 0.5;
										$bgc='CCFF66';
									} else {
										$c15 = 0;
									}
									} else { $c15 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column15[$i]=$c15;
									$tablevalues.=$c15;
									//////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c16 = 0;
									if ($dataAWCvisit->proto_emergency!='' && $dataAWCvisit->proto_emergency__na==0) {
										$c16 = 0.5;
										$bgc='CCFF66';
									} else {
										$c16 = 0;
									}
									} else { $c16 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
									
									$column16[$i]=$c16;
									$tablevalues.=$c16;
									/////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c17 = 0;
									if ($dataAWCvisit->is_aval!='' && $dataAWCvisit->is_aval=='yes') {
										$c17 = 1.0;
										$bgc='CCFF66';
									} else {
										$c17 = 0;
									}
									} else { $c17 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column17[$i]=$c17;
									$tablevalues.=$c17;
									/////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c18 = 0;
									if ($dataAWCvisit->ns_100_aval!='' && $dataAWCvisit->ns_100_aval=='yes') {
										$c18 = 1.0;
										$bgc='CCFF66';
									} else {
										$c18 = 0;
									}
									} else { $c18 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column18[$i]=$c18;
									$tablevalues.=$c18;
									/////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c19 = 0;
									if ($dataAWCvisit->est_heam!='' && $dataAWCvisit->est_heam=='aut_ana') {
										$c19 = 2.0;
										$bgc='CCFF66';
									} else {
										$c19 = 0;
									}
									} else { $c19 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column19[$i]=$c19;
									$tablevalues.=$c19;
									////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c20 = 0;
									if ($dataAWCvisit->est_heam!='' && $dataAWCvisit->est_heam=='dig_heam') {
										$c20 = 1.0;
										$bgc='CCFF66';
									} else {
										$c20 = 0;
									}
									} else { $c20 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column20[$i]=$c20;
									$tablevalues.=$c20;
									//////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c21 = 0;
									$c21ctot=round((($dataAWCvisit->ivis_dose4*100)/$dataAWCvisit->ivis_dose1),0);
									
									if ($c21ctot>45) {
										$c21 = 0.5;
										$bgc='CCFF66';
									} else {
										$c21 = 0;
									}
									} else { $c21 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column21[$i]=$c21;
									$tablevalues.=$c21;
									///////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c22 = 0;
									if ($c21ctot>60) {
										$c22 = 1.0;
										$bgc='CCFF66';
									} else {
										$c22 = 0;
									}
									} else { $c22 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column22[$i]=$c22;
									$tablevalues.=$c22;
									///////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c23 = 0;
									if ($c21ctot>75) {
										$c23 = 1.0;
										$bgc='CCFF66';
									} else {
										$c23 = 0;
									}
									} else { $c23 = 'NA'; $bgc='FFFFFF';  }
									
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column23[$i]=$c23;
									$tablevalues.=$c23;
									/////////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c24 = 0;
									if ($dataAWCvisit->fup_mech!='' && $dataAWCvisit->fup_mech__no_fup==0) {
										$c24 = 2.0;
										$bgc='CCFF66';
									} else {
										$c24 = 0;
									}
									} else { $c24 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column24[$i]=$c24;
									$tablevalues.=$c24;
									//////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c25 = 0;
									$c25ctotal=round((($dataAWCvisit->per_asha_rec_inc_001*100)/$dataAWCvisit->per_asha_rec_inc),0);
									if ($c25ctotal>25) {
										$c25 = 0.5;
										$bgc='CCFF66';
									} else {
										$c25 = 0;
									}
									} else { $c25 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column25[$i]=$c25;
									$tablevalues.=$c25;
									//////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c26 = 0;
									if ($c25ctotal>50) {
										$c26 = 1.0;
										$bgc='CCFF66';
									} else {
										$c26 = 0;
									}
									} else { $c26 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column26[$i]=$c26;
									$tablevalues.=$c26;
									////////////////////////////////
									if($chkService!='')
									{
									 $bgc='F9BB87';
									$c27 = 0;
									if ($c25ctotal>75) {
										$c27 = 2.0;
										$bgc='CCFF66';
									} else {
										$c27 = 0;
									}
									} else { $c27 = 'NA'; $bgc='FFFFFF';  }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$column27[$i]=$c27;
									$tablevalues.=$c27;
									$bgcolorc='';
									$row[$i]=$c1+$c2+$c3+$c4+$c5+$c6+$c7+$c8+$c9+$c10+$c11+$c12+$c13+$c14+$c15+$c16+$c17+$c18+$c19+$c20+$c21+$c22+$c23+$c24+$c25+$c26+$c27; 
								   
								   $c1=$c2=$c3=$c4=$c5=$c6=$c7=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=$c18=$c19=$c20=$c21=$c22=$c23=$c24=$c25=$c26=$c27=0;
									$tcolumnValue='';
									if($chkService=='') { $tcolumnValue="NA"; $bgcolorc='#FFFFFF'; $row[$i]=0;  } else { $tcolumnValue= $row[$i]; $bgcolorc='#A9D18E'; }
									$tablevalues.= '
									
								</td>
								<td class="td_head1" align="center" valign=bottom bgcolor="'.$bgcolorc.'" sdval="3.5" sdnum="1033;0;0.0" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><b>';
								   
									$tablevalues.='<font color="#000000">'.$tcolumnValue.'</font>
									</b></td>
							</tr>'; ?>
						<?php $i++; $j++; }
						/*$tablevalues.='<tr>
							<td colspan=5 style="border-top: 1px solid #000000;  border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000" >Total</font>
							</td>
							
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" height="21" align="left" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column1).' / '.$weightage1tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column2).' / '.$weightage2tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column3).' / '.$weightage3tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column4).' / '.$weightage4tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column5).' / '.$weightage5tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column6).' / '.$weightage6tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
									<font color="#000000">'.array_sum($column7).' / '.$weightage7tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
									<font color="#000000">'.array_sum($column8).' / '.$weightage8tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column9).' / '.$weightage9tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column10).' / '.$weightage10tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column11).' / '.$weightage11tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column12).' / '.$weightage12tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column13).' / '.$weightage13tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column14).' / '.$weightage14tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column15).' / '.$weightage15tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column16).' / '.$weightage16tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column17).' / '.$weightage17tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column18).' / '.$weightage18tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column19).' / '.$weightage19tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column20).' / '.$weightage20tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column21).' / '.$weightage21tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column22).' / '.$weightage22tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column23).' / '.$weightage23tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column24).' / '.$weightage24tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column25).' / '.$weightage25tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column26).' / '.$weightage26tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column27).' / '.$weightage27tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" sdnum="1033;0;0.0" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><b>
									<font color="#000000">'.array_sum($row).'</font>
								</b></td>
						</tr>';
                             */
						?>





<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<title>AWC Quality Parameter Analysis</title>


<style type="text/css">
    tbody, td, tfoot, th, thead, tr {
    border-color: inherit;
    border-style: solid;
    border-width: 1px;
	padding:5px;
	}
	body {
		font-family: "Calibri";

	}

	.td_head {

		border: 1px solid #000000;
		text-align: left;
		vertical-align: bottom;
		background-color: #F2F2F2;
	}

	.td_head1 {
		border: 1px solid #000000;
		border-bottom: 1px solid #000000;
		border-left: 1px solid #000000;
		border-right: 2px solid #000000;
	}

	.td_head2 {
		border: 1px solid #000000;
	}

	.td_head3 {
		border: 1px solid #000000;
	}

	.td_head4 {
		border: 1px solid #000000;
	}
</style>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Report</li>
						<li class="breadcrumb-item active"><i class="fa fa-calandar"></i>IVIS Facility wise Score-Card</li>
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
							$qryDistrictType = mysqli_query($conn, "SELECT distinct(_District) as district from a27ycjonhei3rt5h4badxn");
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
							$qryUserType = mysqli_query($conn,"SELECT DISTINCT assessor_design FROM a27ycjonhei3rt5h4badxn ORDER BY assessor_design ASC");
							while($dataUserType = mysqli_fetch_object($qryUserType)) {
								if(isset($_REQUEST['uType']))
								{
								$selected = (!empty($_REQUEST['uType']) && in_array($dataUserType->assessor_design, $_REQUEST['uType'])) ? 'selected' : '';
								}
								else {
									$selected='';
								}
								
								?>
								<option value="<?=$dataUserType->assessor_design?>" <?=$selected?> > <?=$dataUserType->assessor_design?> </option>
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
							<a href="AWC_quality_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
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
						  <table cellspacing="1"  border="1" id="simpleTable1" style="border-collapse: collapse;"  data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000">
  <col width="45" />
  <col width="85" />
  <col width="130" />
  <col width="103" />
  <col width="85" />
  <col width="89" span="4" />
  <col width="77" />
  <col width="88" />
  <col width="101" />
  <col width="82" />
  <col width="80" />
  <col width="71" />
  <col width="68" />
  <col width="100" />
  <col width="90" />
  <col width="84" />
  <col width="79" />
  <col width="86" />
  <col width="72" />
  <col width="80" />
  <col width="72" />
  <col width="76" />
  <col width="75" span="3" />
  <col width="105" />
  <col width="76" span="3" />
  <col width="64" />
  <tr>
   <td height="34" colspan="33" align="left" valign="middle" bgcolor="#806000" data-fill-color="806000" data-f-color="FFFFFF" ><h4 style="color:#FFFFFF; padding:5px; "><strong >IVIS Facility wise Score-Card  (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F Y',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?></strong></h4></td>
  </tr>
  <tr>
    <td rowspan="2" width="100"><strong>S.No</strong></td>
    <td rowspan="2" width="492"><strong>District</strong></td>
    <td rowspan="2" width="130"><strong>Block</strong></td>
    <td rowspan="2" width="103"><strong>Health Facility</strong></td>
    <td rowspan="2" width="85"><strong>Date of Monitoring</strong></td>
    <td colspan="4" width="356"><strong>IEC materials, treatment protocols (package of 4)-2</strong></td>
    <td rowspan="2" width="77"><strong>Functional BSU - 1</strong></td>
    <td colspan="4" width="351"><strong>Printed    register available and updated - 2</strong></td>
    <td colspan="2" width="139"><strong>Staff    Trained on Anemia Mgmt. -2</strong></td>
    <td colspan="5"><strong>Treatment as per the protocol – defined in the state guidelines - 3</strong></td>
    <td colspan="2" width="152"><strong>Iron    Sucrose &amp; NS availability - 2</strong></td>
    <td colspan="2" width="148"><strong>Hemoglobin testing at the facility- 2</strong></td>
    <td colspan="3" width="225"><strong>Treatment completion rate in the facility in    the last quarter - 2</strong></td>
    <td rowspan="2" width="105"><strong>Facility has follow-up mechanism<br />
      (Tele calling / Throgh ASHA/ Follow-up slips) - 2</strong></td>
    <td colspan="3" width="228"><strong>Utilization of ASHA incentive -2</strong></td>
    <td rowspan="2" width="64"><strong>Total score</strong></td>
  </tr>
  <tr>
    <td width="356"><strong>Availability of anemia mgmt. protocol</strong></td>
    <td width="89"><strong>Emergency    response protocol displayed</strong></td>
    <td width="89"><strong>Availability    of counselling materials on maternal anemia</strong></td>
    <td width="89"><strong>Availability    of counselling materials on Food sources - anemia prevention</strong></td>
    <td width="351"><strong>Printed    register available </strong></td>
    <td width="101"><strong>Beneficiary    name wise dose related data available upto the last month in register</strong></td>
    <td width="82"><strong>Contact    number of beneficiary available in register</strong></td>
    <td width="80"><strong>Contact    number of ASHA available in register</strong></td>
    <td width="139"><strong>Medical    Officer trained for Anaemia Mgmt.</strong></td>
    <td width="68"><strong>Staff    Nurse  trained for Anaemia Mgmt.</strong></td>
    <td width="100"><strong>Respondent    know the correct formula for IVIS dose calculation</strong></td>
    <td width="90"><strong>Responder    know maximum dose of IVIS in pregnancy</strong></td>
    <td width="84"><strong> Respondent know correct dilution of Iron    sucrose</strong></td>
    <td width="79"><strong>Infusion    rate of Iron sucrose</strong></td>
    <td width="86"><strong>Responder    know what are the emergency response protocol </strong></td>
    <td width="152"><strong>Iron    Sucrose (2.5 /5 ml) availability</strong></td>
    <td width="80"><strong>Normal    Saline<br />
      (100 ml)    availability</strong></td>
    <td width="148"><strong>Hb    testing done using Auto analyzer </strong></td>
    <td width="76"><strong>Hb    testing done using Digital Hb meter</strong></td>
    <td width="225"><strong>above    45% </strong></td>
    <td width="75"><strong>Above    60%</strong></td>
    <td width="75"><strong>Above    75%</strong></td>
    <td width="228"><strong>above    25% </strong></td>
    <td width="76"><strong>Above    50%</strong></td>
    <td width="76"><strong>Above    75%</strong></td>
  </tr>
  
						  <tr >
							
							
							<td align="left" colspan="5" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">Indicator wise weightage	</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF" >0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">2.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">2.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">2.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">2.0</strong></td>
							<td align="left" valign="middle" bgcolor="#000000" data-fill-color="000000" data-f-color="ffffff" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong style="color:#FFFFFF">20.0</strong></td>
						  </tr>
						<?=$tablevalues?>

						<tr>
   <td height="34" colspan="33" align="left" valign="middle" bgcolor="#806000" data-fill-color="806000" data-f-color="FFFFFF" ><h6 style="color:#FFFFFF; padding:5px; "><strong >Note: Not Applicable (NA): Anemia management in this facility is not conducted using IV iron sucrose during this period, or calculation is not possible. </strong></h6></td>
  </tr>
					</table>
				</div>
			</section>
	</section>
	<?php include_once('includes/footer.php'); ?>
	<SCRIPT>
	let button = document.querySelector("#button-excel");
	button.addEventListener("click", (e) => {
		let table = document.querySelector("#simpleTable1");
		TableToExcel.convert(table, {
			name: "IVIS-Facility-wise-Score-Card.xlsx", // Set your desired file name here
			sheet: {
				name: "IVIS-Facility-wise-Score-Card" // Set the sheet name here
			}
		});
	});
</SCRIPT>
	<!-- ************************************************************************** -->