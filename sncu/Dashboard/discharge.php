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
        $filter .= " AND m.date_of_admission LIKE '%" . addslashes($date) . "%'";
        $extraParams['date'] = $date;
    }
    $extraParams['search'] = $_GET['search'];
}

$limit = 10;
$dis_page = isset($_GET['dis_page']) ? (int)$_GET['dis_page'] : 1;
$disOffset = ($dis_page - 1) * $limit;

$disQuery = "SELECT * FROM follow_up LIMIT $limit OFFSET $disOffset";
$disResult = mysqli_query($conn, $disQuery);

$total_dis = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) from monitoring_data AS m JOIN registration_form AS r ON m.registration_id=r.id where m.type_of_monitoring = 'Discharge Day' $filter"))[0];
$totaldisPages = ceil($total_dis / $limit);

?>

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Discharge List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                                <li class="breadcrumb-item active">Discharge</li>
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
                            <h4 class="card-title mb-0">Discharge List</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div>
                                <form method="GET" class="row gy-2 gx-3 align-items-end mb-3">
                                    <div class="col-12 col-md-auto">
                                        <h5 class="mb-0">Total Records: <span><?php echo $total_dis ?></span></h5>
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


                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th data-sort="customer_name">Unique Id</th>
                                                <th data-sort="customer_name">Discharge Date</th>
                                                <th data-sort="whatsapp_no">Discharge Weight</th>
                                                <th data-sort="status">Discharge Length</th>
                                                <th data-sort="date">Growth Chart</th>
                                                <th data-sort="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <?php $prag_women = mysqli_query($conn, "SELECT r.unique_id_of_body,m.id,m.date_of_admission,m.admission_weight,m.admission_length,m.type_of_feed,m.other_feed,m.mode_of_feeding,m.growth_chart_used,m.status from monitoring_data AS m JOIN registration_form AS r ON m.registration_id=r.id where m.type_of_monitoring = 'Discharge Day' $filter order by m.id desc limit $limit OFFSET $disOffset");
                                            while ($women_data = mysqli_fetch_object($prag_women)) {
                                            ?>
                                                <tr>
                                                    <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ2101</a></td>
                                                    <td class="customer_name"><?= $women_data->unique_id_of_body ?></td>
                                                    <td class="customer_name"><?= $women_data->date_of_admission ?></td>
                                                    <td class="whatsapp_no"><?= $women_data->admission_weight ?></td>
                                                    <td class="email"><?= $women_data->admission_length ?></td>
                                                    <td class="email"><?= $women_data->growth_chart_used ?></td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <!-- <div class="edit">
                                                                        <button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal" data-bs-target="#showModal">Edit</button>
                                                                    </div> -->
                                                            <div class="remove">
                                                                <button class="btn btn-sm btn-secondary remove-item-btn" data-bs-toggle="modal" data-bs-target="#dischargemodal" onclick="dishcharge_data(<?= $women_data->id ?>,'<?= $women_data->unique_id_of_body ?>')">View</button>
                                                            </div>
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
                                            <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any orders for you search.</p>
                                        </div>
                                    </div>
                                </div>

                                <nav class="d-flex justify-content-center overflow-auto">
                                    <?php echo pagination1($dis_page, $totaldisPages, 'dis_page', $extraParams); ?>
                                </nav>

                            </div>
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end col -->
            </div>

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <?php include('includes/footers.php'); ?>

    <!-- Discharge Modal Start -->
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

        <!-- Discharge script -->
        <script>
            function dishcharge_data(dish_id, uniq_id) {
                $('#viewModalLabel').text('Discharge List');
                $.ajax({
                    url: 'ajax/dishcharge_ajax.php',
                    type: 'POST',
                    data: {
                        dish_id: dish_id,
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