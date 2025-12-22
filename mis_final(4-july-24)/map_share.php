<?php
include_once('includes/config.php');
define("title", "Survey Location | MQUAD");
include_once('includes/header_dashboard.php');
include_once('includes/functions.php');
?>
<?php

$surveyid = mysqli_real_escape_string($conn, $_GET['survey_id']);
$survey_id = base64_decode($surveyid);
$client_qry = $qryUser = "";

$getUser = mysqli_query($conn, "select id,client_id,survey_name from survey where id='" . $survey_id . "'");
$dataUser = mysqli_fetch_array($getUser);
$client_id = $dataUser['client_id'];
$survey_name = $dataUser['survey_name'];

$client_qry = " and survey.client_id='" . $client_id . "' ";
$qryUser = " and users.client_id='" . $client_id . "' ";

?>

<?php
$qry = '';
if (isset($_REQUEST['search'])) {
	if (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != '') {
		$qry .= " AND survey_data_monitoring.survey_name_id='" . base64_decode($_REQUEST['survey_id']) . "'";
	}
	if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') {
        $qry .= " AND survey_data_monitoring.user_id='" . $_REQUEST['user_id'] . "'";
    }
	if (isset($_REQUEST['fdate']) && isset($_REQUEST['tdate'])) {
		$d1 = date('Y-m-d', strtotime($_REQUEST['fdate']));
		//$d1 = $_REQUEST['fdate'] . ' 00:00:00';
		$d2 = date('Y-m-d', strtotime($_REQUEST['tdate']));
		// $d2 = $_REQUEST['tdate'] . ' 23:59:00';
		if (!empty($_REQUEST['fdate']) && !empty($_REQUEST['tdate'])) {
			if ($qry != ' ') {
				$qry .= 'AND ';
			}
			$qry .= "date(survey_data_monitoring.created_on) BETWEEN '" . $d1 . "' AND '" . $d2 . "'";
		} else {
			if (!empty($_REQUEST['fdate'])) {
				if ($qry != ' ') {
					$qry .= 'AND ';
				}
				$qry .= "date(survey_data_monitoring.created_on) >= '" . $d1 . "'";
			}
			if (!empty($_REQUEST['tdate'])) {
				if ($qry != ' ') {
					$qry .= 'AND ';
				}
				$qry .= "date(survey_data_monitoring.created_on) <= '" . $d2 . "'";
			}
		}
	}
}
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
	/* Center alignment class for flexible box layout */
	.c-center-align {
		height: 200px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	/* Main content margin reset */
	#main-content {
		margin-left: 0px;
	}

	/* Hide navigation toggle button */
	.toggle-nav {
		display: none;
	}
</style>

<!-- Main content section -->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="fa fa-laptop"></i> Walkthrough Map</li>
				</ol>
			</div>
		</div>

		<!-- Header section with form and client name placeholders -->
		<div class="container-fluid">
			<form method="GET">
				<div class="row">
					<input type="hidden" name="survey_id" value="<?= base64_encode($survey_id) ?>">
					<div class="col-lg-3 col-md-3">
						<select class="form-control" name="user_id" id="user_id">
						<option value="">Select User</option>
						<?php
						//echo "SELECT users.user_id,users.name FROM `users` where role_id in (6,3) and status='0' and registered_as!='' $qryUser ";
						$surveyorqry = mysqli_query($conn, "SELECT users.user_id,users.name FROM `users` where role_id in (6,3) and status='0' and registered_as='' $qryUser order by users.name ASC");
						while ($surveyorget = mysqli_fetch_array($surveyorqry)) { ?>
							<option value="<?php echo $surveyorget['user_id'] ?>" <?php if ($surveyorget['user_id'] == $_REQUEST['user_id']) { echo "selected"; } ?>><?php echo $surveyorget['name'] ?></option>
						<?php } ?>
					</select>																																														
					</div>
					<div class="col-lg-3 col-md-2">
						<input type="text" data-placement="top" id="date1" data-toggle="tooltip" data-original-title="From date" class="form-control" title="From date" placeholder="From Date" name="fdate" value="<?php if ($_REQUEST['fdate']) { echo $_REQUEST['fdate']; } ?>">
					</div>
					<div class="col-lg-2 col-md-2">
						<input type="text" data-placement="top" id="date2" data-toggle="tooltip" data-original-title="To date" class="form-control" title="To date" placeholder="To Date" name="tdate" value="<?php if ($_REQUEST['tdate']) { echo $_REQUEST['fdate']; } ?>">
					</div>
					<div class="col-lg-2 col-md-2">
						<div class="form-group">
							<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search" disabled>Search</button>
						</div>
					</div>
					<div class="col-lg-2 col-md-2">
						<div class="form-group">
							<a href="map_share.php?survey_id=<?= $surveyid ?>" class="btn btn-primary width-md waves-effect waves-light form-control">Clear Filter</a>
						</div>
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-md-12">
					<section class="panel">
						<header class="panel-heading">Geographical coverage | Form Name: <?=$survey_name?>

						</header>
						<div id="mapCanvas" style="height:400px;width:100%;"></div>
					</section>
				</div>
			</div>
		</div>
	</section>
</section>
<?php include_once('includes/footer.php'); ?>
<?php

//echo "SELECT survey_data_monitoring_id,survey_id,latitude,longitude,created_on,clients.name as client_name,survey.survey_name,users.username FROM survey_data_monitoring left join clients on survey_data_monitoring.client_id=clients.id left JOIN users ON survey_data_monitoring.user_id=users.user_id LEFT join survey on survey_data_monitoring.survey_name_id=survey.id where survey.del_action='N' and survey.id='" . $survey_id . "' $qry ";
$result = mysqli_query($conn, "SELECT survey_data_monitoring_id,survey_id,latitude,longitude,created_on,clients.name as client_name,survey.survey_name,users.username FROM survey_data_monitoring left join clients on survey_data_monitoring.client_id=clients.id left JOIN users ON survey_data_monitoring.user_id=users.user_id LEFT join survey on survey_data_monitoring.survey_name_id=survey.id where survey.del_action='N' and survey.id='" . $survey_id . "' $qry ");
$result2 = mysqli_query($conn, "SELECT survey_data_monitoring_id,survey_id,latitude,longitude,created_on,clients.name as client_name,survey.survey_name,users.username FROM survey_data_monitoring left join clients on survey_data_monitoring.client_id=clients.id left JOIN users ON survey_data_monitoring.user_id=users.user_id LEFT join survey on survey_data_monitoring.survey_name_id=survey.id where survey.del_action='N' and survey.id='" . $survey_id . "' $qry ");

?>
<!--map start code--->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5KJcNipnIvGqZkcdd2lFtY3elcTViNzU &callback=initMap&sensor=false&region=IN" type="text/javascript"></script>
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
							'<p style="color:#394A59;">Survey Name: <?php echo $row['survey_name']; ?></p>' +
							'<p style="color:#394A59;">User Name: <?php echo $row['username']; ?></p>' +
							'<p style="color:#394A59;">Form Id: <?php echo $row['survey_id']; ?></p>' +
							'<p style="color:#394A59;"><?php echo date('d-M-Y,h:i:sA', strtotime($row['created_on'])); ?></p>' + '</div>'],
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

<script>
	$("#user_id").on("input", function() {
		//$("#name,#date1,#survey,#status").on("change",function() {
		if ($("#user_id").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', true);
		}
	});

	$("#date,#date2").on("click", function() {
		if ($("#date1").val() != '' || $("#date2").val() != '') {
			$('#btnsearch').prop('disabled', false);
		} else {
			$('#btnsearch').prop('disabled', false);
		}
	});
</script>

<script>
	$(document).ready(function() {
		// Jquery code here ///
		var startdate;
		var enddate;
		$('#date1').datepicker({
			date_format: 'yy-mm-dd'
		});
		$('#date2').datepicker({
			date_format: 'yy-mm-dd'
		});

		$('#date1').change(function() {
			startdate = $(this).datepicker('getDate');
			$('#date2').datepicker('option', 'minDate', startdate);
		});
		$('#date2').change(function() {
			enddate = $(this).datepicker('getDate');
			$('#date1').datepicker('option', 'maxDate', enddate);
		});
	});
</script>