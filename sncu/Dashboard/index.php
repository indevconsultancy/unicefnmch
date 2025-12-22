<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Login Page</title>
    <style>
        .bg {
            background: linear-gradient(50deg, #5a8bc2, transparent);
            background-attachment: fixed;
        }

        .section {
            box-shadow: 3px 1px 20px 0px #6c9ad2;
            padding: 40px 40px;
            margin: 40px;
            background: #fff;
            /* background: linear-gradient(45deg, #71bfcf, #9cbdde, transparent); */
            border: 28px solid #a1c0df;
        }

        .jumbotron {
            padding: 2rem 1rem;
            margin-bottom: 0 !important;
            background-color: #e9ecef;
            border-radius: 0.3rem;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.37rem 1.3rem;
            font-size: 1.2rem;
            line-height: 1.9;
            background-clip: padding-box;
            border: 4px solid #ced4da;
            border-radius: 8.25rem;
            border: 2px solid #ced4da;
        }

        .loginbutton {
            cursor: pointer;
            width: 416px;
            margin-left: -13px;
            border-radius: 8.25rem;
        }

        .mt-3,
        .my-3 {
            margin-top: 2rem !important;
            border-radius: 8.25rem;
        }

        label {
            font-size: 20px;
            color: white;
            font-weight: 500;
        }

        .btn-primary {
            color: #f6f9f5;
            background-color: #007bff;
            border-color: #0d0d0d;
            border-radius: 18px;
        }

        .btn-login {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 1px;
            border-radius: 50px;
            padding: 12px 10px;
            width: 100%;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
            transition: all 0.3s ease-in-out;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0056b3, #0099cc);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
        }

        .btn-login:active {
            transform: translateY(1px);
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.6);
        }

        element.style {}

        .jumbotron {
            padding: 2rem 1rem;
            margin-bottom: 0 !important;
            background-color: #e9ecef;
            border-radius: 0.3rem;
        }

        .jumbotron {
            padding: 4rem 2rem;
        }

        .jumbotron {
            padding: 2rem 1rem;
            margin-bottom: 1rem;
            background-color: #e9ecef;
            border-radius: 0.3rem;
        }

        form {
            background: linear-gradient(45deg, rgb(0 0 0 / 0%), #442fa8, transparent);
            background: linear-gradient(72deg, #1e6cbb, transparent);
        }

        #captch-img1 {
            height: 39px;
            width: 101px;
            border: 2px solid;
            border-top-left-radius: 17px;
            border-bottom-left-radius: 17px;
        }

        .site-button {
            border-top-right-radius: 17px;
            border-bottom-right-radius: 17px;
            font-size: larger;
            margin-left: -13px;
            height: 39px;
            background-color: #6596c1;
        }
    </style>
</head>

<body class="bg">
    <?php include('includes/headers.php'); ?>
    <div class="container">
        <div class="section">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="image">
                        <img src="assets/lady.png" width="100%" class="image-fluid" alt="image">
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <form method="post" class="jumbotron">
                        <h3 class="text-center">Login</h3>
                        <div class="input-field-login icon username-container">
                            <div class="mb-3">
                                <label style="margin-left: 10px;">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Enter Your Username" style="margin-top: -8px;">
                            </div>
                        </div>

                        <label style="margin-left: 10px;">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter Your Password" style="margin-top: -8px;">
                        <div class="d-flex justify-content-end">
                            <span style="font-size: 14px; font-weight: 500; margin-right: 10px; color: #ffffff;">
                                Reset Password
                            </span>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <input type="text" name="catch" id="captcha1" class="form-control" placeholder="Enter Captcha *" required autocomplete="off">
                                    <span class="text-danger" id="captcherror"></span>
                                </div>
                            </div>
                            <div class="form-group col-md-4 captcha-column">
                                <img src="captcha.php" id="captch-img1" width="100%" style="height: 50px; width: 160px;">
                            </div>
                            <div class="form-group col-md-2 refars">
                                <button type="button" class="site-button site-button-dark w-100" data-toggle="tooltip" title="Refresh Captcha" style="height: 50px;">↻</button>
                            </div>
                        </div>
                        <!-- <div class="input-field-login icon username-container loginbutton" style="margin-left: 6px !important;"> -->
                        <button type="submit" name="submit" class="btn btn-login mt-2">Login</button>
                        <!-- </div> -->
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Script Logic Here -->
    <script>
        document.querySelector(".refars button").addEventListener("click", function() {
            document.getElementById("captch-img1").src = "captcha.php?" + Date.now();
        });
    </script>
</body>

</html>