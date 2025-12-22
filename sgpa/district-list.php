<?php include_once('include/config.php'); ?>
<!doctype html>
<html lang="en" data-layout="horizontal" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="blue" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-body-image="none" data-sidebar-visibility="show">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | District List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('include/link.php'); ?>
</head>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
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
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">District List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                                        <li class="breadcrumb-item active">District List</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                    <!-- pest your content here start -->
                    <div class="row">
                       <!-- Table Head -->
					   <div class="card">
					   <div class="card-header align-items-center">
                       </div>
						<div class="card-body">
                                <table id="example" class="table table-striped" style="width:100%; text-align:left">
									<thead class="table-light">
										<tr>
											<th scope="col" style="text-align:left">Sr. No</th>
											<th scope="col" style="text-align:left">District</th>
											<th scope="col" style="text-align:left">LGD Code</th>
											<th scope="col" style="text-align:left">Total GP</th>
											<th scope="col" style="text-align:left">Total Block</th>
											<th scope="col" style="text-align:left">Total Anaganwadi</th>
										</tr>
									</thead>
									<tbody>
									<?php $i=1;
									$sqlgp=mysqli_query($conn,"select district,district_lgd,count(distinct(block)) as total_block,count(distinct(gp)) as total_gp,sum(total_awc) as total_awc from suposhit_gp_list group by district");
									while($datagp=mysqli_fetch_object($sqlgp)) { ?>
										<tr>
											<td style="text-align:left"><?=$i?></td>
											<td><?=$datagp->district?></td>
											<td style="text-align:left"><?=$datagp->district_lgd?></td>
											<td style="text-align:left"><?=$datagp->total_block?></td>
											<td style="text-align:left"><?=$datagp->total_gp?></td>
											<td style="text-align:left"><?=$datagp->total_awc?></td>
											
										</tr> 
									<?php $i++; } ?>										
										
									</tbody>
								</table>   
                        </div>
					</div>
                    </div>
                    <!-- pest your content here end -->
                </div>
				
            </div>
        </div>
        <!-- End Page-content -->
        <?php include('include/footer.php'); ?>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->
    <!-- Theme Settings -->
    <?php include('include/script.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!--datatable js-->

	    <!-- prismjs plugin -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

    <!-- gridjs js -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <!-- gridjs init -->
	<script>
	new DataTable('#example');
	</script>
</body>

</html>