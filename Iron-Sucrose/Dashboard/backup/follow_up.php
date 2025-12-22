<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php include('includes/headers.php'); ?>

<!-- Search Code -->
<?php
$filter = "";
$extraParams = [];

// calculate pagenation
$limit = isset($_GET['perpage']) ? (int)$_GET['perpage'] : 10;
$extraParams['perpage'] = $limit;
if (isset($_REQUEST['search'])) {
    $pw_id = $_GET['pw_id'] ?? '';
    $mo_no = $_GET['mo_no'] ?? '';

    if (!empty($pw_id)) {
        $filter .= "AND pw_id LIKE '%" . addslashes($pw_id) . "%'";
        $extraParams['pw_id'] = $pw_id;
    }
    if (!empty($mo_no)) {
        $filter .= " AND mobile LIKE '%" . addslashes($mo_no) . "%'";
        $extraParams['mo_no'] = $mo_no;
    }
    $extraParams['search'] = $_GET['search'];
}



// Get current pages
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$fwOffset = ($page - 1) * $limit;

$total_fw = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pw_iron_visit WHERE 1=1 $filter"))[0];
$totalfwPages = ceil($total_fw / $limit);
?>

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Follow-Up</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                                <li class="breadcrumb-item active">Follow-Up</li>
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
                            <?php $sqlfacilitator = mysqli_query($conn, "select * from pw_iron_visit where 1=1 $filter order by created_at desc LIMIT $limit OFFSET $fwOffset");
                            #$total = mysqli_num_rows($sqlfacilitator); 
                            ?>
                            <h4 class="card-title mb-0 ">Follow-Up List : <?= $total_fw ?></h4>
                            <div class="col-sm">
                                <form method="GET">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="ms-2">
                                            <input type="text" name="pw_id" value="<?= htmlspecialchars($_GET['pw_id'] ?? '') ?>" class="form-control" placeholder="PW Id...">
                                        </div>
                                        <div class="ms-2">
                                            <input type="text" name="mo_no" value="<?= htmlspecialchars($_GET['mo_no'] ?? '') ?>" class="form-control " placeholder="Mobile No...">
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
                                                <th data-sort="customer_name">PW ID</th>
                                                <th data-sort="customer_name">Follow-Up</th>
                                                <th data-sort="whatsapp_no">Dose</th>
                                                <th data-sort="email">Visit Date</th>
                                                <th data-sort="phone">Visit Status</th>
                                                <th data-sort="date">Mobile No</th>
                                                <th data-sort="status"> Call Id</th>
                                                <th data-sort="action">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <?php
                                            while ($datafacilitator = mysqli_fetch_object($sqlfacilitator)) {
                                            ?>
                                                <tr>
                                                    <td class="customer_name"><?= $datafacilitator->pw_id ?></td>
                                                    <td class="customer_name"><?= $datafacilitator->followup ?></td>
                                                    <td class="customer_name"><?= $datafacilitator->dose ?></td>
                                                    <td class="customer_name"><?= $datafacilitator->visit_date ?></td>
                                                    <td class="customer_name"><?= ($datafacilitator->visit_status == 0) ? 'pending' : 'done' ?></td>
                                                    <td class="customer_name"><?= $datafacilitator->mobile ?></td>
                                                    <td class="customer_name"><?= $datafacilitator->callid ?></td>
                                                    <td class="customer_name"><?= $datafacilitator->created_at ?></td>
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