<?php include_once('includes/config.php'); ?>
 <?php define("title","User Profile | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php

$user_id = $_SESSION['user_id'];
$client_id = $_SESSION['client_id'];
?>
<?php 
    $sql="select * from thirdparty where id='".$_REQUEST['cid']."'";	
	$getsql=mysqli_query($conn,$sql);
	$data=mysqli_fetch_array($getsql);
						
	?>



<style type="text/css">
	.info-box i {
	display: block;
	height: 40px;
	font-size: 50px;
	line-height: 60px;
	width: 60px;
	float: left;
	text-align: center;
	margin-right: 15px;
	padding-right: 20px;
	color: rgba(255, 255, 255, 0.75);
	}
	.stats {
  height: 50%;
  background: #fff;
  display: flex;
/*   justify-content: center; */
  align-items: center;
}

.stat {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 32%;
/*   background: red; */
}

.stat:nth-child(2) {
  border: 1px solid #adadad;
  border-top: 0;
  border-bottom: 0;
/*   background: blue; */
}

.stat-num {
  font-size: 2.0rem;
}

.stat-name {
  font-size: 0.7em;
  color: #e07272;
}
</style>
<!--main content start-->
<section id="main-content">
<section class="wrapper">
	<div class="row">
		<div class="col-lg-12">
			<div class="row">
			   <div class="col-sm-12 text-center">
				   <?php if($successerror!=''){ ?>
				   <div class="alert alert-success" role="alert">
				  <?=$successerror;?>
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
				<?php } ?>
			   </div>
			</div>
			<div class="row">
			   <div class="col-sm-12 text-center">
				   <?php if($error!=''){ ?>
				   <div class="alert alert-danger" role="alert">
				  <?=$error;?>
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				  </button>
				</div>
				<?php } ?>
			   </div>
			</div>	
			<ol class="breadcrumb">
				<li><i class="fa fa-home"></i><a href="dashboard.php">API Access</a></li>
				<li><i class="icon_documents_alt"></i>Third Party</li>
				<li><i class="fa fa-users" aria-hidden="true"></i><?php echo $data['name'];?>'s Access Token</li>
			</ol>
		</div>
	</div>
	<!-- page start-->
	
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<header class="panel-heading tab-bg-info">
					<ul class="nav nav-tabs">
					
						<li class="active">
							<a data-toggle="tab" href="#profile">
							<i class="icon-user"></i>
							Access Token
							</a>
						</li>
					</ul>
				</header>
										<div class="text">
										<hr>
											<p class="attribution"><a href="#">Name : </a> <?=$data['name']?></p><hr>
											<p class="attribution"><a href="#">Description : </a> <?=$data['description']?></p><hr>
											<p class="attribution"><a href="#">Access Token :</a> <code><?=$data['accesstoken']?></code></p><hr>
											<p class="attribution"><a href="#">Refresh Token :</a> <code><?=$data['refreshtoken']?></code></p><hr>
											<p><strong>Use: </strong> Add header in API Call.
											    Example </p>
<pre>CURL https://anyapiendpoint.data/getvalue
-H "X-Authorization: Bearer <?=$data['accesstoken']?>"
-H "Content-Type: application/json"
											</pre>
											
										</div>
		
	<!-- page end-->
	</section>
</div>
</div>
</section>
</section>

<?php include_once('includes/footer.php'); ?>