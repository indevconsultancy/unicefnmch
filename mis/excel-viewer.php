<?php include('includes/config.php'); ?>
<?php define("title","Home | MQUAD");?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>

<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><i class="fa fa-home"></i>Home</li>
        </ol>
      </div>
    </div>
	<div class="container-fluid">
		<div class="filter_css clearfix">
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<section class="panel">
						<div class="panel panel-default"> 
							<div class="panel-body homemain-icotabs">
								<div class="row">
									<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height: 400px; overflow: scroll;">
										
										<?php
											// VIEW TXT FIELS
											$filename = "spss/Exported.dat";
											$lines = array();
											$fp = fopen($filename, "r");
											if(filesize($filename) > 0){
												$content = fread($fp, filesize($filename));
												$lines = explode("\n", $content);
												fclose($fp);
											}
											foreach($lines as $line){
												echo "<p style='text-wrap: nowrap;'>$line</p>";
											} 
										?>
										
										<?php
											//VIEW EXCEL
											// ini_set('display_errors', 1);
											// ini_set('display_startup_errors', 1);
											// error_reporting(E_ALL);
											
										/* 	set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
											include_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';
											$inputFileType = 'Excel2007';
											//$inputFileName = 'https://mquad.org/mis/uploaded_questionnaire/C91/Cimmyt-test-survey-1-1361641465.xlsx';
											$inputFileName = 'uploaded_questionnaire/C91/test-file.xlsx';

											$objReader = PHPExcel_IOFactory::createReader($inputFileType);
											$objPHPExcel = $objReader->load($inputFileName);

											$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
											$objWriter->save('php://output');
											//exit;
											 */
										?>
										
									</div>
								</div>
							</div>									
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
	</div>
  </section>
  </section>
<!--Dependancy end code--->
<?php include_once('includes/footer.php'); ?>

<script src="js/exporting.js"></script>
<script src="js/export-data.js"></script>
<script src="js/accessibility.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script>
$("#survey_name_id").on("input", function() {
		if ($("#survey_name_id").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', true);
		}
	});
</script>
<!--Day wise activity Chart code -->
<?php if($_SESSION['functional_role_id']!=3 && $_SESSION['functional_role_id']!=1){ ?>
<?php
	$current_month=date('Y-m');
	$graph='';
	$sqldata="SELECT created_on,COUNT(survey_data_monitoring_id) as total_monitoring FROM survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN users on survey_data_monitoring.user_id=users.user_id where users.del_action='N' $client_qry1 $qry and created_on LIKE '$current_month%' GROUP BY date(created_on)";
	$sql=mysqli_query($conn,$sqldata);
	while($row=mysqli_fetch_array($sql)){
		$total_monitoring =  $row['total_monitoring'];
		$createdon = $row['created_on'];
		$created_on =  date('d, M-Y',strtotime($createdon));
		$graph.="['".$created_on."', ".$total_monitoring."],";

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
  credits:{
  	enabled:false
  },
  series: [{
    name: '',
    data: [ <?=$graph ?>
    
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
$data='';
$datadrill='';
$sqlsurvey="SELECT survey.survey_name,COUNT(survey_data_monitoring_id) as total_monitoring,survey_name_id FROM survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN users on survey_data_monitoring.user_id=users.user_id where users.del_action='N' $client_qry1 $qry group by survey.survey_name";
$qrysurvey=mysqli_query($conn,$sqlsurvey);
	while($row=mysqli_fetch_array($qrysurvey)){
		$total_monitoring =  $row['total_monitoring'];
		$survey_name = $row['survey_name'];
		$survey_name_id = $row['survey_name_id'];

		$data.="{
                    name: '".$survey_name."',
                    y: ".$total_monitoring.",
                    drilldown: '".$survey_name."'
                },";
		
			$datadrill.="{
                name: '".$survey_name."',
                id: '".$survey_name."',
                data: [";
		
		$sqlUSersurvey="select users.name as username,COUNT(survey_data_monitoring_id) as total_count,survey.survey_name from survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN users on survey_data_monitoring.user_id=users.user_id where users.del_action='N' and survey_data_monitoring.survey_name_id='".$survey_name_id."' group by users.name";		
		$qrysurveydrill=mysqli_query($conn,$sqlUSersurvey);
		while($row1=mysqli_fetch_array($qrysurveydrill)){
			$total_count =  $row1['total_count'];
			$survey_name = $row1['survey_name'];
			$username = $row1['username'];
			
			$datadrill.="[
                        '".$username."',
                        ".$total_count."
                    ],";
		}
			$datadrill.="] },";
		
		
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
	credits:{
  	enabled:false
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

    series: [
        {
            name: '',
            colorByPoint: true,
            data: [<?=$data;?>]
        }
    ],
    drilldown: {
        breadcrumbs: {
            position: {
                align: 'right',
				fontSize:'20px',
            }
        },
        series: [<?=$datadrill;?>]
    }
});

</script>
<?php 
//echo "SELECT survey_name,latitude,longitude,created_on,users.username FROM survey_data_monitoring left join users on survey_data_monitoring.user_id=users.user_id left join survey on survey.id=survey_data_monitoring.survey_name_id where users.del_action='N' and survey.status='1' $client_qry1 $qry";
$result =mysqli_query($conn,"SELECT survey.survey_name,latitude,longitude,created_on,users.username FROM survey_data_monitoring left join users on survey_data_monitoring.user_id=users.user_id left join survey on survey.id=survey_data_monitoring.survey_name_id where users.del_action='N' $client_qry1 $qry"); 
$result2 =mysqli_query($conn,"SELECT survey.survey_name,latitude,longitude,created_on,users.username FROM survey_data_monitoring left join users on survey_data_monitoring.user_id=users.user_id left join survey on survey.id=survey_data_monitoring.survey_name_id  where users.del_action='N' $client_qry1  $qry"); 
?>

<!--map start code--->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBGDykuELSi8g1GQkyqlUblltEahmERiE&callback=initMap&sensor=false&region=IN" type="text/javascript"></script>
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
        <?php if($result->num_rows > 0){ 
            while($row = $result->fetch_assoc()){ 
			if($row['latitude']!="" && $row['survey_name']!='' ){
                echo '["'.$row['survey_name'].'", '.$row['latitude'].', '.$row['longitude'].'],'; 
            } 
        } 
		}
        ?>
    ];
    // Info window content
    var infoWindowContent = [
        <?php if($result2->num_rows > 0){ 
            while($row = $result2->fetch_assoc()){
				if($row['latitude']!="" && $row['survey_name']!='' ){
				?>
               ['<div class="info_content">' +
               '<p>Survey Name: <?php echo $row['survey_name']; ?></p>' +
			   '<p>User Name: <?php echo $row['username']; ?></p>' +
			   '<p>Date: <?php echo date('d-M-Y,h:i:sA',strtotime($row['created_on'])); ?></p>'+ '</div>'],
        <?php }
			}
        } 
        ?>
    ];
    // Add multiple markers to map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    // Place each marker on the map  
    for( i = 0; i < markers.length; i++ ) {
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
$(document).ready(function (){

  $(".tgltab").click(function () {
	//console.log($(this).parent().parent());
	if($(this).parent().parent().hasClass('active')){
		$(this).parent().parent().removeClass('active')
	}else{
		$(this).parent().parent().addClass('active')
	}
	
    $(this).parent().parent().find('.accord-body').slideToggle(500);
    
  });
	
  $('.dash-body ul li.hasChild:not(.noAct)').each(function(){
	  $(this).addClass('active');
	  $(this).find('.accord-body').css('display','block');
  })
  
});
</script>