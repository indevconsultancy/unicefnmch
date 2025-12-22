<?php include('includes/config.php'); ?>
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
		<div class="row breadcrumb">
			<div class="col-lg-9 ">
				<ol class="breadcrumb">
					<li style="font-size: 18px; margin-top:10px"><i class="fa fa-dashboard"></i> MAA Programe Monthly Reporting Dashboard</li>
					
				</ol>
			</div>
			<?php $qryreportingp=mysqli_query($conn,"select reporting_period from maa_monthly_reporting order by reporting_period desc limit 0,1");
			      $datareportingp=mysqli_fetch_object($qryreportingp);
                  $month_year=$datareportingp->reporting_period;
				  $reporting_period=$datareportingp->reporting_period;
				  $date = DateTime::createFromFormat('n-Y', $month_year);// Format it as "F-Y" where "F" gives the full month name and "Y" gives the year
				  $formatted_date = $date->format('F-Y');
				  ?>
		
			<div class="col-lg-2">
			<select class="form-control" name="reporting_period"  style="margin-top:10px;" >
								<option value=""><?=$formatted_date?></option>
								<?php
									// Set the financial year start and end
									$financial_year_start = 2023;  // Change this to the starting financial year
									$financial_year_end = $financial_year_start + 1;

									// Loop from April of the starting year to March of the ending year
									for ($month = 4, $year = $financial_year_start; $month <= 12; $month++) {
										// Adjust month and year after December
										if ($month > 12) {
											$current_month = $month - 12;
											$current_year = $financial_year_end;
										} else {
											$current_month = $month;
											$current_year = $year;
										}
										
										// Get the full month name
										$month_name = date('F', mktime(0, 0, 0, $current_month, 1));

										// Output the month and year
									$reporting_periodss=$current_month . "-" . $current_year;
									$reporting_periodmm=$month_name . "-" . $current_year; 
									?>
										<option value="<?php echo $reporting_periodss; ?>" <?php if ($reporting_periodss == $_REQUEST['reporting_period'] || $reporting_periodss==$datareportingp->reporting_period) {
																							echo "selected";
												} ?>><?php echo $reporting_periodmm; ?></option>
									 <?php } ?>
									
							</select>
							</div>
							<div class="col-lg-1">
							<input class="btn btn-secondary" type="submit" name="Submit" value="Submit" style="margin-top:10px;">
		    </div>
		</div>
		<!--------filter start------->
		
	<?php
		$qrydashbord=mysqli_query($conn,"select sum(total_nos_asha) as total_nos_asha, sum(nos_asha_orented_iycf_meeting) as nos_asha_orented_iycf_meeting, sum(nos_mothers_meeting_held) as nos_mothers_meeting_held, sum(nos_facility_given_maa_award) as nos_facility_given_maa_award from maa_monthly_reporting where reporting_period='".$reporting_period."'");
		$dashborad_data=mysqli_fetch_object($qrydashbord);



	?>
	<div class="container-fluid1">
    	<div class="row">
		    <div class="col-md-6">
		        <div class="row">
		            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    				<div class="info-box threeColor">
    					<i class="fa fa-users"></i>
    					<div class="count">
                                <?=$dashborad_data->total_nos_asha ?>
    						<div class="title"><span style="color:white;">Total number of  ASHAs </span></div>
    					</div>
    				</div>
    			</div>
    		    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    				<div class="info-box twoColor">
    					<i class="fa fa-bar-chart"></i>
    					<div class="count">
    					 <?=$dashborad_data->nos_asha_orented_iycf_meeting ?>
    						<div class="title"><span style="color:white;">Total IYCF Meetings</span></div>
    					</div>
    				</div>
    			</div>
		        </div>
		       <div class="row">
    			    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    				<div class="info-box oneColor">
    					<i class="fa fa-check-circle"></i>
    					<div class="count">
    						 <?=$dashborad_data->nos_mothers_meeting_held ?>
    						<div class="title"><span style="color:white;">Number of mothers meeting held</span></div>
    					</div>
    				</div>
    			</div>
    		        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
    				<div class="info-box fourColor">
    					<i class="fa fa-times-circle fa-2x"></i>
    					<div class="count">
    						 <?=$dashborad_data->nos_facility_given_maa_award ?>
    						<div class="title"><span style="color:white;">Number of facilities given MAA Award</span></div>
    					</div>
    				</div>
    			</div>
    		    </div>
    		    <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-success">
                                        <i class="fa fa-download"></i>
                                    </div>
                                    <div class="ml-4 align-self-center"> <!-- Added ml-3 class for spacing -->
                                        <span class="text-muted">Export Data</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-info"><i class="fa fa-dashboard"></i></div>
                                    <div class="ml-4 align-self-center">
                                        <span class="text-muted"> Visualization</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <div class="round align-self-center round-danger"><i class="fa fa-newspaper-o"></i></div>
                                    <div class="ml-4 align-self-center">
                                        <span class="text-muted"> Repository</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
		    </div>
		    
		    <div class="col-md-6">
		        <section class="panel">
					<div class="card">
						<header class="panel-heading">District Reported</header>
						<div class="card-body">
						<p id="show_block" style="display:none;"><span class="cursor-pointer" onclick="stateMap('state-10')">Bihar</span>/<span id="block-name"></span></p>
							<div id="map" style="height:315px;width:100%;"></div>
						</div>
					</div>
				</section>
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
		   <!-- <div class="col-md-4">
		        <section class="panel">
					<div class="card">
						<header class="panel-heading">Form Status</header>
						<div class="card-body">
							<div id="form_status"></div>
						</div>
					</div>
				</section>
		    </div>-->
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
<script>
  stateMap('state-10');
  
  <?php 
	
	//$reporting_period=$datareportingp->reporting_period;
	
	$district_map=mysqli_query($conn,"SELECT count(id) as survey_count, district_name,district_id,map_code FROM maa_monthly_reporting where reporting_period='".$reporting_period."' GROUP BY map_code");
	$distictMap='';
	while($district_row=mysqli_fetch_array($district_map)){
		$distictMap.="['".$district_row['map_code']."', ".$district_row['survey_count']."],";
	}
  ?>
	
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
         // data: [['188', 1],['189', 3]],
		  data: [<?=$distictMap ?>],
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
          data: [['1112', 10]],
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
<script>
 <?php 
	
	//$reporting_period=$datareportingp->reporting_period;
	
	$form_wise_qry=mysqli_query($conn,"SELECT count(id) as survey_count, district_name,district_id,map_code FROM maa_monthly_reporting where reporting_period='".$reporting_period."' GROUP BY map_code");
	$formData='';
	while($from_row=mysqli_fetch_array($form_wise_qry)){
		$formData.="['".$from_row['district_name']."', ".$from_row['survey_count']."],";
	}
  ?>
    
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
					rotation: -45,
					style: {
						fontSize: '13px'
						
					}
				}
			},
			yAxis: {
				min: 0,
				allowDecimals: false,
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
				data: [<?=$formData ?>],
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
<script>
    
Highcharts.chart('day_wise_line', {
    chart: {
        type: 'line'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [
            'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7', 'Day 8', 'Day 9', 'Day 10',
            'Day 11', 'Day 12', 'Day 13', 'Day 14', 'Day 15', 'Day 16', 'Day 17', 'Day 18', 'Day 19', 'Day 20',
            'Day 21', 'Day 22', 'Day 23', 'Day 24', 'Day 25', 'Day 26', 'Day 27', 'Day 28', 'Day 29', 'Day 30'
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
            16.0, 18.2, 23.1, 27.9, 32.2, 36.4, 39.8, 38.4, 35.5, 29.2,
            22.0, 17.8, 20.1, 25.3, 29.7, 33.0, 37.4, 40.0, 39.5, 34.6,
            30.2, 26.5, 21.3, 18.5, 22.1, 26.8, 30.0, 33.9, 38.0, 40.3
        ]
    }],
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


