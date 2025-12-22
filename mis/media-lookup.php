<?php include_once('includes/config.php'); ?>
<?php include_once('s3bucket/function_Upload_file.php'); ?>
<?php define("title", "Media Lookup | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
if (isset($_POST['submit'])) {
    $survey_id = $_REQUEST['survey_id'];
    $clientId = $_SESSION['client_id'];
    $filename = $_FILES['lookupfile']['name'];
    $tmp_name = $_FILES['lookupfile']['tmp_name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
	$allowedExtensions = ['zip'];
    $surveyUniqueId = $survey_id; // Assuming $survey_id is unique and correct

    $clientUniqueId = "C" . $clientId;
    $file_name = $surveyUniqueId . "." . $ext;
	if(in_array($ext, $allowedExtensions)){
		if ($survey_id != "" && $file_name != "") {
			// S3 Key (path in the bucket)
			$bucket = 'mquaddata';
			$s3Key = 'media_lookups/' . $clientUniqueId . '/' . $file_name;
			$destination = 'media_lookups/' . $clientUniqueId . '/' . pathinfo($file_name, PATHINFO_FILENAME);

			// Check if the folder exists
			$existingFiles = $s3Client->listObjectsV2([
				'Bucket' => $bucket,
				'Prefix' => 'media_lookups/' . $clientUniqueId . '/' . $surveyUniqueId . '/',
			]);

			// Delete existing files in the folder
			if (isset($existingFiles['Contents'])) {
				foreach ($existingFiles['Contents'] as $content) {
					try {
						$s3Client->deleteObject([
							'Bucket' => $bucket,
							'Key'    => $content['Key'],
						]);
						// echo "Existing file deleted: " . $content['Key'] . "\n";
					} catch (S3Exception $e) {
						echo $e->getMessage() . "\n";
					}
				}
			}

			// Upload the zip file to S3
			try {
				$result = $s3Client->putObject([
					'Bucket' => $bucket,
					'Key'    => $s3Key,
					'SourceFile' => $tmp_name,
				]);

				$zipFileName = 'mquaddata/media_lookups/' . $clientUniqueId . '/' . $file_name;
				if ($zipFileName) {
					// echo "Zip file uploaded successfully. URL: " . $result['ObjectURL'] . "\n";
				} else {
					// echo "File not unzip";
				}

				// Extract and upload files
				$finalstatus = extractAndUpload($bucket, $s3Key, $destination);

				if ($finalstatus) {
					if (file_exists($tmp_name)) {
						unlink($tmp_name);
					}
					$_SESSION['status'] = "Media File Uploaded Successfully";
					$_SESSION['status_code'] = "success";
					echo "<script>window.location.href='survey-list.php';</script>";
				} else {
					$_SESSION['status_error'] = "Something went wrong!!";
					$_SESSION['status_error_code'] = "error";
				}
			} catch (S3Exception $e) {
				echo $e->getMessage() . "\n";
			}
		} else {
			$_SESSION['status_error'] = "Please select the file!!";
			$_SESSION['status_error_code'] = "error";
		}
	}else {
        $_SESSION['status_error'] = "Invalid file type!!";
        $_SESSION['status_error_code'] = "error";
    }
}
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Form</li>
                        <li class="breadcrumb-item" aria-current="page"><i class="fa fa-file-archive-o"></i>Media Lookup</li>
                    </ol>
                </nav>
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

                                <div class="row mb-3">
                                    <label for="cname" class="control-label col-lg-2">Lookup Media File (Zipped): <span style="color:red;">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="file" name="lookupfile" required id="lookupfile" class="form-control" accept=".zip" required />
                                        <span style="color:red;">Choose ZIP containing Audio, Video, or Image Files to Upload</span></br>
                                        <span style="color:red;font-weight: bold;" id="fileError"><span>
                                        
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-12  text-end">
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
<?php if (isset($_SESSION['status_error']) && $_SESSION['status_error'] != '') { ?>
    <script>
        swal.fire({
            title: "<?php echo $_SESSION['status_error']; ?>",
            icon: "<?php echo $_SESSION['status_error_code']; ?>",
            confirmButtonColor: '#449A97',
            confirmButtonText: 'Ok'
        });
    </script>
<?php unset($_SESSION['status_error']);
}  ?>
<script>
    $('#lookupfile').bind('change', function() {

        //this.files[0].size gets the size of your file.
        if (this.files[0].size > 10000000000) {
            //alert("Please upload file less than 2MB. Thanks!!");
            $("#fileError").html("Please upload file less than 10MB. Thanks!!");
            $(this).val('');
        } else {
            $("#fileError").html("");
        }
        //alert(this.files[0].size);

    });
</script>
<script>
    //Media lookup validation
    $("body").on("click", "#btnUpload", function() {
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