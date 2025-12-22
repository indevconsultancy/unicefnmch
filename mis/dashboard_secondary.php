 <?php include('includes/config.php'); ?>
  <?php define("title","Home | MQUAD");?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>

<!--main content start-->
<?php
	$client_qry="";
	$clientsqry='';
	//if($_SESSION['role_id']=="3" || $_SESSION['role_id']=="7"){
	if($_SESSION['role_id']=="3"){
		// $_SESSION['user_id'];
		$client_id = $_SESSION['client_id'];
		$client_qry=" and survey_data_monitoring.client_id='".$client_id."' ";
		$client_qry1=" and survey.client_id='".$client_id."' ";
		$clientqry=" and clients.id='".$client_id."' ";
		$clientsqry=" and client_id='".$client_id."' ";
	}
	if($_SESSION['role_id']=="9"){  //TL
		// $_SESSION['user_id'];
		$user_id = $_SESSION['user_id']; //
		$client_qry=" and survey_data_monitoring.survey_name_id in(SELECT DISTINCT(survey_id) AS survey_id FROM assign_survey WHERE status='0' AND user_id='".$user_id."') ";
		//$clientqry=" and clients.id='".$client_id."' ";
	}
?>
<?php 
	//$client_qry="";
	if($_SESSION['role_id']=="7"){
		$client_id = $_SESSION['client_id'];
		$client_qry=" and survey_data_monitoring.client_id='".$client_id."' ";
		$clientqry=" and clients.id='".$client_id."' ";
	}
?>
<?php
	$qry='';
	if (isset($_REQUEST['search'])){
		 if(isset($_REQUEST['survey_name_id']) && $_REQUEST['survey_name_id']!='') {
			$qry.= " AND survey_data_monitoring.survey_name_id='".$_REQUEST['survey_name_id']."'";
			}
		if(isset($_REQUEST['client_id']) && $_REQUEST['client_id']!='') {
            $qry.= " AND clients.id='".$_REQUEST['client_id']."'";
        }
	}
?>

<style type="text/css">
  
.info-box i {
    display: block;
    height: 40px;
    font-size: 60px;
    line-height: 60px;
    width: 70px;
    float: left;
    text-align: center;
    margin-right: 80px;
    padding-right: 20px;
    color: rgba(255, 255, 255, 0.75);
	}
</style>
<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><i class="fa fa-home"></i><a href="dashboard.php">Home </a></li>
        </ol>
      </div>
    </div>
	<!--------filter start------->
		<div class="container-fluid">
            <form class="form-inline" method="get" role="form">
				<div class="row filter_css clearfix">
					<?php
						if($_SESSION['role_id']!="3"){ ?>
							<div class="form-group col-md-5" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<select class="form-control" name="client_id" id="client_id" onchange="getsurvey(this.value);">
									<option value="">Select Client</option>
										<?php  
											$clentType=mysqli_query($conn,"select id,name from clients where del_action='N' order by name");
											while($Type=mysqli_fetch_array($clentType)){?> 
											<option value="<?php echo $Type['id'];?>"<?php if($Type['id']==$_REQUEST['client_id']){echo "selected";}?>><?php echo $Type['name'];?></option>
											<?php
											}
										?>
								</select>
							</div>
						<?php	
						}
					?>
					
					<div class="form-group col-md-5" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
						<select class="form-control" name="survey_name_id" id="survey_name_id">
							<option value="">Select Form</option>
								<?php //if($_GET['client_id']!=''){
									$sqlservey="SELECT id,survey_name,client_id,created_at FROM survey where del_action='N' $clientsqry order by survey.survey_name ASC";
									$selectservey=mysqli_query($conn,$sqlservey);
									while($surveydata=mysqli_fetch_array($selectservey)){ ?> 
										<!--<option value="<?php echo $surveydata['id'];?>"<?php if($surveydata['id']==$_REQUEST['survey_name_id']){echo "selected";}?>><?php echo $surveydata['survey_name'];?></option>-->
									<option value="<?php echo $surveydata['id'];?>"<?php if($surveydata['id']==$_REQUEST['survey_name_id']){echo "selected";}?>><?php echo "".$surveydata['survey_name']."(".date("j-F-Y",strtotime($surveydata['created_at'])).")";?></option>
									<?php }	?>
									<?php //} ?>
						</select>
					</div>
					<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
						<button type="submit" class="btn btn-secondary width-md waves-effect waves-light form-control" name="search">Search</button>
					</div>
					
				</div>
			</form>
        </div>
		
	<!--------filter end------->
    <!-- page start-->
	
	
	
	
	<div class="row">
      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="info-box oneColor">
          <i class="icon_documents_alt"></i>
          <div class="count">
		  <?php
		 	 $surveysql=mysqli_query($conn,"select COUNT(survey.id) as total_survey from survey LEFT JOIN clients on survey.client_id=clients.id LEFT JOIN survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id where survey.del_action='N' $client_qry1 $qry");
			 $data=mysqli_fetch_array($surveysql);
			 echo $totsurvey=$data['total_survey'];
			?>
          <div class="title"><span style="color:white;">Forms registered</span></div>
		  </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="info-box twoColor">
          <i class="fa fa-check-circle"></i>
          <div class="count">
			<?php
			$sql_publish=mysqli_query($conn,"select COUNT(DISTINCT(survey_name_id)) as total_publish,survey.status from survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey.status='1' and survey.del_action='N' $client_qry $qry");
			$data=mysqli_fetch_array($sql_publish);
			 echo $data['total_publish'];
			?>
          <div class="title"><span style="color:white;">Forms published</span></div>
        </div>
		</div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="info-box threeColor">
          <i class="fa fa-bar-chart"></i>
          <div class="count">
			<?php
			$sql_survey=mysqli_query($conn,"select count(survey_data_monitoring_id) as total_monitoring from survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where 1=1 $client_qry $qry");
			$data=mysqli_fetch_array($sql_survey);
			 echo $data['total_monitoring'];
			?>
           <div class="title"><span style="color:white;">Forms collected data</span></div>
		  </div>
        </div>
      </div>
	  <?php if($_SESSION['role_id']==3){ ?>
	  <div class="col-lg-3 col-md-3col-sm-12 col-xs-12" >
        <div class="info-box fourColor">
        <i class="fa fa-usd" aria-hidden="true"></i>
          <div class="count">
			<?php	
				$total_sub=mysqli_query($conn,"select (membership.nof_survey) as total_nof_survey  from clients inner join membership on clients.membership_id=membership.membership_id where clients.id = '".$_SESSION['client_id']."'");
				$use_total=mysqli_fetch_array($total_sub);
				 $total_survey=$use_total['total_nof_survey'];

				$use_sub=mysqli_query($conn,"SELECT count(survey.id) as use_survey FROM `survey`  where client_id='".$_SESSION['client_id']."' ");
				$total=mysqli_fetch_array($use_sub);
				 $use_survey=$total['use_survey'];
				//echo $total=$total_survey.'/'.$use_survey;
				echo "0";
			?>
				<div class="title"><span style="color:white;">Subscription Status</span></div>
        </div>
		</div>
      </div>
	  <?php } ?>
	   <?php if($_SESSION['role_id']!=3){ ?>
	    <div class="col-lg-3 col-md-3col-sm-12 col-xs-12" >
        <div class="info-box fourColor">
          <i class="fa fa-users" aria-hidden="true"></i>
          <div class="count">
			<?php
			if($_SESSION['role_id']==9){
				$cid = $_SESSION['client_id'];
				$getTlUsers = mysqli_query($conn,"SELECT COUNT(user_id) AS totuser FROM `users` WHERE client_id='".$cid."' AND role_id!='3' ");
				$data = mysqli_fetch_array($getTlUsers);
				echo $data['totuser'];
			?>
				<div class="title"><a href="user-list.php" style="color:white;">Users</a></div>
			<?php	
			}else{
				$sql_client=mysqli_query($conn,"SELECT COUNT(DISTINCT(client_id)) as total_client FROM `survey_data_monitoring` LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where 1=1 $clientqry $qry");
				$data=mysqli_fetch_array($sql_client);
				echo $data['total_client'];
			?>
				<div class="title"><a href="client-list.php" style="color:white;">Clients</a></div>
			<?php } ?>
        </div>
		</div>
      </div>
	  <?php } ?>
	 </div>  
		<!-- page end-->
	<section class="main-content">		
	<div class="row">
		<div class="col-md-6">
			<section class="panel">
				<div class="card">
				  <header class="panel-heading">Total number of Published and Unpublished Forms (N=<?=$totsurvey;?>)</header>
					<div class="card-body">
						<div id="chartdiv"></div>
					</div>
				</div>
			</section>	
		</div>
		<div class="col-md-6">
			<section class="panel">
				<div class="card">
				  <header class="panel-heading">Month_wise day_wise data collected</header>
					<div class="card-body">
						<div id="chartbar"></div>
					</div>
				</div>
			</section>	
		</div>
	</div>
	<div class="row"> 
		<div class="col-md-12">
			<section class="panel">
				<div class="card">
					  <header class="panel-heading">Location_wise aggregated data</header>
					<div class="card-body">
						<div id="mapCanvas" style="height:400px;width:100%;"></div>
					</div>
					</div>	
				</div>
			</section>	
		</div>
	</div>
  </section>
  </section>
</section>
<!--main content end-->
<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 400px;
}
</style>

<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>
<script src="js/export-data.js"></script>
<script src="js/accessibility.js"></script>
<!--pie Chart code start  -->
<?php
	$pubsql="select COUNT(DISTINCT(survey_name_id)) as Publish from survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey.status='1' and survey.del_action='N' $client_qry $qry";
	$pub_query=mysqli_query($conn,$pubsql);
	$data=mysqli_fetch_array($pub_query);
	$publish=$data['Publish'];

	$unpubsql="select COUNT(DISTINCT(survey_name_id)) as Unpublish from survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey.status='0' and survey.del_action='N' $client_qry $qry";
	$unp_query=mysqli_query($conn,$unpubsql);
	$data=mysqli_fetch_array($unp_query);
	$unpublish=$data['Unpublish'];
?>
<!--pie Chart code end -->
<script>
// Build the chart
Highcharts.setOptions({
     colors: ['#0f487f', '#b15c20']
    });
Highcharts.chart('chartdiv', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: ''
  },
  // tooltip: {
    // pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  // },
  tooltip: {
        pointFormat: '{series.name}:<b>{point.y:.0f}</b> <br> <b>{point.percentage:.1f}%</b>'
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
    data: [
	{
      name: 'Publish',
      y: <?=$publish;?>
	  
    }, {
      name: 'Unpublish',
      y: <?=$unpublish?>
    }
	]
  }]
});
</script>

<style>
#chartbar {
  width: 100%;
  height: 400px;
}
</style>

<!--<script src="js/am5/index.js"></script>
<script src="js/am5/xy.js"></script>
<script src="js/am5/Animated.js"></script>-->
<!-- Chart code -->
<!--Day wise activity Chart code -->
<?php
	$current_month=date('Y-m');
	$graph='';
	 $sqldata="SELECT created_on,COUNT(survey_data_monitoring_id) as total_monitoring FROM survey_data_monitoring left join survey on survey_data_monitoring.survey_name_id=survey.id LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where 1=1 $client_qry $qry and created_on LIKE '$current_month%' GROUP BY date(created_on)";
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
	Highcharts.chart('chartbar', {
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
      text: 'Total Count (number)'
    }
  },
  legend: {
    enabled: true
  },
  tooltip: {
    pointFormat: 'Total Count: <b>{point.y}</b>'
  },
  credits:{
  	enabled:false
  },
  series: [{
    name: 'Total Count',
    data: [ <?=$graph ?>
    
    ],
    dataLabels: {
      enabled: true,
      rotation: -80,
      color: '#FFFFFF',
      align: 'right',
      format: '{point.y}', // one decimal
      y: 10, // 10 pixels down from the top
      style: {
        fontSize: '13px',
        fontFamily: 'Verdana, sans-serif'
      }
    }
  }]
});

</script>

<?php 
//echo "SELECT survey_name,latitude,longitude,created_on FROM survey_data_monitoring left join clients on survey_data_monitoring.client_id=clients.id where 1=1 $client_qry $qry";
$result =mysqli_query($conn,"SELECT survey_name,latitude,longitude,created_on,users.username FROM survey_data_monitoring left join clients on survey_data_monitoring.client_id=clients.id left join users on users.user_id=survey_data_monitoring.user_id where 1=1 $client_qry $qry"); 
$result2 =mysqli_query($conn,"SELECT survey_name,latitude,longitude,created_on,users.username FROM survey_data_monitoring left join clients on survey_data_monitoring.client_id=clients.id left join users on users.user_id=survey_data_monitoring.user_id where 1=1 $client_qry $qry"); 
?>

<!--map start code--->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5-ed0P7eZL44UHUnOF_WB-BN3XaUk4zk&callback=initMap&sensor=false&region=IN" type="text/javascript"></script>
<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-dFHYjTqEVLndbN2gdvXsx09jfJHmNc8&callback=initMap"></script>-->
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
<!--map end code--->
<script>
	function getsurvey(val){
		 var surveyIdc=$('#client_id').val();
        // alert (surveyIdc);
        $.ajax({
			type:'post',
            url:'ajax/getsurvey.php',
            data:'client_id='+surveyIdc,
            success:function(responsedata){
                $('#survey_name_id').html(responsedata);
				$('#survey_name_id').selectpicker('refresh');
            }
        });
	}
</script>
<!--Dependancy end code--->
<?php include_once('includes/footer.php'); ?>