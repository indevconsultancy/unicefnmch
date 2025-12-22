<div class="text-end footer-center text-end" style="margin-top:20px;">
	<div class="credits">
		Technology Partner: <a href="https://www.indevconsultancy.com/" target="_blank" class="text-white"> Indev Consultancy Pvt Ltd</a>
	</div>
</div>
</section>

<!-- container section end -->
<!-- javascripts -->

<script src="<?= base_url(); ?>js/jquery-3.7.1.js"></script>
<script src="<?= base_url(); ?>js/jQuery-UI-1.13.js"></script>
<script src="<?= base_url(); ?>js/bootstrap.bundleV5-3.min.js"></script>

<!-- nice scroll -->
<script src="<?= base_url(); ?>js/jquery.scrollTo.min.js"></script>
<!-- <script src="<?= base_url(); ?>js/jquery.nicescroll.js" type="text/javascript"></script> -->
<!--custome script for all page-->
<script src="<?= base_url(); ?>js/jquery.sumoselect.min.js"></script>
<script src="<?= base_url(); ?>js/scripts.js"></script>
<script src="<?= base_url(); ?>js/select2.min.js"></script>

<link href="<?= base_url(); ?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>assets/sweetalerts/sweetalert2.all.min.js"></script>
<script src="<?= base_url(); ?>js/jquery.toast.min.js"></script>
<script src="<?= base_url(); ?>js/purify.min.js"></script>
<script>
	$('.check_multiselect').SumoSelect({
		selectAll: true,
		search: true,
	});
	$(document).ready(function() {
		$('.select2').select2();
	});
	$('#sidebar > ul > li.sub-menu').each(function() {
		$(this).addClass('open');
	})
	$(window).on('load', function() {
		$("#pre-load").delay(1000).fadeOut(500);
	})

	var position = $(".credits").offset().top;

	if (position < 1024) {
		$(".credits").addClass('pfixed');
	}

	function customAlert(msg, icon = 'success') {
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
	/*
	function throttle(func, limit) {
	    let inThrottle;
	    return function() {
	        const args = arguments;
	        const context = this;
	        if (!inThrottle) {
	            func.apply(context, args);
	            inThrottle = true;
	            setTimeout(() => inThrottle = false, limit);
	        }
	    }
	}

	// Usage
	window.addEventListener('scroll', throttle(function() {
	    console.log('Scroll event triggered');
	}, 200));
	*/
</script>
<!--<script>
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  });
</script>-->

</body>

</html>