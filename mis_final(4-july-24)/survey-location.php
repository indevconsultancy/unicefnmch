<?php include_once('includes/config.php'); ?>
<?php define("title", "Survey Location | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php
$survey_id = $_REQUEST['survey_id'];
$client_qry = $qryUser = "";
if ($_SESSION['role_id'] == '3') {
    $survey_id = $_REQUEST['survey_id'];
    $client_id = $_SESSION['client_id'];
    $client_qry = " and survey.client_id='" . $client_id . "' ";
    //$client_qry1=" and survey.client_id='".$client_id."'";
    $qryUser = " and client_id='" . $client_id . "' ";
}

$getUser = mysqli_query($conn, "select id,client_id,survey_name from survey where id='" . $survey_id . "'");
$dataUser = mysqli_fetch_array($getUser);
$client_id = $dataUser['client_id'];
$survey_name = $dataUser['survey_name'];
?>
<?php
$qry = '';
if (isset($_REQUEST['search'])) {
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
<style>
    .panel-heading {
        background: #394a59;
        color: white;
        font-weight: unset;
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
    .ui-datepicker td a {
		text-align: center !important;
	}

    #ui-datepicker-div {
		top: 174px !important;
	}
 
  
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="icon_documents_alt" aria-hidden="true"></i>Form</li>
                    <li><i class="fa fa-list"></i>List Forms</li>
                    <li><i class="fa fa-map-marker" aria-hidden="true"></i>Walkthrough Map</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        <div class="container-fluid">
        <div class="container">
                <form method="GET">
                    <div class="row b5row">
                        <input type="hidden" name="survey_id" value="<?= $survey_id ?>">
                        <div class="col">
                            <select class="form-control" name="user_id" id="user_id">
                                <option value="">Select User</option>
                                <?php
                                $surveyorqry = mysqli_query($conn, "SELECT users.user_id,users.name FROM `users` where role_id in (6,3) and status='0' and registered_as='' $qryUser order by users.name ASC");
                                while ($surveyorget = mysqli_fetch_array($surveyorqry)) { ?>
                                    <option value="<?php echo $surveyorget['user_id'] ?>" <?php if ($surveyorget['user_id'] == $_REQUEST['user_id']) {
                                                                                                echo "selected";
                                                                                            } ?>><?php echo $surveyorget['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    
							<div class="col-lg-2 col-md-4">
								<input type="text" data-placement="top" id="date1" data-toggle="tooltip" data-original-title="From date" class="form-control" title="From date" placeholder="From Date" name="fdate" value="<?php if ($_REQUEST['fdate']) { echo $_REQUEST['fdate'];	} ?>">
							</div>
							<div class="col-lg-2 col-md-4">
								<input type="text" data-placement="top" id="date2" data-toggle="tooltip" data-original-title="To date" class="form-control" title="To date" placeholder="To Date" name="tdate" value="<?php if ($_REQUEST['tdate']) { echo $_REQUEST['tdate']; } ?>">
							</div>
                        <div class="col">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search" disabled>Search</button>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <a href="survey-location.php?survey_id=<?= $survey_id ?>" class="btn btn-primary width-md waves-effect waves-light form-control">Clear Filter</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $survey_id_encode = base64_encode($survey_id);
                    $actual_link = base_url() . "map_share.php?survey_id=" . $survey_id_encode;
                    ?>
                    <section class="panel">
                        <header class="panel-heading">Geographical coverage | Form Name: <?php echo $survey_name; ?>
                            <a href="mailto:?Subject=Geographical coverage&amp;body=You can access your MQUAD: <?php echo $survey_name; ?> by clicking on the URL <?php echo $actual_link; ?>" style="margin:1px;" class="share pull-right"><i class="fa fa-share-alt" aria-hidden="true" style="border:none;"></i> Share</a>

                        </header>
                        <div id="mapCanvas" style="height:400px;width:100%;"></div>
                    </section>
                </div>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
</body>

</html>
<?php
//echo "SELECT survey_data_monitoring_id,survey_id,latitude,longitude,created_on,clients.name as client_name,survey.survey_name,users.username FROM survey_data_monitoring left join clients on survey_data_monitoring.client_id=clients.id left JOIN users ON survey_data_monitoring.user_id=users.user_id LEFT join survey on survey_data_monitoring.survey_name_id=survey.id where survey.del_action='N' and survey.id='" . $survey_id . "' $client_qry $qry";
$result = mysqli_query($conn, "SELECT survey_data_monitoring_id,survey_id,latitude,longitude,created_on,clients.name as client_name,survey.survey_name,users.username FROM survey_data_monitoring left join clients on survey_data_monitoring.client_id=clients.id left JOIN users ON survey_data_monitoring.user_id=users.user_id LEFT join survey on survey_data_monitoring.survey_name_id=survey.id where survey.del_action='N' and survey.id='" . $survey_id . "' $client_qry $qry ");
$result2 = mysqli_query($conn, "SELECT survey_data_monitoring_id,survey_id,latitude,longitude,created_on,clients.name as client_name,survey.survey_name,users.username FROM survey_data_monitoring left join clients on survey_data_monitoring.client_id=clients.id left JOIN users ON survey_data_monitoring.user_id=users.user_id LEFT join survey on survey_data_monitoring.survey_name_id=survey.id where survey.del_action='N' and survey.id='" . $survey_id . "' $client_qry $qry");

?>
<?php include_once('includes/footer.php'); ?>
<!--map start code--->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5KJcNipnIvGqZkcdd2lFtY3elcTViNzU &callback=initMap&sensor=false" async defer></script>
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
                            <?php if ($_SESSION['role_id'] == 1) { ?> '<p style="color:#394A59;">Client Name: <?php echo $row['client_name']; ?></p>' + <?php } ?> '<p style="color:#394A59;">User Name: <?php echo $row['username']; ?></p>' +
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
	/* $(document).ready(function() {
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
	}); */
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

