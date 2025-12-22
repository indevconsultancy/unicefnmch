<?php include_once('include/config.php'); ?>
<!doctype html>
<html lang="en" data-layout="horizontal" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="blue" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-body-image="none" data-sidebar-visibility="show">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | GP List</title>
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
                                <h4 class="mb-sm-0">GP List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                                        <li class="breadcrumb-item active">GP List</li>
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
                       <form>
                    <div class="row">
                        <div class="col-3">
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
						<div class="col-3">
						<select class="form-select" id="district" name="district" required="">
                            <option value="">-- Select Block --</option>
							                         </select>

                           
                        </div>
						<div class="col-4">
						<input class="form-control" type="text" name="gp-name" placeholder="Search GP Name ">

                           
                        </div>
						
                        <div class="col-2">
                            <button type="submit" class="btn btn-primary w-100" onclick="SearchData();">
                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Filters
                            </button>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </form> 
                       </div>
						<div class="card-body">
                                <table id="example" class="table table-striped" style="width:100%; text-align:left">
									<thead class="table-light">
										<tr>
											<th scope="col" style="text-align:left">Sr. No</th>
											<th scope="col" style="text-align:left">GP Name</th>
											<th scope="col" style="text-align:left">LGD Code</th>
											<th scope="col" style="text-align:left">Sector</th>
											<th scope="col" style="text-align:left">Block</th>
											<th scope="col" style="text-align:left">District</th>
											<th scope="col" style="text-align:left">CDPO WhatsApp</th>
											<th scope="col" style="text-align:left">LS WhatsApp</th>
										</tr>
									</thead>
									<tbody>
									<?php $i=1;
									$sqlgp=mysqli_query($conn,"select * from suposhit_gp_list");
									while($datagp=mysqli_fetch_object($sqlgp)) { ?>
										<tr>
											<td style="text-align:left"><?=$i?></td>
											<td><?=$datagp->gp?></td>
											<td style="text-align:left"><?=$datagp->gp_code?></td>
											<td style="text-align:left"><?=$datagp->sector_no?></td>
											<td><?=$datagp->block?></td>
											<td><?=$datagp->district?></td>
											<td style="text-align:left"><?=$datagp->cdpo_whatsapp?></td>
											<td style="text-align:left"><?=$datagp->ls_whatsapp?></td>
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