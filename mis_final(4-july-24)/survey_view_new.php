<?php include_once('includes/config.php'); ?>
<?php define("title","Form View | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
 <?php $survey_id = mysqli_real_escape_string($conn, $_REQUEST['survey_id']); ?>
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
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb" style="background-color:#747474;">
                        <li><i class="icon_documents_alt"></i>Form</li>
                        <li><i class="fa fa-eye"></i>Form View</li>
                    </ol>
                </div>
            </div>

            <!-- page start-->

            <div class="row">
                <div class="col-sm-12">
                    
					<?php
						$_SESSION['query']="SELECT DISTINCT(users.name) as name,users.username,users.orignal_password,users.email,users.mobile FROM `assign_survey` left join users on users.user_id=assign_survey.user_id WHERE `survey_id` = '".$survey_id."' and users.del_action='N' and users.status='0' and assign_survey.status='0'";
						
					?>
                    <section class="panel">

                        <header class="panel-heading">
                            Form View <a href="add-question.php?survey_id=<?=$_REQUEST['survey_id']?>" class="pull-right btn-sm btn-primary" style="margin: 3px;">Add Item</a>
                           <?php
								$_SESSION['header_column']="Name,Username,Password,Email Id,mobile Number";
								$_SESSION['db_column']="name,username,orignal_password,email,mobile_no";
							?>
						   <a href="export/export.php" class="btn-sm btn-primary" style="margin: 3px;">Export data</a>

                        </header>

                        <?php
                       
                        
						
						$htmlfile='cache/s'.$survey_id.'.html';
						if(!file_exists($htmlfile)){
							function htmlContent($conn,$survey_id){
								ob_start();
								$getQuestions = mysqli_query($conn, "SELECT * FROM questions where survey_id='".$survey_id."' and ( repeated='0' || repeated='start' ) order by sequence_no asc ");
								$count = 1;
                        ?>
                        <div class="panel panel-default"> 
                            <div class="panel-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="row" style="padding: 10px;">
                                    <?php
                                    $input_fields = array();
                                    $input_values_one = array();
                                    $input_values_multiple = array();
                                    while($survey = mysqli_fetch_array($getQuestions)){
                                        $input_fields[] = strtolower($survey['input_field_type']);
										
                                        $input_field_type=strtolower($survey['input_field_type']);
                                        if (in_array(strtolower($survey['input_field_type']), ['integer', 'number', 'text','textarea', 'camera','calendar','select_multiple','select_one','button','date','datetime','time','video','audio','begin_repeat','end_repeat'])){
                                            ?>
											
                                            <div class="<?= $class =  ($survey['input_field_type']=='select_one') ? "col-md-12" : "col-md-6" ?>" style="padding-bottom: 10px;">
                                                <div class="form-group select-custom-margin">
                                                    <?php
                                                    $is_required="";$is_requiredstr=""; if(strtolower($survey['required'])=='yes'){ $is_requiredstr='<span class="required">*</span>';$is_required="required"; } 
                                                    if((strtolower($survey['input_field_type'])=='integer' || strtolower($survey['input_field_type'])=='number' || strtolower($survey['input_field_type'])=='text' ) && ($survey['repeated']=="0" || $survey['repeated']=="start" ))
                                                    {
                                                        if($survey['input_field_type']=='integer')
                                                        {
                                                            $type='number';
                                                        }
                                                        else {
                                                            $type=$survey['input_field_type'];
                                                        }
                                                        
                                                        ?>
                                                        <?=$survey['question_name'];?> <?=$is_requiredstr;?>
                                                        <input type="<?=$type?>" onkeypress="return this.value.length < 15;" oninput="if(this.value.length>=15) { this.value = this.value.slice(0,15); } {this.value = Math.abs(this.value)}" class="form-control" placeholder="<?=$survey['question_description']?>" id="<?=$survey['field_name'];?>" name="<?=$survey['field_name'];?>" <?=$is_required;?> >
                                                        <div id="<?=$survey['field_name'];?><?=$survey['question_id'];?>"></div>
														
                                                        <?php
                                                    }else if(strtolower($survey['input_field_type'])=='textarea'){ ?>
														<?=$survey['question_name'];?> <?=$is_requiredstr;?>
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
                                                        ?>
                                                        <?=$survey['question_name'];?><?=$is_requiredstr;?>
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
														<input type="text" name="<?=$survey['field_name'];?>" class="form-control" readonly placeholder="<?=$survey['question_name'];?>" />
                                                       
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='date'){ ?>
                                                        <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                        <br/>
                                                        <input type="text" class="form-control datepicker" name="<?=$survey['field_name'];?>" placeholder="Please select Date" autocomplete="off" <?=$is_required;?> />
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='datetime'){ ?>
                                                        <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                        <br>
                                                        <div class="form-group">
                                                            <div class='input-group date datetimepicker1' >
                                                               <input type='text' class="form-control" name="<?=$survey['field_name'];?>" <?=$is_required;?> />
                                                               <span class="input-group-addon">
                                                               <span class="glyphicon glyphicon-calendar"></span>
                                                               </span>
                                                            </div>
                                                        </div>
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='time'){ ?>
                                                        <?=$survey['question_name'];?><?=$is_requiredstr;?>
                                                        <br/>
                                                        <input type="time" class="form-control" name="<?=$survey['field_name'];?>" placeholder="Please select Time" autocomplete="off" <?=$is_required;?> />
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='video'){ ?> 
                                                        <?=$survey['question_name'];?><?=$is_required;?>
                                                        <br>
														<input type="text" name="<?=$survey['field_name'];?>" class="form-control" readonly placeholder="<?=$survey['question_name'];?>" />
                                                    <?php   
                                                    }else if(strtolower($survey['input_field_type'])=='audio'){ ?>
                                                        <?=$survey['question_name'];?><?=$is_required;?>
                                                        <br>
														<input type="text" name="<?=$survey['field_name'];?>" class="form-control" readonly placeholder="<?=$survey['question_name'];?>" />
                                                    <?php   
                                                    }
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
                                    	<button type="button" class="btn btn-primary" name="saveData" >Submit</button>
									</div>
                                </div>
                            </form>
                            </div>
                        </div>
						
						<?php 
							return ob_get_clean();
							}
							$htmlss = htmlContent($conn,$survey_id);
							$myfile = fopen($htmlfile, "w") or die("Unable to open file..");
							$txt = str_replace("  "," ",$htmlss);
							fwrite($myfile, $txt);
							fclose($myfile);
							echo file_get_contents($htmlfile);
						}
						else{
							//echo "<h1>CACHE</h1>";
							echo file_get_contents($htmlfile);
						}
						?>
	
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<script src="js/select2.full.min.js"></script>
<script src="js/components-select2.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>


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
 
<script>
$(".datepicker").datepicker( {
    format: "dd-mm-yyyy",
    startView: "days", 
    minViewMode: "days"
});
</script>


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
    
</script>

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
<script type="text/javascript">
     $(function () {
         $('.datetimepicker1').datetimepicker();
     });
	 
 </script>  