<?php include_once('includes/config.php'); ?>
<?php define("title", "Simple Random Sampling | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
$download = '';
$error = '';
if (isset($_POST['submit_data'])) {
	$drows = $_POST['drows'];
	$is_header = $_POST['is_header'];
	if ($is_header == "1") {
		$drows = $drows + 1;
	}
	if ($_FILES['randomSampling']['name'] != "") {

		$uploaddir = "sampling/";
		$fname = str_replace(" ", "-", $_FILES['randomSampling']['name']);
		$uploadfile = $uploaddir . basename($fname);

		unlink("sampling/" . $_FILES['randomSampling']['name']);
		move_uploaded_file($_FILES['randomSampling']['tmp_name'], $uploadfile);
		//mysqli_query($conn,"INSERT INTO random_sampling SET file_type='csv', file_name='".$fname."' " );
		//Add New Column in csv 
		$newCsvData = array();
		if (($handle = fopen($uploadfile, "r")) !== FALSE) {
			$sn = 1;
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				if ($sn == 1) {
					//$data[] = 'sss_column';
					array_unshift($data, "SN");
					$newCsvData[] = $data;
				}
				if ($sn > 1) {
					//$data[] = $sn;//'New Column';
					array_unshift($data, $sn);
					$newCsvData[] = $data;
				}
				$sn++;
			}
			fclose($handle);
		}

		$handle = fopen($uploadfile, 'w');

		foreach ($newCsvData as $line) {
			fputcsv($handle, $line);
		}
		fclose($handle);

		//Creating File for download
		$fp = file($uploadfile);
		$fpcount = count($fp);
		for ($i = 1; $i <= $fpcount; $i++) {
			//echo "<br>";
			$ssarr[] = $i;
		}
		shuffle($ssarr);
		$newArray = array_slice($ssarr, 0, $drows, true);
		$arr = $newArray; //array("2","5");

		$table = fopen($uploadfile, 'r');
		$temp_table = fopen('sampling/sss_temp.csv', 'w');

		$sss = 1;
		while (($data = fgetcsv($table, 1000)) !== FALSE) {
			if ($sss > 1) {
				//if(reset($data) != $id){ // this is if you need the first column in a row
				//if(!in_array(reset($data),$arr)){
				if (in_array($data[0], $arr)) {
					//continue;
					fputcsv($temp_table, $data);
				}
			}

			if ($sss == 1) {
				fputcsv($temp_table, $data);
			}
			$sss++;

			//fputcsv($temp_table,$data);
		}
		fclose($table);
		fclose($temp_table);
		//$uploadfile = "sss_temp.csv"; //"sss_".$uploadfile;
		rename('sampling/sss_temp.csv', $uploadfile);
		$download =  '<a href="' . $uploadfile . '" class="btn btn-success">Download</a>';
		$_SESSION['download_sampling'] = '<a href="' . $uploadfile . '" class="btn btn-success">Download</a>';
		echo "<script>window.location.href='systematic_random_process.php';</script>";
	} else {
		$error = "Something went wrong!!";
	}
}
?>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-12 text-center">
						<?php if ($error != '') { ?>
							<div class="alert alert-danger" role="alert">
								<?= $error; ?>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						<?php } ?>
					</div>
				</div>
				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Sampling</li>
					<li><i class="fa fa-window-restore"></i>Simple Random Sampling</li>
				</ol>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">

				<section class="panel">
					<header class="panel-heading">Upload File</header>
					<div class="panel-body">

						<div class="form">

							<form class="form-validate form-horizontal" id="fileupload" method="post" enctype="multipart/form-data">
								<!--
                        <div class="form-group ">
                           <label for="cname" class="control-label col-lg-2">File Type: </label>
                           <div class="col-lg-10">
                              CSV <input type="radio" name="file_type"  value="csv"  /> &nbsp;&nbsp;&nbsp;
                              XLS <input type="radio" name="file_type"  value="xls"  /> 
                           </div>
                        </div>
                        -->

								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Upload file: <span style="color:red;">*</span></label>
									<div class="col-lg-10">
										<input type="file" name="randomSampling" id="file" required class="form-control file-input" onchange="return fileValidation()" accept=".csv" />
										<div id="file_type_error_message" style="color:red;">(Upload only .csv file) </div>
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Do you have a header in your file? </label>
									<div class="col-lg-10">
										<input type="radio" name="is_header" value="1" /> Yes
										<input style="margin: 4px;" type="radio" name="is_header" value="2" /> No
									</div>
								</div>
								<div class="form-group ">
									<label for="cname" class="control-label col-lg-2">Number of samples to be selected: <span style="color:red;">*</span></label>
									<div class="col-lg-5">
										<input type="number" name="drows" required placeholder="Number of samples to be selected." class="form-control" />
									</div>
								</div>

								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10 text-right">
										<button class="btn btn-primary pull-left" type="submit" id="btnUpload" name="submit_data">Submit</button>
										<?= $download; ?>
									</div>
								</div>
							</form>
						</div>
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
	function fileValidation() {
		var fileInput = document.getElementById('file');
		var filePath = fileInput.value;

		// Allowing file type
		var allowedExtensions = /(\.csv)$/i;
		if (!allowedExtensions.exec(filePath)) {
			// alert('Invalid file type');
			$("#file_type_error_message").html("Please select .csv file only").css("color", "red");
			fileInput.value = '';
			return false;
		} else {
			return true;
			//$("#file_type_error_message").html("valid file type.").css("color", "green");
		}
	}
</script>