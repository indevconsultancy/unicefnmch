<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<aside>
	<div id="sidebar" class="nav-collapse">
		<!-- sidebar menu start-->
		<ul class="nav flex-column main-side-menu">


			<?php
			$user_id = $_SESSION['user_id'];

			$sqlMainMenuQry = mysqli_query($conn, "select name,icon,page,class_name,menu_id,parent_id from menu_master where parent_id=0 and status=0 and menu_id in(select distinct(menu_id) from menu_role where role_id='" . $_SESSION['role_id'] . "' and user_id='" . $user_id . "' and status='0' ) ORDER BY sequence ASC");
			while ($dataMainMenuQry = mysqli_fetch_object($sqlMainMenuQry)) {
				$sqlSubMenuQry = mysqli_query($conn, "select name,page,menu_id,class_name,parent_id from menu_master where status=0 and parent_id='" . $dataMainMenuQry->menu_id . "' and menu_id in(select distinct(menu_id) from menu_role where role_id='" . $_SESSION['role_id'] . "'  and user_id='" . $user_id . "'  and status='0')");
				$submenucount = mysqli_num_rows($sqlSubMenuQry);
				if ($submenucount > 0) { ?>

					<li class="nav-item menu-tab">
						<a class="nav-link d-flex justify-content-between" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#submenu<?= $dataMainMenuQry->menu_id ?>" aria-expanded="false" aria-controls="submenu<?= $dataMainMenuQry->menu_id ?>">
							<i class="<?= $dataMainMenuQry->icon ?> me-2"></i>
							<span><?= $dataMainMenuQry->name ?></span>
							<i class="menu-arrow arrow_carrot-down ms-auto me-2"></i>
						</a>
						<div id="submenu<?= $dataMainMenuQry->menu_id ?>" class="collapse sub-menu-list">
							<ul class="nav flex-column ms-0">
								<?php while ($dataSubMenuQry = mysqli_fetch_object($sqlSubMenuQry)) { ?>
									<li class="nav-item">
										<a class="nav-link <?= $dataSubMenuQry->class_name ?>" href="<?= base_url(); ?><?= $dataSubMenuQry->page ?>"><?= $dataSubMenuQry->name ?></a>
									</li>
								<?php }
								?>
							</ul>
						</div>
					</li>

				<?php } else { ?>

					<li class="nav-item menu-tab">
						<a class="nav-link" href="<?= base_url(); ?><?= $dataMainMenuQry->page ?>">
							<i class="<?= $dataMainMenuQry->icon ?>"></i>
							<span><?= $dataMainMenuQry->name ?></span>
						</a>
					</li>

			<?php }
			}
			?>
			<li class="nav-item menu-tab">
				<a class="nav-link <?php if ($currentPage == "poshan_tracker.php" || $currentPage == "poshan_bulk_upload.php") {
										echo "focused";
									} ?>" href="<?= base_url(); ?>poshan_tracker.php">
					<i class="fa fa-line-chart"></i>
					<span> Poshan Tracker</span>
				</a>
			</li>
			<li class="nav-item menu-tab">
				<a class="nav-link <?php if ($currentPage == "attandance_report.php") {
										echo "focused";
									} ?>" href="<?= base_url(); ?>attandance_report.php">
					<i class="fa fa-calendar"></i>
					<span> Performance Report</span>
				</a>
			</li>
			<li class="nav-item menu-tab">
				<a class="nav-link d-flex justify-content-between" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#submenu99" aria-expanded="true" aria-controls="submenu99">
					<i class="fa fa-pie-chart me-1"></i><span>Consolidated Reports</span>
					<i class="menu-arrow arrow_carrot-down ms-auto me-2"></i>
				</a>
				<div id="submenu99" class="sub-menu-list collapse" style="">
					<ul class="nav flex-column ms-0">
						<li class="nav-item">
							<a class="nav-link chart-icon" href="HBYC_report.php">HBYC Analysis Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link chart-icon" href="HBNC_report.php">HBNC Analysis Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link chart-icon" href="VHSND_analysis_report.php">VHSND Analysis Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link chart-icon" href="AWC_HV_report.php">AWC Analysis Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link chart-icon" href="AMB_report.php">AMB School & VHSND Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link chart-icon" href="Beneficiary_report.php">Beneficiary Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link chart-icon" href="VHSND_quality_report.php">VHSND Quality Report</a>
						</li>

						<li class="nav-item">
							<a class="nav-link chart-icon" href="AWC_quality_report.php">AWC Quality Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link chart-icon" href="IVI_Sucrose_quality_report.php">IV Iron Sucrose Facility Scorecard</a>
						</li>
						<li class="nav-item">
							<a class="nav-link chart-icon" href="IVIS_implementation_report.php">IV Iron Sucrose Implementation</a>
						</li>

					</ul>
				</div>
			</li>
			<li class="nav-item menu-tab">
				<a class="nav-link d-flex justify-content-between collapsed" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#submenu22" aria-expanded="false" aria-controls="submenu22">
					<i class="fa fa-indent me-2"></i>
					<span>NMCH Reporting</span>
					<i class="menu-arrow arrow_carrot-down ms-auto me-2"></i>
				</a>
				<div id="submenu22" class="sub-menu-list collapse" style="">
					<ul class="nav flex-column ms-0">
						<li class="nav-item">
							<a class="nav-link create-icon" href="dashboard_maa.php">MAA Programme</a>
						</li>
						<li class="nav-item">
							<a class="nav-link list-icon" href="https://unicef.indevconsultancy.in/mis/survey-list.php">IYCF </a>
						</li>
					</ul>
				</div>
			</li>
			<li class="nav-item menu-tab">
				<a class="nav-link d-flex justify-content-between collapsed" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#submenu28" aria-expanded="false" aria-controls="submenu22">
					<i class="fa fa-indent me-2"></i>
					<span>ECD Reporting</span>
					<i class="menu-arrow arrow_carrot-down ms-auto me-2"></i>
				</a>
				<div id="submenu28" class="sub-menu-list collapse" style="">
					<ul class="nav flex-column ms-0">
						<li class="nav-item">
							<a class="nav-link create-icon" href="ECD_Friendly_Assessment.php">ECD Friendly Indicators Report</a>
						</li>
						<li class="nav-item">
							<a class="nav-link create-icon" href="ECD_Home_Visit_Assessment.php">ECD Home Visit Assessment</a>
						</li>
						<li class="nav-item">
							<a class="nav-link create-icon" href="ECD_VHSND_Analysis.php">ECD VHSND Analysis</a>
						</li>
						<li class="nav-item">
							<a class="nav-link create-icon" href="ECD_AWC_Monthly_Assessmnt.php">ECD_AWC Monthaly Assessment</a>
						</li>
						<li class="nav-item">
							<a class="nav-link create-icon" href="ECD_All_Indicators.php">ECD All Indicators</a>
						</li>
					</ul>
				</div>
			</li>
			<li class="nav-item menu-tab">
				<a class="nav-link d-flex justify-content-between collapsed" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#submenu33" aria-expanded="false" aria-controls="submenu3">
					<i class="fa fa-users me-2"></i>
					<span>MAA Reporting</span>
					<i class="menu-arrow arrow_carrot-down ms-auto me-2"></i>
				</a>
				<div id="submenu33" class="sub-menu-list collapse" style="">
					<ul class="nav flex-column ms-0">
						<li class="nav-item">
							<a class="nav-link create-icon" href="https://unicef.indevconsultancy.in/mis/dashboard_maa.php">Dashboard</a>
						</li>
						<li class="nav-item">
							<a class="nav-link create-icon" href="https://unicef.indevconsultancy.in/mis/maa_reporting.php">Monthly Reporting</a>
						</li>
						<li class="nav-item">
							<a class="nav-link list-icon" href="https://unicef.indevconsultancy.in/mis/summary_report.php">Summary Report</a>
						</li>
			</li>
			<li class="nav-item">
				<a class="nav-link list-icon" href="https://unicef.indevconsultancy.in/mis/reporting_status.php">Reporting Status</a>
			</li>
			<li class="nav-item">
				<a class="nav-link list-icon" href="https://unicef.indevconsultancy.in/mis/asha_condacted_mm.php">Asha Conducted MM & HELD</a>
			</li>
		</ul>
	</div>
	</li>

	<li class="nav-item menu-tab">
		<a class="nav-link d-flex justify-content-between collapsed" href="#" role="button" data-bs-toggle="collapse" data-bs-target="#submenu44" aria-expanded="false" aria-controls="submenu22">
			<i class="fa fa-line-chart me-2"></i>
			<span>IYCF Reporting</span>
			<i class="menu-arrow arrow_carrot-down ms-auto me-2"></i>
		</a>
		<div id="submenu44" class="sub-menu-list collapse" style="">
			<ul class="nav flex-column ms-0">
				<li class="nav-item">
					<a class="nav-link create-icon" href="https://unicef.indevconsultancy.in/mis/iycf_reporting.php">IYCF Reporting </a>
				</li>
			</ul>
		</div>
	</li>
	</ul>
	</div>
</aside>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var links = document.querySelectorAll('#sidebar .nav-link');
		console.log(links);

		links.forEach(function(link) {
			link.addEventListener('click', function() {
				// Remove the focus class from all links
				links.forEach(function(lnk) {
					lnk.classList.remove('focused');
				});

				// Add the focus class to the clicked link
				this.classList.add('focused');
			});
		});
	});


	document.getElementById('toggle-sidebar').addEventListener('click', function() {
		document.body.classList.toggle('sidebar-closed');
	});
</script>