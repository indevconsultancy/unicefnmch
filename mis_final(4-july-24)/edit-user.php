<?php include_once('includes/config.php'); ?>
<?php define("title","Update User | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

if (isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
	$uid=mysqli_real_escape_string($conn,$_REQUEST['id']);
  $getUser = mysqli_query($conn, "SELECT user_id,name,mobile,role_id,email,username FROM users WHERE user_id='" . $uid . "'");
  $user = mysqli_fetch_array($getUser);
}
$error='';
if (isset($_POST['update_user'])) {
	$page=$_GET['page'];
  $uid=mysqli_real_escape_string($conn,$_REQUEST['id']);
  $client_id = mysqli_real_escape_string($conn,$_POST['client_id']);
  $name = mysqli_real_escape_string($conn,$_POST['name']);
  $mobile = mysqli_real_escape_string($conn,$_POST['mobile']);
  $email = mysqli_real_escape_string($conn,$_POST['email']);
  //$username = $_REQUEST['username'];
	$role_id = $_POST['role_id'];
	$count=count($role_id);
	
	if($count<1){ 
		$error='Functional role are required';
	}else{
		$userAccess = implode(",", $role_id);
		$insertUser = mysqli_query($conn, "UPDATE users SET name='" . $name . "', mobile='" . $mobile . "',email='".$email."', role_id='3' where user_id='" .  $uid . "' ");
		$insertFunctional = mysqli_query($conn,"DELETE from functional_role where user_id='" . $uid . "' ");
		$insertFunctional = mysqli_query($conn,"DELETE from menu_role where user_id='" .  $uid . "' ");

		if ($insertUser) {
			$insertFunctional = '';
			foreach ($role_id as $roleid) {
			  $insertFunctional = "INSERT INTO functional_role SET user_id='" . $_REQUEST['id'] . "', role_id='" . $roleid . "' ";
			  mysqli_query($conn, $insertFunctional);
			}
			
			$menuSql=mysqli_query($conn, "INSERT INTO menu_role(menu_id,role_id,user_id,status) SELECT  menu_id,'3','" . $uid . "','1' FROM menu_role WHERE role_id='1' ");
			$getmenus = mysqli_query($conn, "SELECT GROUP_CONCAT(menu_id) AS menu_id FROM `functional_control` WHERE role_id in($userAccess) AND status='0'");
			$menus = mysqli_fetch_object($getmenus);
			$allMenus = $menus->menu_id;

			$menuSql=mysqli_query($conn, "UPDATE menu_role SET status='0' WHERE user_id='" .  $uid . "' and menu_id IN($allMenus) ");
			if($menuSql!=''){
				$_SESSION['status'] = "User has been updated successfully";
				$_SESSION['status_code'] = "success";
				echo "<script>window.location.href='user-list.php?id=$userId?&page=$page'</script>";
			}else{
				$_SESSION['status_error'] = "Something went wrong!!";
				$_SESSION['status_error_code'] = "warning";
			}
		}else{
			$_SESSION['status_error'] = "User has been not updated !!";
			$_SESSION['status_error_code'] = "error";
		}
	}
}
?>
<?php

if (isset($_REQUEST['change_password'])) {
	$page=$_GET['page'];
	$userId=mysqli_real_escape_string($conn,$_GET['id']);
  $oldpass = mysqli_real_escape_string($conn,$_POST['oldpass']);
  $oldpassword=password_hash($oldpass, PASSWORD_DEFAULT);
  $newpass = mysqli_real_escape_string($conn,$_POST['newpassword']);
  $newpassword=password_hash($newpass, PASSWORD_DEFAULT);
  $confirmpass = mysqli_real_escape_string($conn,$_POST['conformpassword']);
  $conformpassword=password_hash($confirmpass, PASSWORD_DEFAULT);

  $queryselpass = mysqli_query($conn, "select user_id,password from users where user_id='" . $userId . "' ");
  $getpass = mysqli_fetch_array($queryselpass);

  $old_Pass = $getpass['password'];

	if(password_verify($oldpass,$old_Pass)){
		if ($newpass == $confirmpass) {
		  $updatepassqry = mysqli_query($conn, "update users set password='".$newpassword."',orignal_password='".$newpass."' where user_id='" . $userId . "'");
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
<!--main content start-->
<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="col-lg-12">
		  
        <ol class="breadcrumb">
          <li><i class="icon_documents_alt"></i> User Management</li>
          <li><i class="fa fa-list"></i>User List </a></li> <!-- <a href="user-list.php"> -->
          <li><i class="fa fa-plus"></i>Update User</li>
        </ol>
      </div>
    </div>
    <!-- page start-->
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading tab-bg-info">
            <ul class="nav nav-tabs">
              <li class="active">
                <a data-toggle="tab" href="#update-profile">
                  <i class="icon-home"></i>
                  Update Profile
                </a>
              </li>

              <li class="">
                <a data-toggle="tab" href="#change-password">
                  <i class="icon-envelope"></i>
                  Change Password
                </a>
              </li>
            </ul>
          </header>
          <div class="panel-body" style="margin-top: 10px;">
            <div class="tab-content">
              <div id="update-profile" class="tab-pane active">
                <div class="form">
                  <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="form-group ">
                      <label for="cname" class="control-label col-lg-2">Functional Role: <span class="required" style="color:red">*</span></label>
                      <div class="col-lg-10">
                        <?php if (isset($conn)) {

                          // echo "SELECT id, name FROM roles where role_type='C' and id!='" . $_SESSION['role_id'] . "' "; die;
                          $getUserType = mysqli_query($conn, "SELECT DISTINCT(roles.name) as name,roles.id FROM `functional_role` INNER join roles on functional_role.role_id=roles.id where functional_role.user_id='" . $_GET['id'] . "'");
                          $rols = array();
                          while ($type = mysqli_fetch_array($getUserType)) {
                            // echo $type['id'];
                            // $rols = explode(",",$type['id']);
                            $rols[] = $type['id'];
                          }
                        ?>
                          <label for=""><input type="checkbox" name="role_id[]" value="5" <?php if (in_array(5, $rols)) {
							echo "checked"; } ?> /> Form Builder</label> <br>
                          <label for=""><input type="checkbox" name="role_id[]" value="6" <?php if (in_array(6, $rols)) {
							echo "checked"; } ?> /> Form Management</label> <br>
                          
                          <label for=""><input type="checkbox" name="role_id[]" value="8" <?php if (in_array(8, $rols)) {
							echo "checked"; } ?> /> Data Reviewer</label> <br>
						  <label for=""><input type="checkbox" name="role_id[]" value="9" <?php if (in_array(9, $rols)) {
								echo "checked"; } ?> /> Data Collector</label> <br>
                          <label for=""><input type="checkbox" name="role_id[]" value="10" <?php if (in_array(10, $rols)) {
							echo "checked"; } ?> /> Repository</label> <br>	
						  <label for=""><input type="checkbox" name="role_id[]" value="12" <?php if (in_array(12, $rols)) {
							echo "checked"; } ?> /> Data Export Identify </label> <br>	
                          <label for=""><input type="checkbox" name="role_id[]" value="7" <?php if (in_array(7, $rols)) {
							echo "checked"; } ?> /> Data Export Deidentify </label> <br>
                          <!--<label for=""><input type="checkbox" name="role_id[]" value="11" <?php //if (in_array(11, $rols)) {echo "checked";} ?> /> Data Administrator</label> <br>-->
                           
                        <?php
                        } ?>
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="cname" class="control-label col-lg-2">Full Name: </label>
                      <div class="col-lg-10">
                        <input class="form-control" name="name" type="text" value="<?= $user['name']; ?>" required />
                      </div>
                    </div>
					 <div class="form-group ">
					  <label for="cname" class="control-label col-lg-2">Mobile</label>
						<div class="col-lg-2">
							<select class="form-control" name="country_id" >
							  <option value="">Country Code</option>	
							  <?php
								$getcountryType = mysqli_query($conn, "SELECT country_id, country_codes,country_name,dialing_code FROM country_code where status='1'");
								while ($type = mysqli_fetch_array($getcountryType)) { 
								?>
							  <option value="<?= $type['country_id'] ?>" <?php if($type['country_id']==$_REQUEST['country_id']){ echo "selected";}?>><?= $type['country_name'] ?>(<?= $type['dialing_code'] ?>)</option>
							  <?php } ?>
							</select>
						</div>
					  <div class="col-lg-8"> 
						<input class="form-control" id="number" value="<?= $user['mobile']; ?>" name="mobile" type="number"/> 
					  </div>
					</div>
				
                    <div class="form-group ">
                      <label for="cname" class="control-label col-lg-2">Email: </label>
                      <div class="col-lg-10">
                        <input class="form-control" name="email" type="email" value="<?= $user['email']; ?>" />
                      </div>
                    </div>
                  
                    <div class="form-group">
                      <div class="col-lg-offset-2 col-lg-10">
                        <button class="btn btn-primary" type="submit" name="update_user">Update</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div id="change-password" class="tab-pane">
                <div class="form">
                  <form class="form-horizontal" role="form" action="" method="POST">
                    <input type="hidden" class="form-control" id="name" name="id" value="<?= $user['id'] ?>">
                    <div class="form-group">
                      <label class="col-lg-2 control-label">Old Password: <span style="color:red">*</span></label>
                      <div class="col-lg-10">
                        <input type="password" class="form-control" id="old_password" required name="oldpass" />
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-lg-2 control-label">New Password: <span style="color:red">*</span></label>
                      <div class="col-lg-10">
                        <input type="password" class="form-control" id="password" required name="newpassword" />
                      </div>
                    </div>
                    <div style="margin-top: 7px;" id="NewPasswordMatch"></div>
                    <div class="form-group">
                      <label class="col-lg-2 control-label">Confirm Password: <span style="color:red">*</span></label>
                      <div class="col-lg-10">
                        <input type="password" class="form-control" id="confirm_password" required name="conformpassword" />
                        <div style="margin-top: 7px;" id="CheckPasswordMatch"></div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-primary" id="checked_pass" name="change_password" disabled >Submit</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
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
<?php if(isset($_SESSION['status_error']) && $_SESSION['status_error']!=''){ ?>
   <script>
		swal.fire({
			  title: "<?php echo $_SESSION['status_error'];?>",
			  icon:"<?php echo $_SESSION['status_error_code']; ?>",
			  confirmButtonColor: '#449A97',
			  confirmButtonText: 'Ok'
			});
	</script>
<?php unset($_SESSION['status_error']);}  ?>
<script>
  $(document).ready(function() {

    $("#confirm_password").on('keyup', function() {
      var password = $("#password").val();
      var confirmPassword = $("#confirm_password").val();
      if (password != confirmPassword)
        $("#CheckPasswordMatch").html("Password does not match !").css("color", "red");
      else
		 $("#checked_pass").attr("disabled", false);
        $("#CheckPasswordMatch").html("Password match !").css("color", "green");

    });
  });
</script>