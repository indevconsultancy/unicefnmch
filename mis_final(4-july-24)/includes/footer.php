<?php if($_SESSION['SUBSCRIPTIONEXPIRED']==true){ ?>
<div class="subscription-pop">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-content">
			<div class="modal-body text-center p-2">
			   

				<div class="mt-2">
					<h4 class="mb-3">  <b> Welcome <?=ucfirst($_SESSION['name'])?></b></h4>
					<br/>
					<p class="mb-4"> Thank you for registering on MQUAD! We are thrilled to have you join our community. MQUAD is a premium service designed to provide you with exclusive access to our comprehensive portal, filled with valuable resources and tools to enhance your experience.</p>
					<p class="mb-4"> Please note that MQUAD is a paid subscription service. To continue enjoying uninterrupted access to our portal, we encourage you to buy one of our subscription plans.</p>
					<div class="hstack gap-2 justify-content-center">
						<a href="../pricing-new.php" class="btn btn-primary">Buy Plan</a>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
<?php } ?>
<div class="text-right footer-center" style="margin-top:20px;" >
  <div class="credits">
    Technology Partner: <a href="https://www.indevconsultancy.com/" target="_blank"> Indev Consultancy Pvt Ltd</a>
  </div>
</div>
</section>
<!-- container section end -->
<!-- javascripts -->
<script src="<?=base_url();?>js/jquery.js"></script>
<script src="<?=base_url();?>js/jquery-ui-1.10.4.min.js"></script>

<script src="<?=base_url();?>js/bootstrap.min.js"></script>
<!-- nice scroll -->
<script src="<?=base_url();?>js/jquery.scrollTo.min.js"></script>
<script src="<?=base_url();?>js/jquery.nicescroll.js" type="text/javascript"></script>
<!--custome script for all page-->
<script src="<?=base_url();?>js/jquery.sumoselect.min.js"></script>
<script src="<?=base_url();?>js/scripts.js"></script>
<script src="<?=base_url();?>js/select2.min.js"></script>

<link href="<?=base_url();?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?=base_url();?>assets/sweetalerts/sweetalert2.all.min.js"></script>
<script src="<?=base_url();?>assets/toast-plugin-jquery/jquery.toast.min.js"></script>
<script>
$('.check_multiselect').SumoSelect({
  selectAll:true,
  search:true,
});
  $(document).ready(function() {
       $('.select2').select2();
   });
$('#sidebar > ul > li.sub-menu').each(function(){
		$(this).addClass('open');
})
$(window).on('load', function() {
	$("#pre-load").delay(1000).fadeOut(500);
})
	
var position =$( ".credits" ).offset().top;

	if(position < 1024){
		$( ".credits" ).addClass('pfixed');
	}
	
function customAlert(msg,icon='success'){
	const Toast = Swal.mixin({
	  toast: true,
	  position: 'top-end',
	  showConfirmButton: false,
	  timer: 3000,
	  timerProgressBar: true,
	  didOpen: (toast) => {
		toast.addEventListener('mouseenter', Swal.stopTimer)
		toast.addEventListener('mouseleave', Swal.resumeTimer)
	  }
	})

	Toast.fire({
	  icon: icon,
	  title: msg
	})
}
</script>


</body>
</html>