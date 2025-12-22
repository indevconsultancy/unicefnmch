<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
	<link rel="icon" type="image/png" href="favicon.png">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Starting as low as $8.95 per month, MQUAD offers affordable monthly plans for individuals, large and small groups as well as Enterprise Packages." />
	<title>Pricing - Home | MQUAD</title>
	<link href="Content/CSS/bootstrap/bootstrap.css" rel="stylesheet" />
	<link href="Content/CSS/Site.css" rel="stylesheet" />
	<script src="Content/Scripts/modernizr-2.6.2.js"></script>
	<style>
		.pricing-sec {
			padding-top: 120px;
			padding-bottom: 40px;
		}

		.pricing-card {
			padding: 15px;
			/* border: 1px solid #003b64; */
			background: #efefef;
			border-radius: 15px;
			min-height: 630px;
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
			<div class="text-center title-sec">
				<img src="Content/Images/pricing/pricing-circle.svg" alt="MQUAD Pricing" title="MQUAD Pricing Options" width="120px">
				<h1 class="title-h1">
					PRICING
				</h1>
			</div>

			<div class="row">
				<div class="col-sm-4">
					<div class="pricing-card">
						<h3 class="pricing-title">Standard</h3>
						<h4 class="pricing-price">$ 99<span>/month</span></h4>
						<a href="#" class="btn btn-primary btn-lg buy-btn">Buy Now</a>
						<p><b>Annual Subscription</b>: Apply for annual subscription at $1069 ($89 monthly basis)</p>
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
						<h4 class="pricing-price">$399<span>/month</span></h4>
						<a href="#" class="btn btn-primary btn-lg buy-btn">Buy Now</a>
						<p><b>Annual Subscription</b>: Apply for annual subscription at $4309 ($359 monthly basis)</p>
						<p>**Special discounted for Humanitarian / LMIC Governments <b>Monthly Plan $199/month</b> <b>Annual Plan $2148 annually</b></p>
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
						<a href="#" class="btn btn-primary btn-lg buy-btn">Buy Now</a>
						<p><b>Annual Subscription</b>:On Request</p>
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
			<p style="margin-top: 10px;"> <b>Note </b>*penalty of 2 month subscription fee for early withdrawal(before 12 month) for Annual Commitment Plan</p>
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