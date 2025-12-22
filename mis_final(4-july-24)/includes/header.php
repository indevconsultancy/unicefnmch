<?php 
 if(empty($_SESSION['username'])){
	 header('Location:index.php');
	//echo "<script>alert('Session Expired. Please login again to continue..');</script>";
	//echo "<script>window.location.href='".base_url()."logout.php'</script>";
	exit;
}
  
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
    <meta name="description" content="MQUAD">
    <meta name="author" content="Satyendra">
    <meta name="keyword" content="MQUAD">
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	
	<link rel="shortcut icon" href="<?=base_url();?>img/mquad-logo.png"> 
    <!--<title>MQUAD | Dashboard</title>-->
	 <title><?php echo title;?></title>
    <link href="<?=base_url();?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url();?>css/bootstrap-theme.css" rel="stylesheet">

    <link href="<?=base_url();?>css/elegant-icons-style.css" rel="stylesheet" />
    <link href="<?=base_url();?>css/font-awesome.min.css" rel="stylesheet" />
	<!-- <link rel="stylesheet" href="<?=base_url();?>assets/font-awesome-4.7.0/css/font-awesome.min.css" /> -->
    <!-- <link rel="stylesheet" href="all-min.css"> -->
    <!-- Custom styles -->
    <link href="<?=base_url();?>css/style.css" rel="stylesheet">
    <link href="<?=base_url();?>css/sumoselect.css" rel="stylesheet">
    <link href="<?=base_url();?>css/style-responsive.css" rel="stylesheet" />
	
	<link href="<?=base_url();?>css/select2.min.css" rel="stylesheet" />
	<link href="<?=base_url();?>assets/toast-plugin-jquery/jquery.toast.min.css" rel="stylesheet" />
	
	<script type="text/javascript">
		// function preventBack(){window.history.forward()}; 
		// selTimeout("preventBack()", 0);
		// window.onunload=function(){null;}
	</script>
	
<style>
body {
	color: #797979;
	background: #eeeeee;
	font-family: revert; //sans-serif
	padding: 0px !important;
	margin: 0px !important;
	font-size: 14px !important;
}
  .panel-heading{
	background: #394a59;
	color: white;
	font-weight: bold;
  }
  .header_logo{
		height: 50px;
		background-color: white;
		border-radius: 50%;
		padding: 4px;
		margin-top: -10px;
	}
	select[class="form-control"]
	{
		text-transform: capitalize;
	}
	.small .italic .pull-right{
		background: #00a0df;
		font-family: 'Lato', sans-serif;
		font-size: 11px;
	}
	.blink {
		animation: blink .5s infinite;
	}
	b, strong {
    color: var(--heading-text-color);
}

.wrapper{
	min-height:538px;
}
.avatar{
	width: 3rem;
    height: 3rem;
    position: relative;
    display: inline-block;
    /* background: var(--colab-gray); */
    border-radius: 50%;
	margin-top: 10px;
}
.avatar img{
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
			   <div class='loader-icon'><img src="<?=base_url();?>img/mquad-logo.png" alt=""></div>
		   </div>
	   </div>              
   </div>

    <section id="container" class="">
    <header class="header">
      <div class="toggle-nav">
        <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
      </div>
      <a href="<?=base_url();?>dashboard_new.php" class="logo">
	   <!--<span class="lite">Admin</span>-->
		<img src="<?=base_url();?>img/mquad-logo.png" class="header_logo" />
		<span style="font-weight: bold;color: white;">MQUAD</span>
	  </a>
      <div class="nav search-row" id="top_menu">
        <ul class="nav top-menu">
          <li>
            <form class="navbar-form">
            </form>
          </li>
        </ul>
        <!--  search form end -->
      </div>
      <div class="top-nav notification-row">
        <!-- notificatoin dropdown start-->
        <ul class="nav pull-right top-menu">
		<!-- alert notification start-->
		<!--Create function in Config page-->
		<?php $ques_total=getcounts_multi($conn,'question_bank','question_bank_id','status_type',0,'status',0);?>
		<?php $tool_total=getcounts_multi($conn,'survey_bank','id','tool_archive_status',0,'tool_access','Public','status',0);?>
		<?php 
			$sqlrepositiry="SELECT COUNT(data_repositroy.data_repository_id) as total_data_repositroy FROM `data_repositroy` left join data_repositroy_otherdata on data_repositroy_otherdata.data_repository_id=data_repositroy.data_repository_id where data_repositroy.data_repositroy_status='0' and data_repositroy_otherdata.data_access='Public' and data_repositroy.status='1'";
			$datarepositiry=mysqli_query($conn,$sqlrepositiry);
			$count=mysqli_fetch_array($datarepositiry);
			$total=$count['total_data_repositroy'];
			
			$totalData=$ques_total+$tool_total+$total;
		?>
		<?php if($_SESSION['role_id']=='1'){?>
			<li id="alert_notificatoin_bar" class="dropdown">
				<a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<i class="icon-bell-l"></i>
					<span class="badge bg-important"><?=$totalData;?></span>
				</a>
				<ul class="dropdown-menu extended notification">
					<div class="notify-arrow notify-arrow-blue"></div>
					<li>
						<p class="blue">You have new notifications</p>
					</li>
					<li>
						<a href="my_question.php">
							<span class="label label-primary"><i class="icon_document_alt"></i></span>
							Question bank
						
							<span class="small italic pull-right"><?=$ques_total;?></span>
						</a>
					</li>
					<li>
						<a href="survey_bank.php">
							<span class="label label-warning"><i class="fa fa-wrench"></i></span>
							Tool Archives
							<span class="small italic pull-right"><?=$tool_total;?></span>
						</a>
					</li>
					<li>
						<a href="data_bank.php">
							<span class="label label-danger"><i class="fa fa-newspaper-o"></i></span>
							Data Repository
							<span class="small italic pull-right"><?php echo $count['total_data_repositroy'];?></span>
						</a>
					</li>
					
				</ul>
			</li>
		<?php } ?>	
		
          <!-- alert notification end-->
          <!-- user login dropdown start-->
          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:">
				<div class="avatar"><img src="<?=base_url();?>img/icons/user-1.png" alt=""></div>
            <span class="username user-hide"><?php echo $_SESSION['username'];?></span>
			<b class="caret caret-hide"></b>
            </a>
            <ul class="dropdown-menu extended logout">
              <div class="log-arrow-up"></div>
			  <?php if($_SESSION['role_id']!='1'){ ?>
              <li class="eborder-top">
                <a href="<?=base_url();?>client-profile.php"><i class="icon_profile"></i> My Profile</a>
              </li>
			  <?php } ?>
			  <?php if($_SESSION['role_id']=='3'){ ?>
			  <li>
                <a href="<?=base_url();?>subscription-details.php"><i class="fa fa-indent"></i> My Subscription</a>
              </li>
			  <?php } ?>
              <li>
                <a href="<?=base_url();?>logout.php"><i class="icon_key_alt"></i> Log Out</a>
              </li>
			  
            </ul>
          </li>
		<li>
		<?php if($_SESSION['functional_role_id']=='3'){ ?>
			
			<span class="username user-hide">
			Used storage / Total storage <br><strong class="blink"><?=round($_SESSION['datasize_mb'])?> MB / <?=$_SESSION['allocatedsize_mb'];?> MB </strong>
			</span>
		<?php } ?>	
		</li>
        </ul>
      </div>
    </header>
