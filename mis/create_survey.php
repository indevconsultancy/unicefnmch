<?php include_once('includes/config.php'); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
 <?php $survey_id = $_REQUEST['survey_id'];

require_once "api/mycrypt.php";

if($_SESSION['enckey'] === 0){
    die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);

 ?>
<script src="js/jquery.js"></script>
<link href="css/select2.min.css" rel="stylesheet" />
<link href="css/select2-bootstrap.min.css" rel="stylesheet" />
    <!--main content start-->
<style>
.required{
    color:red;
}
.remove_other{
	display: none;
}
.credits {
    background: #fff;
    padding: 5px 12px;
    position: fixed;
    bottom: 0;
    width: 100%;
	z-index: -1;
}
.panel-body{
	background-color: aliceblue;
	border-width: 0px 0px 0px!important;
}
.custom-formchecks label{
	border: 1px solid lightblue;
}

.d-none{
	display:none;
}

</style>
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <h3 class="page-header"><i class="fa fa fa-bars"></i> Pages</h3> -->
                    <ol class="breadcrumb" style="background-color:#747474;">
                     <!--   <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li> -->
                        <li><i class="icon_documents_alt"></i>Form</li>
                        <li><i class="fa fa-eye"></i>Form View</li>
                    </ol>
                </div>
            </div>

            <!-- page start-->

            <div class="row">
                <div class="col-sm-12">
                    <?php
						
						if(isset($_POST['saveData'])){
                            //echo "<pre>";
                            $survey_id = $_REQUEST['survey_id'];
							// $sqlsurvey=mysqli_query($conn,"SELECT client_id FROM `survey` where id='".$survey_id."'");
							// $dataSurvey=mysqli_fetch_array($sqlsurvey);
							// $client_id=$dataSurvey['client_id'];
							$language_id='1';
                            $client_id = getone($conn,"survey","client_id","id",$survey_id);
                            $survey_name = getone($conn,"survey","survey_name","id",$survey_id);
                            $digits = 11;
                            $uniq_survey_id = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
                            $user_id = $_SESSION['user_id'];
                            $user_type_id = $_SESSION['role_id'];
                            $cluster_code = "12345";
                            array_pop($_POST);
                            //print_r($_POST);
                            
                            $full_arr['user_id'] = $user_id;
                            $full_arr['survey_id'] = $uniq_survey_id;
                            $full_arr['survey_status'] = "1";
                            $full_arr['cluster_no'] = $cluster_code;
                            $full_arr['reason_of_change'] = "";
                            $full_arr['GPS_latitude_start'] = "";
                            $full_arr['GPS_longitude_start'] = "";
							
							$full_arr['survey_name_id'] = $survey_id;
							$full_arr['survey_name'] = $survey_name;
							$full_arr['hint_record'] = [];
							$full_arr['error_record'] = [];
                            
                            $qtype = $_POST['qtype'];
                            $questions = $_POST['questions'];
                            //print_r($qtype);
                            unset($_POST['qtype']);
                            unset($_POST['questions']);
                            

                            $key_array = array("option_value","input_field_type","question_id","field_name");
                            //print_r($_POST); 
                            //echo "INSIDE";
							//echo "<pre>";
                            foreach($_POST as $key=>$posted_value){
                                
                                ///////NEW///////////////////////

                                if(is_array($posted_value)){
                                    $group_name = $key;
									//echo count($posted_value);
									//print_r($posted_value);
									$posted_data = $_POST[$group_name]['group_id'];
									//print_r($posted_data);
									
                                    foreach($posted_data as $group_key=>$group_value){
                                       //echo $group_key;
                                       //$sn=0;
									   //print_r($group_value);
									   // for($a=0;$a<count($group_value); $a++){
										  // echo  $questionId_id = $group_key['question_id'][$a];
									   // }
									  $field_arr=array();
									   $getQuestionDeatils = mysqli_query($conn,"select * from questions where group_id='".$group_value."' and repeated not in('start','0','') ");
									   while($questDetails = mysqli_fetch_object($getQuestionDeatils)){
											$field_name = $questDetails->field_name;
											$question_id = $questDetails->question_id;
											$question_name = $questDetails->question_name;
											$input_field_type = $questDetails->input_field_type;
											
											$inputValue = $_POST[$fieldarr][$field_name][$group_key];
											$option_id="";
											$option_value ="";
											if($input_field_type=="select_one" || $input_field_type=="select_multiple "){
												$option_id = $inputValue;
											}else{
												$option_value = $inputValue;
											}
									
											
											$fieldarr = $group_name;
											$field_arr['option_value'] = $option_value;
											$field_arr['option_id'] = $option_id;
											$field_arr['question_id'] = $question_id;
											$field_arr['field_name'] = $field_name;
											$fdata_arr[] = $field_arr;
											//echo "<br>";
											$field_arr=array();
									   }
									   
									   unset($fdata_arr[0]);
									   $full_group_array[]=$fdata_arr;
									   $fdata_arr=array();
									  
										
                                    }
									$json_group_name = substr($group_name,1); 
									$full_arr[$json_group_name]=$full_group_array;
									
									$full_group_array=array();
                                }

                                ////////////OLD//////////////////
                                else{


									$input_field_type = $qtype[$key];
									$question_id = $questions[$key];
									$field_name = $key;
									$inputValue = $posted_value;
									
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
									
								}


                            }
							
                            $full_arr['survey_data'] = $postData;
                            foreach($group_Arr as $gpKey=>$gpVal){
                                $full_arr[$gpVal] = $post_dataSubAllSSS[$gpKey];
                            }
                            


                            $full_json = json_encode($full_arr);
                            //echo "Hiii".$full_json;
							
							$curl = curl_init();

							curl_setopt_array($curl, array(
							  CURLOPT_URL => 'https://mquad.org/mis/api/survey_data_upload_v2_web.php',
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => '',
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 0,
							  CURLOPT_FOLLOWLOCATION => true,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => 'POST',
							  CURLOPT_POSTFIELDS =>$full_json,
							));

							$response = curl_exec($curl);

							curl_close($curl);
							//echo $response;
							if($_SESSION['functional_role_id']=='3'){
							    echo "<script>alert('Survey submitted Successfully..!');window.location.href='survey-list.php'</script>";
							}else{
							echo "<script>alert('Survey submitted Successfully..!');window.location.href='form-list.php'</script>";
							}
                          //  die; 
							/*
                            $survey_data_json = json_encode($surveydataArr);
							//echo "INSERT INTO survey_data_monitoring SET survey_id='".$uniq_survey_id."', user_id='".$user_id."', survey_data_json='".$survey_data_json."', full_json='".$full_json."', cluster_code='".$cluster_code."', survey_status='1', user_type='".$user_type_id."', survey_name='".$survey_name."', survey_name_id='".$survey_id."' ";
							//die();
                            $insert = mysqli_query($conn,"INSERT INTO survey_data_monitoring SET survey_id='".$uniq_survey_id."',client_id='".$client_id."',language_id='".$language_id."', user_id='".$user_id."', survey_data_json='".$survey_data_json."', full_json='".$full_json."', cluster_code='".$cluster_code."', survey_status='1', user_type='".$user_type_id."', survey_name='".$survey_name."', survey_name_id='".$survey_id."' ");
                            if($insert){
                                echo "<script>alert('Survey Added Successfully..!');window.location.href='survey-list.php'</script>";
                            }
							*/
                        }
					
                    ?>
					<?php
					//echo "SELECT DISTINCT(users.name) as name,users.username,users.orignal_password,users.email,users.mobile FROM `assign_survey` left join users on users.user_id=assign_survey.user_id WHERE `survey_id` = '".$survey_id."' and users.del_action='N' and users.status='0' and assign_survey.status='0'";
					
						$_SESSION['query']="SELECT DISTINCT(users.name) as name,users.username,users.orignal_password,users.email,users.mobile FROM `assign_survey` left join users on users.user_id=assign_survey.user_id WHERE `survey_id` = '".$survey_id."' and users.del_action='N' and users.status='0' and assign_survey.status='0'";
						
					?>
                    <section class="panel">

                        <header class="panel-heading">
                            Form <!-- <a href="add-question.php?survey_id=<?=$_REQUEST['survey_id']?>" class="pull-right btn-sm btn-primary" style="margin: 3px;">Add Item</a> -->
                           <?php
								$_SESSION['header_column']="Name,Username,Password,Email Id,mobile Number";
								$_SESSION['db_column']="name,username,orignal_password,email,mobile_no";
							?>
						   <!-- <a href="export/export.php" class="btn-sm btn-primary" style="margin: 3px;">Export data</a> -->

                        </header>

                        <?php
                       
                        $getQuestions = mysqli_query($conn, "SELECT * FROM questions where survey_id='".$survey_id."' and ( repeated='0' || repeated='start' ) order by sequence_no asc ");
                        $count = 1;
                        ?>
                        <div class="panel panel-default"> 
                            <div class="panel-body">
                            <form method="post" enctype="multipart/form-data" id="sform">
								<div class="col-sm-1"></div>
                                <div class="row col-sm-10" style="padding: 10px;">

                                    <?php //echo "<pre>";
                                    $input_fields = array();
                                    $input_values_one = array();
                                    $input_values_multiple = array();
                                    while($survey = mysqli_fetch_array($getQuestions)){
                                        $hide='';
										if(!empty($survey['relevant'])){ $hide="d-none"; }
                                        $input_fields[] = strtolower($survey['input_field_type']);
                                        ?>
                                        <?php
                                        $input_field_type=strtolower($survey['input_field_type']);
                                        if (in_array(strtolower($survey['input_field_type']), ['integer', 'number', 'text','textarea', 'camera','calendar','select_multiple','select_one','button','date','datetime','time','video','audio','begin_repeat','end_repeat'])){
                                            ?>
											
                                            <div class="col-md-12" style="padding-bottom: 10px;">
                                                <div class="form-group select-custom-margin <?=$survey['field_name'];?> <?=$hide;?>">
                                                    <?php
                                                    $is_required="";$is_requiredstr=""; if(strtolower($survey['required'])=='yes'){ $is_requiredstr='<span class="required">*</span>';$is_required="required"; } 
                                                     //in_array(strtolower($survey['input_field_type']), ['integer', 'number', 'text', 'camera','calendar','select_multiple','select_one','note']);
                                                    if((strtolower($survey['input_field_type'])=='integer' || strtolower($survey['input_field_type'])=='number' || strtolower($survey['input_field_type'])=='text' ) && ($survey['repeated']=="0" || $survey['repeated']=="start" ))
                                                    {
                                                        if($survey['input_field_type']=='integer')
                                                        {
                                                            $type='number';
                                                        }
                                                        //else if($survey['input_field_type']=='date'){
                                                            //$type = 'date';
                                                        //}
                                                        else {
                                                            $type=$survey['input_field_type'];
                                                        }
                                                        
                                                        // if($survey['repeat_count']!=''){
                                                            
                                                        // } 
                                                        ?>
                                                        <?=$survey['question_name'];?> <?=$is_requiredstr;?>
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                        <input type="<?=$type?>" class="form-control" placeholder="<?=$survey['question_description']?>" id="<?=$survey['field_name'];?>" name="<?=$survey['field_name'];?>" <?=$is_required;?>  >
                                                        <div id="<?=$survey['field_name'];?><?=$survey['question_id'];?>"></div>
														
                                                        <?php
                                                    }else if(strtolower($survey['input_field_type'])=='textarea'){ ?>
														<?=$survey['question_name'];?> <?=$is_requiredstr;?>
														<input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
														<input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
														<textarea class="form-control" placeholder="<?=$survey['question_description']?>" id="<?=$survey['field_name'];?>" name="<?=$survey['field_name'];?>" <?=$is_required;?>></textarea>
														<div id="<?=$survey['field_name'];?><?=$survey['question_id'];?>"></div>
													<?php	
													} else if(strtolower($survey['input_field_type'])=='note'){
                                                        ?>
                                                        <div class="alert alert-warning" style="color: black;">Note: <?=$survey['question_name']?></div>
                                                        <?php
                                                    }
                                                    else if(strtolower($survey['input_field_type'])=='select_multiple'){
                                                        $multiple ='multiple';
                                                        //$multiple = strtolower($survey['input_field_type']) == 'select_multiple' ? 'multiple' : '';
                                                        ?>
                                                        <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                        <select class="form-control js-example-basic-multiple select2" data-placeholder="Please <?=$survey['question_name']?>" <?=$multiple?> <?=$is_required;?> >
                                                            <?php
                                                            $sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
                                                            $getOption = mysqli_query($conn, $sql);
                                                            while ($opt = mysqli_fetch_array($getOption)){
                                                                $input_values_multiple[] = $opt['option_name'];
                                                                ?>
                                                                <option value="<?=$opt['option_sequence']?>"><?=$opt['option_name']?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <?php
                                                    }
                                                    else if((strtolower($survey['input_field_type'])=='select_one') && ($survey['repeated']=="0" || $survey['repeated']=="start" )){
                                                        
                                                        
                                                        if((strtolower($survey['input_field_type'])=='select_one') && ($survey['appearance']=='minimal' || $survey['appearance']=='quick' || $survey['appearance']=='dropdown' || $survey['appearance']=='other' ) ){ ?>
                                                            
                                                            <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                            <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                            <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                            <select class="form-control" name="<?=$survey['field_name'];?>" <?=$is_required;?> onchange="getOther(this), getSelectOptions(this.value)" data-id="<?=$survey['field_name'];?>" id="<?=$survey['field_name'];?>" >
                                                                <option value="">Select Option</option>
																<?php
                                                                $sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
                                                                $getOption = mysqli_query($conn, $sql);
                                                                while ($opt = mysqli_fetch_array($getOption)){

                                                                $input_values_one[] = $opt['option_name'];
                                                                    ?>
                                                                    <option value="<?=$opt['option_sequence']?>"><?=$opt['option_name']?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                            <div id="<?=$question_id?>"></div>
                                                        <?php   
                                                        }
                                                        
                                                        if((strtolower($survey['input_field_type'])=='select_one') && ($survey['appearance']=='autocomplete' ) ){ ?>
                                                        
                                                            <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                            <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                            <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                            <select class="form-control" name="<?=$survey['field_name'];?>" <?=$is_required;?> onchange="getOther(this)" data-id="ss-2" >
                                                               <option value="">Select Option</option>
															   <?php
                                                                $sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
                                                                $getOption = mysqli_query($conn, $sql);
                                                                while ($opt = mysqli_fetch_array($getOption)){
                                                                $input_values_one[] = $opt['option_name'];

                                                                    ?>
                                                                    <option value="<?=$opt['option_sequence']?>"><?=$opt['option_name']?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        
                                                        <?php } ?>
                                                        
                                                        <?php
                                                            if((strtolower($survey['input_field_type'])=='select_one') && ($survey['appearance']=='likert' )){ ?>
                                                                <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                                <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                                <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                                
                                                                <div class="form-check">
                                                                    <?php
                                                                    $sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
                                                                    $getOption = mysqli_query($conn, $sql);
                                                                    while ($opt = mysqli_fetch_array($getOption)){
                                                                        $input_values_one[] = $opt['option_name'];

                                                                        ?>
                                                                        
                                                                          <input class="form-check-input" type="radio" name="<?=$survey['field_name'];?>" id="exampleRadios<?=$opt['option_sequence']?>" value="<?=$opt['option_sequence']?>" >
                                                                           <?=$opt['option_name']?>
                                                                            <img src="<?=$opt['likert_img']?>"  style="border-radius:50%;height:45px;" />
                                                                          
                                                                        
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                                
                                                            <?php   
                                                            }
                                                        ?>
														
														<?php
                                                            if((strtolower($survey['input_field_type'])=='select_one') && ($survey['appearance']=='' || $survey['appearance']=='none' )){ ?>
                                                                <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                                <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                                <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                                
                                                                <div class="form-check">
																    <div class="custom-formchecks">
                                                                    <?php
                                                                    $sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
                                                                    $getOption = mysqli_query($conn, $sql);
                                                                    while ($opt = mysqli_fetch_array($getOption)){
                                                                        $input_values_one[] = $opt['option_name'];

                                                                        ?>
																		
                                                                        <div class="custom-radio">
																			<label>
																				 <input class="form-check-input" type="radio" name="<?=$survey['field_name'];?>" id="exampleRadios<?=$opt['option_sequence']?>" value="<?=$opt['option_sequence']?>" >
                                                                           		 <?=$opt['option_name']?>
																			</label>
																		
																		</div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    </div>
																</div>
                                                                
                                                            <?php   
                                                            }
                                                        ?>
                                                        
                                                        <?php 
                                                    }else if(strtolower($survey['input_field_type'])=='camera'){ ?>
                                                        <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                        <br>
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
														<input type="text" name="<?=$survey['field_name'];?>" class="form-control" readonly placeholder="<?=$survey['question_name'];?>" />
                                                        <!--<img src="assets/camera-icon.jpg" class="sscamera" height="75" />
                                                        <div id="my_camera"></div>-->
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='date'){ ?>
                                                        <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                        <br/>
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                        <input type="text" class="form-control datepicker" name="<?=$survey['field_name'];?>" placeholder="Please select Date" autocomplete="off" <?=$is_required;?> />
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='datetime'){ ?>
                                                        <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                        <br>
                                                        <div class="form-group">
                                                            <div class='input-group date datetimepicker1' >
                                                            <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                            <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                               <input type='text' class="form-control" name="<?=$survey['field_name'];?>" <?=$is_required;?> />
                                                               <span class="input-group-addon">
                                                               <span class="glyphicon glyphicon-calendar"></span>
                                                               </span>
                                                            </div>
                                                        </div>
                                                        <!--<br/>
                                                        <input type="text" class="form-control datepicker" name="datepicker" placeholder="Please select Date Time" />
                                                        -->
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='time'){ ?>
                                                        <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                        <br/>
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                        <input type="time" class="form-control" name="<?=$survey['field_name'];?>" placeholder="Please select Time" autocomplete="off" <?=$is_required;?> />
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='video'){ ?> 
                                                        <?=$survey['question_name'];?><?=$is_required;?>
                                                        <br>
                                                        <!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModalAudio">Start Video Recording</button>-->
                                                        <!--<img src="assets/camera-icon.jpg"  height="75" />-->
														<input type="text" name="<?=$survey['field_name'];?>" class="form-control" readonly placeholder="<?=$survey['question_name'];?>" />
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='audio'){ ?>
                                                        <?=$survey['question_name'];?><?=$is_required;?>
                                                        <br>
                                                        <!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModalAudio">Start Audio Recording</button>-->
                                                        <!--<img src="assets/camera-icon.jpg" height="75" />-->
														<input type="text" name="<?=$survey['field_name'];?>" class="form-control" readonly placeholder="<?=$survey['question_name'];?>" />
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
                                            if($survey['repeated']=="start"){ ?>
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
                                            <?php } ?>
                                            
                                            
                                        <?php
                                        $count++;
                                    }
                                    ?>
									<div class="col-md-12 text-right" style="padding-bottom: 10px;">
                                    	<button type="submit" class="btn btn-primary" name="saveData" >Submit</button>
									</div>
                                    
                                </div>
                                </div>
								<div class="col-sm-1"></div>
                            </form>
                            
                        </div>

                    </section>

                </div>

            </div>

            <!-- page end-->

        </section>

    </section>

    <!--main content end-->

<?php

// foreach($input_fields as $field){
//     if($field=='select_one'){
//         //code here...
//         foreach($input_values_one as $one){
//             if($one==""){

//             }
//         }



//     }else if($field=='select_multiple'){
//         // Code here...

//     }
// }



?>
    
    

 

 <script>
 var other_old;
 function getOther(val){
	var q_name = $(val).data("id");
	var q_value = $("#"+q_name).val();
	var relevant = q_name+"_"+q_value;
	
	var other = $("#"+relevant).data("sample-id");;//$("#"+relevant).val();

	if(relevant==other){
		other_old = other;
		$("#"+relevant).removeClass("remove_other");
	}else{
		$("#"+other_old).addClass("remove_other");
	}
    
 }
 
 
 
 /// STATIC CONDITIONS
/*  $(".custom-radio").on("click", function(){
	$(".seed_producer_other").addClass("d-none");
	var seed_producer = $('input[name="seed_producer"]:checked').val();
	console.log(seed_producer);
	if(seed_producer=="6"){
		$(".seed_producer_other").removeClass("d-none");
	}
	
 }); */
 
 
 $(document).on("change",'.main_source', function(e){
	var msource = $(this).val();
	//console.log(msource);
	//$(this).parent().parent().parent().parent().parent().find('.divmain_source_other').addClass('d-none');
	if(msource=="5"){
		console.log('test');
		//let a = $(this).closest('div').find('.divmain_source_other').removeClass("d-none");
		//let a = $(this).next().find('.divmain_source_other').addClass("d-none111");
		//$("#sform").find('.divmain_source_other').removeClass("d-none");
		$(this).parent().parent().parent().parent().parent().find('.divmain_source_other').removeClass("d-none");
		//console.log(a);
	}
	
 });
 
 </script>
 
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
    $('.js-example-basic-multiple').select2({
		theme: "bootstrap",
		containerCssClass: ':all:'
	});
});
</script>

<script>
    function getSelectOptions(sId) {
        // alert(sId);
        $.ajax({
            url: "options_ajax.php",
            type: "POST",
            data: "question_id=" + sId,
            success: function (data) {
                $('#<?=$question_id?>').html(data);
            }
        });
    }
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
<?php include_once('includes/footer.php'); ?>
