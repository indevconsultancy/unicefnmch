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
	<link rel="stylesheet" href="assets/css/multistepsform.css">
	<link rel="stylesheet" href="assets/css/pricing.css">
	<style>
		.pricing-sec .tab-content {
			margin-bottom: 20px;
		}
		.nav-tabs .nav-item.show .nav-link,
		.nav-tabs .nav-link.active {
			color: #fff;
			background-color: #003b64;
			border-color: var(--bs-nav-tabs-link-active-border-color);
		}
	</style>
</head>
<?php include('includes/header.php'); ?>

<body>

	<div class="mt-5 multiple-step-sec pricing-sec">
		<div class="container">
			<form id="multistepsform">
				<ul id="progressbar">
					<li class="active"><span><b>1</b></span>Basic Details</li>
					<li><span><b>2</b></span>Pricing</li>
					<li><span><b>3</b></span>Payments</li>
				</ul>
				<fieldset>
					<div class="form-group">
						<label for="name">Name</label> <span style="color:red;">*</span>
						<input type="text" name="name" id="name" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="email">Email</label> <span style="color:red;">*</span>
						<input type="email" name="email" id="email" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="number">Number</label> <span style="color:red;">*</span>
						<input type="text" name="number" id="number" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="gst">GST Number</label> <span style="color:red;">*</span>
						<input type="text" name="gst" id="gst" class="form-control" required>
					</div>
					<input type="button" name="next" class="next action-button btn btn-primary" value="Next" />
				</fieldset>

				<fieldset>
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
								<div class="col-sm-4">
									<div class="pricing-card">
										<h3 class="pricing-title">Standard</h3>
										<h4 class="pricing-price">$99<span>/month</span></h4>
										<a href="billing-details.php?subscription=<?= base64_encode(1) ?>" class="btn btn-primary btn-lg buy-btn">Buy Now</a>
										<p> Free Trial for Humanitarian / LMIC Governments <br>
											<a href="billing-details.php?subscription=<?= base64_encode(4) ?>" class="btn btn-primary">Get Free Trial</a>
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
										<p> **Special discounted pricing $199/ pm for Humanitarian / LMIC Governments </p>
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
						<div id="annual" class="tab-pane fade">
							<p style="margin-bottom: 10px;">*penalty of 2 month subscription fee for early withdrawal(before 12 month)</p>
							<div class="row">
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
					<input type="button" name="next" class="next action-button btn btn-primary" value="Next" />
					<input type="button" name="previous" class="previous action-button btn btn-outline-primary me-2" value="Previous">
				</fieldset>
				<fieldset>
					<div class="form-group">
						<label for="payment-name">Name</label> <span style="color:red;">*</span>
						<input type="text" name="payment-name" id="payment-name" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="payment-email">Email</label> <span style="color:red;">*</span>
						<input type="email" name="payment-email" id="payment-email" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="payment-number">Number</label> <span style="color:red;">*</span>
						<input type="text" name="payment-number" id="payment-number" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="payment-gst">GST Number</label> <span style="color:red;">*</span>
						<input type="text" name="payment-gst" id="payment-gst" class="form-control" required>
					</div>
					<input type="submit" name="submit" class="submit action-button btn btn-primary btn-outline-primary" value="Submit">
					<input type="button" name="previous" class="previous action-button btn btn-outline-primary me-2" value="Previous">
				</fieldset>
			</form>
		</div>
	</div>

	<?php include('includes/footer.php'); ?>
	<script src="assets/js/jquery.easing.min.js"></script>
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
	<script>
		$(document).ready(function() {
			var current_fs, next_fs, previous_fs;
			var opacity;
			$(".next").click(function() {
				current_fs = $(this).parent();
				next_fs = $(this).parent().next();

				// Add validation logic here
				var isValid = true;
				current_fs.find('input, select, textarea').each(function() {
					if ($(this).prop('required') && $(this).val() === '') {
						isValid = false;
						$(this).addClass('is-invalid');
						$(this).next('.error-message').text('This field is required.');
					} else {
						$(this).removeClass('is-invalid');
						$(this).next('.error-message').text('');
					}
				});

				if (!isValid) {
					return false; // Prevent form submission if not valid
				}

				$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

				next_fs.show();
				current_fs.animate({
					opacity: 0
				}, {
					step: function(now) {
						opacity = 1 - now;
						current_fs.css({
							'display': 'none',
							'position': 'relative'
						});
						next_fs.css({
							'opacity': opacity
						});
					},
					duration: 600
				});
			});

			$(".previous").click(function() {
				current_fs = $(this).parent();
				previous_fs = $(this).parent().prev();

				$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

				previous_fs.show();
				current_fs.animate({
					opacity: 0
				}, {
					step: function(now) {
						opacity = 1 - now;
						current_fs.css({
							'display': 'none',
							'position': 'relative'
						});
						previous_fs.css({
							'opacity': opacity
						});
					},
					duration: 600
				});
			});

			$('.radio-group .radio').click(function() {
				$(this).parent().find('.radio').removeClass('selected');
				$(this).addClass('selected');
			});

			// $(".submit").click(function() {
			//     return false;
			// });
		});
	</script>
</body>

</html>