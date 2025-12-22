<?php include('includes/config.php'); ?>
<?php define("title", "Poshan Tracker | AKALAN"); ?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr Month Select Plugin CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">  
<style>
.info-box i {width:20px!important; }
.highcharts-figure,
.highcharts-data-table table {
    min-width: 320px;
    max-width: 660px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

.highcharts-description {
    margin: 0.3rem 10px;
}
}
.nav-link:hover, .nav-link.active, .nav-link.focused {
    background-color: #e1844d;
    color: #2a2626 !important;
}
</style>

<link href="<?=base_url();?>css/jquery-ui.min.css" rel="stylesheet" />

<?php
$qry1='';
$qry='';
$qry3='';
 if (isset($_REQUEST['search'])) 
		{
			if (isset($_REQUEST['fromDate']) && isset($_REQUEST['toDate'])) {
				$fromDate = '01-'.$_REQUEST['fromDate'];
				$toDate= '01-'.$_REQUEST['toDate'];
				$from = date('Y-m',strtotime($fromDate));
				$from1 = date('F',strtotime($fromDate));
				$fromMonth=date('F-Y',strtotime($fromDate));
				$fromMonthInt=date('Y-m-01',strtotime($fromDate));
				$toMonthInt=date('Y-m-31',strtotime($toDate));
				$toMonth=date('F-Y',strtotime($toDate));
				$to = date('Y-m',strtotime($toDate));
				$to1 = date('M',strtotime($toDate));
				//$qry .= " AND MONTHNAME(dom) IN ($monthsName)";
				$qry .= " AND date(capture_date) BETWEEN '" . $fromMonthInt . "' AND '" . $toMonthInt . "'";
				$qry1 .= " AND t1.capture_date like'".$from."%' and t2.capture_date like'".$to."%'";
			}

		if (isset($_REQUEST['district_code'])) {
			$districtType = array_filter($_REQUEST['district_code']); // Remove empty values
			if (!empty($districtType)) {
				
				 $districtTypeList = "'" . implode("','", $districtType) . "'"; // Convert to integer to prevent SQL injection
				$qry1 .= " AND t1.district IN ($districtTypeList) AND t2.district IN ($districtTypeList)";
				$qry .= " AND district IN ($districtTypeList)";
				$qry3 .= " AND district IN ($districtTypeList)";
			}	
		}
		
 }
else {
				$fromDate = '01-05-2024';
				$toDate= '01-08-2024';
				$from = date('Y-m',strtotime($fromDate));
				$from1 = date('F',strtotime($fromDate));
				$fromMonth=date('F-Y',strtotime($fromDate));
				$fromMonthInt=date('Y-m-01',strtotime($fromDate));
				$toMonthInt=date('Y-m-31',strtotime($toDate));
				$toMonth=date('F-Y',strtotime($toDate));
				$to = date('Y-m',strtotime($toDate));
				$to1 = date('M',strtotime($toDate));
	 $qry= " AND date(capture_date) BETWEEN '2024-05-01' AND '2025-08-31' and district='Purnea'";
	 $qry1= " AND t1.capture_date like'2024-05%' and t2.capture_date like'2024-08%' and t1.district='Purnea' and t2.district='Purnea'";
	 $qry3= " AND district='Purnea'";
	 
 }
?>

<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="fa fa-home"></i>Home</li>
				</ol>
			</div>
		</div>
		<!--------filter start------->
		
			<div class="container-fluid1">
				<form method="GET" style="margin-bottom:5px;">
				<div class="row filter_css clearfix g-1">
					<div class="col-lg-4 col-md-4 col-sm-12">
						<div class="form-group">
							<b>Select District</b>
							
							<select class="form-select select2" id="district_ids" name="district_code[]" multiple >
							<option value="" <?= (empty($_REQUEST['district_code']) || in_array("", $_REQUEST['district_code'])) ? 'selected' : '' ?>> All</option>
							<?php 
							$allDistricts='';
							$selected=''; $kl=1;
							$qryDistrictType = mysqli_query($conn, "SELECT distinct(district) from avreg2bbpszdgvsxxzwbw2");
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
							<b>From Month</b>
							<input id="fromDate" class="form-control" name="fromDate" placeholder="MM-YYYY" value="<?= isset($_REQUEST['fromDate']) ? $_REQUEST['fromDate'] : date('05-2024') ?>" style="border-radius: 4px; padding-bottom: 7px; padding-right: 5px; padding-top: 9px;">
						</div>
					</div>

					<div class="col-lg-2 col-md-3 col-sm-12">
						<div class="form-group">
							<b>To Month</b>
							<input id="toDate" class="form-control" name="toDate" placeholder="MM-YYYY" value="<?= isset($_REQUEST['toDate']) ? $_REQUEST['toDate'] : date('08-2024') ?>" style="border-radius: 4px; padding-bottom: 7px; padding-right: 5px; padding-top: 9px;">
							
						</div>
					</div>
					
					<div class="col-lg-1 col-md-4 mt-4">
						<div class="form-group ">
							<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search">Search</button>
						</div>
					</div>
					<div class="col-lg-1 col-md-4 mt-4">
						<div class="form-group ">
							<a href="poshan_tracker.php" class="btn btn-warning width-md waves-effect waves-light form-control">Reset</a>
						</div>
					</div>
					<div class="col-lg-2 col-md-4 mt-4">
						<div class="form-group ">
							<a href="poshan_bulk_upload.php" class="btn btn-danger width-md waves-effect waves-light form-control">Upload Monitoring Data</a>
						</div>
					</div>
					
					
				</div>
			</form>
			</div>
			<!--------filter end------->
	<div class="container-fluid1">
    	<div class="row">
		   
		            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
    				<div class="info-box threeColor">
    					<i class="fa fa-child"></i>
    					<div class="count">
                                <?php $surveysql = mysqli_query($conn, "select COUNT(child_id) as total_child from child_masters where 1=1 $qry3 ");
								$data = mysqli_fetch_array($surveysql);
								echo $data['total_child']; ?>
    						<div class="title"><span style="color:white;">Total Unique Children</span></div>
    					</div>
    				</div>
    			</div>
    		    	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
    				<div class="info-box twoColor">
    					<i class="fa fa-bar-chart"></i>
    					<div class="count">
    					<?php $surveysql1 = mysqli_query($conn, "select COUNT(id) as total_monitoring from child_monitorings where 1=1 $qry");
								$data1 = mysqli_fetch_array($surveysql1);
								echo $data1['total_monitoring']; ?>
    						<div class="title"><span style="color:white;">Growth Monitoring Data</span></div>
    					</div>
    				</div>
    			</div>
		        
		      
    			    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
    				<div class="info-box oneColor">
    					<i class="fa fa-globe"></i>
    					<div class="count">
						<?php $surveyrejsql1 = mysqli_query($conn, "SELECT COUNT(distinct(district)) AS total_reject FROM child_masters where 1=1 $qry3");
								$data1 = mysqli_fetch_array($surveyrejsql1);
								echo $data1['total_reject'];
								?>
    						<div class="title"><span style="color:white;">Number of Disticts</span></div>
    					</div>
    				</div>
    			</div>
    		        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
    				<div class="info-box fourColor">
    					<i class="fa fa-map-marker fa-2x"></i>
    					<div class="count">
						<?php
								$surveyrejsql = mysqli_query($conn, "SELECT COUNT(distinct(project_name)) AS total_reject FROM child_masters where 1=1 $qry3");
								$data = mysqli_fetch_array($surveyrejsql);
								echo $data['total_reject'];
								?>
    						<div class="title"><span style="color:white;">Total Blocks</span></div>
    					</div>
    				</div>
    			</div>
	    </div>
		<div class="row">
	       <div class="col-md-4">
		        <section class="panel">
					<div class="card">
						<header class="panel-heading">Stunting</header>
						<div class="card-body">
							<div id="stunted_distribution"></div>
						</div>
					</div>
				</section>
		    </div>
			<div class="col-md-4">
		        <section class="panel">
					<div class="card">
						<header class="panel-heading">Wasting</header>
						<div class="card-body">
							<div id="wasted_distribution"></div>
						</div>
					</div>
				</section>
		    </div>
			<div class="col-md-4">
		        <section class="panel">
					<div class="card">
						<header class="panel-heading">Underweight</header>
						<div class="card-body">
							<div id="underweight_distribution"></div>
						</div>
					</div>
				</section>
		    </div>
	   </div>
	    <div class="row">
	       <div class="col-md-12">
		        <section class="panel">
					<div class="card"> 
						<header class="panel-heading">Change in Nutritional Status</header>
						<div class="card-body">
						<ul class="nav nav-tabs" id="myTab" role="tablist">
						  <li class="nav-item" role="presentation">
							<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true" style="color:black!important;">Stunting</button>
						  </li>
						  <li class="nav-item" role="presentation">
							<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false" style="color:black!important;" >Wasting</button>
						  </li>
						  <li class="nav-item" role="presentation">
							<button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false" style="color:black!important;">Underweight</button>
						  </li>
						</ul>
						<div class="tab-content" id="myTabContent">
						  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0"><div id="growth_monitoring" style="width: 100%; height: 700px;"></div></div>
						  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0"><div id="growth_monitoring_wasted" style="width: 100%; height: 700px;"></div></div>
						  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0"><div id="growth_monitoring_underweight" style="width: 100%; height: 700px;"></div></div>
						 
						</div>
							
							
							
							
						</div>
					</div>
				</section>
		    </div>
	   </div>     
	    <div class="row">
	        <div class="col-md-12">
		        <section class="panel">
					<div class="card">
						<header class="panel-heading">Data Quality</header>
						<div class="card-body">
						<div class="row">
	       <div class="col-md-6">
		        <section class="panel">
					<div class="card">
						
						<div class="card-body">
							<div id="digit_heaping_hight" style="height: 900px; min-width: 320px; max-width: 600px; margin: 0 auto"></div>
						</div>
					</div>
				</section>
		    </div>
			<div class="col-md-6">
		        <section class="panel">
					<div class="card">
						
						<div class="card-body">
							<div id="digit_heaping_weight" style="height: 900px; min-width: 320px; max-width: 600px; margin: 0 auto"></div>
						</div>
					</div>
				</section>
		    </div>
	   </div>
						</div>
					</div>
				</section>
		    </div>
	    </div>
	  
	</div>
</div>
</section>
</section>
<!--Dependancy end code--->
<?php include_once('includes/footer.php'); ?>
   <script src="https://code.highcharts.com/highcharts.js"></script>
	<!--<script src="https://code.highcharts.com/stock/highstock.js"></script>-->
    <script src="https://code.highcharts.com/modules/sankey.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
	<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/stock/modules/accessibility.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Flatpickr Month Select Plugin -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Flatpickr for month-year selection
        flatpickr("#fromDate", {
            dateFormat: "m-Y", // Format: MM-YYYY
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "m-Y", // Format for input value
                    altFormat: "F Y", // Alternative display format
                    theme: "light"
                })
            ]
        });
	});
	document.addEventListener('DOMContentLoaded', function () {
        // Initialize Flatpickr for month-year selection
        flatpickr("#toDate", {
            dateFormat: "m-Y", // Format: MM-YYYY
            plugins: [
                new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "m-Y", // Format for input value
                    altFormat: "F Y", // Alternative display format
                    theme: "light"
                })
            ]
        });
	});
</script>
<script>
	$("#survey_name_id").on("input", function() {
		if ($("#survey_name_id").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', true);
		}
	});
	$("#from_datepicker").on("click", function() {
		if ($("#from_datepicker").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', false);
		}
	});
	$("#to_datepicker").on("click", function() {
		if ($("#to_datepicker").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', false);
		}
	});
</script>

<?php 
$statusArray=['NA','Normal','Moderate','Severe'];
$colorArray=['NA','#2ECC71','#F1C40F','#E74C3C'];
//echo "SELECT t1.stunted AS at_start,t2.stunted AS at_end,CONCAT(t1.stunted, '-', t2.stunted) AS Stunting_status,COUNT(*) AS Child_Count FROM child_monitorings t1 JOIN child_monitorings t2 ON t1.child_id = t2.child_id WHERE 1=1 $qry1 GROUP BY Stunting_status";
$sqlGrowth=mysqli_query($conn,"SELECT t1.stunted AS at_start,t2.stunted AS at_end,CONCAT(t1.stunted, '-', t2.stunted) AS Stunting_status,COUNT(*) AS Child_Count FROM child_monitorings t1 JOIN child_monitorings t2 ON t1.child_id = t2.child_id WHERE 1=1 $qry1 GROUP BY Stunting_status");
$in=1;
$dataGrowthArray='';
$dataStunt_same=0;
$dataStunt_worst=0;
$dataStunt_better=0;
 while($dataGrowth=mysqli_fetch_object($sqlGrowth))
{
	if($dataGrowth->at_start==$dataGrowth->at_end)
	{
		$dataStunt_same=$dataStunt_same+$dataGrowth->Child_Count;
	}
	if($dataGrowth->at_start<$dataGrowth->at_end)
	{
		$dataStunt_worst=$dataStunt_worst+$dataGrowth->Child_Count;
	}
	if($dataGrowth->at_start>$dataGrowth->at_end)
	{
		$dataStunt_better=$dataStunt_better+$dataGrowth->Child_Count;
	}
	if($in>1)
	{
	$dataGrowthArray.=",";
	}
	 
	 $dataGrowthArray.="['".$statusArray[$dataGrowth->at_start]." (".$from1.")', '".$statusArray[$dataGrowth->at_end]." (".$to1.")',".$dataGrowth->Child_Count.",'".$colorArray[$dataGrowth->at_start]."']";
	 $in++;
 }
 // For Wasted 
 
 $statusArray1=['NA','Normal','Moderate Wasting','Severe Wasting','Overweight','Obese'];
$sqlGrowth1=mysqli_query($conn,"SELECT t1.wasted AS at_start,t2.wasted AS at_end,CONCAT(t1.wasted, '-', t2.wasted) AS Stunting_status,COUNT(*) AS Child_Count FROM child_monitorings t1 JOIN child_monitorings t2 ON t1.child_id = t2.child_id WHERE 1=1 $qry1 and t1.wasted<6 and t2.wasted<6 GROUP BY Stunting_status"); 
$in1=1;
$dataGrowthArray1='';
$dataStunt_same1=0;
$dataStunt_worst1=0;
$dataStunt_better1=0;
 while($dataGrowth1=mysqli_fetch_object($sqlGrowth1))
{
	$atStart=$dataGrowth1->at_start;
	$atEnd=$dataGrowth1->at_end;
	if($dataGrowth1->at_start>3)
	{
		$atStart=1;
	}
	if($dataGrowth1->at_end>3)
	{
		$atEnd=1;
	}
	if($atStart==$atEnd)
	{
		$dataStunt_same1=$dataStunt_same1+$dataGrowth1->Child_Count;
	}
	if($atStart<$atEnd)
	{
		$dataStunt_worst1=$dataStunt_worst1+$dataGrowth1->Child_Count;
	}
	if($atStart>$atEnd)
	{
		$dataStunt_better1=$dataStunt_better1+$dataGrowth1->Child_Count;
	}
	if($in1>1)
	{
	$dataGrowthArray1.=",";
	}
	 
	 $dataGrowthArray1.="['".$statusArray1[$dataGrowth1->at_start]." (".$from1.")', '".$statusArray1[$dataGrowth1->at_end]." (".$to1.")',".$dataGrowth1->Child_Count."]";
	 $in1++;
 }
 //////////////////////// For Under-Weight ////////////////////
 
 
 $statusArray2=['NA','Normal','Moderate','Severe'];
 $colorArray2=['NA','#2ECC71','#F1C40F','#E74C3C'];
$sqlGrowth2=mysqli_query($conn,"SELECT t1.stunted AS at_start,t2.stunted AS at_end,CONCAT(t1.stunted, '-', t2.stunted) AS Stunting_status,COUNT(*) AS Child_Count FROM child_monitorings t1 JOIN child_monitorings t2 ON t1.child_id = t2.child_id WHERE 1=1 $qry1 GROUP BY Stunting_status");
$in2=1;
$dataGrowthArray2='';
$dataStunt_same2=0;
$dataStunt_worst2=0;
$dataStunt_better2=0;
 while($dataGrowth2=mysqli_fetch_object($sqlGrowth2))
{
	if($dataGrowth2->at_start==$dataGrowth2->at_end)
	{
		$dataStunt_same2=$dataStunt_same2+$dataGrowth2->Child_Count;
	}
	if($dataGrowth2->at_start<$dataGrowth2->at_end)
	{
		$dataStunt_worst2=$dataStunt_worst2+$dataGrowth2->Child_Count;
	}
	if($dataGrowth2->at_start>$dataGrowth2->at_end)
	{
		$dataStunt_better2=$dataStunt_better2+$dataGrowth2->Child_Count;
	}
	if($in2>1)
	{
	$dataGrowthArray2.=",";
	}
	 
	 $dataGrowthArray2.="['".$statusArray2[$dataGrowth2->at_start]." (".$from1.")', '".$statusArray2[$dataGrowth2->at_end]." (".$to1.")',".$dataGrowth2->Child_Count."]";
	 $in2++;
 }
 
 
 
 
 
?>


<script>
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('growth_monitoring', {
                chart: {
                    type: 'sankey'
                },
                title: {
                    text: "Stunting Status for <?=$fromMonth?> to <?=$toMonth?>"
                },
                series: [{
                    keys: ['from', 'to', 'weight', 'color'],
                    data: [<?=$dataGrowthArray?>
	  ],
                    name: "Flow from <?=$fromMonth?> to <?=$toMonth?>"
                }]
            });
			
			 Highcharts.chart('growth_monitoring_wasted', {
                chart: {
                    type: 'sankey'
                },
                title: {
                    text: "Wasting Status for <?=$fromMonth?> to <?=$toMonth?>"
                },
                series: [{
                    keys: ['from', 'to', 'weight'],
                    data: [<?=$dataGrowthArray1?>
	  ],
                    name: "Flow from <?=$fromMonth?> to <?=$toMonth?>"
                }]
            });
			
			Highcharts.chart('growth_monitoring_underweight', {
                chart: {
                    type: 'sankey'
                },
                title: {
                    text: "Underweight Status for <?=$fromMonth?> to <?=$toMonth?>"
                },
                series: [{
                    keys: ['from', 'to', 'weight'],
                    data: [<?=$dataGrowthArray2?>
	  ],
                    name: "Flow from <?=$fromMonth?> to <?=$toMonth?>"
                }]
            });
			
			
			
        });
		
		
		
		
    </script>



<script>
	$(document).ready(function() {
		$(".tgltab").click(function() {
			//console.log($(this).parent().parent());
			if ($(this).parent().parent().hasClass('active')) {
				$(this).parent().parent().removeClass('active')
			} else {
				$(this).parent().parent().addClass('active')
			}

			$(this).parent().parent().find('.accord-body').slideToggle(500);

		});

		$('.dash-body ul li.hasChild:not(.noAct)').each(function() {
			$(this).addClass('active');
			$(this).find('.accord-body').css('display', 'block');
		})
		
	});
</script>

	<script>
	Highcharts.chart('stunted_distribution', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
	credits: {
        enabled: false
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Change in Status',
        colorByPoint: true,
        data: [{
            name: 'Same',
            y: <?=$dataStunt_same?>,
            sliced: true,
            selected: true,
			color: 'orange'
        },  {
            name: 'Worse',
            y: <?=$dataStunt_worst?>,
			color: 'red'
        },  {
            name: 'Better',
            y: <?=$dataStunt_better?>,
			color: 'green'
        }]
    }]
});
	</script>
		<script>
	Highcharts.chart('wasted_distribution', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
	credits: {
        enabled: false
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Change in Status',
        colorByPoint: true,
        data: [{
            name: 'Same',
            y: <?=$dataStunt_same1?>,
            sliced: true,
            selected: true,
			color: 'orange'
        },  {
            name: 'Worse',
            y: <?=$dataStunt_worst1?>,
			color: 'red'
        },  {
            name: 'Better',
            y: <?=$dataStunt_better1?>,
			color: 'green'
        }]
    }]
});
	</script>
	<script>
	Highcharts.chart('underweight_distribution', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    credits: {
        enabled: false
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Change in Status',
        colorByPoint: true,
        data: [{
            name: 'Same',
            y: <?=$dataStunt_same2?>,
            sliced: true,
            selected: true,
			color: 'orange'
        },  {
            name: 'Worse',
            y: <?=$dataStunt_worst2?>,
			color: 'red'
        },  {
            name: 'Better',
            y: <?=$dataStunt_better2?>,
			color: 'green'
        }]
    }]
});

//////////////////
<?php 
$heightArray='';

$sqldigitheaping_height=mysqli_query($conn,"SELECT SUBSTRING_INDEX(height, '.', -1) AS decimal_part, COUNT(*) AS count, ROUND((COUNT(*) / (SELECT COUNT(*) FROM child_monitorings WHERE height LIKE '%.%')) * 100, 2) AS percentage FROM child_monitorings WHERE height LIKE '%.%' $qry GROUP BY decimal_part ORDER BY decimal_part");
while($datadigitheaping_height=mysqli_fetch_object($sqldigitheaping_height))
{
	$heightArray.='[".'.$datadigitheaping_height->decimal_part.'", '.$datadigitheaping_height->count.'],';
}?>

Highcharts.chart('digit_heaping_hight', {
    chart: {
        type: 'bar',
        marginLeft: 50
    },
    title: {
        text: 'Digit Heaping (Height)'
    },
    xAxis: {
        type: 'category',
        title: {
            text: null
        },
        min: 0,
        max: 98,
        scrollbar: {
            enabled: true
        },
        tickLength: 0
    },
    yAxis: {
        min: 0,
        max: 1200,
        title: {
            text: 'Total Records',
            align: 'high'
        }
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    legend: {
        enabled: false
    },
    credits: {
        enabled: false
    },
	tooltip: {
        formatter: function () {
            const total = this.series.data.reduce((sum, point) => sum + point.y, 0);
            const percentage = ((this.y / total) * 100).toFixed(2);
            return ` For Digit: <b>${this.key}</b><br>
                    Records Count: <b>${this.y}</b><br>
                    Percentage: <b>${percentage}%</b>`;
        }
    },
    series: [{
        name: 'Record Counts',
        data: [<?=$heightArray?>]
    }]
});
<?php 
$weightArray='';
//echo "SELECT SUBSTRING_INDEX(weight, '.', -1) AS decimal_part, COUNT(*) AS count, ROUND((COUNT(*) / (SELECT COUNT(*) FROM child_monitorings WHERE weight LIKE '%.%')) * 100, 2) AS percentage FROM child_monitorings WHERE weight LIKE '%.%' $qry GROUP BY decimal_part ORDER BY decimal_part";
$sqldigitheaping_weight=mysqli_query($conn,"SELECT SUBSTRING_INDEX(weight, '.', -1) AS decimal_part, COUNT(*) AS count, ROUND((COUNT(*) / (SELECT COUNT(*) FROM child_monitorings WHERE weight LIKE '%.%')) * 100, 2) AS percentage FROM child_monitorings WHERE weight LIKE '%.%' $qry GROUP BY decimal_part ORDER BY decimal_part");
while($datadigitheaping_weight=mysqli_fetch_object($sqldigitheaping_weight))
{
	$weightArray.='[".'.$datadigitheaping_weight->decimal_part.'", '.$datadigitheaping_weight->count.'],';
}
?>

Highcharts.chart('digit_heaping_weight', {
    chart: {
        type: 'bar',
        marginLeft: 50
    },
    title: {
        text: 'Digit Heaping (Weight)'
    },
    xAxis: {
        type: 'category',
        title: {
            text: null
        },
        min: 0,
        max: 98,
        scrollbar: {
            enabled: true
        },
        tickLength: 0
    },
    yAxis: {
        min: 0,
        max: 1200,
        title: {
            text: 'Total Records',
            align: 'high'
        }
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    legend: {
        enabled: false
    },
    credits: {
        enabled: false
    },
	tooltip: {
        formatter: function () {
            const total = this.series.data.reduce((sum, point) => sum + point.y, 0);
            const percentage = ((this.y / total) * 100).toFixed(2);
            return ` For Digit: <b>${this.key}</b><br>
                    Records Count: <b>${this.y}</b><br>
                    Percentage: <b>${percentage}%</b>`;
        }
    },
    series: [{
        name: 'Record Counts',
        data: [<?=$weightArray?>]
    }]
});

	</script>
	</body>

	</html>




