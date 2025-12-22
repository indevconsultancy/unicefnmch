<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php include('includes/headers.php'); ?>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">SNCU List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">SNCU List</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <div class="table-responsive table-card mb-1">
                                    <table class="table align-middle table-nowrap" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th data-sort="customer_name">SNCU Id</th>
                                                <th data-sort="customer_name">SNCU Name</th>
                                                <th data-sort="whatsapp_no">District</th>
                                                <th data-sort="status"> Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <?php $sncu_list = mysqli_query($conn, "SELECT dm.district_name,sn.sncu_code,sn.sncu_name,sn.status from sncu_master AS sn JOIN district_master AS dm ON sn.district_id = dm.district_id order by id desc");
                                            while ($sncu_data = mysqli_fetch_object($sncu_list)) {
                                            ?>
                                                <tr>
                                                    <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ2101</a></td>
                                                    <td class="customer_name"><?= $sncu_data->sncu_code ?></td>
                                                    <td class="customer_name"><?= $sncu_data->sncu_name ?></td>
                                                    <td class="whatsapp_no"><?= $sncu_data->district_name ?></td>
                                                    <td class="status"><span class="badge bg-success-subtle text-success text-uppercase"><?= ($sncu_data->status) == 1 ? 'Active' : 'Inactive' ?></span></td>
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

                                <div class="d-flex justify-content-end">
                                    <div class="pagination-wrap hstack gap-2">
                                        <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                            Previous
                                        </a>
                                        <ul class="pagination listjs-pagination mb-0"></ul>
                                        <a class="page-item pagination-next" href="javascript:void(0);">
                                            Next
                                        </a>
                                    </div>
                                </div>
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
                $('#viewModalLabel').text('Discharge Women List');
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