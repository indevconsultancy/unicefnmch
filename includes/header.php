<link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css" />
<style type="text/css">
	.top-bar-btns {
		display: flex;
		justify-content: flex-end;
		padding-right: 30px;
		padding-bottom: 0;
		padding-top: 8px;
	}

	.top-bar-btns>a {
		margin-left: 4px;
	}

	.ih-item.circle {
		margin-bottom: 42px;
	}

	.sidemenu li {
		position: relative;
	}

	@media only screen and (max-width:991px) {
		.top-bar-btns {
			padding-bottom: 0;
			padding-top: 9px;
			max-height: 82px;
			align-items: center;
			margin-left: auto;
			flex-wrap: wrap;
			padding-right: 4px;
		}

		.top-bar-btns>a {
			border-radius: 0px;
			font-size: 11px;
			min-width: 80px;
		}

		.dedooseNav>nav {
			display: flex;
		}

		.navLogo {
			float: none;
			width: auto;
			margin-left: 0;
		}

		.navExpandButton,
		.navCollapseButton {
			position: relative;
			margin-right: 40px;
		}

		.navLogo>a>img {
			min-width: 72px;
		}

		.clientsPanel .title h2 {
			font-size: 22px;
			line-height: 27px;
			margin-top: revert;
			margin-bottom: 10px;
		}

		.awesomePanel .homeAwesomeTitle h2,
		.whatIsDedoosePanel>div>div .title h2,
		.tryDedoosePanel>div>div .descriptionPanel .header>h2 {
			font-size: 22px;
			line-height: 27px;
		}

		.introPanel>div>div>.homeIntroBoxText {
			padding-top: 4px;
		}

		.introPanel>div>div>.homeIntroBoxText p {
			font-size: 14px;
		}

		.awesomePanel .homeAwesomeTitle {
			padding-top: 0px;
		}

		.tryDedoosePanel>div>div .descriptionPanel,
		.whatIsDedoosePanel {
			padding-top: 15px;
		}

		.clientsPanel .title {
			float: none;
		}

		.clientsPanel .clientBoxes .row {
			float: none;
			width: 100%;
			position: relative;
			min-height: 1px;
			padding-left: 0;
			padding-right: 0;
			padding-top: 0;
		}

		.ih-item.circle.effect1 .spinner {
			width: 138px;
			height: 138px;
		}

		.ih-item.circle {
			width: 138px;
			height: 138px;
			margin: 0 auto;
			margin-bottom: 69px;
		}

		.ih-item.circle.effect1 .info h3 {
			color: #fff;
			text-transform: uppercase;
			position: relative;
			letter-spacing: 2px;
			font-size: 8px;
			margin: 0;
			padding: 50px 0 0 0;
			height: 100px;
		}

		.clientsPanel .clientBoxes .row p {
			font-size: 12px;
			margin: 0;
			margin-top: 9px;
			line-height: 14px;
		}

		.mainNav .smScreenNav .dropdown-menu {
			min-width: 100%;
			margin-top: 0;
		}

		.sidemenu li span.glyphicon {
			margin-top: 11px;
			font-size: 11px;
		}

		.mainNav .smScreenNav .dropdown-menu {
			border-radius: 0px !important;
		}

		.mainNav .smScreenNav .dropdown-menu ul {
			margin-left: 0px;
			margin-top: 0;
		}

		.mainNav .smScreenNav .dropdown-menu ul li a {
			color: #ffffff;
			font-size: 16px;
			text-align: left;
			display: block;
			padding: 8px 12px;
			border-bottom: 1px solid #042f4e;
		}

		.mainNav ul li>a {
			border-radius: 0 !important;
		}
	}

	ul.sidemenu {
		margin-top: 0;
		margin-bottom: 0;
		list-style: none;
		padding-left: 0;
	}
</style>
<?php
$domainLink = "https://unicef.indevconsultancy.in/";
?>


<div class="dedooseNav">
	<div class="navShadow"></div>
	<nav class="d-flex align-items-center">
		<div class="navExpandButton">
			<img src="<?= $domainLink; ?>/mis/img/unicef.png" alt="MQUAD Navigation Menu" title="MQUAD Navigation Menu" />
		</div>
		<div class="navCollapseButton">
			<img src="<?= $domainLink; ?>Content/Images/Nav/navigation-close.svg" alt="Close Navigation Menu" title="Close MQUAD Navigation" />
		</div>
		<div class="navLogo">
			<a href='<?= $domainLink; ?>index.php'>
				<img src="<?= $domainLink; ?>mis/img/unicef.png" alt="MQUAD - Feel Good with Data" title="Feel Good with Data" />
			</a>

		</div>

		<div class="mainNav">
			<div class="accountBox">

			</div>
			<div>
				<div class="mainNavItems d-none d-lg-block d-md-block">
					<ul class="d-flex align-items-center gap-3">
						<li>
							<a id="homeNavLink" href='<?= $domainLink; ?>index.php' class="<?= $act = ($seg == 'index.php' || $seg == '') ? '' : '' ?>">Home</a>
						</li>

						<!-- <li>
							<a id="#featuresNavLink" href='<?= $domainLink; ?>features.php' class="<?= $act = ($seg == 'features.php') ? 'active' : '' ?>">
								<img src="<?= $domainLink; ?>Content/Images/Nav/nav-features.svg" alt="Why MQUAD?" title="View MQUAD Features" />Features
							</a>
						</li>
						<li>
							<a id="pricingNavLink" href='<?= $domainLink; ?>pricing-new.php' class="<?= $act = ($seg == 'pricing-new.php') ? 'active' : '' ?>">
								<img src="<?= $domainLink; ?>Content/Images/Nav/nav-pricing.svg" alt="MQUAD offers a range of pricing options to suit your needs and size." title="Check out MQUAD Pricing" />Pricing
							</a>
						</li> -->
						<!-- <li class="hasSubmenu">
							<a id="resourcesLink" href="#">Resources</a>
							<div id="resourcesSubNav" class="subNav" style="display:none">
								<ul>
									
									<li>
										<a href='<?= $domainLink; ?>userguide.php'>
											<img src="<?= $domainLink; ?>Content/Images/Nav/nav-user-guide.svg" alt="" />User Manual
										</a>
									</li>
									<li>
										<a href='<?= $domainLink; ?>faqs.php'>
											<img src="<?= $domainLink; ?>Content/Images/Resources/nav-terms.svg" alt="" title="" />FAQs Document
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="hasSubmenu">
							<a id="aboutLink" href="javascript:void(0);">
								<img src="Content/Images/Nav/nav-about.svg" alt="Learn about what MQUAD is, and where we came from." title="Learn About MQUAD" />About
							</a>
							<div id="aboutSubNav" class="subNav" style="display:none">
								<ul>
									<li>
										<a href='<?= $domainLink; ?>about.php'>
											<img src="<?= $domainLink; ?>Content/Images/Nav/nav-about.svg" alt="Contact MQUAD for support, training or anything else you might need." title="Go to MQUAD Contact Page" />About
										</a>
									</li>
									<li>
										<a href='<?= $domainLink; ?>contact.php'>
											<img src="<?= $domainLink; ?>Content/Images/Nav/nav-contact.svg" alt="Contact MQUAD for support, training or anything else you might need." title="Go to MQUAD Contact Page" />Contact
										</a>
									</li>
								</ul>
							</div>
						</li> -->
						<?php if (!empty($_SESSION['username'])) { ?>
							<li>
								<a id="pricingNavLink" href='<?= $domainLink; ?>mis/dashboard_new.php'>
									<img src="<?= $domainLink; ?>Content/Images/Nav/nav-pricing.svg" alt="MQUAD offers a range of pricing options to suit your needs and size." title="Check out MQUAD Pricing" />Go to Dashboard
								</a>
							</li>

							<li><a href="mis/logout.php">Logout</a></li>
						<?php } ?>

						<?php if (empty($_SESSION['username'])) { ?>
							<li>
								<a href="<?= $domainLink; ?>registration.php" class="btn btn-primary py-2 btnShow">Register</a>
							</li>
							<li>
								<a href="<?= $domainLink; ?>mis/index.php" class="btn btn-primary py-2 btnShow">Log In</a>
							</li>

						<?php } else {  ?>
							<li>
								Welcome &nbsp; <span class="username user-hide"> <?php echo ucfirst($_SESSION['name']); ?></span>
							</li>

						<?php } ?>
					</ul>
				</div>
				<div class="btn-group-vertical d-sm-block d-xs-block d-lg-none d-md-none smScreenNav" role="group" aria-label="Button group with nested dropdown">
					<ul class="sidemenu">
						<li>
							<a class="btn btn-background" href="index.php">Home </a>
						</li>
					</ul>
					<ul class="sidemenu">
						<li>
							<a class="btn btn-background" href='<?= $domainLink; ?>dataverse/' target="_BLANK">Dataverse </a>
						</li>
					</ul>
					<ul class="sidemenu">
						<li>
							<a class="btn btn-background" href="features.php"><img src="Content/Images/Nav/nav-features.svg" alt="" title="View MQUAD Features" />Features </a>
						</li>
					</ul>
					<ul class="sidemenu">
						<li>
							<a class="btn btn-background" href="pricing.php"><img src="Content/Images/Nav/nav-pricing.svg" alt="" title="Check out MQUAD Pricing" />Pricing </a>
						</li>
					</ul>
					<ul class="sidemenu">
						<li class="dropdown">
							<a role="button" id="btnGroupVerticalDrop1" id="resourcesLink" class="btn btn-background dropdown-toggle" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<img src="Content/Images/Nav/nav-resources.svg" alt="" title="View MQUAD Resources" />Resources
							</a>
							<ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
								<!--<li><a href="../videos.php">Videos</a></li>
								<li><a href="../webinars.html">Webinars</a></li>-->
								<!--<li><a href="../validate_xlsform.php">Validate Excel Form</a></li>-->
								<li><a href="../userguide.php">User Manual</a></li>
								<li><a href="../faqs.php">FAQs Document</a></li>
								<!--<li><a href="../blog.php">Blog</a></li>
								<li><a href="../publications.php">Publications</a></li>
								<li><a href="../casestudies.php">Case Studies</a></li>-->
							</ul>
						</li>
					</ul>
					<ul class="sidemenu">
						<li class="dropdown">
							<a role="button" id="btnGroupVerticalDrop2" class="btn btn-background dropdown-toggle" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<img src="Content/Images/Nav/nav-about.svg" alt="Learn about what MQUAD is, and where we came from." title="MQUAD, where we came from and who we are" />About Us
							</a>
							<ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop2">
								<!--<li><a href="about/team.php">Team</a></li>
								<li><a href="about/history.php">History</a></li>
								<li><a href="about/events.php">Events</a></li>
								<li><a href="about/clients.php">Clients</a></li>-->
								<li><a href="about.php">About</a></li>
								<li><a href="contact.php">Contact</a></li>
								<!--<li><a href="request-demo.php">Request Demo</a></li>-->
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
</div>