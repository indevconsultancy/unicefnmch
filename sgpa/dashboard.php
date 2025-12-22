<?php include('include/config.php'); ?>
<!doctype html>

<!-- <html lang="en" data-layout="horizontal" data-layout-style="" data-layout-position="fixed" data-topbar="light"> -->
<html lang="en" data-layout="horizontal" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="blue" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-body-image="none" data-sidebar-visibility="show">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | सुपोषित ग्राम पंचायत अभियान</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('include/link.php'); ?>
</head>

<body>
    <div id="layout-wrapper">
        <?php include('include/header.php'); ?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col">

                            <div class="h-100">
                                <div class="row mb-3 pb-1">
                                    <div class="col-12">
                                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">

                                            <div class="card-body bg-light-subtle border border-dashed border-start-0 border-end-0">
                                                <form>
                                                    <div class="row g-3">
                                                        <div class="col-sm-3">
                                                            <select class="form-select" id="district" name="district" required="">
                                                                <option value="">-- Select District --</option>
                                                                <option value="199">ARARIA</option>
                                                                <option value="200">ARWAL</option>
                                                                <option value="201">AURANGABAD</option>
                                                                <option value="202">BANKA</option>
                                                                <option value="203">BEGUSARAI</option>
                                                                <option value="204">BHAGALPUR</option>
                                                                <option value="205">BHOJPUR</option>
                                                                <option value="206">BUXAR</option>
                                                                <option value="207">DARBHANGA</option>
                                                                <option value="208">GAYA</option>
                                                                <option value="209">GOPALGANJ</option>
                                                                <option value="210">JAMUI</option>
                                                                <option value="211">JEHANABAD</option>
                                                                <option value="212">KAIMUR(BHABUA)</option>
                                                                <option value="213">KATIHAR</option>
                                                                <option value="214">KHAGARIA</option>
                                                                <option value="215">KISHANGANJ</option>
                                                                <option value="216">LAKHISARAI</option>
                                                                <option value="217">MADHEPURA</option>
                                                                <option value="218">MADHUBANI</option>
                                                                <option value="219">MUNGER</option>
                                                                <option value="220">MUZAFFARPUR</option>
                                                                <option value="221">NALANDA</option>
                                                                <option value="222">NAWADA</option>
                                                                <option value="223">PASHCHIM CHAMPARAN</option>
                                                                <option value="224">PATNA</option>
                                                                <option value="225">PURBA CHAMPARAN</option>
                                                                <option value="226">PURNIA</option>
                                                                <option value="227">SAHARSA</option>
                                                                <option value="228">SAMASTIPUR</option>
                                                                <option value="229">SARAN</option>
                                                                <option value="230">SASARAM(ROHTAS)</option>
                                                                <option value="231">SHEIKHPURA</option>
                                                                <option value="232">SHEOHAR</option>
                                                                <option value="233">SITAMARHI</option>
                                                                <option value="234">SIWAN</option>
                                                                <option value="235">SUPAUL</option>
                                                                <option value="236">VAISHALI</option>
                                                            </select>


                                                        </div>
                                                        <div class="col-sm-3">
                                                            <select class="form-select" id="district" name="district" required="">
                                                                <option value="">-- Select Block --</option>
                                                            </select>


                                                        </div>
                                                        <div class="col-sm-2">
                                                            <select class="form-select" id="district" name="district" required="">
                                                                <option value="">-- Select GP --</option>
                                                            </select>


                                                        </div>

                                                        <div class="col-sm-2">
                                                            <select class="form-select" id="district" name="district" required="">
                                                                <option value="">-- Select Month --</option>

                                                            </select>


                                                        </div>
                                                        <div class="col-sm-2">
                                                            <button type="submit" class="btn btn-primary w-100" onclick="SearchData();">
                                                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Filters
                                                            </button>
                                                        </div>
                                                        <!--end col-->
                                                    </div>
                                                    <!--end row-->
                                                </form>
                                            </div>
                                        </div><!-- end card header -->
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->

                                <div class="row">
                                    <div class="col-xl-3 col-md-6">
                                        <div class="card card-animate bg-primary">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p class="fw-medium text-white mb-0">Total GP's</p>
                                                        <h2 class="mt-4 ff-secondary fw-semibold text-white"><span
                                                                class="counter-value" data-target="148">148</span>
                                                        </h2>
                                                        <p class="mb-0 text-white"><a href="#" class="text-white text-decoration-underline">View List </a> </p>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span class="avatar-title bg-white bg-opacity-25 rounded-circle fs-2">
                                                                <i class="bx bxs-map-alt text-white"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div> <!-- end card-->
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="card card-animate bg-danger">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p class="fw-medium text-white mb-0">Total Districts</p>
                                                        <h2 class="mt-4 ff-secondary fw-semibold text-white"><span
                                                                class="counter-value" data-target="29">29</span>
                                                        </h2>
                                                        <p class="mb-0 text-white"><a href="#" class="text-white text-decoration-underline">View List </a> </p>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span class="avatar-title bg-white bg-opacity-25 rounded-circle fs-2">
                                                                <i class="bx bx-globe text-white"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div> <!-- end card-->
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="card card-animate bg-info">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p class="fw-medium text-white mb-0">Total AWC Reported</p>
                                                        <h2 class="mt-4 ff-secondary fw-semibold text-white"><span
                                                                class="counter-value" data-target="110">110</span>
                                                        </h2>
                                                        <p class="mb-0 text-white"><a href="#" class="text-white text-decoration-underline">View List </a> </p>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span class="avatar-title bg-white bg-opacity-25 rounded-circle fs-2">
                                                                <i class="bx bx-dollar-circle text-white"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div> <!-- end card-->
                                    </div>
                                    <div class="col-xl-3 col-md-6">
                                        <div class="card card-animate bg-success">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p class="fw-medium text-white mb-0">GP Reported this Month</p>
                                                        <h2 class="mt-4 ff-secondary fw-semibold text-white"><span
                                                                class="counter-value" data-target="62">62</span>
                                                        </h2>
                                                        <p class="mb-0 text-white"><a href="#" class="text-white text-decoration-underline">View Data </a> </p>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span class="avatar-title bg-white bg-opacity-25 rounded-circle fs-2">
                                                                <i class="bx bx-dollar-circle text-white"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div> <!-- end card-->
                                    </div>
                                </div> <!-- end row-->

                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="card">


                                            <div class="card-header p-0 border-0 bg-light-subtle">
                                                <div class="row g-0 text-center">
                                                    <div class="col-6 col-sm-3">
                                                        <div class="p-3 border border-dashed border-start-0">
                                                            <h5 class="mb-1"><span class="counter-value"
                                                                    data-target="13">0</span></h5>
                                                            <p class="text-muted mb-0">Reported</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-6 col-sm-3">
                                                        <div class="p-3 border border-dashed border-start-0">
                                                            <h5 class="mb-1"><span class="counter-value"
                                                                    data-target="16">0</span></h5>
                                                            <p class="text-muted mb-0">To be Reported</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-6 col-sm-3">
                                                        <div class="p-3 border border-dashed border-start-0">
                                                            <h5 class="mb-1"><span class="counter-value"
                                                                    data-target="111">0</span></h5>
                                                            <p class="text-muted mb-0">GP Reported</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-6 col-sm-3">
                                                        <div
                                                            class="p-3 border border-dashed border-start-0 border-end-0">
                                                            <h5 class="mb-1 text-success"><span class="counter-value"
                                                                    data-target="62">0</span>%</h5>
                                                            <p class="text-muted mb-0">Performance Ratio</p>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                            </div><!-- end card header -->

                                            <div class="card-body p-0 pb-2">
                                                <div class="w-100">
                                                    <image src="map111.PNG">
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div><!-- end col -->

                                    <div class="col-xl-4">
                                        <div class="card card-height-100">
                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0 flex-grow-1">AWC Ranking</h4>
                                                <div class="flex-shrink-0">
                                                    <div class="dropdown card-header-dropdown">
                                                        <a class="text-reset dropdown-btn" href="#"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <span class="text-muted">Report<i
                                                                    class="mdi mdi-chevron-down ms-1"></i></span>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="#">Download Report</a>
                                                            <a class="dropdown-item" href="#">Export</a>
                                                            <a class="dropdown-item" href="#">Import</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card header -->

                                            <div class="card-body">
                                                <div id="store-visits-source"
                                                    data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]'
                                                    class="apex-charts" dir="ltr"></div>
                                            </div>
                                        </div> <!-- .card-->
                                    </div>
                                    <!-- end col -->
                                </div>

                                <div class="row">
                                    <!-- .col-->

                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-header align-items-center d-flex">
                                                <h4 class="card-title mb-0 flex-grow-1">Recent Submission</h4>
                                                <div class="flex-shrink-0">
                                                    <button type="button" class="btn btn-soft-info btn-sm">
                                                        <i class="ri-file-list-3-line align-middle"></i> Generate Report
                                                    </button>
                                                </div>
                                            </div><!-- end card header -->

                                            <div class="card-body">
                                                <div class="table-responsive table-card">
                                                    <table
                                                        class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                                        <thead class="text-muted table-light">
                                                            <tr>
                                                                <th scope="col">Survey ID</th>
                                                                <th scope="col">AWC Name</th>
                                                                <th scope="col">GP Name</th>
                                                                <th scope="col">Block</th>
                                                                <th scope="col">Districts</th>
                                                                <th scope="col">Submitted By</th>
                                                                <th scope="col">Reported Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <a href="apps-ecommerce-order-details.html"
                                                                        class="fw-medium link-primary">#3021011</a>
                                                                </td>
                                                                <td>
                                                                    AWC-1
                                                                </td>
                                                                <td>GP-1</td>
                                                                <td>
                                                                    <span class="text-success">Block-1</span>
                                                                </td>
                                                                <td>District-1</td>
                                                                <td>User-1</td>
                                                                <td>
                                                                    2025-02-14 02:45
                                                                </td>
                                                            </tr><!-- end tr -->
                                                            <tr>
                                                                <td>
                                                                    <a href="apps-ecommerce-order-details.html"
                                                                        class="fw-medium link-primary">#3021011</a>
                                                                </td>
                                                                <td>
                                                                    AWC-1
                                                                </td>
                                                                <td>GP-1</td>
                                                                <td>
                                                                    <span class="text-success">Block-1</span>
                                                                </td>
                                                                <td>District-1</td>
                                                                <td>User-1</td>
                                                                <td>
                                                                    2025-02-14 02:45
                                                                </td>
                                                            </tr><!-- end tr -->
                                                            <tr>
                                                                <td>
                                                                    <a href="apps-ecommerce-order-details.html"
                                                                        class="fw-medium link-primary">#3021011</a>
                                                                </td>
                                                                <td>
                                                                    AWC-1
                                                                </td>
                                                                <td>GP-1</td>
                                                                <td>
                                                                    <span class="text-success">Block-1</span>
                                                                </td>
                                                                <td>District-1</td>
                                                                <td>User-1</td>
                                                                <td>
                                                                    2025-02-14 02:45
                                                                </td>
                                                            </tr><!-- end tr -->
                                                            <tr>
                                                                <td>
                                                                    <a href="apps-ecommerce-order-details.html"
                                                                        class="fw-medium link-primary">#3021011</a>
                                                                </td>
                                                                <td>
                                                                    AWC-1
                                                                </td>
                                                                <td>GP-1</td>
                                                                <td>
                                                                    <span class="text-success">Block-1</span>
                                                                </td>
                                                                <td>District-1</td>
                                                                <td>User-1</td>
                                                                <td>
                                                                    2025-02-14 02:45
                                                                </td>
                                                            </tr><!-- end tr -->
                                                            <tr>
                                                                <td>
                                                                    <a href="apps-ecommerce-order-details.html"
                                                                        class="fw-medium link-primary">#3021011</a>
                                                                </td>
                                                                <td>
                                                                    AWC-1
                                                                </td>
                                                                <td>GP-1</td>
                                                                <td>
                                                                    <span class="text-success">Block-1</span>
                                                                </td>
                                                                <td>District-1</td>
                                                                <td>User-1</td>
                                                                <td>
                                                                    2025-02-14 02:45
                                                                </td>
                                                            </tr><!-- end tr -->
                                                        </tbody><!-- end tbody -->
                                                    </table><!-- end table -->
                                                </div>
                                            </div>
                                        </div> <!-- .card-->
                                    </div> <!-- .col-->
                                </div> <!-- end row-->

                            </div> <!-- end .h-100-->

                        </div> <!-- end col -->

                        <div class="col-auto layout-rightside-col">
                            <div class="overlay"></div>
                            <div class="layout-rightside">
                                <div class="card h-100 rounded-0">
                                    <div class="card-body p-0">
                                        <div class="p-3">
                                            <h6 class="text-muted mb-0 text-uppercase fw-semibold">Recent Activity</h6>
                                        </div>
                                        <div data-simplebar style="max-height: 410px;" class="p-3 pt-0">
                                            <div class="acitivity-timeline acitivity-main">
                                                <div class="acitivity-item d-flex">
                                                    <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                                        <div
                                                            class="avatar-title bg-success-subtle text-success rounded-circle">
                                                            <i class="ri-shopping-cart-2-line"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 lh-base">Purchase by James Price</h6>
                                                        <p class="text-muted mb-1">Product noise evolve smartwatch </p>
                                                        <small class="mb-0 text-muted">02:14 PM Today</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                                        <div
                                                            class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                                            <i class="ri-stack-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 lh-base">Added new <span
                                                                class="fw-semibold">style collection</span></h6>
                                                        <p class="text-muted mb-1">By Nesta Technologies</p>
                                                        <div class="d-inline-flex gap-2 border border-dashed p-2 mb-2">
                                                            <a href="apps-ecommerce-product-details.html"
                                                                class="bg-light rounded p-1">
                                                                <img src="assets/images/products/img-8.png" alt=""
                                                                    class="img-fluid d-block" />
                                                            </a>
                                                            <a href="apps-ecommerce-product-details.html"
                                                                class="bg-light rounded p-1">
                                                                <img src="assets/images/products/img-2.png" alt=""
                                                                    class="img-fluid d-block" />
                                                            </a>
                                                            <a href="apps-ecommerce-product-details.html"
                                                                class="bg-light rounded p-1">
                                                                <img src="assets/images/products/img-10.png" alt=""
                                                                    class="img-fluid d-block" />
                                                            </a>
                                                        </div>
                                                        <p class="mb-0 text-muted"><small>9:47 PM Yesterday</small></p>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img src="assets/images/users/avatar-2.jpg" alt=""
                                                            class="avatar-xs rounded-circle acitivity-avatar">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 lh-base">Natasha Carey have liked the products
                                                        </h6>
                                                        <p class="text-muted mb-1">Allow users to like products in your
                                                            WooCommerce store.</p>
                                                        <small class="mb-0 text-muted">25 Dec, 2021</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs acitivity-avatar">
                                                            <div class="avatar-title rounded-circle bg-secondary">
                                                                <i class="mdi mdi-sale fs-14"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 lh-base">Today offers by <a
                                                                href="apps-ecommerce-seller-details.html"
                                                                class="link-secondary">Digitech Galaxy</a></h6>
                                                        <p class="text-muted mb-2">Offer is valid on orders of Rs.500 Or
                                                            above for selected products only.</p>
                                                        <small class="mb-0 text-muted">12 Dec, 2021</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs acitivity-avatar">
                                                            <div
                                                                class="avatar-title rounded-circle bg-danger-subtle text-danger">
                                                                <i class="ri-bookmark-fill"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 lh-base">Favorite Product</h6>
                                                        <p class="text-muted mb-2">Esther James have Favorite product.
                                                        </p>
                                                        <small class="mb-0 text-muted">25 Nov, 2021</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs acitivity-avatar">
                                                            <div class="avatar-title rounded-circle bg-secondary">
                                                                <i class="mdi mdi-sale fs-14"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 lh-base">Flash sale starting <span
                                                                class="text-primary">Tomorrow.</span></h6>
                                                        <p class="text-muted mb-0">Flash sale by <a
                                                                href="javascript:void(0);"
                                                                class="link-secondary fw-medium">Zoetic Fashion</a></p>
                                                        <small class="mb-0 text-muted">22 Oct, 2021</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item py-3 d-flex">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs acitivity-avatar">
                                                            <div
                                                                class="avatar-title rounded-circle bg-info-subtle text-info">
                                                                <i class="ri-line-chart-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 lh-base">Monthly sales report</h6>
                                                        <p class="text-muted mb-2"><span class="text-danger">2 days
                                                                left</span> notification to submit the monthly sales
                                                            report. <a href="javascript:void(0);"
                                                                class="link-warning text-decoration-underline">Reports
                                                                Builder</a></p>
                                                        <small class="mb-0 text-muted">15 Oct</small>
                                                    </div>
                                                </div>
                                                <div class="acitivity-item d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img src="assets/images/users/avatar-3.jpg" alt=""
                                                            class="avatar-xs rounded-circle acitivity-avatar" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 lh-base">Frank Hook Commented</h6>
                                                        <p class="text-muted mb-2 fst-italic">" A product that has
                                                            reviews is more likable to be sold than a product. "</p>
                                                        <small class="mb-0 text-muted">26 Aug, 2021</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 mt-2">
                                            <h6 class="text-muted mb-3 text-uppercase fw-semibold">Top 10 Categories
                                            </h6>

                                            <ol class="ps-3 text-muted">
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Mobile & Accessories <span
                                                            class="float-end">(10,294)</span></a>
                                                </li>
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Desktop <span
                                                            class="float-end">(6,256)</span></a>
                                                </li>
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Electronics <span
                                                            class="float-end">(3,479)</span></a>
                                                </li>
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Home & Furniture <span
                                                            class="float-end">(2,275)</span></a>
                                                </li>
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Grocery <span
                                                            class="float-end">(1,950)</span></a>
                                                </li>
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Fashion <span
                                                            class="float-end">(1,582)</span></a>
                                                </li>
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Appliances <span
                                                            class="float-end">(1,037)</span></a>
                                                </li>
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Beauty, Toys & More <span
                                                            class="float-end">(924)</span></a>
                                                </li>
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Food & Drinks <span
                                                            class="float-end">(701)</span></a>
                                                </li>
                                                <li class="py-1">
                                                    <a href="#" class="text-muted">Toys & Games <span
                                                            class="float-end">(239)</span></a>
                                                </li>
                                            </ol>
                                            <div class="mt-3 text-center">
                                                <a href="javascript:void(0);"
                                                    class="text-muted text-decoration-underline">View all Categories</a>
                                            </div>
                                        </div>
                                        <div class="p-3">
                                            <h6 class="text-muted mb-3 text-uppercase fw-semibold">Products Reviews</h6>
                                            <!-- Swiper -->
                                            <div class="swiper vertical-swiper" style="height: 250px;">
                                                <div class="swiper-wrapper">
                                                    <div class="swiper-slide">
                                                        <div class="card border border-dashed shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-sm">
                                                                        <div class="avatar-title bg-light rounded">
                                                                            <img src="assets/images/companies/img-1.png"
                                                                                alt="" height="30">
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <div>
                                                                            <p
                                                                                class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                                                " Great product and looks great, lots of
                                                                                features. "</p>
                                                                            <div
                                                                                class="fs-11 align-middle text-warning">
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-end mb-0 text-muted">
                                                                            - by <cite title="Source Title">Force
                                                                                Medicines</cite>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="swiper-slide">
                                                        <div class="card border border-dashed shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0">
                                                                        <img src="assets/images/users/avatar-3.jpg"
                                                                            alt="" class="avatar-sm rounded">
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <div>
                                                                            <p
                                                                                class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                                                " Amazing template, very easy to
                                                                                understand and manipulate. "</p>
                                                                            <div
                                                                                class="fs-11 align-middle text-warning">
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-half-fill"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-end mb-0 text-muted">
                                                                            - by <cite title="Source Title">Henry
                                                                                Baird</cite>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="swiper-slide">
                                                        <div class="card border border-dashed shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-sm">
                                                                        <div class="avatar-title bg-light rounded">
                                                                            <img src="assets/images/companies/img-8.png"
                                                                                alt="" height="30">
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <div>
                                                                            <p
                                                                                class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                                                "Very beautiful product and Very helpful
                                                                                customer service."</p>
                                                                            <div
                                                                                class="fs-11 align-middle text-warning">
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-line"></i>
                                                                                <i class="ri-star-line"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-end mb-0 text-muted">
                                                                            - by <cite title="Source Title">Zoetic
                                                                                Fashion</cite>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="swiper-slide">
                                                        <div class="card border border-dashed shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0">
                                                                        <img src="assets/images/users/avatar-2.jpg"
                                                                            alt="" class="avatar-sm rounded">
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-3">
                                                                        <div>
                                                                            <p
                                                                                class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                                                " The product is very beautiful. I like
                                                                                it. "</p>
                                                                            <div
                                                                                class="fs-11 align-middle text-warning">
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-fill"></i>
                                                                                <i class="ri-star-half-fill"></i>
                                                                                <i class="ri-star-line"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-end mb-0 text-muted">
                                                                            - by <cite title="Source Title">Nancy
                                                                                Martino</cite>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3">
                                            <h6 class="text-muted mb-3 text-uppercase fw-semibold">Customer Reviews</h6>
                                            <div class="bg-light px-3 py-2 rounded-2 mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <div class="fs-16 align-middle text-warning">
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-half-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <h6 class="mb-0">4.5 out of 5</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-muted">Total <span class="fw-medium">5.50k</span>
                                                    reviews</div>
                                            </div>

                                            <div class="mt-3">
                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0">5 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-1">
                                                            <div class="progress animated-progress progress-sm">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width: 50.16%" aria-valuenow="50.16"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0 text-muted">2758</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->

                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0">4 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-1">
                                                            <div class="progress animated-progress progress-sm">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width: 29.32%" aria-valuenow="29.32"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0 text-muted">1063</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->

                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0">3 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-1">
                                                            <div class="progress animated-progress progress-sm">
                                                                <div class="progress-bar bg-warning" role="progressbar"
                                                                    style="width: 18.12%" aria-valuenow="18.12"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0 text-muted">997</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->

                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0">2 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-1">
                                                            <div class="progress animated-progress progress-sm">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width: 4.98%" aria-valuenow="4.98"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0 text-muted">227</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end row -->

                                                <div class="row align-items-center g-2">
                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0">1 star</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="p-1">
                                                            <div class="progress animated-progress progress-sm">
                                                                <div class="progress-bar bg-danger" role="progressbar"
                                                                    style="width: 7.42%" aria-valuenow="7.42"
                                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="p-1">
                                                            <h6 class="mb-0 text-muted">408</h6>
                                                        </div>
                                                    </div>
                                                </div><!-- end row -->
                                            </div>
                                        </div>

                                        <div class="card sidebar-alert bg-light border-0 text-center mx-4 mb-0 mt-3">
                                            <div class="card-body">
                                                <img src="assets/images/giftbox.png" alt="">
                                                <div class="mt-4">
                                                    <h5>Invite New Seller</h5>
                                                    <p class="text-muted lh-base">Refer a new seller to us and earn $100
                                                        per refer.</p>
                                                    <button type="button"
                                                        class="btn btn-primary btn-label rounded-pill"><i
                                                            class="ri-mail-fill label-icon align-middle rounded-pill fs-16 me-2"></i>
                                                        Invite Now</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div> <!-- end card-->
                            </div> <!-- end .rightbar-->

                        </div> <!-- end col -->
                    </div>

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
        </div>
        <!-- End Page-content -->
        <?php include('include/footer.php'); ?>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->
    <!-- Theme Settings -->
    <?php include('include/script.php'); ?>
    <script src="js/highmaps.js"></script>
    <script src="js/exporting.js"></script>
    <script src="js/export-data.js"></script>
    <script src="js/accessibility.js"></script>
    <script src="js/highchartsv11.js"></script>
    <script src="js/datav11.js"></script>
    <script src="js/drilldownv11.js"></script>
    <script>
        $("#survey_name_id").on("input", function() {
            if ($("#survey_name_id").val() != '') {
                $('#btnsearch').prop('disabled', false);
            } else {
                $('#btnsearch').prop('disabled', true);
            }
        });
        $("#from_datepicker").on("click", function() {
            if ($("#from_datepicker").val() != '') {
                $('#btnsearch').prop('disabled', false);
            } else {
                $('#btnsearch').prop('disabled', false);
            }
        });
        $("#to_datepicker").on("click", function() {
            if ($("#to_datepicker").val() != '') {
                $('#btnsearch').prop('disabled', false);
            } else {
                $('#btnsearch').prop('disabled', false);
            }
        });
    </script>
    <script>
        stateMap('state-10');

        function stateMap(stateLGD) {
            $('#show_block').hide();
            var stateName = stateLGD;
            Highcharts.getJSON('https://unicef.indevconsultancy.in/mis//json/' + stateName + '.json', function(geojson) {
                Highcharts.mapChart('map', {
                    chart: {
                        // borderWidth: 1,
                        // borderColor: 'silver',
                        //borderRadius: 3,
                        height: '310px',
                        //shadow: true,
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
                            align: 'right',
                        }
                    },
                    colorAxis: {
                        min: 1,
                        max: 1000,
                        type: 'logarithmic',
                        stops: [
                            [0, '#ff5757'], // Less than 10 in 1000 Population
                            [0.5, '#fed05b'], // 10 to 50 in 1000 Population
                            [0.9, '#59d95b'], // Above 50 in 1000 Population
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
                                        // dtname = dtname.replace(/\ /g, '').toLowerCase();
                                        // alert(this.properties.Dist_LGD);

                                        $("#block-name").html(dtname);
                                        $('#show_block').show();
                                        districtMap(this.properties.Dist_LGD);
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
                        enabled: true,
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        borderWidth: 1,
                        borderColor: 'gray',
                        cursor: 'pointer',
                        data: [
                            ['196', 2312],
                            ['214', 1146],
                            ['0', 8],
                        ],
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
                        },
                    }]
                });
            });
        }

        function districtMap(districtLGD) {
            var districtName = districtLGD;
            Highcharts.getJSON('https://unicef.indevconsultancy.in/mis//json/BD/district-' + districtName + '.json', function(geojson) {
                Highcharts.mapChart('map', {
                    chart: {
                        // borderWidth: 0,
                        // borderColor: 'silver',
                        // borderRadius: 3,
                        height: '310px',
                        // shadow: false,
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
                            align: 'right',
                        }
                    },
                    colorAxis: {
                        min: 1,
                        max: 1000,
                        type: 'logarithmic',
                        stops: [
                            [0, '#59d95b'], // Less than 10 in 1000 Population
                            [0.5, '#fed05b'], // 10 to 50 in 1000 Population
                            [0.9, '#ff5757'], // Above 50 in 1000 Population
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
                                        var sdtname = this.properties.sdtname;
                                        sdtname = sdtname.replace(/\ /g, '').toLowerCase();
                                        // districtMap(dtname);
                                        //stateMap('pashchim_champaran');
                                    }
                                }
                            }
                        }
                    },
                    tooltip: {
                        pointFormatter: function() {
                            return '<b>' + this.properties.sdtname + '</b>: ' + this.value;
                        }
                    },
                    exporting: {
                        enabled: true,
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        borderWidth: 1,
                        borderColor: 'gray',
                        data: [
                            ['1499', 9],
                            ['1493', 12],
                            ['1500', 4],
                            ['1510', 17],
                            ['1489', 574],
                            ['1505', 21],
                            ['1504', 9],
                            ['1502', 6],
                            ['1508', 3],
                            ['1495', 105],
                            ['1497', 8],
                            ['1498', 9],
                            ['1501', 6],
                            ['1490', 200],
                            ['1487', 334],
                            ['1494', 141],
                            ['1509', 8],
                            ['1492', 1],
                            ['1491', 4],
                            ['1496', 15],
                            ['1503', 130],
                            ['1506', 15],
                            ['1488', 308],
                            ['1507', 54],
                            ['1138', 84],
                            ['1139', 92],
                            ['1140', 263],
                            ['1128', 4],
                            ['1129', 7],
                            ['1141', 138],
                            ['1132', 20],
                            ['1137', 23],
                            ['1135', 26],
                            ['1133', 38],
                            ['1134', 74],
                            ['1136', 298],
                            ['0', 1218],
                        ],

                        keys: ['Subdt_LGD', 'value'],
                        joinBy: 'Subdt_LGD',
                        name: 'Total survey',
                        states: {

                            hover: {
                                color: '#a4edba'
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{point.properties.sdtname}'
                        }

                    }]
                });
            });
        }
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

                    // Set the minimum date for the "to" datepicker
                    // $('#to_datepicker').datepicker('option', 'minDate', selectedDate);
                    $('#to_datepicker').datepicker('option', 'minDate', selectedDate);
                }
            });

            $('#to_datepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                maxDate: 0,
                onSelect: function(selectedDate) {
                    // Set the maximum date for the "from" datepicker
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
    <script>
        Highcharts.chart('form_status', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: '',
                align: 'left'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Total',
                colorByPoint: true,
                data: [{
                    name: 'Completed',
                    y: 60,
                    color: '#1cabe2'
                }, {
                    name: 'Ongoing',
                    y: 40,
                    color: '#e34e09'
                }, ]
            }]
        });
    </script>
    <script>
        Highcharts.chart('form_wise_datacollection', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                type: 'category',
                labels: {
                    //rotation: -12,
                    style: {
                        fontSize: '12px'

                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Form wise Data'
                }
            },
            legend: {
                enabled: true
            },
            tooltip: {
                pointFormat: '<span style="font-size:12px">Number of data collected: {point.y}</span>'
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Form wise data collection',
                data: [
                    ['VHSND Monitoring and Assessment Tools', 480],
                    ['AWC Monitoring and Home Visit-field collection', 1519],
                    ['Assessment of Community Based Platform-HBYC1', 97],
                    ['Assessment of Community Based Platform-HBNC 1', 103],
                    ['BENEFICIARY FORM', 1267], //['Age Default Response 2', 10],['AMB checkist', 1],['AWC monitoring', 15]
                ],
                dataLabels: {
                    enabled: true,
                    rotation: 0,
                    color: '#FFFFFF',
                    align: 'center',
                    format: '<span style="font-size:13px">{point.y}</span>', // one decimal
                    y: 5, // 10 pixels down from the top
                    style: {
                        fontSize: '15px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });
    </script>
    <script>
        Highcharts.chart('day_wise_line', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    '15-01-2025', '16-01-2025', '17-01-2025', '18-01-2025', '19-01-2025', '20-01-2025', '21-01-2025', '22-01-2025', '23-01-2025', '24-01-2025', '25-01-2025', '26-01-2025', '27-01-2025', '28-01-2025', '29-01-2025', '30-01-2025', '31-01-2025', '01-02-2025', '02-02-2025', '03-02-2025', '04-02-2025', '05-02-2025', '06-02-2025', '07-02-2025', '08-02-2025', '09-02-2025', '10-02-2025', '11-02-2025', '12-02-2025', '13-02-2025'
                ]
            },
            yAxis: {
                title: {
                    text: 'Total Data'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            series: [{
                    name: 'Day wise data collection',
                    data: [
                        48, 38, 48, 50, 8, 39, 39, 36, 41, 53, 32, 12, 38, 41, 43, 39, 41, 31, 13, 20, 32, 39, 38, 35, 36, 21, 29, 33, 26, 29
                    ],
                    dataLabels: {
                        enabled: true,
                        rotation: 0,
                        color: '#FFFFFF',
                        align: 'center',
                        format: '<span style="font-size:13px">{point.y}</span>', // one decimal
                        y: 5, // 10 pixels down from the top
                        style: {
                            fontSize: '15px',
                            fontFamily: 'Verdana, sans-serif'
                        }
                    }
                }


            ],
            credits: {
                enabled: false
            }
        });
    </script>
    <!--Day wise activity Chart code -->
    <script>
        $(document).ready(function() {

            $(".tgltab").click(function() {
                //console.log($(this).parent().parent());
                if ($(this).parent().parent().hasClass('active')) {
                    $(this).parent().parent().removeClass('active')
                } else {
                    $(this).parent().parent().addClass('active')
                }

                $(this).parent().parent().find('.accord-body').slideToggle(500);

            });

            $('.dash-body ul li.hasChild:not(.noAct)').each(function() {
                $(this).addClass('active');
                $(this).find('.accord-body').css('display', 'block');
            })

        });
    </script>
</body>


<!-- Mirrored from themesbrand.com/velzon/html/default/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 08 Sep 2023 17:55:31 GMT -->

</html>