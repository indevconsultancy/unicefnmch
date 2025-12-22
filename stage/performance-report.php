<?php include_once('include/config.php'); ?>
<?php include_once('include/functions.php'); ?>
<!doctype html>
<html lang="en" data-layout="horizontal" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="blue" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-body-image="none" data-sidebar-visibility="show">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | Aaklan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('include/link.php'); ?>

</head>

<body>
    <div id="layout-wrapper">
        <?php include('include/header.php'); ?>
		<?php
function wagesCalculate($tsurvey)
{
	$return=0;
	if($tsurvey>=4)
	{
		$return=1.0;
	}
	if($tsurvey==3)
	{
		$return=.75;
	}
	if($tsurvey==2)
	{
		$return=.50;
	}
	if($tsurvey==1)
	{
		$return=.25;
	}
	return $return;
}

$qry = "";
$startDate=new DateTime(date('Y-m-01'));
$endDate=new DateTime(date('Y-m-d'));

$ssdate=$startDate->format('01-m-Y');
$eedate=$endDate->format('d-m-Y');

// Check if the search button was pressed
if (isset($_REQUEST['search'])) {

   
    // Check for the date range selection
    if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
        $startDate = new DateTime($_REQUEST['fromDate']);
        $endDate = new DateTime($_REQUEST['toDate']);
        // Modify the end date to the last moment of the day
        // Add date range to the query
        $qry1 .= " AND date(visit_date) BETWEEN '" . $startDate->format('Y-m-d') . "' AND '" . $endDate->format('Y-m-d') . "'";
     $ssdate=$startDate->format('d-m-Y');
	 $esdate=$endDate->format('d-m-Y');
	}

    // Check for selected form IDs
    if (isset($_REQUEST['formID']) && !empty($_REQUEST['formID'])) {
        $formIds = array_filter($_REQUEST['formID']); // Remove empty values
        if (!empty($formIds)) {
            $ssidss = implode(',', array_map('intval', $formIds)); // Convert to integer to prevent SQL injection
            $qry .= " AND survey_name_id IN ($ssidss)";
        }
    }

    // Check for selected user types
    if (isset($_REQUEST['uType']) && !empty($_REQUEST['uType'])) {
		
        $userTypes = array_filter($_REQUEST['uType']); // Remove empty values
		//print_r($userTypes);
        if (!empty($userTypes)) {
             $userTypeList = "'" . implode("','", $userTypes) . "'"; // Convert to integer to prevent SQL injection
			$qry .= " AND user_types IN ($userTypeList)";
        }
		
		
    }
	else {
    // Default query if no search is performed
    $qry.= " AND user_types LIKE '%aiims%'";
	}
}
else {
    // Default query if no search is performed
    $qry.= " AND user_types LIKE '%aiims%'";
	} 

while ($startDate <= $endDate) {
    // Format the date and add to the array
    $dates1[] = $startDate->format('Y-m-d'); // Change format as needed
    // Add one day to the current date
    $startDate->modify('+1 day');
}
// Uncomment the following line to debug the constructed query
// echo $qry;
?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Performance Report</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active">Performance Report</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- pest your content here start -->
                    <div class="row">
                     <div class="card">
					   <div class="card-header align-items-center">
                       </div>
						<div class="card-body">
						<form method="GET">
							<div class="row filter_css clearfix g-1">
								<div class="col-lg-4 col-md-4 col-sm-12">
									<div class="form-group">
										<b>Select Form</b>
										<select class="form-control" id="choices-multiple-remove-button" data-choices data-choices-removeItem name="choices-multiple-remove-button" multiple>
                                                            <option value="Choice 1" selected>Choice 1</option>
                                                            <option value="Choice 2">Choice 2</option>
                                                            <option value="Choice 3">Choice 3</option>
                                                            <option value="Choice 4">Choice 4</option>
                                                        </select>
										<select class="form-control select2" multiple name="formID[]" id="formID" required>
											<option value="" <?= (empty($_REQUEST['formID']) || in_array("", $_REQUEST['formID'])) ? 'selected' : '' ?>> All Forms</option>
											<?php 
											$qryUserType1 = mysqli_query($conn, "SELECT DISTINCT survey_name, survey_name_id FROM survey_data_monitoring ORDER BY survey_name ASC");
											
											while ($dataUserType1 = mysqli_fetch_object($qryUserType1)) {
												// Set $selected1 based on whether the survey_name_id is in the formID array
												$selected1 = (!empty($_REQUEST['formID']) && in_array($dataUserType1->survey_name_id, $_REQUEST['formID'])) ? 'selected' : '';
												?>
												<option value="<?= $dataUserType1->survey_name_id ?>" <?= $selected1 ?>> <?= $dataUserType1->survey_name ?> </option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="col-lg-2 col-md-3 col-sm-12">
									<div class="form-group">
										<b>From Date</b>
										<input class="form-control" type="date" id="fromDate" name="fromDate" value="<?= isset($_REQUEST['fromDate']) ? $_REQUEST['fromDate'] : date('Y-m-01') ?>" required style="border-radius: 4px; padding-bottom: 7px; padding-right: 5px; padding-top: 9px;" />
									</div>
								</div>

								<div class="col-lg-2 col-md-3 col-sm-12">
									<div class="form-group"> 
										<b>To Date</b>
										<input class="form-control" type="date" id="toDate" name="toDate" value="<?= isset($_REQUEST['toDate']) ? $_REQUEST['toDate'] : date('Y-m-d') ?>" required style="border-radius: 4px; padding-bottom: 7px; padding-right: 5px; padding-top: 9px;"/>
									</div>
								</div>

								<div class="col-lg-2 col-md-3 col-sm-12">
									<div class="form-group"> 
										<b>Users Type</b>
										<select class="form-select select2" id="uType" name="uType[]" multiple >
										<?php 
										$selected='';
										$qryUserType = mysqli_query($conn,"SELECT DISTINCT user_types FROM survey_data_monitoring ORDER BY user_types ASC");
										while($dataUserType = mysqli_fetch_object($qryUserType)) {
											if(isset($_REQUEST['uType']))
											{
											$selected = (!empty($_REQUEST['uType']) && in_array($dataUserType->user_types, $_REQUEST['uType'])) ? 'selected' : '';
											}
											else if($dataUserType->user_types=='aiims')
											{
												$selected='selected';
											}
											else {
												$selected='';
											}
											
											?>
											<option value="<?=$dataUserType->user_types?>" <?=$selected?> > <?=$dataUserType->user_types?> </option>
										<?php } ?>
										</select>
									</div>
								</div>

								<div class="col-lg-1 col-md-4 mt-4">
									<div class="form-group">
										<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search">Search</button>
									</div>
								</div>
								
								<div class="col-lg-1 col-md-4 mt-4">
									<div class="form-group">
										<a href="attandance_report.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
									</div>
								</div>
							</div>
						</form>
                        <!-- page start-->
			<div class="row">
				<div class="col-sm-12">
					<!--Start Filter-->
					<div class="container mb-3 mt-3">
						  <button id='button-excel'>Export to Excel</button>
					</div>
					<section class="panel p-1">
						
						<div style="width: 100%; overflow: auto;">
						
						<?php
						
							

							// Print the list of months
							foreach ($months as $month) {
								echo $month . "<br>"; // Output the month
							}
						    
							$calculationdate=$month.'-'.$year;
							$calculationdate1='01-'.$month.'-'.$year;
							$dd=date('m-Y');
							if($calculationdate==$dd)
							{
							$numDays = date('d');	
							}
                            else{
							$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
							}

							// Get the number of days in the selected month
							//$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            
							// Fetch distinct users for the report
							//echo "SELECT DISTINCT monitor_name FROM survey_data_monitoring where 1=1 and monitor_name not in('Dr.Mohit Bhardwaj','Dr Deepika Agrawal','Jyoti kumari','GAGAN GAUTAM') $qry1 ORDER BY monitor_name ASC";
							$usersStmt = mysqli_query($conn,"SELECT DISTINCT monitor_name FROM survey_data_monitoring where 1=1 and monitor_name not in('Dr.Mohit Bhardwaj','Dr Deepika Agrawal','GAGAN GAUTAM') $qry $qry1 ORDER BY monitor_name ASC");
							$userRows=mysqli_num_rows($usersStmt);
							$colspan=2;
							if($userRows>0)
							{
								$colspan=$userRows+2;
							}
							$userslist=array();
							echo "<table style='width: 100%;' id='example' class='display nowrap table table-striped table-responsive table-bordered' data-col-height='200' data-text-center='true' data-vertical-align='middle' >";

							// Create table headers
							echo "<thead>
								  <tr style='background-color:#ccc!important' data-text-center='true' data-vertical-align='middle'> <td data-text-center='true' data-vertical-align='middle' style='font-size:16px; font-weight:700; text-align:center; vertical-align: middle; height:50px; padding-top:10px;' colspan='".$colspan."'>Reporting Period:  ".date('d-M-Y',strtotime($ssdate))." to ".date('d-M-Y',strtotime($eedate))."</td></tr>";
							echo "<th data-f-bold='true' data-f-sz='12' data-fill-color='9fd9ef' data-f-color='000000' style='background-color: #9fd9ef !important; color: #000 !important;'>Date</th>";
							while($users = mysqli_fetch_object($usersStmt))
							{
								$userslist[]=$users->monitor_name;
								echo "<th data-f-bold='true' data-f-sz='12' data-fill-color='9fd9ef' data-f-color='000000' data-text-center='true' data-vertical-align='middle' style='background-color: #9fd9ef !important; color: #000 !important; '>".ucwords($users->monitor_name)."</th>";
							}
							echo "<th data-f-bold='true' data-f-sz='12' data-fill-color='9fd9ef' data-f-color='000000' data-text-center='true' data-vertical-align='middle' style='background-color: #9fd9ef !important; color: #000 !important;'>Total</th>";
							echo "</tr></thead>";
							
							// Loop through each day of the month
							$usersTotal=array();
							$usersTotal1=array();
							//print_r($dates1);
							foreach ($dates1 as $day)
							{
							//for ($day =1;  $day<=$numDays; $day++) { 
								$dayTotal=0;
								$dates = $day; // Format date as YYYY-MM-DD
								
								echo "<tr>";
								echo "<td width='10%'>" . date('d-M-Y',strtotime($dates)) . "</td>"; // Display the current date
								$i=0;
								
								foreach($userslist as $usersl)
									{
								   //echo "SELECT count(*) as total_survey FROM survey_data_monitoring WHERE monitor_name = '".$usersl."' AND date(visit_date) ='".$dates."' $qry ";
									$stmt = mysqli_query($conn,"SELECT count(*) as total_survey FROM survey_data_monitoring WHERE monitor_name = '".$usersl."' AND date(visit_date) ='".$dates."' $qry ");
									$attendance = mysqli_fetch_object($stmt);

									// Display the user's data if available, otherwise display '-'
									$usersTotal1[$i]=$usersTotal1[$i]+wagesCalculate($attendance->total_survey);
									/*
									if($attendance->total_survey>0)
									{
									
									$usersTotal1[$i]=$usersTotal1[$i]+1;
									}
									else {
										$usersTotal1[$i]=$usersTotal1[$i]+0;
									}
									*/
									$usersTotal[$i]=$usersTotal[$i]+$attendance->total_survey;
									
									$dayTotal=$dayTotal+$attendance->total_survey;
									if ($attendance->total_survey>0) {
										if($attendance->total_survey>=5)
										{
											$color='#369506';
											$color1='369506';
										}
										else if($attendance->total_survey>=4)
										{
											$color='#56e21c';
											$color1='56e21c';
										}
										else {
											$color='#e2d31c';
											$color1='e2d31c';
										}
										
										echo "<td data-f-bold='true' data-text-center='true' data-vertical-align='middle' data-f-sz='12' data-fill-color='".$color1."' data-f-color='000000' style='text-align:center; vertical-align:middle; background-color:".$color."; font-weight:bold'>" . $attendance->total_survey."</td>";
									} else {
										echo "<td style='text-align:center;' data-text-center='true' data-vertical-align='middle'>-</td>"; // Empty values for no data
									}
									$i++;
								}
							
                                
								echo "<td  data-f-sz='12' data-text-center='true' data-vertical-align='middle' data-fill-color='9fd9ef' data-f-color='000000' style='background-color: #9fd9ef !important; color: #000 !important; text-align:center;  font-weight:bold;'>".$dayTotal."</td>";
							}
							echo "<tr>";
							echo "<td   data-f-sz='12' data-text-center='true' data-vertical-align='middle' data-fill-color='9fd9ef' data-f-color='000000' style='background-color: #9fd9ef !important; color: #000 !important; font-weight:bold; text-align:center;'>Total</td>";
							foreach($usersTotal as $allTot)
							{
								echo "<td   data-f-sz='12' data-text-center='true' data-vertical-align='middle' data-fill-color='9fd9ef' data-f-color='000000' style='background-color: #9fd9ef !important; color: #000 !important; text-align:center;  font-weight:bold;'>".$allTot."</td>";
							}
							echo "<td   data-f-sz='12' data-text-center='true' data-vertical-align='middle' data-fill-color='9fd9ef' data-f-color='000000' style='background-color: #9fd9ef !important; color: #000 !important; text-align:center;  font-weight:bold;'>".array_sum($usersTotal)."</td>";
							echo "</tr>";
							$allTototal=0;
							echo "<tr>";
							echo "<td   data-f-sz='12' data-text-center='true' data-vertical-align='middle' data-fill-color='f47c20' data-f-color='000000' style='background-color: #f47c20 !important; vertical-align:middle; color: #000 !important; font-weight:bold; text-align:center;'>Total Person Days</td>";
							foreach($usersTotal1 as $allTot1)
							{
								$allTototal=$allTototal+$allTot1;
								echo "<td  data-f-sz='12' data-text-center='true' data-vertical-align='middle' data-fill-color='f47c20' data-f-color='000000' style='background-color: #f47c20 !important; vertical-align:middle; color: #000 !important; text-align:center;  font-weight:bold;'>".$allTot1."</td>";
							}
							echo "<td  data-f-sz='12' data-text-center='true' data-vertical-align='middle' data-fill-color='f47c20' data-f-color='000000' style='background-color: #f47c20 !important; color: #000 !important; vertical-align:middle; text-align:center;  font-weight:bold;'>".$allTototal."</td>";
							echo "</tr>";
                            
							echo "</table>"; ?>
						</div>
					</section>
				</div>
			</div>
			<!-- page end-->

						
						
						
						
                        </div>
					</div>  
                    </div>
                    <!-- pest your content here end -->
                </div>
            </div>
        </div>
        <!-- End Page-content -->
        <?php include('include/footer.php'); ?>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->
    <!-- Theme Settings -->
    <?php include('include/script.php'); ?>
	<script src="https://unicef.indevconsultancy.in/mis/js/jquery-3.7.1.js"></script>
<script src="https://unicef.indevconsultancy.in/mis/js/jQuery-UI-1.13.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            scrollY: "400px",       // Set the height of the scrollable area
            scrollX: true,          // Enable horizontal scrolling
            scrollCollapse: true,
            paging: false,          // Disable pagination for scrolling purposes
            fixedHeader: true,      // Enable fixed header
            fixedColumns: {
                leftColumns: 1      // Freeze the first column
            }
        });
    });
</script>
<SCRIPT>
    let button = document.querySelector("#button-excel");

    button.addEventListener("click", (e) => {
        let table = document.querySelector("#example");
        TableToExcel.convert(table, {
        name: "Performance_Report_<?=date('dMY-h:i:s')?>.xlsx", // Set your desired file name here
        sheet: {
            name: "Performance Report" // Set the sheet name here
        }
    });
    });
	
	

</SCRIPT>
</body>
</html>