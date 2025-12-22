<?php include_once('includes/config.php'); ?>
<?php define("title","Parameters | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="fa fa-home"></i><a href="index.html">Home</a></li>
					<li><i class="fa fa-users" aria-hidden="true"></i>Setting</li>
					<li><i class="fa fa-plus"></i>Parameters</li>
				</ol>
			</div>
		</div>
		<!-- page start-->  
		<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<header class="panel-heading">
					Parameters Form
				</header>
				
				<?php
					if(isset($_POST['submit'])){
						$parameter_names = $_POST['parameter_name'];
						$parameter_values = $_POST['parameter_value'];
						foreach($parameter_names as $key=>$parameter_name){
							$parameter_value = $parameter_values[$key];
							$insert = mysqli_query($conn,"INSERT INTO parameters SET parameter_name='".$parameter_name."', parameter_value='".$parameter_value."' ");
						}
						if($insert){
							echo "<script>alert('Parameters Added Successfully..'); window.location.href='parameter-list.php'</script>";
						}
					}
				?>
				
				<div class="panel-body">
					<div class="form">
						<form class="form-validate form-horizontal " id="register_form" method="post" enctype="multipart/form-data">
							
							<table class="table" id="parameter">
								<thead>
								  <tr>
									<th>Parameter Name</th>
									<th>Parameter Value</th>
									<th>Action</th>
								  </tr>
								</thead>
								
								  <tr>
									<td><input type="text" class="form-control" placeholder="Parameter Name" name="parameter_name[]" required ></td>
									<td><input type="text" class="form-control" placeholder="Parameter Value" name="parameter_value[]" required ></td>
									<td>
										<button type="button" class="btn btn-success  tr_clone_add">Add</button>
										<a href="javascript:" class="btn btn-danger delete">Remove</a>
									</td>
								  </tr>
								</tbody>
							</table>
							<div class="form-group">
							  <div class="col-lg-offset-2 col-lg-10  text-right">
								<button class="btn btn-primary  text-right" type="submit" name="submit">submit</button>
							  </div>
							</div>
						</form>
					</div>
			</section>
			</div>
		</div>
		<!-- page end-->
	</section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>

<script>


var table = $( '#parameter' )[0];
$( table ).delegate( '.tr_clone_add', 'click', function () {
	var thisRow = $( this ).closest( 'tr' )[0];
	$( thisRow ).clone().insertAfter( thisRow ).find( 'input:text' ).val( '' );
});

$(document).on("click", ".delete", function() {
	$(this).parent().parent().remove(); 
});

</script>