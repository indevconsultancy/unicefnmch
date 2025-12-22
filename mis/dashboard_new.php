<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php define("title", "Home | AKALAN"); ?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>

<?php
$user_id = $_SESSION['user_id'];
$client_id = $_SESSION['client_id'];
$client_qry = '';
$client_qry1 = '';
$userqry1 = '';


if ($_SESSION['functional_role_id'] != 3 && $_SESSION['functional_role_id'] != 1) {
	$userqry1 = " and assign_survey.user_id='" . $user_id . "' ";
	$client_qry = " and survey.id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='" . $user_id . "' and status=0) ";
	$client_qry1 = " and survey_data_monitoring.survey_name_id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='" . $user_id . "' and status=0) ";
}
if($_SESSION['functional_role_id']==9)
{
	//$client_qry.=" and survey_data_monitoring.user_id='".$user_id."'";
	$client_qry1.=" and survey_data_monitoring.user_id='".$user_id."'";
}
?>

<?php
$qry = '';
$qry1 = '';
if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['survey_name_id']) && $_REQUEST['survey_name_id'] != '') {
		$qry .= " AND survey_data_monitoring.survey_name_id='" . $_REQUEST['survey_name_id'] . "'";
		$qry1 .= " AND assign_survey.survey_id='" . $_REQUEST['survey_name_id'] . "'";
	}
	if (isset($_REQUEST['from_date']) && isset($_REQUEST['to_date'])) {
		$d1 = date('Y-m-d', strtotime($_REQUEST['from_date']));
		$d2 = date('Y-m-d', strtotime($_REQUEST['to_date']));

		// Initialize $qry if not already initialized
		if (!isset($qry)) {
			$qry = '';
		}

		if (!empty($_REQUEST['from_date']) && !empty($_REQUEST['to_date'])) {
			if (!empty($qry)) {
				$qry .= ' AND ';
			}
			$qry .= "and DATE(survey_data_monitoring.created_on) BETWEEN '$d1' AND '$d2'";
		} else {
			if (!empty($_REQUEST['from_date'])) {
				if (!empty($qry)) {
					$qry .= ' AND ';
				}
				$qry .= "and DATE(survey_data_monitoring.created_on) >= '$d1'";
			}
			if (!empty($_REQUEST['to_date'])) {
				if (!empty($qry)) {
					$qry .= ' AND ';
				}
				$qry .= "and DATE(survey_data_monitoring.created_on) <= '$d2'";
			}
		}
	}
}
?>
<link href="<?=base_url();?>css/jquery-ui.min.css" rel="stylesheet" />

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
		<?php if ($_SESSION['functional_role_id'] != 3 && $_SESSION['functional_role_id'] != 1) { ?>
			<div class="container-fluid1">
				<form class="form-inline" method="get" role="form">
					<div class="row filter_css clearfix">
						<div class="form-group col-md-4" style="margin-bottom: 1rem!important;">
							<select class="form-control" name="survey_name_id" id="survey_name_id">
								<option value="">Select Form</option>
								<?php
								$sqlservey = "SELECT id,survey_name,client_id,created_at FROM survey where del_action='N' $client_qry order by survey.id DESC";
								$selectservey = mysqli_query($conn, $sqlservey);
								while ($surveydata = mysqli_fetch_array($selectservey)) { ?>
									<option value="<?php echo $surveydata['id']; ?>" <?php if ($surveydata['id'] == $_REQUEST['survey_name_id']) {
																							echo "selected";
																						} ?>><?php echo "" . $surveydata['survey_name'] . "(" . date("j-F-Y", strtotime($surveydata['created_at'])) . ")"; ?></option>
								<?php }	?>
							</select>
						</div>
						<div class="col-md-2" style="margin-bottom: 1rem!important;">
							<div class="form-group">
								<input type="text" name="from_date" id="from_datepicker" value="<?= @$_REQUEST['from_date'] ?>" placeholder="From Date" class="form-control">
							</div>
						</div>
						<div class="col-md-2" style="margin-bottom: 1rem!important;">
							<div class="form-group">
								<input type="text" name="to_date" id="to_datepicker" placeholder="To Date" value="<?= @$_REQUEST['to_date'] ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-md-2" style="margin-bottom: 1rem!important;">
							<button type="submit" class="btn btn-secondary width-md waves-effect waves-light form-control" id="btnsearch" name="search">Search</button>
						</div>
						<div class="form-group col-md-2" style="margin-bottom: 1rem!important;">
							<button type="submit" class="btn btn-secondary width-md waves-effect waves-light form-control" name="clear">Clear Filter</button>
						</div>
					</div>
				</form>
			</div>
			<!--------filter end------->
			<div class="container-fluid1">
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box oneColor">
							<i class="icon_documents_alt"></i>
							<div class="count">

								<?php
								//echo "select COUNT(DISTINCT(assign_survey.survey_id)) as total_assign from assign_survey inner join survey on assign_survey.survey_id=survey.id where assign_survey.status='0' $userqry1 $qry1";
								$assignForm = mysqli_query($conn, "select COUNT(DISTINCT(assign_survey.survey_id)) as total_assign from assign_survey inner join survey on assign_survey.survey_id=survey.id where assign_survey.status='0' $userqry1 $qry1");
								$data = mysqli_fetch_array($assignForm);
								echo $total_assign = $data['total_assign'];
								?>
								<div class="title"><span style="color:white;">Number of assigned form </span></div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box twoColor">
							<i class="fa fa-bar-chart"></i>
							<div class="count">
								<?php
								//echo "SELECT COUNT(survey_data_monitoring_id) AS total_monitoring FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id left join users on users.user_id=survey_data_monitoring.user_id where users.del_action='N'  $client_qry1 $qry";
								$surveysql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_monitoring FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id left join users on users.user_id=survey_data_monitoring.user_id where users.del_action='N'  $client_qry1 $qry");
								$data = mysqli_fetch_array($surveysql);
								echo $data['total_monitoring'];
								?>
								<div class="title"><span style="color:white;">Number of entries collected</span></div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box threeColor">
							<i class="fa fa-check-circle"></i>
							<div class="count">
								<?php
								//echo "SELECT COUNT(survey_data_monitoring_id) AS total_accept FROM `survey_data_monitoring` where survey_status='1' $client_qry1 $qry";
								$surveyaccsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_accept FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id where survey.status in (1,6) $client_qry1 $qry");
								$data = mysqli_fetch_array($surveyaccsql);
								echo $data['total_accept'];
								?>
								<div class="title"><span style="color:white;">Number of entries verified</span></div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box fourColor">
							<i class="fa fa-times-circle fa-2x"></i>
							<div class="count">
								<?php
								$surveyrejsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) AS total_reject FROM `survey_data_monitoring` left join survey on survey.id=survey_data_monitoring.survey_name_id where  survey_status='7' $client_qry1 $qry");
								$data = mysqli_fetch_array($surveyrejsql);
								echo $data['total_reject'];
								?>
								<div class="title"><span style="color:white;">Number of entries rejected</span></div>
							</div>
						</div>
					</div>
				</div>
				<section class="main-content">
					<div class="row">
						<!--<div class="col-md-6">
					<section class="panel">
						<div class="card">
						  <header class="panel-heading">Months wise data collected</header>
							<div class="card-body">
								<div id="month_wise"></div>
							</div>
						</div>
					</section>	
				</div>-->
				
						<div class="col-md-6">
							<section class="panel">
								<div class="card">
									<header class="panel-heading">Form wise data collected</header>
									<div class="card-body">
										<div id="form_wise_data"></div>
									</div>
								</div>
							</section>
						</div>
						<div class="col-md-6">
							<section class="panel">
								<div class="card">
									<header class="panel-heading">Day wise data collected(Current Month)</header>
									<div class="card-body">
										<div id="day_wise_data"></div>
									</div>
								</div>
							</section>
						</div>
						
					</div>
					<div class="row">
						<div class="col-md-12">
							<section class="panel">
								<div class="card">
									<header class="panel-heading">Geographical Coverage</header>
									<div class="card-body">
										<div id="mapCanvas" style="height:400px;width:100%;"></div>
									</div>
								</div>
						</div>
				</section>
			</div>
			</div>
	</section>
<?php } else { 		

			$dasboard_tab = mysqli_query($conn, "SELECT  count(survey_data_monitoring_id) as total_data, count(DISTINCT(survey_name_id)) as total_form,count(DISTINCT(monitor_name)) as total_user, count(DISTINCT(user_types)) as total_org FROM survey_data_monitoring");
			$dasboard_data = mysqli_fetch_array($dasboard_tab);
						
			
			?>

	<div class="container-fluid1">
    	<div class="row">
		    <div class="col-md-6">
		        <div class="row">
		            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    				<div class="info-box threeColor">
    					<i class="icon_documents_alt"></i>
    					<div class="count">
                                <?=$dasboard_data['total_data']?>
    						<div class="title"><span style="color:white;">Total Data Collection</span></div>
    					</div>
    				</div>
    			</div>
    		    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    				<div class="info-box twoColor">
    					<i class="fa fa-bar-chart"></i>
    					<div class="count">
    					<?=$dasboard_data['total_form'] ?>
    						<div class="title"><span style="color:white;">Total Forms</span></div>
    					</div>
    				</div>
    			</div>
		        </div>
		       <div class="row">
    			    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    				<div class="info-box oneColor">
    					<i class="fa fa-check-circle"></i>
    					<div class="count">
						 <?=getcountrow($conn, 'consultants', 'id', 'organization', 'aiims')?>
    						<div class="title"><span style="color:white;">Data Collector</span></div>
    					</div>
    				</div>
    			</div>
    		        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    				<div class="info-box fourColor">
    					<i class="fa fa-times-circle fa-2x"></i>
    					<div class="count">
						<?=$dasboard_data['total_org'] ?>
    						<div class="title"><span style="color:white;">Total Organization</span></div>
    					</div>
    				</div>
    			</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				 <a href="https://unicef.indevconsultancy.in/Iron-Sucrose/Dashboard/" target="_blank">
					<div class="card bg-primary text-white">	
									
					<div class="card-body"><span style="font-size:18px">Iron Sucrose Dashboard <i class="fa fa-external-link"> </i></span></div>
						
					</div>
				</a>
				 </div>
				 <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				 <a href="https://unicef.indevconsultancy.in/sncu/Dashboard/dashboard.php" target="_blank">
					<div class="card bg-warning text-white">	
									
					<div class="card-body"><span style="font-size:18px">SNCU Dashboard <i class="fa fa-external-link"> </i></span></div>
						
					</div>
				</a>
				 </div>
    		    </div>
		    </div>
		    
		    <div class="col-md-6">
		        <section class="panel">
					<div class="card">
						<header class="panel-heading">Geographical Coverage</header>
						<div class="card-body">
						<p id="show_block" style="display:none;"><span class="cursor-pointer" onclick="stateMap('state-10')">Bihar</span>/<span id="block-name"></span></p>
							<div id="map" style="height:315px;width:100%;"></div>
						</div>
					</div>
				</section>
		    </div>
	    </div>
		<div class="row mb-4" style="margin-top:-15px;">
		<span style="font-size:17px; font-weight:bold;"> Programme Partner</span>
		<div class=" col-md-2">
			 <div class="card bg-secondary text-white text-center">
				<div class="card-body" >AIIMS</div>
			 </div>
		 </div>
		 <div class=" col-md-2">
			 <div class="card bg-secondary text-white text-center">
				<div class="card-body">NMCH</div>
			 </div>
		 </div>
		 <div class=" col-md-2">
			 <div class="card bg-secondary text-white text-center">
				<div class="card-body">PMCH</div>
			 </div>
		 </div>
		 <div class=" col-md-2">
			 <div class="card bg-secondary text-white text-center">
				<div class="card-body">PIRAMAL</div>
			 </div>
		 </div>
		 <div class=" col-md-2">
			 <div class="card bg-secondary text-white text-center">
				<div class="card-body">PUSA</div>
			 </div>
		 </div>
		 <div class=" col-md-2">
			 <div class="card bg-secondary text-white text-center">
				<div class="card-body">ICDS</div>
			 </div>
		 </div>
		</div>
	    <div class="row">
	       <div class="col-md-12">
		        <section class="panel">
					<div class="card">
						<header class="panel-heading">Form wise Data Collection</header>
						<div class="card-body">
							<div id="form_wise_datacollection"></div>
						</div>
					</div>
				</section>
		    </div>
	   </div>     
	    <div class="row">
	        <div class="col-md-12">
		        <section class="panel">
					<div class="card">
						<header class="panel-heading">Day wise Data Collection</header>
						<div class="card-body">
							<div id="day_wise_line"></div>
						</div>
					</div>
				</section>
		    </div>
	    </div>
	  
	</div>

<?php } ?>
</div>
</section>
</section>
<!--Dependancy end code--->
<?php include_once('includes/footer.php'); ?>

<script src="js/highmaps.js"></script>
<script src="js/exporting.js"></script>
<script src="js/export-data.js"></script>
<script src="js/accessibility.js"></script>
<script src="js/highchartsv11.js"></script>
<script src="js/datav11.js"></script>
<script src="js/drilldownv11.js"></script>
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
/////////////district data//////////////////////
	$dist_data='';
	$district_qry = mysqli_query($conn, "SELECT district_id,COUNT(survey_data_monitoring_id) as total_form FROM survey_data_monitoring GROUP BY district_id");
	while($district_data = mysqli_fetch_array($district_qry)){
		$dist_data.="['".$district_data['district_id']."', ".$district_data['total_form']."],";

	}

	//////////////block data//////////////////////////

	$block_datas='';
	$block_qry = mysqli_query($conn, "SELECT s.block_id,COUNT(s.survey_data_monitoring_id) AS total_form,b.map_code FROM survey_data_monitoring s JOIN blocks b ON b.block_code = s.block_id GROUP BY s.block_id");
	while($block_data = mysqli_fetch_array($block_qry)){
		$block_datas.="['".$block_data['map_code']."', ".$block_data['total_form']."],";

	}
	//echo $block_datas;
	
	
	?>
<script>

	
  stateMap('state-10');
	
  function stateMap(stateLGD) {
	$('#show_block').hide();
    var stateName = stateLGD;
    Highcharts.getJSON('<?=base_url() ?>/json/' + stateName + '.json', function (geojson) {
      Highcharts.mapChart('map', {
        chart: {
         // borderWidth: 1,
         // borderColor: 'silver',
          //borderRadius: 3,
          height: '310px',
          //shadow: true,
          map: geojson
        },
        title: {
          text: ''
        },
        accessibility: {
          typeDescription: ''
        },
        mapNavigation: {
          enabled: true,
          buttonOptions: {
            verticalAlign: 'bottom',
			align: 'right',
          }
        },
        colorAxis: {
          min: 1,
          max: 1000,
          type: 'logarithmic',
          stops: [
            [0, '#ff5757'], // Less than 10 in 1000 Population
            [0.5, '#fed05b'], // 10 to 50 in 1000 Population
            [0.9, '#59d95b'], // Above 50 in 1000 Population
          ],
          marker: {
            color: '#343'
          }
        },
        plotOptions: {
          series: {
            point: {
              events: {
                click: function () {
                   var dtname = this.properties.name;
                  // dtname = dtname.replace(/\ /g, '').toLowerCase();
				 // alert(this.properties.Dist_LGD);
				
				 $("#block-name").html(dtname);
				  $('#show_block').show();
                  districtMap(this.properties.Dist_LGD);
                }
              }
            }
          }
        },
        tooltip: {
          pointFormatter: function () {
            return '<b>' + this.properties.name + '</b>: ' + this.value;
          }
        },
        exporting: {
          enabled: true,
        },
		credits: {
			enabled: false
		},
        series: [{
          borderWidth: 1,
          borderColor: 'gray',
		  cursor: 'pointer',
          data: [<?=$dist_data ?>],
          keys: ['Dist_LGD', 'value'],
          joinBy: 'Dist_LGD',
          name: 'Total survey',
          states: {
            hover: {
              color: '#a4edba'
            }
          },
          dataLabels: {
			enabled: true,
			format: '{point.properties.name}'
		},
        }]
      });
    });
  }

  function districtMap(districtLGD) {
    var districtName = districtLGD;
    Highcharts.getJSON('<?=base_url() ?>/json/BD/district-' + districtName + '.json', function (geojson) {
      Highcharts.mapChart('map', {
        chart: {
         // borderWidth: 0,
         // borderColor: 'silver',
         // borderRadius: 3,
          height: '310px',
         // shadow: false,
          map: geojson
        },
        title: {
          text: ''
        },
        accessibility: {
          typeDescription: ''
        },
        mapNavigation: {
          enabled: true,
          buttonOptions: {
            verticalAlign: 'bottom',
			align: 'right',
          }
        },
        colorAxis: {
          min: 1,
          max: 1000,
          type: 'logarithmic',
          stops: [
            [0, '#59d95b'], // Less than 10 in 1000 Population
            [0.5, '#fed05b'], // 10 to 50 in 1000 Population
            [0.9, '#ff5757'], // Above 50 in 1000 Population
          ],
          marker: {
            color: '#343'
          }
        },
        plotOptions: {
          series: {
            point: {
              events: {
                click: function () {
                  var sdtname = this.properties.sdtname;
                  sdtname = sdtname.replace(/\ /g, '').toLowerCase();
                  // districtMap(dtname);
                  //stateMap('pashchim_champaran');
                }
              }
            }
          }
        },
        tooltip: {
          pointFormatter: function () {
            return '<b>' + this.properties.sdtname + '</b>: ' + this.value;
          }
        },
        exporting: {
          enabled: true,
        },
		credits: {
			enabled: false
		},
        series: [{
          borderWidth: 1,
          borderColor: 'gray',
          data: [<?=$block_datas ?>],

		  keys: ['Subdt_LGD', 'value'],
          joinBy: 'Subdt_LGD',
          name: 'Total survey',
          states: {

            hover: {
              color: '#a4edba'
            }
          },
          dataLabels: {
            enabled: true,
            format: '{point.properties.sdtname}'
          }

        }]
      });
    });
  }
</script>

<script>
 $(document).ready(function() {
		// Jquery code here ///
		var today = new Date();
		var startdate;
		var enddate;
		// Set up the date range
		$('#from_datepicker').datepicker({
			dateFormat: 'dd-mm-yy',
			maxDate: 0,
			onSelect: function(selectedDate) {
				
				// Set the minimum date for the "to" datepicker
				// $('#to_datepicker').datepicker('option', 'minDate', selectedDate);
				$('#to_datepicker').datepicker('option', 'minDate', selectedDate);
			}
		});

		$('#to_datepicker').datepicker({
			dateFormat: 'dd-mm-yy',
			maxDate: 0,
			onSelect: function(selectedDate) {
				// Set the maximum date for the "from" datepicker
				$('#from_datepicker').datepicker('option', 'maxDate', selectedDate);
			}
		});


		$('#from_datepicker').change(function() {
			startdate = $(this).datepicker('getDate');
			$('#to_datepicker').datepicker('option', 'minDate', startdate);
		});
		$('#to_datepicker').change(function() {
			enddate = $(this).datepicker('getDate');
			$('#to_datepicker').datepicker('option', 'maxDate', enddate);
		});
	});
</script>
<script>

Highcharts.chart('form_status', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '',
        align: 'left'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
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
    credits: {
        enabled: false
    },
    series: [{
        name: 'Total',
        colorByPoint: true,
        data: [{
            name: 'Completed',
            y: 60,
            color: '#1cabe2' 
        },  {
            name: 'Ongoing',
            y: 40,
            color: '#e34e09'
        },]
    }]
});

</script>
<?php 

$survey_form_data='';
	$sury_form_qry = mysqli_query($conn, "SELECT count(survey_data_monitoring_id) as tot_survey,survey_name,survey_name_id FROM survey_data_monitoring GROUP BY survey_name_id");
	while($survey_datas = mysqli_fetch_array($sury_form_qry)){
		$survey_form_data.="['".$survey_datas['survey_name']."', ".$survey_datas['tot_survey']."],";

	}

	//echo $survey_form_data;

?>
<script>
    
		Highcharts.chart('form_wise_datacollection', {
			chart: {
				type: 'column'
			},
			title: {
				text: ''
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				type: 'category',
				labels: {
					//rotation: -12,
					style: {
						fontSize: '12px'
						
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Form wise Data'
				}
			},
			legend: {
				enabled: true
			},
			tooltip: {
				pointFormat: '<span style="font-size:12px">Number of data collected: {point.y}</span>'
			},
			credits: {
				enabled: false
			},
			series: [{
				name: 'Form wise data collection',
				data: [<?=$survey_form_data ?>
					//['Age Default Response 2', 10],['AMB checkist', 1],['AWC monitoring', 15]
				],
				dataLabels: {
					enabled: true,
					rotation: 0,
					color: '#FFFFFF',
					align: 'center',
					format: '<span style="font-size:13px">{point.y}</span>', // one decimal
					y: 5, // 10 pixels down from the top
					style: {
						fontSize: '15px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});

</script>
<?php 

	$daywise_cat=$daywisedata=array();
	$sury_form_qry = mysqli_query($conn, "SELECT count(survey_data_monitoring_id) as tot_survey,visit_date FROM survey_data_monitoring GROUP BY visit_date order by visit_date DESC  limit 30");
	$aldata=mysqli_fetch_all($sury_form_qry,MYSQLI_ASSOC);
	usort($aldata, function ($a, $b) {
		return strtotime($a['visit_date']) - strtotime($b['visit_date']);
	});


	//  $current_date=date("d-m-Y");
	//  $last_data_day=date("d-m-Y",strtotime('- 30 Days'));
	
	foreach($aldata as $key=> $survey_datas){
		$daywise_cat[]=date("d-m-Y",strtotime($survey_datas['visit_date']));
		$daywisedata[]=$survey_datas['tot_survey'];

	}

	//echo $survey_form_data;

?>
<script>
    
Highcharts.chart('day_wise_line', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [
            '<?=implode("','",$daywise_cat) ?>'
        ]
    },
    yAxis: {
        title: {
            text: 'Total Data'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: true
        }
    },
    series: [{
        name: 'Day wise data collection',
        data: [
            <?=implode(",",$daywisedata) ?>
        ],
		dataLabels: {
		enabled: true,
		rotation: 0,
		color: '#FFFFFF',
		align: 'center',
		format: '<span style="font-size:13px">{point.y}</span>', // one decimal
		y: 5, // 10 pixels down from the top
		style: {
			fontSize: '15px',
			fontFamily: 'Verdana, sans-serif'
		}
	}
    }
	

],
     credits: {
        enabled: false
    }
});

</script>
<!--Day wise activity Chart code -->
<?php if ($_SESSION['functional_role_id'] != 3 && $_SESSION['functional_role_id'] != 1) { ?>
	<?php
	$current_month = date('Y-m');
	$graph = '';
	$sqldata = "SELECT created_on,COUNT(survey_data_monitoring_id) as total_monitoring FROM survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN users on survey_data_monitoring.user_id=users.user_id where users.del_action='N' $client_qry1 $qry and created_on LIKE '$current_month%' GROUP BY date(created_on)";
	$sql = mysqli_query($conn, $sqldata);
	while ($row = mysqli_fetch_array($sql)) {
		$total_monitoring =  $row['total_monitoring'];
		$createdon = $row['created_on'];
		$created_on =  date('d, M-Y', strtotime($createdon));
		$graph .= "['" . $created_on . "', " . $total_monitoring . "],";
	}
	?>
	<!-- Day wise activity Chart code -->
	<script>
		Highcharts.chart('day_wise_data', {
			chart: {
				type: 'column'
			},
			title: {
				text: ''
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Number of data collected'
				}
			},
			legend: {
				enabled: true
			},
			tooltip: {
				pointFormat: '<span style="font-size:12px">Number of data collected: {point.y}</span>'
			},
			credits: {
				enabled: false
			},
			series: [{
				name: '',
				data: [<?= $graph ?>

				],
				dataLabels: {
					enabled: true,
					rotation: 0,
					color: '#FFFFFF',
					align: 'center',
					format: '<span style="font-size:13px">{point.y}</span>', // one decimal
					y: 5, // 10 pixels down from the top
					style: {
						fontSize: '15px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});
	</script>
	<?php
	$data = '';
	$datadrill = '';
	$sqlsurvey = "SELECT survey.survey_name,COUNT(survey_data_monitoring_id) as total_monitoring,survey_name_id FROM survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN users on survey_data_monitoring.user_id=users.user_id where users.del_action='N' $client_qry1 $qry group by survey.survey_name";
	$qrysurvey = mysqli_query($conn, $sqlsurvey);
	while ($row = mysqli_fetch_array($qrysurvey)) {
		$total_monitoring =  $row['total_monitoring'];
		$survey_name = $row['survey_name'];
		$survey_name_id = $row['survey_name_id'];

		$data .= "{
                    name: '" . $survey_name . "',
                    y: " . $total_monitoring . ",
                    drilldown: '" . $survey_name . "'
                },";

		$datadrill .= "{
                name: '" . $survey_name . "',
                id: '" . $survey_name . "',
                data: [";

		$sqlUSersurvey = "select users.name as username,COUNT(survey_data_monitoring_id) as total_count,survey.survey_name from survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN users on survey_data_monitoring.user_id=users.user_id where users.del_action='N' and survey_data_monitoring.survey_name_id='" . $survey_name_id . "' group by users.name";
		$qrysurveydrill = mysqli_query($conn, $sqlUSersurvey);
		while ($row1 = mysqli_fetch_array($qrysurveydrill)) {
			$total_count =  $row1['total_count'];
			$survey_name = $row1['survey_name'];
			$username = $row1['username'];

			$datadrill .= "[
                        '" . $username . "',
                        " . $total_count . "
                    ],";
		}
		$datadrill .= "] },";
	}

	?>
	<script>
		// Create the chart
		Highcharts.chart('form_wise_data', {
			chart: {
				type: 'column'
			},
			title: {
				align: 'left',
				text: ''
			},
			subtitle: {
				align: 'left',
			},
			accessibility: {
				announceNewData: {
					enabled: true
				}
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -40,
					style: {
						fontSize: '12px',
						//fontFamily: 'Verdana, sans-serif'
					}
				}
			},
			yAxis: {
				title: {
					text: 'Total data collected'
				}

			},
			legend: {
				enabled: false
			},
			credits: {
				enabled: false
			},
			plotOptions: {
				series: {
					borderWidth: 0,
					dataLabels: {
						enabled: true,
						format: '<span style="font-size:12px">{point.y}</span>'
					}
				}
			},

			tooltip: {
				headerFormat: '<span style="font-size:12px">{series.name}</span><br>',
				pointFormat: '<span style="font-size:12px;">{point.name}: <b>{point.y}</b> of total<br/></span>'
			},

			series: [{
				name: '',
				colorByPoint: true,
				data: [<?= $data; ?>]
			}],
			drilldown: {
				breadcrumbs: {
					position: {
						align: 'right',
						fontSize: '20px',
					}
				},
				series: [<?= $datadrill; ?>]
			}
		});
	</script>
	<?php
	//echo "SELECT survey_name,latitude,longitude,created_on,users.username FROM survey_data_monitoring left join users on survey_data_monitoring.user_id=users.user_id left join survey on survey.id=survey_data_monitoring.survey_name_id where users.del_action='N' and survey.status='1' $client_qry1 $qry";
	$result = mysqli_query($conn, "SELECT survey.survey_name,latitude,longitude,created_on,users.username FROM survey_data_monitoring left join users on survey_data_monitoring.user_id=users.user_id left join survey on survey.id=survey_data_monitoring.survey_name_id where users.del_action='N' $client_qry1 $qry");
	$result2 = mysqli_query($conn, "SELECT survey.survey_name,latitude,longitude,created_on,users.username FROM survey_data_monitoring left join users on survey_data_monitoring.user_id=users.user_id left join survey on survey.id=survey_data_monitoring.survey_name_id  where users.del_action='N' $client_qry1  $qry");
	?>

	<!--map start code--->
	 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5KJcNipnIvGqZkcdd2lFtY3elcTViNzU &callback=initMap&sensor=false&region=IN" type="text/javascript"></script>
	<!--<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBGDykuELSi8g1GQkyqlUblltEahmERiE&callback=initMap&sensor=false&region=IN" type="text/javascript"></script>-->
	</body>

	</html>
	<script>
		function initMap() {
			var map;
			var bounds = new google.maps.LatLngBounds();
			var mapOptions = {
				mapTypeId: 'roadmap'
			};
			// Display a map on the web page
			map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
			map.setTilt(100);
			// Multiple markers location, latitude, and longitude
			var markers = [
				<?php if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()) {
						if ($row['latitude'] != "" && $row['survey_name'] != '') {
							echo '["' . $row['survey_name'] . '", ' . $row['latitude'] . ', ' . $row['longitude'] . '],';
						}
					}
				}
				?>
			];
			// Info window content
			var infoWindowContent = [
				<?php if ($result2->num_rows > 0) {
					while ($row = $result2->fetch_assoc()) {
						if ($row['latitude'] != "" && $row['survey_name'] != '') {
				?>['<div class="info_content">' +
								'<p>Form Name: <?php echo $row['survey_name']; ?></p>' +
								'<p>User Name: <?php echo $row['username']; ?></p>' +
								'<p>Date: <?php echo date('d-M-Y,h:i:sA', strtotime($row['created_on'])); ?></p>' + '</div>'],
				<?php }
					}
				}
				?>
			];
			// Add multiple markers to map
			var infoWindow = new google.maps.InfoWindow(),
				marker, i;
			// Place each marker on the map  
			for (i = 0; i < markers.length; i++) {
				var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
				bounds.extend(position);
				marker = new google.maps.Marker({
					position: position,
					map: map,
					icon: markers[i][3],
					title: markers[i][0]
				});
				// Add info window to marker    
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						infoWindow.setContent(infoWindowContent[i][0]);
						infoWindow.open(map, marker);
					}
				})(marker, i));
				// Center the map to fit all markers on the screen
				map.fitBounds(bounds);
			}
			// Set zoom level
			var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
				this.setZoom(5);
				google.maps.event.removeListener(boundsListener);
			});
		}
		// Load initialize function
		google.maps.event.addDomListener(window, 'load', initMap);
	</script>
<?php } ?>
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


