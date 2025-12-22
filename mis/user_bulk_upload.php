<?php include_once('includes/config.php'); ?>
<?php define("title", "User data upload | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
if (isset($_POST['submit'])) {
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            fgetcsv($csvFile);
            $err = 0;
            $errmsg = '';
            $rowno = 1;
            while (($line = fgetcsv($csvFile)) !== FALSE) {
                $temp = $line;
                $searches = array("'", "/");
                $replacements = array('&#39;', "-");
                $line = str_replace($searches, $replacements, $temp);

                $name = ucwords(sanitizeInput($line[0],$conn));
                $email_id = sanitizeInput($line[1],$conn);
                $mobile_number = sanitizeInput($line[2],$conn);
                //  $role_id = $line[3];
                //  $userAccess = implode(",", $role_id);
				if (empty($name)) {
					 $err++;
					$msg.="Name is required";
                    $errmsg .= $msg.' Row no: '.$rowno . ",";
				}
				if (!filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
                     $err++;
					$msg.="Invalid Email ID";
                    $errmsg .= $msg.' Row no: '.$rowno . ",";
                }
                if (strlen($mobile_number) <= 15) {
                    ///////////Generate username//////////////
                    $namestring = str_replace(' ', '', $name);

                    $gtUser = mysqli_query($conn, "SELECT MAX(user_id) as uid FROM users");
                    $userDetails = mysqli_fetch_object($gtUser);
                    $uid = $userDetails->uid + 1;
                    $usernames = uniqueId($name, $uid); //Create function in functions page

                    $username = trim($usernames);
                    //////////////Generate Password//////////////////
                    $passvar = strtoupper(substr($namestring, 0, 2));
                    $randnum = rand(10000, 1000000);
                    $orignal_passw = "$passvar" . "$randnum";
                    $orignal_password = trim($orignal_passw);
                    $password = password_hash(trim($orignal_password), PASSWORD_DEFAULT);

                    $select = "INSERT INTO users set name  = '" . $name . "', email ='" . $email_id . "', mobile = '" . $mobile_number . "',role_id = '3', username = '" . $username . "', password = '" . $password . "', orignal_password = '" . $orignal_password . "', user_code = '" . $email_id . "',status= '1',client_id='" . $_SESSION['client_id'] . "'";
                    $insertuser = mysqli_query($conn, $select);
                    $user_id = mysqli_insert_id($conn);

                    $userselect = "INSERT INTO functional_role set   user_id = '" . $user_id . "', role_id = '9'";
                    $updateuser = mysqli_query($conn, $userselect);
                    $menu_id = mysqli_insert_id($conn);
                    $userselect = "INSERT INTO menu_role(menu_id,role_id,user_id,status) SELECT  menu_id,'3','" . $user_id . "','1' FROM menu_role WHERE role_id='1' ";
                    $updateuser = mysqli_query($conn, $userselect);

                    $getmenus = mysqli_query($conn, "SELECT GROUP_CONCAT(menu_id) AS menu_id FROM `functional_control` WHERE role_id = '9' AND status='0'");
                    $menus = mysqli_fetch_object($getmenus);
                    $allMenus = $menus->menu_id;
                    $updateuser = mysqli_query($conn, "UPDATE menu_role SET status='0' WHERE user_id='" . $user_id . "' and menu_id IN($allMenus) ");
                } else {
                    //$errormobile=false;
                    $err++;
					$msg.="The mobile number should be between 10 and 14 digits.";
                    $errmsg .= $msg.' Row no: '.$rowno . ",";
                }
                $rowno++;
            }

            fclose($csvFile);
            if ($err > 0) {
                mysqli_rollback($conn);
            } else {
                mysqli_commit($conn);
            }
            if ($errmsg != '') {

                $qstring = '?status=err';
                $pagelink = "user_bulk_upload.php" . $qstring;
                $message = 'Something went wrong.Please verify ' . $errmsg;
                echo "<script>alert('$message');
				window.location.href='" . $pagelink . "';
				</script>";
            }
            if ($insertuser) {
                $_SESSION['status'] = "User has been registered successfully";
                $_SESSION['status_code'] = "success";
                echo "<script>window.location.href='user-list.php';</script>";
            } else {
                $_SESSION['status_error'] = "Something went wrong!!";
                $_SESSION['status_error_code'] = "warning";
            }
        } else {
            $_SESSION['status_error'] = "Something went wrong!!";
            $_SESSION['status_error_code'] = "warning";
        }
    }
}

?>
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>User List</li>
                        <li class="breadcrumb-item" aria-current="page"><i class="fa fa-plus"></i>User Upload</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12" style="min-height:420px;">
                                <header class="panel-heading">Upload User
                                </header>
                                <section class="panel">
                                    <div class="panel-body">
                                        <div class="form">
                                            <form class="form-validate form-horizontal" method="post" enctype="multipart/form-data">
                                                <div class="row mb-2">
                                                    <label for="cname" class="control-label col-lg-2">Select File: </label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" required name="file" accept=".xlsx,.xls,.csv" type="file" />
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-offset-2 col-lg-12 text-end">
                                                        <button class="btn btn-primary " type="submit" name="submit" id="import_data">Submit</button>
                                                        <a href="user-template.csv" class="btn btn-primary">Download Template</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </section>
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