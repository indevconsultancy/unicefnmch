<?php include_once('includes/config.php'); ?>
<?php define("title", "AWC Quality Report | UNICEF"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php
/*
function generateForIndicator1($facility,$qry,$chkService,$myer) {
	$Qest=mysqli_query($conn,"select * from a27ycjonhei3rt5h4badxn where 1=1 $qry and dov like'".$myer."' group by hf_name order by dov");
	$datast=mysqli_fetch_object($Qest)
    $tablevalues = '';
    if ($chkService != '') {
        $c1 = 'NV';
        $bgc = 'F9BB87';
        
        // Dynamically calculate based on the provided month-year parameter
        $np = $dataAWCvisit->{$monthYear . '_ref_vhsnd'} +
              $dataAWCvisit->{$monthYear . '_ref_pmsma'} +
              $dataAWCvisit->{$monthYear . '_ref_g_o'} +
              $dataAWCvisit->{$monthYear . '_ref_oth'};
        
        if ($np > 0) {
            $c1 = $np;
            $bgc = 'CCFF66';
        }
    } else {
        $c1 = 'NA';
        $bgc = 'FFFFFF';
    }

    $tablevalues .= '
        <td class="td_head3" align="center" valign=bottom bgcolor="' . $bgc . '" sdval="1" sdnum="1033;" 
        data-fill-color="' . $bgc . '" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" 
        data-b-r-s="thin" data-b-r-c="000000">';
    
    $tablevalues .= $c1;
    $tablevalues .= '</td>';

    return $tablevalues;
}
*/
$colspanss=10;
function generateForIndicator1($conn,$facility,$qry,$chkService,$monthYear,$districts,$m) {
	
	$Qest=mysqli_query($conn,"select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1");
	//echo "select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1";
	$datast=mysqli_fetch_object($Qest);
    $tablevalues1 = '';
	
							if($chkService!='') {
									 $c11 = 'NA';
									 $bgc1='F9BB87';
									//$np1 = $datast->mon1_ref_vhsnd+$datast->mon1_ref_pmsma+$datast->mon1_ref_g_o+$datast->mon1_ref_oth;
									$np1 = $datast->ivis_dose1;
									if ($np1>=0) {
										$c11 = $np1;
										$bgc1='CCFF66';
										$column1[$districts][$m]=$c11;
										if($c11==0)
										{
										$bgc1='F9BB87';
										$column1[$districts][$m]=0;
										}
									}
								}
								else { $c11='NV'; $bgc1='FFFFFF'; $column1[$dataAWCvisit->districts][$i]=0; }
								
								$tablevalues1.= '
								
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc1.'" sdval="'.$np1.'" sdnum="1033;" data-fill-color="'.$bgc1.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">'; 

									$tablevalues1.=$c11;
									$tablevalues1.= '</td>';
								
								
return $tablevalues1;								
	
}

function generateForIndicator2($conn,$facility,$qry,$chkService,$monthYear,$districts,$m) {
	
	$Qest=mysqli_query($conn,"select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1");
	//echo "select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1";
	$datast=mysqli_fetch_object($Qest);
    $tablevalues1 = '';
	
							if($chkService!='')
								{
								   $cc2=0;
									if ($datast->est_heam=='dig_heam' || $datast->est_heam=='aut_ana') {
										$c2 = 'Yes';
										$cc2=1;
										$bgc='CCFF66';
										$column2[$districts][$m]=1;
									} else {
										$c2 = 'No';
										$bgc='F9BB87';
										$column2[$districts][$m]=0;
									}
								} else { $c2='NV'; $bgc='FFFFFF'; $column2[$districts][$m]=0; }
									$tablevalues1.= '
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0.5" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">';
								   
									//$column2[$i]=$cc2;
									$tablevalues1.=$c2;
									$tablevalues1.= '</td>';
								
								
return $tablevalues1;								
	
}

function generateForIndicator3($conn,$facility,$qry,$chkService,$monthYear,$districts,$m) {
	
	$Qest=mysqli_query($conn,"select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1");
	//echo "select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1";
	$datast=mysqli_fetch_object($Qest);
    $tablevalues1 = '';
	
							 if($chkService!='')
								    {
									if ($datast->am_coun_mat=='yes') {
										$c3 = 'Yes';
										$bgc='CCFF66';
										$column3[$districts][$m]=1;
									} else {
										$c3 = 'NA';
										$bgc='F9BB87';
										$column3[$districts][$m]=0;
									}
									} else { $c3 = 'NV'; $bgc='FFFFFF'; $column3[$districts][$m]=0; }
									
									$tablevalues1.= '
									<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-s="thin" data-b-r-c="000000" data-b-r-c="000000">'; 
									
									$tablevalues1.=$c3;
									$tablevalues1.= '</td>';
								
								
return $tablevalues1;								
	
}

function generateForIndicator4($conn,$facility,$qry,$chkService,$monthYear,$districts,$m) {
	
	$Qest=mysqli_query($conn,"select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1");
	//echo "select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1";
	$datast=mysqli_fetch_object($Qest);
    $tablevalues1 = '';
	                         if($chkService!='') {
									 $c11 = 'NA';
									 $bgc1='F9BB87';
									$np1 = $datast->ivis_dose1;
									if ($np1>0) {
										$c11 = $np1;
										$bgc1='CCFF66';
										
									}
								}
								else { $c11='NV'; $bgc1='FFFFFF'; }
								
								$tablevalues1.= '
								
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc1.'" sdval="'.$np1.'" sdnum="1033;" data-fill-color="'.$bgc1.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">'; 

									$tablevalues1.=$c11;
									$tablevalues1.= '</td>';
									
							 if($chkService!='')
								    {
									if (is_numeric($datast->ivis_dose4)) {
										$c4 = $datast->ivis_dose4;
										$bgc='CCFF66';
										$column4[$districts][$m]=$c4;
									} else {
										$c4 = 'NA';
										$bgc='F9BB87';
										$column4[$districts][$m]=0;
									}
									} else{ $c4='NV'; $bgc='FFFFFF'; $column4[$districts][$m]=0; }
									
									$tablevalues1.= '<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0.5" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
									 
							       
									//$column4[$i]=$c4;
									$tablevalues1.=$c4;
									$tablevalues1.= '</td>';
							if($chkService!='')
								    {
								  $c5 = 0;
								    //if(if (is_numeric($dataAWCvisit->ivis_dose4)) )
									//{
									$np = $datast->mon1_ref_vhsnd+$datast->mon1_ref_pmsma+$datast->mon1_ref_g_o+$datast->mon1_ref_oth;
									 $c5ctot=round((($datast->ivis_dose4*100)/$np),1);
										
										if ($c5ctot>=0) {
											$c5 = $c5ctot;
											$bgc='CCFF66';
											$column5[$districts][$m]=$c5;
										} else {
											$c5 = 'NA';
											$bgc='F9BB87';
											$column5[$districts][$m]=0;
										}
									//}
									//else {
									//	$c5='NA*'; $bgc='CCFF66'; 
									//}
									} else { $c5='NV'; $bgc='FFFFFF'; $column5[$districts][$m]=0; }
									$tablevalues1.= '
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">';
								
									//$column5[$i]=$c5.'%';
									if(is_infinite($c5ctot))
									{
										$tablevalues1.='NA*';
									}
									else {
									$tablevalues1.=$c5;}
									$tablevalues1.= '</td>';
								
								
return $tablevalues1;								
	
}

function generateForIndicator41($conn,$facility,$qry,$chkService,$monthYear,$districts,$m) {
	
	$Qest=mysqli_query($conn,"select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1");
	//echo "select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1";
	$datast=mysqli_fetch_object($Qest);
    $tablevalues1 = '';
	
							 if($chkService!='')
								    {
								  $c5 = 0;
								    //if(if (is_numeric($dataAWCvisit->ivis_dose4)) )
									//{
									$np = $datast->ivis_dose1;
									 $c5ctot=round((($datast->ivis_dose4*100)/$np),1);
										
										if ($c5ctot>=0) {
											$c5 = $c5ctot;
											$bgc='CCFF66';
											$column5[$districts][$m]=$c5;
										} else {
											$c5 = 'NA';
											$bgc='F9BB87';
											$column5[$districts][$m]=0;
										}
									//}
									//else {
									//	$c5='NA*'; $bgc='CCFF66'; 
									//}
									} else { $c5='NV'; $bgc='FFFFFF'; $column5[$districts][$m]=0; }
									$tablevalues1.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">';
								
									//$column5[$i]=$c5.'%';
									if(is_infinite($c5ctot))
									{
										$tablevalues1.='NA*';
									}
									else {
									$tablevalues1.=$c5;}
									$tablevalues1.= '</td>';
								
								
return $tablevalues1;								
	
}

function generateForIndicator5($conn,$facility,$qry,$chkService,$monthYear,$districts,$m) {
	
	$Qest=mysqli_query($conn,"select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1");
	//echo "select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1";
	$datast=mysqli_fetch_object($Qest);
    $tablevalues1 = '';
	
							 if($chkService!='')
								    {
									$c6 = 0;
									if ($dataAWCvisit->am_register!='' && $dataAWCvisit->am_register=='yes') {
										if($c1=='NV' && is_numeric($c5))
										{
										$c6 = 'No';
										$bgc='F9BB87';	
										$column6[$districts][$m]=0;
										} else {
										$c6 = 'Yes';
										$bgc='CCFF66';
										$column6[$districts][$m]=1;
										}
									} else {
										$c6 = 'NA';
										$bgc='F9BB87';
										$column6[$districts][$m]=0;
									}
									} else { $c6='NV'; $bgc='FFFFFF'; $column6[$districts][$m]=0; }
									$tablevalues1.= '<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">';
								   
									$column6[$i]=$c6;
									$tablevalues1.=$c6;
									$tablevalues1.= '</td>';
								
								
return $tablevalues1;								
	
}

function generateForIndicator6($conn,$facility,$qry,$chkService,$monthYear,$districts,$m) {
	
	$Qest=mysqli_query($conn,"select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1");
	//echo "select * from a27ycjonhei3rt5h4badxn where 1=1 and date(dov) like'".$monthYear."%' and hf_name='".$facility."' order by id desc limit 0,1";
	$datast=mysqli_fetch_object($Qest);
    $tablevalues1 = '';
	
							 if($chkService!='')
								   {
									$c7 = 0;
									if ($dataAWCvisit->am_coun_mat!='' && $dataAWCvisit->am_coun_mat== 'yes') {
										$c7 = 'Yes';
										$bgc='CCFF66';
										$column7[$districts][$m]=1;
									} else {
										$c7 = 'NA';
										$bgc='F9BB87';
										$column7[$districts][$m]=0;
									}
								   } else { $c7='NV'; $bgc='FFFFFF'; $column7[$districts][$m]=0; }
									$tablevalues1.= '<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									//$column7[$i]=$c7;
									$tablevalues1.=$c7;
									$tablevalues1.= '</td>';
								
								
return $tablevalues1;								
	
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
		/*
		if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {
		
        $userTypes = array_filter($_REQUEST['uType']); // Remove empty values
		//print_r($userTypes);
        if (!empty($userTypes)) {
             $userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
			$qry .= " AND assessor_design IN ($userTypeList)";
        }
		
		
    }*/
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
		/*
		if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {
		
        $userTypes = array_filter($_REQUEST['uType']); // Remove empty values
		//print_r($userTypes);
        if (!empty($userTypes)) {
             $userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
			$qry .= " AND assessor_design IN ($userTypeList)";
        }
		
		
    }*/
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

$sqlVHSNDvisit = mysqli_query($conn, "select * from a27ycjonhei3rt5h4badxn where 1=1 $qry");
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
                        	if($_REQUEST['uType']!='')
					{
									
                        $tablevalues=''; 
						$i=0; //Row
						$j=0; //Column
						
						//$sqlAWCvisit = mysqli_query($conn, "select * from a27ycjonhei3rt5h4badxn where 1=1 $qry $qry1 group by hf_name order by dov");
						$sqlAWCvisit = mysqli_query($conn, "SELECT f.*, m.* FROM ivis_facilities f LEFT JOIN ( SELECT * FROM a27ycjonhei3rt5h4badxn WHERE 1=1 $qry $qry1 GROUP BY hf_name) m ON f.ccenter_name = m.hf_name ORDER BY f.districts");
						while ($dataAWCvisit = mysqli_fetch_object($sqlAWCvisit)) { 
							 $bgc='';
							 $chkService='';
							if($dataAWCvisit->is_admin=='yes')
							{
								 $chkService='yes';
							}
							$tablevalues.=' <tr>
							    <td class="td_head3" align="left" valign=bottom  data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-b-r-s="thin" data-b-r-c="000000" data-wrap="true">
									'.($i+1).'
								</td>
								<td class="td_head4" height="20" align="left" valign=bottom  data-fill-color="'.$bgc.'" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-b-r-s="thin" data-b-r-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->districts).'
								</td> 
								
								<td class="td_head3" align="left" valign=bottom data-fill-color="'.$bgc.'" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-b-r-s="thin" data-b-r-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->ccenter_name).'
								</td>';
								if($_REQUEST['uType']=='PW referred')
								{
									for ($k = 0; $k < $m; $k++) {
									$tablevalues.=generateForIndicator1($conn,$dataAWCvisit->hf_name,$qry,$chkService,$fullfilter[$k],$dataAWCvisit->districts,$i);
									}
								}
                                if($_REQUEST['uType']=='' || $_REQUEST['uType']=='Testing')
								{								
									////////////////////////////
									for ($k = 0; $k < $m; $k++) {
									$tablevalues.=generateForIndicator2($conn,$dataAWCvisit->hf_name,$qry,$chkService,$fullfilter[$k],$dataAWCvisit->districts,$i);
									}
								}
									/////////////////////////////
								if($_REQUEST['uType']=='' || $_REQUEST['uType']=='Treatment')
								{
									for ($k = 0; $k < $m; $k++) {
									$tablevalues.=generateForIndicator3($conn,$dataAWCvisit->hf_name,$qry,$chkService,$fullfilter[$k],$dataAWCvisit->districts,$i);
									}
									 
								}
								if($_REQUEST['uType']=='' || $_REQUEST['uType']=='Doses')
								{	
									////////////////////////////////
									for ($k = 0; $k < $m; $k++) {
									$tablevalues.=generateForIndicator4($conn,$dataAWCvisit->hf_name,$qry,$chkService,$fullfilter[$k],$dataAWCvisit->districts,$i);
									}
								}
								
								
								if($_REQUEST['uType']=='' || $_REQUEST['uType']=='Register')
								{
									for ($k = 0; $k < $m; $k++) {
									$tablevalues.=generateForIndicator5($conn,$dataAWCvisit->hf_name,$qry,$chkService,$fullfilter[$k],$dataAWCvisit->districts,$i);
									}
									///////////////////////////////////////
									
								}
								if($_REQUEST['uType']=='' || $_REQUEST['uType']=='IEC')
								{
									for ($k = 0; $k < $m; $k++) {
									$tablevalues.=generateForIndicator6($conn,$dataAWCvisit->hf_name,$qry,$chkService,$fullfilter[$k],$dataAWCvisit->districts,$i);
									}
									///////////////////////////////////
									
								}
							$tablevalues.= '</tr>'; ?>
<?php $i++; $j++; } } 
					//////////////////////////// Indicator Wise
						 else {

                        $tablevalues=''; 
						$i=0; //Row
						$j=0; //Column
						
						//$sqlAWCvisit = mysqli_query($conn, "select * from a27ycjonhei3rt5h4badxn where 1=1 $qry group by hf_name order by dov");
						$sqlAWCvisit = mysqli_query($conn, "SELECT f.*, m.* FROM ivis_facilities f LEFT JOIN ( SELECT * FROM a27ycjonhei3rt5h4badxn WHERE 1=1 $qry GROUP BY hf_name) m ON f.ccenter_name = m.hf_name ORDER BY f.districts");
						while ($dataAWCvisit = mysqli_fetch_object($sqlAWCvisit)) { 
							 $bgc='';
							 $chkService='';
							if($dataAWCvisit->is_admin=='yes')
							{
								 $chkService='yes';
							}
							$tablevalues.=' <tr>
							    <td class="td_head3" align="left" valign=bottom  data-fill-color="FFFFFF" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-b-r-s="thin" data-b-r-c="000000" data-wrap="true">
									'.($i+1).'
								</td>
								<td class="td_head4" height="20" align="left" valign=bottom  data-fill-color="'.$bgc.'" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-b-r-s="thin" data-b-r-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->districts).'
								</td> 
								
								<td class="td_head3" align="left" valign=bottom data-fill-color="'.$bgc.'" data-f-color="000000" data-b-s="thin" data-b-c="000000" data-b-r-s="thin" data-b-r-c="000000" data-wrap="true">
									'.ucfirst($dataAWCvisit->ccenter_name).'
								</td>';
								if($chkService!='')
								{
									
									 $c1 = 'NA';
									 $bgc='F9BB87';
									//$np = $dataAWCvisit->mon1_ref_vhsnd+$dataAWCvisit->mon1_ref_vhsnd+$dataAWCvisit->mon1_ref_pmsma+$dataAWCvisit->mon1_ref_g_o+$dataAWCvisit->mon1_ref_oth;
									$np = $dataAWCvisit->ivis_dose1;
									if ($np>=0) {
										$c1 = $np;
										$column1[$dataAWCvisit->districts][$i]=$c1;
										$bgc='CCFF66';
										if($c1==0)
										{
										$bgc='F9BB87';
										}
									}
								}
								else { $c1='NV'; $bgc='FFFFFF'; $column1[$dataAWCvisit->districts][$i]=0; }
								$tablevalues.= '
								
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="1" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
								   
                                    
									$tablevalues.=$c1;
									
									////////////////////////////
									if($chkService!='')
								{
								   $cc2=0;
									if ($dataAWCvisit->est_heam=='dig_heam' || $dataAWCvisit->est_heam=='aut_ana') {
										$c2 = 'Yes';
										$cc2=1;
										$column2[$dataAWCvisit->districts][$i]=1;
										$bgc='CCFF66';
									} else {
										$c2 = 'No';
										$bgc='F9BB87';
										$column2[$dataAWCvisit->districts][$i]=0;
									}
								} else { $c2='NV'; $bgc='FFFFFF'; $column2[$dataAWCvisit->districts][$i]=0; }
									
									
									$tablevalues.= '
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0.5" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">';
								   
									
									$tablevalues.=$c2;
									/////////////////////////////
									 if($chkService!='')
								    {
									if ($dataAWCvisit->am_coun_mat=='yes') {
										$c3 = 'Yes';
										$bgc='CCFF66';
										$column3[$dataAWCvisit->districts][$i]=1;
									} else {
										$c3 = 'No';
										$bgc='F9BB87';
										$column3[$dataAWCvisit->districts][$i]=0;
									}
									} else { $c3 = 'NV'; $bgc='FFFFFF'; $column3[$dataAWCvisit->districts][$i]=0; }
									
									$tablevalues.= '
									<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-s="thin" data-b-r-c="000000" data-b-r-c="000000">'; 
									
									
									$tablevalues.=$c3;
									////////////////////////////////
									if($chkService!='')
								    {
									if (is_numeric($dataAWCvisit->ivis_dose4)) {
										$c4 = $dataAWCvisit->ivis_dose4;
										$bgc='CCFF66';
										if($c4==0)
										{
											$bgc='F9BB87';
										}
										$column4[$dataAWCvisit->districts][$i]=$c4;
									} else {
										$c4 = 'NA';
										$bgc='F9BB87';
										$column4[$dataAWCvisit->districts][$i]=0;
									}
									} else{ $c4='NV'; $bgc='FFFFFF'; $column4[$dataAWCvisit->districts][$i]=0; }
									
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0.5" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">'; 
									 
							       
									
									$tablevalues.=$c4;
									////////////////////////////////////
									if($chkService!='')
								    {
								  $c5 = 0;
								    //if(if (is_numeric($dataAWCvisit->ivis_dose4)) )
									//{
									
									 $c5ctot=round((($dataAWCvisit->ivis_dose4*100)/$np),1);
										
										if ($c5ctot>=0) {
											$c5 = $c5ctot;
											$bgc='CCFF66';
											if($c5==0) 
											{
												$bgc='F9BB87';
											}
											$column5[$dataAWCvisit->districts][$i]=$c5;
										} else {
											$c5 = 'NA';
											$bgc='F9BB87';
											$column5[$dataAWCvisit->districts][$i]=0;
										}
									//}
									//else {
									//	$c5='NA*'; $bgc='CCFF66'; 
									//}
									} else { $c5='NV'; $bgc='FFFFFF'; $column5[$dataAWCvisit->districts][$i]=0; }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">';
								
									
									if(is_infinite($c5ctot))
									{
										$tablevalues.='NA*';
									}
									else {
									$tablevalues.=$c5; }
									///////////////////////////////////////
									if($chkService!='')
								    {
									$c6 = 0;
									if ($dataAWCvisit->am_register!='' && $dataAWCvisit->am_register=='yes') {
										if(is_numeric($c5))
										{
										$c6 = 'No';
										$bgc='F9BB87';	
										$column6[$dataAWCvisit->districts][$i]=0;
										} else {
										$c6 = 'Yes';
										$bgc='CCFF66';
										$column6[$dataAWCvisit->districts][$i]=1;
										}
									} else {
										$c6 = 'NA';
										$bgc='F9BB87';
										$column6[$dataAWCvisit->districts][$i]=0;
									}
									} else { $c6='NV'; $bgc='FFFFFF'; $column6[$dataAWCvisit->districts][$i]=0; }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">';
								   
									
									$tablevalues.=$c6;
									///////////////////////////////////
									if($chkService!='')
								   {
									$c7 = 0;
									if ($dataAWCvisit->am_coun_mat!='' && $dataAWCvisit->am_coun_mat== 'yes') {
										$c7 = 'Yes';
										$bgc='CCFF66';
										$column7[$dataAWCvisit->districts][$i]=1;
									} else {
										$c7 = 'No';
										$bgc='F9BB87';
										$column7[$dataAWCvisit->districts][$i]=0;
									}
								   } else { $c7='NV'; $bgc='FFFFFF'; $column7[$dataAWCvisit->districts][$i]=0; }
									$tablevalues.= '
									
								</td>
								<td class="td_head3" align="center" valign=bottom bgcolor="'.$bgc.'" sdval="0" sdnum="1033;" data-fill-color="'.$bgc.'" data-f-color="000000" data-b-r-s="thin" data-b-r-c="000000" data-b-r-s="thin" data-b-r-c="000000">';
									
									
									$tablevalues.=$c7;
									$tablevalues.= '

								</td>
							</tr>'; ?>
						 <?php $i++; $j++; } }
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
							<b>Indicator Wise</b>
							<select class="form-select select2" id="uType" name="uType"  >
							<option value="" <?= ($_REQUEST['uType']=='') ? 'selected' : '' ?>> All</option>
							
							<?php 
							$selected='';
							$indiArray=['PW referred','Testing','Treatment','Doses','Register','IEC'];
							
							foreach($indiArray as $keyindicator) {
								if(isset($_REQUEST['uType']))
								{
								$selected = ($_REQUEST['uType']==$keyindicator) ? 'selected' : '';
								}
								else {
									$selected='';
								}
								
								?>
								<option value="<?=$keyindicator?>" <?=$selected?> > <?=$keyindicator?> </option>
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
						  <table cellspacing="1"   border="1" id="simpleTable1" style="border-collapse: collapse;"  data-fill-color="F2F2F2" data-f-color="000000"  data-b-s="thin" data-b-c="000000">
 <?php if($_REQUEST['uType']=='') { ?>
  <tr>
   <td height="34" colspan="10" align="left" valign="middle" bgcolor="#806000" data-fill-color="806000" data-f-color="FFFFFF" ><h4 style="color:#FFFFFF; padding:5px; "><strong >IV Iron Sucrose Implementation Report  (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F Y',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?></strong></h4></td>
  </tr>
  <tr>
    <td width="64" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">S.No</td>
    <td width="64" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">District</td>
    <td width="202" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">Facility Name</td>
    <td width="64" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">1.<br />
      No. of PW referred for treatment from VHSND/PMSMA/OPD</td>
    <td width="64" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">2. <br />
      Provision of measuring Hemoglobin using an Auto analyzer or Digital HB-meter</td>
    <td width="" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">3. <br />
      Treatment being provided as per protocol (correct dose calculation and under medical supervision)</td>
    <td width="64" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">No of PW completed All doses</td>
    <td width="" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">4. <br />
      No and % of PW completed all doses (No. of PW completed all doses / No. of PW received IV Iron Sucrose)</td>
    <td width="" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">5. <br />
      Record/ register updated</td>
    <td width="143" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000">6. <br />
      IV Iron Sucrose protocol / IEC material&nbsp;available</td>
  </tr>
 <?php } ?>
 <?php if($_REQUEST['uType']!='') { 
 if($_REQUEST['uType']=='Doses') {  $colspanss=3+($m*3); } 
 else {  $colspanss=3+($m*1); }
 
 ?>
  <tr>
   <td height="34" colspan="<?=$colspanss?>" align="center" valign="middle" bgcolor="#806000" data-fill-color="806000" data-f-color="FFFFFF" ><h4 style="color:#FFFFFF; padding:5px; "><strong >
<?=$_REQUEST['uType']?> (District: <?php if($districtText!='') { echo ucwords($districtText); } else { echo $allDistricts; }  ?>) <?php if($first==$last) { ?> <?=date('F-Y',strtotime($last))?> <?php } else { ?> <?=date('F Y',strtotime($first))?> - <?=date('F Y',strtotime($last))?> <?php } ?></strong></h4> </td>
  </tr>
  <tr>
    <td width="64" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000" rowspan="2">S.No</td>
    <td width="64" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000" rowspan="2">District</td>
    <td width="202" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000" rowspan="2">Facility Name</td>
	
	<?php for ($i = 0; $i < $m; $i++) { 
	
	if($_REQUEST['uType']=='Doses') {
	?>
	<td data-b-a-s="thin" data-b-a-c="000000" align="center" valign="middle" class="td_head_main" colspan="3"  style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b><?=$fullfilterText[$i];?></b></td>
	<?php } else { ?>						
	                            
	<td data-b-a-s="thin" data-b-a-c="000000" align="center" valign="middle"  class="td_head_main" colspan="1" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b><?=$fullfilterText[$i];?></b></td>
	<?php } } ?>
	</tr>
	<tr>
	<?php
								if($_REQUEST['uType']=='PW referred')
								{
									for ($i = 0; $i < $m; $i++) { 
									?>
									<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b>No. of PW referred for treatment from VHSND/PMSMA/OPD</b></td>
									<?php
									}
								}
                                if($_REQUEST['uType']=='Testing')
								{								
									////////////////////////////
									for ($i = 0; $i < $m; $i++) { 
									?>
									<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b>Provision of measuring Hemoglobin using an Auto analyzer or Digital HB-meter</b></td>
									<?php
									}
								}
									/////////////////////////////
								if($_REQUEST['uType']=='Treatment')
								{
									for ($i = 0; $i < $m; $i++) { 
									?>
									<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b>Treatment being provided as per protocol (correct dose calculation and under medical supervision)</b></td>
									<?php
									}
									
								}
								if($_REQUEST['uType']=='Doses')
								{	
									////////////////////////////////
									for ($i = 0; $i < $m; $i++) { 
									?>
									<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b>No of PW completed All doses</b></td>
									<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b>No of PW referred</b></td>
									<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b>% PW who completed all doses of the referred PW</b></td>
									<?php
									}
								}
								
								
								if($_REQUEST['uType']=='Register')
								{
									for ($i = 0; $i < $m; $i++) { 
									?>
									<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b>Record/ register updated</b></td>
									<?php
									}
									///////////////////////////////////////
									
								}
								if($_REQUEST['uType']=='IEC')
								{
									for ($i = 0; $i < $m; $i++) { 
									?>
									<td data-b-a-s="thin" data-b-a-c="000000" class="td_head_main" style="font-size: 14px;font-weight: 900;" data-f-sz="14" data-f-bold="true"><b>IV Iron Sucrose protocol / IEC material&nbsp;available</b></td>
									<?php
									}
									
									
								} ?>
	
	
	
	</tr>
	
	
 <?php } ?>

						<?=$tablevalues?>
  <?php if($_REQUEST['uType']=='') { ?>
  
  <tr bgcolor="#9e9e9e" data-fill-color="9e9e9e" data-f-color="000000" style="font-size:17px">
    <td colspan="3" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000" >Gaya Total</td>
    <td width="64" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column1['Gaya'])?></td>
    <td width="64" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column2['Gaya'])?></td>
    <td width="" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column3['Gaya'])?></td>
    <td width="64" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column4['Gaya'])?></td>
    <td width="" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=round(((array_sum($column4['Gaya'])*100)/array_sum($column1['Gaya'])),1)?></td>
    <td width="" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column6['Gaya'])?></td>
    <td width="143" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column7['Gaya'])?></td>
  </tr>
  <tr bgcolor="#9e9e9e" data-fill-color="9e9e9e" data-f-color="000000" style="font-size:17px">
    <td colspan="3" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000" >Purnea Total</td>
    <td width="64" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column1['Purnea'])?></td>
    <td width="64" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column2['Purnea'])?></td>
    <td width="" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column3['Purnea'])?></td>
    <td width="64" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column4['Purnea'])?></td>
    <td width="" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=round(((array_sum($column4['Purnea'])*100)/array_sum($column1['Purnea'])),1)?></td>
    <td width="" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column6['Purnea'])?></td>
    <td width="143" align="center" style="font-weight:700" data-b-r-s="thin" data-b-r-c="000000"><?=array_sum($column7['Purnea'])?></td>
  </tr>
 <?php } ?>
   <tr>
   <td height="34" colspan="<?=$colspanss?>" align="left" valign="middle" bgcolor="#806000" data-fill-color="806000" data-f-color="FFFFFF" ><h6 style="color:#FFFFFF; padding:5px; "><strong >Note-	<br/>
Not Applicable (NA): Anemia management in this facility is not conducted using IV iron sucrose during this period, or calculation is not possible. <br/>
Not Visited (NV): The facility was not visited during this period.</strong></h6></td>
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
			name: "IV Iron Sucrose Implementation Report.xlsx", // Set your desired file name here
			sheet: {
				name: "AWC & HV Quality Report" // Set the sheet name here
			}
		});
	});
</SCRIPT>
	<!-- ************************************************************************** -->