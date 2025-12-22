 
<?php include('../includes/config.php'); ?>
<?php define("title", "Dashboard | MQUAD"); ?>
<?php include('../includes/header.php'); ?>
<?php include('../includes/left-sidebar.php'); ?>
<?php //include('includes/functions.php'); 
?>
<?php
$survey_id = $_GET['survey_id'];

?>
<style>
	.qField>input[type="checkbox"] {
		margin: 0px;
	}

	#searchQuestion::placeholder {
		color: green;
		font-weight: bold;
	}

	#searchQuestionGroupBy::placeholder {
		color: green;
		font-weight: bold;
	}

	.list-group-item {
		position: relative;
		display: block;
		padding: 5px 10px;
		margin-bottom: -1px;
		background-color: #ffffff;
		border: 1px solid #d7d7d7 !important;
	}


	#customizeGraphColor .modal-content {
		border-radius: 0px;
		background: rgb(255 255 255 / 96%);
	}


	table tr th{
		font-size: 14px !important;
		font-weight: 600 !important;
	}
	
	#customeColors {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
	}

	#customeColors .form-group {
		display: flex;
		align-items: center;

		background: #fff;
		padding: 8px;
		justify-content: space-between;
		box-shadow: 0px 0px 10px #dad9d9;
		border-radius: 4px;
		width: 49%;
	}

	#customeColors .form-group:nth-child(odd) {
		margin-right: 6px;
	}

	#customeColors .form-group label {
		margin: 0;
		min-width: 160px;
	}

	#customeColors .form-group input[type="color"] {
		border-color: #d7d7d7;
		background: #fff;
		padding: 0 1px;
		border-radius: 4px;
		overflow: hidden;
		width: 50px;
		height: 27px;
	}

	.alreadySelect {
		background-color: lightseagreen;
		color: white !important;
		cursor: no-drop;
		pointer-events: none;

	}

	.r90 {
		rotate: 90deg;
	}

	#main-content .wrapper .row {
		margin-bottom: 0px;
	}

	.panel {
		margin-bottom: 10px;
	}

	
</style>

<section id="main-content">
	<section class="wrapper">

		<div class="row">
			<div class="col-lg-12">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><i class="icon_documents_alt"></i>Form</li>
						<li class="breadcrumb-item"><i class="icon_document_alt"></i>List Form</li>
						<li class="breadcrumb-item active" aria-current="page"><i class="fa fa-laptop"></i> Dashboard </li>
					</ol>
				</nav>
			</div>
		</div>


		<div class="row">
			<div class="col-md-12">
				<section class="panel">
					<?php
					$surveyqry = mysqli_query($conn, "SELECT survey_name,clients.name as client_name, clients.id as clientId FROM `survey` left join clients on survey.client_id=clients.id where survey.id='" . $survey_id . "'");
					$surveydata = mysqli_fetch_array($surveyqry);
					$survey_name = $surveydata['survey_name'];
					$client_name = $surveydata['client_name'];
					$clientId = $surveydata['clientId'];
					?>
					<header class="panel-heading"> Survey Name: <?php echo $survey_name; ?></header>
				</section>
			</div>
		</div>
		<div class="row survey-graph-div">

			<div class="col-md-3 mb-3">
				<section class="panel">
					<header class="panel-heading">Questions</header>
					<input type="text" class="form-select rounded-0" id="searchQuestion" placeholder="Search Questions  " />
					<div class="list-group rounded-0 border-0" id="formQuestions" style="max-height: 450px; overflow: auto;">
						<input type="hidden" id="questionFieldName" />
						<input type="hidden" id="questionName" />
						<input type="hidden" id="surveyId" value="<?php echo $_GET['survey_id']; ?>" />
						<input type="hidden" id="cid" value="<?php echo $clientId; ?>" />

						<a class="list-group-item qField" data-id="user_id" href="javascript:void(0);"> User Wise</a>
						<a class="list-group-item qField" data-id="survey_edate" href="javascript:void(0);"> Date Wise</a>
						<?php
						$getQuestions = mysqli_query($conn, "SELECT question_id, question_name, dictionary_label, field_name,input_field_type  FROM questions_language WHERE survey_id='" . $survey_id . "' AND question_name!='' AND language_id='1' AND input_field_type!='note' and repeated=''");
						//while($question = mysqli_fetch_object($getQuestions)){ 
						$allQuestions = mysqli_fetch_all($getQuestions, MYSQLI_ASSOC);
						foreach ($allQuestions as $question) {
						?>
							<a class="list-group-item qField" data-id="<?= $question['field_name']; ?>" href="javascript:void(0);"> <?= $question['dictionary_label'] ? $question['dictionary_label'] : $question['question_name']; ?></a>
						<?php
						}
						?>
					</div>
				</section>
			</div>
			
			<div class="col-md-7 mb-3" >
				<section class="panel">
				
					<header class="panel-heading">
						You can design a dashboard as you want.
						<a href="javascript:" data-bs-toggle="modal" data-bs-target="#chartCustomiseModal" class="badge bg-white badge-primary customModal" style="float: right; margin-top: 8px;" data-bs-backdrop="static" data-bs-keyboard="false" data-whatever="@fat">Customize & Save</a>
						<input type="hidden" id="graph_custom_color" />
						<input type="hidden" id="datalbl" />
						<input type="hidden" id="legendColors" />
					</header>
					
					<div class="panel-body" id="ssid">
					<span class="totalCount" style="font-size:18px;"></span>
						<img id="loading-image" src="<?= base_url(); ?>loader.gif" style="display: none;width: 100%;" />
						<div id="sschartarea" style="height:450px">
						
						</div>
					</div>

					<!--
			    <div class="btn-group btn-group-justified" >
                  <a class="btn btn-primary" href="#">COUNT</a>
                  <a class="btn btn-danger" href="#">SUM</a>
                  <a class="btn btn-info" href="#">AVERAGE</a>
                </div>
				-->
				</section>
			</div>

			<div class="col-md-2 mb-3"  >
				<div class="accordion mb-2" id="accordionExample">
					<div class="accordion-item">
						<h4 class="accordion-header" id="headingOne">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="font-size: 13px!important;">
								Single Variable Charts
							</button>
						</h4>
						<div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
							<div class="accordion-body p-0">
								<div class="list-group rounded-0 border-0">
									<a class="list-group-item" href="javascript:;" onclick="createChart('basicline')"><i class="fa fa-line-chart"></i> Line</a>
									<!--<a class="list-group-item" href="javascript:;" onclick="createChart('tableAdvance','tableAdvance')"><i class="fa fa-bar-chart"></i> Table</a>-->
									
									<a class="list-group-item" href="javascript:;" onclick="createChart('basiccolumn')"><i class="fa fa-bar-chart"></i> Column</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('basicbar')"><i class="fa fa-bar-chart r90"></i> Bar</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('pie')"><i class="fa fa-pie-chart"></i> Pie</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('basicarea')"><i class="fa fa-area-chart"></i> Area</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="accordion mb-2" id="accordionExample">
					<div class="accordion-item">
						<h4 class="accordion-header" id="headingTwo">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="font-size: 13px!important;">
								Two Variable Charts
							</button>
						</h4>
						<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
							<div class="accordion-body p-0" style="height: 180px; overflow-y: auto">
								<div class="list-group rounded-0 border-0">
									<a class="list-group-item" href="javascript:;" onclick="createChart('basicClumnAdvance','column')"><i class="fa fa-bar-chart"></i> Column</a>
									<!--<a class="list-group-item" href="javascript:;" onclick="createChart('tableAdvance','bar')"><i class="fa fa-table"></i> Table</a>-->
									<a class="list-group-item" href="javascript:;" onclick="createChart('basicClumnAdvance','line')"><i class="fa fa-line-chart"></i> Line</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('basicClumnAdvance','spline')"><i class="fa fa-line-chart"></i> Spline</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('basicClumnAdvance','bar')"><i class="fa fa-align-left"></i> Bar</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('basicClumnAdvance','area')"><i class="fa fa-area-chart"></i> Area</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('stackClumnAdvance','column')"><i class="fa fa-bar-chart"></i> Stack Column</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('stackClumnAdvance100','column')"><i class="fa fa-bar-chart"></i> Stack 100%</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('splitBubbleAdvance','column')"><i class="fa fa-bar-chart"></i> Bubble</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('RadialAdvance','column')"><i class="fa fa-bar-chart"></i> Radial Column</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('RadialAdvance','bar')"><i class="fa fa-align-left"></i> Radial Bar</a>
									
									<a class="list-group-item" href="javascript:;" onclick="createChart('spiderAdvance','line')"><i class="fa fa-line-chart"></i> Spider Line</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('spiderAdvance','spline')"><i class="fa fa-line-chart"></i> Spider Spline</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('spiderAdvance','column')"><i class="fa fa-bar-chart"></i> Spider Column</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('spiderAdvance','bar')"><i class="fa fa-align-left"></i> Spider Bar</a>
									<a class="list-group-item" href="javascript:;" onclick="createChart('spiderAdvance','area')"><i class="fa fa-area-chart"></i> Spider Area</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="mb-2" id="accordion">
					<section class="panel">
						<div class="card">
							<header class="panel-heading">Group By</header>
							<input type="text" class="form-select rounded-0" id="searchQuestionGroupBy" placeholder="Search Questions" />
							<div class="list-group rounded-0 border-0" id="formQuestionsGroupBy" style="height: 213px; overflow-y: scroll;">
								<input type="hidden" id="gquestionFieldName" />
								<input type="hidden" id="gquestionName" />
								<input type="hidden" id="surveyIdGroupBy" value="<?php echo $survey_id = filter_var($_GET['survey_id'], FILTER_VALIDATE_INT); ?>" />

								<a class="list-group-item qFieldGroupBy" data-id="user_id" href="javascript:void(0);">User Wise</a>
								<a class="list-group-item qFieldGroupBy" data-id="survey_edate" href="javascript:void(0);">Date Wise</a>

								<?php
								foreach ($allQuestions as $questionGby) {
								?>
									<a class="list-group-item qFieldGroupBy" data-id="<?= $questionGby['field_name']; ?>" href="javascript:void(0);">
										<?= $questionGby['dictionary_label'] ? $questionGby['dictionary_label'] : $questionGby['question_name']; ?>
									</a>
								<?php
								}
								?>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</section>
</section>
<!--main content end-->

<?php include_once('custom_modal.php'); ?>
 
<?php include_once('../includes/footer.php'); ?>

<link href="<?= base_url(); ?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>assets/sweetalerts/sweetalert2.all.min.js"></script>
<script>
	$(function() {

		$('#searchQuestion').keyup(function() {
			var searchText = $(this).val();
			$('#formQuestions a').each(function() {
				var currentLiText = $(this).text(),
					showCurrentLi = currentLiText.indexOf(searchText) !== -1;
				$(this).toggle(showCurrentLi);
			});
		});
		$('#searchQuestionGroupBy').keyup(function() {
			var searchText = $(this).val();
			$('#formQuestionsGroupBy a').each(function() {
				var currentLiText = $(this).text(),
					showCurrentLi = currentLiText.indexOf(searchText) !== -1;
				$(this).toggle(showCurrentLi);
			});
		});

		///save graph details
		$("#savegraphdetails").on("click", function() {
			var graph_title = $("#graph_title").val();
			//var xtitle = $("#xaxTitle").val();
			//var ytitle = $("#yaxTitle").val();
			var graph_title = $("#graph_title").val();
			var graph_description = $("#graph_description").val();
			var graph_survey_id = $("#surveyId").val();
			var graph_fieldname = $("#questionFieldName").val();
			var graph_group_field_name = $("#gquestionFieldName").val();
			//var legendColors = $("#sschartarea #glegend_color").val(); //$("#legendColors").val();
			var graph_chart_name = localStorage.getItem("chartType");
			var graph_chart_type = localStorage.getItem("cType");
			var copt = JSON.stringify(customChartOptions);

			if (graph_title != "") {
				$.ajax({
					url: "<?= base_url(); ?>customdashboard/ssajax.php",
					type: "post",
					data: {
						graph_title: graph_title,
						//xtitle:xtitle,
						//ytitle:ytitle,
						graph_description: graph_description,
						graph_survey_id: graph_survey_id,
						graph_fieldname: graph_fieldname,
						graph_group_field_name: graph_group_field_name,
						graph_chart_name: graph_chart_name,
						graph_chart_type: graph_chart_type,
						customChartOptions: copt,
						//legendColors:legendColors
					},
					success: function(response) {
						//console.log(response);
						//$('#savegraph').modal('hide');
						//$("#xtitle").val('');
						//$("#ytitle").val('');
						$("#graph_title").val('');
						$("#graph_description").val('');
						customAlert('Graph save successfully.');
						let redirectUrl = "<?= base_url() ?>" + "customdashboard/analysis_dashboard.php?survey_id=" + graph_survey_id + "&search="
						window.location.href = redirectUrl;
					}
				})
			} else {
				$("#graph_title_err").show();
			}

		})


	});

	/*
	function customAlert(msg,icon='success'){
		const Toast = Swal.mixin({
		  toast: true,
		  position: 'top-end',
		  showConfirmButton: false,
		  timer: 3000,
		  timerProgressBar: true,
		  didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		  }
		})

		Toast.fire({
		  icon: icon,
		  title: msg
		})
	}
	*/
</script>

<script>
	$(".qField").on("click", function() {
		//$(this).append('<i class="fa fa-check-circle"></i>');
		$(".sscheck").remove();
		$(this).append(' <i class="fa fa-check sscheck"></i>');
		var qfName = $(this).data("id");
		var qName = $(this).text();
		$("#questionFieldName").attr("value", qfName);
		$("#questionName").attr("value", qName);

		$(".alreadySelect").removeClass(" alreadySelect");
		$('#formQuestionsGroupBy [data-id="' + qfName + '"]').addClass(" alreadySelect");

	});


	$(".qFieldGroupBy").on("click", function() {
		//$(this).append('<i class="fa fa-check-circle"></i>');
		$(".groupcheck").remove();
		$(this).append(' <i class="fa fa-check groupcheck"></i>');
		var gqfName = $(this).data("id");
		var gqName = $(this).text();
		$("#gquestionFieldName").attr("value", gqfName);
		$("#gquestionName").attr("value", gqName);
	});
</script>

<div id="chartStr" class="d-none"></div>


<script type="text/javascript">
	$("#chngcolor").hide();
	$("#graphsavebtn").hide();

	function createChart(chartType, cType = "") {
		var name = "Satyendra";
		var questionFName = $("#questionFieldName").val();
		var surveyId = $("#surveyId").val();
		var gTitle = $("#questionName").val();
		var cid = $("#cid").val();

		var gquestionFName = $("#gquestionFieldName").val();
		var ggTitle = $("#gquestionName").val();
		localStorage.setItem("chartType", chartType);
		localStorage.setItem("cType", cType);
		var legendColors = $("#legendColors").val();
		if (questionFName != "") {
			$.ajax({
				type: "POST",
				url: "<?= base_url(); ?>customdashboard/ssajax.php",
				data: {
					chartType: chartType,
					cType: cType,
					questionFName: questionFName,
					surveyId: surveyId,
					cid: cid,
					gTitle: gTitle,
					gquestionFName: gquestionFName,
					ggTitle: ggTitle,
					legendColors: legendColors
				},
				beforeSend: function() {
					$("#loading-image").show();
				},
				success: function(res) {
					console.log(res);
					 //console.log("kk"+res);
					if (chartType === 'tableAdvance') {
                            $("#sschartarea").html(res);
                        } else {
							 let ress = JSON.parse(res);
							let totalCount = ress.totalCount;
							$(".totalCount").html(totalCount);
							let coptions = ress.chartOptions;
							$("#chartStr").html(coptions);
                           generateCharts(customChartOptions);
                        }
					
					$("#loading-image").hide();

 
					// $("#xaxTitle").val($("#sschartarea #xTitle").val());
					// $("#yaxTitle").val($("#sschartarea #yTitle").val());

				}
			});
		} else {
			customAlert('Please select at least one question.', 'warning');
		}


		$("#chngcolor").show();
		$("#graphsavebtn").show();
	}

	function generateCharts(options) {
		//console.log(options);
		var SSCHARTS = Highcharts.chart('sschartarea', options);
	}
</script>


<script>
	$("#chngcolor").on("click", function() {
		var glegend_color = $("#sschartarea #glegend_color").val().split(',');
		var glegend_lbl = $("#sschartarea #glegend_lbl").val().split(',');


		$("#customeColors").html('');
		$.each(glegend_lbl, function(index, value) {
			$("#customeColors").append('<div class="form-group"> <label>' + value + '</label> <input type="color" class="legendColor" value="' + glegend_color[index] + '"> </div>');
		});
	});

	$("#setColor").on("click", function() {
		var legendColor = $(".legendColor").map((i, el) => el.value).get().join(',');
		$("#legendColors").attr("value", legendColor);
		var chrt = localStorage.getItem("chartType");
		var chrtp = localStorage.getItem("cType");
		$('#customizeGraphColor').modal('hide');
		createChart(chrt, chrtp);
	})
</script>

 
<script src="<?= base_url(); ?>assets/highcharts/highcharts.js"></script>
<script src="<?= base_url(); ?>assets/highcharts/highcharts-more.js"></script>
<script src="<?= base_url(); ?>assets/highcharts/exporting.js"></script>
<script src="<?= base_url(); ?>assets/highcharts/export-data.js"></script>
<script src="<?= base_url(); ?>assets/highcharts/accessibility.js"></script>


<!--<script src="<?= base_url(); ?>customdashboard/cchart.js"></script>-->
<script src="<?= base_url(); ?>customdashboard/sscharts.js"></script>