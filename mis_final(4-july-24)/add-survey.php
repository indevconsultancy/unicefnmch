<?php include_once('includes/config.php'); ?>
<?php define("title","Add Form | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php
  $client_qry = "";
	if($_SESSION['role_id']=='3'){
		$client_id = $_SESSION['client_id'];
		$client_qry=" and projects.client_id='".$client_id."' ";
		
	}

?>
<!--main content start-->
<style>
    .panel-heading {
    background: #394a59;
    color: white;
    font-weight: unset;
	}
	.btn:not(:disabled):not(.disabled) {
		cursor: pointer;
	}
	.add-button-bg a {
		position: fixed;
		bottom: 54px;
		right: 50px;
		background: rgb(57,74,89);
		z-index: 99999;
		border-radius: 50%;
		width: 60px;
		height: 60px;
		color: #fff;
		line-height: 46px;
		font-size: 22px;
		transition: all .3s ease-in-out;
	}
	.add-button-bg a:hover{
		background: rgb(4 39 60);
		color: #ffffff;
		-webkit-transform: rotate(90deg);
		transform: rotate(90deg);
		box-shadow: 1px 1px 1px 17px rgb(255 192 192 / 28%);
		
	}
	
	.sm_value1 {
		padding: 3px;
		color: #ffffff;
		border-radius: 5px;
		min-width: 45px;
		text-align: center;
		font-size: 20px;
		font-weight: 700;
		background: #033d66;
		width: 20px;
	}
</style>
<style>
#main-content .wrapper .row{
	margin-bottom:0px;
}
.panel{
	margin-bottom: 20px;
}
.panel .panel-heading {
    margin-top: -10px;
}
input#web_form {
    margin-left: 10px;
}
</style>

<!--<div class="loading-indicator">
	<div class="lds-facebook"><div></div><div></div><div></div></div>
</div>-->
<div id="pre-load" class="loading-indicator">
   <div id="loader" class="loader">
	   <div class="loader-container">
		   <div class='loader-icon'><img src="https://mquad.org/mis/img/mquad-logo.png" alt=""></div>
	   </div>
   </div>              
</div>

<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="icon_documents_alt"></i>Form </li> <!--<a href=""survey-list.php></a> -->
                    <li><i class="fa fa-plus"></i>New Form</li>
                </ol>
            </div>
        </div>
        <!-- page start-->
        
        <div class="row">
            <div class="col-lg-12">
            	<section class="panel">
                    <div class="panel-body">
                    	<div class="row">
                        	<div class="col-lg-12" style="min-height:420px;">
                            	<header class="panel-heading">Add New Form 
								</header>
                                <section class="panel">
                                    <div class="panel-body">
                                        <div class="form">
										
                                            <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
											
                                                  <div class="form-group " >
                                                    <label for="ctegoryname" class="control-label col-lg-2">Project: <span style="color:red;">*</span></label>    
													<div class="col-lg-10" >
                                                        <select class="form-control" name="project_id" id="project_id" required>
                                                            <option value="">Select Project</option>
                                                            <?php
                                                                $getProject = mysqli_query($conn,"SELECT project_id,project_name FROM `projects` where status='0' $client_qry");
                                                                while($projectid = mysqli_fetch_array($getProject)){ ?>
                                                                    <option value="<?php echo $projectid['project_id'];?>"><?php echo $projectid['project_name'];?></option>
                                                                <?php	
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
												<div class="form-group">
													<label for="categoryname" class="control-label col-lg-2">Thematic Area: <span style="color:red;">*</span></label>
													<div class="col-lg-10">
														<select class="form-control select2" multiple name="category_id[]" class="category_id" id="category_id" required>
															<option value="">Select Thematic Area</option>
															<?php
																$getCategoryname = mysqli_query($conn, "SELECT category_id, category_name FROM `categories` WHERE status='0' ORDER BY sequence ASC");
																while ($categoryid = mysqli_fetch_array($getCategoryname)) { ?>
																	<option value="<?php echo $categoryid['category_id']; ?>"><?php echo $categoryid['category_name']; ?></option>
																<?php }
															?>
														</select>
													</div>
												</div>
												<div class="form-group" id="other_text" style="display:none;">
													<label for="cname" class="control-label col-lg-2">Other Specify: <span style="color:red;">*</span></label>
													<div class="col-lg-10">
														<input class="form-control" name="other" id="other" type="text" />
													</div>
												</div>

                                                <?php if($_SESSION['role_id']!='3') { ?>
                                                <div class="form-group " >
                                                    <label for="cname" class="control-label col-lg-2">Client Name: <span style="color:red;">*</span></label>
                                                    <div class="col-lg-10">
                                                        <select class="form-control abc_client" name="client_id" onchange="getClientsdata(this.value)" required>
                                                            <option value="">Select Client</option>
                                                            <?php
                                                                $getClients = mysqli_query($conn,"SELECT id,name FROM clients WHERE del_action='N' AND role_id='3'");
                                                                while($client = mysqli_fetch_object($getClients)){ ?>
                                                                    <option value="<?=$client->id;?>"><?=$client->name;?></option>
                                                                <?php	
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                
                                                <div class="form-group " id="survey_name">
                                                    <label for="cname" class="control-label col-lg-2">Form Name: <span style="color:red;">*</span></label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" name="survey_name" id="survey_name_txt" required type="text" />
													 <p id="statsurvey" style="color:red;"></p>
													</div>
													
                                                    <br><br>
                                                    <label for="cname" class="control-label col-lg-2" style="display:none;">Select Language</label>
                                                    <div class="col-lg-10" style="display:none;">
                                                        <select class="form-control" required name="language_id_english">
                                                            <option value="1" selected >English</option>
                                                        </select>
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group ">
                                                    <label for="cname" class="control-label col-lg-2">
                                                      </label>
                                                    <div class="col-lg-10">
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="radio" name="file_check" id="upload_excel" onclick="toggleUpload(this.value)" value="1">
															<label class="form-check-label" for="upload_excel">Upload Excel </label>

															<input class="form-check-input" type="radio" name="file_check" id="web_form" onclick="toggleUpload(this.value)" value="0">
															<label class="form-check-label" for="web_form">Create Web Form</label>

                                                        </div>
														<script>
															let toggleUpload = (value) => {
																let upload = document.getElementById('upload_structure');
																if (value === '0') {
																	upload.style.display = 'none';
																} else {
																	upload.style.display = 'block';
																}
															}
														</script>
														<!--<div class="form-check">
															<input class="form-check-input" type="checkbox" name="file_check"
                                                                id="file_check" onclick="upload_file(this.value)"  value="0" >
                                                            <label class="form-check-label"  for="exampleRadios1">
                                                            Upload From File
                                                            </label>
															<input class="form-check-input" type="checkbox" name="file_check"
                                                                id="file_check" onclick="upload_file(this.value)"  value="0" >
                                                            <label class="form-check-label"  for="exampleRadios1">
                                                           Web Form
                                                            </label>
														</div>

														<div class="form-group" style="display: none;" id="upload_structure">
															<label for="structure" class="control-label">Select File:</label>
															<input class="form-control" name="structure" id="structure" accept=".xlsx, .xls" type="file">
														</div>
                                                        <script>
                                                           let upload_file = (value) => {
                                                                let upload = document.getElementById('upload_structure');
                                                                console.log(value);
                                                                if (value == '0'){
                                                                    upload.style.display = 'none';
                                                                    document.getElementById('file_check').value = 0;
                                                                }
                                                            
                                                                if (value == '1'){
                                                                    upload.style.display = 'block';
                                                                    document.getElementById('file_check').value = 1;
                                                                }
                                                            }
                                                            
                                                        </script>-->
														
                                                    </div>
                                                </div>
                                                <div class="form-group " style="display: none;" id="upload_structure">
                                                    <label for="cname" class="control-label col-lg-2">Select Excel File: <span style="color:red;">*</span></label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control tooltips" name="structure" id="structure" accept=".xlsx,.xls," required data-placement="top" data-toggle="tooltip"  minlength="5" type="file" />
												   </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-offset-2 col-lg-10">
														<button class="btn btn-secondary" type="button" name="add_survey" id="add_survey" <?=$_SESSION['BTNBLOCK'];?> >Submit</button>
														<a href="Questionnaire-sample.xlsx" class="btn btn-primary pull-right "><i class="fa fa-download"></i> Download Template</a>
                                                   </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </section>
                            </div>
							
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
<!--
<link href="<?=base_url();?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?=base_url();?>assets/sweetalerts/sweetalert2.all.min.js"></script> -->
<?php 
	if($_SESSION['ISMEMORYFULL']){ ?>
		<!-- <script>
		Swal.fire(
		  'Storage Full',
		  'Kindly renew the storage.',
		  'error'
		)
		</script>-->
	<?php } ?>
<script>
$(document).ready(function(){
	$('#survey_name_txt').keyup(function(){
		var survey_name_txt=$(this).val();
		var project_id = $( '#project_id' ).val();
		if(survey_name_txt.length >= 3){
		$.ajax({
			url:"add-survey-ssajax1.php",
			method:"POST",
			data:{survey_name_txt:survey_name_txt,project_id:project_id},
			dataType:"text",
			success:function(html)
			{
				//console.log(html);
				//alert(html);
				$('#statsurvey').html(html);
				
			}
		})
		}
	})
 })
</script>
<script>
$(document).ready(function() {
    $("#category_id").on('change', function() {
        var category_id = $(this).val();
        if (category_id.includes('17')) {
            $("#other_text").show();
            $("#other").prop('required', true);
        } else {
            $("#other_text").hide();
            $("#other").prop('required', false);
        }
    });
});
</script>

<script>
$("#add_survey").on('click', function() {
    var dd = new FormData();
    var file = $('#structure')[0].files[0];
    var survey_name = $('#survey_name_txt').val();
    var project_id = $('#project_id').val();
    var category_id = $('#category_id').val();
    var other = $('#other').val();
	//alert(other);
    var file_check = $('input[name="file_check"]:checked').val();
    var survey_id = "";
    var add_survey = "sss";
    var language_id = 1;

    if (typeof file !== "undefined") {
        dd.append('structure', file);
    }

    dd.append('survey_name', survey_name);
    dd.append('project_id', project_id);
    dd.append('survey_id', survey_id);
    dd.append('add_survey', add_survey);
    dd.append('language_id', language_id);
    dd.append('category_id', category_id);
    dd.append('other', other);
    dd.append('file_check', file_check);
	
    if (survey_name === "" || category_id === "" || project_id === "") {
		swal.fire({
			title: "All fields are required.",
			icon: "warning",
			confirmButtonColor: '#449A97',
			confirmButtonText: 'Ok'
		});
        return;
    } else if (typeof file_check === "undefined") {
        /* Swal.fire(
            'Error',
            'Please select form type',
            'error'
        ); */
		swal.fire({
			title: "Please Select Option to Upload or Create Form",
			icon: "warning",
			confirmButtonColor: '#449A97',
			confirmButtonText: 'Ok'
		});
        return;
    } else if (file_check != 0 && (typeof file === "undefined" || file === null)) {
        swal.fire({
			title: "Please upload excel file.",
			icon: "warning",
			confirmButtonColor: '#449A97',
			confirmButtonText: 'Ok'
		});
        return;
    } else if (category_id.includes('17') && other === "") {
        swal.fire({
            title: "Please enter other specify.",
            icon: "warning",
            confirmButtonColor: '#449A97',
            confirmButtonText: 'Ok'
        });
        return;
    }else {
        $.ajax({
            url: "add-survey-ssajax1.php",
            type: 'POST',
            data: dd,
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function() {
               $('.loading-indicator').addClass('active');
            },
            success: function(res) {
                console.log(res);
               $('.loading-indicator').removeClass('active');

                let ress = JSON.parse(res);
                if (ress.status == 1) {
                    console.log(ress);
                    window.location.href = 'survey-list.php';
                } else if (ress.status == 2) {
                    console.log(ress);
					swal.fire({
						title: "Please upload excel file.",
						icon: "warning",
						confirmButtonColor: '#449A97',
						confirmButtonText: 'Ok'
					 })
					return;
                } else if (ress.status == 0) {
                    console.log(ress);
					swal.fire({
						title: "Invalid Excel Format.",
						icon: "warning",
						confirmButtonColor: '#449A97',
						confirmButtonText: 'Ok'
					 })
					 return;
                } else {
                    console.log(ress);
					swal.fire({
						title: "Something went wrong.",
						icon: "warning",
						confirmButtonColor: '#449A97',
						confirmButtonText: 'Ok'
					})
                }  
            }
        });
    }
});

</script>