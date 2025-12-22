<?php include('includes/config.php'); ?>
 <?php define("title","Dashboard | MQUAD");?>
<?php include('includes/header_dashboard.php'); ?>
<?php include('customdashboard/sscharts.php'); ?>

<?php
    $surveyid=mysqli_real_escape_string($conn,$_GET['survey_id']);
    $survey_id=base64_decode($surveyid);
	//echo "select id,user_id from survey where id='".$survey_id."'";
	$getUser=mysqli_query($conn,"select id,user_id from survey where id='".$survey_id."'");
	$dataUser=mysqli_fetch_array($getUser);
	 $user_id=$dataUser['user_id'];
   
 ?>
 <?php
 include('includes/functions.php');
 include_once('api/mycrypt.php');
 if ($_SESSION['enckey'] === 0) {
	 die("You are not authorized to see the survey");
 }
         $getclient = mysqli_query($conn,"SELECT client_id FROM survey WHERE id='".$survey_id."'  ");
		 $dataclientID=mysqli_fetch_object($getclient);
		 get_enc_key($dataclientID->client_id);
		 $mcrypt = new EncryptionUtils($_SESSION['enckey']);
		//$full_jsons = $mcrypt->decrypt($allData['full_json']);

?>
 
<style>
   .c-center-align {
   height: 200px;
   display: flex;
   align-items: center;
   justify-content: center;
   }
	#main-content {
    margin-left: 0px;
}
.toggle-nav {
	display: none;
}
.highcharts-figure,
	.highcharts-data-table table {
	  min-width: 310px;
	  max-width: 800px;
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

.export-thumb.home-export {
	flex-direction: column;
	justify-content: center;
	min-height: 195px;
	text-align: center;
}
.export-thumb.home-export  .thumb-icon i{
	margin-right:0;
	margin-bottom:10px;
}
</style>
<style>
	#main-content .wrapper .row {
		margin-bottom: 0px;
	}

	.panel {
		margin-bottom: 20px;
	}

	.panel .panel-heading {
		margin-top: -1px;
	}

	.coptions,
	.chartTitle {
		display: none;
	}

	#sortable div {
		cursor: move;
	}

	.sk-btn {
		border: 1px solid lightblue;
		margin: 1px;
		border-radius: 5px;
		float: right;
		text-align: center;
	}

	.sk-btn:hover {
		background-color: #394a59;
	}
</style>
<link href="https://mquad.org/mis/css/jquery-ui.min.css" rel="stylesheet" />
<script src="<?= base_url(); ?>assets/highcharts/highcharts.js"></script>
<script src="<?= base_url(); ?>assets/highcharts/highcharts-more.js"></script>
<script src="<?= base_url(); ?>assets/highcharts/exporting.js"></script>
<script src="<?= base_url(); ?>assets/highcharts/export-data.js"></script>
<script src="<?= base_url(); ?>assets/highcharts/accessibility.js"></script>

<section id="main-content">
   <section class="wrapper">
      <div class="row">
         <div class="col-lg-12">
            <ol class="breadcrumb">
               <li><i class="fa fa-laptop"></i>View Dashboard</li>
            </ol>
         </div>
      </div>
	  <?php if ($survey_id != '') { ?>
      <div class="row">
				<div class="col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="info-box oneColor">
						<i class="icon_documents_alt"></i>
						<div class="count">
							<?php
							$surveycollect = mysqli_query($conn, "SELECT count(*) total FROM `survey_data_monitoring` WHERE survey_name_id='" . $survey_id . "'");
							$survey_result = mysqli_fetch_array($surveycollect);
							echo $survey_result['total'];
							?>
							<div class="title"><span style="color:white;">Number of entries collected</span></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="info-box twoColor">
						<i class="fa fa-check-circle" aria-hidden="true"></i>
						<div class="count">
							<?php
							$accsql = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as acc_total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id WHERE survey_status IN (1,6) AND survey_name_id='" . $survey_id . "'");
							$survey_accept = mysqli_fetch_array($accsql);

							$accsqlsend = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as send_for_review FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id WHERE survey_status IN (4) AND survey_name_id='" . $survey_id . "'");
							$survey_send = mysqli_fetch_array($accsqlsend);

							$accsqlter = mysqli_query($conn, "SELECT COUNT(survey_data_monitoring_id) as tot_terminated FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id WHERE survey_status IN (3) AND survey_name_id='" . $survey_id . "'");
							$survey_terminated = mysqli_fetch_array($accsqlter);

							echo '<span class="text-left tooltips" data-bs-placement="right" data-bs-toggle="tooltip" data-bs-html="true" title="<span style=\'color:#449a97;font-weight: normal;\'>Terminated: ' . $survey_terminated['tot_terminated'] . '</span><br><span style=\'color:#449a97;font-weight: normal;\'>Sent for review: ' . $survey_send['send_for_review'] . '</span>">' .
									$survey_accept['acc_total'] .
									'</span>';	
							?>
							<div class="title"><span style="color:white;">Number of entries verified</span></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="info-box threeColor">
						<i class="fa fa-users" aria-hidden="true"></i>
						<div class="count">
							<?php
							$usersql = mysqli_query($conn, "SELECT count(DISTINCT(user_id)) as total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id WHERE survey_name_id='" . $survey_id . "'");
							$data = mysqli_fetch_array($usersql);
							echo $data['total'];
							?>
							<div class="title"><span style="color:white;">Total Users</span></div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-12">
					<div class="info-box fourColor">
						<i class="icon_documents_alt"></i>
						<div class="count">
							<?php
							$today = date('y-m-d');
							$todaysurvey = mysqli_query($conn, "SELECT count(survey_data_monitoring_id) as total_date FROM `survey_data_monitoring` WHERE survey_name_id='" . $survey_id . "' AND date(created_on)='" . $today . "'");
							$data = mysqli_fetch_array($todaysurvey);
							echo $data['total_date'];
							?>
							<div class="title"><span style="color:white;">Number of entries collected today</span></div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<section class="panel">
						<div class="card">
							<?php
							$surveyqry = mysqli_query($conn, "SELECT survey.survey_name,clients.name as client_name,questionnaire_pdf,questinnour_file FROM survey left join clients on clients.id=survey.client_id where survey.id='" . $survey_id . "' order by survey.id DESC");

							$surveydata = mysqli_fetch_array($surveyqry);
							$survey_name = $surveydata['survey_name'];
							$client_name = $surveydata['client_name'];
							$questinnour_file = $surveydata['questinnour_file'];
							$questionnaire_pdf = $surveydata['questionnaire_pdf'];

							?>

							<?php
							$sidencode = base64_encode($survey_id);
							$actual_link = base_url() . "dashboard_share.php?survey_id=" . $sidencode;
							?>
							<header class="panel-heading analysis_dashboard-btn"> Form Name: <?php echo $survey_name; ?> <?php if ($_SESSION['functional_role_id'] != '3') { ?>|| Client Name: <?php echo $client_name; ?> <?php } ?>
							
							</header>
						</div>
					</section>
				</div>

			</div>
			<div class="row" id="">
				<?php
				$sid = $survey_id;
				$uid = $_SESSION['user_id'];
				$i = 0;
				$getCharts = mysqli_query($conn, "SELECT `id`, `title`, `description`, `fieldname`, `groupby`, `colors`, `user_id`, `survey_id`, `chart_name`, `chart_type`, `created_on`, `status`, chart_options FROM `surveydashboard` WHERE  status='0' AND survey_id='" . $sid . "'  ORDER BY sequence ASC ");
				while ($chart = mysqli_fetch_object($getCharts)) {
					$surveyId = $chartName = $fieldname = $groupby = "";
					$surveyId = $chart->survey_id;
					$chartName = $chart->chart_name;
					$chartType = $chart->chart_type;
					$colors = [];
					if (!empty($chart->colors)) {
						$colors = explode(",", $chart->colors);
					}
					$fieldname = $chart->fieldname;
					$groupby = $chart->groupby;
					//echo "hello";
					$chart_options = $chart->chart_options;
				?>
					<div class="col-md-6 chart-box" id="sss-<?= $chart->id; ?>" data-id="<?= $chart->id; ?>">
						<section class="panel ">
							<div class="card rounded-0">
								<div class="panel-heading">
									<div class="pull-left gtitle<?= $chart->id; ?>"><?= $chart->title; ?></div>
									
									<div class="clearfix"></div>
								</div>
								<div class=" ">
									<?php
									$dcharts = dynamic_graph($conn, $surveyId, $chartName, $chartType, $colors, $fieldname, $groupby, "", [], $mcrypt);
									$totalCount = $dcharts['totalCount'];
									echo '<div style="font-size: 18px;margin-left: 5px;">'.$totalCount.'</div>';
									
									$categories = $dcharts['categories'];
									$dataseries = str_replace("'", '"', $dcharts['dataseries']);
									$chart_options_Arr = json_decode($chart_options, true);
									
									$chart_options_Arr['series'] = ['finaldataseries'];
									$modify_chart_options = json_encode($chart_options_Arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
									$final_charts = str_replace('["finaldataseries"]', $dataseries, $modify_chart_options);
									if (!empty($final_charts)) {
										echo '<div id="sss' . $i++ . '"></div>';
										
										echo '<span class="coptions" >' . $final_charts . '</span>';
										echo '<span class="chartTitle" >' . $title . '</span>';
									}
									?>
								</div>
								<div class="panel-footer  border-0">
									<h5 class="panel-title">
										<a class="accordion-toggle" data-bs-toggle="collapse" href="#collapse<?= $chart->id; ?>">
											Description <span class="menu-arrow arrow_carrot-down"></span> <span class="float-end"><i class="fa fa-calendar"></i> <?= date("d-M-Y", strtotime($chart->created_on)); ?></span>
										</a>
									</h5>
									<div id="collapse<?= $chart->id; ?>" class="accordion-collapse   collapse">
										<br>
										<p class="gdesc<?= $chart->id; ?>" style="text-align:justify"><?= $chart->description ?></p>
									</div>
								</div>
							</div>
						</section>
					</div>
				<?php
				}
				?>

			</div>
		<?php } ?>
          
		</div>
	</section>
</section>
<!--main content end-->


<?php include_once('includes/footer.php'); ?> 
<script>
	




	//UPDATE GRAPH TITLE AND DESCRIPTION




	$(document).ready(function() {
		var coptions = [];
		//var titles = [];

		$('.coptions').each(function() {
			coptions.push($(this).text());
		});
		// $('.chartTitle').each(function() {
			// titles.push($(this).text());
		// });

		$.each(coptions, function(k, v) {
			console.log(v);
			let gid = 'sss' + k;
			let goptions = JSON.parse(v);
			//return false;
			generateCharts(gid, goptions);
			// if (goptions['title']) {
				// goptions['title']['text'] = titles[k];
				// generateCharts(gid, goptions);
			// } else {
				// console.error('Title property missing in options:', goptions);
			// }
		});
	});

	function generateCharts(id, options) {
		//console.log(options);
		var SSCHARTS = Highcharts.chart(id, options);
	}
</script>
<script>
	$(document).ready(function() {
		$('[data-bs-toggle="tooltip"]').tooltip();
	});
</script>

<script type="text/javascript" src="<?=base_url();?>js/jspdf.min.js"></script>




