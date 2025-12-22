<?php include_once('includes/config.php'); ?>
 <?php define("title","Form View | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<style>
.required{
	color:red;
}
</style>
<script src="js/jquery.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<link href="css/select2-bootstrap.min.css" rel="stylesheet" />
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <h3 class="page-header"><i class="fa fa fa-bars"></i> Pages</h3> -->
                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>
                        <li><i class="icon_documents_alt"></i>Form</li>
                        <li><i class="fa fa-bars"></i>Form View</li>
                    </ol>
                </div>
            </div>
            <!-- page start-->
            <div class="row">
                <div class="col-sm-12">
					<?php
						if(isset($_POST['updateData'])){
							//echo "<pre>";
							$survey_id = $_POST['survey_id'];
							$survey_data_monitoring_id = $_REQUEST['id'];
							//$survey_name = getone($conn,"survey","survey_name","id",$survey_id);
							// $digits = 11;
							// $uniq_survey_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
							$user_id = $_SESSION['user_id'];
							$user_type_id = $_SESSION['user_type_id'];
							$cluster_code = "12345";
							array_pop($_POST);
							
							$full_arr['user_id'] = $user_id;
							$full_arr['survey_id'] = $survey_id;
							$full_arr['survey_status'] = "1";
							$full_arr['cluster_no'] = $cluster_code;
							$full_arr['reason_of_change'] = "";
							$full_arr['GPS_latitude_start'] = "";
							$full_arr['GPS_longitude_start'] = "";
							
							$qtype = $_POST['qtype'];
							$questions = $_POST['questions'];
							//print_r($qtype);
							unset($_POST['qtype']);
							unset($_POST['questions']);
							unset($_POST['survey_id']);
							//print_r($_POST);
							
							foreach($_POST as $key=>$posted_value){
								$input_field_type = $qtype[$key];
								$question_id = $questions[$key];
								$field_name = $key;
								$inputValue = $posted_value;
								//$explode=explode('@',$inputValue);
								
								
								$option_id="";
								$option_value ="";
								$display_value="";
								if($input_field_type=="select_one" || $input_field_type=="select_multiple "){
									$option_id = $inputValue;
									$where = "where question_id='".$question_id."' and option_sequence='".$option_id."' ";
									$display_value = getdata($conn,"options","option_name",$where);//getone($conn,'questions','question_name','field_name',$key)
								}else{
									$option_value = $inputValue;
									$display_value = $inputValue;
								}
								
								$post_data["field_name"] = $field_name;
								$post_data["option_id"] = $option_id;
								$post_data["option_value"] = $option_value;
								$post_data["pre_field"] = "0";
								$post_data["question_id"] = $question_id;
								$post_data["input_field_type"] = $input_field_type;
								$postData[] = $post_data;
								
								
								$surveydataArr[$field_name] = $display_value;
								//$sdArr = $surveydataArr;
							}
							//print_r($surveydataArr);
							$full_arr['survey_data'] = $postData;
							$full_json = json_encode($full_arr);
							//echo $full_json;
							$survey_data_json = json_encode($surveydataArr);
							$update = mysqli_query($conn,"UPDATE survey_data_monitoring SET survey_id='".$survey_id."', user_id='".$user_id."', survey_data_json='".$survey_data_json."', full_json='".$full_json."', cluster_code='".$cluster_code."', survey_status='1', user_type='".$user_type_id."' WHERE survey_data_monitoring_id='".$survey_data_monitoring_id."' ");
							if($update){
								echo "<script>alert('Survey Added Successfully..!');window.location.href='survey-data-list.php'</script>";
							}
						}
					?>
					
					<?php
						if(isset($_POST['submitdata'])){
							echo "<pre>";
							//$survey_id = $_POST['survey_id'];
							$comment = $_POST['comment'];
							$survey_name = $_POST['survey_name'];
							$survey_name_id = $_POST['survey_name_id'];
							$client_id = $_POST['client_id'];
							$survey_data_monitoring_id = $_REQUEST['id'];
							//$survey_name = getone($conn,"survey","survey_name","id",$survey_id);
							
							$digits = 11;
							$uniq_survey_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
							$user_id = $_SESSION['user_id'];
							$user_type_id = $_SESSION['role_id'];
							$cluster_code = "12345";
							if($_POST['submitdata']=="accept"){
								$survey_status="5";
							}
							
							if($_POST['submitdata']=="reject"){
								$survey_status="4";
							}
							array_pop($_POST);
					
							$full_arr['user_id'] = $user_id;
							$full_arr['comment'] = $comment;
							$full_arr['survey_id'] = $uniq_survey_id;
							$full_arr['survey_status'] = $survey_status;
							$full_arr['cluster_no'] = $cluster_code;
							$full_arr['reason_of_change'] = "";
							$full_arr['GPS_latitude_start'] = "";
							$full_arr['GPS_longitude_start'] = "";
							
							
							$qtype = $_POST['qtype'];
							$questions = $_POST['questions'];
							//print_r($qtype);
							unset($_POST['qtype']);
							unset($_POST['questions']);
							//unset($_POST['survey_id']);
							unset($_POST['comment']);
							unset($_POST['survey_name']);
							unset($_POST['survey_name_id']);
							unset($_POST['client_id']);
							//print_r($_POST);
							
							foreach($_POST as $key=>$posted_value){
								$input_field_type = $qtype[$key];
								$question_id = $questions[$key];
								$field_name = $key;
								$inputValue = $posted_value;
								//$explode=explode('@',$inputValue);
								
								
								$option_id="";
								$option_value ="";
								$display_value="";
								if($input_field_type=="select_one" || $input_field_type=="select_multiple "){
									$option_id = $inputValue;
									$where = "where question_id='".$question_id."' and option_sequence='".$option_id."' ";
									$display_value = getdata($conn,"options","option_name",$where);//getone($conn,'questions','question_name','field_name',$key)
								}else{
									$option_value = $inputValue;
									$display_value = $inputValue;
								}
								
								$post_data["field_name"] = $field_name;
								$post_data["option_id"] = $option_id;
								$post_data["option_value"] = $option_value;
								$post_data["pre_field"] = "0";
								$post_data["question_id"] = $question_id;
								$post_data["input_field_type"] = $input_field_type;
								$postData[] = $post_data;
								$surveydataArr[$field_name] = $display_value;
								//$sdArr = $surveydataArr;
							}
							//print_r($surveydataArr);
							$full_arr['survey_data'] = $postData;
							$full_json = json_encode($full_arr);
							$survey_data_json = json_encode($surveydataArr);
							
							$insert = mysqli_query($conn,"insert INTO survey_data_monitoring SET survey_id='".$uniq_survey_id."', user_id='".$user_id."', survey_data_json='".$survey_data_json."', full_json='".$full_json."', cluster_code='".$cluster_code."', survey_status='".$survey_status."', user_type='".$user_type_id."',survey_name='".$survey_name."',survey_name_id='".$survey_name_id."',client_id='".$client_id."' ");
							if($insert){
								echo "<script>alert('Survey Added Successfully..!');window.location.href='survey-data-list.php'</script>";
							}
						}
					?>
                    <section class="panel">
                        <header class="panel-heading">
                            Edit Survey 
                        </header>

                        <?php
                        $survey_data_monitoring_id = $_REQUEST['id'];
                        $getQuestions = mysqli_query($conn, "SELECT survey_id, user_id, survey_data_json, full_json, cluster_code, survey_name, survey_name_id,client_id  FROM survey_data_monitoring where survey_data_monitoring_id='".$survey_data_monitoring_id."'");
                        $monitoring_data = mysqli_fetch_array($getQuestions);
						$full_json =  json_decode($monitoring_data['full_json'],true);
						$survey_id = $full_json['survey_id'];
						$user_id = $full_json['user_id'];
						$survey_data = $full_json['survey_data'];
				
						$count = 1;
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
							<form method="post" enctype="multipart/form-data">
                                <div class="col-lg-6" style="padding: 10px;">
									
									<input type="hidden" value="<?=$monitoring_data['survey_name'];?>" name="survey_name" /> 
									<input type="hidden" value="<?=$monitoring_data['survey_name_id'];?>" name="survey_name_id" /> 
									<input type="hidden" value="<?=$monitoring_data['client_id'];?>" name="client_id" /> 
									<input type="hidden" value="<?=$survey_id;?>" name="survey_id" /> 
                                    <?php //echo "<pre>";
                                    foreach($survey_data as $value){
										//print_r( $value);
                                        $question_id = $value[ 'question_id' ];
										$field_name = $value[ 'field_name' ];
										$pre_field = $value[ 'pre_field' ];
										$option_id = $value[ 'option_id' ];
										$option_value = $value[ 'option_value' ];
										$input_field_type=strtolower($value['input_field_type']);
										
										$question_details = getMulticolumns($conn,"questions","question_name,required","question_id",$question_id);
										$question_name = $question_details->question_name;
										?>
                                        <?php
										
										//in_array(strtolower($survey['input_field_type']), ['integer', 'text', 'camera','calendar',])
										//!in_array($input_field_type), ['start', 'end', 'today', 'begin group', 'end group', 'end repeat', 'begin repeat', 'imei', 'calculate'])
                                        if (in_array(strtolower($input_field_type), ['integer', 'number', 'text', 'camera','calendar','select_multiple','select_one','note','button','date','datetime','time','video','audio','begin_repeat','end_repeat'])){
                                            ?>
                                            <div class="row filter_css clearfix" style="margin-top:10px; padding-bottom: 10px; padding-top: 10px;">
                                                <div class="form-group select-custom-margin">
                                                    <?php
													$is_required="";$is_requiredstr=""; if(strtolower($question_details->required)=='yes'){ $is_requiredstr='<span class="required">*</span>';$is_required="required"; } 
													if(strtolower($input_field_type)=='integer' || strtolower($input_field_type)=='number' || strtolower($input_field_type)=='text')
													{
														if($value['input_field_type']=='integer')
														{
															$type='number';
														}
														else {
															$type=$input_field_type;
														}
														
														// if($survey['repeat_count']!=''){
															
														// } 
                                                        ?>
                                                        <?=$question_name;?><?=$is_requiredstr;?>
														<input type="hidden" value="<?=$value['input_field_type'];?>" name="qtype[<?=$value['field_name'];?>]" />
														<input type="hidden" value="<?=$value['question_id'];?>" name="questions[<?=$value['field_name'];?>]" />
                                                        <input type="<?=$type?>" class="form-control" value="<?=$option_value;?>" id="<?=$value['field_name'];?>" name="<?=$value['field_name'];?>" <?=$is_required;?> >
														<div id="<?=$value['field_name'];?><?=$value['question_id'];?>"></div>
                                                        <?php
                                                    } else if(strtolower($value['input_field_type'])=='note'){
                                                        ?>
                                                        <div class="alert alert-warning" style="color: black;">Note: <?=$survey['question_name']?></div>
                                                        <?php
                                                    }
                                                    else if(strtolower($value['input_field_type'])=='select_multiple'){
														$multiple ='multiple';
                                                        //$multiple = strtolower($survey['input_field_type']) == 'select_multiple' ? 'multiple' : '';
                                                        ?>
                                                        <?=$question_name;?><?=$is_requiredstr;?>
														<input type="hidden" value="<?=$value['input_field_type'];?>" name="qtype[<?=$value['field_name'];?>]" />
														<input type="hidden" value="<?=$value['question_id'];?>" name="questions[<?=$value['field_name'];?>]" />
                                                        <select class="form-control js-example-basic-multiple select2" data-placeholder="Please <?=$survey['question_name']?>" <?=$multiple?> <?=$is_required;?> >
                                                            <?php
                                                            $sql = "SELECT * FROM options where question_id = '".$value['question_id']."'";
                                                            $getOption = mysqli_query($conn, $sql);
                                                            while ($opt = mysqli_fetch_array($getOption)){
                                                                ?>
                                                                <option value="<?=$opt['option_sequence']?>"><?=$opt['option_name']?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <?php
                                                    }
													else if(strtolower($value['input_field_type'])=='select_one'){
														$multiple ='';
                                                        $multiple = strtolower($value['input_field_type']) == 'select_multiple' ? 'multiple' : '';
                                                        ?>
                                                        <?=$question_name;?><?=$is_requiredstr;?>
														<input type="hidden" value="<?=$value['input_field_type'];?>" name="qtype[<?=$value['field_name'];?>]" />
														<input type="hidden" value="<?=$value['question_id'];?>" name="questions[<?=$value['field_name'];?>]" <?=$is_required;?> />
                                                        <select class="form-control" name="<?=$value['field_name'];?>">
                                                            <?php
                                                            $sql = "SELECT * FROM options where question_id = '".$value['question_id']."'";
                                                            $getOption = mysqli_query($conn, $sql);
                                                            while ($opt = mysqli_fetch_array($getOption)){
                                                                ?>
                                                                <option value="<?=$opt['option_sequence']?>" <?php if($opt['option_sequence']==$option_id){ echo "selected";} ?> ><?=$opt['option_name']?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <?php 
                                                    }else if(strtolower($value['input_field_type'])=='camera'){ ?>
														<?=$question_name;?><?=$is_requiredstr;?>
														<br>
														<input type="hidden" value="<?=$value['input_field_type'];?>" name="qtype[<?=$value['field_name'];?>]" />
														<input type="hidden" value="<?=$value['question_id'];?>" name="questions[<?=$value['field_name'];?>]" />
														<img src="assets/camera-icon.jpg" class="sscamera" height="75" />
														<div id="my_camera"></div>
													<?php	
													}else if(strtolower($value['input_field_type'])=='date'){ ?>
														<?=$question_name;?><?=$is_requiredstr;?>
														<br/>
														<input type="hidden" value="<?=$value['input_field_type'];?>" name="qtype[<?=$value['field_name'];?>]" />
														<input type="hidden" value="<?=$value['question_id'];?>" name="questions[<?=$value['field_name'];?>]" />
														<input type="text" class="form-control datepicker" name="<?=$value['field_name'];?>" value="<?=$option_value?>" autocomplete="off"  <?=$is_required;?> />
													<?php	
													}else if(strtolower($value['input_field_type'])=='datetime'){ ?>
														<?=$question_name;?><?=$is_requiredstr;?>
														<br>
														<div class="form-group">
															<div class='input-group date datetimepicker1' >
															<input type="text" value="<?=$value['input_field_type'];?>" name="qtype[<?=$value['field_name'];?>]" />
															<input type="text" value="<?=$value['question_id'];?>" name="questions[<?=$value['field_name'];?>]" />
															   <input type='text' class="form-control" name="<?=$value['field_name'];?>" />
															   <span class="input-group-addon">
															   <span class="glyphicon glyphicon-calendar"></span>
															   </span>
															</div>
														</div>
														<!--<br/>
														<input type="text" class="form-control datepicker" name="datepicker" placeholder="Please select Date Time" />
														-->
													<?php	
													}else if(strtolower($value['input_field_type'])=='time'){ ?>
														<?=$question_name;?><?=$is_requiredstr;?>
														<br/>
														<input type="hidden" value="<?=$value['input_field_type'];?>" name="qtype[<?=$value['field_name'];?>]" />
														<input type="hidden" value="<?=$value['question_id'];?>" name="questions[<?=$value['field_name'];?>]" />
														<input type="time" class="form-control" name="<?=$value['field_name'];?>" placeholder="Please select Time" autocomplete="off" <?=$is_required;?> />
													<?php	
													}else if(strtolower($value['input_field_type'])=='video'){ ?> 
														<?=$question_name;?><?=$is_requiredstr;?>
														<br>
														<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModalAudio">Start Video Recording</button>
													<?php	
													}else if(strtolower($value['input_field_type'])=='audio'){ ?>
														<?=$question_name;?><?=$is_requiredstr;?>
														<br>
														<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModalAudio">Start Audio Recording</button>
													<?php	
													}
													//echo $survey['input_field_type'];
                                                    ?>

                                                </div>
                                            </div>
                                            <?php

                                        }
                                        ?>

										<?php //input_field_type repeat_count
											//if(){}
										?>
											<script>
												$("#<?=$survey['field_name'];?>").keyup(function(e){
													//var This_id = $(this).attr("id");
													//alert('SSS');
													var <?=$survey['field_name'];?> = $("#<?=$survey['field_name'];?>").val();
													$.ajax({
													  type:'POST',
													  url:'ajax_page.php',
													  data:{'questionId':<?=$survey['question_id'];?>,'input_value':<?=$survey['field_name'];?>,'survey_id':<?=$_REQUEST['survey_id'];?>},
													  success:function(result){
														  $('#<?=$survey['field_name'];?><?=$survey['question_id'];?>').html(result);
														 
													  }
													});
												});
											</script>
                                        <?php
                                        $count++;
                                    }
                                    ?>
									<div class="row"> 
										<div class=" form-group col-md-12">
											<label>Comment</label>
											<input type="text" class="form-control" name="comment">
										</div>
									</div>
									<button class="btn btn-primary" name="updateData" >Update</button>
									<?php
										if($_SESSION['role_id']=="7"){ ?>
											<button class="btn btn-success" name="submitdata" value="accept" >Accept</button>
											<button class="btn btn-danger" name="submitdata" value="reject" >Reject</button>
										<?php	
										}
									?>
                                </div>
							</form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->
	
<?php include_once('includes/footer.php'); ?>
 
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script>
$(".datepicker").datepicker( {
    format: "dd-mm-yyyy",
    startView: "days", 
    minViewMode: "days"
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<script language="JavaScript">
	Webcam.set({
		width: 490,
		height: 390,
		image_format: 'jpeg',
		jpeg_quality: 90
	});
  
	$(document).ready(function(){
		$('.sscamera').click(function(){
			Webcam.attach( '#my_camera' );
		});
	});
	
  
	// function take_snapshot() {
		// Webcam.snap( function(data_uri) {
			// $(".image-tag").val(data_uri);
			// document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
		// } );
	// }
</script>

<script src="js/select2.full.min.js"></script>
<script src="js/components-select2.min.js"></script>
<script>
$(document).ready(function() { 
    $('.js-example-basic-multiple').select2();
});
</script>

<link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
 $(function () {
	$('.datetimepicker1').datetimepicker();
 });
 </script>