<?php include_once('includes/config.php'); ?>
<?php define("title", "Update User | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php

if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
  $uid = mysqli_real_escape_string($conn, $_REQUEST['id']);
  $getUser = mysqli_query($conn, "SELECT user_id,name,mobile,role_id,email,username FROM users WHERE user_id='" . $uid . "'");
  $user = mysqli_fetch_array($getUser);
}
$error = '';
if (isset($_POST['update_user'])) {
  $page = $_GET['page'];
  $uid = mysqli_real_escape_string($conn, $_REQUEST['id']);
  $client_id = mysqli_real_escape_string($conn, $_POST['client_id']);
  $name=sanitizeInput($_POST['name'], $conn);
  $mobile =  sanitizeInput($_POST['mobile'],$conn);
  $email = sanitizeInput($_POST['email'],$conn);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$_SESSION['status_error'] = "Invalid email ID";
		$_SESSION['status_error_code'] = "error";
	}
  //$username = $_REQUEST['username'];
  $role_id = $_POST['role_id'];
  $count = count($role_id);

  if ($count < 1) {
    $error = 'Functional role are required';
  } else {
    $userAccess = implode(",", $role_id);
    $insertUser = mysqli_query($conn, "UPDATE users SET name='" . $name . "', mobile='" . $mobile . "',email='" . $email . "', role_id='3' where user_id='" .  $uid . "' ");
    $insertFunctional = mysqli_query($conn, "DELETE from functional_role where user_id='" . $uid . "' ");
    $insertFunctional = mysqli_query($conn, "DELETE from menu_role where user_id='" .  $uid . "' ");

    if ($insertUser) {
      $insertFunctional = '';
      foreach ($role_id as $roleid) {
        $insertFunctional = "INSERT INTO functional_role SET user_id='" . $_REQUEST['id'] . "', role_id='" . $roleid . "' ";
        mysqli_query($conn, $insertFunctional);
      }

      $menuSql = mysqli_query($conn, "INSERT INTO menu_role(menu_id,role_id,user_id,status) SELECT  menu_id,'3','" . $uid . "','1' FROM menu_role WHERE role_id='1' ");
      $getmenus = mysqli_query($conn, "SELECT GROUP_CONCAT(menu_id) AS menu_id FROM `functional_control` WHERE role_id in($userAccess) AND status='0'");
      $menus = mysqli_fetch_object($getmenus);
      $allMenus = $menus->menu_id;

      $menuSql = mysqli_query($conn, "UPDATE menu_role SET status='0' WHERE user_id='" .  $uid . "' and menu_id IN($allMenus) ");
      if ($menuSql != '') {
        $_SESSION['status'] = "User has been updated successfully";
        $_SESSION['status_code'] = "success";
        echo "<script>window.location.href='user-list.php?id=$userId?&page=$page'</script>";
      } else {
        $_SESSION['status_error'] = "Something went wrong!!";
        $_SESSION['status_error_code'] = "warning";
      }
    } else {
      $_SESSION['status_error'] = "User has been not updated !!";
      $_SESSION['status_error_code'] = "error";
    }
  }
}
?>
<?php

if (isset($_REQUEST['change_password'])) {
  $page = $_GET['page'];
  $userId = mysqli_real_escape_string($conn, $_GET['id']);
 // $oldpass = mysqli_real_escape_string($conn, sanitize_input($_POST['oldpass']));
  $oldpass=sanitizeInput($_POST['oldpass'],$conn);
  $oldpassword = password_hash($oldpass, PASSWORD_DEFAULT);
  
  $newpass=sanitizeInput($_POST['newpassword'],$conn);
  $newpassword = password_hash($newpass, PASSWORD_DEFAULT);
  
  $confirmpass=sanitizeInput($_POST['conformpassword'],$conn);
  $conformpassword = password_hash($confirmpass, PASSWORD_DEFAULT);

  $queryselpass = mysqli_query($conn, "select user_id,password from users where user_id='" . $userId . "' ");
  $getpass = mysqli_fetch_array($queryselpass);
	
  $old_Pass = $getpass['password'];

  if (password_verify($oldpass, $old_Pass)) {
    if ($newpass == $confirmpass) {
      $updatepassqry = mysqli_query($conn, "update users set password='" . $newpassword . "',orignal_password='" . $newpass . "' where user_id='" . $userId . "'");
      $_SESSION['status'] = "Updated password Successfully";
      $_SESSION['status_code'] = "success";
      echo "<script>window.location.href='user-list.php?id=$userId&page=$page'</script>";
    } else {
      $_SESSION['status_error'] = "New and confirm Password is not match !!";
      $_SESSION['status_error_code'] = "warning";
    }
  } else {
    $_SESSION['status_error'] = "Old Password Do Not Match !!";
    $_SESSION['status_error_code'] = "warning";
  }
}
?>

<style>
  .nav-tabs .nav-item.show .nav-link,
  .nav-tabs .nav-link.active {
    color: var(--bs-nav-tabs-link-active-color);
    background-color: #8ed2c3;
    height: 100%;
  }

  .panel-heading .nav>li>a {
    color: #fff !important;
  }

  .panel-heading .nav>li.active>a,
  .panel-heading .nav>li>a:hover {
    color: #ffffff;
    background: #8ed2c3;
    height: 100%;
  }
</style>

<!--main content start-->
<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="col-lg-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="icon_documents_alt"></i> User Management</li>
            <li class="breadcrumb-item"><i class="fa fa-list"></i>User List</li>
            <li class="breadcrumb-item" aria-current="page"><i class="fa fa-plus"></i>Update User</li>
          </ol>
        </nav>
      </div>
    </div>
    <!-- page start-->
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading tab-bg-info p-0">
            <ul class="nav nav-tabs m-0">
              <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#update-profile">
                  Update Profile</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#change-password""> 
                  Change Password</a>
              </li>
            </ul>
          </header>
          <div class=" tab-content border p-3">
                  <div id="update-profile" class="tab-pane active">
                    <form class="form-validate" method="post" enctype="multipart/form-data">
                      <div class="mb-3 row">
                        <label for="functional-role" class="col-sm-2 col-form-label text-end">Functional Role: <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <?php if (isset($conn)) {
                            $getUserType = mysqli_query($conn, "SELECT DISTINCT(roles.name) as name, roles.id FROM `functional_role` INNER JOIN roles on functional_role.role_id=roles.id WHERE functional_role.user_id='" . $_GET['id'] . "'");
                            $rols = array();
                            while ($type = mysqli_fetch_array($getUserType)) {
                              $rols[] = $type['id'];
                            }
                          ?>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="role_id[]" value="5" <?php if (in_array(5, $rols)) {
                                                                                                            echo "checked";
                                                                                                          } ?>>
                              <label class="form-check-label">Form Builder</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="role_id[]" value="6" <?php if (in_array(6, $rols)) {
                                                                                                            echo "checked";
                                                                                                          } ?>>
                              <label class="form-check-label">Form Manager</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="role_id[]" value="8" <?php if (in_array(8, $rols)) {
                                                                                                            echo "checked";
                                                                                                          } ?>>
                              <label class="form-check-label">Data Reviewer</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="role_id[]" value="9" <?php if (in_array(9, $rols)) {
                                                                                                            echo "checked";
                                                                                                          } ?>>
                              <label class="form-check-label">Data Collector</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="role_id[]" value="10" <?php if (in_array(10, $rols)) {
                                                                                                            echo "checked";
                                                                                                          } ?>>
                              <label class="form-check-label">Repository</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="role_id[]" value="12" <?php if (in_array(12, $rols)) {
                                                                                                            echo "checked";
                                                                                                          } ?>>
                              <label class="form-check-label">Data Export Identify</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="role_id[]" value="7" <?php if (in_array(7, $rols)) {
                                                                                                            echo "checked";
                                                                                                          } ?>>
                              <label class="form-check-label">Data Export Deidentify</label>
                            </div>
                          <?php } ?>
                        </div>
                      </div>

                      <hr>

                      <div class="mb-3 row">
                        <label for="name" class="col-sm-2 col-form-label text-end">Full Name: <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input class="form-control" id="form_fname" name="name" type="text" required value="<?= htmlspecialchars($user['name']); ?>" required />
						<span class="error_form" id="fname_error_message" style="color:red;"></span>
						</div>
                      </div>

                      <hr>

                      <div class="mb-3 row">
                        <label for="mobile" class="col-sm-2 col-form-label text-end">Mobile:</label>
                        <div class="col-sm-2 pe-0">
                          <select class="form-select" name="country_id">
                            <option value="">Country Code</option>
                            <?php
                            $getcountryType = mysqli_query($conn, "SELECT country_id, country_codes, country_name, dialing_code FROM country_code WHERE status='1'");
                            while ($type = mysqli_fetch_array($getcountryType)) {
                            ?>
                              <option value="<?= $type['country_id'] ?>" <?php if ($type['country_id'] == $_REQUEST['country_id']) {
                                                                            echo "selected";
                                                                          } ?>><?= htmlspecialchars($type['country_name']) ?>(<?= htmlspecialchars($type['dialing_code']) ?>)</option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="col-sm-8">
                          <input class="form-control" id="number" value="<?= htmlspecialchars($user['mobile']); ?>" name="mobile" type="number" />
                        </div>
                      </div>

                      <hr>

                      <div class="mb-3 row">
                        <label for="email" class="col-sm-2 col-form-label text-end">Email: <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input class="form-control" name="email" type="email" required value="<?= htmlspecialchars($user['email']); ?>" />
                        </div>
                      </div>

                      <hr>

                      <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-2 text-end">
                          <button class="btn btn-primary" type="submit" id="update_register" name="update_user">Update</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div id="change-password" class="tab-pane">
                    <form class="form-horizontal" role="form" action="" method="POST">
                      <input type="hidden" class="form-control" id="name" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                      <div class="mb-3 row">
                        <label for="old_password" class="col-sm-2 col-form-label text-end">Old Password: <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="old_password" required name="oldpass" />
                        </div>
                      </div>

                      <div class="mb-3 row">
                        <label for="password" class="col-sm-2 col-form-label text-end">New Password: <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="password" required name="newpassword" />
                        </div>
                      </div>

                      <div class="mb-3 row">
                        <label for="confirm_password" class="col-sm-2 col-form-label text-end">Confirm Password: <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="password" class="form-control" id="confirm_password" required name="conformpassword" />
                          <div class="mt-2" id="CheckPasswordMatch"></div>
                        </div>
                      </div>

                      <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-2 text-end">
                          <button type="submit" class="btn btn-primary" id="checked_pass" name="change_password">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
          </div>

  </section>
  </div>
  </div>
  <!-- page end-->
</section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>
<?php if (isset($_SESSION['status_error']) && $_SESSION['status_error'] != '') { ?>
  <script>
    swal.fire({
      title: "<?php echo $_SESSION['status_error']; ?>",
      icon: "<?php echo $_SESSION['status_error_code']; ?>",
      confirmButtonColor: '#449A97',
      confirmButtonText: 'Ok'
    });
  </script>
<?php unset($_SESSION['status_error']);
}  ?>
<script>
  $(document).ready(function() {
		$("#confirm_password").on('keyup', function() {
		var newpassword = $("#password").val();
		var confirmPassword = $("#confirm_password").val();
		if (newpassword != confirmPassword)
			$("#CheckPasswordMatch").html("Password does not match !").css("color", "red");
		else
			//$("#checked_pass").attr("disabled", false);
			$("#CheckPasswordMatch").html("Password match !").css("color", "green");
	});
});
  $(function() {
    $("#fname_error_message").hide();
    var error_fname = false;

    $("#form_fname").focusout(function() {
        check_fname();
    });

    function check_fname() {
        var pattern = /^[a-zA-Z ]*$/;
        var fname = $("#form_fname").val().trim();
        
        if (pattern.test(fname) && fname !== '') {
            $("#fname_error_message").hide();
            $('#update_register').attr("disabled", false);
            error_fname = false;
        } else {
            $("#fname_error_message").html("Only alphabets and spaces are allowed, and the field cannot be empty");
            $("#fname_error_message").show();
            $('#update_register').attr("disabled", true);
            error_fname = true;
        }
    }
});
</script>