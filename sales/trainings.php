<?php include('includes/head.php') ?>
<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Trainings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">List</a></li>
                                <li class="breadcrumb-item active">Trainings</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Trainings List</h4>
                        </div>
                        <div class="card-body">
                            <div class="listjs-table" id="customerList">
                                <div class="row g-4 mb-3">
                                    <div class="col-sm-auto">
                                        <div>
                                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Add</button>
                                            <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end">
                                            <div class="search-box ms-2">
                                                <input type="text" class="form-control search" placeholder="Search...">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" style="width: 50px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                                    </div>
                                                </th>
                                                <th class="sort" data-sort="customer_name">Trainer Name</th>
                                                <th class="sort" data-sort="date">Email ID</th>
                                                <th class="sort" data-sort="status">Organization</th>
                                                <th class="sort" data-sort="status">State</th>
                                                <th class="sort" data-sort="status">District</th>
                                                <th class="sort" data-sort="status">Status</th>
                                                <th class="sort" data-sort="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="chk_child" value="option1">
                                                    </div>
                                                </th>
                                                <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">#VZ2101</a></td>
                                                <td class="customer_name">Vivek Kumar Sharma</td>
                                                <td class="phone">vivek7763066634@gmail.com</td>
                                                <td class="date">Alternate Software</td>
                                                <td class="date">Bihar</td>
                                                <td class="date">Chhapra (Saran)</td>
                                                <td class="status"><span class="badge bg-success-subtle text-success text-uppercase">Active</span></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <div class="edit">
                                                            <button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                                        </div>
                                                        <div class="remove">
                                                            <button class="btn btn-sm btn-danger remove-item-btn" data-bs-toggle="modal" data-bs-target="#deleteRecordModal">Remove</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
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

                                <div class="d-flex justify-content-end">
                                    <div class="pagination-wrap hstack gap-2">
                                        <a class="page-item pagination-prev disabled" href="javascript:void(0);">
                                            Previous
                                        </a>
                                        <ul class="pagination listjs-pagination mb-0"></ul>
                                        <a class="page-item pagination-next" href="javascript:void(0);">
                                            Next
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-light p-3">
                            <h5 class="modal-title" id="">Add Training</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                        </div>
                        <form class="tablelist-form" autocomplete="off">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="user_name">Training Name <span class="text-danger">*</span></label>
                                        <input type="text" name="user_name" oninput="this.value = this.value.replace(/^\s+/, '')" id="user_name" class="form-control" placeholder="Enter Name" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Training">Training Type <span class="text-danger">*</span></label>
                                        <select name="Training" id="Training" class="form-select" required>
                                            <option value="">Training Type</option>
                                            <option value="1">Training Type 1</option>
                                            <option value="2">Training Type 2</option>
                                            <option value="3">Training Type 3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Training">Training Duration <span class="text-danger">*</span></label>
                                        <select name="Training" id="Training" class="form-select" required>
                                            <option value="">Training Duration</option>
                                            <option value="1">1212</option>
                                            <option value="2">2121</option>
                                            <option value="3">343434</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Agency">Training Agency <span class="text-danger">*</span></label>
                                        <select name="Agency" id="Agency" class="form-select" required>
                                            <option value="">Training Agency</option>
                                            <option value="1">School</option>
                                            <option value="2">Organization</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Trainer">Master Trainer <span class="text-danger">*</span></label>
                                        <select name="Trainer" id="Trainer" class="form-select" required>
                                            <option value="">Master Trainer</option>
                                            <option value="1">Vivek Kumar Sharma</option>
                                            <option value="2">Vivek Kumar Sharma</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Participant">Number of Participant <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Participant" name="Participant" placeholder="Enter Number of Participant" oninput="this.value = this.value.replace(/\s/g, '')" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,''),this.value.slice(0, this.maxLength) " maxlength="10">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="age">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="age">End Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" id="add-btn">Add Training</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-light p-3">
                            <h5 class="modal-title" id="">Edit Training</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                        </div>
                        <form class="tablelist-form" autocomplete="off">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="user_name">Training Name <span class="text-danger">*</span></label>
                                        <input type="text" name="user_name" oninput="this.value = this.value.replace(/^\s+/, '')" id="user_name" class="form-control" placeholder="Enter Name" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Training">Training Type <span class="text-danger">*</span></label>
                                        <select name="Training" id="Training" class="form-select" required>
                                            <option value="">Training Type</option>
                                            <option value="1">Training Type 1</option>
                                            <option value="2">Training Type 2</option>
                                            <option value="3">Training Type 3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Training">Training Duration <span class="text-danger">*</span></label>
                                        <select name="Training" id="Training" class="form-select" required>
                                            <option value="">Training Duration</option>
                                            <option value="1">1212</option>
                                            <option value="2">2121</option>
                                            <option value="3">343434</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Agency">Training Agency <span class="text-danger">*</span></label>
                                        <select name="Agency" id="Agency" class="form-select" required>
                                            <option value="">Training Agency</option>
                                            <option value="1">School</option>
                                            <option value="2">Organization</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Trainer">Master Trainer <span class="text-danger">*</span></label>
                                        <select name="Trainer" id="Trainer" class="form-select" required>
                                            <option value="">Master Trainer</option>
                                            <option value="1">Vivek Kumar Sharma</option>
                                            <option value="2">Vivek Kumar Sharma</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="Participant">Number of Participant <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="Participant" name="Participant" placeholder="Enter Number of Participant" oninput="this.value = this.value.replace(/\s/g, '')" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,''),this.value.slice(0, this.maxLength) " maxlength="10">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="age">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="age">End Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" id="add-btn">Add Training</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mt-2 text-center">
                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                                <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                    <h4>Are you Sure ?</h4>
                                    <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record ?</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn w-sm btn-danger " id="delete-record">Yes, Delete It!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-4 col-sm-auto">
                    <p class="mb-0">Technology Partner : <a href="https://alttechsoftware.com/" class="text-warning" target="_blank">Alternate Software for Future</a></p>
                </div>
                <div class="col-4 col-sm-auto text-center">
                    <p class="mb-0 ">© <script>
                            document.write(new Date().getFullYear())
                        </script> SKILL DEVELOPMENT CERTIFICATE PROGRAME<small></small></p>
                </div>
                <div class="col-4 col-sm-auto text-end">
                    <p class="mb-0 text-end">Terms & Conditions | Privacy Policy</p>
                </div>
            </div>
        </div>
    </footer>

</div>

<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="assets/js/plugins.js"></script>
<script src="assets/libs/prismjs/prism.js"></script>
<script src="assets/libs/list.js/list.min.js"></script>
<script src="assets/libs/list.pagination.js/list.pagination.min.js"></script>
<script src="assets/js/pages/listjs.init.js"></script>
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="assets/js/app.js"></script>