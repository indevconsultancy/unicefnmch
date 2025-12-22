<?php include_once('includes/config.php'); ?>
<?php define("title", "List Users | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>



<?php
$id = $_GET['id'];
$client_id = $_SESSION['client_id'];
$clientId = "C" . $client_id;

$query = "SELECT id,questionnaire_type,survey_bank.user_id,users.client_id,users.name,survey_title,description,client_name,survey_bank.category_id,survey_bank.published_date,tool_study_year_from,tool_study_year_to,survey_bank.source,survey_bank.uploaded_questionnaire,survey_bank.tool_archive_status,survey_bank.tool_access FROM `survey_bank` left join users on users.user_id=survey_bank.user_id where survey_bank.status='0' and  survey_bank.id=$id";

$get_query = mysqli_query($conn, $query);

$tooldata = mysqli_fetch_assoc($get_query);
$category_id = $tooldata['category_id'];
?>


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

    .btn-success:hover,
    .btn-success:focus,
    .btn-success:active,
    .btn-success.active,
    .open .dropdown-toggle.btn-success {
        color: #ffffff;
        border-color: #003b64;
        background: #003b64;
    }

    .btn-danger:hover,
    .btn-danger:focus,
    .btn-danger:active,
    .btn-danger.danger,
    .open .dropdown-toggle.btn-danger {
        color: #ffffff;
        border-color: #003b64;
        background: #003b64;
    }

    .widget .padd,
    .modal-body {
        background-color: white;
        min-height: 93px;
    }

    .form-group1 {
        width: 20%;
    }

    .SumoSelect.open>.optWrapper {
        width: 500px;
    }

    .SumoSelect .select-all {
        padding: 1px 0 3px 35px;
    }

    .SumoSelect {
        width: 100% !important;
    }

    .SumoSelect .select-all {
        border-radius: 3px 3px 0 0;
        position: relative;
        border-bottom: 1px solid #ddd;
        background-color: #fff;
        padding: 1px 5px 2px 35px !important;
        height: 20px;
        cursor: pointer;
    }

    #main-content .wrapper .row {
        margin-bottom: 0px;
    }

    .panel {
        margin-bottom: 20px;
    }

    
	@media (max-width: 768px) {
		.survey-box .survey-container .survey-data{
			margin-bottom: 10px;
		}
		.survey-box .survey-container .survey-heading p, .survey-box .survey-container .survey-data p , .access-type{
			margin-bottom: 0px;
			margin-top: 10px;
		}
	}


</style>
<link href="<?= base_url(); ?>/css/sumoselect.css"> 

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Tools Archive</li>
                        <li class="breadcrumb-item" aria-current="page"><i class="fa fa-list"></i>View Tools </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="survey-box" id="tools_id-<?= $tooldata['id']; ?>">


            <div class="survey-container">
                <div class="survey-heading">
                    <p><i class="fa fa-address-card-o"></i> Name:</p>
                </div>
                <div class="survey-data">
                    <p> <?php echo $tooldata['survey_title']; ?>
                    </p>

                </div>
            </div>
            <div class="survey-container">
                <div class="survey-heading">
                    <p><i class="fa fa-calendar"></i> Year of Study:</p>
                </div>
                <div class="survey-data">
                    <p> <?= $tooldata['tool_study_year_from'] . "-" . $tooldata['tool_study_year_to'] ?>
                    </p>

                </div>
            </div>
            <div class="survey-container">
                <div class="survey-heading">
                    <p><i class="fa fa-file"></i> Description:</p>
                </div>
                <div class="survey-data">
                    <p> <?= $tooldata['description']; ?>
                    </p>

                </div>
            </div>
            <div class="survey-container">
                <div class="survey-heading">
                    <p><i class="fa fa-list-alt"></i> Thematic Area:</p>
                </div>
                <div class="survey-data">
                    <?php
                    $sqlcdata = "SELECT GROUP_CONCAT(category_name) as category_name FROM `categories` WHERE `category_id` IN (" . $category_id . ") $qrycat";
                    $sqlcates = mysqli_query($conn, $sqlcdata);
                    $rows = mysqli_fetch_array($sqlcates);

                    ?>
                    <p><?php echo $rows['category_name']; ?></p>
                </div>
            </div>
            <div class="survey-container">
                <div class="survey-heading">
                    <p><i class="fa fa-building"></i> Institution/Organization:</p>
                </div>
                <div class="survey-data">
                    <p><?php echo $tooldata['client_name']; ?></p>
                </div>
            </div>

            <div class="survey-container">
                <div class="survey-heading">
                    <p><i class="fa fa-low-vision"></i> Tool Access:</p>
                </div>
                <div class="survey-data">
                    <p><?php echo $tooldata['tool_access']; ?></p>
                </div>
            </div>
            <div class="survey-container">
                <div class="survey-heading">
                    <p><i class="fa fa-download"></i> Questionnaire: </p>
                </div>
                <div class="survey-data">
                    <?php if ($tooldata['questionnaire_type'] == "File") { ?>
                        <p><a href="upload_data_file/tools_archive_datafile/<?= $clientId; ?>/<?php echo $tooldata['uploaded_questionnaire']; ?>" target="_blank"><?php echo $tooldata['uploaded_questionnaire']; ?></a></p>
                    <?php } else { ?>
                        <p><a href="<?php echo $tooldata['uploaded_questionnaire']; ?>" target="_blank"><?php echo $tooldata['uploaded_questionnaire']; ?></a></p>

                    <?php  } ?>
                </div>
            </div>


        </div>
    </section>
</section><?php include_once('includes/footer.php'); ?>

<?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') { ?>
    <script>
        swal.fire({
            title: "<?php echo $_SESSION['status']; ?>",
            icon: "<?php echo $_SESSION['status_code']; ?>",
            confirmButtonColor: '#449A97',
            confirmButtonText: 'Ok'
        });
    </script>
<?php unset($_SESSION['status']);
} ?>