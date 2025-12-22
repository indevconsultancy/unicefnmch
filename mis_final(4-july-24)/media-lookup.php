<?php include_once('includes/config.php'); ?>
<?php define("title","Media Lookup | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
//error_reporting(1);
if(isset($_POST['submit'])){
	$survey_id=$_REQUEST['survey_id'];
	$clientId=$_SESSION['client_id'];
	$filename = $_FILES['lookupfile']['name'];
	$tmp_name = $_FILES['lookupfile']['tmp_name'];
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$surveyUniqueId = $survey_id;//getone($conn,"survey","unique_id","id",$survey_id);
	
	 $clientUniqueId="C".$clientId;
	 $file_name = $surveyUniqueId.".".$ext;
	if ($survey_id!="" && $file_name!=""){
		//echo $mediaPath = "medialookups/".$surveyUniqueId;
		$mediaPath = "medialookups/".$clientUniqueId."/".$surveyUniqueId ;
		
		//array_map('unlink', glob("$mediaPath/*.*"));
		if (!file_exists($mediaPath)) {
			 //mkdir($mediaPath, 0777, true);
			if (!mkdir($mediaPath, 0777, true)) {
				
				// $error='Something Went wrong!!';
				$_SESSION['status_error'] = "Folder not created";
				$_SESSION['status_error_code'] = "error";
			}else{
					mkdir($mediaPath, 0777, true);
					echo $mediaPath;
					
				$_SESSION['status_error'] = "Folder created";
				$_SESSION['status_error_code'] = "error";
			
			}
		}
		
		if(move_uploaded_file($tmp_name,$mediaPath."/".$file_name)){
			
		  $destination = $mediaPath."/".$file_name;
		
		 $zip_obj = new ZipArchive;
		  if($zip_obj->open($destination) === TRUE){
			  $zip_obj->extractTo($mediaPath);
				// $_SESSION['status'] = "Media File Uploaded Successfully";
				// $_SESSION['status_code'] = "success";
			  echo "<script>window.location.href='survey-list.php';</script>";
		  }else{
			$_SESSION['status_error'] = "Something went wrong!!";
			$_SESSION['status_error_code'] = "error";
		  }
		  
		}else{
			$_SESSION['status_error'] = "File not moved!!";
			$_SESSION['status_error_code'] = "error";
		  }
		
	}
}
?>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Form</li>
					<li><i class="fa fa-file-archive-o"></i>Media Lookup</li>
				</ol>
			</div>
		</div>
		<!-- page start-->  
		
		<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<header class="panel-heading">
					Media Lookup
				</header>
				
				<div class="panel-body">
					<div class="form">
						<form class="form-validate form-horizontal " id="register_form" method="post" enctype="multipart/form-data">
							
							<div class="form-group ">
							   <label for="cname" class="control-label col-lg-2">Lookup Media File (Zipped): <span style="color:red;">*</span></label>
							   <div class="col-lg-10">
								 <input type="file" name="lookupfile" required id="lookupfile" class="form-control" accept=".zip" required /> 
									<span style="color:red;font-weight: bold;" id="fileError"><span>
							   </div>
							</div>
						
							<div class="form-group">
							  <div class="col-lg-offset-2 col-lg-10  text-right">
								<button class="btn btn-primary  text-right" type="submit" id="btnUpload" name="submit">Submit</button>
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
<?php if(isset($_SESSION['status_error']) && $_SESSION['status_error']!=''){ ?>
<script>
	swal.fire({
		title: "<?php echo $_SESSION['status_error'];?>",
		icon:"<?php echo $_SESSION['status_error_code']; ?>",
		confirmButtonColor: '#449A97',
		confirmButtonText: 'Ok'
	});
</script>
<?php unset($_SESSION['status_error']);}  ?>
<script>
$('#lookupfile').bind('change', function() {

  //this.files[0].size gets the size of your file.
	if(this.files[0].size > 10000000000) {
		//alert("Please upload file less than 2MB. Thanks!!");
		$("#fileError").html("Please upload file less than 10MB. Thanks!!");
		$(this).val('');
	}else{
		$("#fileError").html("");
	}
  //alert(this.files[0].size);

});
</script>
<script>
 //Media lookup validation
  $("body").on("click", "#btnUpload", function () {
        var allowedFiles = [".zip"];
        var lookupfile = $("#lookupfile");
        var fileError = $("#fileError");
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(lookupfile.val().toLowerCase())) {
            fileError.html("Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.");
            return false;
        }
        fileError.html('');
        return true;
    });
	
 </script>