<?php include_once('includes/config.php'); ?>
<?php define("title", "View Contact | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
$userid = $_GET['cid'];
$getdata = mysqli_query($conn, "SELECT `id`, `name`, `email`, `phone_number`, `subject`, `comments`, `created_at`, `status` FROM `contacts` WHERE  status=1 and contacts.id='$userid' ");
$data = mysqli_fetch_array($getdata);
?>
<style>
   .cls {
      color: white;
   }
</style>
<!--main content start-->
<section id="main-content">
   <section class="wrapper">
      <div class="row">
         <div class="col-lg-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><i class="icon_documents_alt"></i>Contact List</li>
                  <li class="breadcrumb-item" aria-current="page"><i class="fa fa-eye"></i></i> View Contact</li>
               </ol>
            </nav>
         </div>
      </div>
      <!-- page start-->
      <div class="row">
         <div class="col-md-12">
            <div class="row">
               <div class="col-md-4">
                  <div class="panel mb-0" style="padding:15px;">
                     <!-- <h4><span style="font-weight: bold; font-size:22px;">Total Form: <?= $count_data['total_survey']; ?></span></h4>-->
                     <hr>
                     <div class="profile-usermenu">
                        <ul class="nav">
                           <li>
                              <p style="color:#394A59;"><strong>Name: </strong><?php echo $data['name']; ?></p>
                           </li>
                           <hr>
                           <li>
                              <p style="color:#394A59;"><strong>Email: </strong><?php echo $data['email']; ?></p>
                           </li>
                           <hr>
                           <li>
                              <p style="color:#394A59;"><strong>Phone Number : </strong><?php echo $data['phone_number']; ?></p>
                           </li>
                           <hr>
                        </ul>
                     </div>
                  </div>
               </div>

               <!--start-->
               <div class="col-md-8">
                  <div class="profiles-data">
                     <ul class="nav nav-tabs">
                        <li class="nav-item">
                           <a class="nav-link active" data-bs-toggle="tab" href="#home">View Contact</a>
                        </li>
                     </ul>
                     <div class="tab-content">
                        <div id="home" class="tab-pane fade show active">
                           <div class="table-responsive mb-0 mt-2">
                              <section class="panel mb-0" style="padding:15px;">
                                 <div class="table-responsive">
                                    <div><span><b>Subject:</b> <?= $data['subject']; ?> </span></div>
                                    <hr>
                                    <div><span><b>Comments:</b> <?php echo $data['comments']; ?> </span></div>
                                 </div>
                              </section>
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