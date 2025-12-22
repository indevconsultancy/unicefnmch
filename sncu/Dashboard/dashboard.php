<?php include('includes/config.php'); ?>
<?php include('includes/functions.php');
session_start();
?>
<!--
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#000000">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
-->

<?php include('includes/headers.php'); ?>

<!-- dashboard Filter -->
<?php
$reg_ftr = '';
$mon_ftr = '';
$call_fun = '';
$disqry = '';
$join = '';
$outqry = '';
$dtt = '';
$fdtt = '';
$join .= ' LEFT JOIN registration_form ON monitoring_data.registration_id = registration_form.id';

if (isset($_REQUEST['search'])) {
  // From Date
  if (!empty($_GET['fdate'])) {
    $fromDate = date('Y-m-d', strtotime($_GET['fdate']));
    $reg_ftr .= " AND DATE(r.created_at) >= '$fromDate'";
    $dtt .= " AND DATE(registration_form.created_at) >= '$fromDate'";
    $fdtt .= " AND DATE(f.created_at) >= '$fromDate'";
    $mon_dtt .= " AND DATE(registration_form.created_at) >= '$fromDate'";
    $call_fun .= " AND DATE(created_at) >= '$fromDate'";
  }

  // To Date
  if (!empty($_GET['tdate'])) {
    $toDate = date('Y-m-d', strtotime($_GET['tdate']));
    $reg_ftr .= " AND DATE(r.created_at) <= '$toDate'";
    $dtt .= " AND DATE(registration_form.created_at) <= '$toDate'";
    $fdtt .= " AND DATE(f.created_at) <= '$toDate'";
    $mon_dtt .= " AND DATE(registration_form.created_at) <= '$toDate'";
    $call_fun .= " AND DATE(created_at) <= '$toDate'";
  }

  // Dictionary Selection
  if (!empty($_GET['sel_dict'])) {
    $dict = $_GET['sel_dict'];
    $outqry .= '  AND registration_form.status="1" AND registration_form.district_id = ' . $dict . '';
    $reg_ftr .= " AND r.district_id = '$dict'";
    $disqry .= " AND registration_form.district_id = '$dict'";
    $fol_ftr .= " AND d.district_id='$dict'";
    $call_fun .= " AND district_id='$dict'";
  }
}
?>


<style>
  /*html, body {
  overscroll-behavior-y: contain; /* or none */
  touch-action: pan-x pan-y;
  }

  */ .highcharts-credits {
    dispaly: none !important;
  }

  .circle {
    width: 100px;
    height: 100px;
    display: flex;
    font-weight: bold;
    font-size: 1.2rem;
    background-color: #d0eed1;
    justify-content: center;
    border-radius: 50%;
    text-align: center;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }
</style>
<div class="main-content">

  <div class="page-content">
    <div class="container-fluid">

      <div class="row">
        <div class="col">

          <div class="h-100">
            <div class="row mb-3 pb-1">
              <div class="col-12 d-flex justify-content-between">
                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                  <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">Good Morning, <?= $_SESSION['username']; ?></h4>
                  </div>
                </div><!-- end card header -->
                <!--Start filter-->
                <div class="col-md-9 text-end">
                  <?php
                  $dis = mysqli_query($conn, "SELECT district_id,district_name FROM district_master WHERE status='1'");
                  ?>
                  <form>
                    <div class="row filter_css res-filter clearfix g-1 justify-content-end">
                      <!-- Status Dropdown -->
                      <div class="col-lg-2 col-md-4 col-sm-12">
                        <select class="form-select shadow-none" name="sel_dict" id="sel_dict">
                          <option value="">Select District</option>
                          <?php
                          while ($row = mysqli_fetch_assoc($dis)) {
                            $district = htmlspecialchars($row['district_name']);
                            $district_id = htmlspecialchars($row['district_id']);
                            echo "<option value='$district_id'>$district</option>";
                          }
                          ?>
                        </select>
                      </div>

                      <!-- From Date -->
                      <div class="col-lg-2 col-md-4">
                        <input type="text" id="from_datepicker" data-bs-placement="top" data-bs-toggle="tooltip"
                          title="From date" class="form-control" placeholder="From Date" name="fdate" value="<?= (isset($_GET['fdate'])) ? $_GET['fdate'] : ''; ?>">
                      </div>

                      <!-- To Date -->
                      <div class="col-lg-2 col-md-4">
                        <input type="text" id="to_datepicker" data-bs-placement="top" data-bs-toggle="tooltip"
                          title="To date" class="form-control" placeholder="To Date" name="tdate" value="<?= (isset($_GET['tdate'])) ? $_GET['tdate'] : '' ?>">
                      </div>

                      <!-- Search Button -->
                      <div class="col-lg-2 col-md-4">
                        <div class="form-group">
                          <button type="submit"
                            class="btn btn-success width-md waves-effect waves-light form-control"
                            id="btnsearch" name="search">Apply</button>
                        </div>
                      </div>

                      <!-- Clear Filter Button -->
                      <div class="col-lg-2 col-md-4">
                        <div class="form-group">
                          <a href="dashboard.php"
                            class="btn btn-primary width-md waves-effect waves-light form-control">Clear Filter</a>
                        </div>
                      </div>

                    </div>
                  </form>
                </div>

                <!-- Filter End-->
              </div>
              <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
              <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-animate bg-primary">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white text-truncate mb-0"> Total Registration</p>
                      </div>
                      <div class="flex-shrink-0">
                        <h5 class="text-success fs-14 mb-0 text-white">
                          <i class="ri-arrow-right-up-line fs-13 align-middle text-white"></i> +<?= getcountforToday($conn, 'registration_form', 'id', 'id>', 0, 'created_at') ?>
                        </h5>
                      </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                      <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-white" data-target="<?= getcountrow($conn, 'registration_form', 'id', 'id>', 0, "$call_fun") ?>">0</span> </h4>
                        <a href="child-registration.php" class="text-decoration-underline">View List</a>
                      </div>
                      <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success-subtle rounded fs-3">
                          <!-- <i class="bx bx-user-plus text-success"></i> -->
                          <img src="assets/newborn.png" height="30" width="30">
                        </span>
                      </div>
                    </div>
                  </div><!-- end card body -->
                </div><!-- end card -->
              </div><!-- end col -->

              <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-animate bg-info">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white text-truncate mb-0">Discharge</p>
                      </div>
                      <div class="flex-shrink-0">
                        <h5 class="text-danger fs-14 mb-0">
                          <!-- <i class="ri-arrow-right-down-line fs-13 align-middle"></i> -->
                        </h5>
                      </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                      <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white"><span class="counter-value" data-target="<?= getcountrowid($conn, 'monitoring_data', 'id', 'type_of_monitoring', 'Discharge Day') ?>">0</span></h4>
                        <a href="discharge.php" class="text-decoration-underline text-white">View List</a>
                      </div>
                      <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info-subtle rounded fs-3">
                          <!-- <i class="bx bx-shopping-bag text-info"></i> -->
                          <img src="assets/mother-and-son.png" height="30" width="30">
                        </span>
                      </div>
                    </div>
                  </div><!-- end card body -->
                </div><!-- end card -->
              </div><!-- end col -->

              <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-animate bg-success">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white text-truncate mb-0">Post Discharge follow Up</p>
                      </div>
                      <div class="flex-shrink-0">
                        <h5 class="text-success fs-14 mb-0">
                          <!-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i>  -->
                        </h5>
                      </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                      <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white"><span class="counter-value" data-target="<?= getcounttotal($conn, 'follow_up', 'id') ?>">0</span></h4>
                        <a href="post_discharge.php" class="text-decoration-underline text-white">View List</a>
                      </div>
                      <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning-subtle rounded fs-3">
                          <i class="bx bx-user-check text-primary"></i>
                        </span>
                      </div>
                    </div>
                  </div><!-- end card body -->
                </div><!-- end card -->
              </div><!-- end col -->

              <div class="col-xl-3 col-md-6">
                <!-- card -->
                <div class="card card-animate bg-warning">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white text-truncate mb-0"> Total SNCU</p>
                      </div>
                      <div class="flex-shrink-0">
                        <h5 class="text-muted fs-14 mb-0">

                        </h5>
                      </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                      <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white"><span class="counter-value" data-target="<?= getcounttotal($conn, 'sncu_master', 'id') ?>">0</span> </h4>
                        <a href="sncu_list.php" class="text-decoration-underline text-white">View List</a>
                      </div>
                      <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary-subtle rounded fs-3">
                          <i class="bx bx-clinic text-primary"></i>
                        </span>
                      </div>
                    </div>
                  </div><!-- end card body -->
                </div><!-- end card -->
              </div><!-- end col -->
            </div> <!-- end row-->
            <?php
            $sqlMonthly = "SELECT DATE_FORMAT(created_at, '%b-%Y') AS month_year, 'Registration' AS activity_type, COUNT(*) AS total FROM registration_form WHERE created_at >= DATE_FORMAT(CURDATE() - INTERVAL 11 MONTH, '%Y-%m-01') $disqry $dtt GROUP BY month_year UNION SELECT DATE_FORMAT(date_of_admission, '%b-%Y') AS month_year, 'Discharge' AS activity_type, COUNT(*) AS total FROM monitoring_data LEFT JOIN registration_form ON monitoring_data.registration_id = registration_form.id WHERE type_of_monitoring = 'Discharge Day' $disqry $dtt AND date_of_admission >= DATE_FORMAT(CURDATE() - INTERVAL 11 MONTH, '%Y-%m-01') GROUP BY month_year UNION SELECT DATE_FORMAT(date_of_admission, '%b-%Y') AS month_year, 'Admission' AS activity_type, COUNT(*) AS total FROM monitoring_data LEFT JOIN registration_form ON monitoring_data.registration_id = registration_form.id WHERE type_of_monitoring = 'Date of Admission' $disqry $dtt AND date_of_admission >= DATE_FORMAT(CURDATE() - INTERVAL 11 MONTH, '%Y-%m-01') GROUP BY month_year UNION SELECT DATE_FORMAT(date_of_visit, '%b-%Y') AS month_year, 'Follow-up' AS activity_type, COUNT(*) AS total FROM follow_up LEFT JOIN registration_form ON follow_up.registration_id = registration_form.id WHERE date_of_visit >= DATE_FORMAT(CURDATE() - INTERVAL 11 MONTH, '%Y-%m-01') AND registration_form.status = '1' $disqry $dtt GROUP BY month_year ORDER BY STR_TO_DATE(month_year, '%b-%Y')";
            $resultMonthly = mysqli_query($conn, $sqlMonthly);

            // Prepare empty structure
            $data = [];
            $activity_types = ['Registration', 'Admission', 'Discharge', 'Follow-up'];
            $total_sum = array_fill_keys($activity_types, 0);
            while ($row = mysqli_fetch_assoc($resultMonthly)) {
              $month = $row['month_year'];
              $type = $row['activity_type'];
              $count = (int)$row['total'];

              if (!isset($data[$month])) {
                $data[$month] = array_fill_keys($activity_types, 0);
              }
              $data[$month][$type] = $count;

              // Track total sum
              if (isset($total_sum[$type])) {
                $total_sum[$type] += $count;
              }
            }

            // Prepare categories and stacked series
            $categories = array_keys($data);
            $series = [];

            foreach ($activity_types as $type) {
              $series_data = [];
              foreach ($categories as $month) {
                $series_data[] = $data[$month][$type];
              }
              $series[] = [
                'name' => $type,
                'data' => $series_data
              ];
            }
            ?>
            <div class="row">
              <div class="col-xl-8">
                <div class="card">
                  <div class="card-header border-0 align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Activity Done (Last 12 Months)</h4>
                    <!--<div>
                                                    <button type="button" class="btn btn-soft-secondary btn-sm">
                                                        ALL
                                                    </button>
                                                    <button type="button" class="btn btn-soft-secondary btn-sm">
                                                        1M
                                                    </button>
                                                    <button type="button" class="btn btn-soft-secondary btn-sm">
                                                        6M
                                                    </button>
                                                    <button type="button" class="btn btn-soft-primary btn-sm">
                                                        1Y
                                                    </button>
                                                </div>-->
                  </div><!-- end card header -->

                  <div class="card-header p-0 border-0 bg-light-subtle">
                    <div class="row g-0 text-center">
                      <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                          <h5 class="mb-1"><span class="counter-value" data-target="<?= $total_sum['Registration'] ?>">0</span></h5>
                          <p class="text-muted mb-0">Registration</p>
                        </div>
                      </div>
                      <!--end col-->
                      <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                          <h5 class="mb-1"><span class="counter-value" data-target="<?= $total_sum['Admission'] ?>">0</span></h5>
                          <p class="text-muted mb-0">Admission</p>
                        </div>
                      </div>
                      <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                          <h5 class="mb-1"><span class="counter-value" data-target="<?= $total_sum['Discharge'] ?>">0</span></h5>
                          <p class="text-muted mb-0">Discharge</p>
                        </div>
                      </div>
                      <!--end col-->
                      <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                          <h5 class="mb-1"><span class="counter-value" data-target="<?= $total_sum['Follow-up'] ?>">0</span></h5>
                          <p class="text-muted mb-0">Follow-up</p>
                        </div>
                      </div>
                      <!--end col-->

                      <!--end col-->
                    </div>
                  </div><!-- end card header -->

                  <div class="card-body p-0 pb-2">
                    <div class="w-100">
                      <div id="activity_chart"></div>
                    </div>
                  </div><!-- end card body -->
                </div><!-- end card -->
              </div><!-- end col -->

              <div class="col-xl-4">
                <!-- card -->
                <div class="card card-height-100">
                  <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Registration by Locations</h4>
                    <div class="flex-shrink-0">
                      <button type="button" class="btn btn-soft-primary btn-sm">
                        Export Report
                      </button>
                    </div>
                  </div><!-- end card header -->

                  <!-- card body -->
                  <div class="card-body">

                    <div id="map" style="height:315px;width:100%;"></div>

                    <div class="px-2 py-2 mt-1">
                      <?php $dist_data = '';
                      $district_qry = mysqli_query($conn, "SELECT district_id,COUNT(id) as total_form FROM registration_form WHERE 1=1 $disqry $dtt GROUP BY district_id");
                      while ($district_data = mysqli_fetch_array($district_qry)) {
                        $dist_data .= "['" . $district_data['district_id'] . "', " . $district_data['total_form'] . "],";
                        $per = round(($district_data['total_form'] * 100) / getcountrow($conn, 'registration_form', 'id', 'id>', 0), 0)
                      ?>
                        <p class="mb-1"><?= getone($conn, 'district_master', 'district_name', 'district_id', $district_data['district_id']) ?> <span class="float-end"><?= $per ?>%</span></p>
                        <div class="progress mt-2" style="height: 6px;">
                          <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: <?= $per ?>%" aria-valuenow="<?= $per ?>" aria-valuemin="0" aria-valuemax="<?= $per ?>"></div>
                        </div>
                      <?php } ?>


                    </div>
                  </div>
                  <!-- end card body -->
                </div>
                <!-- end card -->
              </div>
              <!-- end col -->
            </div>
            <?php
            // preterm
            $ga_less_37 = getoneless($conn, 'registration_form', 'id', 'gestational_age_LBW<', '37');
            $pre_percent = ($ga_less_37 / getcounttotal($conn, 'registration_form', 'id')) * 100;
            $pre_data = number_format($pre_percent, 2);

            // lbw
            $lbw_percent = (getcountrow($conn, 'registration_form', 'id', 'was_baby_lbw_at_time_of_birth_kg=', 'LBW') / getcounttotal($conn, 'registration_form', 'id')) * 100;
            $lbw = number_format($lbw_percent, 2);

            // male
            $male_percent = (getcountrow($conn, 'registration_form', 'id', 'sex=', 'Male') / getcounttotal($conn, 'registration_form', 'id')) * 100;
            $mle = number_format($male_percent, 2);
            ?>

            <!-- Total admission of 1910 since May 2024 -->
            <div class="row shadow p-3 mb-3 bg-white rounded m-1">
              <div class="col-12">
                <h5>Total admission of <?= getcounttotal($conn, 'registration_form', 'id') ?> since April 2024</h5>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-3">
                <!-- card -->
                <div class="card card-height-100">
                  <div class="card-height-50">
                    <center>
                      <p class="mt-4 fw-bold fs-4">Preterm</p>
                    </center>
                    <!-- card body -->
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                      <div class="circle"><?= $pre_data ?> %</div>
                      <p class="mt-3 text-center">
                        Out of <?= getoneless($conn, 'registration_form', 'id', 'gestational_age_LBW>', '0'); ?> records with GA, <?= $pre_data ?>% are preterm < 37 weeks of GA
                          </p>
                    </div>
                  </div>
                  <!-- end card body -->
                </div>
                <!-- end card -->
              </div>
              <div class="col-xl-3">
                <!-- card -->
                <div class="card card-height-100">
                  <div class="card-height-50">
                    <center>
                      <p class="mt-4 fw-bold fs-4">LBW</p>
                    </center>
                    <!-- card body -->
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                      <div class="circle"><?= $lbw ?> %</div>
                      <p class="mt-3 text-center">
                        Out of <?= getoneless($conn, 'registration_form', 'id', 'birth_weight_kg>', '0') ?> records with birth weight.
                      </p>
                    </div>
                  </div>
                  <!-- end card body -->
                </div>
                <!-- end card -->
              </div>
              <div class="col-xl-3">
                <!-- card -->
                <div class="card card-height-100">
                  <div class="card-height-50">
                    <center>
                      <p class="mt-4 fw-bold fs-4">Boys</p>
                    </center>
                    <!-- card body -->
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                      <div class="circle"><?= $mle ?> %</div>
                      <p class="mt-3 text-center">
                        Higher proportion of boys being admitted
                      </p>
                    </div>
                  </div>
                  <!-- end card body -->
                </div>
                <!-- end card -->
              </div>
              <div class="col-xl-3">
                <!-- card -->
                <div class="card card-height-100">
                  <!-- card body -->
                  <div class="card-height-50">
                    <div class="card-header border-0 align-items-center d-flex">
                      <h4 class="card-title mb-0 flex-grow-1">Pre-Term & Term</h4>
                    </div>
                    <div id="term_chart" style="height: 291px;"></div>
                  </div>
                  <!-- end card body -->
                </div>
                <!-- end card -->
              </div>
            </div>

            <div class="row">
              <!-- Gestational Age Chart -->
              <div class="col-xl-6">
                <div class="card card-height-100">
                  <div class="card-height-50">
                    <div class="card-header border-0 align-items-center d-flex">
                      <h4 class="card-title mb-0 flex-grow-1">Gestational age (weeks)</h4>
                    </div>
                    <div id="term_chart1"></div>
                  </div>
                </div>
              </div>
              <!-- Discharge chart -->
              <div class="col-xl-6">
                <div class="card card-height-100">
                  <div class="card-height-50">
                    <div class="card-header border-0 align-items-center d-flex">
                      <h4 class="card-title mb-0 flex-grow-1">Exit outcomes</h4>
                    </div>
                    <div id="dis_chart"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Feeding Practice -->
            <div class="row">
              <!-- Single Card with Two Side-by-Side Charts -->
              <div class="col-xl-8">
                <div class="card card-height-100">
                  <div class="card-header border-0 align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1 text-center fs-3">Feeding Practices</h4>
                  </div>

                  <div class="card-body">
                    <div class="d-flex justify-content-between gap-3">
                      <!-- Chart 1 -->
                      <div id="at_adm" style="width: 50%; height: 400px;"></div>

                      <!-- Chart 2 -->
                      <div id="at_exitt" style="width: 52%; height: 420px;"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4">
                <div class="card card-height-100">
                  <div class="card-height-50">
                    <div class="card-header border-0 align-items-center d-flex">
                      <h4 class="card-title mb-0 flex-grow-1">Exclusivity of Fenton’s and Intergrowth 21st charts</h4>
                    </div>
                    <div id="fent_inter"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xl-6">
                <div class="card">
                  <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Recent Registration</h4>
                    <div class="flex-shrink-0">
                      <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="fw-semibold text-uppercase fs-12">Sort by:
                          </span><span class="text-muted">Today<i class="mdi mdi-chevron-down ms-1"></i></span>
                        </a>
                      </div>
                    </div>
                  </div><!-- end card header -->

                  <div class="card-body">
                    <div class="table-responsive table-card">
                      <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                        <tbody>
                          <thead>
                            <tr>
                              <th>Unique Id</th>
                              <th>District</th>
                              <th>Monitor Name</th>
                              <th>Reg_Date</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <?php $sqlfacilitator = mysqli_query($conn, "SELECT r.id,r.unique_id_of_body,r.monitor_name,r.monitor_institution,r.baby_date_of_birth,r.sex,r.status,r.created_at,d.district_name from registration_form AS r JOIN district_master AS d ON r.district_id = d.district_id WHERE r.status='1' $reg_ftr order by r.id desc limit 5");
                          while ($datafacilitator = mysqli_fetch_object($sqlfacilitator)) {
                          ?>
                            <tr>
                              <th scope="row"><?= $datafacilitator->unique_id_of_body ?></th>
                              <td><?= $datafacilitator->district_name ?></td>
                              <td><?= $datafacilitator->monitor_name ?></td>
                              <td><?= date('Y-m-d', strtotime($datafacilitator->created_at)) ?></td>
                              <td>
                                <button type="button" class="btn btn-sm btn-secondary remove-item-btn" data-bs-toggle="modal" data-bs-target="#regmodal" onclick="reg_detail(<?= $datafacilitator->id ?>,'<?= $datafacilitator->unique_id_of_body ?>')">
                                  View
                                </button>
                              </td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>

                    <div class="align-items-center mt-4 pt-2 justify-content-between row text-center text-sm-start">
                      <div class="col-sm">
                        <div class="text-muted">
                          Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">All</span> Results
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-6">
                <div class="card card-height-100">
                  <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Recent Fallow-Up</h4>
                    <div class="flex-shrink-0">
                      <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                        </a>
                        <!-- <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="#">Download Report</a>
                                                            <a class="dropdown-item" href="#">Export</a>
                                                            <a class="dropdown-item" href="#">Import</a>
                                                        </div> -->
                      </div>
                    </div>
                  </div><!-- end card header -->

                  <div class="card-body">
                    <div class="table-responsive table-card">
                      <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                        <tbody>
                          <thead>
                            <tr>
                              <th>Unique Id</th>
                              <th>Follow-up</th>
                              <th>Monitor</th>
                              <th>Visit Date</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <?php
                          $sqlfacilitator = mysqli_query($conn, "SELECT f.id,f.type_of_time, f.date_of_visit,f.baby_weight,r.id,r.unique_id_of_body,r.monitor_name,r.monitor_institution,r.baby_date_of_birth,d.district_name from follow_up as f join registration_form as r on f.registration_id=r.id JOIN district_master AS d ON r.district_id = d.district_id Where 1=1 $fdtt order by f.id desc limit 5");
                          while ($datafacilitator = mysqli_fetch_object($sqlfacilitator)) {
                          ?>
                            <tr>
                              <th scope="row"><?= $datafacilitator->unique_id_of_body ?></th>
                              <td><?= $datafacilitator->type_of_time ?></td>
                              <td><?= $datafacilitator->monitor_name ?></td>
                              <td><?= $datafacilitator->date_of_visit ?></td>
                              <td>
                                <button type="button" class="btn btn-sm btn-secondary remove-item-btn" data-bs-toggle="modal" data-bs-target="#regmodal" onclick="fol_detail(<?= $datafacilitator->id ?>, '<?= $datafacilitator->unique_id_of_body ?>')">
                                  View
                                </button>
                              </td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table><!-- end table -->
                    </div>

                    <div class="align-items-center mt-4 pt-2 justify-content-between row text-center text-sm-start">
                      <div class="col-sm">
                        <div class="text-muted">
                          Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">All</span> Results
                        </div>
                      </div>
                      <!-- <div class="col-sm-auto  mt-3 mt-sm-0">
                                                        <ul class="pagination pagination-separated pagination-sm mb-0 justify-content-center">
                                                            <li class="page-item disabled">
                                                                <a href="#" class="page-link">←</a>
                                                            </li>
                                                            <li class="page-item">
                                                                <a href="#" class="page-link">1</a>
                                                            </li>
                                                            <li class="page-item active">
                                                                <a href="#" class="page-link">2</a>
                                                            </li>
                                                            <li class="page-item">
                                                                <a href="#" class="page-link">3</a>
                                                            </li>
                                                            <li class="page-item">
                                                                <a href="#" class="page-link">→</a>
                                                            </li>
                                                        </ul>
                                                    </div> -->
                    </div>

                  </div> <!-- .card-body-->
                </div> <!-- .card-->
              </div> <!-- .col-->
            </div> <!-- end row-->

          </div> <!-- end .h-100-->

        </div> <!-- end col -->
      </div>

    </div>
    <!-- container-fluid -->
  </div>
  <!-- End Page-content -->
  <?php include('includes/footers.php'); ?>

  <!-- follow Up Modal Start -->
  <div class="modal fade" id="regmodal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
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

    <!-- Gestational Age chart Data -->
    <?php
    $query = mysqli_query($conn, "
                            SELECT
                                SUM(CASE WHEN gestational_age_LBW < 33 and gestational_age_LBW >0 THEN 1 ELSE 0 END) AS very_pre_term,
                                SUM(CASE WHEN gestational_age_LBW >= 33 AND gestational_age_LBW < 37  THEN 1 ELSE 0 END) AS pre_term,
                                SUM(CASE WHEN gestational_age_LBW >= 37 AND gestational_age_LBW < 39 THEN 1 ELSE 0 END) AS early_term,
                                SUM(CASE WHEN gestational_age_LBW >= 39 AND gestational_age_LBW < 41 THEN 1 ELSE 0 END) AS full_term,
                                SUM(CASE WHEN gestational_age_LBW >= 41 THEN 1 ELSE 0 END) AS late_term
                            FROM registration_form AS r where r.gestational_age_LBW>0 $reg_ftr
                        ");

    $result = mysqli_fetch_assoc($query);

    // Individual counts
    $very_pre_term = (int)$result['very_pre_term'];
    $pre_term      = (int)$result['pre_term'];
    $early_term    = (int)$result['early_term'];
    $full_term     = (int)$result['full_term'];
    $late_term     = (int)$result['late_term'];
    $totalgs = $very_pre_term + $pre_term + $early_term + $full_term + $late_term;

    $dataaaa = [$very_pre_term, $pre_term, $early_term, $full_term, $late_term];
    ?>
    <!-- Term & Pre-Term chart Data -->
    <?php
    $query = mysqli_query($conn, "
                            SELECT
                                SUM(CASE WHEN gestational_age_LBW < 37 AND was_baby_lbw_at_time_of_birth_kg = 'LBW' THEN 1 ELSE 0 END) AS lbwpre_term,
                                SUM(CASE WHEN gestational_age_LBW >= 37 AND was_baby_lbw_at_time_of_birth_kg = 'LBW' THEN 1 ELSE 0 END) AS lbwterm,
                                SUM(CASE WHEN gestational_age_LBW < 37 AND was_baby_lbw_at_time_of_birth_kg = 'Normal' THEN 1 ELSE 0 END) AS nmpreterm,
                                SUM(CASE WHEN gestational_age_LBW >= 37 AND was_baby_lbw_at_time_of_birth_kg = 'Normal' THEN 1 ELSE 0 END) AS nmterm
                            FROM registration_form AS r WHERE r.gestational_age_LBW>0 $reg_ftr
                        ");

    $result = mysqli_fetch_assoc($query);

    $lbpt = (int)$result['lbwpre_term'];
    $lbt = (int)$result['lbwterm'];
    $nmpt = (int)$result['nmpreterm'];
    $nmt = (int)$result['nmterm'];

    $termdata = [$lbt, $nmt];
    $pretermdata = [$lbpt, $nmpt];
    ?>
    <!-- Exit outcomes -->
    <?php
    $querym = mysqli_query($conn, "
                            SELECT
                                SUM(CASE WHEN type_of_monitoring = 'Discharge Day' AND progress_of_child = 'Discharge' THEN 1 ELSE 0 END) AS discharge,
                                SUM(CASE WHEN type_of_monitoring = 'Discharge Day' AND progress_of_child = 'Referred' THEN 1 ELSE 0 END) AS referred,
                                SUM(CASE WHEN type_of_monitoring = 'Discharge Day' AND progress_of_child = 'LAMA' THEN 1 ELSE 0 END) AS lama,
                                SUM(CASE WHEN type_of_monitoring = 'Discharge Day' AND progress_of_child = 'Death' THEN 1 ELSE 0 END) AS death
                            FROM monitoring_data $join WHERE 1=1 $outqry $mon_dtt
                        ");

    $resultm = mysqli_fetch_assoc($querym);

    $tot_dis = getcountdis($conn, 'monitoring_data', 'id', 'type_of_monitoring', 'Discharge Day');

    // Individual counts
    $d = (int)$resultm['discharge'];
    $r      = (int)$resultm['referred'];
    $l   = (int)$resultm['lama'];
    $dth     = (int)$resultm['death'];

    $discharge = ($d / $tot_dis) * 100;
    $referred = ($r / $tot_dis) * 100;
    $lama = ($l / $tot_dis) * 100;
    $death = ($dth / $tot_dis) * 100;

    ?>
    <!-- Admission Monitoring -->
    <?php
    $querym = mysqli_query($conn, "
                                SELECT
                                    SUM(CASE WHEN type_of_monitoring = 'Date of Admission' AND type_of_feed__Breastmilk = 'Yes' THEN 1 ELSE 0 END) AS b,
                                    SUM(CASE WHEN type_of_monitoring = 'Date of Admission' AND type_of_feed__Animal_Milk = 'Yes' THEN 1 ELSE 0 END) AS a,
                                    SUM(CASE WHEN type_of_monitoring = 'Date of Admission' AND type_of_feed__Formula_Milk = 'Yes' THEN 1 ELSE 0 END) AS f,
                                    SUM(CASE WHEN type_of_monitoring = 'Date of Admission' AND type_of_feed__Parenteral = 'Yes' THEN 1 ELSE 0 END) AS p
                                FROM monitoring_data $join WHERE 1=1 $outqry $mon_dtt
                            ");

    $admi_dt = mysqli_fetch_assoc($querym);

    $tot_rec = getcountrow($conn, 'monitoring_data', 'id', 'type_of_monitoring=', 'Date of Admission');

    // Individual counts
    $bb = (int)$admi_dt['b'];
    $aa = (int)$admi_dt['a'];
    $ff = (int)$admi_dt['f'];
    $pp = (int)$admi_dt['p'];

    $breast = ($bb / $tot_rec) * 100;
    $animal = ($aa / $tot_rec) * 100;
    $formula = ($ff / $tot_rec) * 100;
    $parent = ($pp / $tot_rec) * 100;
    ?>
    <!-- Exit Monitoring -->
    <?php
    $querym = mysqli_query($conn, "
                                    SELECT
                                        SUM(CASE WHEN type_of_monitoring = 'Discharge Day' AND type_of_feed__Breastmilk = 'Yes' THEN 1 ELSE 0 END) AS b,
                                        SUM(CASE WHEN type_of_monitoring = 'Discharge Day' AND type_of_feed__Animal_Milk = 'Yes' THEN 1 ELSE 0 END) AS a,
                                        SUM(CASE WHEN type_of_monitoring = 'Discharge Day' AND type_of_feed__Formula_Milk = 'Yes' THEN 1 ELSE 0 END) AS f,
                                        SUM(CASE WHEN type_of_monitoring = 'Discharge Day' AND type_of_feed__Parenteral = 'Yes' THEN 1 ELSE 0 END) AS p
                                    FROM monitoring_data $join WHERE 1=1 $outqry $mon_dtt
                                ");

    $exit_ds = mysqli_fetch_assoc($querym);

    $tot_drec = getcountrow($conn, 'monitoring_data', 'id', 'type_of_monitoring=', 'Date of Admission');

    // Individual counts
    $ebb = (int)$exit_ds['b'];
    $eaa = (int)$exit_ds['a'];
    $eff = (int)$exit_ds['f'];
    $epp = (int)$exit_ds['p'];

    $ebreast = ($ebb / $tot_drec) * 100;
    $eanimal = ($eaa / $tot_drec) * 100;
    $eformula = ($eff / $tot_drec) * 100;
    $eparent = ($epp / $tot_drec) * 100;
    ?>
    <!-- Fentons and Intergrouth Data -->
    <?php
    $query = mysqli_query($conn, "SELECT
                                        SUM(CASE WHEN fenton_growth_classification = 'SGA' AND growth_status_fenton='1' THEN 1 ELSE 0 END) AS fen_sga,
                                        SUM(CASE WHEN fenton_growth_classification = 'AGA' AND growth_status_fenton='1' THEN 1 ELSE 0 END) AS fen_aga,
                                        SUM(CASE WHEN fenton_growth_classification = 'LGA' AND growth_status_fenton='1' THEN 1 ELSE 0 END) AS fen_lga,
                                        SUM(CASE WHEN intergrowth_classification = 'SGA' AND growth_status_intergrowth='1' THEN 1 ELSE 0 END) AS int_sga,
                                        SUM(CASE WHEN intergrowth_classification = 'AGA' AND growth_status_intergrowth='1' THEN 1 ELSE 0 END) AS int_aga,
                                        SUM(CASE WHEN intergrowth_classification = 'LGA' AND growth_status_intergrowth='1' THEN 1 ELSE 0 END) AS int_lga
                                        FROM monitoring_data $join WHERE 1=1 $outqry $dtt
                                    ");

    $fenint_res = mysqli_fetch_assoc($query);

    $fen_sga = (int)$fenint_res['fen_sga'];
    $fen_aga = (int)$fenint_res['fen_aga'];
    $fen_lga = (int)$fenint_res['fen_lga'];
    $int_sga = (int)$fenint_res['int_sga'];
    $int_aga = (int)$fenint_res['int_aga'];
    $int_lga = (int)$fenint_res['int_lga'];

    $ft = [$fen_sga, $fen_aga, $fen_lga];
    $ig = [$int_sga, $int_sga, $int_sga];

    ?>

    <!--<script src="https://code.highcharts.com/highcharts.js"></script>-->
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/maps/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">



    <!-- Activity Chart -->
    <script>
      Highcharts.chart('activity_chart', {
        chart: {
          type: 'column'
        },
        title: {
          text: ''
        },
        xAxis: {
          categories: <?= json_encode($categories) ?>,
          //crosshair: true
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Total Activities'
          },
          stackLabels: {
            enabled: true,
            style: {
              color: 'black'
            }
          }
        },
        legend: {
          reversed: false
        },
        tooltip: {
          shared: true,
          formatter: function() {
            let s = '<b>' + this.x + '</b>';
            let total = 0;
            this.points.forEach(function(point) {
              s += '<br/>' + point.series.name + ': ' + point.y;
              total += point.y;
            });
            s += '<br/><b>Total: ' + total + '</b>';
            return s;
          }
        },
        plotOptions: {
          column: {
            stacking: 'normal'
          }
        },
        series: <?= json_encode($series) ?>
      });
    </script>

    <!-- Term Chart -->
    <script>
      Highcharts.chart('term_chart', {
        chart: {
          type: 'column'
        },
        title: {
          text: '',
          align: 'left'
        },
        xAxis: {
          categories: ['LBW', 'Normal']
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Label'
          },
          stackLabels: {
            enabled: true
          }
        },
        legend: {
          enabled: true
        },
        tooltip: {
          headerFormat: '<b>{category}</b><br/>',
          pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
          column: {
            stacking: 'normal',
            dataLabels: {
              enabled: true
            }
          }
        },
        series: [{
          name: 'Term',
          data: <?= json_encode($termdata) ?>
        }, {
          name: 'Pre-Term',
          data: <?= json_encode($pretermdata) ?>
        }]
      });
    </script>

    <!-- Gestational Chart -->
    <script>
      Highcharts.chart('term_chart1', {
        chart: {
          type: 'bar'
        },
        title: {
          text: '',
          align: 'center'
        },
        xAxis: {
          categories: [
            'Very Pre-Term(<33wks)', 'Pre-Term(>=33<37)', 'Early Term(>=37<39)', 'Full-Term(>=39<41)', 'Late-Term(>=41)'
          ]
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Total-<?= $totalgs ?>'
          }
        },
        legend: {
          reversed: true
        },
        plotOptions: {
          series: {
            stacking: 'normal',
            dataLabels: {
              enabled: true
            }
          }
        },
        series: [{
          name: '',
          data: <?= json_encode($dataaaa) ?>,
          showInLegend: false
        }]
      });
    </script>

    <!-- Discharge Chart -->
    <script>
      Highcharts.chart('dis_chart', {
        chart: {
          type: 'pie',
          zooming: {
            type: 'xy'
          },
          panning: {
            enabled: true,
            type: 'xy'
          },
          panKey: 'shift'
        },
        title: {
          text: '',
          enabled: false
        },
        tooltip: {
          valueSuffix: '%'
        },
        subtitle: {
          text: ''
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
              enabled: true,
              distance: 20
            }, {
              enabled: true,
              distance: -40,
              format: '{point.percentage:.1f}%',
              style: {
                fontSize: '1.2em',
                textOutline: 'none',
                opacity: 0.7
              },
              filter: {
                operator: '>',
                property: 'percentage',
                value: 10
              }
            }]
          }
        },
        series: [{
          name: 'Percentage',
          colorByPoint: true,
          data: [{
              name: 'Discharge',
              y: <?= $discharge ?>
            },
            {
              name: 'LAMA',
              y: <?= $lama ?>
            },
            {
              name: 'Referad',
              y: <?= $referred ?>
            },
            {
              name: 'Death',
              y: <?= $death ?>
            }
          ]
        }]
      });
    </script>

    <!-- admissiom Chart -->
    <script>
      Highcharts.chart('at_adm', {
        chart: {
          type: 'pie',
          zooming: {
            type: 'xy'
          },
          panning: {
            enabled: true,
            type: 'xy'
          },
          panKey: 'shift'
        },
        title: {
          text: '',
          enabled: false
        },
        tooltip: {
          valueSuffix: '%'
        },
        subtitle: {
          text: 'At admission'
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
              enabled: true,
              distance: 20
            }, {
              enabled: true,
              distance: -40,
              format: '{point.percentage:.1f}%',
              style: {
                fontSize: '1.2em',
                textOutline: 'none',
                opacity: 0.7
              },
              filter: {
                operator: '>',
                property: 'percentage',
                value: 10
              }
            }]
          }
        },
        series: [{
          name: 'Percentage',
          colorByPoint: true,
          data: [{
              name: 'Formula',
              y: <?= $formula ?>
            },
            {
              name: 'Breastmilk',
              y: <?= $breast ?>
            },
            {
              name: 'Parenteral',
              y: <?= $parent ?>
            }
          ]
        }]
      });
    </script>

    <!-- Exit Chart -->
    <script>
      Highcharts.chart('at_exitt', {
        chart: {
          type: 'pie',
          zooming: {
            type: 'xy'
          },
          panning: {
            enabled: true,
            type: 'xy'
          },
          panKey: 'shift'
        },
        title: {
          text: '',
          enabled: false
        },
        tooltip: {
          valueSuffix: '%'
        },
        subtitle: {
          text: 'At exit'
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
              enabled: true,
              distance: 20
            }, {
              enabled: true,
              distance: -40,
              format: '{point.percentage:.1f}%',
              style: {
                fontSize: '1.2em',
                textOutline: 'none',
                opacity: 0.7
              },
              filter: {
                operator: '>',
                property: 'percentage',
                value: 10
              }
            }]
          }
        },
        series: [{
          name: 'Percentage',
          colorByPoint: true,
          data: [{
              name: 'Formula',
              y: <?= $eformula ?>
            },
            {
              name: 'Breastmilk',
              y: <?= $ebreast ?>
            },
            {
              name: 'Parenteral',
              y: <?= $eparent ?>
            }
          ]
        }]
      });
    </script>

    <!-- Fenton & Intergrouth Chart -->
    <script>
      Highcharts.chart('fent_inter', {
        chart: {
          type: 'column'
        },
        title: {
          text: ''
        },
        xAxis: {
          categories: ['SGA', 'AGA', 'LGA'],
          crosshair: true,
          accessibility: {
            description: 'Countries'
          }
        },
        yAxis: {
          enabled: false
        },
        plotOptions: {
          column: {
            pointPadding: 0.2,
            borderWidth: 0
          }
        },
        series: [{
            name: 'Fentons',
            data: <?= json_encode($ft) ?>
          },
          {
            name: 'Intergrowth',
            data: <?= json_encode($ig) ?>
          }
        ]
      });
    </script>

    <script>
      function stateMap(stateLGD) {
        Highcharts.getJSON('https://unicef.indevconsultancy.in/mis/json/' + stateLGD + '.json', function(geojson) {
          Highcharts.mapChart('map', {
            chart: {
              height: '310px',
              map: geojson
            },
            title: {
              text: ''
            },
            accessibility: {
              typeDescription: ''
            },
            mapNavigation: {
              enabled: true,
              buttonOptions: {
                verticalAlign: 'bottom',
                align: 'right'
              }
            },
            colorAxis: {
              min: 1,
              max: 1000,
              type: 'logarithmic',
              stops: [
                [0, '#ff5757'],
                [0.5, '#fed05b'],
                [0.9, '#59d95b']
              ],
              marker: {
                color: '#343'
              }
            },
            plotOptions: {
              series: {
                point: {
                  events: {
                    click: function() {
                      var dtname = this.properties.name;
                      // You can trigger further drilldown if you want
                      // districtMap(this.properties.Dist_LGD);
                    }
                  }
                }
              }
            },
            tooltip: {
              pointFormatter: function() {
                return '<b>' + this.properties.name + '</b>: ' + this.value;
              }
            },
            exporting: {
              enabled: true
            },
            credits: {
              enabled: false
            },
            series: [{
              borderWidth: 1,
              borderColor: 'gray',
              cursor: 'pointer',
              data: [<?= $dist_data ?>],
              keys: ['Dist_LGD', 'value'],
              joinBy: 'Dist_LGD',
              name: 'Total survey',
              states: {
                hover: {
                  color: '#a4edba'
                }
              },
              dataLabels: {
                enabled: true,
                format: '{point.properties.name}'
              }
            }]
          });
        });
      }

      // Load Bihar Map initially
      stateMap('state-10');
    </script>

    <script>
      // Recent Registration
      function reg_detail(hhh, unique1) {
        $('#viewModalLabel').text('Recent Registration');
        $.ajax({
          url: 'ajax/registration_data.php',
          type: 'POST',
          data: {
            reg_id: hhh,
            unique1: unique1
          },
          success: function(response) {
            $('#modalContent').html(response);
          },
          error: function(xhr, status, error) {
            $('#modalContent').html('Error loading data.');
          }
        });
      }

      // Recent Fallow-Up
      function fol_detail(lkg, unique2) {
        $('#viewModalLabel').text('Recent Fallow-Up');
        $.ajax({
          url: 'ajax/registration_data.php',
          type: 'POST',
          data: {
            followup_id: lkg,
            unique2: unique2
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

    <!-- Dashboard Filter start -->
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
    // <script>
      //   // Force fullscreen on page load
      //   function openFullscreen() {
      //     const elem = document.documentElement;
      //     if (elem.requestFullscreen) {
      //       elem.requestFullscreen();
      //     } else if (elem.mozRequestFullScreen) { // Firefox
      //       elem.mozRequestFullScreen();
      //     } else if (elem.webkitRequestFullscreen) { // Chrome, Safari
      //       elem.webkitRequestFullscreen();
      //     } else if (elem.msRequestFullscreen) { // IE/Edge
      //       elem.msRequestFullscreen();
      //     }
      //   }

      //   // Disable right-click
      //   document.addEventListener('contextmenu', function (e) {
      //     e.preventDefault();
      //     alert("Right-click is disabled.");
      //   });

      //   // Block common shortcut keys and all key presses
      //   document.addEventListener('keydown', function (e) {
      //     const tag = e.target.tagName.toLowerCase();
      //     const isInput = tag === 'input' || tag === 'textarea';

      //     if (
      //       !isInput || // block all keys if not in input
      //       (e.ctrlKey || e.metaKey || e.altKey) || // any meta keys
      //       ["F5", "F12", "U", "I", "J", "R"].includes(e.key.toUpperCase())
      //     ) {
      //       e.preventDefault();
      //     }

      //     // Specific dev tools/view source keys
      //     if (
      //       (e.ctrlKey && e.key.toLowerCase() === 'u') || // Ctrl+U
      //       (e.ctrlKey && e.shiftKey && ['i', 'j', 'c'].includes(e.key.toLowerCase())) || // Ctrl+Shift+I/J/C
      //       e.key === "F12" || // F12
      //       e.key === "F5" || // Refresh
      //       (e.ctrlKey && e.key.toLowerCase() === "r") // Ctrl+R
      //     ) {
      //       e.preventDefault();
      //     }
      //   });

      //   // Trigger full screen on load
      //   window.addEventListener('load', function () {
      //     openFullscreen();
      //   });
      // 
    </script>

    <!-- dashboard Filter End -->