<?php session_start(); ?>
<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php include('includes/headers.php'); ?>

<!-- Upload CSV Code -->
<?php
$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

$invalidValues = ['#NAME?', '#VALUE!', '#REF!', '#DIV/0!', '#NUM!', '#N/A', 'N/A', 'NULL', 'INF', '-INF', '=-Infinity'];
function cleanExcelValue($conn, $val)
{
    $val = trim($val);
    global $invalidValues;
    return (in_array(strtoupper($val), $invalidValues) || $val === '') ? '' : mysqli_real_escape_string($conn, $val);
}


if (isset($_POST['fol_csv_upd'])) {
    if (!empty($_FILES['fol_csv_file']['name']) && in_array($_FILES['fol_csv_file']['type'], $csvMimes)) {
        if (is_uploaded_file($_FILES['fol_csv_file']['tmp_name'])) {

            $csvFile = fopen($_FILES['fol_csv_file']['tmp_name'], 'r');
            if (!$csvFile) {
                echo "<script>alert('Unable to read file.'); window.location.href='intergrowth_follow_up.php';</script>";
                exit;
            }

            // Optional: skip header row
            // fgetcsv($csvFile);
            $growth_class = '';
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                // Defensive: Ensure minimum column count
                if (count($line) < 12) continue;

                // Escape & sanitize
                $id_raw = trim($line[0]);
                if (!ctype_digit($id_raw) || (int)$id_raw <= 0) continue;
                $id = (int)$id_raw;
                // $id = (int)trim($line[0]);
                $LengthZScore = cleanExcelValue($conn, trim($line[4]));
                $LengthCentile = cleanExcelValue($conn, trim($line[5]));
                $WeightZScore = cleanExcelValue($conn, trim($line[7]));
                $WeightCentile = (float)trim($line[8]);
                $HeadCircumferenceZScore = cleanExcelValue($conn, trim($line[10]));
                $HeadCircumferenceCentile = cleanExcelValue($conn, trim($line[11]));

                // Determine growth classification
                if ($WeightCentile < 10) {
                    $growth_class = 'SGA';
                } elseif ($WeightCentile <= 90) {
                    $growth_class = 'AGA';
                } else {
                    $growth_class = 'LGA';
                }

                //Only update if any relevant value is available
                if ($LengthZScore !== '' || $WeightZScore !== '' || $growth_class !== '') {
                    $query = "
                        UPDATE follow_up SET 
                            intergrowth_lenage = '$LengthZScore',
                            intergrowth_per_lenage = '$LengthCentile',
                            intergrowth_wtage = '$WeightZScore',
                            intergrowth_per_wtage = '$WeightCentile',
                            intergrowth_head_circum = '$HeadCircumferenceZScore',
                            intergrowth_head_circum_per = '$HeadCircumferenceCentile',
                            intergrowth_classification = '$growth_class',
                            growth_status_intergrowth = '1'
                            WHERE id = $id";
                    mysqli_query($conn, $query);
                }
            }

            fclose($csvFile);

            echo "<script>alert('Upload data successfully.'); window.location.href='intergrowth_follow_up.php';</script>";
        } else {
            echo "<script>alert('Upload failed. Please try again.'); window.location.href='intergrowth_follow_up.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid file. Please upload a valid CSV file.'); window.location.href='intergrowth_follow_up.php';</script>";
    }
}
?>

<!-- Enable/Disable Export -->
<?php $showExport = !empty($_GET['type']); ?>

<!-- Search Code -->
<?php
$filter = '';
$extraParams = [];
if (isset($_REQUEST['search'])) {
    $type = $_GET['type'] ?? '';

    if (!empty($type)) {
        $filter .= " AND fol.type_of_time = '$type'";
        $extraParams['type'] = $type;
    }
    $extraParams['search'] = $_GET['search'];
}
?>

<?php
$limit = 10;
// Get current pages
$child_page = isset($_GET['child_page']) ? (int)$_GET['child_page'] : 1;
$childOffset = ($child_page - 1) * $limit;

$childQuery = "SELECT reg.id FROM follow_up AS fol JOIN registration_form AS reg ON fol.registration_id = reg.id WHERE fol.status='1' and fol.baby_weight > 0 and reg.gestational_age_LBW >= 24 and fol.growth_status_intergrowth=0 $filter ORDER BY fol.id DESC OFFSET $childOffset";
$childResult = mysqli_query($conn, $childQuery);

$total_child11 = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(fol.id) FROM follow_up AS fol JOIN registration_form AS reg ON fol.registration_id = reg.id WHERE fol.status='1' and fol.baby_weight > 0 and reg.gestational_age_LBW >= 24 and fol.growth_status_intergrowth=0 $filter ORDER BY fol.id DESC"))[0];
echo $total_child11;
$totalchildPages = ceil($total_child11 / $limit);
?>

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Follow-Up List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                                <li class="breadcrumb-item">Intergrowth Data</li>
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Follow-Up List</h4>
                            <a class="btn btn-warning" href="https://intergrowth21.ndog.ox.ac.uk/preterm" target="_blank">
                                Generate Growth Status <i class="ri-links-fill"></i>
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploaddata"> Upload Data</button>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div>
                                <div class="row g-4 mb-3">
                                    <form method="GET">
                                        <div class="d-flex justify-content-sm-end align-items-center flex-wrap">
                                            <h5 class="text-start me-auto">Total Records: <span><?php echo $total_child11 ?></span></h5>
                                            <div class="ms-2 mb-2">
                                                <select name="type" id="type" class="form-control" style="width: 250px;">
                                                    <option value="">-- select perioud --</option>
                                                    <option value="8 Days" <?= (isset($_GET['type']) && $_GET['type'] == '8 Days') ? 'selected' : '' ?>>8 Days</option>
                                                    <option value="1 Month" <?= (isset($_GET['type']) && $_GET['type'] == '1 Month') ? 'selected' : '' ?>>1 Month</option>
                                                    <option value="3 Month" <?= (isset($_GET['type']) && $_GET['type'] == '3 Month') ? 'selected' : '' ?>>3 Month</option>
                                                    <option value="6 Month" <?= (isset($_GET['type']) && $_GET['type'] == '6 Month') ? 'selected' : '' ?>>6 Month</option>
                                                    <option value="1 Year" <?= (isset($_GET['type']) && $_GET['type'] == '1 Year') ? 'selected' : '' ?>>1 Year</option>
                                                </select>
                                            </div>
                                            <div class="ms-2 mb-2">
                                                <button type="submit" name="search" id="searchBtn" class="form-control btn btn-secondary" style="width: 150px;" disabled>Search</button>
                                            </div>
                                            <div class="ms-2 mb-2">
                                                <button type="reset" name="reset" onclick="resetSearch()" class="form-control btn btn-danger" style="width: 150px;">Reset</button>
                                            </div>
                                            <div class="ms-2 mb-2 <?= $showExport ? '' : 'd-none' ?>">
                                                <a href="includes/fol_export.php?filename=follow-up" name="growth_export" class="form-control btn btn-success">Export to CSV</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap" id="intergrowth_minitor">
                                        <thead class="table-light">
                                            <tr>
                                                <th data-sort="customer_name">Id</th>
                                                <th data-sort="customer_name">Baby Id</th>
                                                <th data-sort="customer_name">Mother Name</th>
                                                <th data-sort="customer_name">Type of Time</th>
                                                <th data-sort="customer_name">Schedule Date</th>
                                                <th data-sort="customer_name">Visit Date</th>
                                                <th data-sort="customer_name">Gender</th>
                                                <th data-sort="customer_name">Gestational Age</th>
                                                <th data-sort="customer_name">Weight</th>
                                                <th data-sort="customer_name">Length</th>
                                                <th data-sort="customer_name">Headcircumference</th>

                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <?php

                                            // $_SESSION['query'] = "SELECT reg.unique_id_of_body,reg.sncu_id,reg.boby_of_mothers_name,reg.sex,reg.gestational_age_LBW,fol.id,fol.type_of_time,fol.schedule_follow_up_date,fol.date_of_visit,fol.baby_weight,fol.baby_length,fol.baby_head_circumference FROM follow_up AS fol JOIN registration_form AS reg ON fol.registration_id = reg.id WHERE fol.status='1' and fol.baby_weight > 0 and reg.gestational_age_LBW >= 24 $filter ORDER BY fol.id DESC ORDER BY fol.id DESC";

                                            $_SESSION['db_column'] = 'id,sex,GA,baby_weight,baby_length,baby_head_circumference';
                                            $_SESSION['header_column'] = 'ID,Sex,GA,Weight,Length,Headcircumference';

                                            $sqlfacilitator = mysqli_query($conn, "SELECT reg.unique_id_of_body,reg.sncu_id,reg.boby_of_mothers_name,reg.sex,reg.gestational_age_LBW,fol.id,fol.type_of_time,fol.schedule_follow_up_date,fol.date_of_visit,fol.baby_weight,fol.baby_length,fol.baby_head_circumference FROM follow_up AS fol JOIN registration_form AS reg ON fol.registration_id = reg.id WHERE fol.status='1' and fol.baby_weight > 0 and reg.gestational_age_LBW >= 24 and fol.growth_status_intergrowth=0 $filter ORDER BY fol.id DESC LIMIT $limit OFFSET $childOffset");
                                            $sncuname1 = '';
                                            while ($datafacilitator = mysqli_fetch_object($sqlfacilitator)) {
                                                $sncuname1 = ($datafacilitator->sncu_id == 1) ? 'SNCU Gaya' : 'SNCU Purnea';
                                            ?>
                                                <tr>
                                                    <!-- <th scope="row">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="chk_child" value="<#?=$datafacilitator->id?>">
                                                            </div>
                                                        </th> -->
                                                    <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ2101</a></td>
                                                    <td><?= $datafacilitator->id ?></td>
                                                    <td><?= $datafacilitator->unique_id_of_body ?></td>
                                                    <td><?= $datafacilitator->boby_of_mothers_name ?></td>
                                                    <td><?= $datafacilitator->type_of_time ?></td>
                                                    <td><?= $datafacilitator->schedule_follow_up_date ?></td>
                                                    <td><?= $datafacilitator->date_of_visit ?></td>
                                                    <td><?= $datafacilitator->sex ?></td>
                                                    <td><?= $datafacilitator->gestational_age_LBW * 7 ?></td>
                                                    <td><?= $datafacilitator->baby_weight ?></td>
                                                    <td><?= $datafacilitator->baby_length ?></td>
                                                    <td><?= $datafacilitator->baby_head_circumference ?></td>
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

                                <?php echo pagination1($child_page, $totalchildPages, 'child_page', $extraParams); ?>
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

    <!-- Open Edit Modal -->
    <div class="modal fade" id="uploaddata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  modal-mg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Upload Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div>
                            <input type="file" name="fol_csv_file" id="fol_csv_file" accept=".csv" required><br>
                            <label class="d-none" id="al_msg" style="color: red;">Please select a CSV file.</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" name="fol_csv_upd" onclick="return file_validate()">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Edit Modal -->

    <?php include('includes/footers.php'); ?>
    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>
    <script src="assets/libs/list.js/list.min.js"></script>
    <script src="assets/libs/list.pagination.js/list.pagination.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- listjs init -->
    <script src="assets/js/pages/listjs.init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Sweet Alerts js -->
    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />



    <!-- Search Button -->
    <script>
        const inputs = document.querySelectorAll('#type');
        const searchBtn = document.getElementById('searchBtn');

        function toggleSearchButton() {
            let enable = false;
            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    enable = true;
                }
            });
            searchBtn.disabled = !enable;
        }

        inputs.forEach(input => {
            input.addEventListener('input', toggleSearchButton);
        });

        toggleSearchButton();
    </script>

    <!-- Reset Button -->
    <script>
        function resetSearch() {
            window.location.href = window.location.pathname; // removes all GET parameters
        }
    </script>

    <!-- Validate file -->
    <script>
        function file_validate() {
            const fileInput = document.getElementById('fol_csv_file');
            const filePath = fileInput.value;
            const msgElement = document.getElementById('al_msg');

            if (!filePath) {
                msgElement.classList.remove('d-none');

                setTimeout(() => {
                    msgElement.classList.add('d-none');
                }, 3000);

                return false;
            }

            const allowedExtension = /(\.csv)$/i;
            if (!allowedExtension.exec(filePath)) {
                msgElement.classList.remove('d-none');

                setTimeout(() => {
                    msgElement.classList.add('d-none');
                }, 3000);

                fileInput.value = '';
                return false;
            }

            return true;
        }
    </script>

    <!-- data export code -->
    <!-- <script>
        let button = document.querySelector("#growth_export");
        button.addEventListener("click", (e) => {
            let table = document.querySelector("#intergrowth_minitor");
            TableToExcel.convert(table, {
                name: "InterGrowth Data.xlsx",
                sheet: {
                    name: "InterGrowth Monitor"
                }
            });
        });
    </script> -->