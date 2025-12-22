<?php include_once('includes/config.php'); ?>
<?php define("title", "My Tools | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];
$qryUser = '';
$qryUser2 = '';

if ($role_id == '1') {
    $qryUser = "";
}
if ($role_id == 3) {
    $qryUser = " and survey_bank.user_id='" . $user_id . "'";
}

?>
<?php
$page = $_GET['page'];
$pages = '';
if ($page != '') {
    $pages = $_GET['page'];
} else {
    $pages = 1;
}
?>
<?php
$qry = '';
if (isset($_REQUEST['search'])) {
    if (isset($_REQUEST['survey_title']) && $_REQUEST['survey_title'] != '') {
        $qry = " and 	survey_title like '%" . $_REQUEST['survey_title'] . "%' ";
    }
    if (isset($_REQUEST['from_date']) && $_REQUEST['from_date'] != '') {
        $qry = " and tool_study_year_from ='" . $_REQUEST['from_date'] . "' ";
    }
    if (isset($_REQUEST['to_date']) && $_REQUEST['to_date'] != '') {
        $qry = " and tool_study_year_to ='" . $_REQUEST['to_date'] . "' ";
    }
}

?>

<?php

//pagination
$per_page = 10;
$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$page_url = "?";
$page_url = isset($_GET['category_id']) ? $page_url . "category_id=" . $_GET['category_id'] : $page_url;
$page_url = isset($_GET['question_type']) ? $page_url . "&question_type=" . $_GET['question_type'] : $page_url;
$page_url = isset($_GET['search']) ? $page_url . "&search=" . $_GET['search'] : $page_url;

$page = 0;
$current_page = 1;
if (isset($_GET['page'])) {
    $current_page = intval($_GET['page']);
    $page = ($current_page - 1) * $per_page;
}
// echo "SELECT id,survey_bank.user_id,users.name,tool_study_year_from,tool_study_year_to,survey_title,description,client_name,survey_bank.category_id,survey_bank.published_date,survey_bank.source,survey_bank.uploaded_questionnaire,survey_bank.tool_archive_status,survey_bank.tool_access FROM survey_bank left join users on users.user_id=survey_bank.user_id where survey_bank.status='0' $qryUser $qry order by id DESC";
$query = "SELECT id,survey_bank.user_id,users.name,tool_study_year_from,tool_study_year_to,survey_title,description,client_name,survey_bank.category_id,survey_bank.published_date,survey_bank.source,survey_bank.uploaded_questionnaire,survey_bank.tool_archive_status,survey_bank.tool_access FROM survey_bank left join users on users.user_id=survey_bank.user_id where survey_bank.status='0' $qryUser $qry order by id DESC";

$get_query = mysqli_query($conn, $query);
$total_record = mysqli_num_rows($get_query);
$total_pages = ceil($total_record / $per_page);
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

    .panel {
        margin-bottom: 20px;
    }

    #main-content .wrapper .row {
        margin-bottom: 0px;
    }

    /* Flexbox styling for the header */
    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .panel-heading {
        padding: 10px;
        background-color: #f5f5f5;
        border-bottom: 1px solid #ddd;
    }

    /* Button styling */
    .btn-md {
        padding: 5px 10px;
        font-size: 14px;
    }

    /* Tooltip styling */
    .tooltips {
        cursor: pointer;
    }

    .row-bottom.text-end {
        margin-top: 4px;
        margin-bottom: 4px;
    }

    /* Custom tooltip styling */
    .tooltips+.tooltip>.tooltip-inner {
        background-color: #000;
        color: #fff;
        text-align: justify;
    }

    .tooltips+.tooltip.top .tooltip-arrow {
        border-top-color: #000;
    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Tools Archive</li>
                        <li class="breadcrumb-item" aria-current="page"><i class="fa fa-list"></i></i>My Tools</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- page start-->
        <div class="container-fluid1 mb-3">
            <form method="GET">
                <div class="row g-2">
                    <div class="form-group col-md-4">
                        <input type="text" class="form-control" name="survey_title" id="survey_title" value="<?= @$_REQUEST['survey_title'] ?>" placeholder="Name	" title="Name" data-bs-toggle="tooltip" data-bs-placement="top">
                    </div>
                    <div class="form-group col-md-2">
                        <input class="form-control" id="from_datepicker" placeholder="From Year" value="<?= @$_REQUEST['from_date'] ?>" name="from_date" type="text" />

                    </div>
                    <div class="form-group col-md-2">
                        <input class="form-control" id="to_datepicker" placeholder="To Year" value="<?= @$_REQUEST['to_date'] ?>" name="to_date" type="text" />
                    </div>

                    <div class="form-group col-md-2">
                        <button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" id="btnsearch" name="search" disabled>Search</button>
                    </div>
                    <div class="form-group col-md-2">
                        <a href="my_tool.php" class="btn btn-primary width-md waves-effect waves-light form-control">Clear
                            Filter</a>
                    </div>
                </div>
            </form>
        </div>
        <section class="panel p-2">
            <header class="table-title d-flex justify-content-between align-items-center">
                <div>My Tool(s): <?= $total_record ?> </div>
                <div class="row-bottom text-end">
                    <form enctype="multipart/form-data" action="" method="post">

                        <a class="btn btn-md btn-primary" href="add-contribute-surveybank.php"><i class="fa fa-plus"></i> Add Tool</a>
                    </form>
                </div>
            </header>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th width="110">Year of Study</th>
                            <th>Thematic Area</th>
                            <th>Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $_SESSION['query'] = "SELECT id,survey_bank.user_id,users.name,tool_study_year_from,tool_study_year_to,survey_title,description,client_name,survey_bank.category_id,survey_bank.published_date,survey_bank.source,survey_bank.uploaded_questionnaire,survey_bank.tool_archive_status,survey_bank.tool_access FROM survey_bank left join users on users.user_id=survey_bank.user_id where survey_bank.status='0' $qryUser order by id DESC";
                        $_SESSION['query'] = "SELECT survey_bank.id, survey_bank.user_id, users.name, survey_bank.tool_study_year_from, survey_bank.tool_study_year_to, survey_bank.survey_title, survey_bank.description, survey_bank.client_name, GROUP_CONCAT(categories.category_name) as category_name, survey_bank.published_date, survey_bank.source, survey_bank.uploaded_questionnaire, survey_bank.tool_archive_status, survey_bank.tool_access FROM survey_bank LEFT JOIN users ON users.user_id = survey_bank.user_id LEFT JOIN (SELECT category_id, category_name FROM categories) categories ON FIND_IN_SET(categories.category_id, survey_bank.category_id) WHERE survey_bank.status = '0' $qryUser GROUP BY survey_bank.id ORDER BY survey_bank.id DESC";
                        $sqltools = "SELECT id,survey_bank.user_id,users.name,tool_study_year_from,tool_study_year_to,survey_title,description,client_name,survey_bank.category_id,survey_bank.published_date,survey_bank.source,survey_bank.uploaded_questionnaire,survey_bank.tool_archive_status,survey_bank.tool_access FROM survey_bank left join users on users.user_id=survey_bank.user_id where survey_bank.status='0' $qryUser $qry order by id DESC limit $page,$per_page";
                        $getsql = mysqli_query($conn, $sqltools);
                        $sn = 1 + $page;
                        if (mysqli_num_rows($getsql) > 0) {
                            while ($gettool = mysqli_fetch_array($getsql)) {

                                $category_id = $gettool['category_id'];

                        ?>
                                <tr>
                                    <td>
                                        <?= $sn++; ?>
                                    </td>
                                    <td>
                                        <?= $gettool['survey_title'] ?>
                                    </td>
                                    <td>
                                        <?= $gettool['tool_study_year_from'] . "-" . $gettool['tool_study_year_to'] ?>
                                    </td>
                                    <td>
                                        <?php
                                        $sqlcdata = "SELECT GROUP_CONCAT(category_name) as category_name FROM `categories` WHERE `category_id` IN (" . $category_id . ") $qrycat";
                                        $sqlcates = mysqli_query($conn, $sqlcdata);
                                        $rows = mysqli_fetch_array($sqlcates);

                                        ?>

                                        <?php echo $rows['category_name']; ?>
                                    </td>


                                    <?php if ($_SESSION['role_id'] == '3') { ?>
                                        <td>
                                            <?php
                                            if ($gettool['tool_archive_status'] == '0') { ?>
                                                <span class="btn btn-warning ">Pending</span>

                                            <?php
                                            } else if ($gettool['tool_archive_status'] == '1') {
                                            ?>
                                                <span class="label label-success">Approved</span>
                                            <?php
                                            } else if ($gettool['tool_archive_status'] == '2') {
                                            ?>
                                                <span class="label label-danger">Rejected</span>
                                            <?php
                                            }

                                            ?>
                                        </td>
                                    <?php } ?>

                                    <?php if ($_SESSION['role_id'] == '1') { ?>
                                        <td>
                                            <form method="post" action="">
                                                <?php
                                                if ($gettool['tool_archive_status'] == '0') { ?>
                                                    <button class="btn btn-warning btn-sm" data-bs-target="#Modalassignque<?= $gettool['id'] ?>" onclick="assignque(<?= $gettool['id']; ?>)" ; type="button" data-bs-toggle="modal" data-bs-keyboard="false" data-bs-whatever="@fat">
                                                        Pending</button>
                                                <?php
                                                } else if ($gettool['tool_archive_status'] == '1') {
                                                ?>
                                                    <button class="btn btn-success btn-sm" data-bs-target="#Modalassignque<?= $gettool['id'] ?>" onclick="assignque(<?= $gettool['id']; ?>)" ; type="button" data-bs-toggle="modal" data-bs-keyboard="false" data-bs-whatever="@fat">
                                                        Approved</button>

                                                <?php
                                                } else if ($gettool['tool_archive_status'] == '2') {
                                                ?>
                                                    <button class="btn btn-danger btn-sm " data-bs-target="#Modalassignque<?= $gettool['id'] ?>" onclick="assignque(<?= $gettool['id']; ?>)" ; type="button" data-bs-toggle="modal" data-bs-keyboard="false" data-bs-whatever="@fat">
                                                        Rejected</button>
                                                <?php
                                                }

                                                ?>
                                            </form>
                                        </td>
                                    <?php } ?>
                                    <td>
                                        <?php
                                        if ($_SESSION['user_id'] == $gettool['user_id']) { ?>
                                            <a href="edit-survey-bank.php?eid=<?= $gettool['id'] ?>" class="btn-sm btn-primary py-2" title="Edit  tool"><i class="fa fa-pencil-square-o"></i></a>
                                        <?php
                                        }
                                        ?>
                                        <?php if ($_SESSION['user_id'] == $gettool['user_id'] || $_SESSION['role_id'] == '1') { ?>
                                            <a href="javascript:void(0);" data-id="<?= $gettool['id']; ?>" class="btn-sm btn-primary delQuestions py-2 "><i class="fa fa-trash"></i></a>
                                        <?php } else { ?>

                                        <?php } ?>
                                    </td>
									
									<div class="modal fade assignque_model" id="Modalassignque<?= $gettool['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static"
										data-bs-keyboard="false">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title" id="exampleModalLabel">
                                                        <span>Change question bank status</span>
                                                    </h1>
                                                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body" style="background-color:white;">

                                                        <div class="profile-inner-bg mt-0 mb-3">
                                                            <span style="font-weight: 400;">Do you want to approve or reject the
                                                                question?</span>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form class="login-form" action="" method="POST">
                                                            <input type="hidden" name="id" id="id" class="form-control" value="<?= $gettool['id']; ?>" />
                                                            <?php
                                                            if ($gettool['tool_archive_status'] == '0') {
                                                            ?>
                                                                <button type="submit" class="btn btn-md btn-success approveID" data-id="<?= $gettool['id']; ?>" name="approve">Approve</button>
                                                                <button type="submit" class="btn btn-md btn-danger rejectID" data-id="<?= $gettool['id']; ?>" name="reject">Reject</button>
                                                            <?php
                                                            } else if ($gettool['tool_archive_status'] == '1') {
                                                            ?>
                                                                <button type="submit" class="btn btn-md btn-danger rejectID" data-id="<?= $gettool['id']; ?>" name="reject">Reject</button>
                                                                <button type="button" class="btn btn-md btn-secondary" data-dismiss="modal">Close</button>
                                                            <?php
                                                            } else if ($gettool['tool_archive_status'] == '2') {
                                                            ?>
                                                                <button type="submit" class="btn btn-md btn-success approveID" data-id="<?= $gettool['id']; ?>" name="approve">Approve</button>
                                                                <button type="button" class="btn btn-md btn-secondary" data-dismiss="modal">Close</button>
                                                            <?php
                                                            }
                                                            ?>
                                                        </form>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                        <?php }
                        } else {
                            echo '<tr><td colspan="7" class="text-center" style="font-size: 25px;"  >Records Not Found !!<br/>   </td></tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
        </section>
        </div>
        </div>
        <div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
            <div class="col-md-10">
                <div class="" id="pagination">
                    <?= paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>
                </div>
            </div>
            <?php
            $_SESSION['file_name'] = 'My-Tool.csv';
            $_SESSION['header_column'] = "S.No,Name,From Date,To Date,Thematic Area";
            $_SESSION['db_column'] = "id,survey_title,tool_study_year_from,tool_study_year_to,category_name";
            ?>
            <div class=" col-md-2 export-csv text-end" style="margin-bottom: 0rem!important; padding-top: 5px">
                <a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
            </div>

        </div>
        <!-- page end-->
    </section>
</section>
<?php include_once('includes/footer.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

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


<script>
    $("#category_id,#survey_title,#from_datepicker,#to_datepicker").on("change", function() {

        if ($("#category_id").val() != '' || $("#survey_title").val() != '' || $("#from_datepicker").val() != '' || $("#to_datepicker").val() != '') {
            $('#btnsearch').prop('disabled', false);
        } else {
            $('#btnsearch').prop('disabled', true);
        }
    });
</script>
<script>
    $(".delQuestions").on("click", function(e) {
        let questionid = $(this).data("id");
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure to Delete this Question?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#449A97',
            cancelButtonColor: '#449A97',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "ajax/get_ajax.php",
                    type: "post",
                    data: {
                        tool_questionid: questionid
                    },
                    success: function(res) {
                        var ress = JSON.parse(res);
                        if (ress.status == "1") {
                            $("#qid-" + questionid).hide();
                            Swal.fire({
                                title: 'Question deleted successfully',
                                icon: 'success',
                                confirmButtonColor: '#449A97',
                                confirmButtonText: 'Ok'
                            })
                            window.location.reload();
                        }
                    }
                })
            }
        });
    })
	function assignque(val) {
		//alert(val);
		$("#question_bank_id").attr("value", val);
	}
    $(".approveID").on("click", function(e) {
        let approveid = $(this).data("id");
        // alert(approveid)
        e.preventDefault();
        $.ajax({
            url: "ajax/get_ajax.php",
            type: "post",
            data: {
                tool_approveID: approveid
            },
            success: function(res) {
                var ress = JSON.parse(res);
                if (ress.status == "1") {
                    Swal.fire({
                        title: 'Approved successfully',
                        icon: 'success',
                        confirmButtonColor: '#449A97',
                        confirmButtonText: 'Ok'
                    })
                    $(".assignque_model").modal('hide');
                    window.location.reload();
                }
            }
        })
    })
    $(".rejectID").on("click", function(e) {
        let rejectID = $(this).data("id");
        e.preventDefault();
        $.ajax({
            url: "ajax/get_ajax.php",
            type: "post",
            data: {
                tool_rejectID: rejectID
            },
            success: function(res) {
                var ress = JSON.parse(res);
                if (ress.status == "1") {
                    Swal.fire({
                        title: 'Rejected successfully',
                        icon: 'success',
                        confirmButtonColor: '#449A97',
                        confirmButtonText: 'Ok'
                    })
                    $(".assignque_model").modal('hide');
                    window.location.reload();
                }
            }
        })
    })
</script>

<script>
    $(document).ready(function() {
        $('#category_id').change(function() {
            var category_id = $(this).val();
            $.ajax({
                url: 'ajax/get_sub_themes_ajax.php',
                type: 'POST',
                data: {
                    category_id: category_id
                },
                success: function(response) {
                    $('#sub_theme').html(response);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
<script type="text/javascript">
    var currentYear = new Date().getFullYear();
    $("#from_datepicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        endDate: new Date(currentYear, 11, 31)
    }).on("changeDate", function(selected) {
        var minDate = new Date(selected.date.valueOf());
        $("#to_datepicker").datepicker("setStartDate", minDate);
    });

    $("#to_datepicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true
    });

    $("#to_datepicker").on("changeDate", function() {
        var fromDate = $("#from_datepicker").datepicker("getDate");
        var toDate = $("#to_datepicker").datepicker("getDate");

        if (fromDate && toDate && toDate < fromDate) {
            $("#to_datepicker").datepicker("setDate", fromDate);
        }
    });
</script>