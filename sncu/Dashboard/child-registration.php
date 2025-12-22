<?php include('includes/config.php'); ?>
<?php include('includes/functions.php'); ?>
<?php include('includes/headers.php'); ?>

<!-- Search Code -->
<?php
$filter = '';
$extraParams = [];
if (isset($_REQUEST['search'])) {
  $reg_idd = $_GET['reg_idd'] ?? '';
  $mother = $_GET['mother'] ?? '';
  $monitor = $_GET['monitor'] ?? '';

  if (!empty($reg_idd)) {
    $filter .= " AND r.unique_id_of_body LIKE '%" . addslashes($reg_idd) . "%'";
    $extraParams['reg_idd'] = $reg_idd;
  }
  if (!empty($mother)) {
    $filter .= " AND r.boby_of_mothers_name LIKE '%" . addslashes($mother) . "%'";
    $extraParams['mother'] = $mother;
  }
  if (!empty($monitor)) {
    $filter .= " AND r.monitor_name LIKE '%" . addslashes($monitor) . "%'";
    $extraParams['monitor'] = $monitor;
  }
  $extraParams['search'] = $_GET['search'];
}
?>

<?php
$limit = 10;
// Get current pages
$child_page = isset($_GET['child_page']) ? (int)$_GET['child_page'] : 1;
$childOffset = ($child_page - 1) * $limit;

$childQuery = "SELECT * FROM registration_form LIMIT $limit OFFSET $childOffset";
$childResult = mysqli_query($conn, $childQuery);

$total_child = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM registration_form AS r WHERE r.status $filter"))[0];
$totalchildPages = ceil($total_child / $limit);
?>

<?php
// Insert Registration
if (isset($_POST['ins_reg'])) {

  $reg_year = $_POST['reg_year'];
  $dist_id = $_POST['dist_name'];
  $sncu_name = $_POST['sncu_name'];
  $sncu_id = $_POST['real_sncuid'];
  $user_id = $_POST['mon_name'];
  $mtr_namee = $_POST['mtr_namee'];
  $monitor_insti = $_POST['monitor_insti'];
  $serialno = $_POST['serialno'];
  $unq_id = $_POST['unq_id'];
  $baby_name = $_POST['baby_name'];
  $mdr_name = $_POST['mdr_name'];
  $father_name = $_POST['fdr_name'];
  $phone_number = $_POST['phone_number'];
  $dob = $_POST['b_dob'];
  $gender = $_POST['gender'];
  $delivery_type = $_POST['dlry_type'];
  $birth_waight = $_POST['baby_wt'];
  $lbw_val = $_POST['lbw_val'];
  $immu_status = $_POST['immu_status'];
  $vrfi_mean = ($immu_status == 'Up to date') ? $_POST['vrfi_mean'] : '';
  $mdr_age = $_POST['mdr_age'];
  $mdr_wt = $_POST['mdr_wt'];
  $age_marrige = $_POST['age_marrige'];
  $admt_res = $_POST['admt_res'];
  $admt_res_data = implode(',', $admt_res);
  $oth_res = (in_array('Others', $admt_res)) ? $_POST['oth_res'] : '';
  $gest_age = $_POST['gest_age'];

  // check user exist or not
  $isexist = mysqli_query($conn, "SELECT * FROM registration_form WHERE sncu_registration_serial_no='$serialno' AND user_id='$user_id'");
  if ($isexist) {
    if (mysqli_num_rows($isexist) > 0) {
      echo "<script>
                alert('SNCU Registration Serial No already exists!');
                window.location.href = 'child-registration.php';
            </script>";
      exit;
    }
  } else {
    echo "<script>
              alert('Something went wrong!');
              window.location.href = 'child-registration.php';
          </script>";
    exit;
  }

  $reg_query = "INSERT INTO registration_form(registration_year, registration_code, user_id, district_id, sncu_id, monitor_name, monitor_institution, sncu_registration_serial_no, unique_id_of_body, boby_name_optional, boby_of_mothers_name, fathers_name, phone_number_of_mother_father_caregivers, baby_date_of_birth, sex, delivery_type, birth_weight_kg, was_baby_lbw_at_time_of_birth_kg,immunization_status, means_for_verification_immunization, mothers_age_years,mother_weight_kg, age_at_marrage_years, reason_for_admission,mention_other_reason, gestational_age_LBW) VALUES ('$reg_year','2','$user_id','$dist_id','$sncu_id','$mtr_namee','$monitor_insti','$serialno','$unq_id','$baby_name','$mdr_name','$father_name','$phone_number','$dob','$gender','$delivery_type','$birth_waight','$lbw_val','$immu_status','$vrfi_mean','$mdr_age','$mdr_wt','$age_marrige','$admt_res_data','$oth_res','$gest_age')";

  $child_ins = mysqli_query($conn, $reg_query);

  if ($child_ins) {
    echo "<script>alert('Record Inserted successfully.');</script>";
    header('Location: child-registration.php');
  } else {
    echo "Error updating record: " . mysqli_error($conn);
  }
}
// Update Registration
if (isset($_POST['upd_reg'])) {
  $child_id = $_POST['child_id'];

  $monitor_inst = $_POST['monitor_institution'];
  $baby_name = $_POST['baby_name'];
  $monitor_name = $_POST['monitor_name'];
  $father_name = $_POST['father_name'];
  $phone_number = $_POST['phone_number'];
  $dob = $_POST['dob'];
  $gender = $_POST['gender'];
  $delivery_type = $_POST['delivery_type'];
  $gest = $_POST['gest'];
  $imu_status = $_POST['immunization_status'];
  $updvrfi_mean = ($imu_status == 'Up to date') ? $_POST['updvrfi_mean'] : '';
  $birth_waight = $_POST['birth_waight'];
  $updlbw_val = $_POST['updlbw_val'];
  $mother_age = $_POST['mother_age'];
  $mother_weight = $_POST['mother_weight'];
  $marrage_weight = $_POST['marrage_weight'];
  $admit_reason = $_POST['admit_reason'];
  $check_other = $_POST['checkother'];
  $admit_res_data = implode(',', $admit_reason);
  $other_reason = ($check_other != '') ? '' : $_POST['other_reason'];

  $update_query = "UPDATE registration_form SET monitor_institution = '$monitor_inst', boby_name_optional = '$baby_name',
                     boby_of_mothers_name = '$monitor_name', fathers_name = '$father_name', phone_number_of_mother_father_caregivers = '$phone_number',
                     baby_date_of_birth = '$dob', sex = '$gender', delivery_type = '$delivery_type', gestational_age_LBW = '$gest', birth_weight_kg = '$birth_waight',
                     was_baby_lbw_at_time_of_birth_kg = '$updlbw_val', mothers_age_years = '$mother_age', mother_weight_kg = '$mother_weight', age_at_marrage_years = '$marrage_weight',
                     reason_for_admission = '$admit_res_data',mention_other_reason = '$other_reason', immunization_status= '$imu_status',means_for_verification_immunization='$updvrfi_mean' WHERE id = '$child_id'";
  $child_res = mysqli_query($conn, $update_query);

  if ($child_res) {
    echo "<script>alert('Record updated successfully.');</script>";
    header('Location: child-registration.php');
  } else {
    echo "Error updating record: " . mysqli_error($conn);
  }
}
?>


<div class="main-content">

  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Registered Child</h4>

            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Listing</a></li>
                <li class="breadcrumb-item active">Child List</li>
              </ol>
            </div>

          </div>
        </div>
      </div>
      <!-- end page title -->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title mb-0">Child List</h4>
            </div><!-- end card header -->

            <div class="card-body">
              <div>
                <div class="row g-4 mb-3">
                  <form method="GET" class="d-flex flex-wrap align-items-end gap-2 mb-3">
                    <div>
                      <h5 class="mb-0">Total Records: <span><?php echo $total_child ?></span></h5>
                    </div>

                    <div style="flex: 1 1 200px;">
                      <input type="text" name="reg_idd" value="<?= htmlspecialchars($_GET['reg_idd'] ?? '') ?>" class="form-control" placeholder="Registration ID…">
                    </div>

                    <div style="flex: 1 1 200px;">
                      <input type="text" name="mother" value="<?= htmlspecialchars($_GET['mother'] ?? '') ?>" class="form-control" placeholder="Mother…">
                    </div>

                    <div style="flex: 1 1 200px;">
                      <input type="text" name="monitor" value="<?= htmlspecialchars($_GET['monitor'] ?? '') ?>" class="form-control" placeholder="Monitor…">
                    </div>

                    <div class="d-flex gap-2" style="flex-shrink: 0;">
                      <button type="submit" name="search" class="btn btn-secondary">Search</button>
                      <button type="reset" name="reset" onclick="resetSearch()" class="btn btn-danger">Reset</button>
                    </div>
                  </form>
                </div>

                <div class="table-responsive table-card mt-3 mb-1">
                  <table class="table align-middle table-nowrap" id="customerTable">
                    <thead class="table-light">
                      <tr>
                        <!-- <th scope="col" style="width: 50px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                                            </div>
                                                        </th> -->
                        <th data-sort="customer_name">Reg Id</th>
                        <th data-sort="customer_name">SNCU Name</th>
                        <th data-sort="customer_name">Mother Name</th>
                        <th data-sort="whatsapp_no">DOB</th>
                        <th data-sort="email">Delivery Type</th>
                        <th data-sort="email">Birth Weight(kg)</th>
                        <th data-sort="email">Admit Reason</th>
                        <th data-sort="whatsapp_no">Monitor By</th>
                        <th data-sort="whatsapp_no">Created At</th>

                        <th data-sort="action">Action</th>
                      </tr>
                    </thead>
                    <tbody class="list form-check-all">
                      <?php
                      $sqlfacilitator = mysqli_query($conn, "SELECT r.created_at,r.id, r.baby_date_of_birth, r.unique_id_of_body, r.sncu_id, r.boby_of_mothers_name, r.sex, r.delivery_type, r.birth_weight_kg, r.reason_for_admission, r.monitor_name, r.monitor_institution, r.status FROM registration_form AS r WHERE r.status $filter ORDER BY r.id DESC LIMIT $limit OFFSET $childOffset");
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
                          <td><?= $datafacilitator->unique_id_of_body ?></td>
                          <td><?= $sncuname1 ?></td>
                          <td><?= $datafacilitator->boby_of_mothers_name ?></td>
                          <td><?= $datafacilitator->baby_date_of_birth ?></td>
                          <td><?= $datafacilitator->delivery_type ?></td>
                          <td><?= $datafacilitator->birth_weight_kg ?></td>
                          <td><?= $datafacilitator->reason_for_admission ?></td>
                          <td><?= $datafacilitator->monitor_name ?></td>
                          <td><?= date('d-m-Y', strtotime($datafacilitator->created_at)) ?></td>
                          <!-- <td class="email"><#?=getone($conn, 'monitoring_data', 'date_of_admission', 'registration_id', $datafacilitator->id)?></td> -->

                          <td>
                            <div class="d-flex gap-2">
                              <div class="edit">
                                <button class="btn btn-sm btn-success edit-item-btn" data-bs-toggle="modal" data-bs-target="#editmodal" onclick="updatechild(<?= $datafacilitator->id ?>,'<?= $datafacilitator->unique_id_of_body ?>')">Edit</button>
                              </div>
                              <div class="remove">
                                <a href="child_profile.php?c_id=<?= $datafacilitator->id ?>" class="btn btn-sm btn-danger remove-item-btn">View</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                      <?php } ?>
                      <div class="text-end me-3 mb-1">
                        <button class="btn btn-primary btn-sm text-end" data-bs-toggle="modal" data-bs-target="#editmodal" onclick="addchild()">Add New Child</button>
                      </div>
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
  <div class="modal fade" id="editmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div id="regdata">

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            <button type="submit" id="regupdate" class="btn btn-success d-none" name="upd_reg" onclick="return upd_validate()">Update</button>
            <button type="submit" id="reginsert" class="btn btn-success d-none" name="ins_reg" onclick="return add_validate()">Add</button>
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

  <!-- Get Modal Data -->
  <script>
    ////////////////////////////// update registration //////////////////////////////////
    function updatechild(reg_id, reg_uniq_id) {
      $('#staticBackdropLabel').text('UPDATE CHILD REGISTRATION');
      $('#reginsert').addClass('d-none');
      $('#regupdate').removeClass('d-none');
      $.ajax({
        url: 'ajax/registration_data.php',
        type: 'POST',
        data: {
          reg_id: reg_id,
          reg_uniq_id: reg_uniq_id
        },
        beforeSend: function() {
          $('#regdata').html('<div class="loader-container"><div class="loader"></div></div>');
        },
        success: function(response) {
          $('#regdata').html(response);
        },
        error: function(response) {
          $('#regdata').html(response);
        }
      });
    }

    function oth_field(val) {
      var otherBox = document.getElementById('other_field');
      if (val.checked) {
        otherBox.classList.remove('d-none');
        document.getElementById("checkother").value = '';
      } else {
        otherBox.classList.add('d-none');
        document.getElementById("checkother").value = 'd-none';
      }
    }

    function mng_immu(immval) {
      if (immval == 'Up to date') {
        $('#means_vrfcn').removeClass('d-none');
      } else {
        $('#means_vrfcn').addClass('d-none');
      }
    }

    function calregwt(regwt) {
      if (regwt > 0 && regwt < 2.5) {
        $('#updlbw_val').val('LBW');
      }
      if (regwt >= 2.5) {
        $('#updlbw_val').val('Normal');
      }
      if (regwt == '') {
        $('#updlbw_val').val('');
      }
    }
    //////////////////////////////// add registration ///////////////////////////////////
    function addchild() {
      $('#staticBackdropLabel').text('ADD NEW CHILD');
      $('#regupdate').addClass('d-none');
      $('#reginsert').removeClass('d-none');
      $.ajax({
        url: 'ajax/registration_data.php',
        type: 'POST',
        data: {
          add_reg: 1,
        },
        beforeSend: function() {
          $('#regdata').html('<div class="loader-container"><div class="loader"></div></div>');
        },
        success: function(response) {
          $('#regdata').html(response);
        },
        error: function(response) {
          $('#regdata').html(response);
        }
      });
    }
    // unique id conditions script  
    function sel_disct(ss_id) {
      $.ajax({
        url: 'ajax/registration_data.php',
        type: 'POST',
        data: {
          ss_id: ss_id
        },

        success: function(response) {
          var jsondata = JSON.parse(response);
          $('#real_sncuid').val(jsondata.id);
          $('#sncu_name').val(jsondata.sncu_name);
          $('#unq_id').val(jsondata.sncu_code);
        },
        error: function(response) {
          $('#regdata').html(response);
        }
      });
    }

    function sel_monoter(mmmm) {
      var ss_idvalue = $(mmmm).val();
      var selectedName = $(mmmm).find('option:selected').data('name');
      $('#mtr_namee').val(selectedName);
    }

    function yearofreg(sso) {
      $('#serialno').val('');
      var getvalue = sso;
      var sncucode = $('#unq_id').val();
      var arr = sncucode.split("/");

      var createsunccode = `${arr[0]}/${arr[1]}/${arr[2]}`;
      var finalValue = `${createsunccode}/${getvalue}`;
      $('#unq_id').val(finalValue);
      getvalue = '';
    }

    function serialnofn(srnooo) {
      var getvalue = srnooo;
      var sncucode = $('#unq_id').val();
      var arr = sncucode.split("/");

      var createsunccode = `${arr[0]}/${arr[1]}/${arr[2]}/${arr[3]}`;
      var finalValue = `${createsunccode}/${getvalue}`;
      $('#unq_id').val(finalValue);
      getvalue = '';
    }

    function cal_lbw(callbwval) {
      if (callbwval > 0 && callbwval < 2.5) {
        $('#lbw_val').val('LBW');
      }
      if (callbwval >= 2.5) {
        $('#lbw_val').val('Normal');
      }
      if (callbwval == '') {
        $('#lbw_val').val('');
      }
    }

    // immunization Varification
    function imm_vfcn(immval) {
      if (immval == 'Up to date') {
        $('#means_vrfcn').removeClass('d-none');
      } else {
        $('#means_vrfcn').addClass('d-none');
      }
    }
  </script>

  <!-- Reset Button -->
  <script>
    function resetSearch() {
      window.location.href = window.location.pathname;
    }
  </script>

  <!-- Update Validation -->
  <script>
    function upd_validate() {
      let mn_ins = $('#mn_ins').val();
      let mdr_name = $('#mdr_name').val();
      let fdr_name = $('#fdr_name').val();
      let ph_no = $('#ph_no').val();
      let dob = $('#dob').val();
      let gen = $('#gen').val();
      let dlry_type = $('#dlry_type').val();
      let gest_age = $('#gest_age').val();
      let immu_status = $('#immu_status').val();
      let updvrfi_mean = $('#updvrfi_mean').val();
      let brth_wt = $('#updbrth_wt').val();
      let updlbw_val = $('#updlbw_val').val();
      let mdr_age = $('#updmdr_age').val();
      let mdr_wt = $('#updmdr_wt').val();
      let age_marrige = $('#updage_marrige').val();

      let mrage_age = parseInt(age_marrige);
      let mthr_age = parseInt(mdr_age);
      if (mrage_age > mthr_age) {
        alert("Mother Age at Marriage should be less then Mother Age");
        return false;
      }
      if (brth_wt > 10 || brth_wt < 0) {
        alert("Please Enter Valid Birth Weight");
        return false;
      }

      if (mn_ins === '') {
        alert("Monitor Institution is required.");
        $("#mn_ins").focus();
        return false;
      }
      if (mdr_name === '') {
        alert("Mother Name is required.");
        $("#mdr_name").focus();
        return false;
      }
      if (fdr_name === '') {
        alert("Father Name is required.");
        $("#fdr_name").focus();
        return false;
      }
      if (ph_no === '') {
        alert("Phone Numberis required.");
        $("#ph_no").focus();
        return false;
      }
      if (dob === '') {
        alert("Date of Birth is required.");
        $("#dob").focus();
        return false;
      }
      if (gen === '') {
        alert("Gender is required.");
        $("#gen").focus();
        return false;
      }
      if (dlry_type === '') {
        alert("Delivery Type is required.");
        $("#dlry_type").focus();
        return false;
      }
      if (gest_age === '') {
        alert("Gestational Age is required.");
        $("#gest_age").focus();
        return false;
      }
      if (immu_status === '') {
        alert("Immunization Status is required.");
        $("#immu_status").focus();
        return false;
      }
      if (immu_status === 'Up to date') {
        if (updvrfi_mean === '') {
          alert("Means for Varification is required.");
          $("#updvrfi_mean").focus();
          return false;
        }
      }
      if (brth_wt === '') {
        alert("Birth Waight is required.");
        $("#brth_wt").focus();
        return false;
      }
      if (updlbw_val === '') {
        alert("Was baby LBW is required.");
        $("#updlbw_val").focus();
        return false;
      }
      if (mdr_age === '') {
        alert("Mother Age is required.");
        $("#mdr_age").focus();
        return false;
      }
      if (mdr_wt === '') {
        alert("Mother Weight is required.");
        $("#mdr_wt").focus();
        return false;
      }
      if (age_marrige === '') {
        alert("Mother Age at Marrage is required.");
        $("#age_marrige").focus();
        return false;
      }
      const reasonbox = document.querySelectorAll('input[name="admit_reason[]"]');
      let cunChecked = false;
      reasonbox.forEach((checkbox) => {
        if (checkbox.checked) {
          cunChecked = true;
        }
      });
      if (!cunChecked) {
        alert("Please select at least one reason.");
        return false;
      }
      const otrreason = document.getElementById('otr3');
      const otherrn = document.getElementById('updoth_res');

      if (otrreason.checked && otherrn.value.trim() === '') {
        alert("Please specify 'Other' reason.");
        otherrn.focus();
        return false;
      }

    }
  </script>
  <!-- Add Child Validation -->
  <script>
    function add_validate() {
      const dist_name = document.getElementById('dist_name').value.trim();
      const sncu_name = document.getElementById('sncu_name').value.trim();
      const mon_name = document.getElementById('mon_name').value.trim();
      const mn_ins = document.getElementById('mn_ins').value.trim();
      const dateofyear = document.getElementById('dateofyear').value.trim();
      const serialno = document.getElementById('serialno').value.trim();
      const unq_id = document.getElementById('unq_id').value.trim();
      const mdr_name = document.getElementById('mdr_name').value.trim();
      const fdr_name = document.getElementById('fdr_name').value.trim();
      const ph_no = document.getElementById('ph_no').value.trim();
      const b_dob = document.getElementById('b_dob').value.trim();
      const gender = document.getElementById('gender').value.trim();
      const dlry_type = document.getElementById('dlry_type').value.trim();
      const bbwt = document.getElementById('bbwt').value.trim();
      const lbw_val = document.getElementById('lbw_val').value.trim();
      const gest_age = document.getElementById('gest_age').value.trim();
      const immu_status = document.getElementById('immu_status').value.trim();
      const vrfi_mean = document.getElementById('vrfi_mean').value.trim();
      const mdr_age = document.getElementById('mdr_age').value.trim();
      const mdr_wt = document.getElementById('mdr_wt').value.trim();
      const age_marrige = document.getElementById('age_marrige').value.trim();

      let mrage_agee = parseInt(age_marrige);
      let mthr_agee = parseInt(mdr_age);
      if (mrage_agee > mthr_agee) {
        alert("Mother Age at Marriage should be less then Mother Age");
        return false;
      }
      if (bbwt > 10 || bbwt < 0) {
        alert("Please Enter Valid Birth Weight");
        return false;
      }

      if (dist_name === '') {
        alert("District Name is required.");
        $("#dist_name").focus();
        return false;
      }
      if (sncu_name === '') {
        alert("SNCU Name is required.");
        $("#sncu_name").focus();
        return false;
      }
      if (mon_name === '') {
        alert("Monitor Name is required.");
        $("#mon_name").focus();
        return false;
      }
      if (mn_ins === '') {
        alert("Monitor Institution is required.");
        $("#mn_ins").focus();
        return false;
      }
      if (dateofyear === '') {
        alert("Year of Registration is required.");
        $("#dateofyear").focus();
        return false;
      }
      if (serialno === '') {
        alert("SNCU Registration Serial No is required.");
        $("#serialno").focus();
        return false;
      }
      if (unq_id === '') {
        alert("Unique ID of Baby is required.");
        $("#unq_id").focus();
        return false;
      }
      if (mdr_name === '') {
        alert("Baby Mother Name is required.");
        $("#mdr_name").focus();
        return false;
      }
      if (fdr_name === '') {
        alert("Father's Name is required.");
        $("#fdr_name").focus();
        return false;
      }
      if (ph_no === '') {
        alert("Phone Number is required.");
        $("#ph_no").focus();
        return false;
      }
      if (b_dob === '') {
        alert("Baby Date of Birth is required.");
        $("#b_dob").focus();
        return false;
      }
      if (gender === '') {
        alert("Gender is required.");
        $("#gender").focus();
        return false;
      }
      if (dlry_type === '') {
        alert("Delivery Type is required.");
        $("#dlry_type").focus();
        return false;
      }
      if (bbwt === '') {
        alert("Birth weight is required.");
        $("#bbwt").focus();
        return false;
      }
      if (lbw_val === '') {
        alert("Was baby LBW is required.");
        $("#lbw_val").focus();
        return false;
      }
      if (gest_age === '') {
        alert("Gestational Age is required.");
        $("#gest_age").focus();
        return false;
      }
      if (immu_status === '') {
        alert("Immunization Status is required.");
        $("#immu_status").focus();
        return false;
      }
      if (immu_status === 'Up to date') {
        if (vrfi_mean === '') {
          alert("Means for Varification is required.");
          $("#vrfi_mean").focus();
          return false;
        }
      }
      if (mdr_age === '') {
        alert("Mother Age is required.");
        $("#mdr_age").focus();
        return false;
      }
      if (mdr_wt === '') {
        alert("Mother Weight is required.");
        $("#mdr_wt").focus();
        return false;
      }
      if (age_marrige === '') {
        alert("Mother Age at Marriage is required.");
        $("#age_marrige").focus();
        return false;
      }
      const reasonbox = document.querySelectorAll('input[name="admt_res[]"]');
      let cunChecked = false;
      reasonbox.forEach((checkbox) => {
        if (checkbox.checked) {
          cunChecked = true;
        }
      });
      if (!cunChecked) {
        alert("Please select at least one reason.");
        return false;
      }
      const otrreason = document.getElementById('otrr3');
      const otherrn = document.getElementById('oth_res');

      if (otrreason.checked && otherrn.value.trim() === '') {
        alert("Please specify 'Other' reason.");
        otherrn.focus();
        return false;
      }
    }
  </script>