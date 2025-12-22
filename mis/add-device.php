<?php include_once('includes/config.php'); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

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
          <li><i class="fa fa-mobile" aria-hidden="true"></i>Device Management</li>
          <li><i class="fa fa-plus"></i>Add Device</li>
        </ol>
      </div>
    </div>
    <!-- page start-->
    <div class="row">
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">Add Device</header>
          <div class="panel-body">
            <div class="form">
              <form class="form-validate form-horizontal" id="myForm" method="post" enctype="multipart/form-data">
				  <div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Device Id <span style="color:red">*</span></label>
                  <div class="col-lg-10"> <input class="form-control" name="device_id" type="text" />
                  </div>
                </div>
                <div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Device Name <span style="color:red">*</span></label>
                  <div class="col-lg-10">
                    <input class="form-control" id="form_fname" name="device_name" required type="text" />
                  </div>
                </div>
               <div class="form-group ">
                  <label for="cname" class="control-label col-lg-2">Version<span style="color:red">*</span></label>
                  <div class="col-lg-10"> <input class="form-control" name="version" type="text" />
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-lg-offset-2 col-lg-10  text-end"> <button class="btn btn-primary" type="submit" name="submit">Submit</button> </div>
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
