<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php include('includes/headers.php'); ?>
<?php
$filter = '';
$extraParams = [];

// calculate pagenation
$limit = isset($_GET['perpage']) ? (int)$_GET['perpage'] : 10;
$extraParams['perpage'] = $limit;

if (isset($_REQUEST['search'])) {
    $pwName = $_GET['pw_name'] ?? '';
    $pwNumber = $_GET['whatsapp_no'] ?? '';

    if (!empty($pwName)) {
        $filter .= " AND pw_r.name LIKE '%" . addslashes($pwName) . "%'";
        $extraParams['pw_name'] = $pwName;
    }
    if (!empty($pwNumber)) {
        $filter .= " AND pw_r.mobile LIKE '%" . addslashes($pwNumber) . "%'";
        $extraParams['whatsapp_no'] = $pwNumber;
    }
    if (!empty($_GET['fdate'])) {
        $fromDate = date('Y-m-d', strtotime($_GET['fdate']));
        $filter .= " AND DATE(pw_r.created_on) >= '$fromDate'";
        $extraParams['fdate'] = $_GET['fdate'];
    }
    if (!empty($_GET['tdate'])) {
        $toDate = date('Y-m-d', strtotime($_GET['tdate']));
        $filter .= " AND DATE(pw_r.created_on) <= '$toDate'";
        $extraParams['tdate'] = $_GET['tdate'];
    }

    $extraParams['search'] = $_GET['search'];
}

// Get current pages
$child_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pwOffset = ($child_page - 1) * $limit;

$total_pw = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pw_iron_registration AS pw_r WHERE 1=1 $filter"))[0];
$totalpwPages = ceil($total_pw / $limit);
?>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Pregnant Women</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                                <li class="breadcrumb-item active">Pregnant Women</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row g-4 align-items-end">
                <form method="GET" class="d-flex flex-wrap align-items-end justify-content-end gap-2 mb-3 w-100">
                    <div class="d-flex">
                        <div class="ms-2">
                            <input type="text" name="pw_name" value="<?= htmlspecialchars($_GET['pw_name'] ?? '') ?>" class="form-control" placeholder="PW Name">
                        </div>

                        <div class="ms-2">
                            <input type="text" name="whatsapp_no" value="<?= htmlspecialchars($_GET['whatsapp_no'] ?? '') ?>" class="form-control" placeholder="Whatsapp Numbber">
                        </div>

                        <div class="ms-2">
                            <input type="text" id="from_datepicker" data-bs-placement="top" data-bs-toggle="tooltip"
                                title="From date" class="form-control" placeholder="From Date" name="fdate" value="<?= (isset($_GET['fdate'])) ? $_GET['fdate'] : ''; ?>">
                        </div>

                        <div class="ms-2">
                            <input type="text" id="to_datepicker" data-bs-placement="top" data-bs-toggle="tooltip"
                                title="To date" class="form-control" placeholder="To Date" name="tdate" value="<?= (isset($_GET['tdate'])) ? $_GET['tdate'] : '' ?>">
                        </div>
                        <input type="hidden" name="perpage" value="<?= htmlspecialchars($_GET['perpage'] ?? 10) ?>">
                        <div class="d-flex gap-2 ms-2">
                            <button type="submit" name="search" class="btn btn-secondary">Search</button>
                            <button type="reset" name="reset" onclick="resetSearch()" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- end page title -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Pregnant Women List: <?= $total_pw ?></h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="listjs-table" id="customerList">

                                <div class="table-responsive table-card mb-1">
                                    <table class="table align-middle table-nowrap" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th data-sort="customer_name">Id</th>
                                                <th data-sort="customer_name">Name</th>
                                                <th data-sort="whatsapp_no">WhatsApp No</th>
                                                <th data-sort="weight">Weight(Kg)</th>
                                                <th data-sort="hb">HB(g/dL)</th>
                                                <th data-sort="date">Registered On</th>
                                                <th data-sort="date">Iron Dose Required</th>
                                                <th data-sort="date">Total Visit</th>
                                                <th data-sort="date">View</th>
                                                <th data-sort="status"> Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <?php $sqlfacilitator = mysqli_query($conn, "select * from pw_iron_registration AS pw_r WHERE 1=1 $filter order by pw_r.created_on desc LIMIT $limit OFFSET $pwOffset");
                                            while ($datafacilitator = mysqli_fetch_object($sqlfacilitator)) {
                                            ?>
                                                <tr>
                                                    <td class="id"><?= $datafacilitator->id ?></td>
                                                    <td class="customer_name"><?= $datafacilitator->name ?></td>
                                                    <td class="whatsapp_no"><?= $datafacilitator->mobile ?></td>
                                                    <td class="email"><?= $datafacilitator->weight ?></td>
                                                    <td class="phone"><?= $datafacilitator->hb ?></td>
                                                    <td class="date"><?= date('d-F-Y', strtotime($datafacilitator->created_on)) ?></td>
                                                    <td class="email"><?= $datafacilitator->total_dose ?></td>
                                                    <td class="email"><?= $datafacilitator->total_visit ?></td>
                                                    <td>
                                                        <div class="remove">
                                                            <button onclick="getvsit(<?= $datafacilitator->id ?>)" class="btn btn-sm btn-success remove-item-btn" data-bs-toggle="modal" data-bs-target="#viewmodal">
                                                                View
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td class="status"><span class="badge bg-success-subtle text-success text-uppercase">Active</span></td>
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
                                    <div class="row align-items-center">
                                        <div class="col text-center">
                                            <form id="perPageForm" method="get">
                                                <select name="perpage" class="form-select w-auto d-inline-block shadow-none" id="recordsPerPage">
                                                    <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                                                    <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                                                    <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                                                </select>
                                            </form>
                                        </div>
                                        <div class="col-auto ms-auto me-3">
                                            <?php echo pagination1($child_page, $totalpwPages, 'page', $extraParams); ?>
                                        </div>
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

    <!-- Modal -->
    <div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="viewmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="viewmodalLabel">Visit Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" id="visit_modal_data">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <!-- End Page-content -->
    <?php include('includes/footers.php'); ?>
    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>
    <script src="assets/libs/list.js/list.min.js"></script>
    <script src="assets/libs/list.pagination.js/list.pagination.min.js"></script>

    <!-- listjs init -->
    <script src="assets/js/pages/listjs.init.js"></script>

    <!-- Sweet Alerts js -->
    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!--<script src="https://code.highcharts.com/highcharts.js"></script>-->
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/maps/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <script>
        function getvsit(idd) {
            $.ajax({
                url: 'ajax/get_visit.php',
                type: 'post',
                data: {
                    idd: idd
                },

                success: function(response) {
                    $('#visit_modal_data').html(response);
                },
                error: function(response) {
                    $('#visit-modal-content').html('<div class="text-danger">Error loading visit details.</div>');
                }
            })
        }
    </script>

    <script>
        $("#from_datepicker,#to_datepicker").on("click", function() {
            if ($("#from_datepicker").val() != '' || $("#to_datepicker").val() != '') {
                $('#btnsearch').prop('disabled', false);
            } else {
                $('#btnsearch').prop('disabled', false);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Jquery code here ///
            var today = new Date();
            var startdate;
            var enddate;
            // Set up the date range
            $('#from_datepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                maxDate: 0,
                onSelect: function(selectedDate) {
                    $('#to_datepicker').datepicker('option', 'minDate', selectedDate);
                }
            });

            $('#to_datepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                maxDate: 0,
                onSelect: function(selectedDate) {
                    $('#from_datepicker').datepicker('option', 'maxDate', selectedDate);
                }
            });


            $('#from_datepicker').change(function() {
                startdate = $(this).datepicker('getDate');
                $('#to_datepicker').datepicker('option', 'minDate', startdate);
            });
            $('#to_datepicker').change(function() {
                enddate = $(this).datepicker('getDate');
                $('#to_datepicker').datepicker('option', 'maxDate', enddate);
            });
        });
    </script>

    <!-- Reset Button -->
    <script>
        function resetSearch() {
            window.location.href = window.location.pathname;
        }
    </script>
    <script>
        document.getElementById("recordsPerPage").addEventListener("change", function() {
            document.getElementById("perPageForm").submit();
        });
    </script>