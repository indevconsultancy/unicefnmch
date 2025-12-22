<?php
session_start();
include('Dashboard/includes/config.php');
$showSignup = false;
$msg1 = "";
$msg2 = "";

if (isset($_POST['signup'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];
    $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

    $checkQry = "SELECT * FROM users WHERE email='$email'";
    $checkRes = mysqli_query($conn, $checkQry);
    if (mysqli_num_rows($checkRes) > 0) {
        $msg2 = "Email already exists. Please use another email.";
        $showSignup = true;
    } else {
        $qry = "INSERT INTO users (username, email, password,role_id) VALUES ('$user', '$email', '$hashedPassword',1)";
        $res = mysqli_query($conn, $qry);
        if ($res) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role_id'] = $row['role_id'];
            header('Location: Dashboard/dashboard.php');
            exit;
        } else {
            $msg2 = "Something went wrong!";
        }
    }
}

if (isset($_POST['signin'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $captcha = trim($_POST['captcha']);
    $pass = $_POST['password'];

    if (strcasecmp($captcha, $_SESSION['captcha']) == 0) {

        $qry = "SELECT * FROM users WHERE email = '$user' AND status = 1";
        // die;
        $res = mysqli_query($conn, $qry);

        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);

            if (password_verify($pass, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role_id'] = $row['role_id'];

                header('Location: Dashboard/dashboard.php');
                exit;
            } else {
                $msg1 = "Error: Invalid Password!";
            }
        } else {
            $msg1 = "Error: Invalid Username!";
        }
    } else {
        $msg1 = "Error: Invalid Captcha!";
    }
}

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Sign In | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index.html" class="d-inline-block auth-logo">
                                    <img src="assets/images/logo-light.png" alt="" height="20">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h4 class="text-primary" id="formTitle">Login</h4>
                                    <p class="text-muted" id="formSubtitle">Sign in to continue to SNCU.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <!-- Sign In Form -->
                                    <form id="signInForm" method="post">
                                        <?php if (!empty($msg1)) : ?>
                                            <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
                                                <div>
                                                    <?= $msg1 ?>
                                                </div>
                                                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        <?php endif; ?>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Email</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5 password-input" name="password" placeholder="Enter password" id="password-input" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button"><i class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="captcha" class="form-label">Captcha</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="captcha" name="captcha" placeholder="Enter captcha" style="margin-right: 30px;" required>
                                                <span class="input-group-text p-0">
                                                    <img src="captcha.php" id="captchaImage" alt="Captcha" style="height:36px;">
                                                </span>
                                                <button class="btn btn-outline-secondary" type="button" onclick="refreshCaptcha()">⟳</button>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" name="signin" type="submit">Sign In</button>
                                        </div>
                                        <!-- <div class="mt-4 text-center">
                                            <p>Don't have an account? <a href="javascript:void(0)" onclick="toggleForm('signup')">Sign Up</a></p>
                                        </div> -->
                                    </form>

                                    <!-- Sign Up Form (hidden by default) -->
                                    <form id="signUpForm" style="display:none;" method="post">
                                        <?php if (!empty($msg2)) : ?>
                                            <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
                                                <div>
                                                    <?= $msg2 ?>
                                                </div>
                                                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        <?php endif; ?>
                                        <div class="mb-3">
                                            <label for="newUsername" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5 password-input" name="password" placeholder="Enter password" id="password-input" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button"><i class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" name="signup" type="submit">Sign Up</button>
                                        </div>
                                        <div class="mt-4 text-center">
                                            <p>Already have an account? <a href="javascript:void(0)" onclick="toggleForm('signin')">Sign In</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <!-- <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Velzon. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer> -->
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <script>
        function refreshCaptcha() {
            document.getElementById('captchaImage').src = 'captcha.php?' + Date.now();
        }

        function toggleForm(type) {
            if (type === 'signup') {
                document.getElementById('signInForm').style.display = "none";
                document.getElementById('signUpForm').style.display = "block";
                document.getElementById('formTitle').innerText = "Sign Up";
                document.getElementById('formSubtitle').innerText = "Create your account to continue to SNCU.";
            } else {
                document.getElementById('signInForm').style.display = "block";
                document.getElementById('signUpForm').style.display = "none";
                document.getElementById('formTitle').innerText = "Login";
                document.getElementById('formSubtitle').innerText = "Sign in to continue to SNCU.";
            }
        }
    </script>

    <script>
        <?php if ($showSignup): ?>
            toggleForm('signup');
        <?php endif; ?>
    </script>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- particles js -->
    <script src="assets/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="assets/js/pages/particles.app.js"></script>
    <!-- password-addon init -->
    <script src="assets/js/pages/password-addon.init.js"></script>
</body>


<!-- Mirrored from themesbrand.com/velzon/html/default/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 08 Sep 2023 17:56:48 GMT -->

</html>