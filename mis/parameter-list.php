<?php include_once('includes/config.php'); ?>
<?php define("title","List parameter | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>


<?php
	$qry='';
	if(isset($_REQUEST['search'])){
		if(isset($_REQUEST['pname']) && $_REQUEST['pname']!=''){
			$qry=" and parameter_name like '%".$_REQUEST['pname']."%' ";
		}
	}
?>

<?php

    //pagination
    $per_page=10;  
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page_url = "?";
    $page_url = isset($_GET['id'])? $page_url."user_type_id=".$_GET['id']:$page_url;
    $page_url = isset($_GET['pname'])? $page_url."&name=".$_GET['pname']:$page_url;
    $page_url = isset($_GET['search'])? $page_url."&search=".$_GET['search']:$page_url;
                
    $page=0;
    $current_page=1;
    if(isset($_GET['page'])){
        $current_page=intval($_GET['page']);
        $page=($current_page-1)*$per_page;
    }
	$query ="select * from parameters where status='0' $qry ";
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
		<div class="add-button-bg">
				<a href="add-parameter.php" class="btn btn-fixed-circle" title="Add Parameter"><i class="fa fa-plus"></i></a>
			</div>
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>
                        <li><i class="fa fa-user" aria-hidden="true"></i>Parameter</li>
                       <li><i class="icon_documents_alt"></i>List parameter</li>
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
										<input type="text" class="form-control" name="pname" value="<?=@$_REQUEST['pname']?>" placeholder="Parameter Name"></input>
									</div>
									<div class="form-group col-md-2" style="margin-bottom: 1rem!important;">
										<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
									</div>	
								</div>
                            </form>
                        </div>
						<section class="panel">
						<header class="panel-heading">Parameter list : <?=$total_record?>
						</header>
                        <table class="table table-striped">
                            <thead>
							<tr>
                                <th class="">S.No</th>
                                <th class="">Parameter Name</th>
								<th class="">Parameter Value</th>
								<th class="">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
							$_SESSION['query']="select * from parameters where status='0' $qry ";
							
							$sql="select parameter_id,parameter_name,parameter_value from parameters where status='0' $qry limit $page,$per_page";
							$getsql = mysqli_query($conn,$sql);
                            $sn=1+$page;
							while ($user = mysqli_fetch_array($getsql)) { ?>
                                <tr>
                                    <td><?= $sn++; ?></td>
                                    <td><?= ucfirst($user['parameter_name']) ?></td>
									<td><?= $user['parameter_value'] ?></td>
									<td><a href="edit-parameter.php?pid=<?=$user['parameter_id'];?>" class="btn btn-primary btn-sm" >Edit</a></td>
									
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
				 <div class="">
					<?php
					$_SESSION['header_column']="Parameter Name,Parameter Value";
					$_SESSION['db_column']="parameter_name,parameter_value";
					?>
				 <div class=" col-md-2 " style="margin-bottom: 0rem!important; padding-top: 40px">
				  <a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
				 </div>
				 </div>
			</div>
			
			
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

<?php include_once('includes/footer.php'); ?>