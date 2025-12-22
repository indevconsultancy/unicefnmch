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

$weightage1=1.0;
$weightage2=0.5;
$weightage3=0.5;
$weightage4=0.5;
$weightage5=0.5;
$weightage6=0.5;
$weightage7=0.0;
$weightage8=0.5;
$weightage9=0.5;
$weightage10=1.0;
$weightage11=1.0;
$weightage12=0.5;
$weightage13=0.5;
$weightage14=0.5;
$weightage15=0.5;
$weightage16=0.5;
$weightage17=1.0;

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
	//////////////////////////////////////////////////////////
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

$sqlVHSNDvisit = mysqli_query($conn, "select * from aujyspbgxt8m3j9tjmv62c where 1=1 $qry");
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



?>

<?php 
                        $tablevalues=''; 
						$i=0; //Row
						$j=0; //Column
						//echo "select * from aujyspbgxt8m3j9tjmv62c where 1=1 $qry order by awc_name";
						$sqlAWCvisit = mysqli_query($conn, "select * from aujyspbgxt8m3j9tjmv62c where 1=1 $qry order by awc_name");
						while ($dataAWCvisit = mysqli_fetch_object($sqlAWCvisit)) { 
							
							$tablevalues.=' <tr>
								<td class="td_head4" height="20" align="left" valign=bottom  data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->district).'
								</td> 
								<td class="td_head3" align="left" valign=bottom  data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->block).'
								</td>
								<td class="td_head3" align="left" valign=bottom data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->village).'
								</td>
								<td class="td_head3" align="left" valign=bottom data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->awc_name).'('.$dataAWCvisit->code_awc.')
								</td>
								<td class="td_head3" colspan=2 align="left" valign=bottom data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->aww_name).'
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="1" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 

									 $c1 = 0;
									 if($dataAWCvisit->reg_05>0)
									 {
										$np = (($dataAWCvisit->mes_05) / $dataAWCvisit->reg_05);
										if ($np >=1) {
											$c1 = 1;
										}
										else {
											$c1 = round($np,1);
										}
									 }
									 
                                    $column1[$i]=$c1;
									$tablevalues.=$c1;
									$tablevalues.= '
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0.5" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
								
									if ($dataAWCvisit->sam_mgmt_steps__st_4 >'0') {
										$c2 = 0.5;
									} else {
										$c2 = 0;
									}
									$column2[$i]=$c2;
									$tablevalues.=$c2;
									$tablevalues.= '
									<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
									
								
									if ($dataAWCvisit->improve_nutri__week >'0') {  
										$c3 = 0.5;
									} else {
										$c3 = 0;
									}
									$column3[$i]=$c3;
									$tablevalues.=$c3;
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0.5" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
									
									if ($dataAWCvisit->improve_nutri__coun >'0') {
										$c4 = 0.5;
									} else {
										$c4 = 0;
									}
									$column4[$i]=$c4;
									$tablevalues.=$c4;
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									if ($dataAWCvisit->improve_nutri__awc_sess >'0') {
										$c5 = 0.5;
									} else {
										$c5 = 0;
									}
									$column5[$i]=$c5;
									$tablevalues.=$c5;
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									if ($dataAWCvisit->improve_nutri__prov_thr >'0') {
										$c6 = 0.5;
									} else {
										$c6 = 0;
									}
									$column6[$i]=$c6;
									$tablevalues.=$c6;
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$c17 = 0;
									$nmb=explode(' ',$dataAWCvisit->nuri_msg);
									
									if (count($nmb)>=5) {
										$c17 = 1;
									} else {
										$c17 = 0;
									}
									$column17[$i]=$c17;
									$tablevalues.=$c17;
									$tablevalues.= '

								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0.5" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
								
									if ($dataAWCvisit->sam_mgmt_steps__st_5>0) {
										$c8 = 0.5;
									} else {
										$c8 = 0;
									}
									$column8[$i]=$c8;
									$tablevalues.=$c8;
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';

									$c9 = 0;
									$c91 = '';
									$c92 = 0;
									
									/*if ($dataAWCvisit->sam2_aww_visit == 'yes') {
										$c91 = 0.5;
									}*/
									
                                    if ($dataAWCvisit->sam1_aww_visit!='' && $dataAWCvisit->sam1_aww_visit == 'yes') {
										$c92 = 1;
									}
									else if($dataAWCvisit->sam1_aww_visit == 'no')
									{
										$c92 = 0;
									}
									else {
										$c92 = '';
									}
									
									$c9 = $c92;
									
									$column9[$i]=$c9;
									$tablevalues.=$c9;
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="1" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
									<font color="#000000">';
									// Example usage:
									
									$c14=0;
									$c141=0;
									$c142=0;
									if($dataAWCvisit->child1_rcat!='' && $dataAWCvisit->child2_rcat!='')
									{
										if($dataAWCvisit->child1_rcat==$dataAWCvisit->child1_ccat && $dataAWCvisit->child2_rcat==$dataAWCvisit->child2_ccat)
										{
											$c141=1;
										}
										else
										{
											$c141=0;
										}	
						            }
									else {
										$c141='';
									}
									// if($dataAWCvisit->child2_rcat==$dataAWCvisit->child2_ccat)
									// {
										// $c142=0.5;
									// }
									// $c14=$c141+$c142;
									$c14=$c141;
									$column14[$i]=$c14;
									$tablevalues.=$c14;
									$tablevalues.= '</font>
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
									<font color="#000000">';
									// Example usage:
									$c15='';
									$c151=0;
									$c152=0;
									/*
									$values1=[];
									$values2=[];
									$values1 = [$dataAWCvisit->child1_rw, $dataAWCvisit->child1_cw]; // Replace E7 and F7 with actual numbers or variables
									$values2 = [$dataAWCvisit->child2_rw, $dataAWCvisit->child2_cw]; // Replace E7 and F7 with actual numbers or variables
									
									//print_r($values);
									$stds1=standardDeviation($values1);
									$stds2=standardDeviation($values2);
									
									if($stds1<2)
									{
										$c151=0.25;
									}
									if($stds2<2)
									{
										$c152=0.25;
									}
									$c15=$c151+$c152;
									*/
									

									if($dataAWCvisit->c1_wt_diff!='NaN' && $dataAWCvisit->c2_wt_diff!='NaN')
									{
									$stds111 = (($dataAWCvisit->c1_wt_diff+$dataAWCvisit->c2_wt_diff)/2); // Replace E7 and F7 with actual numbers or variables
										if($stds111 >= -0.1 && $stds111 <= 0.1) 
										{
											$c15=0.5;
										}
										else 
										{
											$c15=0;
										}
									}
									else 
									{
										$c15='';
									}
									
									
									$column15[$i]=$c15;
									$tablevalues.=$c15;
									$tablevalues.= '</font>
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
									<font color="#000000">';
									// Example usage:
									$c16=0;
									/*
									$c161=0;
									$c162=0;
									$values1=[];
									$values2=[];
									$values1 = [$dataAWCvisit->child1_rhl, $dataAWCvisit->child1_chl]; // Replace E7 and F7 with actual numbers or variables
									$values2 = [$dataAWCvisit->child2_rhl, $dataAWCvisit->child2_chl]; // Replace E7 and F7 with actual numbers or variables
									//print_r($values);
									$stds1=standardDeviation($values1);
									$stds2=standardDeviation($values2);
									if($stds1<2)
									{
										$c161=0.25;
									}
									if($stds2<2)
									{
										$c162=0.25;
									}
									$c16=$c161+$c162;
									*/
									
									if($dataAWCvisit->c1_hl_diff!='NaN' && $dataAWCvisit->c2_hl_diff!='NaN')
									{
									$stds1 = (($dataAWCvisit->c1_hl_diff+$dataAWCvisit->c2_hl_diff)/2);
									
									if($stds1 >=-1 && $stds1 <=1)
										{
											$c16=0.5;
										}
										else
										{
											$c16=0;
										}
									}
									else {
										$c16='';
									}
									$column16[$i]=$c16;
									$tablevalues.=$c16;
									$tablevalues.= '</font>
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$c11 = 0;
									if ($dataAWCvisit->mt_poor_app== 'aware') {
										$c11 = 0.5;
									} else {
										$c11 = 0;
									}
									$column11[$i]=$c11;
									$tablevalues.=$c11;
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$c10 = 0;
									if ($dataAWCvisit->sam_mt == 'aware') {
										$c10 = 0.5;
									} else {
										$c10 = 0;
									}
									$column10[$i]=$c10;
									$tablevalues.=$c10;
									$tablevalues.= '
									
								</td>
								
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
									
									$c12 = 0;
									if ($dataAWCvisit->child_not_resp== 'nrc' || $dataAWCvisit->child_not_resp== 'chc_phc_rbsk') {
										$c12 = 0.5;
									} else {
										$c12 = 0;
									}
									$column12[$i]=$c12;
									$tablevalues.=$c12;
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="#92D050" sdval="0" sdnum="1033;" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									$c13 = 0;
									if ($dataAWCvisit->what_done_aft_3mon== 'refer_nrc') {
										$c13 = 0.5;
									} else {
										$c13 = 0;
									}
									$column13[$i]=$c13;
									$tablevalues.=$c13;
									$tablevalues.= '
									
								</td>
								<td class="td_head1" align="center" valign=bottom bgcolor="#A9D18E" sdval="3.5" sdnum="1033;0;0.0" data-fill-color="92D050" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><b>';
								   $row[$i]=$c1+$c2+$c3+$c4+$c5+$c6+$c17+$c8+$c9+$c10+$c11+$c12+$c13+$c14+$c15+$c16; $c1=$c2=$c3=$c4=$c5=$c6=$c8=$c9=$c10=$c11=$c12=$c13=$c14=$c15=$c16=$c17=0;
									$tablevalues.='<font color="#000000">'.$row[$i].'</font>
									</b></td>
							</tr>'; ?>
						<?php $i++; $j++; }
						/*$tablevalues.='<tr>
							<td colspan=6 style="border-top: 1px solid #000000;  border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
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
									<font color="#000000">'.array_sum($column17).' / '.$weightage17tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column8).' / '.$weightage8tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column9).' / '.$weightage9tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column14).' / '.$weightage14tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column15).' / '.$weightage15tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column16).' / '.$weightage16tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column10).' / '.$weightage10tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column11).' / '.$weightage11tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column12).' / '.$weightage12tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">
								<font color="#000000">'.array_sum($column13).' / '.$weightage13tot.'</font>
							</td>
							<td style="border-top: 1px solid #000000; border-bottom: 2px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=bottom bgcolor="#1cabe2" sdnum="1033;0;0.0" data-fill-color="1cabe2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><b>
									<font color="#000000">'.array_sum($row).'</font>
								</b></td>
						</tr>'*/;

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
						<li class="breadcrumb-item active"><i class="fa fa-calandar"></i>AWC Quality Report</li>
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
							$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from aujyspbgxt8m3j9tjmv62c");
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
							$qryUserType = mysqli_query($conn,"SELECT DISTINCT type_mon FROM aujyspbgxt8m3j9tjmv62c ORDER BY type_mon ASC");
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
			<!--<form method="GET">
				<div class="row filter_css clearfix g-1">
					<div class="col-lg-4 col-md-4 col-sm-12">
						<div class="form-group">
							<b>Select District</b>
							<select class="form-select" id="formID" name="district_code">
								<option value="" selected> All Districts</option>
								<?php $qryUserType1 = mysqli_query($conn, "SELECT local_name,district_name FROM `districts` where district_code in(select distinct(district_id) from survey_data_monitoring where survey_name_id=24)");
								while ($dataUserType1 = mysqli_fetch_object($qryUserType1)) {
									$selected1 = '';
									if ($dataUserType1->local_name == $_REQUEST['district_code']) {
										$selected1 = 'selected';
									}
								?>
									<option value="<?= $dataUserType1->local_name ?>" <?= $selected1 ?>> <?= $dataUserType1->district_name ?> </option>
								<?php } ?>
							</select>

						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12">
						<div class="form-group">
							<b> Select Month </b>
							<input class="form-control" type="month" id="start" name="reporting_period" min="<?= $mindate ?>" value="<?= $maxdate ?>" />
						</div>
					</div>
					<div class="col-lg-2 col-md-4 mt-4">
						<div class="form-group ">
							<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search">Search</button>
						</div>
					</div>
					<div class="col-lg-1 col-md-4 mt-4">
						<div class="form-group ">
							<a href="AWC_HV_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
						</div>
					</div>
					<div class="col-lg-2 col-md-4 mt-4">
						<div class="form-group ">
							<button class="btn btn-success width-md waves-effect waves-light form-control" id="button-excel"><i class="fas fa-file-excel"></i> Export to excel</button>
						</div>
					</div>
				</div>
			</form>-->
			<section class="panel p-1">
				<div class="table-responsive">
					<table cellspacing="1"  border="1" id="simpleTable1" style="border-collapse: collapse;"  data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-t-s="thick" data-b-t-c="000000"  >
						  <tr>
							<td height="34" colspan="24" align="left" valign="middle" bgcolor="#806000" data-fill-color="806000" data-f-color="000000" ><h4 style="color:#FFFFFF; padding:5px; "><strong >Categorization- AWC CMAM Quality Services (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?></strong></h4></td>
						  </tr>
						  <tr>
							<td height="31" colspan="24" align="right" valign="bottom" bgcolor="#F2F2F2"  data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><br/></td>
						  </tr>
						  <tr>
							<td height="28" colspan="2" align="left" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" style="border-top:solid thin 1px #000000!important"><strong>Total AWC visited</strong><strong><br />
							</strong></td>
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="40" sdnum="1033;"><div align="center"><strong><?=$totalRecord?></strong></div></td>
							<td bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><br /></td>
							<td rowspan="5" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000">&nbsp;</td>
							<td colspan="3" rowspan="5" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" style="vertical-align:middle; text-align: center"><strong>Quality Parameters %</strong></td>
							<td colspan="4" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong>Poshan Tracker</strong><br /></td>
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="0.9" sdnum="1033;0;0%"><div align="center"><strong>
							<?=round(((array_sum($column1)*100)/$weightage1tot),0)?>%</strong></div></td>
							<td colspan="11" rowspan="5" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><br />
							  <br />      
							<br />      <br />      <br />      <br /></td>
						  </tr>
						  <tr>
							<td height="28" colspan="2" align="left" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong>Front runner (&gt;7 out of 10)</strong><strong><br />
							</strong></td>
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="10" sdnum="1033;"><div align="center"><strong><?=$frontRunner=count(array_filter($row, function($value) {
											return $value > 7; 
									  }));?></strong></div></td>
							<td bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="0.25" sdnum="1033;0;0%"><div align="center"><strong><?= round((($frontRunner * 100) / $totalRecord), 0)?>%</strong></div></td>
							<td colspan="4" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong>AWW aware home based principles</strong><br /></td>
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="0.55" sdnum="1033;0;0%"><div align="center"><strong><?=round((((array_sum($column2)+array_sum($column3)+array_sum($column4)+array_sum($column5)+array_sum($column6)+array_sum($column17))*100)/($weightage2tot+$weightage3tot+$weightage4tot+$weightage5tot+$weightage6tot+$weightage17tot)),0)?>%</strong></div></td>
						  </tr>
						  <tr>
							<td height="28" colspan="2" align="left" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong>Performer (5-7 out of 10)</strong><strong><br />
							</strong></td>
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="7" sdnum="1033;0;0"><div align="center"><strong>
							<?=$performer=count(array_filter($row, function($value) {
											return $value >= 5 && $value <=7; 
									  }));?></strong></div></td>
							<td bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="0.175" sdnum="1033;0;0%"><div align="center"><strong>
							<?= round((($performer * 100) / $totalRecord), 0)?>%</strong></div></td>
							<td colspan="4" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong>Frequency of visits</strong><br /></td> 
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="0.3" sdnum="1033;0;0%"><div align="center"><strong> 
							<?php $filtered9 = array_filter($column9, function ($value) { return !is_null($value) && $value !== ''; }); $weightage9tot1 = count($filtered9)*1.0; ?><?php echo round((((array_sum($filtered9)+array_sum($column8))*100)/($weightage9tot1+$weightage8tot)),0)?>% </strong></div></td> 
						  </tr>
						  <tr>
							<td height="28" colspan="2" align="left" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong>Aspirant(&lt;5 out of 10)</strong><strong><br />
							</strong></td>
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="23" sdnum="1033;"><div align="center"><strong>
							<?=$aspirent=count(array_filter($row, function($value) {
											return $value <5; 
									  }));?></strong></div></td>
							<td bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="0.575" sdnum="1033;0;0%"><div align="center"><strong> 
							<?= round((($aspirent * 100) / $totalRecord), 0)?>%</strong></div></td>
							<td colspan="4" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong>Validation of SAM/ MAM</strong><br /></td>
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="0.563432835820896" sdnum="1033;0;0%"><div align="center"><strong>
							<?php 
							$filtered14 = array_filter($column14, function ($value) { return !is_null($value) && $value !== ''; }); $weightage14tot1 = count($filtered14)*1.0; 
							$filtered15 = array_filter($column15, function ($value) { return !is_null($value) && $value !== ''; }); $weightage15tot1 = count($filtered15)*0.5; 
							$filtered16 = array_filter($column16, function ($value) { return !is_null($value) && $value !== ''; }); $weightage16tot1 = count($filtered16)*0.5; 
							?><?php echo round((((array_sum($filtered14)+array_sum($filtered15)+array_sum($filtered16))*100)/($weightage14tot1+$weightage15tot1+$weightage16tot1)),0);?> 
							<?php //round((((array_sum($column14)+array_sum($column15)+array_sum($column16))*100)/($weightage11tot+$weightage12tot+$weightage13tot)),0)?>%</strong></div></td>
						  </tr>
						  <tr>
							<td colspan="2" height="28" align="right" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" ><strong><br />
							</strong></td>
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong><br />
							</strong></td>
							<td bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdnum="1033;0;0%"><strong><br />
							</strong></td>
							<td colspan="4" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong>Referral</strong></td>
							<td align="center" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" sdval="0.375" sdnum="1033;0;0%"><div align="center"><strong><?php 
							
							echo round((((array_sum($column10)+array_sum($column11)+array_sum($column12)+array_sum($column13))*100)/(0.5*count($column11)+0.5*count($column12)+0.5*count($column13)+0.5*count($column14))),0)?>%     </strong></div></td>
						  </tr>
						  <tr>
							<td height="28" colspan="24" align="right" valign="bottom" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000"><strong><br /></td>
						  </tr>
						  <tr>
							<td height="51" align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>District</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>Block</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>Village Name</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>AWC Name</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>AWW Name</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>Parameters</strong></td>
							<td align="center" valign="middle" bgcolor="#DEEBF7" data-fill-color="DEEBF7" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>PT Updated</strong></td>
							<td colspan="6" align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>AWW aware of home based care principles</strong></td>
							<td colspan="2" align="center" valign="middle" bgcolor="#FFE7FF" data-fill-color="FFE7FF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>frequency of visit</strong></td>
							<td colspan="3" align="center" valign="middle" bgcolor="#E6FF9F" data-fill-color="E6FF9F" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>On validation data of SAM &amp; MAM matches</strong></td>
							<td colspan="4" align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>Referral</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>Total Score out of 10</strong></td>
						  </tr>
						  <tr>
							<td height="195" align="left" valign="middle" bgcolor="#F2F2F2"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2"  data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2"  data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Monitoring Indicators</td>
							<td align="center" valign="middle" bgcolor="#DEEBF7" data-fill-color="DEEBF7" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Measuring efficency of poshan tracker &gt;80%</td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Nutritional Mgmt. by AWW through home visits and THR distribution</td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Weekly/ fort- nightly home visit</td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Counselling &amp; demo-nstration for home based energy &amp; nutrient enhance-ment of diet</td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">AWC based session</td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Provide Take Home Ration</td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">AWW gave messages to the family of SAM case for enhancing the energy and nutrient of  daily diet (atleast 5/9 msgs)</td>
							<td align="center" valign="middle" bgcolor="#FFE7FF" data-fill-color="FFE7FF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Follow up at monthly VHSND and fortnightly Home visit (Health &amp; ICDS)</td>
							<td align="center" valign="middle" bgcolor="#FFE7FF" data-fill-color="FFE7FF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">AWW visited SAM child home in last fifteen days to provide information on child's diet</td>
							<td align="center" valign="middle" bgcolor="#E6FF9F" data-fill-color="E6FF9F" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Category recorded by AWW matches with Category Validated by monitor</td>
							<td align="center" valign="middle" bgcolor="#E6FF9F" data-fill-color="E6FF9F" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Correct weighing done by AWW</td>
							<td align="center" valign="middle" bgcolor="#E6FF9F" data-fill-color="E6FF9F" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Correct height/ length measure- ment done by AWW</td>
							<td align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Medical treatment shall a SAM case will receive if he/she has illness or with poor appetite</td>
							<td align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">Medical treatment shall a SAM case will receive if he/she has no illness and with good appetite</td>
							<td align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">where to refer , if SAM child does not gain weight or loses weight in two consecutive follow-ups visits</td>
							<td align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true">wherer to refer, if a child continues to remain in SAM category even after 3 months follow up period in CMAM program</td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true" ><strong><br />
							</strong></td>
						  </tr>
						  <tr>
							<td height="30" align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true" ><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>Source</strong></td>
							<td align="center" valign="middle" bgcolor="#DEEBF7" data-fill-color="DEEBF7" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>Poshan Tracker</strong></td>
							<td colspan="6" align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>AWW Interview</strong></td>
							<td align="center" valign="middle" bgcolor="#FFE7FF" data-fill-color="FFE7FF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>AWW Interview</strong></td>
							<td align="center" valign="middle" bgcolor="#FFE7FF" data-fill-color="FFE7FF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>SAM House Visit</strong></td>
							<td colspan="3" align="center" valign="middle" bgcolor="#E6FF9F" data-fill-color="E6FF9F" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>SAM / MAM Anthropometry Validation</strong></td>
							<td colspan="4" align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>AWW Interview</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
						  </tr>
						  <tr>
							<td height="30" align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong><br />
							</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>Weightage</strong></td>
							<td align="center" valign="middle" bgcolor="#DEEBF7" data-fill-color="DEEBF7" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FFFFC1" data-fill-color="FFFFC1" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#FFE7FF" data-fill-color="FFE7FF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FFE7FF" data-fill-color="FFE7FF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#E6FF9F" data-fill-color="E6FF9F" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>1.0</strong></td>
							<td align="center" valign="middle" bgcolor="#E6FF9F" data-fill-color="E6FF9F" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#E6FF9F" data-fill-color="E6FF9F" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="center" valign="middle" bgcolor="#FBE5D6" data-fill-color="FBE5D6" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>0.5</strong></td>
							<td align="left" valign="middle" bgcolor="#F2F2F2" data-fill-color="F2F2F2" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-wrap="true"><strong>10.0</strong></td>
						  </tr>
						<?=$tablevalues?>

						
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
			name: "AWC_HV_Quality_Report.xlsx", // Set your desired file name here
			sheet: {
				name: "AWC & HV Quality Report" // Set the sheet name here
			}
		});
	});
</SCRIPT>
	<!-- ************************************************************************** -->