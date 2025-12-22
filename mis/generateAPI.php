<?php include_once('includes/config.php'); ?>
<?php define("title", "Generate API | MQUAD"); ?>
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
<?php

$pqry = '';
if ($_GET['sid'] != '') {
	$pid = $_GET['sid'];
	$pqry = " and survey.project_id='" . $pid . "' ";
$query = "SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data, survey.survey_name,survey.project_id,survey.created_at,survey.client_id, survey.status, survey.user_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.id='".$pid."'";
$get_query = mysqli_query($conn, $query);
$get_data=mysqli_fetch_object($get_query);
$unid=$get_data->user_id.$get_data->client_id;
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
            <div class='loader-icon'><img src="https://mquad.org/mis/img/mquad-logo.png" alt=""></div>
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
                        <li class="breadcrumb-item active" aria-current="page"><i class="fa fa-plus"></i>Generate API</li>
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
                                <header class="panel-heading mt-3">Form Name: <?=$get_data->survey_name?>
                                </header>
                                <section class="panel rounded-0">
                                    <div class="panel-body  rounded-0">
									 <div class="row">
									 <div class="col-lg-7">
									 
									 <label>List of available API </label>
									 </div>
									 <div class="col-lg-5 border">
									   <p class="col-form-label text-start text-black">Version: <span style="color:#645c5c;">V1</span></p>
                                       <p class="col-form-label text-start text-black">Type: <span style="color:#645c5c;">RESTful API</span></p>             
                                       <p class="col-form-label text-start text-black ">Authorization Key: <input style="border:.5px dashed #ccc; width:60%; font-size:11px;" type="text" value="<?=encrypt_url($unid) ?>" id="myInput"><button onclick="myFunction()">Copy</button></p> 									   
									 </div>
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
    function myFunction() {
  // Get the text field
  var copyText = document.getElementById("myInput");

  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

   // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);

  // Alert the copied text
  alert("Copied the text: " + copyText.value);
}

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