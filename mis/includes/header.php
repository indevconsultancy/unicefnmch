<?php
if (empty($_SESSION['username'])) {
	header('Location:index.php');
	//echo "<script>alert('Session Expired. Please login again to continue..');</script>";
	//echo "<script>window.location.href='".base_url()."logout.php'</script>";
	exit;
}

$max_requests = 10; // Max requests allowed within the time frame
$time_frame = 60; // Time frame in seconds

// Check if session variables exist
if (!isset($_SESSION['last_request_time'])) {
	// Initialize session variables if not set
	$_SESSION['last_request_time'] = time();
	$_SESSION['request_count'] = 1;
} else {
	// Calculate time elapsed since the last request
	$time_elapsed = time() - $_SESSION['last_request_time'];

	if ($time_elapsed < $time_frame) {
		// If within time frame, increment request count
		$_SESSION['request_count']++;
	} else {
		$_SESSION['request_count'] = 1;
		$_SESSION['last_request_time'] = time();
	}


	// Check if request count exceeds the maximum allowed
	if ($_SESSION['request_count'] > $max_requests) {
		// Send a 429 Too Many Requests response
		http_response_code(429);
		die('Rate limit exceeded. Please try again later.');
	}
}
$exemptPages = ['view_survey.php', 'my_tool.php', 'systematic_random_process.php', 'export_import_formate.php', 'upload_question_bank.php', 'data-lookup.php', 'customdashboard/surveydashboard.php', 'add-survey-ssajax_fi.php', 'add-survey_fi.php', 'bulk_upload.php', 'view_survey_fi.php', 'add-question-web-v1.php', 'user_bulk_upload.php', 'view_tools.php', 'form_delete_otp.php', 'validate_xlsform.php', 'survey_view.php', 'replace_webform.php', 'skip_userform_design.php', 'survey_list_ajax.php', 'survey-data-list.php', 'survey-location.php', 'externalApi.php', 'generateAPI.php', 'export_paradata.php', 'view-user.php', 'edit-user.php', 'edit_question_bank.php', 'dataset/add-datafile.php', 'dataset/dataset-details.php', 'client-profile.php', 'edit-survey-bank.php', 'logout.php'];
//var_dump($exemptPages);
$page_name = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$page_name = str_replace('/mis/', '', $page_name);
$menu_id = 0;
$menu_id = getone($conn, 'menu_master', 'menu_id', 'page', $page_name);
//echo $_SESSION['user_id'];
//echo $_SESSION['menu_role_id'];
$menurolesAvial = explode(',', $_SESSION['menu_role_id']);

//var_dump($menurolesAvial);
//echo "Raju2";
//var_dump($_SESSION['menu_role_id']);
$oup = '';
if (in_array($menu_id, $menurolesAvial) || in_array($page_name, $exemptPages)) {
	$authorized_to_set_headers = true;
	//$oup="Authorized";
} else {
	$authorized_to_set_headers = false;
}
/*
 if(!$authorized_to_set_headers)
{
// Set the 403 Forbidden header
header("HTTP/1.1 403 Forbidden");

// Optionally, display a custom error message or page content
echo "<h1>Unauthorized Access</h1>";
echo '<p>You do not have permission to access this page.</p> <a class="btn btn-danger" href="javascript:history.back()">Go Back</a>';

// Stop further execution of the script
exit;
} 
*/
//setcookie('username', 'MQUAD', time() + (7 * 24 * 60 * 60)); 
/* if (isset($_SESSION['custom_session_time']) && (time() - $_SESSION['custom_session_time'] > 1440)) {
	// request 24 minates ago
	//$_SESSION['custom_session_time'] = time();
	session_destroy();
	session_unset();
   echo "<script>alert('Session Expired. Please login again to continue..');</script>";
	echo "<script>window.location.href='".base_url()."logout.php'</script>";
}else{
	$_SESSION['custom_session_time'] = time();
	if(empty($_SESSION['username'])){
	echo "<script>alert('Session Expired. Please login again to continue..');</script>";
	echo "<script>window.location.href='".base_url()."logout.php'</script>";
	exit;
	}
}   */

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="FI Data System">
	<meta name="author" content="FI Data System">
	<meta name="keyword" content="FI Data System">


	<link rel="shortcut icon" href="<?= base_url(); ?>img/<?= $_SESSION['logo_image'] ?>">
	<title><?php echo title; ?></title>
	<link href="<?= base_url(); ?>css/bootstrapV5-3.min.css" rel="stylesheet">

	<link href="<?= base_url(); ?>css/elegant-icons-style.css" rel="stylesheet" />
	<link href="<?= base_url(); ?>css/font-awesome.min.css" rel="stylesheet" />
	<!-- Custom styles -->
	<link href="<?= base_url(); ?>css/style.css" rel="stylesheet">
	<link href="<?= base_url(); ?>css/sumoselect.css" rel="stylesheet">
	<link href="<?= base_url(); ?>css/style-responsive.css" rel="stylesheet" />

	<link href="<?= base_url(); ?>css/select2.min.css" rel="stylesheet" />
	<link href="<?= base_url(); ?>assets/toast-plugin-jquery/jquery.toast.min.css" rel="stylesheet" />

	<!--<link href="<?= base_url(); ?>css/style-updated.css" rel="stylesheet" />-->

	<!--<script type="text/javascript">
		// function preventBack(){window.history.forward()}; 
		// selTimeout("preventBack()", 0);
		// window.onunload=function(){null;}
	</script>-->
	<?php if ($_SESSION['SUBSCRIPTIONEXPIRED'] == true &&  $_SESSION['role_id'] > 1) { ?>

	<?php } ?>
	<style>
		body {
			color: #797979;
			background: #eeeeee;
			font-family: revert; //sans-serif
			padding: 0px !important;
			margin: 0px !important;
			font-size: 14px !important;
		}

		.panel-heading {
			background: #394a59;
			color: white;
			font-weight: bold;
		}

		.header_logo {
			height: 50px;
			padding: 4px;
			margin-top: -10px;
		}

		select[class="form-control"] {
			text-transform: capitalize;
		}

		.small .italic .pull-right {
			background: #00a0df;
			font-family: 'Lato', sans-serif;
			font-size: 11px;
		}

		.blink {
			animation: blink .5s infinite;
		}

		b,
		strong {
			color: var(--heading-text-color);
		}

		.wrapper {
			min-height: 538px;
		}

		.avatar {
			width: 3rem;
			height: 3rem;
			position: relative;
			display: inline-block;
			/* background: var(--colab-gray); */
			border-radius: 50%;
			margin-top: 10px;
		}

		.avatar img {
			width: 100%;
			height: 100%;
		}
	</style>
	<!--<script type="text/javascript">
	function disableBack() { window.history.forward(); }
        setTimeout("disableBack()", 0);
        window.onunload = function () { null };
</script>-->
</head>

<body>

	<div id="pre-load">
		<div id="loader" class="loader">
			<div class="loader-container">
				<div class='loader-icon'><img src="<?= base_url(); ?>img/<?= $_SESSION['logo_image'] ?>" alt=""></div>
			</div>
		</div>
	</div>

	<section id="container" class="">
		<header class="header">
			<div class="toggle-nav">
				<button id="toggle-sidebar" style="line-height: 1rem;" class="btn fs-1 text-white tooltips p-0" title="Toggle Navigation" data-bs-placement="bottom"><i class="icon_menu"></i></button>
			</div>
			<a href="<?= base_url(); ?>dashboard_new.php" class="logo">
				<!--<span class="lite">Admin</span>-->
				<!-- <img src="<?= base_url(); ?>img/unicef.png" class="header_logo" /> -->
				<img src="<?= base_url(); ?>img/<?= $_SESSION['logo_image'] ?>" class="header_logo" />
				<!--<span style="font-weight: bold;color: white;">AAKLAN</span>-->
			</a>
			<div class="nav search-row" id="top_menu">
				<ul class="nav top-menu">
					<li>
						<form class="navbar-form">

						</form>
					</li>
				</ul>

			</div>
			<div class="top-nav notification-row">
				<!-- notificatoin dropdown start-->
				<ul class="nav pull-right top-menu">
					<!-- alert notification start-->
					<!-- Create function in Config page -->
					<?php
					$ques_total = getcounts_multi($conn, 'question_bank', 'question_bank_id', 'status_type', 0, 'status', 0);
					$tool_total = getcounts_multi($conn, 'survey_bank', 'id', 'tool_archive_status', 0, 'tool_access', 'Public', 'status', 0);
					$sqlrepositiry = "SELECT COUNT(data_repositroy.data_repository_id) as total_data_repositroy FROM `data_repositroy` LEFT JOIN data_repositroy_otherdata ON data_repositroy_otherdata.data_repository_id=data_repositroy.data_repository_id WHERE data_repositroy.data_repositroy_status='0' AND data_repositroy_otherdata.data_access='Public' AND data_repositroy.status='1'";
					$datarepositiry = mysqli_query($conn, $sqlrepositiry);
					$count = mysqli_fetch_array($datarepositiry);
					$total = $count['total_data_repositroy'];

					$totalData = $ques_total + $tool_total + $total;
					?>
					<?php //if ($_SESSION['role_id'] == '1') { 
					?>
					<!-- <li id="alert_notification_bar" class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="icon-bell-l"></i>
								<span class="badge bg-important"><?= $totalData; ?></span>
							</a>
							<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
								<div class="dropdown-arrow"></div>
								<li>
									<p class="dropdown-header">You have new notifications</p>
								</li>
								<li>
									<a class="dropdown-item" href="my_question.php">
										<span class="badge bg-primary"><i class="icon_document_alt"></i></span>
										Question bank
										<span class="small italic pull-right"><?= $ques_total; ?></span>
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="survey_bank.php">
										<span class="badge bg-warning"><i class="fa fa-wrench"></i></span>
										Tool Archives
										<span class="small italic pull-right"><?= $tool_total; ?></span>
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="data_bank.php">
										<span class="badge bg-danger"><i class="fa fa-newspaper-o"></i></span>
										Data Repository
										<span class="small italic pull-right"><?php echo $count['total_data_repositroy']; ?></span>
									</a>
								</li>
							</ul>
						</li>-->
					<?php //} 
					?>

					<!-- alert notification end-->
					<!-- user login dropdown start-->
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<div class="avatar"><img src="<?= base_url(); ?>img/icons/user-1.png" alt=""></div>
							<span class="username user-hide"><?php echo $_SESSION['username']; ?></span>
						</a>
						<ul class="dropdown-menu extended logout dropdown-menu-end" aria-labelledby="userDropdown">
							<div class="log-arrow-up"></div>
							<?php if ($_SESSION['role_id'] != '1') { ?>
								<li>
									<a class="dropdown-item" href="<?= base_url(); ?>client-profile.php"><i class="icon_profile"></i> My Profile</a>
								</li>
							<?php } ?>
							<li>
								<a class="dropdown-item" href="<?= base_url(); ?>logout.php"><i class="icon_key_alt"></i> Log Out</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</header>