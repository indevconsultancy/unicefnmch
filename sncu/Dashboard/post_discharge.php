<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php include('includes/headers.php'); ?>

<!-- Search Code -->
<?php
$filter = '';
$extraParams = [];
if (isset($_REQUEST['search'])) {
    $reg_idd = $_GET['reg_idd'] ?? '';
    $date = $_GET['date'] ?? '';

    if (!empty($reg_idd)) {
        $filter .= " AND r.unique_id_of_body LIKE '%" . addslashes($reg_idd) . "%'";
        $extraParams['reg_idd'] = $reg_idd;
    }
    if (!empty($date)) {
        $filter .= " AND f.date_of_visit LIKE '%" . addslashes($date) . "%'";
        $extraParams['date'] = $date;
    }
    $extraParams['search'] = $_GET['search'];
}
?>

<?php
$limit = 10;
// Get current pages
$p_dis_page = isset($_GET['p_dis_page']) ? (int)$_GET['p_dis_page'] : 1;
$pdisOffset = ($p_dis_page - 1) * $limit;

$disQuery = "SELECT * FROM follow_up LIMIT $limit OFFSET $pdisOffset";
$disResult = mysqli_query($conn, $disQuery);

$total_dis = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) from follow_up AS f JOIN registration_form AS r ON f.registration_id=r.id $filter"))[0];
$totaldisPages = ceil($total_dis / $limit);
?>

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Post Discharge List</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                                <li class="breadcrumb-item active">Post Discharge</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0" style="font-weight: 600; font-size: 16px; color: black;">Post Discharge List</h4>
                        </div>

                        <div class="card-body">
                            <!-- Responsive Search Form -->
                            <form method="GET" class="row gy-2 gx-3 align-items-end mb-3">
                                <div class="col-12 col-md-auto">
                                    <h5 class="mb-0" style="font-weight: 600; font-size: 16px;">
                                        Total Records: <span><?php echo $total_dis ?></span>
                                    </h5>
                                </div>

                                <div class="col-12 col-md">
                                    <input type="text" name="reg_idd" value="<?= htmlspecialchars($_GET['reg_idd'] ?? '') ?>" class="form-control" placeholder="Registration ID...">
                                </div>

                                <div class="col-12 col-md">
                                    <input type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>" class="form-control">
                                </div>

                                <div class="col-6 col-md-auto">
                                    <button type="submit" name="search" class="btn btn-secondary w-100">Search</button>
                                </div>

                                <div class="col-6 col-md-auto">
                                    <button type="reset" name="reset" onclick="resetSearch()" class="btn btn-danger w-100">Reset</button>
                                </div>
                            </form>

                            <!-- Table -->
                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap" id="customerTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Unique ID</th>
                                            <th>Type of Visit</th>
                                            <th>Schedule Date</th>
                                            <th>Immunization Status</th>
                                            <th>Date of Visit</th>
                                            <th>Post Discharge Weight</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        <?php
                                        $postdis = mysqli_query($conn, "SELECT r.unique_id_of_body,f.id,f.type_of_time,f.immunization_status,f.schedule_follow_up_date,f.date_of_visit,f.baby_weight FROM follow_up AS f JOIN registration_form AS r ON f.registration_id=r.id $filter ORDER BY f.id DESC LIMIT $limit OFFSET $pdisOffset");
                                        while ($postdisdata = mysqli_fetch_object($postdis)) {
                                        ?>
                                            <tr>
                                                <td><?= $postdisdata->unique_id_of_body ?></td>
                                                <td><?= $postdisdata->type_of_time ?></td>
                                                <td><?= $postdisdata->schedule_follow_up_date ?></td>
                                                <td><?= $postdisdata->immunization_status ?></td>
                                                <td><?= $postdisdata->date_of_visit ?></td>
                                                <td><?= $postdisdata->baby_weight ?></td>
                                                <td>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#dischargemodal" onclick="postdish(<?= $postdisdata->id ?>,'<?= $postdisdata->unique_id_of_body ?>')">View</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <div class="noresult" style="display: none">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                        <p class="text-muted mb-0">We've searched more than 150+ records but did not find any for your search.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <nav class="d-flex justify-content-center overflow-auto mt-3">
                                <?php echo pagination1($p_dis_page, $totaldisPages, 'p_dis_page', $extraParams); ?>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Page-content -->
    <?php include('includes/footers.php'); ?>

    <!-- Post Discharge Modal Start -->
    <div class="modal fade" id="dischargemodal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewModalLabel"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">

                </div>
            </div>
        </div>
        <!-- Discharge modal end -->

        <!-- prismjs plugin -->
        <script src="assets/libs/prismjs/prism.js"></script>
        <script src="assets/libs/list.js/list.min.js"></script>
        <script src="assets/libs/list.pagination.js/list.pagination.min.js"></script>

        <!-- listjs init -->
        <script src="assets/js/pages/listjs.init.js"></script>

        <!-- Sweet Alerts js -->
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <!-- Post Discharge script -->
        <script>
            function postdish(post_id, uniq_id) {
                $('#viewModalLabel').text('Discharge Women List');
                $.ajax({
                    url: 'ajax/postdis_ajax.php',
                    type: 'POST',
                    data: {
                        post_id: post_id,
                        uniq_id: uniq_id
                    },
                    success: function(response) {
                        $('#modalContent').html(response);
                    },
                    error: function(xhr, status, error) {
                        $('#modalContent').html('Error loading data.');
                    }
                });
            }
        </script>

        <script>
            function resetSearch() {
                window.location.href = window.location.pathname; // removes all GET parameters
            }
        </script>