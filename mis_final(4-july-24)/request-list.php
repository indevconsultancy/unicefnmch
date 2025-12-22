<?php include_once('includes/config.php'); ?>
<?php define("title","Request Demo | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php
   $qry='';
   if (isset($_REQUEST['search'])){
   	if (isset($_REQUEST['name']) && $_REQUEST['name']!=''){
   		$qry .= " AND CONCAT(first_name, '' , last_name) like '%" . $_REQUEST['name'] ."%'";
   	}
   
    if (isset($_REQUEST['business_email']) && $_REQUEST['business_email']!='') {
   	$qry.=" AND request_demo.business_email like '%". $_REQUEST['business_email'] ."%'";
   		}
   }
   ?>
<?php
   //pagination
   $per_page=10;
                   
   $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   $page_url = "?";
   $page_url = isset($_GET['first_name'])? $page_url."&first_name=".$_GET['first_name']:$page_url;
   $page_url = isset($_GET['business_email'])? $page_url."&business_email=".$_GET['business_email']:$page_url;
   $page_url = isset($_GET['search'])? $page_url."&search=".$_GET['search']:$page_url;
               
   $page=0;
   $current_page=1;
   if(isset($_GET['page'])){
       $current_page=intval($_GET['page']);
       $page=($current_page-1)*$per_page;
   }
   
   $query="SELECT `request_id`, `first_name`, `last_name`, `company_name`, `job_title`, `business_email`, `created_at`, `status` FROM `request_demo` WHERE status=1 $qry";
   $get_query=mysqli_query($conn,$query);
   $total_record=mysqli_num_rows($get_query);
   $total_pages=ceil($total_record/$per_page); 
   ?>
<style>
   .panel-heading {
   background: #394a59;
   color: white;
   font-weight: unset;
   }
   .btn:not(:disabled):not(.disabled) {
   cursor: pointer;
   }
   .add-button-bg a {
   position: fixed;
   bottom: 54px;
   right: 50px;
   background: rgb(57,74,89);
   z-index: 99999;
   border-radius: 50%;
   width: 60px;
   height: 60px;
   color: #fff;
   line-height: 46px;
   font-size: 22px;
   transition: all .3s ease-in-out;
   }
   .add-button-bg a:hover{
   background: rgb(4 39 60);
   color: #ffffff;
   -webkit-transform: rotate(90deg);
   transform: rotate(90deg);
   box-shadow: 1px 1px 1px 17px rgb(255 192 192 / 28%);
   }
</style>
<!--main content start-->
<section id="main-content">
   <section class="wrapper">
  
      </div>
      <div class="row">
         <div class="col-lg-12">
            <ol class="breadcrumb">
               
               <li><i class="fa fa-user" aria-hidden="true"></i>Support Service</li>
               <li><i class="fa fa-list"></i>Request Demo </li>
            </ol>
         </div>
      </div>
      <!-- page start-->
      <div class="row">
         <div class="col-sm-12">
            <div class="container-fluid">
               <form class="form-inline" method="get" role="form">
                  <div class="row filter_css clearfix">
                     <div class="col-md-5" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
                        <input type="text" class="form-control" name="name" value="<?=@$_REQUEST['name']?>" placeholder="Name"></input> 
                     </div>
                     <div class="form-group col-md-5"style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
                        <input type="text" class="form-control" name="business_email" value="<?=@$_REQUEST['business_email']?>" placeholder="Business Email"></input>
                     </div>
                     <div class="form-group col-md-2"style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
                        <button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
                     </div>
                  </div>
               </form>
            </div>
            <section class="panel">
               <header class="panel-heading">Total Record:(s) <?=$total_record?>
               </header>
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th class="">S.No</th>
                        <th class=""> Name</th>
                        <th class="">Company name</th>
                        <th class="">Job Title </th>
                        <th class="">Business Email </th>
                        <th class="">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                        $_SESSION['query']="SELECT `request_id`, `first_name`, `last_name`, `company_name`, `job_title`, `business_email`, `created_at`, `status` FROM `request_demo` WHERE status=1";
                        $sql="SELECT `request_id`, `first_name`, `last_name`, `company_name`, `job_title`, `business_email`, `created_at`me ,`status`  from request_demo WHERE status=1 $qry ";
                        $getsql = mysqli_query($conn,$sql);
                                             $sn=1+$page;
                        while ($user = mysqli_fetch_array($getsql)) { ?>
                     <tr>
                        <td><?= $sn++; ?></td>
                        <td><?=$user['first_name'] ?> <?=$user['last_name'] ?></td>
                        <td><?= $user['company_name'] ?></td>
                        <td><?= $user['job_title'] ?></td>
                        <td><?= $user['business_email'] ?></td>
                        <td>
                           <a href="user-profile.php?cid=<?=$user['id'];?>" class="btn btn-sm btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </td>
                     </tr>
                     <?php
                        }
                        ?>
                  </tbody>
               </table>
            </section>
         </div>
      </div>
      <div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
         <div class="col-md-10">
            <div class="d-flex align-items-center justify-content-between" id="pagination">
               <?=paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>                               
            </div>
         </div>
         <?php
            $_SESSION['header_column']="First Name,Last Name,Company name,Job Title,Business Email";
            $_SESSION['db_column']="first_name,last_name,company_name,job_title,business_email";
            ?>
         <div class=" col-md-2 " style="margin-bottom: 0rem!important; padding-top: 5px">
            <a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
         </div>
      </div>
      <!-- page end-->
   </section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>