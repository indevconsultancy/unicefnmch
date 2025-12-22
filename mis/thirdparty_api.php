<?php include_once('includes/config.php'); ?>
 <?php define("title","List API Access | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php 
if($_SESSION['role_id']==7){
	if(!isset($_SERVER['HTTP_REFERER'])){
		echo "<script>alert('Sorry, You Are Not Allowed to Access This Page');</script>";
		echo "<script>window.location.href='dashboard.php'</script>";
		exit;	
	}
}
?>
<?php
	
	$qry='';
	if (isset($_REQUEST['search'])){
		 if (isset($_REQUEST['name']) && $_REQUEST['name']!='') {
			$qry .= " AND thirdparty.name like '%" . $_REQUEST['name'] . "%'";
			}
	}
?>
<?php

    //pagination
    $per_page=10;
                    
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page_url = "?";
    $page_url = isset($_GET['id'])? $page_url."user_type_id=".$_GET['id']:$page_url;
    $page_url = isset($_GET['name'])? $page_url."&name=".$_GET['name']:$page_url;
    $page_url = isset($_GET['search'])? $page_url."&search=".$_GET['search']:$page_url;
                
    $page=0;
    $current_page=1;
    if(isset($_GET['page'])){
        $current_page=intval($_GET['page']);
        $page=($current_page-1)*$per_page;
    }
	$query="select thirdparty.id,thirdparty.name,thirdparty.description,thirdparty.accesstoken ,thirdparty.refreshtoken from thirdparty WHERE thirdparty.del_action='N' $qry order by thirdparty.id DESC";
	$get_query=mysqli_query($conn,$query);
    $total_record=mysqli_num_rows($get_query);
    $total_pages=ceil($total_record/$per_page);
?>
<?php
	if(!empty($_REQUEST['delid'])){
		 $did=$_GET['delid'];
		 $delsql=mysqli_query($conn,"UPDATE thirdparty SET del_action='Y' where id='".$did."'");
		 if($delsql){
			 echo"<script>alert('Deleted Successfully...')</script>";
			 
		 }
		 echo"<script>window.location.href='thirdparty_api.php'</script>";
			 
	}
?>	

    <!--main content start-->
    <section id="main-content">
		<section class="wrapper">
			
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                       <!-- <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li> -->
                        <li><i class="fa fa-user" aria-hidden="true"></i>API Access</li>
                       <li><i class="fa fa-list"></i>List Third party</li>
                    </ol>
                </div>
            </div>
            <!-- page start-->
            <div class="row">
                <div class="col-sm-12">                  
						<div class="container-fluid">
                            <form class="form-inline" method="get" role="form">
							<div class="row filter_css clearfix">
                                
									<div class="form-group col-md-10"style="margin-bottom: 1rem!important;">
                                    <input type="text" class="form-control" name="name" placeholder="Name"></input>
                                </div>
								<div class="form-group col-md-2"style="margin-bottom: 1rem!important;">
									<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
								</div>	
								</div>
                            </form>
                        </div>
                        <div class="row-bottom text-end">
							<form enctype="multipart/form-data" action="" method="post">
														 <a class="btn btn-md btn-primary" href="add_thirdparty_api.php"><i class="fa fa-plus"></i> Add ThirdParty</a>
																		
							</form>
						</div>
						<section class="panel">
						<header class="panel-heading">Total Access: <?=$total_record?>
						</header>
                        <table class="table table-striped">
                            <thead>
							<tr>
                                <th class="">S.No</th>
                                <th class="">Third party name</th>
                                
                                <th class="">Description</th>
                               
								<th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
							$_SESSION['query']="select * from thirdparty where thirdparty.del_action='N' order by thirdparty.id DESC";
							$sql="select * from thirdparty where thirdparty.del_action='N' $qry order by thirdparty.id DESC limit $page,$per_page";
							$getsql = mysqli_query($conn,$sql);
                            $sn=1+$page;
							while ($user = mysqli_fetch_array($getsql)) { ?>
                                <tr>
                                    <td><?= $sn++; ?></td>
                                    <td><?= ucfirst($user['name']) ?></td>
									 
                                    <td><?= $user['description'] ?></td>
									 
									 <td>
									  <a href="thirdparty-profile.php?cid=<?=$user['id'];?>" class="btn btn-sm btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>
									  <a href="?delid=<?=$user['id'];?>" onclick="return confirm('Do you want to delete ?');"; class="btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
					$_SESSION['header_column']="Third party name,Description, Access Token, Refresh Token";
					$_SESSION['db_column']="name,dscription,accesstoken, refreshtoken";
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