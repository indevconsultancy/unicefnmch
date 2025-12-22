<?php include_once('includes/config.php'); ?>
<?php define("title", "Add User | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>

<?php
if (isset($_REQUEST['add_user'])) {

  $client_id = mysqli_real_escape_string($conn, $_POST['client_id']);
  // $name = ucwords(mysqli_real_escape_string($conn, trim(sanitizeInput($_POST['name']))));
  $name=sanitizeInput($_POST['name'], $conn);
  $namestring = str_replace(' ', '', $name);
  $country_id = mysqli_real_escape_string($conn, $_POST['country_id']);
  $mobileno =  sanitizeInput($_POST['mobile'],$conn);
  
  $mobile = $country_id . '' . $mobileno;
  //$email=sanitize_input($_POST['email'], $conn)
  $email = sanitizeInput($_POST['email'],$conn);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$_SESSION['status_error'] = "Invalid email ID";
		$_SESSION['status_error_code'] = "error";
	}
  $gtUser = mysqli_query($conn, "SELECT MAX(user_id) as uid FROM users");
  $userDetails = mysqli_fetch_object($gtUser);
  $uid = $userDetails->uid + 1;
  $usernames = uniqueId($namestring, $uid); //Create function in functions page
  $username = trim($usernames);
  $passvar = strtoupper(substr($namestring, 0, 2));
  $randnum = rand(10000, 1000000);
  $orignal_passw = "$passvar" . "$randnum";
  $orignal_password = trim($orignal_passw);

  $password = password_hash(trim($orignal_password), PASSWORD_DEFAULT);

  $role_id = $_REQUEST['role_id'];
  $count = count($role_id);

  if ($count < 1) {

    $_SESSION['status_error'] = "Functional role are required";
    $_SESSION['status_error_code'] = "error";
  } else {

    $userAccess = implode(",", $role_id);

    $insertsql = "INSERT INTO users (client_id,name,mobile,username,email,password,orignal_password,role_id,user_code,status,otp) values ('$client_id','$name','$mobile','$username','$email','$password','$orignal_password','3','$email','1','$otp')";

    $insertdata = mysqli_query($conn, $insertsql);
    if ($insertdata) {
      $lastuserid = mysqli_insert_id($conn);

      $insertFunctional = '';
      foreach ($role_id as $roleid) {
        $insertFunctional = "INSERT INTO functional_role SET user_id='" . $lastuserid . "', role_id='" . $roleid . "' ";
        mysqli_query($conn, $insertFunctional);
      }
      $menuSql = mysqli_query($conn, "INSERT INTO menu_role(menu_id,role_id,user_id,status) SELECT  menu_id,'3','" . $lastuserid . "','1' FROM menu_role WHERE role_id='1' ");

      $getmenus = mysqli_query($conn, "SELECT GROUP_CONCAT(menu_id) AS menu_id FROM `functional_control` WHERE role_id in($userAccess) AND status='0'");
      $menus = mysqli_fetch_object($getmenus);
      $allMenus = $menus->menu_id;

      $menuSql = mysqli_query($conn, "UPDATE menu_role SET status='0' WHERE user_id='" . $lastuserid . "' and menu_id IN($allMenus) ");
      if ($menuSql != '') {
        $_SESSION['status'] = "User has been added successfully";
        $_SESSION['status_code'] = "success";
        echo "<script>window.location.href='user-list.php'</script>";
      } else {
        $_SESSION['status_error'] = "Something went wrong!!";
        $_SESSION['status_error_code'] = "warning";
      }
    } else {
      $_SESSION['status_error'] = "User has been not added !!";
      $_SESSION['status_error_code'] = "error";
    }
  }
}
?>

<!--main content start-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/additional-methods.js"></script>

<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="col-lg-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="icon_documents_alt"></i>User Management</a></li>
            <li class="breadcrumb-item" aria-current="page"><i class="fa fa-plus"></i>Add User</li>
          </ol>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">Add User</header>
          <div class="panel-body">
            <form class="form-validate form-horizontal" id="myForm" method="post" enctype="multipart/form-data">
              <?php if ($_SESSION['role_id'] == "1") { ?>
                <div class="mb-3 row">
                  <label for="d" class="col-lg-2 col-form-label text-end">Client: <span class="text-danger">*</span></label>
                  <div class="col-lg-10">
                    <select class="form-select" name="client_id" id="d">
                      <option value="">Select Client</option>
                      <?php if (isset($conn)) {
                        $getClientType = mysqli_query($conn, "SELECT id,name FROM clients WHERE del_action='N'");
                        while ($type = mysqli_fetch_array($getClientType)) {
                      ?>
                          <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                </div>
              <?php } else { ?>
                <input class="form-control" name="client_id" type="hidden" value="<?= $_SESSION['client_id']; ?>" />
              <?php } ?>
              <div class="mb-3 row">
                <label for="role_id" class="col-lg-2 col-form-label text-end">Functional Role: <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                  <?php if (isset($conn)) {
                    $getUserType = mysqli_query($conn, "SELECT id, name FROM roles WHERE role_type='C' AND id!='" . $_SESSION['role_id'] . "' AND del_action='N' ORDER BY sequence ASC");
                    while ($type = mysqli_fetch_array($getUserType)) {
                  ?>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="role_id[]" value="<?= $type['id'] ?>" <?php if ($type['id'] == "9") echo "checked"; ?>>
                        <label class="form-check-label"><?= $type['name'] ?></label>
                      </div>
                  <?php }
                  } ?>
                </div>
              </div>
              <div class="mb-3 row">
                <label for="name" class="col-lg-2 col-form-label text-end">Full Name: <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                  <input class="form-control" id="form_fname" name="name" required type="text" pattern="[A-Za-z\s]+"/>
                  <span class="error_form" id="fname_error_message" style="color:red;"></span>
                </div>
              </div>
              <div class="mb-3 row">
                <label for="mobile" class="col-12 col-lg-2 col-form-label text-lg-end">Mobile</label>
                <div class="col-5 col-lg-2 pe-0">
                  <select class="form-select" name="country_id">
                    <option value="">Country Code</option>
                    <?php
                    $getcountryType = mysqli_query($conn, "SELECT country_id, country_codes, country_name, dialing_code FROM country_code WHERE status='1'");
                    while ($type = mysqli_fetch_array($getcountryType)) {
                      $dialing_code = $type['dialing_code'];
                      $dialingcode = str_replace("+", "", $dialing_code);
                    ?>
                      <option value="<?= $dialingcode; ?>"><?= $type['country_name'] ?> (<?= $type['dialing_code']; ?>)</option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-7 col-lg-8"">
                  <input class="form-control" id="number" name="mobile" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,''),this.value.slice(0, this.maxLength)" maxlength="18" />
                </div>
              </div>
              <div class="mb-3 row">
                <label for="email" class="col-lg-2 col-form-label text-end">Email: <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                  <input class="form-control" name="email" id="email" required type="email" />
                </div>
              </div>
              <div class="mb-3 row">
                <div class="col-lg-10 offset-lg-2 text-end">
                  <button class="btn btn-primary" type="submit" id="register" name="add_user">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </section>
      </div>
    </div>
    <!-- page end-->
  </section>
</section>

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
<script type="text/javascript">
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
            $('#register').attr("disabled", false);
            error_fname = false;
        } else {
            $("#fname_error_message").html("Only alphabets and spaces are allowed, and the field cannot be empty");
            $("#fname_error_message").show();
            $('#register').attr("disabled", true);
            error_fname = true;
        }
    }
});

</script>