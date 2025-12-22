<?php include_once('includes/config.php'); ?>
<?php define("title","Add Form | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php 
if(isset($_REQUEST['proceeds']))
{
	$name=$_REQUEST['name'];
	$email=$_REQUEST['email'];
	$country=$_REQUEST['country'];
	$state=$_REQUEST['state'];
	$address=$_REQUEST['address'];
	$user_id=$_REQUEST['userID'];
	$client_id=$_REQUEST['client_id'];
	$subscriptionID=$_REQUEST['subscriptionID'];
	$TotalAmount=$_REQUEST['TotalAmount'];
		
$sqlorder=mysqli_query($conn,"insert into pm_orders set  UserID='".$user_id."',client_id='".$client_id."',SubscriptionID='".$subscriptionID."',TotalAmount='".$TotalAmount."',BillingName='".$name."',BillingEmail='".$email."',BillingAddress='".$address."',BillingCountryID='".$country."',BillingStateID='".$state."'");
$orderid=mysqli_insert_id($conn);

}

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
	$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".getRealIpAddr());
	$geoplugin_currencyCode=$xml->geoplugin_currencyCode;
	$geoplugin_currencyConverter=$xml->geoplugin_currencyConverter;
	$geoplugin_currencySymbol_UTF8=$xml->geoplugin_currencySymbol_UTF8;
	$geoplugin_request=$xml->geoplugin_request;
	$geoplugin_countryName=$xml->geoplugin_countryName;
	
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
			min-height: 40px;
		}

		#annual .pricing-card {
			min-height: 30px;
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
       <div class="mt-5 pricing-sec">
	
		<div class="container">
			
			<div class="tab-content">
				<div id="monthly" class="tab-pane fade in active">
					<div class="row">
					<div class="col-sm-12" style="justify-content:center; float:center">
					<header class="panel-heading">Checkout Details </header>
							<div class="pricing-card col-sm-8">
								
                                 <?php  $subscriptioninfo=mysqli_query($conn,"SELECT * FROM pm_subscriptions WHERE SubscriptionID='".$subscriptionID."'");
										$subscriptiondata=mysqli_fetch_object($subscriptioninfo);
										$subplan=json_decode($subscriptiondata->Description,TRUE);
										$descriptiontext=$subscriptiondata->SubscriptionName.' '.$subscriptiondata->SuscriptionType;
										?>
                                    	<input type="hidden" id="descriptiontext" name="descriptiontext" value="<?=$descriptiontext?>">
                      				<ul class="col-sm-6">
									
								  <?php $i=0;
										foreach($subplan as $key => $value)
										{
										 if($i>2 && $i<=6) {	
									   ?>
									   
									<li> <?=$value?></li>
										 <?php } $i++;} ?>
								</ul> 
									<ul class="col-sm-6">
									<li> Subsciption Type : <?=$subscriptiondata->SuscriptionType?> </li>
									<li> Support Type : <?=$subscriptiondata->SubscriptionName?> </li>
								  <?php $i=0;
										foreach($subplan as $key => $value)
										{
										 if($i>6) {
									   ?>
									   
									<li> <?=$value?></li>
										 <?php } $i++;} ?>
								</ul>								
							</div>
							<div class="pricing-card col-sm-4" >
								  <div style="border-radius: 5px; background: #ff9800; ">
                                  <form action="" method="post" style='color: #333333;'  >
								      <?php $convertedAmount=round($TotalAmount*$geoplugin_currencyConverter,0); ?>
									  <input type="hidden" id="userID" name="userID" value="<?=$_SESSION['user_id']?>">
									  <input type="hidden" id="client_id" name="client_id" value="<?=$_SESSION['client_id']?>">
									  <input type="hidden" id="subscriptionID" name="subscriptionID" value="<?=$subscriptionID?>">
									  <input type="hidden" id="name" name="name" value="<?=$name?>">
									  <input type="hidden" id="email" name="email" value="<?=$email?>">
									  <input type="hidden" id="orderID" name="orderID" value="<?=$orderid?>">
									  <input type="hidden" id="AmountInUSD" name="AmountInUSD" value="<?=$TotalAmount?>">
									 <div style="text-align:center; padding:10px">
									 <h3 style="font-weight:700">Total Amount: $<?=$TotalAmount?> USD </h3>
									  <input type="hidden" id="currencyCode" name="currencyCode" value="<?=$geoplugin_currencyCode?>">
									  <input type="hidden" id="conversionRate" name="conversionRate" value="<?=$geoplugin_currencyConverter?>">
									  <input type="hidden" id="paidAmount" name="paidAmount" value="<?=$convertedAmount?>">
									  <input type="hidden" id="ipAddress" name="ipAddress" value="<?=$geoplugin_request?>">
									  <input type="hidden" id="paymentCountry" name="paymentCountry" value="<?=$geoplugin_countryName?>">
									 <span style="font-size:30px; font-weight:bold; padding: 10px; "><?=$geoplugin_currencySymbol_UTF8?><?=$convertedAmount?></span></br>
									 </br>
									 <input type="button" class="btn btn-primary text-end" name="btn" id="btn" value="Pay Now" onclick="pay_now()" />
									 </div>
																	
									  
								
							</form>
							</div>
							</div>
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