<?php include_once('includes/config.php'); ?>
<?php define("title", "App Version | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php include_once('mycrypt.php'); ?>
<?php 
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
$client_id=$_SESSION['client_id'];

?>
<?php						
	$qry='';
	if(isset($_REQUEST['search'])) 
	{
		if(isset($_REQUEST['survey_name']) && $_REQUEST['survey_name']!='') {
			$qry.= " AND survey_data_monitoring.survey_name like'%".$_REQUEST['survey_name']."%'";
		}
		if(isset($_REQUEST['name']) && $_REQUEST['name']!='') {
			$qry.= " AND users.name like'%".$_REQUEST['name']."%'";
		}
	}
?>
<?php
$per_page=10;
                    
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page_url = "?";
	$page_url = isset($_GET['survey_name'])? $page_url."survey_name=".$_GET['survey_name']:$page_url;
    $page_url = isset($_GET['name'])? $page_url."&name=".$_GET['name']:$page_url;
	$page_url = isset($_GET['search'])? $page_url."&search=".$_GET['search']:$page_url;
    $page=0;
    $current_page=1;
    if(isset($_GET['page'])){
        $current_page=intval($_GET['page']);
        $page=($current_page-1)*$per_page;
    }
//$query = "SELECT survey_id,users.name,users.username,full_json FROM `survey_data_monitoring` left join users on users.user_id=survey_data_monitoring.user_id where survey_name_id='".$survey_id."' $qry order by users.user_id DESC";
$query="SELECT users.user_id,users.name,survey_data_monitoring.survey_id,survey_data_monitoring.survey_name,full_json FROM `survey_data_monitoring` inner join users on users.user_id=survey_data_monitoring.user_id where survey_data_monitoring.client_id='".$client_id."' $qry and users.status='0' group by survey_data_monitoring.user_id order by survey_data_monitoring.user_id DESC";
								
$get_query = mysqli_query($conn, $query);
$total_record = mysqli_num_rows($get_query);
$total_pages = ceil($total_record / $per_page);
?>
<style>
	#main-content .wrapper .row {
		margin-bottom: 0px;
	}

	.panel {
		margin-bottom: 20px;
	}
</style>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Project Management</li>
					<li><i class="fa fa-list"></i>List Project</li>
				</ol>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-sm-12">
				<div class="container-fluid">
					<form class="form-inline" action="" method="GET">
					
						<div class="row filter_css clearfix">
							<div class="col-lg-4" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<input type="text" class="form-control" name="survey_name" id="survey_name" value="<?=@$_REQUEST['survey_name']?>" placeholder="Enter Form Name">
									
							</div>
							<div class="col-lg-4" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<input type="text" class="form-control" name="name" id="name" value="<?=@$_REQUEST['name']?>" placeholder="Enter User name">
									
							</div>
							<div class="form-group col-md-2"
								style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<button type="submit"
									class="btn btn-primary width-md waves-effect waves-light form-control"
									id="btnsearch" disabled name="search">Search</button>
							</div>
							<div class="form-group col-md-2"
								style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
								<a href="app_version.php" class="btn btn-primary width-md waves-effect waves-light form-control">Clear Filter</a>
							</div>
						</div>
					</form>
				</div>
				<section class="panel">
					<header class="panel-heading">Total User(s): <?=$total_record;?>
					</header>
					<div class=" table-responsive">
						<table class=" table table-striped  ">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Form ID</th>
									<th>Form Name</th>
									<th>Username</th>
									<th>App Version</th>
									
								</tr>
							</thead>
							<tbody>
								<?php
								//$appVersionsql = "SELECT survey_id,users.name,users.username,full_json FROM `survey_data_monitoring` left join users on users.user_id=survey_data_monitoring.user_id where survey_name_id='".$survey_id."' $qry order by survey_data_monitoring.survey_id DESC limit $page,$per_page";
								
								$appVersionsql="SELECT users.user_id,users.name,survey_data_monitoring.survey_id,survey_data_monitoring.survey_name,full_json FROM `survey_data_monitoring` inner join users on users.user_id=survey_data_monitoring.user_id where survey_data_monitoring.client_id='".$client_id."' and users.status='0' group by survey_data_monitoring.user_id order by survey_data_monitoring.user_id DESC limit $page,$per_page";
								$qryAppVersion = mysqli_query($conn, $appVersionsql);
								$sn = 1 + $page;
								if (mysqli_num_rows($qryAppVersion) > 0) {
									while ($row = mysqli_fetch_array($qryAppVersion)) {
										$DecryptedJson = $mcrypt->decrypt($row['full_json']);
										$full_json = json_decode($DecryptedJson, true);
										//print_r($full_json);
										$app_version=$full_json[app_version];
										$os_version=$full_json[os_version];
										$device_name=$full_json[device_name];
										?>
										<tr>
											<td>
												<?= $sn++; ?>
											</td>
											<td>
												<?= $row['survey_id']; ?>
											</td>
											<td>
												<?= $row['survey_name']; ?>
											</td>
											
											<td>
											
												<?= $row['name']; ?>
											</td>
											<td>
											<span class="label label-success" data-toggle="tooltip"data-html="true" data-placement="top" title="App Version </br> Device: <?=$device_name?> </br> OS Version: <?=$os_version?> "> v<?=$app_version;?></span> 
								
												
											</td>
											
										</tr>
									<?php }
								} else {
									echo '<tr><td colspan="7" class="text-center" style="font-size: 25px;"  >Records Not Found !!</td></tr>';
								} ?>
							</tbody>
						</table>
					</div>
				</section>
				<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
					<div class="col-md-10">
						<div class="d-flex align-items-center justify-content-between" id="pagination">
							<?= paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>
						</div>
					</div>
					
				<div class=" col-md-2 export-csv" style="margin-bottom: 0rem!important; padding-top: 4px">
					<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export_app_version.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
					</div>
				</div>
			</div>
		</div>
		<!-- page end-->
	</section>
</section>
<!--main content end-->

<?php include_once('includes/footer.php'); ?>
<script>
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
  });
</script>
<script>
$("#name,#survey_name").on("input",function() {
//$("#name,#date1,#survey,#status").on("change",function() {
	if ($("#name").val() != '' || $("#survey_name").val() != '') {
		$('#btnsearch').prop('disabled', false);
	} else {
		$('#btnsearch').prop('disabled', true);
	}
});
</script>