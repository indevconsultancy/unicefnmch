<?php include_once('mis/includes/config.php'); ?>
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
			border-radius: 15px;
			min-height: 560px;
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

		.nav-tabs .nav-item.show .nav-link,
		.nav-tabs .nav-link.active {
			color: #fff;
			background-color: #003b64;
			border-color: var(--bs-nav-tabs-link-active-border-color);
		}

		label {
			font-weight: 500;
		}

		@media (min-width: 576px) {
			.modal-dialog {
				min-width: 50% !important;
			}
		}

		
	</style>
</head>
<?php include('includes/header.php'); ?>

<body>


	<div class="mt-5 pricing-sec">
		<!-- <div class="pricingOuter">
			<div class="pricingMain">
				<div class="container">
					<div class="header">
						<div class="headerImage">
							<img src="Content/Images/pricing/pricing-circle.svg" alt="MQUAD Pricing" title="MQUAD Pricing Options">
						</div>
						<div class="headerTitle">
							<h2>PRICING</h2>
						</div>
						<div class="headerText">

						</div>
					</div>
				</div>
			</div>
		</div> -->
		<div class="container">
			<div class="text-center title-sec">
				<img src="Content/Images/pricing/pricing-circle.svg" class="d-block mx-auto" alt="MQUAD Pricing" title="MQUAD Pricing Options" width="120px">
				<h1 class="title-h1">
					PRICING
				</h1>
			</div>
			<button type="button" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#exampleModal">
				Buy Now
			</button>
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link active" data-bs-toggle="tab" href="#monthly">Monthly payment Plan</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-bs-toggle="tab" href="#annual">Annual Commitment Plan*</a>
				</li>
			</ul>
			<div class="tab-content">
				<div id="monthly" class="tab-pane fade show active">
					<div class="row">
						<?php $sqlsubs = mysqli_query($conn, "select * from pm_subscriptions where SuscriptionType='Monthly' and SubscriptionID!=4");
						while ($datasubs = mysqli_fetch_object($sqlsubs)) {

						?>
							<div class="col-sm-4">
								<div class="pricing-card">
									<h3 class="pricing-title"><?= $datasubs->SubscriptionName ?></h3>
									<h4 class="pricing-price"><?php if ($datasubs->Price > 0) { ?>$<?= round($datasubs->Price, 0); ?><span>/month</span><?php } else { ?> <?= $datasubs->refer_to ?> <?php } ?></h4>
									<a href="billing-details.php?subscription=<?= base64_encode($datasubs->SubscriptionID) ?>" class="btn btn-primary btn-lg buy-btn">Buy Now</a>
									<?php if ($datasubs->SubscriptionID == 1) { ?>
										<p> Free Trial for Humanitarian / LMIC Governments <br>
											<a href="billing-details.php?subscription=<?= base64_encode(4) ?>" class="btn btn-primary">Get Free Trial</a>
										</p>
									<?php } else {  ?> <p> <?= $datasubs->default_text ?> </p> <?php } ?>
									<ul>
										<?php //
										$sqlsubsItems = mysqli_query($conn, "select displayText from pm_subscription_items where subscriptionID='" . $datasubs->SubscriptionID . "'");
										while ($descriptions = mysqli_fetch_object($sqlsubsItems)) { ?>
											<li><?= $descriptions->displayText ?></li>
										<?php } ?>

									</ul>
								</div>
							</div>
						<?php } ?>

					</div>
				</div>
				<div id="annual" class="tab-pane fade">
					<p style="margin-bottom: 10px;">*penalty of 2 month subscription fee for early withdrawal(before 12 month)</p>
					<div class="row">

						<?php $sqlsubs = mysqli_query($conn, "select * from pm_subscriptions where SuscriptionType='Yearly' and SubscriptionID!=4");
						while ($datasubs = mysqli_fetch_object($sqlsubs)) {

						?>
							<div class="col-sm-4">
								<div class="pricing-card">
									<h3 class="pricing-title"><?= $datasubs->SubscriptionName ?></h3>
									<h4 class="pricing-price"><?php if ($datasubs->Price > 0) { ?>$<?= round($datasubs->Price, 0); ?><span>/month</span><?php } else { ?> <?= $datasubs->refer_to ?> <?php } ?></h4>
									<a href="billing-details.php?subscription=<?= base64_encode($datasubs->SubscriptionID) ?>" class="btn btn-primary btn-lg buy-btn">Buy Now</a>
									<?php if ($datasubs->SubscriptionID == 1) { ?>
										<p> Free Trial for Humanitarian / LMIC Governments <br>
											<a href="billing-details.php?subscription=<?= base64_encode(4) ?>" class="btn btn-primary">Get Free Trial</a>
										</p>
									<?php } else {  ?> <p> <?= $datasubs->default_text ?> </p> <?php } ?>
									<ul>
										<?php //
										$sqlsubsItems = mysqli_query($conn, "select displayText from pm_subscription_items where subscriptionID='" . $datasubs->SubscriptionID . "'");
										while ($descriptions = mysqli_fetch_object($sqlsubsItems)) { ?>
											<li><?= $descriptions->displayText ?></li>
										<?php } ?>

									</ul>
								</div>
							</div>
						<?php } ?>
						<div class="col-sm-4">
							<div class="pricing-card">
								<h3 class="pricing-title">Standard</h3>
								<h4 class="pricing-price">$89<span>/month</span></h4>
								<p>You will pay $1069 annually.</p>
								<a href="#" class="btn btn-primary btn-lg buy-btn">Buy Now</a>
								<p> Free Trial for Humanitarian / LMIC Governments <br>
									<a href="#" class="btn btn-primary">Get Free Trial</a>
								</p>
								<ul>
									<li> Unlimited Forms</li>
									<li>Unlimited Users</li>
									<li>10000 Monthly Submissions</li>
									<li>10 GB Storage</li>
									<li>1 Project Space</li>
									<li>Standard Support</li>
									<li>3 Business Days by Email Response Time</li>
								</ul>
							</div>
						</div>




						<div class="col-sm-4">
							<div class="pricing-card">
								<h3 class="pricing-title">Professional**</h3>
								<h4 class="pricing-price">$359<span>/month</span></h4>
								<p>You will pay $4309 annually.</p>
								<a href="#" class="btn btn-primary btn-lg buy-btn">Buy Now</a>
								<p> **Special discounted pricing $179/ month for Humanitarian / LMIC Governments </p>
								<ul>
									<li> Unlimited Forms</li>
									<li>Unlimited Users</li>
									<li>40000 Monthly Submissions</li>
									<li>40 GB Storage</li>
									<li>4 Project Space</li>
									<li>Professional Support</li>
									<li>1 Business Days by Email Response Time</li>
								</ul>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="pricing-card">
								<h3 class="pricing-title">Enterprise</h3>
								<h4 class="pricing-price">On Request</h4>
								<p>Annual Price On Request.</p>
								<a href="#" class="btn btn-primary btn-lg buy-btn">Buy Now</a>
								<ul>
									<li> Unlimited Forms</li>
									<li>Unlimited Users</li>
									<li>100000+ Monthly Submissions</li>
									<li>100 GB Storage</li>
									<li>10+ Project Space</li>
									<li>Enterprise Support</li>
									<li>Within 24 Hrs Phone/Email Response Time</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-bs-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog">

			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="exampleModalLabel">Login</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label>uname</label>
							<input type="text" class="form-control" placeholder="User Name">
							<label>upwd</label>
							<input type="password" class="form-control" placeholder="password">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button> -->
					<button type="button" class="btn btn-success btn-sm " data-bs-toggle="modal" data-bs-target="#exampleModal1">Login</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-bs-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="card border-success ">
						<div class="card-header  d-flex justify-content-between">Submission Successful
							<button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="card-body text-dark">
							<p class="card-text">Thank you for submitting your form! We have received your information and will be in touch with you shortly.</p>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include('includes/footer.php'); ?>
	<script type="text/javascript">
		gtag('event', 'visit', {
			'event_category': 'pricing page',
			'event_label': 'Pricing visit'
		});

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
</body>

</html>