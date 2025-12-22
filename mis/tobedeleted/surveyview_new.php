<?php include_once('includes/config.php'); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

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
</style>
<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}

#regForm {
  background-color: #ffffff;
  margin: 100px auto;
  font-family: Raleway;
  padding: 40px;
  width: 70%;
  min-width: 300px;
}

h1 {
  text-align: center;  
}

input {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}

/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #04AA6D;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #04AA6D;
}
</style>
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <h3 class="page-header"><i class="fa fa fa-bars"></i> Pages</h3> -->
                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>
                        <li><i class="icon_documents_alt"></i>Survey</li>
                        <li><i class="fa fa-bars"></i>Survey View</li>
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
                            
                            $qtype = $_POST['qtype'];
                            $questions = $_POST['questions'];
                            //print_r($qtype);
                            unset($_POST['qtype']);
                            unset($_POST['questions']);
                            

                            $key_array = array("option_value","input_field_type","question_id","field_name");
                            //print_r($_POST); 
                            //echo "INSIDE";
							echo "<pre>";
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
											$fieldarr = $group_name;
											$field_arr['option_value'] = $_POST[$fieldarr][$field_name][$group_key];
											$field_arr['option_id'] = "";
											$field_arr['question_id'] = $question_id;
											$field_arr['field_name'] = $field_name;
											$fdata_arr[] = $field_arr;
											//echo "<br>";
											$field_arr=array();
									   }
									   
									   $full_group_array[]=$fdata_arr;
									   $fdata_arr=array();
									   
                                        /*foreach($group_value as $gkey=>$gval){
                                                // echo $gkey;
												//echo $gval;
                                                $keyname = $key_array[$sn];
                                                $post_dataSub[$keyname] = $gval;
                                                $post_dataSub['option_id'] = "";
                                                
                                                $sn++;
                                                if($sn==4){ 
                                                    $post_dataSubAll[] = $post_dataSub;
                                                    $post_dataSub = array();
                                                    $sn=0;
                                                }
                                            }
                                        */
										
                                    }
									$full_arr[$group_name]=$full_group_array;
									
                                    //$post_dataSubAllSSS[$group_name] = $post_dataSubAll;
                                    // $group_Arr[] = ltrim($group_name,'s');//$group_name;
                                    // $post_dataSubAllSSS[] = $post_dataSubAll;
                                    // $post_dataSubAll = array();
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
                            //print_r($post_dataSubAllSSS);   
                            //echo json_encode($post_dataSubAllSSS);
                            //echo "<br><br>";
                            $full_arr['survey_data'] = $postData;
                            foreach($group_Arr as $gpKey=>$gpVal){
                                $full_arr[$gpVal] = $post_dataSubAllSSS[$gpKey];
                            }
                            

                            // $rows[] = $full_arr;
                            // $rows[] = $post_dataSubAllSSS;

                            //json_encode($full_arr);


                            $full_json = json_encode($full_arr);
                            //echo "Hiii".$full_json;
                            //die;
                            $survey_data_json = json_encode($surveydataArr);
                            $insert = mysqli_query($conn,"INSERT INTO survey_data_monitoring SET survey_id='".$uniq_survey_id."', user_id='".$user_id."', survey_data_json='".$survey_data_json."', full_json='".$full_json."', cluster_code='".$cluster_code."', survey_status='1', user_type='".$user_type_id."', survey_name='".$survey_name."', survey_name_id='".$survey_id."' ");
                            if($insert){
                                echo "<script>alert('Survey Added Successfully..!');window.location.href='survey-data-list.php'</script>";
                            }
                        }
                    ?>
                    <section class="panel">

                        <header class="panel-heading">

                            Survey Questions <a href="add-question.php?survey_id=<?=$_REQUEST['survey_id']?>" class="pull-right btn-sm btn-primary" style="margin: 3px;">Add Questions</a>

                        </header>

                        <?php
                        $survey_id = $_REQUEST['survey_id'];
                        $getQuestions = mysqli_query($conn, "SELECT * FROM questions where survey_id='".$survey_id."'");
                        $count = 1;
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="col-lg-12" style="padding: 10px;">
									<h1>Start Survey:</h1>
									
									<?php //echo "<pre>";
                                    while($survey = mysqli_fetch_array($getQuestions)){
										
                                        $input_field_type=strtolower($survey['input_field_type']);
                                        if (in_array(strtolower($survey['input_field_type']), ['integer', 'number', 'text', 'camera','calendar','select_multiple','select_one','note','button','date','datetime','time','video','audio','begin_repeat','end_repeat'])){
                                            ?>
											
											
                                                    <?php
                                                    $is_required="";$is_requiredstr=""; if(strtolower($survey['required'])=='yes'){ $is_requiredstr='<span class="required">*</span>';$is_required="required"; } 
                                                    if((strtolower($survey['input_field_type'])=='integer' || strtolower($survey['input_field_type'])=='number' || strtolower($survey['input_field_type'])=='text') && ($survey['repeated']=="0" || $survey['repeated']=="start" ))
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
														$steps.='<span class="step"></span>';
                                                        ?>
                                                        
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                        <div class="tab"> <?=$survey['question_name'];?> <?=$is_requiredstr;?>
															<p><input type="<?=$type?>" class="form-control" placeholder="<?=$survey['question_name'];?>" oninput="this.className = ''" name="<?=$survey['field_name'];?>" <?=$is_required;?> > </p>
														</div>
                                                        <?php
                                                    } else if(strtolower($survey['input_field_type'])=='note'){ $steps.='<span class="step"></span>';
                                                        ?>
														<div class="tab">
															<p>
																<div class="alert alert-warning" style="color: black;">Note: <?=$survey['question_name']?></div>
															</p>
														</div>
														<?php
                                                    }
                                                    else if(strtolower($survey['input_field_type'])=='select_multiple'){
                                                        $multiple ='multiple';
														$steps.='<span class="step"></span>';
                                                        //$multiple = strtolower($survey['input_field_type']) == 'select_multiple' ? 'multiple' : '';
                                                        ?>
                                                        
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                        
														<div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
															<p>
															<select class="form-control js-example-basic-multiple select2" oninput="this.className = ''" data-placeholder="Please <?=$survey['question_name']?>" <?=$multiple?> <?=$is_required;?> >
																<?php
																$sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
																$getOption = mysqli_query($conn, $sql);
																while ($opt = mysqli_fetch_array($getOption)){
																	?>
																	<option value="<?=$opt['option_sequence']?>"><?=$opt['option_name']?></option>
																	<?php
																}
																?>
															</select>
															</p>
														</div>
														
														
                                                        <?php
                                                    }
                                                    else if(strtolower($survey['input_field_type'])=='select_one'){
                                                        
                                                        
                                                        if((strtolower($survey['input_field_type'])=='select_one') && ($survey['appearance']=='minimal' || $survey['appearance']=='quick' || $survey['appearance']=='dropdown' || $survey['appearance']=='other' ) ){ $steps.='<span class="step"></span>'; ?>
                                                            
                                                            <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                            <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                            
															<div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
																<p>
																<select class="form-control" oninput="this.className = ''" name="<?=$survey['field_name'];?>" <?=$is_required;?> onchange="getOther(this)" data-id="<?=$survey['field_name'];?>" id="<?=$survey['field_name'];?>" >
																	<option value="">Select Option</option>
																	<?php
																	$sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
																	$getOption = mysqli_query($conn, $sql);
																	while ($opt = mysqli_fetch_array($getOption)){
																		?>
																		<option value="<?=$opt['option_sequence']?>"><?=$opt['option_name']?></option>
																		<?php
																	}
																	?>
																</select>
															</p>
															</div>
                                                        <?php   
                                                        }
                                                        
                                                        if((strtolower($survey['input_field_type'])=='select_one') && ($survey['appearance']=='autocomplete' ) ){ $steps.='<span class="step"></span>'; ?>
                                                        
                                                            <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                            <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                            
															
															<div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
																<p>
																<select class="form-control" oninput="this.className = ''" name="<?=$survey['field_name'];?>" <?=$is_required;?> onchange="getOther(this)" data-id="ss-2" >
																   <option value="">Select Option</option>
																   <?php
																	$sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
																	$getOption = mysqli_query($conn, $sql);
																	while ($opt = mysqli_fetch_array($getOption)){
																		?>
																		<option value="<?=$opt['option_sequence']?>"><?=$opt['option_name']?></option>
																		<?php
																	}
																	?>
																</select>
																</p>
															</div>
                                                        
                                                        <?php } ?>
                                                        
                                                        <?php
                                                            if((strtolower($survey['input_field_type'])=='select_one') && ($survey['appearance']=='likert' )){ $steps.='<span class="step"></span>'; ?>
                                                                
                                                                <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                                <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                                
																<div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
																	<p>
																		<?php
																			$sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
																			$getOption = mysqli_query($conn, $sql);
																			while ($opt = mysqli_fetch_array($getOption)){
																				?>
																					<input type="radio"  name="<?=$survey['field_name'];?>" id="exampleRadios<?=$opt['option_sequence']?>" value="<?=$opt['option_sequence']?>" > 
																					<?=$opt['option_name']?>
																					<img src="<?=$opt['likert_img']?>"  style="border-radius:50%;height:45px;" />
																				  
																				
																				<?php
																			}
																			?>																			
																		
																	</p>
																</div>
                                                                
                                                            <?php   
                                                            }
                                                        ?>
														
														<?php
                                                            if((strtolower($survey['input_field_type'])=='select_one') && ($survey['appearance']=='' || $survey['appearance']=='none' )){ $steps.='<span class="step"></span>'; ?>
                                                                
                                                                <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                                <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                                
																<div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
																	<p>
																		<?php
																			$sql = "SELECT * FROM options where question_id = '".$survey['question_id']."'";
																			$getOption = mysqli_query($conn, $sql);
																			while ($opt = mysqli_fetch_array($getOption)){
																				?>
																					<input type="radio"  name="<?=$survey['field_name'];?>" id="exampleRadios<?=$opt['option_sequence']?>" value="<?=$opt['option_sequence']?>" > 
																					<?=$opt['option_name']?>
																					
																				<?php
																			}
																			?>																			
																		
																	</p>
																</div>
																
                                                                
                                                            <?php   
                                                            }
                                                        ?>
                                                        
                                                        <?php 
                                                    }else if(strtolower($survey['input_field_type'])=='camera'){ $steps.='<span class="step"></span>'; ?>
                                                        
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
														
                                                        <div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
															<p><input type="text" class="form-control" value="NA" oninput="this.className = ''" name="<?=$survey['field_name'];?>" <?=$is_required;?> readonly > </p>
														</div>
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='date'){ $steps.='<span class="step"></span>'; ?>
                                                        
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                        
														<div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
															<p>
																<input type="text" class="form-control datepicker" oninput="this.className = ''" name="<?=$survey['field_name'];?>" placeholder="Please select Date" autocomplete="off" <?=$is_required;?> />
															</p>
														</div>
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='datetime'){ $steps.='<span class="step"></span>'; ?>
                                                       
                                                        <div class="form-group">
                                                            <div class='input-group date datetimepicker1' >
                                                            <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                            <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
																<div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
																<p>
																   <input type='text' class="form-control" oninput="this.className = ''" name="<?=$survey['field_name'];?>" <?=$is_required;?> />
																   <span class="input-group-addon">
																   <span class="glyphicon glyphicon-calendar"></span>
																   </span>
																</p>
															   </div>
                                                            </div>
                                                        </div>
                                                        <!--<br/>
                                                        <input type="text" class="form-control datepicker" name="datepicker" placeholder="Please select Date Time" />
                                                        -->
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='time'){ $steps.='<span class="step"></span>'; ?>
                                                        
                                                        <input type="hidden" value="<?=$survey['input_field_type'];?>" name="qtype[<?=$survey['field_name'];?>]" />
                                                        <input type="hidden" value="<?=$survey['question_id'];?>" name="questions[<?=$survey['field_name'];?>]" />
                                                        <div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
															<p>
															<input type="time" class="form-control" oninput="this.className = ''" name="<?=$survey['field_name'];?>" placeholder="Please select Time" autocomplete="off" <?=$is_required;?> />
															</p>
														</div>	
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='video'){ $steps.='<span class="step"></span>'; ?> 
                                                        
                                                        <div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
															<p>
															<input type="text" class="form-control" value="NA" oninput="this.className = ''" name="<?=$survey['field_name'];?>"  autocomplete="off" <?=$is_required;?> readonly />
															</p>
														</div>
														
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='audio'){ $steps.='<span class="step"></span>'; ?>
                                                        
                                                        <div class="tab"><?=$survey['question_name'];?> <?=$is_requiredstr;?>
															<p>
															<input type="text" class="form-control" value="NA" oninput="this.className = ''" name="<?=$survey['field_name'];?>"  autocomplete="off" <?=$is_required;?> readonly />
															</p>
														</div>
														
                                                    <?php   
                                                    }
                                                    //echo $survey['input_field_type'];
                                                    ?>

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
                                    
								  
								  
								  
								  
								  <div style="overflow:auto;">
									<div style="float:right;">
									  <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
									  <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
									</div>
								  </div>
								  <!-- Circles which indicates the steps of the form: -->
								  <div style="text-align:center;margin-top:40px;display:none;">
									<?=$steps;?>
								  </div>
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
 
 <script>
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    //if (y[i].value == "") {
	/*if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
	*/
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>
 
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