<?php include_once('includes/config.php'); ?>

<?php include_once('includes/header.php'); ?>
<?php define("title","Add Third party | MQUAD");?>
<?php include_once('includes/left-sidebar.php'); ?>

<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateToken($userId, $secretKey)
{
    // Generate JWT token
    $payload = [
        'user_id' => $userId,
        'exp' => time() + (60 * 60), // Token expiration time (1 hour)
        'nbf' => time()
    ];
    return JWT::encode($payload, $secretKey, 'HS256');
}

function generateRefreshToken($userId, $secretKey)
{
    // Generate refresh token
    $payload = [
        'user_id' => $userId,
        'exp' => time() + (364 * 24 * 60 * 60), // Token expiration time (365 days)
        'nbf' => time() 
    ];
    return JWT::encode($payload, $secretKey, 'HS256');
}


if($_SESSION['role_id']==7){
	if(!isset($_SERVER['HTTP_REFERER'])){
		echo "<script>alert('Sorry, You Are Not Allowed to Access This Page');</script>";
		echo "<script>window.location.href='dashboard.php'</script>";
		exit;	
	}
}
	
	if(isset($_REQUEST['submit']))
	{
			$user_id=$_SESSION['user_id'];
			$name=$_REQUEST['name'];
			$description=$_REQUEST['description'];
						/*add jwt auth key in response
			*/
			$accesstoken = generateToken($user_id, JWT_SECRET_KEY_THIRD);
			// Generate refresh token
			$refreshtoken = generateRefreshToken($user_id, REFRESH_TOKEN_SECRET_KEY_THIRD);	
		

			$insertcontribute=mysqli_query($conn,"insert into thirdparty SET name='".$name."',description='".$description."',accesstoken='".$accesstoken."',refreshtoken='".$refreshtoken."',added_by='".$user_id."' ");
			
			
			if($insertcontribute){
				$_SESSION['success']='Your data has been successfully submitted';
				echo "<script>window.location.href='thirdparty_api.php'</script>";
			}else{
				$error='Somthing went wrong !!';
			}
				
    }
    ?>
<style>
  .statuserror {
  color: red;
  }
</style>
<!--main content start-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/additional-methods.js"></script>
<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><i class="fa fa-mobile" aria-hidden="true"></i>API Access</li>
          <li><i class="fa fa-plus"></i>Add Third party</li>
        </ol>
      </div>
    </div>
    <!-- page start-->
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">Add Third party</header>
          <div class="panel-body">
            <div class="form">
              <form class="form-validate form-horizontal" id="myForm" method="post" enctype="multipart/form-data">
				  <div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Name <span style="color:red">*</span></label>
                  <div class="col-lg-10"> <input class="form-control" name="name" type="text" />
                  </div>
                </div>
                <div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Description</label>
                  <div class="col-lg-10">
                    <input class="form-control" id="form_fname" name="description" required type="text" />
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10  text-right"> <button class="btn btn-primary" type="submit" name="submit">Submit</button> </div>
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
