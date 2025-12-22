<?php include_once('includes/config.php'); ?>
<?php define("title","Add Form | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php 
if(isset($_REQUEST['subscription']))
{
$subscriptionID=base64_decode($_REQUEST['subscription']);
}
?>
<!--main content start-->
<style>
    .panel-heading {
    background: #394a59;
    color: white;
    font-weight: unset;
	}
	.btn:not(:disabled):not(.disabled) {
		cursor: pointer;
	}
	.add-button-bg a {
		position: fixed;
		bottom: 54px;
		right: 50px;
		background: rgb(57,74,89);
		z-index: 99999;
		border-radius: 50%;
		width: 60px;
		height: 60px;
		color: #fff;
		line-height: 46px;
		font-size: 22px;
		transition: all .3s ease-in-out;
	}
	.add-button-bg a:hover{
		background: rgb(4 39 60);
		color: #ffffff;
		-webkit-transform: rotate(90deg);
		transform: rotate(90deg);
		box-shadow: 1px 1px 1px 17px rgb(255 192 192 / 28%);
		
	}
	
	.sm_value1 {
		padding: 3px;
		color: #ffffff;
		border-radius: 5px;
		min-width: 45px;
		text-align: center;
		font-size: 20px;
		font-weight: 700;
		background: #033d66;
		width: 20px;
	}
</style>
<style>
#main-content .wrapper .row{
	margin-bottom:0px;
}
.panel{
	margin-bottom: 20px;
}
.panel .panel-heading {
    margin-top: -10px;
}
</style>
<style>
		.pricing-sec {
			padding-top: 0px;
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
			/* border-radius: 15px;*/
			min-height: 420px;
		}

		#annual .pricing-card {
			min-height: 520px;
		}

		.pricing-title {
			font-size: 21px;
			padding-bottom:10px;
			font-weight:700;
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

		
	</style>

<!--<div class="loading-indicator">
	<div class="lds-facebook"><div></div><div></div><div></div></div>
</div>-->
<div id="pre-load" class="loading-indicator">
   <div id="loader" class="loader">
	   <div class="loader-container">
		   <div class='loader-icon'><img src="https://mquad.org/mis/img/mquad-logo.png" alt=""></div>
	   </div>
   </div>              
</div>

<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><i class="icon_documents_alt"></i>Product List </li> <!--<a href=""survey-list.php></a> -->
                 
                </ol>
            </div>
        </div>
        <!-- page start-->
        
        <div class="pricing-sec">
		
		<div class="container">
			
			<div class="tab-content">
				<div id="monthly" class="tab-pane fade in active">
					<div class="row">
						<div class="col-sm-12">
						
						    <div class="pricing-card col-sm-8">
							<form action="proceed_payment.php" method="post">
								<h4 class="pricing-title">Billing Details</h4>
									<div class="card">
                                                    <div class="card-body">
													
                                                     <?php 
													      $userinfo=mysqli_query($conn,"SELECT user_id,name,mobile,email,company_name,registered_as FROM users WHERE user_id='".$_SESSION['user_id']."'");
														   $userdata=mysqli_fetch_object($userinfo);
													 ?>
													 
													 <div class="dedoooseSignupForm">
							
							 <input type="hidden" name="userID" value="<?=$_SESSION['user_id']?>">
							  <input type="hidden" name="client_id" value="<?=$_SESSION['client_id']?>">
							  <input type="hidden" name="subscriptionID" value="<?=$subscriptionID?>">
								<div class="form-group">
									<label for="txtReferredBy">Billing Name</label> <span style="color:red;">*</span>
									<span class="formControlSpan"><input type="text" required class="form-control" id="form_fname" name="name" placeholder="Enter your name" value="<?=$userdata->name ?>"></span>
									<span id="fname_error_message" style="color:red;"></span>
								</div>
								<div class="form-group">
									<label for="txtEmailAddress">Email ID <span style="color:red;">*</span></label>
									<span class="formControlSpan"><input type="text" required class="form-control" id="email" name="email" placeholder="Enter your Email" value="<?=$userdata->email ?>" ></span>
								</div>
								<div>
								<div class="form-group" style="width:45%; float:left">
									<label for="txtUsername">Country</label> <span style="color:red;">*</span>
									<span class="formControlSpan">
										<select class="form-control" name="country" required id="country" placeholder="Country *">
											<option value="">Select Country </option>
											<?php $sqlcountry=mysqli_query($conn,"select country_id,country_name from country order by country_name asc");
                                            while($datacountry=mysqli_fetch_object($sqlcountry))
											{?>
										    <option value="<?=$datacountry->country_id?>"><?=$datacountry->country_name?></option>
											<?php } ?>
										</select>
									</span>
								</div>
								<div class="form-group" style="width:50%; float:right">
									<label for="txtUsername">State</label> <span style="color:red;">*</span>
									<span class="formControlSpan">
										<select class="form-control" name="state" required id="state" placeholder="State *">
											<option value="">Select State </option>
											<?php $sqlstate=mysqli_query($conn,"select state_id,state_name from states order by state_name asc");
                                            while($datastate=mysqli_fetch_object($sqlstate))
											{?>
										    <option value="<?=$datastate->state_id?>"><?=$datastate->state_name?></option>
											<?php } ?>
										</select>
									</span>
								</div>
								</div>
								<div class="form-group">
									<label for="txtUsername">Billing Address</label> 
									<span class="formControlSpan">
									<textarea class="form-control"  name="address" id="form_address" rows="3"></textarea>
									</span>
								</div>
								
								<!----------------End logo upload------------------->
								
							
						</div>
                                                    </div><!-- end card body -->
                                                </div>
								
							</div>
							<div class="pricing-card col-sm-4">
							 <?php $subscriptioninfo=mysqli_query($conn,"SELECT * FROM pm_subscriptions WHERE SubscriptionID='".$subscriptionID."'");
										$subscriptiondata=mysqli_fetch_object($subscriptioninfo);
										$subplan=json_decode($subscriptiondata->Description,TRUE);
							?>
								<h5 class="pricing-title"><?=$subscriptiondata->SuscriptionType?> Subscription ($<?=round($subscriptiondata->Price,0)?>)</h5>				
                                  <ul>
								  <?php $i=0;
										foreach($subplan as $key => $value)
										{
										 if($i>2) {	
									   ?>
									   
									<li> <?php echo  $key; ?> <?=$value?></li>
										 <?php } $i++;} ?>
								</ul>
								 <input type="hidden" name="TotalAmount" value="<?=round($subscriptiondata->Price,0)?>">
								<button type="submit" class="btn btn-primary btn-lg buy-btn" disabled id="register" name="proceeds" style="float:right">Confirm & Proceeds</button>
								<?php $subscriptionData=subscription_services($conn,$_SESSION['user_id']);
								
								?>
							</div>
							</form>
						</div>
						
					</div>
				</div>
				
			</div>
		</div>
	</div>

        <!-- page end-->
    </section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>
<!--
<link href="<?=base_url();?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?=base_url();?>assets/sweetalerts/sweetalert2.all.min.js"></script> -->
<?php 
	if($_SESSION['ISMEMORYFULL']){ ?>
		<script>
		Swal.fire(
		  'Storage Full',
		  'Kindly renew the storage.',
		  'error'
		)
		</script>
	<?php	
	}
?>
<script type="text/javascript">
    $(document).ready(function() {
        // Fetch districts based on selected state
        $('#country').change(function() {
            var stateId = $(this).val();
            $.ajax({
                url: 'getStates.php',
                method: 'POST',
                data: { country_id: stateId },
                success: function(data) {
                    $('#state').html(data);
                }
            });
        });
    });
	</script>
	<script type="text/javascript">
		$(function() {
			$("#fname_error_message").hide();
			var error_fname = false;


			
				var pattern = /^[a-zA-Z ]*$/;
				var fname = $("#form_fname").val().trim();
				var email = $("#email").val();

				if (pattern.test(fname) && fname !== '') {
					$("#fname_error_message").hide();
					if (email !== '') {
						$('#register').prop("disabled", false);
					} else {
						$('#register').prop("disabled", true);
					}
				} else {
					$("#fname_error_message").html("First name should contain only letters and white space.");
					$("#fname_error_message").show();
					$('#register').prop("disabled", true);
					error_fname = true;
				}
			
		});
	</script>