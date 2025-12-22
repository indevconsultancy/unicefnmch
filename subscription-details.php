<?php include_once('mis/includes/config.php');  ?>
<?php 
 // if(empty($_SESSION['username'])){
	// echo "<script>window.location.href='mis/index.php'</script>";
	// exit;
// }
if(isset($_REQUEST['p']))
{
$subscriptionCode=$data = mysqli_real_escape_string($conn, $_REQUEST['p']);
$sqlprod=mysqli_query($conn,"select SubscriptionID from pm_subscriptions where SubscriptionCode='".$subscriptionCode."'");
$sqlprodata=mysqli_fetch_object($sqlprod);

$subscriptionID=$sqlprodata->SubscriptionID;

}

 ?>
<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
	<link rel="icon" type="image/png" href="favicon.png">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Starting as low as $8.95 per month, MQUAD offers affordable monthly plans for individuals, large and small groups as well as Enterprise Packages." />
	<title>Pricing - Home | MQUAD</title>
	<link href="Content/CSS/bootstrap/bootstrapV5-3.min.css" rel="stylesheet" />
	<link href="Content/CSS/Site.css" rel="stylesheet" />
	<script src="Content/Scripts/modernizr-2.6.2.js"></script>
	<style>
		.pricing-sec {
			padding-top: 120px;
			/* min-height: calc(100vh - 60px); */
			padding-bottom: 40px;
		}

		/* .pricingOuter {
			background: none !important;
			margin-top: 70px;
		}

		.pricingOuter .pricingMain .header {
			display: flex;
			align-items: center;
		} */


		.pricing-card {
			padding: 8px;
			/* border: 1px solid #003b64; */
			background: #efefef;
			/* border-radius: 15px;*/
			min-height: 480px;
		}

		#annual .pricing-card {
			min-height: 590px;
		}

		.pricing-title {
			font-size: 21px;
			padding-bottom:10px;
		}

		.pricing-price {
			font-size: 42px;
		}

		.pricing-price span {
			font-size: 16px;
		}

		.pricing-card ul {
			padding: 0px;
		}

		.pricing-card ul li {
			text-align: left;
			font-size: 16px;
			list-style: none;
			display: flex;
			align-items: start;
			margin-bottom: 10px;
		}

		.pricing-card ul li::before {
			content: "\f00c";
			display: inline-flex;
			font-family: 'FontAwesome';
			width: 25px;
			height: 25px;
			background: #003b64;
			color: #fff;
			justify-content: center;
			align-items: center;
			border-radius: 50%;
			font-size: 14px;
			margin-right: 10px;
		}

		.pricing-card p {
			font-size: 16px;
			margin-bottom: 10px;
		}

		.pricing-card .buy-btn {
			width: 100%;
			margin-bottom: 15px;
		}



		.pricing-sec .tab-content {
			border: 1px solid #003b64;
			/* border-top: 0px; */
			padding: 20px;
			border-bottom-right-radius: 5px;
			border-bottom-left-radius: 5px;
		}

		.pricing-sec .nav-tabs {
			border: 1px solid #003b64;
			border-top-right-radius: 5px;
			border-top-left-radius: 5px;
			overflow: hidden;
			width: max-content;
			border-bottom: 0;
		}

		.pricing-sec .nav-tabs>li>a {
			border-radius: 0px !important;
			color: #003b64;
			font-size: 16px;
			margin: 0px;
		}

		.pricing-sec .nav-tabs>li.active>a,
		.pricing-sec .nav-tabs>li.active>a:hover,
		.pricing-sec .nav-tabs>li.active>a:focus {
			background-color: #003b64;
			border: 1px solid #003b64;
			color: #fff;
		}

		.title-sec {
			margin-bottom: 15px;
		}

		.title-h1 {
			font-weight: 700;
			background-color: #003b64;
			color: #fff;
			width: 200px;
			padding: 10px;
			border-radius: 20px 0px;
			display: inline-block;
			margin-left: 10px;
		}
	</style>
</head>
<?php include('includes/header.php'); ?>

<body>


<div class="mt-5 pricing-sec">
    <div class="container">
        <div class="tab-content">
            <div id="monthly" class="tab-pane fade show active">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pricing-card col-lg-8 col-md-12">
                            <form action="proceed_payment.php" method="post">
                                <h4 class="pricing-title">Billing Details </h4>
                                <div class="card">
                                    <div class="card-body">
                                        <?php 
                                            $userinfo = mysqli_query($conn, "SELECT user_id, name, mobile, email, company_name, registered_as FROM users WHERE user_id='".$_SESSION['user_id']."'");
                                            $userdata = mysqli_fetch_object($userinfo);
                                        ?>
                                        <div class="dedoooseSignupForm">
                                            <input type="hidden" name="userID" value="<?=$_SESSION['user_id']?>">
                                            <input type="hidden" name="client_id" value="<?=$_SESSION['client_id']?>">
                                            <input type="hidden" name="subscriptionID" value="<?=$subscriptionID?>">
                                            <div class="mb-3">
                                                <label for="form_fname" class="form-label">Billing Name <span style="color:red;">*</span></label>
                                                <input type="text" required class="form-control" id="form_fname" name="name" placeholder="Enter your name" value="<?=$userdata->name ?>">
                                                <!--<div id="fname_error_message" class="form-text text-danger"></div>-->
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email ID <span style="color:red;">*</span></label>
                                                <input type="email" required class="form-control" id="email" name="email" placeholder="Enter your Email" value="<?=$userdata->email ?>">
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div class="mb-3 me-2 w-50">
                                                    <label for="country" class="form-label">Country <span style="color:red;">*</span></label>
                                                    <select class="form-select" name="country" required id="country">
                                                        <option value="">Select Country</option>
                                                        <?php 
                                                            $sqlcountry = mysqli_query($conn, "SELECT country_id, country_name FROM country ORDER BY country_name ASC");
                                                            while($datacountry = mysqli_fetch_object($sqlcountry)) {
                                                        ?>
                                                        <option value="<?=$datacountry->country_id?>"><?=$datacountry->country_name?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3 ms-2 w-50">
                                                    <label for="state" class="form-label">State <span style="color:red;">*</span></label>
                                                    <select class="form-select" name="state" required id="state">
                                                        <option value="">Select State</option>
                                                        <?php 
                                                            $sqlstate = mysqli_query($conn, "SELECT state_id, state_name FROM states ORDER BY state_name ASC");
                                                            while($datastate = mysqli_fetch_object($sqlstate)) {
                                                        ?>
                                                        <option value="<?=$datastate->state_id?>"><?=$datastate->state_name?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="form_address" class="form-label">Billing Address</label>
                                                <textarea class="form-control" name="address" id="form_address" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div>
                           
                        </div>
                        <div class="pricing-card col-lg-4 col-md-12">
                            <?php 
                                $subscriptioninfo = mysqli_query($conn, "SELECT * FROM pm_subscriptions WHERE SubscriptionID='".$subscriptionID."'");
                                $subscriptiondata = mysqli_fetch_object($subscriptioninfo);
                                $subplan = json_decode($subscriptiondata->Description, TRUE);
                            ?>
                            <h5 class="pricing-title"><?=$subscriptiondata->SuscriptionType?> Subscription ($<?=round($subscriptiondata->Price, 0)?>)</h5>
                            <ul class="list-unstyled">
                                <?php 
                                    $i = 0;
                                    foreach($subplan as $key => $value) {
                                        if($i > 2) {
                                ?>
                                <li><?php echo $key; ?> <?=$value?></li>
                                <?php } $i++; } ?>
                            </ul>
                            <input type="hidden" name="TotalAmount" value="<?=round($subscriptiondata->Price, 0)?>">
                            <button type="submit" class="btn btn-primary btn-lg w-100 buy-btn" disabled id="register" name="proceeds">Confirm & Proceed</button>
                            <?php $subscriptionData = subscription_services($conn, $_SESSION['user_id']); ?>
                        </div>
						 </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

	<?php include('includes/footer.php'); ?>
	<script type="text/javascript">
    $(document).ready(function() {
        // Fetch districts based on selected state
        $('#country').change(function() {
            var stateId = $(this).val();
            $.ajax({
                url: 'getStates.php',
                method: 'POST',
                data: { country_id: stateId },
                success: function(data) {
                    $('#state').html(data);
                }
            });
        });
    });
	</script>
	<script type="text/javascript">
		/*gtag('event', 'visit', {
			'event_category': 'pricing page',
			'event_label': 'Pricing visit'
		});*/

		$(document).ready(function() {
			AnimateIn();
		});

		function AnimateIn() {
			TweenLite.from(".headerImage", .5, {
				delay: 0,
				alpha: 0
			});
			TweenLite.from(".headerTitle", .5, {
				delay: .4,
				alpha: 0
			});
			TweenLite.from(".pricingUpperContent", .5, {
				delay: .4,
				alpha: 0
			});
			TweenLite.from(".pricingBoxesHeader", .5, {
				delay: .6,
				alpha: 0
			});
			TweenLite.from(".pricingBoxes", .5, {
				delay: .7,
				alpha: 0,
				x: -1000
			});
		};
	</script>
	<script>
		function setMinHeightToMax() {
			var cards = document.querySelectorAll('.pricing-details');
			var maxHeight = 0;

			// Iterate through each card to find the maximum height
			cards.forEach(function(card) {
				var cardHeight = card.clientHeight;
				if (cardHeight > maxHeight) {
					maxHeight = cardHeight;
				}
			});

			// Set the minimum height of all cards to the maximum height
			cards.forEach(function(card) {
				card.style.minHeight = maxHeight + 'px';
			});
		}

		// Call the function to set min-height after the page has loaded
		window.addEventListener('load', setMinHeightToMax);
	</script>
	<script type="text/javascript">
		$(function() {
			$("#fname_error_message").hide();
			var error_fname = false;


			
				var pattern = /^[a-zA-Z ]*$/;
				var fname = $("#form_fname").val().trim();
				var email = $("#email").val();

				if (pattern.test(fname) && fname !== '') {
					$("#fname_error_message").hide();
					if (email !== '') {
						$('#register').prop("disabled", false);
					} else {
						$('#register').prop("disabled", true);
					}
				} else {
					$("#fname_error_message").html("First name should contain only letters and white space.");
					$("#fname_error_message").show();
					$('#register').prop("disabled", true);
					error_fname = true;
				}
			
		});
	</script>
</body>

</html>