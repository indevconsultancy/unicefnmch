<?php include_once('includes/config.php'); ?>
<?php define("title", "Add Form | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php
$client_qry = "";
if ($_SESSION['role_id'] == '3') {
    $client_id = $_SESSION['client_id'];
    $client_qry = " and projects.client_id='" . $client_id . "' ";
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
        background: rgb(57, 74, 89);
        z-index: 99999;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        color: #fff;
        line-height: 46px;
        font-size: 22px;
        transition: all .3s ease-in-out;
    }

    .add-button-bg a:hover {
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
    #main-content .wrapper .row {
        margin-bottom: 0px;
    }

    .panel {
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
            <div class='loader-icon'><img src="<?= base_url(); ?>img/<?=$_SESSION['logo_image']?>" alt=""></div>
        </div>
    </div>
</div>

<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Form </li>
                        <!--<a href=""survey-list.php></a> -->
                        <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-plus"></i>New Form</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- page start-->

        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12" style="min-height:420px;">
                                <header class="panel-heading mt-3">Add New Form
                                </header>
                                <section class="panel rounded-0">
                                    <div class="panel-body  rounded-0">
                                        <div class="form">
                                            <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                                                <div class="mb-3 row">
                                                    <label for="project_id" class="col-lg-2 col-form-label text-end">Project: <span style="color:red;">*</span></label>
                                                    <div class="col-lg-10">
                                                        <select class="form-control" name="project_id" id="project_id" required>
                                                            <option value="">Select Project</option>
                                                            <?php
                                                            $getProject = mysqli_query($conn, "SELECT project_id, project_name FROM `projects` WHERE status='0' $client_qry");
                                                            while ($projectid = mysqli_fetch_array($getProject)) { ?>
                                                                <option value="<?php echo $projectid['project_id']; ?>"><?php echo $projectid['project_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <hr class="line-horizontal">
                                                <div class="mb-3 row">
                                                    <label for="category_id" class="col-lg-2 col-form-label text-end">Thematic Area(s): <span style="color:red;">*</span></label>
                                                    <div class="col-lg-10">
                                                        <select class="form-control select2" multiple name="category_id[]" id="category_id" required>
                                                            <option value="" disabled>Select Thematic Area(s)</option>
                                                            <?php
                                                            $getCategoryname = mysqli_query($conn, "SELECT category_id, category_name FROM `categories` WHERE status='0' ORDER BY sequence ASC");
                                                            while ($categoryid = mysqli_fetch_array($getCategoryname)) { ?>
                                                                <option value="<?php echo $categoryid['category_id']; ?>"><?php echo $categoryid['category_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <hr class="line-horizontal">
                                                <div class="mb-3 row" id="other_text" style="display:none;">
                                                    <label for="other" class="col-lg-2 col-form-label text-end">Other Specify: <span style="color:red;">*</span></label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" name="other" id="other" type="text" />
                                                    </div>
                                                </div>

                                                <?php if ($_SESSION['role_id'] != '3') { ?>
                                                    <div class="mb-3 row">
                                                        <label for="client_id" class="col-lg-2 col-form-label text-end">Client Name: <span style="color:red;">*</span></label>
                                                        <div class="col-lg-10">
                                                            <select class="form-control abc_client" name="client_id" onchange="getClientsdata(this.value)" required>
                                                                <option value="">Select Client</option>
                                                                <?php
                                                                $getClients = mysqli_query($conn, "SELECT id, name FROM clients WHERE del_action='N' AND role_id='3'");
                                                                while ($client = mysqli_fetch_object($getClients)) { ?>
                                                                    <option value="<?= $client->id; ?>"><?= $client->name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <div class="mb-3 row">
                                                    <label for="survey_name_txt" class="col-lg-2 col-form-label text-end">Form Name: <span style="color:red;">*</span></label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" name="survey_name" id="survey_name_txt" required type="text" />
                                                        <p id="statsurvey" class="text-danger"></p>
                                                    </div>
                                                </div>
                                                <hr class="line-horizontal">

                                                <div class="mb-3 row">
                                                    <label class="col-lg-2 col-form-label text-end" style="display:none;">Select Language</label>
                                                    <div class="col-lg-10" style="display:none;">
                                                        <select class="form-control" required name="language_id_english">
                                                            <option value="1" selected>English</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-3 row">
                                                    <label for="file_check" class="col-lg-2 col-form-label"></label>
                                                    <div class="col-lg-10">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="file_check" id="upload_excel" onclick="toggleUpload(this.value)" value="1">
                                                            <label class="form-check-label" for="upload_excel">Upload Excel File</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="file_check" id="web_form" onclick="toggleUpload(this.value)" value="0">
                                                            <label class="form-check-label ps-2" for="web_form">Create Web Form</label>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        let toggleUpload = (value) => {
                                                            let upload = document.getElementById('upload_structure');
															let VerifySurvey = document.getElementById('VerifySurvey');
															let add_survey = document.getElementById('add_survey');
															let downloadTemplate = document.getElementById('downloadTemplate');
                                                            if (value === '0') {
                                                                upload.style.display = 'none';
																// VerifySurvey.style.display = 'none';
																// add_survey.style.display = 'block';
																// downloadTemplate.style.display = 'none';
																
                                                            } else {
                                                                upload.style.display = 'block';
																// VerifySurvey.style.display = 'block';
																// add_survey.style.display = 'none';
																// downloadTemplate.style.display = 'block';
                                                            }
                                                        }
                                                    </script>
                                                </div>

                                                <hr class="line-horizontal">

                                                <div class="mb-3" style="display: none;" id="upload_structure">
                                                    <div class="row">
                                                        <label for="structure" class="col-lg-2 col-form-label text-end">Select Excel File: <span style="color:red;">*</span></label>
                                                        <div class="col-lg-10">
                                                            <input class="form-control tooltips" name="structure" id="structure" accept=".xlsx, .xls" required data-bs-placement="top" data-bs-toggle="tooltip" minlength="5" type="file" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-10 text-end offset-lg-2">
													  
													  <button class="btn btn-secondary " type="button" name="add_survey" style="display:none" id="add_survey" <?= $_SESSION['BTNBLOCK']; ?>>Submit</button>
                                                      <span class="btn btn-success"  id="verified_button" style="display:none">Verified</span>
													  <button class="btn btn-danger" id="error_button"  style="display:none" type="button" data-bs-toggle="modal" data-bs-target="#xlsIssues" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-whatever="@fat">Error Found</button>
													  <button class="btn btn-primary" type="button" id="VerifySurvey" name="VerifySurvey" title="Verify XLSX FORM VALIDATION">Validate Form</button>
													  <a href="Questionnaire-sample.xlsx" class="btn btn-primary float-end ms-2" id="downloadTemplate"><i class="fa fa-download"></i> Download Template</a>
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

<div class="modal fade" id="xlsIssues" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: 500 !important;"  >XLS Errors</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="modal-body"  style="max-height: 400px; overflow: auto;">
					<h4 style="color: red;">We have found some errors in xls file.</h4>
					<p style="color: red;">Please correct the errors listed below and re-upload the Excel file.</p>
					<div id="errorMessages"></div>
					
				</div> 
				<div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button> 
                </div>
			</form>
		</div>
	</div>
</div>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>
<!--
<link href="<?= base_url(); ?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>assets/sweetalerts/sweetalert2.all.min.js"></script> -->
<?php
if ($_SESSION['ISMEMORYFULL']) { ?>
    <!-- <script>
		Swal.fire(
		  'Storage Full',
		  'Kindly renew the storage.',
		  'error'
		)
		</script>-->
<?php } ?>
<script>
    $(document).ready(function() {
        $('#survey_name_txt').keyup(function() {
            var survey_name_txt = $(this).val();
            var project_id = $('#project_id').val();
            if (survey_name_txt.length >= 3) {
                $.ajax({
                    url: "add-survey-ssajax1.php",
                    method: "POST",
                    data: {
                        survey_name_txt: survey_name_txt,
                        project_id: project_id
                    },
                    dataType: "text",
                    success: function(html) {
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
 $(document).ready(function() {
	$("#VerifySurvey").on('click', function() {
	var dd = new FormData();
	var file = $('#structure')[0].files[0];
	if (typeof file !== "undefined") {
		
		dd.append('structure', file);
		dd.append('validate_file', 'validate-file');
		console.log(file);
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
				$('.loading-indicator').removeClass('active');
				var result=JSON.parse(res);
				if (!result) { 
					swal.fire({
						title: "Verified Excel File.",
						icon: "success",
						confirmButtonColor: '#449A97',
						confirmButtonText: 'Ok'
					}).then(() => {
						$("#verified_button").show();
						$("#add_survey").show();
						$("#VerifySurvey").hide();
						$("#error_button").hide();
					});
				} else if (result.status == 0) {
					//console.log(result);
					swal.fire({
						title: "Invalid excel file.",
						icon: "warning",
						confirmButtonColor: '#449A97',
						confirmButtonText: 'Ok'
					})
					return;
				}else{
				swal.fire({
					title: "We have found errors in xls file.",
					icon: "warning",
					confirmButtonColor: '#449A97',
					confirmButtonText: 'Ok'
				}).then(() => {
					$("#xlsIssues").modal('show');
					$("#error_button").show();
					 $("#errorMessages").empty();
					 result.forEach(function(results) {
						$("#errorMessages").append("▶ " + results + "<br>");
					});
				});
			}
			}
		});
		}else{
			swal.fire({
				title: "Please Select the Excel File.",
				icon: "warning",
				confirmButtonColor: '#449A97',
				confirmButtonText: 'Ok'
			});
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
        } else {
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
                    } else if (ress.status == 3) {
                        console.log(ress);
                        swal.fire({
                            title: "Something went wrong",
                            icon: "warning",
                            confirmButtonColor: '#449A97',
                            confirmButtonText: 'Ok'
                        })
                        return;
                    }else {
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
	
	 $("#web_form").on('click', function() {
		$('#VerifySurvey').hide();
		$('#add_survey').show();
		
	 });
	 
	 $("#upload_excel").on('click', function() {
		$('#VerifySurvey').show();
		$('#add_survey').hide();
		
	 });
</script>