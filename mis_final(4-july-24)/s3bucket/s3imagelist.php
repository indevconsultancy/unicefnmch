<?php 
require '../includes/config.php';
require 's3_bucket_config.php';

function fileDisplay($s3Client,$mediaKey){
	$cmd = $s3Client->getCommand('GetObject', [
		'Bucket' => 'mquaddata',
		//'Key'    => 'mquad_img/img_1675686028.jpg'
		'Key'    => $mediaKey //foldername/imagename
	]);
	$request = $s3Client->createPresignedRequest($cmd, '+20 seconds');
	echo $signedUrl = (string)$request->getUri();
}

 ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>S3 Bucket Image List</title>
  </head>
  <body>
    <div class="container">
		<div class="wrapper mt-2">
			<h2 class="text-center">Image List S3 bucket</h2>
			<div class="col-md-12">
				<div class="card">
					<?php 
						if(!empty($_SESSION['statusMsg'])){ ?>
							
							<div class="alert alert-success alert-dismissible fade show" role="alert">
							  <?php echo $_SESSION['statusMsg'];?>
							  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
					<?php } ?>
					<div class="card-header">
						Image List
					<a href="createzip1.php" class="btn btn-primary">Upload Image</a>	
					</div>
					<div class="card-body">
						<table class="table">
							<thead>
								<tr>
								  <th scope="col">S.No</th>
								  <th scope="col">Name</th>
								  <th scope="col">Image</th>
								  <th scope="col">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php 
								$sqlselect=mysqli_query($conn,"select id,name,image from upload_data order by id desc");
								$i=1;
								while($data=mysqli_fetch_object($sqlselect)){
							?>
								<tr>
									<th scope="row"><?=$i;?></th>
									<td><?=$data->name?></td>
									<td><?php 
									$imgpathkey = 'mquad_img/'.$data->image;
									$zippathkey='mquad_img/C91/801/'.$data->image;
									?>
										<img src="<?php fileDisplay($s3Client,$imgpathkey);?>" height="100" >
									</td>
								  <td><a href="" class="btn btn-primary">View</a></td>
								</tr>
							<?php $i++; } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>	

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>