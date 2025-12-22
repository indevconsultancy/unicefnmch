<?php include('includes/config.php'); ?>
<?php define("title", "Home | MQUAD"); ?>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" />

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
			<div class="container-fluid">
				<form class="form-inline" method="get" role="form">
					<div class="row filter_css clearfix">
						<div class="form-group col-md-4" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
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
						<div class="col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
							<div class="form-group">
								<input type="text" name="from_date" id="from_datepicker" value="<?= @$_REQUEST['from_date'] ?>" placeholder="From Date" class="form-control">
							</div>
						</div>
						<div class="col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
							<div class="form-group">
								<input type="text" name="to_date" id="to_datepicker" placeholder="To Date" value="<?= @$_REQUEST['to_date'] ?>" class="form-control">
							</div>
						</div>
						<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
							<button type="submit" class="btn btn-secondary width-md waves-effect waves-light form-control" id="btnsearch" name="search">Search</button>
						</div>
						<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
							<button type="submit" class="btn btn-secondary width-md waves-effect waves-light form-control" name="clear">Clear Filter</button>
						</div>
					</div>
				</form>
			</div>
			<!--------filter end------->
			<div class="container-fluid">
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
<?php } else { ?>
	<div class="container-fluid">
		<div class="filter_css clearfix">
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<section class="panel">
						<div class="panel panel-default">
							<div class="panel-body homemain-icotabs">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="dash-card">
											<div class="dash-head">
												<div class="thumb-icon">
													<div>
														<i class="fa fa-wpforms" aria-hidden="true"></i>
													</div>
												</div>
												<h5 class="title">Project Management</h5>
											</div>
											<div class="dash-body">
												<ul>
													<li>
														<div class="accord-head">
															<a href="add-project.php">
																<i class="fa fa-plus"></i> Add Project
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="project-list.php">
																<i class="fa fa-list-ul"></i> List Project
															</a>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="dash-card">
											<div class="dash-head">
												<div class="thumb-icon">
													<div>
														<i class="fa fa-indent" aria-hidden="true"></i>
													</div>
												</div>
												<h5 class="title">Form</h5>
											</div>
											<div class="dash-body">
												<ul>
													<li>
														<div class="accord-head">
															<a href="add-survey.php">
																<i class="fa fa-plus"></i> Add Form
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="survey-list.php">
																<i class="fa fa-list-ul"></i> List Forms
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="validate_xlsform.php">
																<i class="fa fa-check-circle-o"></i> Validate Excel Form
															</a>
														</div>

													</li>
												</ul>
											</div>
										</div>
									</div>
									<?php if ($_SESSION['role_id'] == 1) { ?>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-handshake-o" aria-hidden="true"></i>
														</div>
													</div>
													<h5 class="title">Client Management</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="registration.php">
																	<i class="fa fa-plus"></i> Add Client
																</a>
															</div>
														</li>
														<li>
															<div class="accord-head">
																<a href="client-list.php">
																	<i class="fa fa-list-ul"></i> List Clients
																</a>
															</div>
														</li>
													</ul>
												</div>
											</div>
										</div>
									<?php } ?>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="dash-card">
											<div class="dash-head">
												<div class="thumb-icon">
													<div>
														<i class="fa fa fa-users" aria-hidden="true"></i>
													</div>
												</div>
												<h5 class="title">User Management</h5>
											</div>
											<div class="dash-body">
												<ul>
													<li>
														<div class="accord-head">
															<a href="add-user.php">
																<i class="fa fa-plus"></i> Add User
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="user-list.php">
																<i class="fa fa-list-ul"></i> List Users
															</a>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="dash-card">
											<div class="dash-head">
												<div class="thumb-icon">
													<div>
														<i class="fa fa-tasks" aria-hidden="true"></i>
													</div>
												</div>
												<h5 class="title">Question Bank</h5>
											</div>
											<div class="dash-body">
												<ul>
													<li>
														<div class="accord-head">
															<a href="add-question-bank.php">
																<i class="fa fa-plus"></i> Add Question
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="my_question.php">
																<i class="fa fa-list-ul"></i>My Questions
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="question-bank-list.php">
																<i class="fa fa-list-ul"></i> Show Questions
															</a>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="dash-card">
											<div class="dash-head">
												<div class="thumb-icon">
													<div>
														<i class="fa fa-wrench" aria-hidden="true"></i>
													</div>
												</div>
												<h5 class="title">Tools Archive</h5>
											</div>
											<div class="dash-body">
												<ul>
													<li>
														<div class="accord-head">
															<a href="add-contribute-surveybank.php">
																<i class="fa fa-plus"></i> Add Tool
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="survey_bank.php">
																<i class="fa fa-list-ul"></i> Show Tool
															</a>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="dash-card">
											<div class="dash-head">
												<div class="thumb-icon">
													<div>
														<i class="fa fa-newspaper-o" aria-hidden="true"></i>
													</div>
												</div>
												<h5 class="title">Data Repository</h5>
											</div>
											<div class="dash-body">
												<ul>
													<li>
														<div class="accord-head">
															<a href="add-contribute-databank.php">
																<i class="fa fa-plus"></i> Add Dataset
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="data_bank.php">
																<i class="fa fa-list-ul"></i> Show Dataset
															</a>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="dash-card">
											<div class="dash-head">
												<div class="thumb-icon">
													<div>
														<i class="fa fa-braille" aria-hidden="true"></i>
													</div>
												</div>
												<h5 class="title">Sampling</h5>
											</div>
											<div class="dash-body">
												<ul>
													<li>
														<div class="accord-head">
															<a href="simple-random-samling.php">
																<i class="fa fa-object-group"></i> Simple Random Sampling
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="systematic_random.php">
																<i class="fa fa-object-ungroup"></i> Systematic Random Sampling
															</a>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>

									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
										<div class="dash-card">
											<div class="dash-head">
												<div class="thumb-icon">
													<div>
														<i class="fa fa-search" aria-hidden="true"></i>
													</div>
												</div>
												<h5 class="title">Resources</h5>
											</div>
											<div class="dash-body">
												<ul>
													<li>
														<div class="accord-head">
															<a href="help_file.php">
																<i class="fa fa-info-circle"></i> Help
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="faqs.php">
																<i class="fa fa-question-circle-o"></i> Frequently Asked Question
															</a>
														</div>
													</li>
													<li>
														<div class="accord-head">
															<a href="#">
																<i class="fa fa-wechat"></i>MQUAD Community
															</a>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<?php if ($_SESSION['role_id'] == 1) { ?>
										<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-cogs" aria-hidden="true"></i>
														</div>
													</div>
													<h5 class="title">Settings</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li class="hasChild">
															<div class="accord-head">

																<i class="fa fa-info-circle"></i>Thematic Area
																<span class="tgltab"></span>
															</div>
															<div class="accord-body">
																<div class="inner-boxes">
																	<a href="category-list.php">
																		<i class="fa fa-list-ul"></i> List Thematic Area </a>
																</div>
															</div>
														</li>
														<li class="hasChild">
															<div class="accord-head">
																<i class="fa fa-pain-brush"></i> Theme
																<span class="tgltab"></span>
															</div>
															<div class="accord-body">
																<div class="inner-boxes">
																	<a href="theme-list.php">
																		<i class="fa fa-list-ul"></i>List Theme
																	</a>
																</div>
															</div>
														</li>
													</ul>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
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
								'<p>Survey Name: <?php echo $row['survey_name']; ?></p>' +
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