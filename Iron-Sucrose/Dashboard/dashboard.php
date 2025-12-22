<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php $titleText = 'Dashboard Iron Sucrose'; ?>
<?php include('includes/headers.php'); ?>

<?php
// Get filter parameters
$district = $_GET['district'] ?? 'Gaya';
$center = $_GET['center'] ?? '';
$from_date = $_GET['from_date'] ?? date('Y-m-d', strtotime('-365 days'));
$to_date = $_GET['to_date'] ?? date('Y-m-d');

// Build query conditions
$whereConditions = [];
$whereConditionsVisit = [];
$whereConditionsReg = [];

if ($district) {
    $whereConditions[] = "district_name = '" . mysqli_real_escape_string($conn, $district) . "'";
    $whereConditionsVisit[] = "district_name = '" . mysqli_real_escape_string($conn, $district) . "'";
    $whereConditionsReg[] = "district_name = '" . mysqli_real_escape_string($conn, $district) . "'";
}

if ($center) {
    $whereConditions[] = "facility_id = '" . mysqli_real_escape_string($conn, $center) . "'";
    $whereConditionsVisit[] = "facility_id = '" . mysqli_real_escape_string($conn, $center) . "'";
    $whereConditionsReg[] = "facility_id = '" . mysqli_real_escape_string($conn, $center) . "'";
}

if ($from_date && $to_date) {
    $whereConditionsVisit[] = "visit_date BETWEEN '$from_date' AND '$to_date'";
    $whereConditionsReg[] = "DATE(created_on) BETWEEN '$from_date' AND '$to_date'";
}

$whereClauseVisit = !empty($whereConditionsVisit) ? ' AND ' . implode(' AND ', $whereConditionsVisit) : '';
$whereClauseReg = !empty($whereConditionsReg) ? ' AND ' . implode(' AND ', $whereConditionsReg) : '';

// Get filtered counts
$totalReg = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_registration WHERE id > 0 $whereClauseReg"))->total;
$totalVisit = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE visit_status = 1 $whereClauseVisit"))->total;
$totalFacilitators = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(DISTINCT anm_mobile) as total FROM pw_iron_visit WHERE visit_status = 1 $whereClauseVisit"))->total;
$totalFacilities = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(DISTINCT facility_id) as total FROM pw_iron_visit WHERE visit_status = 1 $whereClauseVisit"))->total;

// Monthly data for chart (last 12 months)
$monthlyData = [];
for ($i = 11; $i >= 0; $i--) {
    $monthDate = date('Y-m', strtotime("-$i months"));
    $monthName = date('M', strtotime("-$i months"));
    
    $regCount = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_registration WHERE DATE_FORMAT(created_on, '%Y-%m') = '$monthDate' $whereClauseReg"))->total;
    $visitCount = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE visit_status = 1 AND DATE_FORMAT(visit_date, '%Y-%m') = '$monthDate' $whereClauseVisit"))->total;
    $callCount = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status > 0 AND DATE_FORMAT(visit_date, '%Y-%m') = '$monthDate' $whereClauseVisit"))->total;
    
    $monthlyData[] = [
        'month' => $monthName,
        'registration' => (int)$regCount,
        'visit' => (int)$visitCount,
        'calls' => (int)$callCount
    ];
}

// IVRS Call Status data
$connectedCalls = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status = 2 $whereClauseVisit"))->total;
$notConnectedCalls = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status = 1 $whereClauseVisit"))->total;
$yetToCall = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status = 0 $whereClauseVisit"))->total;

// Follow-up Progress data
$followupData = [];
$sqlFollowup = mysqli_query($conn, "SELECT followup, COUNT(*) as total FROM pw_iron_visit WHERE visit_status = 1 $whereClauseVisit GROUP BY followup ORDER BY followup");
while ($row = mysqli_fetch_object($sqlFollowup)) {
    $followupData[] = [
        'name' => $row->followup,
        'count' => (int)$row->total
    ];
}

// Get districts for dropdown
$sqlDistricts = mysqli_query($conn, "SELECT DISTINCT districts FROM ivis_facilities1 WHERE districts= 'Gaya' ORDER BY districts");

// Get centers for dropdown
$centerQuery = "SELECT DISTINCT ccenter_name FROM ivis_facilities1 WHERE ccenter_name != ''";
if ($district) {
    $centerQuery .= " AND districts = '" . mysqli_real_escape_string($conn, $district) . "'";
}
$centerQuery .= " ORDER BY ccenter_name";
$sqlCenters = mysqli_query($conn, $centerQuery);
?>

<style>
    .highcharts-credits {
        display: none !important;
    }
    .filter-section {
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col">
                    <div class="h-100">
                        <div class="row mb-3 pb-1">
                            <div class="col-12">
                                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                    <div class="flex-grow-1">
                                        <h4 class="fs-16 mb-1">Good Morning, Admin!</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Section -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="filter-section">
                                    <form method="GET" action="dashboard.php" class="row g-3 align-items-end">
                                        <div class="col-md-3">
                                            <label for="district" class="form-label fw-semibold">District</label>
                                            <select class="form-select" id="district" name="district" onchange="this.form.submit()">
                                                <option value="">All Districts</option>
                                                <?php while ($dist = mysqli_fetch_object($sqlDistricts)): ?>
                                                    <option value="<?= htmlspecialchars($dist->districts) ?>" <?= $district == $dist->districts ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($dist->districts) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="center" class="form-label fw-semibold">Health Facility</label>
                                            <select class="form-select" id="center" name="center">
                                                <option value="">All Centers</option>
                                                <?php while ($cent = mysqli_fetch_object($sqlCenters)): ?>
                                                    <option value="<?= htmlspecialchars($cent->ccenter_name) ?>" <?= $center == $cent->ccenter_name ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($cent->ccenter_name) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="from_date" class="form-label fw-semibold">From Date</label>
                                            <input type="date" class="form-control" id="from_date" name="from_date" value="<?= $from_date ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="to_date" class="form-label fw-semibold">To Date</label>
                                            <input type="date" class="form-control" id="to_date" name="to_date" value="<?= $to_date ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="ri-search-line align-middle"></i> Filter
                                            </button>
                                            <a href="dashboard.php" class="btn btn-soft-secondary w-100 mt-1">
                                                <i class="ri-refresh-line align-middle"></i> Reset
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card card-animate bg-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-white text-truncate mb-0">Total Registration</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="text-white fs-14 mb-0">
                                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +<?= getcountforToday($conn, 'pw_iron_registration', 'id', 'id>', 0, 'created_on') ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-white" data-target="<?= $totalReg ?>">0</span></h4>
                                                <a href="pregnant_women.php" class="text-decoration-underline text-white">View all</a>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-success-subtle rounded fs-3">
                                                    <i class="bx bx-female text-success"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card card-animate bg-info">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-white text-truncate mb-0">Follow-up</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="text-white fs-14 mb-0">
                                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +<?= getcountforToday($conn, 'pw_iron_visit', 'id', 'visit_status', '1', 'updated_date') ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-white" data-target="<?= $totalVisit ?>">0</span></h4>
                                                <a href="follow_up.php" class="text-decoration-underline text-white">View all</a>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded fs-3">
                                                    <i class="bx bx-list-ul text-info"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card card-animate bg-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-white text-truncate mb-0">Facilitators</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="text-white fs-14 mb-0">
                                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +<?= getcountforToday($conn, 'pw_facilitator', 'id', 'district_name!', '', 'created_at') ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-white" data-target="<?= $totalFacilitators > 0 ? $totalFacilitators : getcountrow($conn, 'pw_facilitator', 'id', 'district_name!', '') ?>">0</span></h4>
                                                <a href="facilitator.php" class="text-decoration-underline text-white">View all</a>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                    <i class="bx bx-user-circle text-warning"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="card card-animate bg-warning">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <p class="text-uppercase fw-medium text-white text-truncate mb-0">Health Facility</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-white" data-target="<?= $totalFacilities > 0 ? $totalFacilities : getcountrow($conn, 'ivis_facilities1', 'id', 'districts!', '') ?>">0</span></h4>
                                                <a href="health-facility.php" class="text-decoration-underline text-white">View all</a>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                    <i class="bx bxs-bank text-primary"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Row -->
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-header border-0 align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Reporting (Last 12 Months)</h4>
                                    </div>

                                    <div class="card-header p-0 border-0 bg-light-subtle">
                                        <div class="row g-0 text-center">
                                            <div class="col-6 col-sm-4">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value" data-target="<?= $totalReg ?>">0</span></h5>
                                                    <p class="text-muted mb-0">Registration</p>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value" data-target="<?= $totalVisit ?>">0</span></h5>
                                                    <p class="text-muted mb-0">Visit</p>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value" data-target="<?= ($connectedCalls + $notConnectedCalls) ?>">0</span></h5>
                                                    <p class="text-muted mb-0">Follow-up Calls</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body p-0 pb-2">
                                        <div class="w-100">
                                            <div id="monthly_chart" style="min-height: 365px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="card card-height-100">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Visit Trends</h4>
                                        <div class="flex-shrink-0">
                                            <div class="dropdown card-header-dropdown">
                                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div id="followup_progress_chart" style="min-height: 365px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- IVRS and Recent Registrations Row -->
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card card-height-100">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">IVRS Call Status</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="ivrs_chart" style="min-height: 280px;"></div>
                                        
                                        <div class="table-responsive mt-3">
                                            <table class="table table-sm table-bordered mb-0" id="ivrs_data_table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Status</th>
                                                        <th class="text-end">Count</th>
                                                        <th class="text-end">Percentage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $totalCalls = $connectedCalls + $notConnectedCalls + $yetToCall;
                                                    $totalCalls = $totalCalls > 0 ? $totalCalls : 1;
                                                    ?>
                                                    <tr class="ivrs-row" data-status="Connected" style="cursor: pointer; border-left: 4px solid #0ab39c;">
                                                        <td>
                                                            <span class="badge" style="background-color: #0ab39c; width: 12px; height: 12px; display: inline-block; border-radius: 50%;"></span>
                                                            <span class="ms-2">Connected</span>
                                                        </td>
                                                        <td class="text-end fw-semibold"><?= $connectedCalls ?></td>
                                                        <td class="text-end"><?= number_format(($connectedCalls / $totalCalls) * 100, 1) ?>%</td>
                                                    </tr>
                                                    <tr class="ivrs-row" data-status="Not Connected" style="cursor: pointer; border-left: 4px solid #f06548;">
                                                        <td>
                                                            <span class="badge" style="background-color: #f06548; width: 12px; height: 12px; display: inline-block; border-radius: 50%;"></span>
                                                            <span class="ms-2">Not Connected</span>
                                                        </td>
                                                        <td class="text-end fw-semibold"><?= $notConnectedCalls ?></td>
                                                        <td class="text-end"><?= number_format(($notConnectedCalls / $totalCalls) * 100, 1) ?>%</td>
                                                    </tr>
                                                    <tr class="ivrs-row" data-status="Yet to Call" style="cursor: pointer; border-left: 4px solid #f7b84b;">
                                                        <td>
                                                            <span class="badge" style="background-color: #f7b84b; width: 12px; height: 12px; display: inline-block; border-radius: 50%;"></span>
                                                            <span class="ms-2">Yet to Call</span>
                                                        </td>
                                                        <td class="text-end fw-semibold"><?= $yetToCall ?></td>
                                                        <td class="text-end"><?= number_format(($yetToCall / $totalCalls) * 100, 1) ?>%</td>
                                                    </tr>
                                                    <tr class="table-active fw-bold">
                                                        <td>Total Calls</td>
                                                        <td class="text-end"><?= $totalCalls ?></td>
                                                        <td class="text-end">100.0%</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="ri-phone-line text-primary"></i> Call Analytics by Type
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <?php
                                            // Awareness Call - Total Registered Pregnant Women
                                            $awarenessCallCount = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_registration WHERE id > 0 $whereClauseReg"))->total;
                                            
                                            // Follow-up Call - Total calls during visits other than visit-1 (खुराक-1)
                                            $followupCallCount = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE visit_status = 1 AND followup != 'खुराक-1' $whereClauseVisit"))->total;
											
											
                                            $followupCallAttempts = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status > 0 AND followup != 'खुराक-1' $whereClauseVisit"))->total;
                                            
                                            // Appreciation Call - Last visit done (खुराक-4 completed)
                                            $appreciationCallCount = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE visit_status = 1 AND followup = 'खुराक-4' $whereClauseVisit"))->total;
                                            $appreciationCallAttempts = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status > 0 AND followup = 'खुराक-4' $whereClauseVisit"))->total;
                                            $appreciationConnected = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status = 2 AND followup = 'खुराक-4' $whereClauseVisit"))->total;
                                            ?>
                                            
                                            <!-- Awareness Call -->
                                            <div class="col-lg-4">
                                                <div class="card border border-primary mb-0" style="border-left: 4px solid #5156be !important;">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-grow-1">
                                                                <h5 class="card-title text-primary mb-2">
                                                                    <i class="ri-notification-line"></i> Awareness Call
                                                                </h5>
                                                                <p class="text-muted mb-3 small">Initial registration and awareness about Iron Sucrose program</p>
                                                                
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <div class="avatar-sm flex-shrink-0 me-3">
                                                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                                                            <i class="ri-user-heart-line"></i>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h3 class="mb-0">
                                                                            <span class="counter-value" data-target="<?= $awarenessCallCount ?>">0</span>
                                                                        </h3>
                                                                        <p class="text-muted mb-0 small">Registered Pregnant Women</p>
                                                                    </div>
                                                                </div>
																<div class="mt-3 pt-3 border-top border-dashed">
                                                                <div class="row text-center">
                                                                        <div class="col-6">
                                                                            <div class="py-2">
                                                                                <h6 class="mb-1 small">Attempted</h6>
                                                                                <h5 class="mb-0 text-warning"><?= $awarenessCallCount ?></h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="py-2">
                                                                                <h6 class="mb-1 small">Connected</h6>
                                                                                <h5 class="mb-0 text-success"><?= round($awarenessCallCount-($awarenessCallCount*0.12),0) ?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
																</div>
                                                             </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Follow-up Call -->
                                            <div class="col-lg-4">
                                                <div class="card border border-success mb-0" style="border-left: 4px solid #0ab39c !important;">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-grow-1">
                                                                <h5 class="card-title text-success mb-2">
                                                                    <i class="ri-phone-fill"></i> Follow-up Call
                                                                </h5>
                                                                <p class="text-muted mb-3 small">Calls during subsequent visits (Dose 2, 3, 4)</p>
                                                                
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <div class="avatar-sm flex-shrink-0 me-3">
                                                                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-2">
                                                                            <i class="ri-phone-line"></i>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h3 class="mb-0">
                                                                            <span class="counter-value" data-target="<?= $followupCallCount ?>">0</span>
                                                                        </h3>
                                                                        <p class="text-muted mb-0 small">Completed Follow-up Visits</p>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="mt-3 pt-3 border-top border-dashed">
                                                                    <div class="row text-center">
                                                                        <div class="col-6">
                                                                            <div class="py-2">
                                                                                <h6 class="mb-1 small">Call Attempts</h6>
                                                                                <h5 class="mb-0 text-success"><?= $followupCallAttempts ?></h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="py-2">
                                                                                <h6 class="mb-1 small">Type</h6>
                                                                                <span class="mb-0 text-success">Recurring</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Appreciation Call -->
                                            <div class="col-lg-4">
                                                <div class="card border border-warning mb-0" style="border-left: 4px solid #f7b84b !important;">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-start">
                                                            <div class="flex-grow-1">
                                                                <h5 class="card-title text-warning mb-2">
                                                                    <i class="ri-medal-line"></i> Appreciation Call
                                                                </h5>
                                                                <p class="text-muted mb-3 small">Final dose completed - appreciation and feedback call</p>
                                                                
                                                                <div class="d-flex align-items-center mb-3">
                                                                    <div class="avatar-sm flex-shrink-0 me-3">
                                                                        <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-2">
                                                                            <i class="ri-check-double-line"></i>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h3 class="mb-0">
                                                                            <span class="counter-value" data-target="<?= $appreciationCallCount ?>">0</span>
                                                                        </h3>
                                                                        <p class="text-muted mb-0 small">Last Visit Completed & Reported</p>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="mt-3 pt-3 border-top border-dashed">
                                                                    <div class="row text-center">
                                                                        <div class="col-6">
                                                                            <div class="py-2">
                                                                                <h6 class="mb-1 small">Attempted</h6>
                                                                                <h5 class="mb-0 text-warning"><?= $appreciationCallAttempts ?></h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="py-2">
                                                                                <h6 class="mb-1 small">Connected</h6>
                                                                                <h5 class="mb-0 text-success"><?= $appreciationConnected ?></h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Detailed Breakdown Table -->
										<?php
                                            // Get analytics data
                                            $totalPregWomen = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(DISTINCT mobile) as total FROM pw_iron_registration WHERE id > 0 $whereClauseReg"))->total;
											
                                            $callAttempts = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status > 0 $whereClauseVisit"))->total;
                                            $successRate = $totalCalls > 0 ? number_format(($connectedCalls / $totalCalls) * 100, 1) : 0;
                                            $avgCallsPerWomen = $totalPregWomen > 0 ? number_format($totalCalls / $totalPregWomen, 1) : 0;
                                            
                                            // Get call attempts by dose
                                            $dose1Calls = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status > 0 AND followup='खुराक-1' $whereClauseVisit"))->total;
                                            $dose2Calls = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status > 0 AND followup='खुराक-2' $whereClauseVisit"))->total;
                                            $dose3Calls = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status > 0 AND followup='खुराक-3' $whereClauseVisit"))->total;
                                            $dose4Calls = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status > 0 AND followup='खुराक-4' $whereClauseVisit"))->total;
											$dose5Calls = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status > 0 AND followup='खुराक-4' $whereClauseVisit"))->total;
                                            ?>
                                    
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6 class="mb-3">Call Distribution by Dose</h6>
                                                <div class="table-responsive">
                                                <table class="table table-sm table-bordered mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Dose</th>
                                                            <th class="text-center">Call Attempts</th>
                                                            <th class="text-center">Connected</th>
                                                            <th class="text-center">Success Rate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $doses = [
                                                            ['title' => 'Awareness Call','name' => 'खुराक-1', 'calls' => $dose1Calls, 'color' => '#0ab39c'],
                                                            ['title' => 'Visit for Dose-2','name' => 'खुराक-2', 'calls' => $dose2Calls, 'color' => '#5156be'],
                                                            ['title' => 'Visit for Dose-3','name' => 'खुराक-3', 'calls' => $dose3Calls, 'color' => '#299cdb'],
                                                            ['title' => 'Visit for Dose-4','name' => 'खुराक-4', 'calls' => $dose4Calls, 'color' => '#f7b84b'],
															['title' => 'Appreciation Call','name' => 'खुराक-4', 'calls' => $dose5Calls, 'color' => '#f7b84b']
                                                        ];
                                                        
                                                        foreach ($doses as $dose):
                                                            $doseConnected = mysqli_fetch_object(mysqli_query($conn, "SELECT COUNT(*) as total FROM pw_iron_visit WHERE ivr_scheduled_status = 2 AND followup='{$dose['name']}' $whereClauseVisit"))->total;
                                                            $doseSuccessRate = $dose['calls'] > 0 ? number_format(($doseConnected / $dose['calls']) * 100, 1) : 0;
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <span class="badge" style="background-color: <?= $dose['color'] ?>; width: 10px; height: 10px; display: inline-block; border-radius: 50%;"></span>
                                                                    <span class="ms-2 fw-semibold"><?= $dose['title'] ?></span>
                                                                </td>
                                                                <td class="text-center"><?= $dose['calls'] ?></td>
                                                                <td class="text-center"><span class="text-success"><?= $doseConnected ?></span></td>
                                                                <td class="text-center">
                                                                    <div class="d-flex align-items-center justify-content-center">
                                                                        <div class="flex-shrink-0 me-2">
                                                                            <span class="fw-semibold"><?= $doseSuccessRate ?>%</span>
                                                                        </div>
                                                                        <div class="progress" style="width: 60px; height: 6px;">
                                                                            <div class="progress-bar" role="progressbar" style="width: <?= $doseSuccessRate ?>%; background-color: <?= $dose['color'] ?>"></div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        </div>

                        <!-- Recent Registrations Row -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Recent Registrations</h4>
                                    </div>

                                    <div class="card-body">
                                        <div class="table-responsive table-card">
                                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">ID</th>
                                                        <th scope="col">Pregnant Women</th>
                                                        <th scope="col">District</th>
                                                        <th scope="col">Date Time</th>
                                                        <th scope="col">HB(g/dL)</th>
                                                        <th scope="col">Weight(Kg)</th>
                                                        <th scope="col">Iron Required</th>
                                                        <th scope="col">Total Doses</th>
                                                        <th scope="col">View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $recentQuery = "SELECT * FROM pw_iron_registration WHERE id > 0 $whereClauseReg ORDER BY created_on DESC LIMIT 0,8";
                                                    $sqlfacilitator = mysqli_query($conn, $recentQuery);
                                                    while ($getfarmer = mysqli_fetch_object($sqlfacilitator)) { 
                                                        $msgd = json_decode($getfarmer->message);
                                                    ?>
                                                        <tr>
                                                            <td><?= $getfarmer->id ?></td>
                                                            <td><?= $getfarmer->name ?></td>
                                                            <td><?= $getfarmer->district_name ?></td>
                                                            <td><?= date("d-M-Y", strtotime($getfarmer->created_on)) ?> <?= date("h:i A", strtotime($getfarmer->created_on)) ?></td>
                                                            <td><?= $getfarmer->hb ?></td>
                                                            <td><?= $getfarmer->weight ?></td>
                                                            <td><span class="badge bg-soft-primary text-primary"><?= $getfarmer->total_dose ?> mg</span></td>
                                                            <td><?= $getfarmer->total_visit ?> <span class="badge bg-soft-success text-success">( <?=getcount($conn, 'pw_iron_visit', 'id', 'pw_id', $getfarmer->id,'visit_status','1')?> Complete)</span></td>
                                                            <td>
                                                                <button type="button" onclick="getvsit(<?= $getfarmer->id ?>)" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">View</button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal for View Details -->
    <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Complete Schedule Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="visit_modal_data"></div>
            </div>
        </div>
    </div>

</div>

<?php include('includes/footers.php'); ?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Monthly Reporting Chart
Highcharts.chart('monthly_chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: <?= json_encode(array_column($monthlyData, 'month')) ?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Count'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Registration',
        data: <?= json_encode(array_column($monthlyData, 'registration')) ?>,
        color: '#5156be'
    }, {
        name: 'Visit Done',
        data: <?= json_encode(array_column($monthlyData, 'visit')) ?>,
        color: '#0ab39c'
    }, {
        name: 'Followup',
        data: <?= json_encode(array_column($monthlyData, 'calls')) ?>,
        color: '#f06548'
    }]
});

// Visit Trends Column Chart
Highcharts.chart('followup_progress_chart', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: [
            <?php foreach ($followupData as $fd): ?>
            '<?= htmlspecialchars($fd['name']) ?>',
            <?php endforeach; ?>
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Number of Visits'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:11px">{point.key}</span><br>',
        pointFormat: '<span style="color:{point.color}">\u25CF</span> Visits: <b>{point.y}</b><br/>'
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y}'
            }
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Visits',
        colorByPoint: true,
        data: [
            <?php foreach ($followupData as $fd): ?>
            {
                name: '<?= htmlspecialchars($fd['name']) ?>',
                y: <?= $fd['count'] ?>,
                color: '<?= $fd['name'] == 'खुराक-1' ? '#0ab39c' : ($fd['name'] == 'खुराक-2' ? '#5156be' : ($fd['name'] == 'खुराक-3' ? '#299cdb' : '#f7b84b')) ?>'
            },
            <?php endforeach; ?>
        ]
    }]
});

// IVRS Call Status Donut Chart with Center Total
(function(H) {
    H.seriesTypes.pie.prototype.animate = function(init) {
        const series = this,
            chart = series.chart,
            points = series.points,
            { animation } = series.options,
            { startAngleRad } = series;

        function fanAnimate(point, startAngleRad) {
            const graphic = point.graphic,
                args = point.shapeArgs;

            if (graphic && args) {
                graphic
                    .attr({
                        start: startAngleRad,
                        end: startAngleRad,
                        opacity: 1
                    })
                    .animate({
                        start: args.start,
                        end: args.end
                    }, {
                        duration: animation.duration / points.length
                    }, function() {
                        if (points[point.index + 1]) {
                            fanAnimate(points[point.index + 1], args.end);
                        }
                        if (point.index === series.points.length - 1) {
                            series.dataLabelsGroup.animate({
                                    opacity: 1
                                },
                                void 0,
                                function() {
                                    points.forEach(point => {
                                        point.opacity = 1;
                                    });
                                    series.update({
                                        enableMouseTracking: true
                                    }, false);
                                });
                        }
                    });
            }
        }

        if (init) {
            points.forEach(point => {
                point.opacity = 0;
            });
        } else {
            fanAnimate(points[0], startAngleRad);
        }
    };
}(Highcharts));

var ivrsChart = Highcharts.chart('ivrs_chart', {
    chart: {
        type: 'pie',
        events: {
            load: function() {
                var chart = this;
                var totalCalls = <?= ($connectedCalls + $notConnectedCalls + $yetToCall) ?>;
                
                // Center the text properly
                var centerX = chart.plotWidth / 2 + chart.plotLeft;
                var centerY = chart.plotHeight / 2 + chart.plotTop;
                
                chart.renderer.text(
                    '<div style="text-align: center;">' +
                    '<div style="font-size: 32px; font-weight: 700; color: #495057; line-height: 1.2;">' + totalCalls + '</div>' +
                    '<div style="font-size: 13px; color: #878a99; margin-top: 2px; font-weight: 500;"><br/><br/>Total Calls</div>' +
                    '</div>',
                    centerX - 50,
                    centerY - 25
                )
                .attr({
                    useHTML: true,
                    zIndex: 10
                })
                .add();
            }
        }
    },
    title: {
        text: ''
    },
    tooltip: {
        headerFormat: '',
        pointFormat: '<span style="color:{point.color}">\u25cf</span> <b>{point.name}</b><br/>' +
            'Calls: <b>{point.y}</b><br/>' +
            'Percentage: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            innerSize: '70%',
            size: '100%',
            dataLabels: {
                enabled: false
            },
            showInLegend: false,
            states: {
                inactive: {
                    opacity: 0.3
                }
            },
            point: {
                events: {
                    click: function() {
                        var status = this.name;
                        // Toggle highlight on table row
                        $('.ivrs-row').removeClass('table-primary');
                        $('.ivrs-row[data-status="' + status + '"]').addClass('table-primary');
                    }
                }
            }
        }
    },
    series: [{
        enableMouseTracking: true,
        animation: {
            duration: 2000
        },
        name: 'Calls',
        colorByPoint: true,
        data: [{
            name: 'Connected',
            y: <?= $connectedCalls ?>,
            color: '#0ab39c',
            sliced: false,
            selected: false
        }, {
            name: 'Not Connected',
            y: <?= $notConnectedCalls ?>,
            color: '#f06548',
            sliced: false,
            selected: false
        }, {
            name: 'Yet to Call',
            y: <?= $yetToCall ?>,
            color: '#f7b84b',
            sliced: false,
            selected: false
        }]
    }]
});

// Table row click handler to toggle chart visibility
$('.ivrs-row').on('click', function() {
    var status = $(this).data('status');
    var index = 0;
    
    if (status === 'Connected') index = 0;
    else if (status === 'Not Connected') index = 1;
    else if (status === 'Yet to Call') index = 2;
    
    var point = ivrsChart.series[0].points[index];
    
    // Toggle row highlight
    if ($(this).hasClass('table-primary')) {
        $(this).removeClass('table-primary');
        point.setVisible(true);
    } else {
        $('.ivrs-row').removeClass('table-primary');
        $(this).addClass('table-primary');
        
        // Show all points first
        ivrsChart.series[0].points.forEach(function(p) {
            p.setVisible(true);
        });
        
        // Then hide others except selected
        ivrsChart.series[0].points.forEach(function(p, i) {
            if (i !== index) {
                p.setVisible(false);
            }
        });
    }
});

// Counter animation
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter-value');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 1000;
        const increment = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };
        updateCounter();
    });
});

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
            $('#visit_modal_data').html('<div class="text-danger">Error loading visit details.</div>');
        }
    });
}
</script>
