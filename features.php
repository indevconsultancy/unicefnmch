<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
	<link rel="icon" type="image/png" href="favicon.png">
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Check out some of features, function and implementations that make MQUAD the clear winner in research and collaboration software." />
	<title>Features - Home | MQUAD</title> 
	<link href="Content/CSS/bootstrap/bootstrapV5-3.min.css" rel="stylesheet" />
	<link href="Content/CSS/Site.css" rel="stylesheet" />
	<script src="Content/Scripts/modernizr-2.6.2.js"></script>
</head>
<?php include('includes/header.php'); ?>

<body>

	<div class="featuresOuter">
		<div>
			<div class="featuresMain">
				<div class=header>
					<div class="headerImage">
						<img src="Content/Images/features/features-circle.svg" alt="MQUAD Features" title="MQUAD Application Features" />
					</div>
					<div class="headerTitle">
						<h4>FEATURES</h4>
					</div>
				</div>
				<div class="featuresBoxes">
					<div class="featureRowIMGLeft">
						<div class="featureImage">
							<img src="Content/Images/features/featuresintro.png" alt="Full Qualitative and Mixed Methods Support" title="MQUAD Qualitative and Mixed Methods" />
						</div>
						<div class="featureText">
							<h2>Repository Services</h2>
							<p>
								MQUAD offers complete services for storing, sharing, and accessing tools, documents, and data, whether part of MQUAD or not
							<ul>
								<li>Validated Questions Library</li>
								<li>Study Documents by Theme</li>
								<li>Dataverse</li>
							</ul>
							</p>
							</p>
						</div>
					</div>
					<div class="featureRowIMGRight">
						<div class="featureText">
							<h2>Advanced Form Management</h2>
							<p>Advanced features enable users to create, manage, and perform various tasks using menu-driven options</p>
							<ul>
								<li>Development using Excel and Web-based forms</li>
								<li>Multi-format Search Capablities</li>
								<li>Cross-Platform Compatibility</li>
							</ul>

							</p>
						</div>
						<div class="featureImage">
							<img src="Content/Images/features/webbased_features.png" alt="Fully Web-Based and Cross Platform" title="MQUAD Web-Based" />
						</div>
					</div>
					<div class="featureRowIMGLeft">
						<div class="featureImage">
							<img src="Content/Images/features/intuitive_features.png" alt="Intuitive Features" title="MQUAD Intuitive" />
						</div>
						<div class="featureText">
							<h2>Field Based Sampling</h2>
							<p>
								Applies algorithms for sampling without the need to retain data. Following sampling methods are built to enable field based sampling
							<ul>
								<li>Simple Random Sampling</li>
								<li>Systematic Sampling</li>
							</ul>
							</p>
						</div>
					</div>
					<div class="featureRowIMGRight">
						<div class="featureText">
							<h2>Data Quality Monitoring</h2>
							<p>
								MQUAD offers various data quality metrics such as Timestamp, GPS, keystroke dynamics, and more. Some of the metrics collected and analysed are:
							<ul>
								<li>Start Time and End Time</li>
								<li>Keystroke Details</li>
								<li>Audio Recording</li>
								<li>Period of Inactivity</li>
								<li>Error Statistics</li>
								<li>Customised Reports/Dashboard</li>
								<li>Many other Parameters</li>
							</ul>
							</p>
						</div>
						<div class="featureImage">
							<img src="Content/Images/features/interactive_features.png" alt="Fully Interactive Visualizations and Analytics" title="MQUAD Interactive Visualizations and Analytics" />
						</div>
					</div>
					<div class="featureRowIMGLeft">
						<div class="featureImage">
							<img src="Content/Images/features/mediasupport_features.png" alt="Comprehensive Media Support" title="MQUAD Support" />
						</div>
						<div class="featureText">
							<h2>Easy to Use</h2>
							<p>
								Designed for all skill levels, eliminating the need for advanced technical expertise or coding knowledge, making it user-Friendly
							<ul>
								<li>Menu Driven</li>
								<li>Functions that are Simple to Find</li>
								<li>Comprehensive Help Document</li>
								<li>Simple Excel Templates</li>
							</ul>
							</p>
						</div>
					</div>
					<div class="featureRowIMGRight">
						<div class="featureText">
							<h2>Data Export</h2>
							<p>
								MQUAD support exporting data into various formats, including but not limited to:
							<ul>
								<li>Excel</li>
								<li>Stata</li>
								<li>SPSS</li>
								<li>JSON</li>
								<li>Media File</li>
							</ul>
							</p>
						</div>
						<div class="featureImage">
							<img src="../Content/Images/features/importexport_features.png" alt="Fast and Friendly Import/Export Features" title="MQUAD Import/Export" />
						</div>
					</div>
					<div class="featureRowIMGLeft">
						<div class="featureImage">
							<img src="../Content/Images/features/secure_features.png" alt="Top-Tier Data Security and Privacy Protection" title="MQUAD Security" />
						</div>
						<div class="featureText">
							<h2>Secured Data Controls</h2>
							<p>
								MQUAD has built security features that are of highest standards
							<ul>
								<li>End-to-End Encryption</li>
								<li>Two Stage Authentication</li>
								<li>Use of HTTPS for transfer of data </li>
								<li>Segregated servers for application, data collection, and data storage </li>
								<li>In line with HIPAA & GDPR Guidelines</li>
							</ul>
							</p>
						</div>

					</div>
					<div class="featureRowIMGRight">
						<div class="featureText">
							<h2>Support Services</h2>
							<p>
								MQUAD provides prompt and clear assistance to its subscribers
							<ul>
								<li>Response within 12 hours</li>
								<li>Tutorial Videos</li>
								<li>MQUAD User Community</li>
							</ul>
							</p>
						</div>
						<div class="featureImage">
							<img src="../Content/Images/features/support_features.png" alt="Outstanding Support" title="MQUAD Support" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('includes/footer.php'); ?>
	<script type="text/javascript">
		$(document).ready(function() {
			AnimateIn();
		});

		function AnimateIn() {
			var featureBoxes = $(".featuresBox");
			for (var i = 0; i < featureBoxes.length; i++) {
				TweenLite.from(featureBoxes[i], .7, {
					delay: i * .2,
					alpha: 0,
					y: 1400
				});
			}
		};
	</script>
</body>

</html>