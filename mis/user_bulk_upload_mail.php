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

                $name = $line[0];
                $email_id = $line[1];
                $mobile_number = $line[2];
                //  $role_id = $line[3];
                //  $userAccess = implode(",", $role_id);

                if (strlen($mobile_number) <= '15') {
					mysqli_autocommit($conn, FALSE);
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
					$sqlusercheck = mysqli_query($conn, "SELECT user_id,email FROM `users` where email='" . $email_id . "'");
					if (mysqli_num_rows($sqlusercheck) > 0) {
						$err++;
						$errmsg .= $rowno . ",";
						$errmsgmail .= $email_id . ", ";
					}else{
						mysqli_autocommit($conn, FALSE);
						if($email_id!=''){
							$mailto=$email_id;
							$message_all = '';
							$subject = base64_encode("MQUAD Login Details");
							$message_all .= '<td width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%">
								<table align="center" width="570" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif"; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; margin: 0 auto; padding: 0; width: 570px">
									<tbody>
										<tr>
											<td style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; max-width: 100vw; padding: 32px">
											<h4 style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left">Dear ' . $name . ',</h4>
											<p style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">Thank you for registering on MQUAD.<br>Login with the username and password given below.</p>
												<table style="font-family: Arial; text-align: left; font-size: 13px" cellpadding="0" cellspacing="0" width="520px">
													<thead>
														<tr>
															<th colspan="2" style="padding: 15px; background: #449a97; color: #fff; border: 1px solid #449a97">Your login credentials are as follows.</th>
														</tr>
													</thead>
													<tbody>
														
														<tr>
															<th style="border: 1px solid #ccc; border-right: none; border-bottom: none; padding: 8px 15px" width="80px">
																Username :
															</th>
															<td style="border: 1px solid #ccc; border-left: none; border-bottom: none;">
																' . $username . '
															</td>
														</tr>
														<tr>
															<th style="border: 1px solid #ccc; border-right: none; padding: 8px 15px">
																Password :
															</th>
															<td style="border: 1px solid #ccc; border-left: none;">
																' . $orignal_password . '
															</td>
														</tr>
													</tbody>
												</table>
												<table align="center" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif; margin: 30px auto; padding: 0; text-align: center; width: 100%">
													<tbody>
													<tr>
														<td align="center" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif">
														<table width="100%" border="0" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif">
															<tbody>
															<tr>
																<td align="center" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif">
																<table border="0" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont">
																	<tr>
																		<td style="box-sizing: border-box; font-family: -apple-system,BlinkMacSystemFont,Roboto,Helvetica,Arial,sans-serif">
																		</td>
																	</tr>
																	</tbody>
																</table>
																</td>
															</tr>
															</tbody>
														</table>
														</td>
													</tr>
													</tbody>
												</table>
												</tbody>
												</table>
											</td>	
										</tr>
									</tbody>  
								</table>	
							</td>';
							$message_all = base64_encode($message_all);
							$sendmail = send_mail_function($mailto, $message_all, $subject);
							if ($sendmail['status'] == 1) {
								$select = "INSERT INTO users set name  = '" . $name . "', email ='" . $email_id . "', mobile = '" . $mobile_number . "',role_id = '3', username = '" . $username . "', password = '" . $password . "', orignal_password = '" . $orignal_password . "', user_code = '" . $email_id . "',status= '1',client_id='" . $_SESSION['client_id'] . "'";
								$insertuser = mysqli_query($conn, $select);
								if($insertuser){
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
								}
							}
						}	
					}	
				} else {
					$err++;
					$errmsg .= $rowno . ",";
					$errmsmobile .= $mobile_number . ", ";
                }
				$rowno++;
            }
            fclose($csvFile);
			if ($err > 0) {
				mysqli_rollback($conn);
			} else {
				mysqli_commit($conn);
			}
			
			if ($errmsg != '' && $errmsgmail!='') {
				$qstring = '?status=err&errmsg=' . $errmsg;
				$pagelink = "user_bulk_upload_mail.php" . $qstring;
				$message = 'Email ID already registered.Please verify';
				echo "<script>alert('$message');
				window.location.href='" . $pagelink . "';
				</script>";
			}
			if ($errmsmobile!='') {
				$qstring = '?status=err&errmsg=' . $errmsg;
				$pagelink = "user_bulk_upload_mail.php" . $qstring;
				$message = 'Enter mobile number less than 15 digit.Please verify';
				echo "<script>alert('$message');
				window.location.href='" . $pagelink . "';
				</script>";
			}
			if ($insertuser!='') {
                $_SESSION['status'] = "User has been registered successfully";
                $_SESSION['status_code'] = "success";
                echo "<script>window.location.href='user-list.php';</script>";
            }
			else {
                $_SESSION['status_error'] = "Something went wrong!!";
                $_SESSION['status_error_code'] = "warning";
            }
        } else {
            $_SESSION['status_error'] = "File Not found!!";
            $_SESSION['status_error_code'] = "warning";
        }
    }
}

?>
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="icon_documents_alt"></i>User List</li>
                    <li><i class="fa fa-plus"></i>User Upload</li>
                </ol>
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
                                                <div class="form-group">
                                                    <label for="cname" class="control-label col-lg-2">Select File: </label>
                                                    <div class="col-lg-10">
                                                        <input class="form-control" required name="file" accept=".xlsx,.xls,.csv" type="file" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-lg-offset-2 col-lg-10">
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