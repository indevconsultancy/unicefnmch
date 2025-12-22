<?php include_once('includes/config.php'); ?>
<?php define("title","Show Dataset | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php  
	$username=$_SESSION['username'];
	$user_id=$_SESSION['user_id'];
	$role_id=$_SESSION['role_id'];
	$qryUser=$qryUser1=$qryaccess='';
	
	if($user_id!='' and $role_id!='1' ){
		$qryUser=" and (data_repositroy.user_id='".$user_id."' or data_repositroy.data_repositroy_status='1') ";
		$qryUser1=" and data_repositroy_otherdata.user_id='".$user_id."' ";
	}
	if($user_id!='' and $role_id=='1' ){
		
		$qryaccess=" and data_repositroy_otherdata.data_access='Public' ";
	}
	

 ?>

<?php
		$qry='';
		$qrycat='';
		if(isset($_REQUEST['searchdata'])) 
		{
            if (isset($_REQUEST['study_name']) && $_REQUEST['study_name']!='') {
                 $qry .= " AND data_repositroy.study_name like '%" . $_REQUEST['study_name'] . "%'";
		   }
			if(isset($_REQUEST['category_id']) && $_REQUEST['category_id']!=''){
				//$qry .="AND data_repositroy.category_id='".$_REQUEST['category_id']."'";
				
				 $qry .="AND find_in_set('".$_REQUEST['category_id']."',data_repositroy.category_id)";
				$qrycat .="AND find_in_set('".$_REQUEST['category_id']."',categories.category_id)";
			
			}
			if(isset($_REQUEST['published_date']) && $_REQUEST['published_date']!=''){
				$qry .="AND data_repositroy.data_study_year='".$_REQUEST['published_date']."'";
			}
			if(isset($_REQUEST['institution_name']) && $_REQUEST['institution_name']!=''){
				$qry .="AND data_repositroy.institution_name='".$_REQUEST['institution_name']."'";
			}
		}
?>
<?php
//pagination
    $per_page=10;
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page_url = "?";
    $page_url = isset($_GET['study_name'])? $page_url."study_name=".$_GET['study_name']:$page_url;
    $page_url = isset($_GET['category_id'])? $page_url."&category_id=".$_GET['category_id']:$page_url;
    $page_url = isset($_GET['published_date'])? $page_url."&published_date=".$_GET['published_date']:$page_url;
    $page_url = isset($_GET['institution_name'])? $page_url."&institution_name=".$_GET['institution_name']:$page_url;
	$page_url = isset($_GET['searchdata'])? $page_url."&search=".$_GET['searchdata']:$page_url;
    $page=0;
    $current_page=1;
    if(isset($_GET['page'])){
        $current_page=intval($_GET['page']);
        $page=($current_page-1)*$per_page;
    }
	$query="SELECT data_repositroy.data_repository_id,data_repositroy.user_id,users.name,data_repositroy.study_name,data_repositroy.data_repositroy_status,data_repositroy.description,data_repositroy.institution_name, data_repositroy.published_date,data_repositroy.category_id,data_repositroy.data_study_year,data_repositroy_otherdata.data_access FROM data_repositroy left join users on users.user_id=data_repositroy.user_id left join data_repositroy_otherdata on data_repositroy.data_repository_id=data_repositroy_otherdata.data_repository_id where data_repositroy.status='1' $qryUser $qry order by data_repositroy.data_repository_id DESC";
																															
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

</style>
  <!--main content start-->
    <section id="main-content">
        <div class="wrapper">
            <div class="row">
                <div class="col-lg-12">
					
                    <ol class="breadcrumb">
                        <li><i class="icon_documents_alt"></i>Data Repository</li>
                        <li><i class="fa fa-bar-chart"></i></i>Show Dataset</li>
                    </ol>
                    <!--Start filter-->
                    <div class="container-fluid">
                    </div>
                    <!-- Filter End-->
                </div>
            </div>
            <!-- page start-->
            <div class="row">
            	<div class="col-sm-12">
                	<div class="panel panel-default">
                    	 <div class="row">
                            <div class="col-md-8"> 
								<form method="GET">
                                <div class="search-widget input-group mb-xs" style="margin:15px;!important">
                                    <input id="inputDataverseSearch" class="form-control" type="text" name="study_name" placeholder="Search Surveys" aria-label="Search all data" value="<?=$_REQUEST['study_name']?>">
                                    <span class="input-group-btn">
                                   <button id="btnDataverseSearch" class="btn btn-secondary bootstrap-button-tooltip" name="searchdata"  type="submit" data-original-title="Find"><span class="fa fa-search"></span></button>
                                   </span>
									
                                </div>
								 </form>
                                <div class="row pl-0 pr-0">
                            		<div class="col-md-12"> 
										<?php 
										    $sqlcontribute="SELECT data_repositroy.data_repository_id,data_repositroy.user_id,users.name,data_repositroy.study_name,data_repositroy.data_repositroy_status,data_repositroy.description,data_repositroy.institution_name, data_repositroy.published_date,data_repositroy.category_id,data_repositroy.data_study_year,data_repositroy_otherdata.data_access FROM data_repositroy left join users on users.user_id=data_repositroy.user_id left join data_repositroy_otherdata on data_repositroy.data_repository_id=data_repositroy_otherdata.data_repository_id where data_repositroy.status='1' $qryUser $qry order by data_repositroy.data_repository_id DESC limit $page,$per_page";
											$contribute_survey=mysqli_query($conn,$sqlcontribute);
											if(mysqli_num_rows($contribute_survey)>0){
											while($contribute_row=mysqli_fetch_array($contribute_survey)){
												$published_date=$contribute_row['published_date'];
												 $published_year= date("d-m-Y", strtotime($published_date));
												 $category_id=$contribute_row['category_id'];
												 $data_repositroy_status=$contribute_row['data_repositroy_status'];
												 $data_access=$contribute_row['data_access'];
												
												?>
											<div class="survey-box" id="data_id-<?=$contribute_row['data_repository_id'];?>">
												<div class="survey-container">
													<div class="survey-heading titled">
														<div class="col-md-9">
															<h4>Study Name: <?php echo $contribute_row['study_name'];?>
														</div>
														<div class="col-md-3">
															<span style="float: right;font-style: italic;color:white"><?=$data_access;?></span>
															<?php if($contribute_row['user_id']==$user_id){?>
																<!--<a type="submit" href="edit-data-bank.php?eid=<?=$contribute_row['data_repository_id']?>" class="btn btn-success">Edit</a>-->
																<a href="javascript:void(0);" type="submit" data-id="<?=$contribute_row['data_repository_id']?>" class="btn btn-danger delRepository">Delete</a>
															<?php } ?>
														</div>	
													</h4>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-user"></i> Uploaded By:</p>
													</div>
													<div class="survey-data">
														<p><?php echo $contribute_row['name']?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-clock-o"></i> Publication date:</p>
													</div>
													<div class="survey-data">
														<p><?php echo $published_year;?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-clock-o"></i> Study Year:</p>
													</div>
													<div class="survey-data">
														<p><?php echo $contribute_row['data_study_year'];?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-file"></i> Data Name:</p>
													</div>
												
													<div class="survey-data">
														<?php 
														$sqldata="SELECT GROUP_CONCAT(data_name) as data_name FROM data_repositroy_otherdata where data_repository_id='".$contribute_row['data_repository_id']."' and status='1'";	
														$qrydata=mysqli_query($conn,$sqldata);
														$dataname=mysqli_fetch_array($qrydata); 
															
														?>
														<p><?php echo $dataname['data_name'];?></p>
													
													</div>
												</div>
												<?php if($data_access=='Public'){ ?>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class='fa fa-exclamation-circle'></i> Status: </p>
													</div>
													<div class="survey-data">
														<p>
															<?php if($data_repositroy_status=='1') { ?>
																	<span class="label label-success">Approved</span>
															<?php }else if($data_repositroy_status=='2'){ ?>
																	<span class="label label-danger">Rejected</span>
															<?php } else{ ?>
																	<span class="label label-warning">Pending</span>
															<?php } ?>
														</p>
													</div>
												</div>
												<?php } ?>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-file"></i> Description:</p>
													</div>
													<div class="survey-data">
														<p><?php echo $contribute_row['description'];?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-list-alt"></i> Thematic Area:</p>
													</div>
													<div class="survey-data">
													<?php
														$sqlcdata="SELECT GROUP_CONCAT(category_name) as category_name FROM `categories` WHERE `category_id` IN (".$category_id.") $qrycat";
														$sqlcates=mysqli_query($conn,$sqlcdata);
														$rows=mysqli_fetch_array($sqlcates);
															//$categories[]=$rows['category_name'];
														
														?>
														
														<!--<p><?php //echo implode(',',array_unique($categories));?></p>-->
														<p><?php echo $rows['category_name'];?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-user"></i> Institution :</p>
													</div>
													<div class="survey-data">
														<p><?php echo $contribute_row['institution_name'];?></p>
													</div>
												</div>
												
												<a href="view_databank.php?vid=<?=$contribute_row['data_repository_id']?>" type="submit" class="btn btn-primary">View more</a>
												<?php if($_SESSION['role_id']==1 && $data_access=='Public'){ ?>
													<?php if($data_repositroy_status=='1') { ?>
													<a type="submit" class="btn btn-success" disabled>Approved</a>
													<?php } else if($data_repositroy_status=='0'){?>
														<a href="javascript:void();" type="submit" data-id="<?=$contribute_row['data_repository_id']?>" class="btn btn-success approveRepository">Approve</a>
													<?php } ?>	
													
													<?php if($data_repositroy_status=='2') { ?>	
														<a type="submit" class="btn btn-danger" disabled>Rejected</a>
														<a href="javascript:void();" type="submit" data-id="<?=$contribute_row['data_repository_id']?>" class="btn btn-success approveRepository">Approve</a>
													<?php }else{ ?>	
														<a href="javascript:void();" type="submit" data-id="<?=$contribute_row['data_repository_id']?>" class="btn btn-danger rejectRepository">Reject</a>
													<?php } ?>
												<?php } ?>
												
											</div>
											
										<?php } 
										}else{ ?>
											<div class="survey-data text-center">
												<h3>No record found !!</h3>
												<a class="badge badge-primary" href="survey_bank.php">Refresh</a> </td></tr>
											</div>
										<?php } ?>
                                    </div>
                                </div>
                                <div class="panel ">
									<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
										<div class="col-md-10">
										  <div class="d-flex align-items-center justify-content-between" id="pagination">
											 <?=paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>                               
										 </div>
										 </div>
									</div>
								</div>
                            </div>
                            <div class="col-md-4">
                            	<div class="sticky-top">
                                	<div class="sidebar mt-xl">
                                        <div class="side-heading">
                                            <h4>Thematic Area</h4>
                                        </div>
										<form action="GET">
                                        <div class="side-data">
                                            <ul>
											
												<?php 
												$categorysssql="SELECT category_id,category_name FROM categories where status='0' order by category_name asc";
												$datassqry=mysqli_query($conn,$categorysssql);
												while($datacategory=mysqli_fetch_array($datassqry)){
												
											    $sqlcatdata="SELECT COUNT(data_repository_id) as total FROM data_repositroy where find_in_set('".$datacategory['category_id']."',category_id) and data_repositroy.status='1' $qryUser ";
												$sqlcategory=mysqli_query($conn,$sqlcatdata);
												while($row=mysqli_fetch_array($sqlcategory)){	
												?>
												
                                               <li>
											   <a href="data_bank.php?category_id=<?=$datacategory['category_id'] ?>&published_date=&client_name=&searchdata="><span><?php echo $datacategory['category_name'];?></span> <span class="badg"><?php echo $row['total'];?></span></a>
                                                </li>
												<?php } } ?>
                                            </ul>
                                        </div>
										</form>
                                    </div>
                                    <div class="sidebar">
                                        <div class="side-heading">
                                            <h4>Study Year</h4>
                                        </div>
                                        <div class="side-data">
                                            <ul>
												<?php 
												$publish_survey=mysqli_query($conn,"SELECT COUNT(data_repository_id) as count_publish_survey,data_study_year,data_repositroy.institution_name FROM `data_repositroy` where data_repositroy.status='1' $qry $qryUser group by data_study_year order by data_repository_id ASC");
												while($publish_row=mysqli_fetch_array($publish_survey)){
												//$published_date=$publish_row['data_study_year'];
												 //$published_date= date("Y-m-d", strtotime($published_date));
												?>
                                               
												<li>
                                                    <a href="data_bank.php?published_date=<?php echo $publish_row['data_study_year'];?>&category_id=&client_name=&searchdata="><span><?php echo $publish_row['data_study_year'] ;?></span> <span class="badg"><?php echo $publish_row['count_publish_survey'];?></span></a>
                                                </li>
												<?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="sidebar">
                                        <div class="side-heading">
                                            <h4>By Institution </h4>
                                        </div>
                                        <div class="side-data">
                                            <ul>
												<?php 
												//echo "SELECT count(id) as clientwise_survey ,institution_name,year(published_date) as published_date,data_repositroy.category_id FROM `data_repositroy` where status='0' group by institution_name";
												$sqlclient=mysqli_query($conn, "SELECT count(data_repository_id) as clientwise_survey ,institution_name,data_study_year,data_repositroy.category_id FROM `data_repositroy` where data_repositroy.status='1' $qry $qryUser group by institution_name");
												while($rowclient=mysqli_fetch_array($sqlclient)){
													
												?>
                                               <li>
                                                    <a href="data_bank.php?institution_name=<?=$rowclient['institution_name'] ?>&published_date=&category_id=&searchdata="><span><?php echo $rowclient['institution_name'];?></span> <span class="badg"><?php echo $rowclient['clientwise_survey'];?></span></a>
                                                </li>
												<?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            	
                            </div>  
                        </div>
                          
                    </div>              
                </div>
            </div>
            <!-- page end-->
	    </div>
    </section>
  <!--main content end-->
 <?php include_once('includes/footer.php'); ?> 
 <!-- Optional JavaScript -->
 <?php if(isset($_SESSION['status']) && $_SESSION['status']!=''){ ?>
   <script>
		swal.fire({
			  title: "<?php echo $_SESSION['status'];?>",
			  icon:"<?php echo $_SESSION['status_code']; ?>",
			  confirmButtonColor: '#449A97',
			  confirmButtonText: 'Ok'
			});
	</script>
<?php unset($_SESSION['status']);
}  ?>
 <script>
 $('.approveRepository').on('click',function(e){
	e.preventDefault();
	var approve_data_id=$(this).data('id');
	Swal.fire({
	  title: 'Are you sure to approve this Data Repository?',
	   icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#449A97',
	  cancelButtonColor: '#449A97',
	  confirmButtonText: 'Approve'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url:"ajax/get_ajax.php",
				type:"post",
				data:{approve_data_id:approve_data_id},
				success:function(res){
					var ress=JSON.parse(res);
					if(ress.status=="1"){
						Swal.fire({
						  title:'Approve successfully',
						  icon:'success',
						  confirmButtonColor: '#449A97',
						  confirmButtonText: 'Ok'
						}) 
						//window.location.reload();
					} 
				}
			})
		}
	}); 
	 
 })

 $(".rejectRepository").on("click", function(e){
	let data_reject_id = $(this).data("id");
	e.preventDefault();
	Swal.fire({
	  title: 'Are you sure to reject this Data Repository?',
	   icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#449A97',
	  cancelButtonColor: '#449A97',
	  confirmButtonText: 'Reject'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url:"ajax/get_ajax.php",
				type:"post",
				data:{data_reject_id:data_reject_id},
				success:function(res){
					var ress = JSON.parse(res);
					if(ress.status=="1"){
						Swal.fire({
						  title: 'Rejected successfully',
						  icon: 'success',
						  confirmButtonColor: '#449A97',
						  confirmButtonText: 'Ok'
						}) 
						//window.location.reload();
					} 
				}
			})
		}
	});
})

 $('.delRepository').on('click',function(e){
	e.preventDefault();
	var del_data_id=$(this).data('id');
	Swal.fire({
	  title: 'Are you sure to delete this Data Repository?',
	   icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#449A97',
	  cancelButtonColor: '#449A97',
	  confirmButtonText: 'Delete'
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url:"ajax/get_ajax.php",
				type:"post",
				data:{del_data_id:del_data_id},
				success:function(res){
					console.log(res);
					var ress = JSON.parse(res);
					if(ress.status=="1"){
						$("#data_id-"+del_data_id).hide();
						Swal.fire({
						  title: 'Deleted successfully',
						  icon: 'success',
						  confirmButtonColor: '#449A97',
						  confirmButtonText: 'Ok'
						}) 
						//window.location.reload();
					} 
				}
			})
		}
	});
 })
 </script>
 
