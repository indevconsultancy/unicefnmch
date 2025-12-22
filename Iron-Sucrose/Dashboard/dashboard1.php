<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php $titleText = 'Dashboard Iron Sucrose'; ?>
<?php include('includes/headers.php'); ?>
<style>
    .highcharts-credits {
        dispaly: none !important;
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
                                </div><!-- end card header -->
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
                                                <h5 class="text-white fs-14 mb-0">
                                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +<?= getcountforToday($conn, 'pw_iron_registration', 'id', 'id>', 0, 'created_on') ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-white" data-target="<?= getcountrow($conn, 'pw_iron_registration', 'id', 'id>', 0) ?>">0</span> </h4>
                                                <a href="pregnant_women.php" class="text-decoration-underline text-white">View all</a>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-success-subtle rounded fs-3">
                                                    <i class="bx  bx bx-female text-success"></i>
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
                                                <p class="text-uppercase fw-medium text-white text-truncate mb-0">Follow-up</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <h5 class="text-white fs-14 mb-0 ">
                                                    <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +<?= getcountforToday($conn, 'pw_iron_visit', 'id', 'visit_status', '1', 'updated_date') ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-white" data-target="<?= getcountrow($conn, 'pw_iron_visit', 'id', 'visit_status', '1') ?>">0</span></h4>
                                                <a href="follow_up.php" class="text-decoration-underline text-white">View all</a>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-info-subtle rounded fs-3">
                                                    <i class="bx bx-list-ul text-info"></i>
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
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-white" data-target="<?= getcountrow($conn, 'pw_facilitator', 'id', 'district_name!', '') ?>">0</span></h4>
                                                <a href="facilitator.php" class="text-decoration-underline text-white">View all</a>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-warning-subtle rounded fs-3">
                                                    <i class="bx bx-user-circle text-warning"></i>
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
                                                <p class="text-uppercase fw-medium text-white text-truncate mb-0"> Health Facility</p>
                                            </div>

                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-4">
                                            <div>
                                                <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value text-white" data-target="<?= getcountrow($conn, 'ivis_facilities', 'id', 'districts!', '') ?>">0</span> </h4>
                                                <a href="health-facility.php" class="text-decoration-underline text-white">View all</a>
                                            </div>
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                                    <i class="bx bxs-bank text-primary"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div> <!-- end row-->

                        <div class="row">
                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-header border-0 align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Reporting (Last 12 Months)</h4>
                                    </div><!-- end card header -->

                                    <div class="card-header p-0 border-0 bg-light-subtle">
                                        <div class="row g-0 text-center">
                                            <div class="col-6 col-sm-4">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value" data-target="<?= getcountrow($conn, 'pw_iron_registration', 'id', 'id>', 0) ?>">0</span></h5>
                                                    <p class="text-muted mb-0">Registration</p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-6 col-sm-4">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value" data-target="<?= getcountrow($conn, 'pw_iron_visit', 'id', 'visit_status', '1') ?>">0</span></h5>
                                                    <p class="text-muted mb-0">Visit</p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-6 col-sm-4">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value" data-target="<?= getcountrow($conn, 'pw_iron_visit', 'id', 'visit_status', '1') * 3 ?>">0</span></h5>
                                                    <p class="text-muted mb-0">Follow-up Calls</p>
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <!--end col-->
                                        </div>
                                    </div><!-- end card header -->

                                    <div class="card-body p-0 pb-2">
                                        <div class="w-100">
                                            <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                            <div class="col-xl-4">
                                <div class="card card-height-100">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Follow-up Progress</h4>
                                        <div class="flex-shrink-0">
                                            <div class="dropdown card-header-dropdown">
                                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div><!-- end card header -->

                                    <div class="card-body">
                                        <div id="store-visits-source" data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]' class="apex-charts" dir="ltr"></div>
                                    </div>
                                </div> <!-- .card-->
                            </div> <!-- .col-->
                            <!-- end col -->
                        </div>


                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card card-height-100">


                                    <div class="card-body">

                                        <figure class="highcharts-figure">
                                            <div id="container"></div>
                                            <p class="highcharts-description">

                                            </p>
                                        </figure>
                                    </div>
                                </div> <!-- .card-->
                            </div> <!-- .col-->

                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Recent Registrations</h4>
                                    </div><!-- end card header -->

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
                                                        <th scope="col">Date Time</th>
                                                        <th scope="col">View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $sqlfacilitator = mysqli_query($conn, "select * from pw_iron_registration order by created_on desc limit 0,8");
                                                    while ($datafacilitator = mysqli_fetch_object($sqlfacilitator)) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $datafacilitator->id ?></td>
                                                            <td><?= $datafacilitator->name ?></td>
                                                            <td>Gaya</td>
                                                            <td> <?= date('d-M-Y H:i:s', strtotime($datafacilitator->created_on)) ?></td>
                                                            <td><?= $datafacilitator->hb ?></td>
                                                            <td><?= $datafacilitator->weight ?></td>
                                                            <td><span class="badge bg-success-subtle text-success"><?= $datafacilitator->total_dose ?> mg</span></td>
                                                            <td>
                                                                <h5 class="fs-14 fw-medium mb-0"><?= $datafacilitator->total_visit ?><span class="text-muted fs-11 ms-1">(1 Completed)</span></h5>
                                                            </td>
                                                            <td>
                                                                <div class="remove">
                                                                    <button onclick="getvsit(<?= $datafacilitator->id ?>)" class="btn btn-sm btn-success remove-item-btn" data-bs-toggle="modal" data-bs-target="#viewmodal">
                                                                        View
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr><!-- end tr -->
                                                    <?php } ?>
                                                </tbody><!-- end tbody -->
                                            </table><!-- end table -->
                                        </div>
                                    </div>
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


    <?php include('includes/footers.php'); ?>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        (function(H) {
            H.seriesTypes.pie.prototype.animate = function(init) {
                const series = this,
                    chart = series.chart,
                    points = series.points,
                    {
                        animation
                    } = series.options,
                    {
                        startAngleRad
                    } = series;

                function fanAnimate(point, startAngleRad) {
                    const graphic = point.graphic,
                        args = point.shapeArgs;

                    if (graphic && args) {

                        graphic
                            // Set inital animation values
                            .attr({
                                start: startAngleRad,
                                end: startAngleRad,
                                opacity: 1
                            })
                            // Animate to the final position
                            .animate({
                                start: args.start,
                                end: args.end
                            }, {
                                duration: animation.duration / points.length
                            }, function() {
                                // On complete, start animating the next point
                                if (points[point.index + 1]) {
                                    fanAnimate(points[point.index + 1], args.end);
                                }
                                // On the last point, fade in the data labels, then
                                // apply the inner size
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
                                            chart.update({
                                                plotOptions: {
                                                    pie: {
                                                        innerSize: '40%',
                                                        borderRadius: 8
                                                    }
                                                }
                                            });
                                        });
                                }
                            });
                    }
                }

                if (init) {
                    // Hide points on init
                    points.forEach(point => {
                        point.opacity = 0;
                    });
                } else {
                    fanAnimate(points[0], startAngleRad);
                }
            };
        }(Highcharts));

        Highcharts.chart('container', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'IVRS Call Status'
            },
            subtitle: {
                text: ''
            },
            tooltip: {
                headerFormat: '',
                pointFormat: '<span style="color:{point.color}">\u25cf</span> ' +
                    '{point.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    borderWidth: 2,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b><br>{point.y}',
                        distance: 20
                    }
                }
            },
            series: [{
                // Disable mouse tracking on load, enable after custom animation
                enableMouseTracking: false,
                animation: {
                    duration: 2000
                },
                colorByPoint: true,
                data: [{
                        name: 'Not Connected',
                        y: 6
                    }, {
                        name: 'Yet to Call',
                        y: 12
                    },
                    {
                        name: 'Connected',
                        y: 27
                    }
                ]
            }]
        });
    </script>

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