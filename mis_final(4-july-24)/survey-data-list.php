<?php include_once('includes/config.php'); ?>
<?php define("title", "Review Data | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('mycrypt.php'); ?>
<?php
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
$client_id = $_SESSION['client_id'];
?>
<?php $surveyid = mysqli_real_escape_string($conn, $_GET['survey_id']); ?>
<?php
$qry = '';
if (isset($_REQUEST['search'])) {


	if (isset($_REQUEST['survey']) && $_REQUEST['survey'] != '') {
		$qry .= " AND survey_data_monitoring.survey_id='" . $_REQUEST['survey'] . "'";
	}
	if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
		$qry .= " AND users.name like'%" . $_REQUEST['name'] . "%'";
	}
	if (isset($_REQUEST['status']) && $_REQUEST['status'] != '') {
		$qry .= " AND survey_status_master.id='" . $_REQUEST['status'] . "'";
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

<?php
$client_qry = "";
$clientqry = "";
$client_qry1 = "";
if ($_SESSION['role_id'] == "3" || $_SESSION['role_id'] == "7") {

	$client_qry = " and survey_data_monitoring.client_id='" . $_SESSION['client_id'] . "' ";
	$client_qry1 = " and survey.client_id='" . $_SESSION['client_id'] . "' ";
	$clientqry = " and client_id='" . $_SESSION['client_id'] . "' ";
}
?>

<?php

//pagination
$per_page = 10;

$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['survey_id']) ? $page_url . "survey_id=" . $_GET['survey_id'] : $page_url;
$page_url = isset($_GET['survey']) ? $page_url . "&survey=" . $_GET['survey'] : $page_url;
$page_url = isset($_GET['name']) ? $page_url . "&name=" . $_GET['name'] : $page_url;
$page_url = isset($_GET['status']) ? $page_url . "&status=" . $_GET['status'] : $page_url;
$page_url = isset($_GET['from_date']) ? $page_url . "&from_date=" . $_GET['from_date'] : $page_url;
$page_url = isset($_GET['to_date']) ? $page_url . "&to_date=" . $_GET['to_date'] : $page_url;

//$page_url = isset($_GET['tdate'])? $page_url."&tdate=".$_GET['tdate']:$page_url;
//$page_url = isset($_GET['date'])? $page_url."&date=".$_GET['date']:$page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
	$current_page = intval($_GET['page']);
	$page = ($current_page - 1) * $per_page;
}
// echo "SELECT count(survey_name_id) as totrecords FROM survey_data_monitoring left JOIN users ON survey_data_monitoring.user_id=users.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id left join clients on survey_data_monitoring.client_id=clients.id where survey_name_id='" . $surveyid . "' $qry $client_qry order by survey_data_monitoring.survey_data_monitoring_id DESC";
$query = "SELECT count(survey_name_id) as totrecords FROM survey_data_monitoring left JOIN users ON survey_data_monitoring.user_id=users.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id left join clients on survey_data_monitoring.client_id=clients.id where survey_name_id='" . $surveyid . "' $qry $client_qry order by survey_data_monitoring.survey_data_monitoring_id DESC";
$get_query = mysqli_query($conn, $query);
$total_record = mysqli_fetch_object($get_query);
$total_record = $total_record->totrecords;
$total_pages = ceil($total_record / $per_page);
?>

<style>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" />

<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Form</a></li>
					<li><i class="fa fa-list"></i>List Forms</li>
					<li><i class='fa fa-eye'></i>Review Data</li>
				</ol>
			</div>
		</div>
		<div class="container-fluid">
			<div class="container-fluid">
				<form method="GET">
					<div class="row b5row">
						<input type="hidden" name="survey_id" value="<?= $surveyid ?>">
						<div class="col">
							<div class="form-group">
								<input type="text" class="form-control" id="survey" name="survey" value="<?= @$_REQUEST['survey'] ?>" placeholder="Form ID">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<input type="text" class="form-control" id="name" name="name" value="<?= @$_REQUEST['name'] ?>" placeholder="Enter Name">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<select class="form-control" id="status" name="status">
									<option value="">Status Type</option>
									<?php
									$sqlsurveyStatus = mysqli_query($conn, "SELECT id,name FROM survey_status_master WHERE id!='2' AND id!='5'");
									while ($rowstatus = mysqli_fetch_array($sqlsurveyStatus)) { ?>
										<option value="<?= $rowstatus['id'] ?>" <?php if ($_REQUEST['status'] == $rowstatus['id']) {
										echo "selected";
										} ?>><?= $rowstatus['name'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<input type="text" name="from_date" id="from_datepicker" value="<?= @$_REQUEST['from_date'] ?>" placeholder="From Date" class="form-control">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<input type="text" name="to_date" id="to_datepicker" placeholder="To Date" value="<?= @$_REQUEST['to_date'] ?>" class="form-control">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search" disabled>Search</button>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<a href="survey-data-list.php?survey_id=<?= $surveyid ?>" class="btn btn-primary width-md waves-effect waves-light form-control">Clear Filter</a>
							</div>
						</div>
					</div>
				</form>
			</div>



			<!-- page start-->
			<div class="row">
				<div class="col-sm-12">
					<!--Start Filter-->
					<div class="container">
						<form method="get">
							<input type="hidden" name="survey_id" value="<?= @$_REQUEST['survey_id'] ?>" />
							<!--<div class="row filter_css clearfix" style="margin-top:10px; padding-bottom: 10px; padding-top: 10px;">-->
						</form>
					</div>

					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="info-box brown-bg">
								<i class="icon_documents_alt"></i>
								<div class="count">
									<?php
									$surveysql = mysqli_query($conn, "SELECT count(survey_data_monitoring_id) as total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id  left join users on users.user_id=survey_data_monitoring.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id where survey_name_id='" . $surveyid . "' $qry $client_qry ");
									$data = mysqli_fetch_array($surveysql);
									echo $data['total'];
									?>
									<div class="title"><span style="color:white;">Number of entries collected</span></div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="info-box green-bg">
								<i class="fa fa-check-circle" aria-hidden="true"></i>
								<div class="count">
									<?php
									$accsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as acc_total,survey_name FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id  left join users on users.user_id=survey_data_monitoring.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id where survey_status in (1,6) and survey_name_id='" . $surveyid . "' $qry $client_qry");
									$data = mysqli_fetch_array($accsql);
									//echo $data['acc_total'];
									
									$accsqlsend = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as send_for_review,survey_name FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id  left join users on users.user_id=survey_data_monitoring.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id where survey_status in (4) and survey_name_id='" . $surveyid . "'$client_qry");
									$datasend = mysqli_fetch_array($accsqlsend);
									//echo $datasend['send_for_review'];
									
									$accsqlter = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as tot_terminated,survey_name FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id  left join users on users.user_id=survey_data_monitoring.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id where survey_status in (3) and survey_name_id='" . $surveyid . "' $client_qry");
									$datater = mysqli_fetch_array($accsqlter);
									//echo $datater['terminated'];
									
										// echo '<span class="text-left tooltips" data-placement="top" data-toggle="tooltip" data-html="true" data-original-title="Terminated: '.$datater['tot_terminated'].'<br>Send for review: '.$datasend['send_for_review'].'">'.
										 // $data['acc_total'].
										 // '</span>';
										 echo '<span class="text-left tooltips" data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="<span style=\'color:#449a97;font-weight: normal;\'>Terminated: '.$datater['tot_terminated'].'</span><br><span style=\'color:#449a97;font-weight: normal;\'>Sent for review: '.$datasend['send_for_review'].'</span>">'.
										 $data['acc_total'].
										 '</span>';
										
									//$survey_name = $data['survey_name'];
									?>
									<div class="title"><span style="color:white;">Number of entries verified</span></div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="info-box blue-bg">
								<i class="fa fa-users" aria-hidden="true"></i>
								<div class="count">
									<?php
									$usersql = mysqli_query($conn, "SELECT count(DISTINCT(users.user_id)) as total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id  left join users on users.user_id=survey_data_monitoring.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id where survey_name_id='" . $surveyid . "' $qry $client_qry");
									$data = mysqli_fetch_array($usersql);
									echo $data['total'];
									?>
									<div class="title"><span style="color:white;">Total Users</span></div>
								</div>
							</div>
						</div>

					</div>
					<?php

					$sql = "SELECT survey.survey_name,clients.name as client_name FROM survey left join clients on clients.id=survey.client_id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id left join users on survey_data_monitoring.user_id=users.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id where survey.id='" . $surveyid . "' $qry $client_qry1 order by survey.id DESC";
					$getSurvey = mysqli_query($conn, $sql);
					$row1 = mysqli_fetch_array($getSurvey);

					$survey_id_encode = base64_encode($surveyid);
					$actual_link = base_url() . "map_share.php?survey_id=" . $survey_id_encode;
					?>
					<section class="panel">
						<div class="card">
							<header class="panel-heading pheader">Form Name: <?php echo $row1['survey_name']; ?> <?php if ($_SESSION['role_id'] != '3') { ?>|| Client Name: <?php echo $row1['client_name']; ?> <?php } ?> || Total Records: <?= $total_record ?>

							</header>
						</div>
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="">S.No</th>
									<th class="">Form ID</th>
									<th class="">Submitted By</th>
									<th class="">Created On</th>
									<th class="">Status</th>
									<th class="">Action </th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql = "SELECT survey_name_id,survey_data_monitoring_id,survey_id,created_on,users.name,users.username,survey_status_master.name AS survey_status,survey_name,survey_data_monitoring.survey_status as survey_status_id,clients.name as client_name,survey_data_monitoring.full_json as full_json FROM survey_data_monitoring left JOIN users ON survey_data_monitoring.user_id=users.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id left join clients on survey_data_monitoring.client_id=clients.id where survey_name_id='" . $surveyid . "' $qry $client_qry order by survey_data_monitoring.survey_data_monitoring_id DESC limit $page,$per_page";
								$getSurvey = mysqli_query($conn, $sql);
								$sn = 1 + $page;
								if (mysqli_num_rows($getSurvey) > 0) {
									while ($survey = mysqli_fetch_array($getSurvey)) {
										//$full_json=json_decode($survey['full_json'],true);

										$DecryptedJson = $mcrypt->decrypt($survey['full_json']);
										$full_json = json_decode($DecryptedJson, true);
										$app_version = $full_json[app_version];
										$os_version = $full_json[os_version];
										$device_name = $full_json[device_name];
										$sequence_unique_id = $full_json[sequence_unique_id];
								?>
										<tr>
											<td><?= $sn++; ?></td>
											<td><?php if ($sequence_unique_id != '') {
													echo $sequence_unique_id;
												} else {
													echo $survey['survey_id'];
												}
												?></td>
											<td>
												<span class="label label-success" data-toggle="tooltip" data-html="true" data-placement="top" title="App Version </br> Device: <?= $device_name ?> </br> OS Version: <?= $os_version ?> "> v<?= $app_version; ?></span>
												<?= $survey['name'] ?> (<?= $survey['username'] ?>)

											</td>
											<td><?= date('d-M-Y,h:i:s A', strtotime($survey['created_on'])); ?></td>
											<td>
												<?php
												if ($survey['survey_status_id'] == 1) {
												?>
													<span class="label label-success">Submitted</span>
												<?php
												} elseif ($survey['survey_status_id'] == 3) {
												?>
													<span class="label label-danger">Terminated</span>
												<?php
												} elseif ($survey['survey_status_id'] == 5) {
												?>
													<span class="label label-success">Approved</span>
												<?php
												} elseif ($survey['survey_status_id'] == 6) {
												?>
													<span class="label label-warning">Re-submitted</span>
												<?php
												} elseif ($survey['survey_status_id'] == 4) {
												?>
													<span class="label label-primary">Sent for review</span>
												<?php
												} elseif ($survey['survey_status_id'] == 7) {
												?>
													<span class="label label-danger">Rejected</span>
												<?php
												}
												?>
											</td>
											<td>
												<a href="exportparadata.php?id=<?= $survey['survey_data_monitoring_id']; ?>" data-placement="top" data-toggle="tooltip" class="btn-sm btn-success tooltips" data-original-title="Download Paradata"><i class="fa fa-download" aria-hidden="true"></i></a>
												<a href="view_survey.php?id=<?= $survey['survey_data_monitoring_id']; ?>" class="btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View Record"><i class="fa fa-eye"></i></a>

											</td>
										</tr>
								<?php }
								} else {
									echo '<tr><td colspan="7" class="text-center" style="font-size: 25px;"  >Records Not Found !!</td></tr>';
								} ?>
							</tbody>
						</table>
					</section>
				</div>
			</div>
			<!-- page end-->
			<div class="text-left">
				<div class="d-flex align-items-center justify-content-between" id="pagination">
					<?= paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<section class="panel">
						<div class="card">
							<header class="panel-heading">Geographical coverage | Form Name: <?php echo $row1['survey_name']; ?>
								<a href="mailto:?Subject=Geographical coverage&amp;body=You can access your MQUAD: <?php echo $row1['survey_name']; ?> by clicking on the URL <?php echo $actual_link; ?>" style="margin:1px;" class="share pull-right"><i class="fa fa-share-alt" aria-hidden="true" style="border:none;"></i> Share</a>

							</header>
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
<?php include_once('includes/footer.php'); ?>

<?php
$result = mysqli_query($conn, "SELECT created_on,survey_id,survey_name,latitude,longitude,clients.name as client_name,users.username,users.name FROM survey_data_monitoring left JOIN users ON survey_data_monitoring.user_id=users.user_id left join clients on survey_data_monitoring.client_id=clients.id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id where survey_name_id='" . $surveyid . "' $qry $client_qry");
$result2 = mysqli_query($conn, "SELECT created_on,survey_id,survey_name,latitude,longitude,clients.name as client_name,users.username,users.name FROM survey_data_monitoring left JOIN users ON survey_data_monitoring.user_id=users.user_id left join clients on survey_data_monitoring.client_id=clients.id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id where survey_name_id='" . $surveyid . "' $qry $client_qry");
?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5KJcNipnIvGqZkcdd2lFtY3elcTViNzU &callback=initMap&sensor=false&region=IN" type="text/javascript"></script>
<!--<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBGDykuELSi8g1GQkyqlUblltEahmERiE&callback=initMap&sensor=false&region=IN" type="text/javascript"></script>-->
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
							<?php if ($_SESSION['role_id'] == '1') { ?> '<p style="color:#394A59;">Client Name: <?php echo $row['client_name']; ?></p>' + <?php } ?> '<p style="color:#394A59;">User Name: <?= $row['name']; ?> (<?php echo $row['username']; ?>)</p>' +
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
<!--map end code--->
<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip();
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
	});
</script>
<script>
	$("#name,#survey,#status").on("input", function() {
		//$("#name,#date1,#survey,#status").on("change",function() {
		if ($("#name").val() != '' || $("#survey").val() != '' || $("#status").val() != '') {
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
	$(document).ready(function() {
		// Jquery code here ///
		var startdate;
		var enddate;
		$('#from_datepicker').datepicker({
			date_format: 'yy-mm-dd'
		});
		$('#to_datepicker').datepicker({
			date_format: 'yy-mm-dd'
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