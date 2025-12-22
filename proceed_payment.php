<?php include_once('mis/includes/config.php');

if (isset($_REQUEST['proceeds'])) {
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];
	$country = $_REQUEST['country'];
	$state = $_REQUEST['state'];
	$address = $_REQUEST['address'];
	$user_id = $_REQUEST['userID'];
	$client_id = $_REQUEST['client_id'];
	$subscriptionID = $_REQUEST['subscriptionID'];
	$TotalAmount = $_REQUEST['TotalAmount'];

	$sqlorder = mysqli_query($conn, "insert into pm_orders set  UserID='" . $user_id . "',client_id='" . $client_id . "',SubscriptionID='" . $subscriptionID . "',TotalAmount='" . $TotalAmount . "',BillingName='" . $name . "',BillingEmail='" . $email . "',BillingAddress='" . $address . "',BillingCountryID='" . $country . "',BillingStateID='" . $state . "'");
	$orderid = mysqli_insert_id($conn);
}

function getRealIpAddr()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	{
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . getRealIpAddr());
$geoplugin_currencyCode = $xml->geoplugin_currencyCode;
$geoplugin_currencyConverter = $xml->geoplugin_currencyConverter;
$geoplugin_currencySymbol_UTF8 = $xml->geoplugin_currencySymbol_UTF8;
$geoplugin_request = $xml->geoplugin_request;
$geoplugin_countryName = $xml->geoplugin_countryName;
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
			padding: 15px;
			/* border: 1px solid #003b64; */
			background: #efefef;
			/* border-radius: 15px;*/
			min-height: 250px;
		}

		#annual .pricing-card {
			min-height: 590px;
		}

		.pricing-title {
			font-size: 26px;
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
	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<?php include('includes/header.php'); ?>

<body>

	<div class="mt-5 pricing-sec">
		<div class="container">
			<div class="tab-content">
				<div id="monthly" class="tab-pane fade show active">
					<div class="row justify-content-center">
						<div class="col-sm-8">
							<div class="pricing-card">
								<h4 class="pricing-title">Checkout Details</h4>
								<?php
								$subscriptioninfo = mysqli_query($conn, "SELECT * FROM pm_subscriptions WHERE SubscriptionID='" . $subscriptionID . "'");
								$subscriptiondata = mysqli_fetch_object($subscriptioninfo);
								$subplan = json_decode($subscriptiondata->Description, TRUE);
								$descriptiontext = $subscriptiondata->SubscriptionName . ' ' . $subscriptiondata->SuscriptionType;
								?>
								<input type="hidden" id="descriptiontext" name="descriptiontext" value="<?= $descriptiontext ?>">
								<ul class="list-unstyled col-sm-6">
									<?php
									$i = 0;
									foreach ($subplan as $key => $value) {
										if ($i > 2 && $i <= 6) {
									?>
											<li><?= $value ?></li>
									<?php }
										$i++;
									} ?>
								</ul>
								<ul class="list-unstyled col-sm-6">
									<li>Subscription Type: <?= $subscriptiondata->SuscriptionType ?></li>
									<li>Support Type: <?= $subscriptiondata->SubscriptionName ?></li>
									<?php
									$i = 0;
									foreach ($subplan as $key => $value) {
										if ($i > 6) {
									?>
											<li><?= $value ?></li>
									<?php }
										$i++;
									} ?>
								</ul>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="pricing-card" style="border-radius: 5px; background: #ff9800;">
								<form action="" method="post">
									<?php $convertedAmount = round($TotalAmount * $geoplugin_currencyConverter, 0); ?>
									<input type="hidden" id="userID" name="userID" value="<?= $_SESSION['user_id'] ?>">
									<input type="hidden" id="client_id" name="client_id" value="<?= $_SESSION['client_id'] ?>">
									<input type="hidden" id="subscriptionID" name="subscriptionID" value="<?= $subscriptionID ?>">
									<input type="hidden" id="name" name="name" value="<?= $name ?>">
									<input type="hidden" id="email" name="email" value="<?= $email ?>">
									<input type="hidden" id="orderID" name="orderID" value="<?= $orderid ?>">
									<input type="hidden" id="AmountInUSD" name="AmountInUSD" value="<?= $TotalAmount ?>">
									<div style="text-align:center; padding:10px">
										<h3>Total Amount: $<?= $TotalAmount ?> USD</h3>
										<input type="hidden" id="currencyCode" name="currencyCode" value="<?= $geoplugin_currencyCode ?>">
										<input type="hidden" id="conversionRate" name="conversionRate" value="<?= $geoplugin_currencyConverter ?>">
										<input type="hidden" id="paidAmount" name="paidAmount" value="<?= $convertedAmount ?>">
										<input type="hidden" id="ipAddress" name="ipAddress" value="<?= $geoplugin_request ?>">
										<input type="hidden" id="paymentCountry" name="paymentCountry" value="<?= $geoplugin_countryName ?>">
										<span style="font-size:30px; font-weight:bold; padding: 10px;"><?= $geoplugin_currencySymbol_UTF8 ?><?= $convertedAmount ?></span><br><br>
										<input type="button" class="btn btn-primary" name="btn" id="btn" value="Pay Now" onclick="pay_now()" />
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<?php include('includes/footer.php'); ?>
	<script>
		function pay_now() {
			var userID = $('#userID').val();
			var client_id = $('#client_id').val();
			var subscriptionID = $('#subscriptionID').val();
			var name = $('#name').val();
			var email = $('#email').val();
			var orderID = $('#orderID').val();
			var currency = $('#currencyCode').val();
			var AmountInUSD = $('#AmountInUSD').val();
			var conversionRate = $('#conversionRate').val();
			var paidAmount = $('#paidAmount').val();
			var ipAddress = $('#ipAddress').val();
			var paymentCountry = $('#paymentCountry').val();
			var description = $('#descriptiontext').val();
			var orderIDEncoded = btoa(orderID);

			$.ajax({
				type: 'post',
				url: 'paynow.php',
				data: {
					userID: userID,
					client_id: client_id,
					subscriptionID: subscriptionID,
					name: name,
					email: email,
					orderID: orderID,
					AmountInUSD: AmountInUSD,
					conversionRate: conversionRate,
					paidAmount: paidAmount,
					ipAddress: ipAddress,
					paymentCountry: paymentCountry,
					currency: currency
				},
				success: function(result) {
					console.log(result);
					var options = {
						"key": "rzp_live_a7011OswCdCIeC", //MQUAD live razor pay key
						"amount": AmountInUSD * .01,
						"currency": currency,
						"name": "MQUAD",
						//"image": "https://image.freepik.com/free-vector/logo-sample-text_355-558.jpg",
						"image": "https://mquad.org/Content/Images/logo2.png",
						"handler": function(response) {
							console.log(response);
							$.ajax({
								type: 'post',
								url: 'paynow.php',
								data: "payment_id=" + response.razorpay_payment_id,
								success: function(result) {
									console.log(result);
									window.location.href = "thank_you.php?oid="+orderIDEncoded;
								}
							});
						}
					};
					var rzp1 = new Razorpay(options);
					rzp1.open();
				}
			});


		}
	</script>

</body>

</html>