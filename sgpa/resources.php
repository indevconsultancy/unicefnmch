<?php include_once('include/config.php');
   $conns = new mysqli(hostname, username, password, database);
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $userId        = $_POST['user_id'];
    $documentName  = $_POST['document_name'];
    $documentType  = $_POST['document_type'];
    $description   = $_POST['description'];

    // Validate and process file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $allowedExtensions = array('pdf', 'doc', 'docx', 'jpg', 'png'); // define allowed extensions
        $fileName    = $_FILES['file']['name'];
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileSize    = $_FILES['file']['size'];

        // Get the file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Check if the file extension is allowed
        if (!in_array($fileExt, $allowedExtensions)) {
            die("Invalid file extension. Allowed types: " . implode(', ', $allowedExtensions));
        }

        // Define upload directory and ensure it exists
        $uploadDir = 'uploads/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique file name to avoid collisions
        $newFileName = uniqid() . '.' . $fileExt;
        $destPath = $uploadDir . $newFileName;

        // Move the file to the upload directory
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Prepare the SQL statement to insert data
            $stmt = $conns->prepare("INSERT INTO documents (user_id, document_name, document_type, description, file_path, file_extension, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("isssss", $userId, $documentName, $documentType, $description, $destPath, $fileExt);

            // Execute and check insertion
            if ($stmt->execute()) {
                echo "File uploaded and data saved successfully.";
            } else {
                echo "Database insertion failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error moving the uploaded file.";
        }
    } else {
        echo "No file uploaded or there was an error during the file upload.";
    }
}
 ?>
<!doctype html>
<html lang="en" data-layout="horizontal" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="blue" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default" data-body-image="none" data-sidebar-visibility="show">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | Resources List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('include/link.php'); ?>
</head>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
	<style>
	.modal-team-cover img, .profile-offcanvas .team-cover img, .team-box .team-cover img {
    height: 54px!important;
    width: 100%;
    -o-object-fit: cover;
    object-fit: cover;
}
	</style>
<body>
    <div id="layout-wrapper">
        <?php include('include/header.php'); ?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Resource List</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Resources</a></li>
                                        <li class="breadcrumb-item active">List</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="card">
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <div class="search-box">
                                        <input type="text" class="form-control" id="searchMemberList" placeholder="Search for documents name......">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-sm-auto ms-auto">
                                    <div class="list-grid-nav hstack gap-1">
                                        <button type="button" id="grid-view-button" class="btn btn-soft-info nav-link btn-icon fs-14 active filter-button"><i class="ri-grid-fill"></i></button>
                                        <button type="button" id="list-view-button" class="btn btn-soft-info nav-link  btn-icon fs-14 filter-button"><i class="ri-list-unordered"></i></button>
                                        <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-soft-info btn-icon fs-14"><i class="ri-more-2-fill"></i></button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                            <li><a class="dropdown-item" href="#">All</a></li>
                                            <li><a class="dropdown-item" href="#">Last Week</a></li>
                                            <li><a class="dropdown-item" href="#">Last Month</a></li>
                                            <li><a class="dropdown-item" href="#">Last Year</a></li>
                                        </ul>
                                        <button class="btn btn-success addMembers-modal" data-bs-toggle="modal" data-bs-target="#addmemberModal"><i class="ri-add-fill me-1 align-bottom"></i> Add Resource File</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div>

                                <div id="teamlist">
                                    <div class="team-list grid-view-filter row" id="team-member-list">
						<div class="row">
							<div class="col-sm-6 col-xl-3">
								<!-- Simple card -->
								<div class="card">
									<img class="card-img-top img-fluid" src="assets/images/small/img-1.jpg" alt="Card image cap">
									<div class="card-body">
										<h4 class="card-title mb-2">Web Developer</h4>
										<p class="card-text">At missed advice my it no sister. Miss told ham dull knew see she spot near can. Spirit her entire her called.</p>
										<div class="text-end">
											<a href="javascript:void(0);" class="btn btn-primary">Submit</a>
										</div>
									</div>
								</div><!-- end card -->
							</div><!-- end col -->
							<div class="col-sm-6 col-xl-3">
								<!-- Simple card -->
								<div class="card">
									<img class="card-img-top img-fluid" src="assets/images/small/img-1.jpg" alt="Card image cap">
									<div class="card-body">
										<h4 class="card-title mb-2">Web Developer</h4>
										<p class="card-text">At missed advice my it no sister. Miss told ham dull knew see she spot near can. Spirit her entire her called.</p>
										<div class="text-end">
											<a href="javascript:void(0);" class="btn btn-primary">Submit</a>
										</div>
									</div>
								</div><!-- end card -->
							</div><!-- end col -->
							<div class="col-sm-6 col-xl-3">
								<!-- Simple card -->
								<div class="card">
									<img class="card-img-top img-fluid" src="assets/images/small/img-1.jpg" alt="Card image cap">
									<div class="card-body">
										<h4 class="card-title mb-2">Web Developer</h4>
										<p class="card-text">At missed advice my it no sister. Miss told ham dull knew see she spot near can. Spirit her entire her called.</p>
										<div class="text-end">
											<a href="javascript:void(0);" class="btn btn-primary">Submit</a>
										</div>
									</div>
								</div><!-- end card -->
							</div><!-- end col -->
							<div class="col-sm-6 col-xl-3">
								<!-- Simple card -->
								<div class="card">
									<img class="card-img-top img-fluid" src="assets/images/small/img-1.jpg" alt="Card image cap">
									<div class="card-body">
										<h4 class="card-title mb-2">Web Developer</h4>
										<p class="card-text">At missed advice my it no sister. Miss told ham dull knew see she spot near can. Spirit her entire her called.</p>
										<div class="text-end">
											<a href="javascript:void(0);" class="btn btn-primary">Submit</a>
										</div>
									</div>
								</div><!-- end card -->
							</div><!-- end col -->
							
						</div>                                    
                                    </div>
                                   
                                </div>
                                <div class="py-4 mt-4 text-center" id="noresult" style="display: none;">
                                   
                                    <h5 class="mt-4">Sorry! No Result Found</h5>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="addmemberModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0">
                                            
                                            <div class="modal-body">
                                                <form action="" method="post" enctype="multipart/form-data" novalidate>
														<div class="row">
                                                        <div class="col-lg-12">
                                                           
                                                            <div class="px-1 pt-1">
                                                                <div class="modal-team-cover position-relative mb-0 mt-n4 mx-n4 rounded-top overflow-hidden">
                                                                    <img src="assets/images/small/img-9.jpg" alt="" id="cover-img" class="img-fluid">
    
                                                                    <div class="d-flex position-absolute start-0 end-0 top-0 p-3">
                                                                        <div class="flex-grow-1">
                                                                            <h5 class="modal-title text-white" id="createMemberLabel">Add New Resources</h5>
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            	<!-- Document Title -->
																<div class="mb-3">
																  <label for="documentTitle" class="form-label">Document Title</label>
																  <input type="text" name="document_title" class="form-control" id="documentTitle" placeholder="Enter document title" required>
																  <div class="invalid-feedback">Please enter a Document Title.</div>
																</div>

																<!-- Document Name -->
																<div class="mb-3">
																  <label for="documentName" class="form-label">Document Name</label>
																  <input type="text" name="document_name" class="form-control" id="documentName" placeholder="Enter document name" required>
																  <div class="invalid-feedback">Please enter a Document Name.</div>
																</div>

																<!-- Document Type (Select Option) -->
																<div class="mb-3">
																  <label for="documentType" class="form-label">Document Type</label>
																  <select name="document_type" class="form-select" id="documentType" required>
																	<option value="">Select Document Type</option>
																	<option value="report">Report</option>
																	<option value="invoice">Invoice</option>
																	<option value="contract">Contract</option>
																	<!-- Add more options as needed -->
																  </select>
																  <div class="invalid-feedback">Please select a Document Type.</div>
																</div>

																<!-- Description -->
																<div class="mb-3">
																  <label for="description" class="form-label">Description</label>
																  <textarea name="description" class="form-control" id="description" rows="3" placeholder="Enter description"></textarea>
																</div>

																<!-- File Upload -->
																<div class="mb-3">
																  <label for="fileUpload" class="form-label">Upload File</label>
																  <input type="file" name="file" class="form-control" id="fileUpload" required>
																  <div class="invalid-feedback">Please upload a file.</div>
																</div>	
                                                            <input type="hidden" id="project-input" class="form-control" name="user_id" value="0">
                                                            
                                                            <div class="hstack gap-2 justify-content-end">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-success" id="addNewMember">Add Document</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!--end modal-content-->
                                    </div>
                                    <!--end modal-dialog-->
                                </div>
                                <!--end modal-->

                                <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="member-overview">
                                    <!--end offcanvas-header-->
                                    <div class="offcanvas-body profile-offcanvas p-0">
                                        <div class="team-cover">
                                            <img src="assets/images/small/img-9.jpg" alt="" class="img-fluid" />
                                        </div>
                                        <div class="p-3">
                                            <div class="team-settings">
                                                <div class="row">
                                                    <div class="col">
                                                        <button type="button" class="btn btn-light btn-icon rounded-circle btn-sm favourite-btn "> <i class="ri-star-fill fs-14"></i> </button>
                                                    </div>
                                                    <div class="col text-end dropdown">
                                                        <a href="javascript:void(0);" id="dropdownMenuLink14" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill fs-17"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink14">
                                                            <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-star-line me-2 align-middle"></i>Favorites</a></li>
                                                            <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <div class="p-3 text-center">
                                            <img src="assets/images/users/avatar-2.jpg" alt="" class="avatar-lg img-thumbnail rounded-circle mx-auto profile-img">
                                            <div class="mt-3">
                                                <h5 class="fs-15 profile-name">Nancy Martino</h5>
                                                <p class="text-muted profile-designation">Team Leader & HR</p>
                                            </div>
                                            <div class="hstack gap-2 justify-content-center mt-4">
                                                <div class="avatar-xs">
                                                    <a href="javascript:void(0);" class="avatar-title bg-secondary-subtle text-secondary rounded fs-16">
                                                        <i class="ri-facebook-fill"></i>
                                                    </a>
                                                </div>
                                                <div class="avatar-xs">
                                                    <a href="javascript:void(0);" class="avatar-title bg-success-subtle text-success rounded fs-16">
                                                        <i class="ri-slack-fill"></i>
                                                    </a>
                                                </div>
                                                <div class="avatar-xs">
                                                    <a href="javascript:void(0);" class="avatar-title bg-info-subtle text-info rounded fs-16">
                                                        <i class="ri-linkedin-fill"></i>
                                                    </a>
                                                </div>
                                                <div class="avatar-xs">
                                                    <a href="javascript:void(0);" class="avatar-title bg-danger-subtle text-danger rounded fs-16">
                                                        <i class="ri-dribbble-fill"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-0 text-center">
                                            <div class="col-6">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1 profile-project">124</h5>
                                                    <p class="text-muted mb-0">Projects</p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-6">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1 profile-task">81</h5>
                                                    <p class="text-muted mb-0">Tasks</p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                        <div class="p-3">
                                            <h5 class="fs-15 mb-3">Personal Details</h5>
                                            <div class="mb-3">
                                                <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Number</p>
                                                <h6>+(256) 2451 8974</h6>
                                            </div>
                                            <div class="mb-3">
                                                <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Email</p>
                                                <h6>nancymartino@email.com</h6>
                                            </div>
                                            <div>
                                                <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Location</p>
                                                <h6 class="mb-0">Carson City - USA</h6>
                                            </div>
                                        </div>
                                        <div class="p-3 border-top">
                                            <h5 class="fs-15 mb-4">File Manager</h5>
                                            <div class="d-flex mb-3">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-danger-subtle text-danger rounded fs-16">
                                                        <i class="ri-image-2-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1"><a href="javascript:void(0);">Images</a></h6>
                                                    <p class="text-muted mb-0">4469 Files</p>
                                                </div>
                                                <div class="text-muted">
                                                    12 GB
                                                </div>
                                            </div>
                                            <div class="d-flex mb-3">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-secondary-subtle text-secondary rounded fs-16">
                                                        <i class="ri-file-zip-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1"><a href="javascript:void(0);">Documents</a></h6>
                                                    <p class="text-muted mb-0">46 Files</p>
                                                </div>
                                                <div class="text-muted">
                                                    3.46 GB
                                                </div>
                                            </div>
                                            <div class="d-flex mb-3">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-success-subtle text-success rounded fs-16">
                                                        <i class="ri-live-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1"><a href="javascript:void(0);">Media</a></h6>
                                                    <p class="text-muted mb-0">124 Files</p>
                                                </div>
                                                <div class="text-muted">
                                                    4.3 GB
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 avatar-xs">
                                                    <div class="avatar-title bg-primary-subtle text-primary rounded fs-16">
                                                        <i class="ri-error-warning-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1"><a href="javascript:void(0);">Others</a></h6>
                                                    <p class="text-muted mb-0">18 Files</p>
                                                </div>
                                                <div class="text-muted">
                                                    846 MB
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end offcanvas-body-->
                                    <div class="offcanvas-foorter border p-3 hstack gap-3 text-center position-relative">
                                        <button class="btn btn-light w-100"><i class="ri-question-answer-fill align-bottom ms-1"></i> Send Message</button>
                                        <a href="pages-profile.html" class="btn btn-primary w-100"><i class="ri-user-3-fill align-bottom ms-1"></i> View Profile</a>
                                    </div>
                                </div>
                                <!--end offcanvas-->
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!--end row-->
                </div><!-- container-fluid -->
            </div><!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Velzon.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Themesbrand
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- removeFileItemModal -->
    <div id="removeMemberModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-removeMemberModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this member ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-danger" id="remove-item">Yes, Delete It!</button>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!--end delete modal -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="customizer-setting d-none d-md-block">
        <div class="btn-info rounded-pill shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
            <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
        </div>
    </div>

    <!-- Theme Settings -->
    <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex align-items-center bg-primary bg-gradient p-3 offcanvas-header">
            <h5 class="m-0 me-2 text-white">Theme Customizer</h5>

            <button type="button" class="btn-close btn-close-white ms-auto" id="customizerclose-btn" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
       <?php include('include/footer.php'); ?>
	   </div>
<?php include('include/script.php'); ?>
    
    <!-- team init js -->
    <script src="assets/js/pages/team.init.js"></script>
    <script>
		(function () {
		  'use strict'
		  const forms = document.querySelectorAll('form')
		  Array.prototype.slice.call(forms)
			.forEach(function (form) {
			  form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
				  event.preventDefault()
				  event.stopPropagation()
				}
				form.classList.add('was-validated')
			  }, false)
			})
		})()
	</script>
  
</body>
</html>