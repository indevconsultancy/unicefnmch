<?php include_once('includes/config.php'); ?>
<?php define("title", "Review Data List | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('mycrypt.php'); ?>

<?php
$month = date('m'); // October
$year = date('Y');
$mindate='2024-01';
$maxdate=$year.'-'.$month;

// Get the number of days in the selected month
$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$qry = "";

if (isset($_REQUEST['search'])) {
	
	if (isset($_REQUEST['reporting_period']) && $_REQUEST['reporting_period'] != '') {
		$month = date('m',strtotime($_REQUEST['reporting_period'])); // October
		$year = date('Y',strtotime($_REQUEST['reporting_period']));
		$mindate='2024-01';
		$maxdate=$year.'-'.$month;
		$qry .= " AND date(visit_date) like'".$_REQUEST['reporting_period']."%'";
	}
	if (isset($_REQUEST['formID'])) {
		$ssidss='';
		foreach($_REQUEST['formID'] as $formids)
		{
			if($formids!='')
			{
				$ssidss .=$formids.',';
			}
			
			
		}
		$ssidss= rtrim($ssidss,",");
		if($ssidss!='')
				{
			$qry .= " AND survey_name_id in(".$ssidss.")";
				}
	}
	if (isset($_REQUEST['uType']) && $_REQUEST['uType'] != '') {
		$qry .= " AND user_types='" . $_REQUEST['uType'] . "'";
	}
}
else{
	$qry = "and user_types like'%aiims%' ";
}
//echo $qry;
?>

<style>
	.myTable {
	  max-height: 400px; /* Adjust the height as needed */
	  overflow-y: auto;  /* Enable vertical scrolling */
	  display: block;
	}
	.info-box i {
		display: block;
		height: 20px;
		font-size: 30px;
		line-height: 20px;
		width: 70px;
		float: left;
		text-align: center;
		margin-right: 80px;
		padding-right: 20px;
		color: rgba(255, 255, 255, 0.75);
	}

	.info-box {
		min-height: 5px;
		margin-bottom: 20px;
	}

	.info-box .count {
		margin-top: 0px;
		font-size: 20px;
		font-weight: 1000;
	}

	.panel-heading {
		background: #394a59;
		color: white;
		font-weight: unset;
	}

	.ui-datepicker td a {
		text-align: center !important;
	}

	element.style {}

	.ui-state-default,
	.ui-widget-content .ui-state-default,
	.ui-widget-header .ui-state-default {

		/* border: 3px solid #22bacf !important; */
		box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
		border-radius: 0px !important;
		-webkit-border-radius: 0px !important;
		*
	}

	/* .ui-state-active,
	.ui-widget-content .ui-state-active,
	.ui-widget-header .ui-state-active,
	a.ui-button:active,
	.ui-button:active,
	.ui-button.ui-state-active:hover {

		background: #449A97 !important;
		font-weight: normal;
		color: #fff !important;
	} */

	#ui-datepicker-div {
		top: 174px !important;
	}

	#main-content .wrapper .row {
		margin-bottom: 0px;
	}

	.panel {
		margin-bottom: 20px;
	}

	.panel .panel-heading {
		margin-top: 10px;
	}

	.b5row {
		display: flex;
		gap: 10px;
		align-items: center;
		justify-content: center;
		margin-bottom: 19px !important;
	}

	.b5row .col {
		flex: 1 0 0%;
	}
	
</style>

<link href="<?= base_url(); ?>css/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
 
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Report</li>
						<li class="breadcrumb-item active"><i class="fa fa-calandar"></i>Attendance  Report</li>
						
					</ol>
				</nav>
			</div>
		</div>
		<div class="container-fluid1">
			<form method="GET">
				<div class="row filter_css clearfix g-1">
					<div class="col-lg-4 col-md-4 col-sm-12">
						<div class="form-group">
							<b>Select Form</b>
						<!--<select class="form-select " id="formID" name="formID">-->
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
					<div class="col-lg-3 col-md-3 col-sm-12">
						<div class="form-group">
						    <b> Select Month </b>
							<input class="form-control" type="month" id="start" name="reporting_period" min="<?=$mindate?>" value="<?=$maxdate?>" />
						</div>
					</div>
					
					<div class="col-lg-3 col-md-3 col-sm-12">
						<div class="form-group"> 
						<b>Users Type</b>
						<select class="form-select" id="uType" name="uType">
						<?php $qryUserType = mysqli_query($conn,"SELECT DISTINCT user_types FROM survey_data_monitoring ORDER BY user_types ASC");
                            while($dataUserType=mysqli_fetch_object($qryUserType)) {
								$selected='';
								if($dataUserType->user_types==$_REQUEST['uType'])
								{
									$selected='selected';
								}
								else if(stripos('aiims', $dataUserType->user_types) !== FALSE)
								{
									$selected='selected';
								}
								?>
							<option value="<?=$dataUserType->user_types?>" <?=$selected?> > <?=$dataUserType->user_types?> </option>
							<?php } ?>
							</select>
						
						</div>
					</div>
					<div class="col-lg-1 col-md-4 mt-4">
								<div class="form-group " >
									<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search" >Search</button>
								</div>
							</div>
					<div class="col-lg-1 col-md-4 mt-4">
								<div class="form-group ">
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
							//echo "SELECT DISTINCT monitor_name FROM survey_data_monitoring where 1=1 and monitor_name not in('Dr.Mohit Bhardwaj','Dr Deepika Agrawal','Jyoti kumari','GAGAN GAUTAM') $qry ORDER BY monitor_name ASC";
							$usersStmt = mysqli_query($conn,"SELECT DISTINCT monitor_name FROM survey_data_monitoring where 1=1 and monitor_name not in('Dr.Mohit Bhardwaj','Dr Deepika Agrawal','GAGAN GAUTAM') $qry ORDER BY monitor_name ASC");
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
								  <tr style='background-color:#ccc!important' data-text-center='true' data-vertical-align='middle'> <td data-text-center='true' data-vertical-align='middle' style='font-size:16px; font-weight:700; text-align:center; vertical-align: middle; height:50px; padding-top:10px;' colspan='".$colspan."'>Attandance Report for Month:  ".date('M-Y',strtotime($calculationdate1))."</td></tr>";
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
							for ($day =1;  $day<=$numDays; $day++) { 
								$dayTotal=0;
								$dates = sprintf("%04d-%02d-%02d", $year, $month, $day); // Format date as YYYY-MM-DD
								
								echo "<tr>";
								echo "<td width='10%'>" . date('d-M-Y',strtotime($dates)) . "</td>"; // Display the current date
								$i=0;
								
								foreach($userslist as $usersl)
									{
										//echo "SELECT count(*) as total_survey FROM survey_data_monitoring WHERE monitor_name = '".$usersl."' AND date(visit_date) ='".$dates."' $qry ";
									$stmt = mysqli_query($conn,"SELECT count(*) as total_survey FROM survey_data_monitoring WHERE monitor_name = '".$usersl."' AND date(visit_date) ='".$dates."' $qry ");
									$attendance = mysqli_fetch_object($stmt);

									// Display the user's data if available, otherwise display '-'
									if($attendance->total_survey>0)
									{
									$usersTotal1[$i]=$usersTotal1[$i]+1;
									}
									else {
										$usersTotal1[$i]=$usersTotal1[$i]+0;
									}
									
									$usersTotal[$i]=$usersTotal[$i]+$attendance->total_survey;
									
									$dayTotal=$dayTotal+$attendance->total_survey;
									if ($attendance->total_survey>0) {
										if($attendance->total_survey>=5)
										{
											$color='#369506';
											$color1='369506';
										}
										else if($attendance->total_survey>=2)
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
</section>
</section>
<?php include_once('includes/footer.php'); ?>
<script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
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
        name: "Performance_Report_<?=date('M-Y',strtotime($calculationdate1))?>.xlsx", // Set your desired file name here
        sheet: {
            name: "Performance Report" // Set the sheet name here
        }
    });
    });
	
	

</SCRIPT>