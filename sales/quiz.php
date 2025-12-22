<!DOCTYPE html>

<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="">
	<meta name="author" content="Indev">
	<meta name="robots" content="">
	<meta name="description" content="">
	<meta name="format-detection" content="telephone=no">

	<!-- FAVICONS ICON -->
	<link rel="icon" href="https://qcc.uacuae.com/exam/images/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" type="image/x-icon" href="https://qcc.uacuae.com/exam/images/favicon.png">

	<!-- PAGE TITLE HERE -->
	<title>Assessment Initiation | GAGAN</title>

	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	</head><body id="bg" onload="start_webcam_on_page_load()"><button id="action" onclick="handleAction()" style="display:none; color:white;">Record Audio</button>

	<!--[if lt IE 9]>
	<script src="js/html5shiv.min.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	<!-- STYLESHEETS -->
	<script src="quiz/combining.js.download"></script><!-- COMBINING JS  -->
	<link rel="stylesheet" type="text/css" href="quiz/plugins.min.css">
	<link rel="stylesheet" type="text/css" href="quiz/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="quiz/themify-icons.css">
	<link rel="stylesheet" type="text/css" href="quiz/style.min.css">
	<link rel="stylesheet" type="text/css" href="quiz/templete.min.css">
	<link class="skin" rel="stylesheet" type="text/css" href="quiz/skin-1.min.css">
	<link href="quiz/css2" rel="stylesheet">
	<script src="quiz/camvas.js.download"></script>
	<script src="quiz/pico.js.download"></script>
	<script src="quiz/lploc.js.download"></script>
	<script src="quiz/audio_script.js.download"></script>
	<!--<script src='https://code.responsivevoice.org/responsivevoice.js'></script>-->
	<!--<script src='https://code.responsivevoice.org/responsivevoice.js?key=auvTMQpf'></script>-->
	<script src="quiz/responsivevoice.js.download"></script>

	<style type="text/css">
		.modal-backdrop {
			z-index: 100 !important;
		}

		/* custom radio design */
		/* End custom radio design onload="win_onkeydown_handler()" */
	</style>

<style>
	.button-success,
	.button-error {
		background: #3a78b8;
		color: #ffffff;
		padding: 5px;
		padding-left: 4px;
		padding-right: 4px;
		font-size: 15px;
		font-family: 'arial';
		border-radius: 4px;
		border: 1px solid #87b0d9;
		margin-bottom: 4px;
		line-height: 34px;
	}

	div.count_btn {
		cursor: pointer;
		float: left;
		width: 38px;
		background: #212121;
		color: #ffffff;
		margin: 3px;
		padding: 4px;
		font-size: 18px;
		font-family: 'arial';
		border-radius: 3px;
	}
</style>
<style>
	#output {
		padding: 10px;
		width: 100%;
		border-radius: 3px;
		height: 53px;
		box-shadow: 0 0 7px #005;
	}

	#notify {
		padding: 10px;
		width: 100%;
		border-radius: 3px;
		box-shadow: 0 0 7px #005;
	}

	canvas {
		-webkit-transform: scaleX(-1);
		transform: scaleX(-1);
	}
</style>
<script>
	var student_id;
	var batch_id;

	function start_webcam_on_page_load() {
		$("#startcam").triggerHandler("click");
		student_id = "5602";
		batch_id = "2272";
	}
</script>

<!--<script>    function handleAction() {
			  const handleAction = async () => {
			  const recorder = await recordAudio();
			  const actionButton = document.getElementById('action');
			  actionButton.disabled = true;
			  recorder.start();
			  await sleep(20000);
			  const audio = await recorder.stop();
			  //audio.play();
			  //await sleep(20000);
			  actionButton.disabled = false;
			}
		  }
		</script>-->

<script>
	var recognition = new webkitSpeechRecognition() ||
		root.mozSpeechRecognition ||
		root.msSpeechRecognition ||
		root.oSpeechRecognition ||
		root.SpeechRecognition;
	recognition.continuous = true;
	//recognition.interimResults = true;

	var auto_refresh_stt = setInterval(
		function start() {
			recognition.onresult = function(event) {
				//var output = document.getElementById("output");
				//console.log(output);
				//output.innerHTML = "";
				var acbutton = document.getElementById("action");
				if (acbutton.disabled == false) {
					$("#action").triggerHandler("click");
					console.log("Button Click");
				}
				/*
				for (var i = 0; i < event.results.length; i++) {
					output.innerHTML = output.innerHTML + event.results[i][0].transcript;
				}
				var data = JSON.stringify({ speech_text: output.innerHTML, student_id: student_id, batch_id: batch_id });
				fetch("save_speech.php",
				{
					method: "POST",
					body: data
				})*/
			}
			recognition.start();
		}, 3000);
</script>
<script>
	var initialized = false;
	var coutperson = 0;
	var sscounts = 0;

	function button_callback() {
		var y = document.getElementById("stopcam");
		y.style.display = "inline";
		var x = document.getElementById("startcam");
		if (x.style.display === "none") {
			x.style.display = "block";
		} else {
			x.style.display = "none";
		}
		if (initialized)
			return;
		var update_memory = pico.instantiate_detection_memory(5);
		var facefinder_classify_region = function(r, c, s, pixels, ldim) {
			return -1.0;
		};
		var cascadeurl = 'https://raw.githubusercontent.com/nenadmarkus/pico/c2e81f9d23cc11d1a612fd21e4f9de0921a5d0d9/rnt/cascades/facefinder';
		//var cascadeurl = 'https://qcc.uacuae.com/exam/facefinder';
		fetch(cascadeurl).then(function(response) {
			response.arrayBuffer().then(function(buffer) {
				var bytes = new Int8Array(buffer);
				facefinder_classify_region = pico.unpack_cascade(bytes);
				console.log('* facefinder loaded');
			})
		})
		var do_puploc = function(r, c, s, nperturbs, pixels, nrows, ncols, ldim) {
			return [-1.0, -1.0];
		};
		var puplocurl = 'https://f002.backblazeb2.com/file/tehnokv-www/posts/puploc-with-trees/demo/puploc.bin';
		//var puplocurl = 'https://qcc.uacuae.com/exam/puploc.bin'
		fetch(puplocurl).then(function(response) {
			response.arrayBuffer().then(function(buffer) {
				var bytes = new Int8Array(buffer);
				do_puploc = lploc.unpack_localizer(bytes);
				console.log('* puploc loaded');
			})
		})
		var ctx = document.getElementsByTagName('canvas')[0].getContext('2d');

		function rgba_to_grayscale(rgba, nrows, ncols) {
			var gray = new Uint8Array(nrows * ncols);
			for (var r = 0; r < nrows; ++r)
				for (var c = 0; c < ncols; ++c)
					gray[r * ncols + c] = (2 * rgba[r * 4 * ncols + 4 * c + 0] + 7 * rgba[r * 4 * ncols + 4 * c + 1] + 1 * rgba[r * 4 * ncols + 4 * c + 2]) / 10;
			return gray;
		}

		var processfn = function(video, dt) {
			ctx.drawImage(video, 0, 0);
			var rgba = ctx.getImageData(0, 0, 640, 480).data;
			image = {
				"pixels": rgba_to_grayscale(rgba, 480, 640),
				"nrows": 480,
				"ncols": 640,
				"ldim": 640
			}
			params = {
				"shiftfactor": 0.1,
				"minsize": 100,
				"maxsize": 1000,
				"scalefactor": 1.1
			}

			dets = pico.run_cascade(image, facefinder_classify_region, params);
			dets = update_memory(dets);
			dets = pico.cluster_detections(dets, 0.0);

			var rz = '';
			
				var can = document.getElementsByTagName('canvas')[0]
				var img = new Image();
				img.src = can.toDataURL('image/jpeg', 1.0);
				var data = JSON.stringify({
					image: img.src,
					len: dets.length,
					student_id: student_id,
					batch_id: batch_id,
					coutperson: coutperson
				});
				
				
			/*
			if (dets.length == 0) {
				rz = 'No Face on Camera';
				coutperson = coutperson + 2;
			fetch("save_image.php", {
						method: "POST",
						body: data
					})
					.then(function(res) {
						return res.json();
					})
					.then(function(data) {
						return data.message
					})
			}
			if (dets.length == 0) {
				rz = 'No Face on Camera';
				coutperson = coutperson + 2;
			}
			if (dets.length >= 1) {
				rz = ' Persons Identified' + dets.length;
			}
           */
			///////////////////////////////////////////////////
			//var rz = '';

			if (dets.length == 0) {
				rz = 'No Face on Camera';
				coutperson = coutperson + 2;
			}
			
			if (dets.length >= 1) {
				if (dets.length == 1) {
					coutperson = 1;
					rz = ' Persons Identified :1';
				}
				else if (dets.length >= 2) {
					coutperson = coutperson + 2;
					rz = 'Persons Identified : ' + dets.length;
				}
				//console.log(coutperson);
				if (coutperson > 150) {
					rz = 'Persons Identified : ' + dets.length;
					
					fetch("save_image.php", {
						method: "POST",
						body: data
					})
					.then(function(res) {
						return res.json();
					})
					.then(function(data) {
						return data.message
					})
					if (coutperson > 200) {
					coutperson=0;
				}
				}
			}
            if(sscounts>400)
				{
				fetch("save_image.php", {
						method: "POST",
						body: data
					})
					.then(function(res) {
						return res.json();
					})
					.then(function(data) {
						return data.message
					})
					sscounts=0;
				}
			document.getElementById("notify").innerHTML = rz;

			for (i = 0; i < dets.length; ++i) {
				if (dets[i][3] > 50.0) {
					var r, c, s;
					ctx.beginPath();
					ctx.arc(dets[i][1], dets[i][0], dets[i][2] / 2, 0, 2 * Math.PI, false);
					ctx.lineWidth = 3;
					ctx.strokeStyle = 'red';
					ctx.stroke();

					r = dets[i][0] - 0.075 * dets[i][2];
					c = dets[i][1] - 0.175 * dets[i][2];
					s = 0.35 * dets[i][2];
					[r, c] = do_puploc(r, c, s, 63, image)
					if (r >= 0 && c >= 0) {
						ctx.beginPath();
						ctx.arc(c, r, 1, 0, 2 * Math.PI, false);
						ctx.lineWidth = 3;
						ctx.strokeStyle = 'red';
						ctx.stroke();
					}

					r = dets[i][0] - 0.075 * dets[i][2];
					c = dets[i][1] + 0.175 * dets[i][2];
					s = 0.35 * dets[i][2];
					[r, c] = do_puploc(r, c, s, 63, image)
					if (r >= 0 && c >= 0) {
						ctx.beginPath();
						ctx.arc(c, r, 1, 0, 2 * Math.PI, false);
						ctx.lineWidth = 3;
						ctx.strokeStyle = 'red';
						ctx.stroke();
					}
					
				}
			}
			sscounts++;
		}
		
		var mycamvas = new camvas(ctx, processfn);
		initialized = true;
	}
</script>
<script>
	function refreshPage() {
		window.location.reload();
	}
</script>
<!--<script>
	var auto_refresh = setInterval(
		function (){
			$('#notify').load('data.php').fadeIn("slow");
		}, 30000); 
</script>-->

<!-- onload="start_webcam_on_page_load()"-->


	
	<div class="page-wraper">
		<!-- header -->
		<header class="site-header mo-left header fullwidth" style="height: 0px;">
    <!-- main header -->

    <!-- main header END -->
</header>		<div class="page-content">
			<div class="content-block">

				<div class="section-full content-inner bg-white">
					<div class="container">
						<div class="row">
							<div class="col-xl-12 col-lg-12 m-b30">
								<div class="job-bx job-profile">
									<form id="msform" action="result-info.php?quiz_id=MjI3Mg==&amp;bid=OTI4$lang=" method="post" onsubmit="showquestion(&#39;0&#39;);">

										<div class="row">
											<!--<div class="col-md-12">
											<div class="job-title-bx section-head">
												<div class="mr-auto">
													<h4>Test  Subham (CAN005602)</h4>
												</div>
												<hr>
											</div>
										</div>-->
											<div class="col-md-12">
												<div class="job-title-bx section-head">
													<div class="row">
														<div class="col-md-9">
															<div class="mr-auto">
																<h4>Test  Subham (CAN005602) </h4>
															</div>

														</div>

													</div>
													<hr>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-9">
												<!--<div class="row-bottom custom-margin">
												<h2 class="detail-title mb-0">Gas Operation Technician</h2>
											</div>!-->
												<div class="mockup-test-container">
																										<input type="hidden" id="json_category_range" value="[[&quot;0&quot;],[&quot;1&quot;],[&quot;2&quot;],[&quot;3&quot;],[&quot;4&quot;],[&quot;5&quot;],[&quot;6&quot;],[&quot;7&quot;],[&quot;8&quot;],[&quot;9&quot;],[&quot;10&quot;],[&quot;11&quot;],[&quot;12&quot;],[&quot;13&quot;],[&quot;14&quot;],[&quot;15&quot;],[&quot;16&quot;],[&quot;17&quot;],[&quot;18&quot;],[&quot;19&quot;]]">

																												<input type="hidden" value="0" id="current_cate">
													
													<input type="hidden" value="19" id="total_cate">
													<input type="hidden" value="5602" name="candidate_id">
													<input type="hidden" value="" name="cart_id">
													<input type="hidden" value="6254" name="user_id">
													<input type="hidden" value="2272" name="rid" id="rid">
													<input type="hidden" value="1" name="language_id" id="language_id">

																																										<input type="hidden" value="0" id="current_question">
																																																									<fieldset id="ques0" class="showquestion">
																<div class="row">
																	<input type="hidden" name="q_type0" value="1" id="question_type0">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q1. </span>
																			
																			What is the purpose of following Material Safety Data Sheets (MSDS)?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;What is the purpose of following Material Safety Data Sheets (MSDS)&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG1 | <b>OS</b>: Familiarity with the requirements and standards of occupational Health and Safety | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Medium ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To provide information on the pricing of materials </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="0" id="op-0-0" name="answers0" value="10957" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To provide information on the pricing of materials&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To ensure safe handling, storage, and disposal of materials </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="0" id="op-0-1" name="answers0" value="10958" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To ensure safe handling, storage, and disposal of materials&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To offer tips on creative uses of materials </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="0" id="op-0-2" name="answers0" value="10959" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To offer tips on creative uses of materials&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To recommend alternative materials to use </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="0" id="op-0-3" name="answers0" value="10960" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To recommend alternative materials to use&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(0); showquestion(&#39;1&#39;,&#39;1&#39;);changecategory(&#39;1&#39;);update_curr_ans(&#39;0&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="https://qcc.uacuae.com/exam/quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;0&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="1" id="current_question">
																																																									<fieldset id="ques1" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type1" value="1" id="question_type1">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q2. </span>
																			
																			Why is the use of personal protective equipment (PPE)?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;Why is the use of personal protective equipment (PPE)&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG2 | <b>OS</b>: Familiarity with the requirements and standards of occupational Health and Safety | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To reduce the risk of injury or exposure to hazards </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="1" id="op-1-0" name="answers1" value="11009" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To reduce the risk of injury or exposure to hazards&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To make the work more complicated and time-consuming </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="1" id="op-1-1" name="answers1" value="11010" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To make the work more complicated and time-consuming&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p>  To adhere to fashion trends and set a professional image </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="1" id="op-1-2" name="answers1" value="11011" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot; To adhere to fashion trends and set a professional image&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To avoid any form of physical activity while working </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="1" id="op-1-3" name="answers1" value="11012" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To avoid any form of physical activity while working&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;0&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(1); showquestion(&#39;2&#39;,&#39;1&#39;);changecategory(&#39;2&#39;);update_curr_ans(&#39;1&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;1&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="2" id="current_question">
																																																									<fieldset id="ques2" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type2" value="7" id="question_type2">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q3. </span>
																			
																			While following the occupational health and safety [OHS] guidelines if you find the following sign then what does it indicates?

																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;While following the occupational health and safety [OHS] guidelines if you find the following sign then what does it indicates&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																	<img src="quiz/question_image_202402130601.png" style="height:200px; width:200px">
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG3 | <b>OS</b>: Familiarity with the requirements and standards of occupational Health and Safety | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Hard ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Mandatory action </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="2" id="op-2-0" name="answers2" value="8383" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Mandatory action&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Warning </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="2" id="op-2-1" name="answers2" value="8384" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Warning&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Safe place </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="2" id="op-2-2" name="answers2" value="8385" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Safe place&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Prohibition </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="2" id="op-2-3" name="answers2" value="8386" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Prohibition&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;1&#39;,&#39;7&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(2); showquestion(&#39;3&#39;,&#39;7&#39;);changecategory(&#39;3&#39;);update_curr_ans(&#39;2&#39;,&#39;7&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;7&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;2&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="3" id="current_question">
																																																									<fieldset id="ques3" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type3" value="1" id="question_type3">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q4. </span>
																			
																			Why is it important to inform the line manager about situations involving potential risks?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;Why is it important to inform the line manager about situations involving potential risks&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG4 | <b>OS</b>: Familiarity with the requirements and standards of occupational Health and Safety | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Medium ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To overwhelm the line manager with unnecessary information </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="3" id="op-3-0" name="answers3" value="11121" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To overwhelm the line manager with unnecessary information&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To address the situation independently without involving others </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="3" id="op-3-1" name="answers3" value="11122" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To address the situation independently without involving others&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To prove ones dedication to the job </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="3" id="op-3-2" name="answers3" value="11123" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To prove ones dedication to the job&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To ensure timely intervention and prevent harm to individuals and property </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="3" id="op-3-3" name="answers3" value="11124" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To ensure timely intervention and prevent harm to individuals and property&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;2&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(3); showquestion(&#39;4&#39;,&#39;1&#39;);changecategory(&#39;4&#39;);update_curr_ans(&#39;3&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;3&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="4" id="current_question">
																																																									<fieldset id="ques4" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type4" value="1" id="question_type4">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q5. </span>
																			
																			What should you do if you encounter a task that requires safety gear you don t have?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;What should you do if you encounter a task that requires safety gear you don t have&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG17 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Proceed without the safety gear </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="4" id="op-4-0" name="answers4" value="10510" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Proceed without the safety gear&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p>  Use your imagination to create makeshift safety gear </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="4" id="op-4-1" name="answers4" value="10511" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot; Use your imagination to create makeshift safety gear&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Bypass the task and proceed to the next task </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="4" id="op-4-2" name="answers4" value="10512" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Bypass the task and proceed to the next task&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Stop working and request the required safety gear </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="4" id="op-4-3" name="answers4" value="10513" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Stop working and request the required safety gear&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;3&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(4); showquestion(&#39;5&#39;,&#39;1&#39;);changecategory(&#39;5&#39;);update_curr_ans(&#39;4&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;4&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="5" id="current_question">
																																																									<fieldset id="ques5" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type5" value="1" id="question_type5">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q6. </span>
																			
																			What should you do if there are any discrepancies or changes needed on the work order after its been signed?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;What should you do if there are any discrepancies or changes needed on the work order after its been signed&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG16 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Ignore the issues and continue with the process </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="5" id="op-5-0" name="answers5" value="11531" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Ignore the issues and continue with the process&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Consult with the relevant parties, make necessary changes, and obtain new signatures </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="5" id="op-5-1" name="answers5" value="11532" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Consult with the relevant parties, make necessary changes, and obtain new signatures&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Ask the engineer to approve the changes without additional signatures </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="5" id="op-5-2" name="answers5" value="11533" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Ask the engineer to approve the changes without additional signatures&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Erase the signature and fix the discrepancies </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="5" id="op-5-3" name="answers5" value="11534" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Erase the signature and fix the discrepancies&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;4&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(5); showquestion(&#39;6&#39;,&#39;1&#39;);changecategory(&#39;6&#39;);update_curr_ans(&#39;5&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;5&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="6" id="current_question">
																																																									<fieldset id="ques6" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type6" value="1" id="question_type6">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q7. </span>
																			
																			Why is a leak test using soap spray conducted on valve joints after rectification/repair/replacement?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;Why is a leak test using soap spray conducted on valve joints after rectification/repair/replacement&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG15 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To make the valves look clean </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="6" id="op-6-0" name="answers6" value="11491" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To make the valves look clean&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To identify potential leaks by observing soap bubbles </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="6" id="op-6-1" name="answers6" value="11492" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To identify potential leaks by observing soap bubbles&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To create a pleasant fragrance in the area </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="6" id="op-6-2" name="answers6" value="11493" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To create a pleasant fragrance in the area&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To check the color change of the soap when in contact with valves </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="6" id="op-6-3" name="answers6" value="11494" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To check the color change of the soap when in contact with valves&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;5&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(6); showquestion(&#39;7&#39;,&#39;1&#39;);changecategory(&#39;7&#39;);update_curr_ans(&#39;6&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;6&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="7" id="current_question">
																																																									<fieldset id="ques7" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type7" value="1" id="question_type7">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q8. </span>
																			
																			What is the purpose of the method of statement?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;What is the purpose of the method of statement&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG14 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> It provides step-by-step instructions for carrying out a task safely and correctly </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="7" id="op-7-0" name="answers7" value="11315" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;It provides step-by-step instructions for carrying out a task safely and correctly&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> It s a document that is never used in practice </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="7" id="op-7-1" name="answers7" value="11316" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;It s a document that is never used in practice&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Its a guideline that can be ignored if you re experienced </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="7" id="op-7-2" name="answers7" value="11317" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Its a guideline that can be ignored if you re experienced&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Its only for administrative purposes </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="7" id="op-7-3" name="answers7" value="11318" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Its only for administrative purposes&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;6&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(7); showquestion(&#39;8&#39;,&#39;1&#39;);changecategory(&#39;8&#39;);update_curr_ans(&#39;7&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;7&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="8" id="current_question">
																																																									<fieldset id="ques8" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type8" value="7" id="question_type8">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q9. </span>
																			
																			The following P&amp;ID Symbol represents which of the following? 
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;The following P&amp;ID Symbol represents which of the following&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																	<img src="quiz/question_image_202402201025.png" style="height:200px; width:200px">
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG13 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Butterfly valve </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="8" id="op-8-0" name="answers8" value="11233" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Butterfly valve&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Ball Valve </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="8" id="op-8-1" name="answers8" value="11234" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Ball Valve&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Globe valve </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="8" id="op-8-2" name="answers8" value="11235" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Globe valve&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Needle valve </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="8" id="op-8-3" name="answers8" value="11236" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Needle valve&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;7&#39;,&#39;7&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(8); showquestion(&#39;9&#39;,&#39;7&#39;);changecategory(&#39;9&#39;);update_curr_ans(&#39;8&#39;,&#39;7&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;7&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;8&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="9" id="current_question">
																																																									<fieldset id="ques9" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type9" value="1" id="question_type9">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q10. </span>
																			
																			What should you observe while conducting the leak test using soap spray?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;What should you observe while conducting the leak test using soap spray&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG12 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Color changes of the soap spray </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="9" id="op-9-0" name="answers9" value="11675" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Color changes of the soap spray&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Formation of soap bubbles around the valve joints </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="9" id="op-9-1" name="answers9" value="11676" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Formation of soap bubbles around the valve joints&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Vaporization of the soap </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="9" id="op-9-2" name="answers9" value="11677" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Vaporization of the soap&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Vibration of the valves </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="9" id="op-9-3" name="answers9" value="11678" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Vibration of the valves&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;8&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(9); showquestion(&#39;10&#39;,&#39;1&#39;);changecategory(&#39;10&#39;);update_curr_ans(&#39;9&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;9&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="10" id="current_question">
																																																									<fieldset id="ques10" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type10" value="1" id="question_type10">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q11. </span>
																			
																			What should be the first step before conducting a visual inspection of gas equipment and pipes?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;What should be the first step before conducting a visual inspection of gas equipment and pipes&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG11 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Hard ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Disconnecting all gas supply </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="10" id="op-10-0" name="answers10" value="11379" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Disconnecting all gas supply&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Conducting a pressure test </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="10" id="op-10-1" name="answers10" value="11380" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Conducting a pressure test&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Putting on appropriate personal protective equipment (PPE) </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="10" id="op-10-2" name="answers10" value="11381" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Putting on appropriate personal protective equipment (PPE)&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Notifying the local fire department </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="10" id="op-10-3" name="answers10" value="11382" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Notifying the local fire department&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;9&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(10); showquestion(&#39;11&#39;,&#39;1&#39;);changecategory(&#39;11&#39;);update_curr_ans(&#39;10&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;10&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="11" id="current_question">
																																																									<fieldset id="ques11" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type11" value="1" id="question_type11">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q12. </span>
																			
																			 What is the purpose of a gas analyzer tool?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;What is the purpose of a gas analyzer tool&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG10 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To measure the speed of gas flow. </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="11" id="op-11-0" name="answers11" value="11319" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To measure the speed of gas flow.&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To analyze the composition of gases in a system </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="11" id="op-11-1" name="answers11" value="11320" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To analyze the composition of gases in a system&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To generate invoices for gas consumption </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="11" id="op-11-2" name="answers11" value="11321" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To generate invoices for gas consumption&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> To provide weather forecasts for gas-related operations </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="11" id="op-11-3" name="answers11" value="11322" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;To provide weather forecasts for gas-related operations&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;10&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(11); showquestion(&#39;12&#39;,&#39;1&#39;);changecategory(&#39;12&#39;);update_curr_ans(&#39;11&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;11&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="12" id="current_question">
																																																									<fieldset id="ques12" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type12" value="8" id="question_type12">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q13. </span>
																			
																			Which tool is commonly used to detect and locate gas leaks?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;Which tool is commonly used to detect and locate gas leaks&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG9 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Hard ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<img src="quiz/option_image8_202402201031Operation 1.JPG" style="height:200px; width:200px">
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="12" id="op-12-0" name="answers12" value="11237" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<img src="quiz/option_image8_202402201031Operation 2.JPG" style="height:200px; width:200px">
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="12" id="op-12-1" name="answers12" value="11238" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<img src="quiz/option_image8_202402201031operation3.JPG" style="height:200px; width:200px">
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="12" id="op-12-2" name="answers12" value="11239" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<img src="quiz/option_image8_202402201031operation4.JPG" style="height:200px; width:200px">
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="12" id="op-12-3" name="answers12" value="11240" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;11&#39;,&#39;8&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(12); showquestion(&#39;13&#39;,&#39;8&#39;);changecategory(&#39;13&#39;);update_curr_ans(&#39;12&#39;,&#39;8&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;8&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;12&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="13" id="current_question">
																																																									<fieldset id="ques13" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type13" value="1" id="question_type13">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q14. </span>
																			
																			When receiving a work order for branch maintenance, what is the technicians first step?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;When receiving a work order for branch maintenance what is the technicians first step&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG8 | <b>OS</b>: Duties & Responsibilities to ensure an effective performance of the job | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Medium ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Begin maintenance immediately </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="13" id="op-13-0" name="answers13" value="11217" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Begin maintenance immediately&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Gather required tools and equipment </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="13" id="op-13-1" name="answers13" value="11218" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Gather required tools and equipment&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Contact another technician for assistance </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="13" id="op-13-2" name="answers13" value="11219" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Contact another technician for assistance&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Ignore the work order </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="13" id="op-13-3" name="answers13" value="11220" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Ignore the work order&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;12&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(13); showquestion(&#39;14&#39;,&#39;1&#39;);changecategory(&#39;14&#39;);update_curr_ans(&#39;13&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;13&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="14" id="current_question">
																																																									<fieldset id="ques14" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type14" value="1" id="question_type14">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q15. </span>
																			
																			How can a leader ensure that the right tasks are assigned to the right team members?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;How can a leader ensure that the right tasks are assigned to the right team members&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG20 | <b>OS</b>: Work efficiency in a team | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> By assigning tasks based on personal preferences </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="14" id="op-14-0" name="answers14" value="11643" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;By assigning tasks based on personal preferences&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> By considering team members capabilities and aligning tasks with their skills </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="14" id="op-14-1" name="answers14" value="11644" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;By considering team members capabilities and aligning tasks with their skills&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> By avoiding any form of task assignment </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="14" id="op-14-2" name="answers14" value="11645" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;By avoiding any form of task assignment&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> By assigning tasks randomly without any consideration </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="14" id="op-14-3" name="answers14" value="11646" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;By assigning tasks randomly without any consideration&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;13&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(14); showquestion(&#39;15&#39;,&#39;1&#39;);changecategory(&#39;15&#39;);update_curr_ans(&#39;14&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;14&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="15" id="current_question">
																																																									<fieldset id="ques15" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type15" value="1" id="question_type15">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q16. </span>
																			
																			Why is setting clear goals important for a teams success?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;Why is setting clear goals important for a teams success&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG19 | <b>OS</b>: Work efficiency in a team | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Goals are not necessary; teams can work without them </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="15" id="op-15-0" name="answers15" value="11631" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Goals are not necessary; teams can work without them&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Setting goals creates unnecessary stress for team members </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="15" id="op-15-1" name="answers15" value="11632" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Setting goals creates unnecessary stress for team members&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Teams are better off without goals to avoid conflicts </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="15" id="op-15-2" name="answers15" value="11633" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Teams are better off without goals to avoid conflicts&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Clear goals provide a sense of direction and purpose, helping to align efforts toward a common objective </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="15" id="op-15-3" name="answers15" value="11634" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Clear goals provide a sense of direction and purpose, helping to align efforts toward a common objective&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;14&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(15); showquestion(&#39;16&#39;,&#39;1&#39;);changecategory(&#39;16&#39;);update_curr_ans(&#39;15&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;15&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="16" id="current_question">
																																																									<fieldset id="ques16" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type16" value="1" id="question_type16">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q17. </span>
																			
																			What does it mean to share knowledge within a team?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;What does it mean to share knowledge within a team&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG18 | <b>OS</b>: Work efficiency in a team | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Hiding information to gain an advantage </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="16" id="op-16-0" name="answers16" value="11615" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Hiding information to gain an advantage&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Communicating openly and transparently about ones expertise and insights </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="16" id="op-16-1" name="answers16" value="11616" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Communicating openly and transparently about ones expertise and insights&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Keeping all knowledge to oneself </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="16" id="op-16-2" name="answers16" value="11617" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Keeping all knowledge to oneself&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Only sharing knowledge with friends within the team </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="16" id="op-16-3" name="answers16" value="11618" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Only sharing knowledge with friends within the team&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;15&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(16); showquestion(&#39;17&#39;,&#39;1&#39;);changecategory(&#39;17&#39;);update_curr_ans(&#39;16&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;16&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="17" id="current_question">
																																																									<fieldset id="ques17" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type17" value="1" id="question_type17">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q18. </span>
																			
																			We should always greet the client and client representative with?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;We should always greet the client and client representative with&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG5 | <b>OS</b>: Maintain standard of etiquette and hospitable conduct | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Anger </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="17" id="op-17-0" name="answers17" value="11125" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Anger&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Smile </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="17" id="op-17-1" name="answers17" value="11126" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Smile&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Sad </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="17" id="op-17-2" name="answers17" value="11127" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Sad&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> All the Above </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="17" id="op-17-3" name="answers17" value="11128" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;All the Above&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;16&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(17); showquestion(&#39;18&#39;,&#39;1&#39;);changecategory(&#39;18&#39;);update_curr_ans(&#39;17&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;17&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="18" id="current_question">
																																																									<fieldset id="ques18" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type18" value="1" id="question_type18">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q19. </span>
																			
																			Whats the appropriate way to handle confidential customer information?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;Whats the appropriate way to handle confidential customer information&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG6 | <b>OS</b>: Maintain standard of etiquette and hospitable conduct | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Medium ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Share it with colleagues if necessary </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="18" id="op-18-0" name="answers18" value="11149" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Share it with colleagues if necessary&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Keep it confidential at all times </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="18" id="op-18-1" name="answers18" value="11150" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Keep it confidential at all times&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Share it for personal gain </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="18" id="op-18-2" name="answers18" value="11151" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Share it for personal gain&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Use it to negotiate better deals </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="18" id="op-18-3" name="answers18" value="11152" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Use it to negotiate better deals&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;17&#39;,&#39;1&#39;);">Back</a>
																																																		<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick=" savefData(18); showquestion(&#39;19&#39;,&#39;1&#39;);changecategory(&#39;19&#39;);update_curr_ans(&#39;18&#39;,&#39;1&#39;);"> Save &amp; Next</a>
																																	&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;18&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

																																											<input type="hidden" value="19" id="current_question">
																																																									<fieldset id="ques19" class="hidequestion">
																<div class="row">
																	<input type="hidden" name="q_type19" value="1" id="question_type19">
																	<div class="col-md-11">
																		<h3 class="question-heading">
																			<span>Q20. </span>
																			
																			How do we handle customer complaints or concerns?
																
																		</h3>
																	</div>
																	<div class="col-md-1" style="margin-top:15px">
																		<div class="option-serial">
																			
																			<input onclick="responsiveVoice.speak(&quot;How do we handle customer complaints or concerns&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																				</div>
																	</div>
																</div>
																																<!--<span> [ <b>Element Group</b>: Operation Technician EG7 | <b>OS</b>: Maintain standard of etiquette and hospitable conduct | <b>Marks</b>:5  | <b>Alloted Time</b>: 2  | <b>Difficulty Level</b>: Easy ]</span>-->

																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>A</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Ignore them </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="19" id="op-19-0" name="answers19" value="11173" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Ignore them&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>B</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Address them promptly and professionally </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="19" id="op-19-1" name="answers19" value="11174" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Address them promptly and professionally&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>C</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Blame the customer </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="19" id="op-19-2" name="answers19" value="11175" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Blame the customer&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																	<div class="row">
																		<div class="col-md-11">
																			<div class="option-box">
																				<div class="option-serial">
																					<span>D</span>
																				</div>
																				<div class="option-value">
																					<div class="option-data">
																																													<p> Redirect them to another department </p>
																																											</div>
																					<div class="option-check">
																						<label>
																							<input type="radio" class="option-input radio" data-id="19" id="op-19-3" name="answers19" value="11176" onclick="">
																						</label>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="col-md-1" style="margin-top:15px">
																																					<div class="option-serial">
																				
																						<input onclick="responsiveVoice.speak(&quot;Redirect them to another department&quot;, &quot;UK English Female&quot;,{rate: 0.85});" type="button" value="🔊">
																																							</div>
																																				</div>
																	</div>
																																<hr class="m-t20">

																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="showquestion(&#39;18&#39;,&#39;1&#39;);">Back</a>
																																																	<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-success" onclick="savefData(19);"> Save</a>

																																&nbsp;

																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-warning" onclick="reviewlater(&#39;1&#39;);"> Review later </a>
																&nbsp;
																<a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="btn btn-info" onclick="clearresponse(&#39;19&#39;);"> Clear-Response </a>
																&nbsp; <a href="javascript:pre_sbtform();" class="btn btn-danger">Submit Test</a>															</fieldset>

														
													<fieldset>
														<div class="form-card">
															<div class="row">
																<div class="col-12">
																	<h1 class="text-center text-success">Thank You!</h1>
																	<p class="text-center">Assessment has been completed successfully.</p>
																	<a href="javascript:pre_sbtform();" class="btn btn-danger" style="cursor:pointer;">Cancel</a> &nbsp; &nbsp; &nbsp; &nbsp;
																	<a href="javascript:sbtform();" class="btn btn-info" style="cursor:pointer;">Submit Quiz</a>
																</div>
															</div>
														</div>
													</fieldset>
													<div id="warning_div" style="padding:10px; position:fixed;z-index:100;display:none;width:100%;border-radius:5px;height:200px; border:1px solid #dddddd;left:4px;top:70px;background:#ffffff;">
														<center><b>Do you really want to submit Quiz? </b><br><br>
															<a href="javascript:pre_sbtform();" class="btn btn-danger" style="cursor:pointer;">Cancel</a> &nbsp; &nbsp; &nbsp; &nbsp;
															<a href="javascript:sbtform();" class="btn btn-info" style="cursor:pointer;">Submit Quiz</a>
														</center>
													</div>

												</div>
											</div>


											<div class="col-md-3" style="margin-top: -108px;">
												<div class="d-flex align-items-center justify-content-between">
													<div>
														<h4 class="font-19 m-0">Time Left:</h4>
													</div>
													<div id="countdown" class="countdownHolder"><span class="countDays"><span class="position">					<span class="digit static" style="top: 0px; opacity: 1;">0</span>				</span>				<span class="position">					<span class="digit static" style="top: 0px; opacity: 1;">0</span>				</span></span><span class="countDiv countDiv0"></span><span class="countHours"><span class="position">					<span class="digit static" style="top: 0px; opacity: 1;">0</span>				</span>				<span class="position">					<span class="digit static" style="top: 0px; opacity: 1;">0</span>				</span></span><span class="countDiv countDiv1"></span><span class="countMinutes"><span class="position">					<span class="digit static" style="top: 0px; opacity: 1;">3</span>				</span>				<span class="position">					<span class="digit static" style="top: 0px; opacity: 1;">6</span>				</span></span><span class="countDiv countDiv2"></span><span class="countSeconds"><span class="position">					<span class="digit static" style="top: 0px; opacity: 1;">3</span>				</span>				<span class="position">					<span class="digit" style="top: -27.7107px; opacity: 0.175276;">8</span>				</span></span></div>
												</div>
												<p id="note" class="font-12" style="margin-bottom: 54px;">0 hours, 36 minutes and 38 seconds left from now!</p>
												<div class="preparedness-box">
													<h4>Question Palette</h4>
													<hr>
													<div class="questons-info">
																													<div class="qinfo" id="nq0" onclick="savefData(0); showquestion(&#39;0&#39;);">
																1															</div>
																													<div class="qinfo" id="nq1" onclick="savefData(1); showquestion(&#39;1&#39;);">
																2															</div>
																													<div class="qinfo" id="nq2" onclick="savefData(2); showquestion(&#39;2&#39;);">
																3															</div>
																													<div class="qinfo" id="nq3" onclick="savefData(3); showquestion(&#39;3&#39;);">
																4															</div>
																													<div class="qinfo" id="nq4" onclick="savefData(4); showquestion(&#39;4&#39;);">
																5															</div>
																													<div class="qinfo" id="nq5" onclick="savefData(5); showquestion(&#39;5&#39;);">
																6															</div>
																													<div class="qinfo" id="nq6" onclick="savefData(6); showquestion(&#39;6&#39;);">
																7															</div>
																													<div class="qinfo" id="nq7" onclick="savefData(7); showquestion(&#39;7&#39;);">
																8															</div>
																													<div class="qinfo" id="nq8" onclick="savefData(8); showquestion(&#39;8&#39;);">
																9															</div>
																													<div class="qinfo" id="nq9" onclick="savefData(9); showquestion(&#39;9&#39;);">
																10															</div>
																													<div class="qinfo" id="nq10" onclick="savefData(10); showquestion(&#39;10&#39;);">
																11															</div>
																													<div class="qinfo" id="nq11" onclick="savefData(11); showquestion(&#39;11&#39;);">
																12															</div>
																													<div class="qinfo" id="nq12" onclick="savefData(12); showquestion(&#39;12&#39;);">
																13															</div>
																													<div class="qinfo" id="nq13" onclick="savefData(13); showquestion(&#39;13&#39;);">
																14															</div>
																													<div class="qinfo" id="nq14" onclick="savefData(14); showquestion(&#39;14&#39;);">
																15															</div>
																													<div class="qinfo" id="nq15" onclick="savefData(15); showquestion(&#39;15&#39;);">
																16															</div>
																													<div class="qinfo" id="nq16" onclick="savefData(16); showquestion(&#39;16&#39;);">
																17															</div>
																													<div class="qinfo" id="nq17" onclick="savefData(17); showquestion(&#39;17&#39;);">
																18															</div>
																													<div class="qinfo" id="nq18" onclick="savefData(18); showquestion(&#39;18&#39;);">
																19															</div>
																													<div class="qinfo" id="nq19" onclick="savefData(19); showquestion(&#39;19&#39;);">
																20															</div>
																												<input type="hidden" name="noq" id="noq" value="20">
													</div>
													<hr>
													<hr>
													<div>
														<p id="notify">No Face on Camera</p>
													</div>
													<center>
														<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
															<i class="fa fa-eye" alt="Camera" title="Camera"></i> Show Live Camera
														</button>
													</center>
													<div style="display:none; color:white;">
														<!--<button id="action" onclick="handleAction()" style="display:none; color:white;">Record Audio</button>-->

														<input id="bStart" type="button" value="Speech to Text" onclick="start();" style="display:none; color:white;">
														<br>
														<input type="button" value="Start Webcam" id="startcam" onclick="button_callback()" class="btn btn-success" style="display: none;">
														&nbsp;
														<button type="button" onclick="refreshPage()" id="stopcam" class="btn btn-danger" style="display: inline; color: white;">Stop Webcam</button>
														<hr>
														<div>
															<p id="output"></p>
														</div>
														<hr>
														<h5 style="display:none; color:white;"><b>Candidate's Physical Movements:</b></h5>
														<hr>
														<div>
															<p id="notify"></p>
														</div>
													</div>
												</div>
											</div>

										</div>
									</form>

								</div>
							</div>
						</div>
					</div>
				</div>

				
	<!-- Footer -->
    <footer class="site-footer" style="display: block; height: 42px;">
        <!-- footer bottom part -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 text-left">
						<span> © Copyright 2023. All rights reserved.</span> 
					</div>
                    <div class="col-lg-6 text-right">
						<span> <a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="text-white">Privacy Policy</a> | <a href="quiz.php?bid=OTI4&amp;cart=NTE5Mg==&amp;jrid=Mjk=&amp;quiz=MjI3Mg==&amp;lang=MQ==#" class="text-white">Terms &amp; Conditions</a></span> 
					</div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer END -->
    <!-- scroll top button -->
    <button class="scroltop fa fa-arrow-up"></button>			</div>
		</div>
	</div>
	<!-- JAVASCRIPT FILES ========================================= -->
	<!-- Button trigger modal -->

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Secure Eye</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<center><canvas style="float:left" width="640" height="480"></canvas></center>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="ltime" value="1717967344">
	<input type="hidden" id="oldltime" value="0">
	<script src="quiz/basics.js.download"></script><!-- COMBINING JS  -->

	<script>

var currentAudio = null;
$(".urdAudio").on("click", function(){
    var urls = $(this).data("id");
    var audurls = urls;
    var audio = new Audio(audurls);

    if (currentAudio !== null && !currentAudio.paused) {
        currentAudio.pause();
    }

    currentAudio = audio;
    audio.play();
});


	// $(".urdAudio").on("click", function(){
		
	// 	var urls=$(this).data("id");
	// 	//var audurls='https://qcc.uacuae.com/exam/Urdu/'+urls;
	// 	var audurls=urls;
	// 	const audio = new Audio(audurls);
	// 	audio.play();
	// 	// audios.forEach(a => {
	// 		// if (a !== audio) {
	// 			// a.pause();
	// 		// }
	// 	// });
				
	// });
		// $(".urdAudio").on("click", function(){
		
	// 	var urls=$(this).data("id");
	// 	var audurls=urls;
	// 	console.log(audurls);
	// 	const audio = new Audio(audurls);
	// 	audio.play();
	// });
	
	/*This is fine*/
	$(".urdLng").on("click", function(){
		
		var txt=$(this).data("id");
	
		console.log('RUN');
		
		$.ajax({
			url:"ssdemo.php",
			type:"post",
			data:{txt:txt},
			dataType:"json",
			error: function(xhr, status, error) {
			  alert(xhr.responseText);
			},
			success:function(res){
				console.log(res.url);
				//playAudio(res.url)
				var audurls='https://qcc.uacuae.com/exam/Urdu/'+res.url;
				const audio = new Audio(audurls);
				audio.play();
			}
		})
	 });
/*This is fine*/


// var currentAudio = null; 

// $(".urdLng").on("click", function(){
//     var txt = $(this).data("id");

//     if (currentAudio !== null && !currentAudio.paused) {
//         currentAudio.pause();
//     }

//     $.ajax({
//         url: "ssdemo.php",
//         type: "post",
//         data: { txt: txt },
//         dataType: "json",
//         error: function(xhr, status, error) {
//             alert(xhr.responseText);
//         },
//         success: function(res) {
//             var audUrl = 'https://qcc.uacuae.com/exam/Urdu/' + res.url;
//             currentAudio = new Audio(audUrl);
//             currentAudio.play();
//         }
//     });
// });

	
		function savefData(sqid) {
			var lastCecked = $("input[type='radio']:checked:last").data("id");
			//alert(ddd);

			var sqid = sqid + 1;
			var json_category_range = $("#json_category_range").val();
			var rid = $("#rid").val();
			var ltime = $("#ltime").val();
			var oldltime = $("#oldltime").val();
			var language_id = $("#language_id").val();

			console.log(ltime);
			var obj = JSON.parse(json_category_range);
			var myarray = [];
			$.each(obj, function(key, val) {
				// console.log(val[0]);
				var qseq = val[0];
				var oId = 'answers' + qseq;
				var optValue = $('input[name=' + oId + ']:checked').val();
				if (optValue === undefined) {
					optValue = 0;
				}
				myarray.push(optValue);
			});
			var arrString = myarray.join(",");

			$.ajax({
				//url: "https://qcc.uacuae.com/exam/ssajax.php",
				type: "post",
				data: {
					rid: rid,
					ltime: ltime,
					lastCecked: lastCecked,
					sqid: sqid,
					arrString: arrString,
					oldltime: oldltime,
					language_id: language_id
				},
				success: function(data) {
					console.log('sk');
					console.log(data);
				}
			});
		}
	</script>

	<script>
		$('#english').show();
		$('#hindi').hide();

		function hideShow() {
			var selectedValue = $('#language_id1').val();
			//alert(selectedValue);						

			if (selectedValue == '1') {
				$('#english').show();
			} else {
				$('#english').hide();
			}

			if (selectedValue == '2') {
				$('#hindi').show();
			} else {
				$('#hindi').hide();
			}
		}
	</script>
	<script>
		$(document).ready(function() {
			$(function() {
				var note = $('#note'),
					ts = new Date(2012, 0, 1),
					newYear = true;
				var qtime = 40;
				//alert(qtime);
				if ((new Date()) > ts) {
					// The new year is here! Count towards something else.
					// Notice the *1000 at the end - time must be in milliseconds		
					ts = (new Date()).getTime() + 1000 * 60 * qtime;
					newDate = false;
				}

				$('#countdown').countdown({
					timestamp: ts,
					callback: function(days, hours, minutes, seconds) {

						var message = "";
						message += hours + " hour" + (hours == 1 ? '' : 's') + ", ";
						message += minutes + " minute" + (minutes == 1 ? '' : 's') + " and ";
						message += seconds + " second" + (seconds == 1 ? '' : 's') + " ";
						if ((new Date()).getTime() >= ts) {
							message += "Time Completed";
							alert('Time is Over');
							document.getElementById('msform').submit();
						}
						if (newDate) {
							message += "Time Completed";
						} else {
							message += "left from now!";
						}

						note.html(message);
					}
				});
			});

		});
		/*
		$(window).on('keydown', win_onkeydown_handler);
		function win_onkeydown_handler() {
		    switch (event.keyCode) {

		    case 116 : // 'F5'
		         event.returnValue = false;
		         event.keyCode = 0;
		         break;
				 
		    case 122 : // 'Full Screen'
		         event.returnValue = false;
		         event.keyCode = 0;
		         break;		 

		    case 27: // 'Esc'
		        event.returnValue = false;
		        event.keyCode = 0;
		        break;

		    case 08: // 'BackSpace'
		        if (event.srcElement.tagName == "INPUT"
		                || event.srcElement.tagName == "TEXTAREA") {
		        } else {
		            event.returnValue = false;
		            event.keyCode = 0;
		        }
		        break;

		    }
		}
		*/
	</script>

	<!--<script language="javascript">
document.onmousedown=disableclick;
status="Right Click Disabled";
Function disableclick(event)
{
  if(event.button==2)
   {
     alert(status);
     return false;    
   }
}
</script>-->
	<script language="javascript">
		function pre_sbtform() {
			if ((document.getElementById('warning_div').style.display) == "block") {
				document.getElementById('warning_div').style.display = "none";
			} else {
				document.getElementById('warning_div').style.display = "block";
			}
		}

		function sbtform() {
			document.getElementById('msform').submit();
		}
	</script>
	<script>
		function close_practice() {
			window.location = "</script><div><video autoplay="1" playsinline="1" width="1" height="1"></video></div></body></html>