<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php include('includes/headers.php'); ?>
<?php
$filter = "";
$extraParams = [];

// calculate pagenation
$limit = isset($_GET['perpage']) ? (int)$_GET['perpage'] : 10;
$extraParams['perpage'] = $limit;

if (isset($_REQUEST['search'])) {
    $facility_name = $_GET['facility_name'] ?? '';

    if (!empty($facility_name)) {
        $filter .= "AND ccenter_name LIKE '%" . addslashes($facility_name) . "%'";
        $extraParams['facility_name'] = $facility_name;
    }
    $extraParams['search'] = $_GET['search'];
}

// Get current pages
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$fwOffset = ($page - 1) * $limit;

$total_fw = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM ivis_facilities WHERE 1=1 $filter"))[0];
$totalfwPages = ceil($total_fw / $limit);

?>
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Health Facility</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                                <li class="breadcrumb-item active">Health Facility</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h4 class="card-title mb-0">Health Facilities: <?= $total_fw ?></h4>
                            <div class="col-sm">
                                <form method="GET">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="ms-2">
                                            <input type="text" name="facility_name" value="<?= htmlspecialchars($_GET['facility_name'] ?? '') ?>" class="form-control" placeholder="Facility Name">
                                        </div>
                                        <input type="hidden" name="perpage" value="<?= htmlspecialchars($_GET['perpage'] ?? 10) ?>">
                                        <div class="ms-2">
                                            <button type="submit" name="search" class="form-control btn btn-secondary">Search</button>
                                        </div>
                                        <div class="ms-2">
                                            <button type="reset" name="reset" onclick="resetSearch()" class="form-control btn btn-danger">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="listjs-table" id="customerList">
                                <div class="table-responsive table-card mb-1">
                                    <table class="table align-middle table-nowrap" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th data-sort="customer_name">ID</th>
                                                <th data-sort="customer_name">Facility Name</th>
                                                <th data-sort="whatsapp_no">District</th>
                                                <th data-sort="email">Block</th>
                                                <th data-sort="status"> Status</th>
                                                <th data-sort="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <?php $sqlfacilitator = mysqli_query($conn, "select * from ivis_facilities Where 1=1 $filter order by districts desc LIMIT $limit OFFSET $fwOffset");
                                            while ($datafacilitator = mysqli_fetch_object($sqlfacilitator)) {
                                            ?>
                                                <tr>
                                                    <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ2101</a></td>
                                                    <td class="customer_name"><?= $datafacilitator->id ?></td>
                                                    <td class="customer_name"><?= $datafacilitator->ccenter_name ?></td>
                                                    <td class="whatsapp_no"><?= $datafacilitator->districts ?></td>
                                                    <td class="email"><?= $datafacilitator->blocks ?></td>
                                                    <td class="status"><span class="badge bg-success-subtle text-success text-uppercase">Active</span></td>
                                                    <td>
                                                        <div class="remove">
                                                            <button onclick="getvsit(<?= $datafacilitator->id ?>)" class="btn btn-sm btn-success remove-item-btn" data-bs-toggle="modal" data-bs-target="#viewmodal">
                                                                View
                                                            </button>
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
                                            <?php echo pagination1($page, $totalfwPages, 'page', $extraParams); ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->
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

    <!-- reset page -->
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