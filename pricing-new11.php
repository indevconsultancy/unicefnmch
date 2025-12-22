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
		.pricing-details {
			padding: 15px;
			border: 1px solid #003b64;
			margin-bottom: 15px;
			border-radius: 15px;
		}
	</style>
</head>
<?php include('includes/header.php'); ?>

<body>
	<div class="mt-5 pricing-sec" style="margin-top: 120px;">

		<div class="container">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#football">Football</a></li>
				<li><a data-toggle="tab" href="#basketball">Basketball</a></li>
			</ul>
			<div class="tab-content">
				<div id="football" class="tab-pane fade in active">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Future</th>
								<th colspan="1">Humanitarian / LMIC Governments</th>
								<th colspan="3">Paid Subscriptions </th>
							</tr>
							<tr>
								<th>
									Category
								</th>
								<th>Free Trial</th>
								<th>Standard</th>
								<th>Professional**</th>
								<th>Enterprise</th>
							</tr>
						</thead>
						<tbody>

							<tr>
								<th>Forms</th>
								<td>Unlimited</td>
								<td>Unlimited</td>
								<td>Unlimited</td>
								<td>Unlimited</td>
							</tr>
							<tr>
								<th>Users</th>
								<td>Unlimited</td>
								<td>Unlimited</td>
								<td>Unlimited</td>
								<td>Unlimited</td>
							</tr>
							<tr>
								<th>Monthly Submissions</th>
								<td>10000</td>
								<td>10000</td>
								<td>40000</td>
								<td>100000+</td>
							</tr>
							<tr>
								<th>Storage</th>
								<td>10 GB</td>
								<td>10 GB</td>
								<td>40 GB</td>
								<td>100 GB</td>
							</tr>
							<tr>
								<th>Project Space</th>
								<td>1</td>
								<td>1</td>
								<td>4</td>
								<td>10+</td>
							</tr>
							<tr>
								<th>Standard Support</th>
								<td>yes</td>
								<td>yes</td>
								<td>no</td>
								<td>no</td>
							</tr>
							<tr>
								<th>Professional Support</th>
								<td>no</td>
								<td>no</td>
								<td>yes</td>
								<td>no</td>
							</tr>
							<tr>
								<th>Enterprise Support</th>
								<td>no</td>
								<td>no</td>
								<td>no</td>
								<td>yes</td>
							</tr>
							<tr>
								<th>Response Time</th>
								<td>3 business days by email</td>
								<td>3 business days by email</td>
								<td>1 business day by email</td>
								<td>Within 24 hrs phone/email</td>
							</tr>
							<tr>
								<th>Price</th>
								<td>Free</td>
								<td>$1069 ($ 89 /month)
								</td>
								<td>
									$4309 ($359 /month) <br>
									**Special discounted pricing $179/ month for Humanitarian / LMIC Governments
								</td>
								<td>
									On Request
								</td>
							</tr>
							<tr>
								<td></td>
								<td><a href="#" class="btn btn-primary">Buy Now</a></td>
								<td><a href="#" class="btn btn-primary">Buy Now</a></td>
								<td><a href="#" class="btn btn-primary">Buy Now</a></td>
								<td><a href="#" class="btn btn-primary">Buy Now</a></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="basketball" class="tab-pane fade">
					<h3>Basketball</h3>
					<p>Basketball content goes here.</p>
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