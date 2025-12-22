<?php include_once('includes/config.php'); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php 
 $client_id=$_SESSION['client_id'];
?>
<?php 
if($_SESSION['role_id']==7){
	if(!isset($_SERVER['HTTP_REFERER'])){
		// redirect them to your desired location
		echo "<script>alert('Sorry, You Are Not Allowed to Access This Page');</script>";
		echo "<script>window.location.href='dashboard.php'</script>";
		exit;
	}
}	
 ?>

<?php
	$qry='';
	if (isset($_REQUEST['search'])){
		  if(isset($_REQUEST['name']) && ($_REQUEST['name']!="")){
           $qry.=" AND users.name like '%".$_REQUEST['name']."%' ";
		  }
		if (isset($_REQUEST['user_type_id']) && $_REQUEST['user_type_id']!=''){
			$qry .= " AND users.role_id='" . $_REQUEST['user_type_id'] ."'";
		}
		if(isset($_REQUEST['email']) && $_REQUEST['email']!='') {
            $qry.= " AND users.email='".$_REQUEST['email']."'";
        }
		if(isset($_REQUEST['status']) && $_REQUEST['status']!='') {
            $qry.= " AND users.status='".$_REQUEST['status']."'";
        }
		/* if (isset($_REQUEST['fdate']) && isset($_REQUEST['tdate'])) {  
			$d1 = date('Y-m-d h:i:s', strtotime($_REQUEST['fdate']));
			$d1 = $_REQUEST['fdate'] . ' 00:00:00';
			$d2 = date('Y-m-d h:i:s', strtotime($_REQUEST['tdate']));
			$d2 = $_REQUEST['tdate'] . ' 23:59:00';
			if (!empty($_REQUEST['fdate']) && !empty($_REQUEST['tdate'])) {
				if ($qry != ' ') {
					$qry .= 'AND ';
				}
				$qry .= "users.created_at BETWEEN '" . $d1 . "' AND '" . $d2 . "'";
			} else {
				if (!empty($_REQUEST['fdate'])) {
					if ($qry != ' ') {
						$qry .= 'AND ';
					}
					$qry .= "users.created_at >= '" . $d1 . "'";
				}
				if (!empty($_REQUEST['tdate'])) {
					if ($qry != ' ') {
						$qry .= 'AND ';
					}
					$qry .= "users.created_at <= '" . $d2 . "'";
				}
			}
		} */
	}
	
?>
<?php

    //pagination
    $per_page=10;
                    
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page_url = "?";
   $page_url = isset($_GET['name'])? $page_url."name=".$_GET['name']:$page_url;
    $page_url = isset($_GET['user_type_id'])? $page_url."user_type_id=".$_GET['user_type_id']:$page_url;
    $page_url = isset($_GET['email'])? $page_url."&email=".$_GET['email']:$page_url;
    $page_url = isset($_GET['search'])? $page_url."&search=".$_GET['search']:$page_url;
                
    $page=0;
    $current_page=1;
    if(isset($_GET['page'])){
        $current_page=intval($_GET['page']);
        $page=($current_page-1)*$per_page;
    }
	$sql='';
	
	if($_SESSION['role_id']==1){
		$query="SELECT users.user_id,users.name,users.mobile,email,roles.name as user_type,users.status as user_status,users.created_at FROM users INNER join roles on users.role_id=roles.id where users.del_action='N' $qry order by users.user_id DESC ";
	}else{
		$query="SELECT users.user_id,users.name,users.mobile,users.email,roles.name as user_type,users.status as user_status,users.created_at FROM users INNER join roles on users.role_id=roles.id where users.client_id='".$client_id."' and users.del_action='N'  $qry order by users.user_id DESC";
	}	
	$get_query=mysqli_query($conn,$query);
    $total_record=mysqli_num_rows($get_query);
    $total_pages=ceil($total_record/$per_page);
?>
<?php
if(isset($_REQUEST['active'])){
	$user_id=$_REQUEST['active'];
	
	$update=mysqli_query($conn,"update users set status='0' where user_id='".$user_id."'");
	if($update)
	{ 
		echo "<script>alert('Approved Successfully');</script>";
		echo "<script>window.location.href='user-list.php';</script>";
	}
}

?>
<?php
if(isset($_REQUEST['inactive'])){
	$user_id=$_REQUEST['inactive'];
	$update=mysqli_query($conn,"update users set status='1' where user_id='".$user_id."'");
	if($update)
	{ 
		echo "<script>window.location.href='user-list.php';</script>";
	}
}
?>
<?php
	if(!empty($_REQUEST['delid'])){
		 $did=$_GET['delid'];
		 // echo "UPDATE users SET del_action='Y' where user_id='".$did."'";
		// die();
		$delsql=mysqli_query($conn,"UPDATE users SET del_action='Y' where user_id='".$did."'");
		 if($delsql){
			 echo"<script>alert('Deleted Successfully...')</script>";
		 }
		 echo"<script>window.location.href='user-list.php'</script>";
			 
	}
?>	
<?php

if(isset($_REQUEST['save_multiple']))
{
	$forms=$_POST['forms'];
	$userid=$_POST['user_id'];
	$sqlUsers=mysqli_query($conn,"SELECT user_id,status FROM `users` where user_id='$userid'");
	$dataUser=mysqli_fetch_array($sqlUsers);
	if($dataUser['status']==0){
		foreach($forms as $formrow)
		{
			$insertsql="insert into assign_survey (user_id,survey_id) values ('$userid','$formrow')";
			$insquery=mysqli_query($conn,$insertsql);
		}
			if($insquery)
			{
				echo'<script>alert("Form assigned successfully.")</script>';
				//echo "<script>location.href='user-list.php'</script>"; 
			}
			else
			{
				echo'<script>alert("Somthing went wrong")</script>';
				echo "<script>location.href='user-list.php'</script>"; 
			}
	}else{
		echo'<script>alert("Your account has been Inactive")</script>';
        echo "<script>location.href='user-list.php'</script>"; 
	}
}	

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
	.btn-success:hover, .btn-success:focus, .btn-success:active, .btn-success.active, .open .dropdown-toggle.btn-success {
    color: #ffffff;
    border-color: #003b64;
    background: #003b64;
}
.btn-danger:hover, .btn-danger:focus, .btn-danger:active, .btn-danger.danger, .open .dropdown-toggle.btn-danger {
    color: #ffffff;
    border-color: #003b64;
    background: #003b64;
}
.widget .padd, .modal-body {
        background-color: white;
    height: 93px;
}
.form-group1{
	width: 20%;
}
    </style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />          
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
			<div class="add-button-bg">
				<a href="add-user.php" class="btn btn-fixed-circle" title="Add User"><i class="fa fa-plus"></i></a>
			</div>
           
			<div class="row">
          <div class="col-lg-12">
            <ol class="breadcrumb">
             
              <li><i class="icon_documents_alt"></i>User Management</li>
              <li><i class="fa fa-list"></i>List Users</li>
            </ol>
          </div>
        </div>
            <!-- page start-->
            <div class="row">
                <div class="col-sm-12">                  
                        <div class="container-fluid">
                            <form class="form-inline" method="get" role="form">
								<div class="row filter_css clearfix">
									<div class="form-group col-lg-3" style="margin-bottom: 1rem!important;margin-top:-1rem!important;">
										<input type="text" class="form-control" name="name" value="<?=@$_REQUEST['name']?>" placeholder="Name">
									</div>
									<div class="form-group col-lg-3" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
										<input type="email" class="form-control" name="email"  value="<?=@$_REQUEST['email']?>"placeholder="Email"></input>
									</div>
									<!--<div class="form-group1 col-lg-3" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
										 <input type="date" data-placement="top" data-toggle="tooltip" class="form-control btn btn tooltips" data-original-title="Form date" placeholder="From Date" name="fdate"value="<?php if ($_REQUEST['fdate']) {echo $_REQUEST['fdate'];} ?>">
									</div>
									<div class="form-group1 col-md-3" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
									  <input type="date" data-placement="top" data-toggle="tooltip" class="form-control btn btn tooltips" data-original-title="To date" placeholder="To Date" name="tdate"value="<?php if ($_REQUEST['tdate']) {echo $_REQUEST['tdate'];} ?>">
									</div>-->
									<div class="col-lg-3">
										<select class="form-control" name="status" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
											<option value="">Select Status</option>
											<option value="0"<?php if($_REQUEST['status']=='0') {echo "selected";}?>>Active </option>
											<option value="1"<?php if($_REQUEST['status']=='1') {echo "selected";}?>>Inactive</option>
										</select>
									</div>
									<div class="form-group col-lg-3" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
										<button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
									</div>
								</div>
							</form>
                        </div>
						<section class="panel">
						<header class="panel-heading">Total Users: <?=$total_record?>
						</header>
                        <table class="table table-striped">
                            <thead>
							<tr>
                                <th>Sl.No</th>
                                <th>Name</th>
                                <!--<th class="">Mobile</th>-->
                                <th>Email</th>
                                <th width="28%">Functional Type</th>
                                <th>Created On</th>
                                <th>Assign Form</th>
                                <th>Status</th>
								<th>Action</th>
								
                            </tr>
                            </thead>
                            <tbody>
                            <?php
							$sql='';
							if($_SESSION['role_id']==1){
								$_SESSION['query']="SELECT users.user_id,users.name,users.username as username,users.mobile,users.email,users.status as user_status,users.created_at FROM users INNER join roles on users.role_id=roles.id where users.del_action='N' $qry order by users.user_id DESC ";								
							}else{
							$_SESSION['query']="SELECT users.user_id,users.name,users.username as username,users.mobile,users.email,users.status as user_status,users.created_at FROM users INNER join roles on users.role_id=roles.id where users.client_id='".$client_id."' and users.del_action='N' $qry order by users.user_id DESC ";
							}	
							$sql='';
							if($_SESSION['role_id']==1){
							 $sql="SELECT users.user_id,users.name,users.mobile,email,roles.name as user_type,users.status as user_status,users.created_at FROM users INNER join roles on users.role_id=roles.id where users.del_action='N' $qry order by users.user_id DESC limit $page,$per_page";
							}else{
							 $sql="SELECT users.user_id,users.name,users.mobile,users.email,roles.name as user_type,users.status as user_status,users.created_at FROM users INNER join roles on users.role_id=roles.id where users.client_id='".$client_id."' and users.del_action='N'  $qry order by users.user_id DESC limit $page,$per_page";
							}
							$getUsers = mysqli_query($conn,$sql);
                            $sn=1+$page;
							while ($user = mysqli_fetch_array($getUsers)) {
							?>
                                <tr>
                                    <td><?= $sn++; ?></td>
                                    <td><?= ucfirst($user['name']) ?></td>
                                    <!--<td><?= $user['mobile'] ?></td>-->
                                    <td><?= $user['email'] ?></td>
									<td>
									<?php 
								   $functionalsql="SELECT DISTINCT(roles.name) as name FROM `functional_role` INNER join roles on functional_role.role_id=roles.id where functional_role.user_id='".$user['user_id']."'";
									$getfunUsers = mysqli_query($conn,$functionalsql);
									while($fundata = mysqli_fetch_array($getfunUsers)){
										$funName[]=$fundata['name'];
									}?>
									
									<?php echo implode(",",array_unique($funName));?>
									
									</td>
									<td><?= date('d-M-Y', strtotime($user['created_at'])); ?></td>
									<td>
									<a href="" data-toggle="modal" data-target="#Modalassignform" onclick="assignform(<?=$user['user_id'];?>)"; data-backdrop="static" data-keyboard="false" data-whatever="@fat" class="btn-sm btn-primary">Assign</a>
									
									</td>
									<td>
										<?php
										 if($user['user_status']==1){
											?>
											<a href="user-list.php?active=<?=$user['user_id'];?>" onclick="return confirm('Do you want to Active ?')";  data-placement="top" data-toggle="tooltip" class="btn-sm btn-danger tooltips" data-original-title="Active"><i class="fa fa-times-circle" style="font-size:15px"></i></a>
										<?php		
										 }else{
											 ?>
											   <a href="user-list.php?inactive=<?=$user['user_id'];?>" onclick="return confirm('Do you want to Inctive ?')";  data-placement="top" data-toggle="tooltip" class="btn-sm btn-success tooltips" data-original-title="Inactive"><i class="fa fa-check-circle" aria-hidden="true"  style="font-size:15px"></i></a>
											 <?php
										 }
										 ?>
									</td>
									<td>
										<div class="dropdown">
											<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Action
											<span class="caret"></span>
											</button>
											<ul class="dropdown-menu" aria-labelledby="export-btn">
												<li><a href="view-user.php?id=<?=$user['user_id'];?>" class="">View</a></li>
												<li><a href="edit-user.php?id=<?= $user['user_id']; ?>" class="">Edit</a></li>
												<?php 
												if ($_SESSION['role_id']==1){ ?> 
												<li><a href="user-list.php?delid=<?=$user['user_id'];?>" onclick="return confirm('Are you sure that you want to Delete User?');"; class="">Delete</a></li>
											 <?php	}  ?>
											</ul>
										</div>
										<!--<a href="view-user.php?id=<?=$user['user_id'];?>" data-placement="top" data-toggle="tooltip" class="btn-sm btn-success tooltips" data-original-title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
										<a href="edit-user.php?id=<?= $user['user_id']; ?>" class="btn-sm btn-primary"><i class="fa fa-pencil-square-o" style="font-size:18px"></i></a>
										<a href="user-list.php?delid=<?=$user['user_id'];?>" onclick="return confirm('Are you sure that you want to Delete User?');"; class="btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></a>-->
									</td>
                                </tr>
                                <?php } ?>
                            </tbody>
							</table>
								<div class="modal fade" id="Modalassignform" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
												<h1 class="modal-title" id="exampleModalLabel">
												<span>Assign Form</span></h1>
											</div>
											<form action="" method="POST" enctype="multipart/form-data">
												<div class="modal-body">
														<label style="margin-left:14px;">Select Forms</label>
													<div class="form-group">
														<input type="hidden" name="user_id" id="user_id" class="form-control" value="<?php echo $user['user_id'];?>" />
														<div class="col-lg-12" >
														 <select name="forms[]" class="form-control check_multiselect" multiple="multiple " data-placeholder="Assign User" required style="width: 500px;">
														 <?php $clnt='';
															if($_SESSION['role_id']=='3'){
																$clnt = " and client_id='".$_SESSION['client_id']."' ";
															}
															if($_SESSION['role_id']=='8'){
																$uid = $_SESSION['user_id'];
															 $clnt=" and id in(SELECT DISTINCT(survey_id) FROM assign_survey WHERE user_id='".$uid."') ";
															
															}
															 $form_sql="select id,survey_name from survey where survey.del_action='N' AND survey.id not in(SELECT survey_id FROM `assign_survey` where user_id='$userid') $clnt";
															
														 $form_query=mysqli_query($conn,$form_sql);
														 if(mysqli_num_rows($form_query)>0)
														 {
															foreach($form_query as $rowform)
															{ ?>
																<option value="<?php echo $rowform['id'];?>"><?php echo $rowform['survey_name']; ?></option>
															<?php
															}
														 }
														 else
														 {
															 echo "No Record Found";
														 }
														 ?>
														 </select>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
													<button type="submit" name="save_multiple" class="btn btn-primary">Submit</button>
												</div>
											</form>
										</div>
									</div>
								</div>
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
				    $_SESSION['header_column']="Name,Username,Mobile No,Email Id,Created On";
					$_SESSION['db_column']="name,username,mobile,email,created_at";
				?>
				 <div class=" col-md-2 " style="margin-bottom: 0rem!important; padding-top: 10px">
				  <a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
				 </div>
			</div>
			
            <!-- page end-->
        </section>
    </section>
	
    <!--main content end-->
<?php include_once('includes/footer.php'); ?>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(function() {
    $('#toggle-active').bootstrapToggle({
      on: 'Active'
     
    });
  })
  
   $(function() {
    $('#toggle-inactive').bootstrapToggle({
      off: 'Inactive'
    });
  })
</script>
<script>
function assignform(val){
	 $("#user_id").attr("value",val);
 }
</script>

<script>
	$(".multiple-select").select2({
	//maximumSelectionLength: 2
	});
</script>
