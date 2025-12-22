<?php include_once('includes/config.php'); ?>
<?php define("title","List Form | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php 
$pages='';
	if($_GET['page']!=''){
		 $pages=$_GET['page'];
	}else{
		$pages='1'; 
	}

?>

<?php
	$pqry='';
	if($_GET['pid']!=''){
		$pid = $_GET['pid'];
		$pqry=" and survey.project_id='".$pid."' ";	
	}
?>
<?php
	$client_qry = "";
	$project_qry="";
	if($_SESSION['functional_role_id']=='3'){
		$client_id = $_SESSION['client_id'];
		$client_qry=" and survey.client_id='".$client_id."' ";
		$project_qry=" and projects.client_id='".$client_id."' ";
		
	}
	 
	if($_SESSION['functional_role_id']=='9'){
		$uid = $_SESSION['user_id'];
		$client_qry=" and survey.id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='".$uid."') ";
	}
	// if($_SESSION['functional_role_id']!="3" || $_SESSION['functional_role_id']!="1"){
		// $uid = $_SESSION['user_id'];
		// $client_qry=" and survey.id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='".$uid."') ";
	
	// }
	 $page_button_id = $_SESSION['page_button_id'];

	//echo "SELECT `page_button_id`, `page_name`, `activity_name`, `redirect_link`, `button_name`, `icon`, `status`, `confirm_msg`,`tooltip`,`btn_group` FROM page_buttons WHERE page_button_id IN($page_button_id) AND status='0' and page_name='survey-list.php' order by sequence asc  ";
	$getShowButtons = mysqli_query($conn,"SELECT `page_button_id`, `page_name`, `activity_name`, `redirect_link`, `button_name`, `icon`, `status`, `confirm_msg`,`tooltip`,`btn_group` FROM page_buttons WHERE page_button_id IN($page_button_id) AND status='0' and page_name='survey-list.php' order by sequence asc ");
	while($showAllButtons = mysqli_fetch_object($getShowButtons)){
		$showAllButtonsArr["page_button_id"] = $showAllButtons->page_button_id;
		$showAllButtonsArr["page_name"] = $showAllButtons->page_name;
		$showAllButtonsArr["activity_name"] = $showAllButtons->activity_name;
		$showAllButtonsArr["redirect_link"] = $showAllButtons->redirect_link;
		$showAllButtonsArr["confirm_msg"] = $showAllButtons->confirm_msg;
		$showAllButtonsArr["button_name"] = $showAllButtons->button_name;
		$showAllButtonsArr["icon"] = $showAllButtons->icon;
		$showAllButtonsArr["tooltip"] = $showAllButtons->tooltip;
		$showAllButtonsArr["btn_group"] = $showAllButtons->btn_group;
		$showAllButtonsArrData[] = $showAllButtonsArr;
	}
	//print_r($showAllButtonsArr);
	// $showAllButtons = mysqli_fetch_all($getShowButtons);

	$manage = ["add_item","add_survey_replace","survey_view","survey_publish","survey_unpublish","edit_form","assign_user","advance_option"];
	//$manage = ["add_item","edit_question","survey_view","survey_publish","survey_unpublish","edit_form","data_lookup","media_lookup","add_survey_replace","download _tool","bulk_upload"];
	//$manageVisualization = ["survey_review","survey_dashboard","survey_location"];
	$manageVisualization = ["survey_dashboard","advance_option_Visualization"];
	$manageStatus = ["survey_ongoing","survey_complete"];
	$manageExport = ["survey_export_excel", "survey_export_excel_coded", "survey_export_stata", "survey_export_spss", "survey_export_json", "survey_paradata"];
	$manageDelete = ["survey_delete"];
	
	//print_r($manageExport);
	foreach($showAllButtonsArrData as $showAllButtonsArrData1){
		if(in_array($showAllButtonsArrData1["button_name"], $manage)){
			$manageButtons["activity_name"] = $showAllButtonsArrData1["activity_name"];
			$manageButtons["redirect_link"] = $showAllButtonsArrData1["redirect_link"];
			$manageButtons["confirm_msg"] = $showAllButtonsArrData1["confirm_msg"];
			$manageButtons["icon"] = $showAllButtonsArrData1["icon"];
			$manageButtons["button_name"] = $showAllButtonsArrData1["button_name"];
			$manageButtons["btn_group"] = $showAllButtonsArrData1["btn_group"];
			$manageAllButtons[] = $manageButtons;
		}
		//print_r($manageAllButtons);
		if(in_array($showAllButtonsArrData1["button_name"], $manageVisualization)){
			$manageVisualButtons["activity_name"] = $showAllButtonsArrData1["activity_name"];
			$manageVisualButtons["redirect_link"] = $showAllButtonsArrData1["redirect_link"];
			$manageVisualButtons["icon"] = $showAllButtonsArrData1["icon"];
			$manageVisualButtons["btn_group"] = $showAllButtonsArrData1["btn_group"];
			$manageAllVisualButtons[] = $manageVisualButtons;
		}

		if(in_array($showAllButtonsArrData1["button_name"], $manageStatus)){
			$manageStatusButtons["activity_name"] = $showAllButtonsArrData1["activity_name"];
			$manageStatusButtons["redirect_link"] = $showAllButtonsArrData1["redirect_link"];
			$manageStatusButtons["icon"] = $showAllButtonsArrData1["icon"];
			$manageStatusButtons["button_name"] = $showAllButtonsArrData1["button_name"];
			$manageAllStatusButtons[] = $manageStatusButtons;
		}

		if(in_array($showAllButtonsArrData1["button_name"], $manageExport)){
			$manageExportButtons["activity_name"] = $showAllButtonsArrData1["activity_name"];
			$manageExportButtons["redirect_link"] = $showAllButtonsArrData1["redirect_link"];
			$manageExportButtons["icon"] = $showAllButtonsArrData1["icon"];
			$manageExportButtons["tooltip"] = $showAllButtonsArrData1["tooltip"];
			$manageAllExportButtons[] = $manageExportButtons;
		}

		if(in_array($showAllButtonsArrData1["button_name"], $manageDelete)){
			$manageDeleteButtons["activity_name"] = $showAllButtonsArrData1["activity_name"];
			$manageDeleteButtons["redirect_link"] = $showAllButtonsArrData1["redirect_link"];
			$manageDeleteButtons["icon"] = $showAllButtonsArrData1["icon"];
			$manageAllDeleteButtons[] = $manageDeleteButtons;
		}
		//print_r($manageAllDeleteButtons);
		
	}

	?>
<?php
	//$qry = "WHERE 1=1 ";
	$qry='';
	 $count = 1;
	if(isset($_REQUEST['search'])) 
	{
		if (isset($_REQUEST['survey_name']) && $_REQUEST['survey_name']!='') {
		  $qry .= " AND survey.survey_name like '%" . $_REQUEST['survey_name'] . "%'";
		}
		if (isset($_REQUEST['project_id']) && $_REQUEST['project_id']!='') {
		  $qry .= " AND survey.project_id= '".$_REQUEST['project_id']."'";
		}
		if(isset($_REQUEST['status']) && $_REQUEST['status']!=''){
	
			$qry .="AND survey.status='".$_REQUEST['status']."'";
		}
		if(isset($_REQUEST['client_id']) && $_REQUEST['client_id']!=''){
			$qry .="AND survey.client_id='".$_REQUEST['client_id']."'";
		}
		if (isset($_REQUEST['fdate']) && isset($_REQUEST['tdate'])) {
		  $d1 = date('Y-m-d', strtotime($_REQUEST['fdate']));
		  //$d1 = $_REQUEST['fdate'] . ' 00:00:00';
		  $d2 = date('Y-m-d', strtotime($_REQUEST['tdate']));
		 // $d2 = $_REQUEST['tdate'] . ' 23:59:00';
		  if (!empty($_REQUEST['fdate']) && !empty($_REQUEST['tdate'])) {
			  if ($qry != ' ') {
				  $qry .= 'AND ';
			  }
			  $qry .= "date(survey.created_at) BETWEEN '" . $d1 . "' AND '" . $d2 . "'";
		  } else {
			  if (!empty($_REQUEST['fdate'])) {
				  if ($qry != ' ') {
					  $qry .= 'AND ';
				  }
				  $qry .= "date(survey.created_at) >= '" . $d1 . "'";
			  }
			  if (!empty($_REQUEST['tdate'])) {
				  if ($qry != ' ') {
					  $qry .= 'AND ';
				  }
				  $qry .= "date(survey.created_at) <= '" . $d2 . "'";
			  }
		  }
		}
	}
	?>
<?php
//pagination
	$per_page=10;
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$page_url = "?";
	$page_url = isset($_GET['survey_name'])? $page_url."survey_name=".$_GET['survey_name']:$page_url;
	$page_url = isset($_GET['client_id'])? $page_url."&client_id=".$_GET['client_id']:$page_url;
	$page_url = isset($_GET['status'])? $page_url."&status=".$_GET['status']:$page_url;
	$page_url = isset($_GET['fdate'])? $page_url."&fdate=".$_GET['fdate']:$page_url;
	$page_url = isset($_GET['tdate'])? $page_url."&tdate=".$_GET['tdate']:$page_url;
	$page_url = isset($_GET['search'])? $page_url."&search=".$_GET['search']:$page_url;
	$page=0;
	$current_page=1;
	if(isset($_GET['page'])){
		$current_page=intval($_GET['page']);
		$page=($current_page-1)*$per_page;
	}
	$query="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data, survey.survey_name, survey.created_at,survey.client_id, survey.status, survey.user_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry $pqry GROUP BY survey.id  order by survey.created_at DESC";
	$get_query=mysqli_query($conn,$query);
	$total_record=mysqli_num_rows($get_query);
	$total_pages=ceil($total_record/$per_page);
?>

<?php
	if(isset($_REQUEST['delteSurvey'])){
			 $emailId=$_POST['email'];
			 $survey_id=$_POST['survey_id'];
			$pages='';
			if($_GET['page']!=''){
				 $pages=$_GET['page'];
			}else{
				$pages='1'; 
			}
			$sqlsuser=mysqli_query($conn,"SELECT users.user_id,users.email,users.name,survey.survey_name,survey.id FROM `survey` inner join users on survey.user_id=users.user_id where id='".$survey_id."'");
			$qryusersurvey=mysqli_fetch_array($sqlsuser);
			$survey_name=$qryusersurvey['survey_name'];
			$id=$qryusersurvey['id'];
			$user_id=$qryusersurvey['user_id'];
			$email=$qryusersurvey['email'];
			
			$name=$qryusersurvey['name'];
			$otp=(rand(0,1000000));
			if($email==$emailId)
			{
				 $updateqry = "UPDATE survey SET otp='".$otp."' WHERE id='".$survey_id."' ";
				
				$updateotp=mysqli_query($conn,$updateqry);
				if($updateotp)
				{
					$mailto=$email;
					if($mailto!=''){
						$otp = $otp;
						$name = $name;
						$message_all = '';
						$message_all= "Dear " . $name .",<br>";
						$message_all.= "Please enter your OTP for delete Form: ".$otp;
						
						$txt = $message_all;
						$subject=base64_encode("MQUAD OTP for Delete Form");
						$message=base64_encode($txt);
						
						$sendmail=send_mail_function($mailto,$message,$subject);
						
						if($sendmail['status']==1){
							echo "<script>window.location.href='form_delete_otp.php?survey_id=$survey_id?&page=$pages'</script>";
						}
						 else {
							$_SESSION['status'] = "Mail not send!!";
							$_SESSION['status_code'] = "warning";
						} 
					}
				} 
				$_SESSION['status'] = "Something went wrong!!";
				$_SESSION['status_code'] = "warning";
			}else{
				$_SESSION['status'] = "This survey is not registered with this Email ID, please enter valid Email ID";
				$_SESSION['status_code'] = "warning";
			}	
		} 
	?>
<?php

if(isset($_REQUEST['assign_user']))
{
	$user_id=$_POST['user_id'];
	$survey_id=$_POST['survey_id'];
	foreach($user_id as $user_ids)
	{
		$insertsql="insert into assign_survey (survey_id,user_id) values ('$survey_id','$user_ids')";
		$insquery=mysqli_query($conn,$insertsql);
	}
	if($insquery){
		$_SESSION['status'] = "Form has been assigned successfully";
		$_SESSION['status_code'] = "success";
	}
	else
	{
		$_SESSION['status'] = "Something went wrong!!";
		$_SESSION['status_code'] = "warning";
	}
}	

?>									
<style>
	.panel-heading {
	background: #394a59;
	color: white;
	font-weight: unset;
	}
	.table>tbody>tr>td>b{
	font-weight: bold;
	color: #033d66;
	}
	button.dropdown-toggle {
	height: 28px;
	}
	.btn-sm, .btn-xs {
	display: inline-block;
	margin-bottom: 1px;
	margin-right: 2px;
	}
	td .dropdown {
	display: inline-block;
	}
	
	.btn-danger:hover, .btn-danger:focus, .btn-danger:active, .btn-danger.danger, .open .dropdown-toggle.btn-danger {
	color: #ffffff;
	border-color: #003b64;
	background: #003b64;
	}
	a.disabled {
	/* Make the disabled links grayish*/
	color: gray;
	pointer-events: none;
	/* cursor: not-allowed !important;*/
	}
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
	padding: 8px;
	line-height: 0.928571429;
	vertical-align: top;
	border-top: 1px solid #dddddd;
	}
	
	.chide{
		display:none;
	}
	
.copied {
  font-family: 'Montserrat', sans-serif;
  width: 75px;
  display: none;
  position:fixed;
  
  color:#fff;
  padding: 15px 15px;
  background-color: #000;
  border-radius: 5px;
  box-shadow: 0 3px 15px #b8c6db;
  -moz-box-shadow: 0 3px 15px #b8c6db;
  -webkit-box-shadow: 0 3px 15px #b8c6db;
}

.tooltips + .tooltip > .tooltip-inner {background-color: #000;color:#fff; text-align: justify;}
.ui-datepicker td a {
    text-align: center !important;
  
}

element.style {
}

.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
 
    /* border: 3px solid #22bacf !important; */
    box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
    border-radius: 0px !important;
    -webkit-border-radius: 0px !important; *
}

.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active, a.ui-button:active, .ui-button:active, .ui-button.ui-state-active:hover {

    background: #449A97 !important;
    font-weight: normal;
    color: #fff !important;
}
#ui-datepicker-div{
	top:174px !important;
}
div.dataTables_wrapper div.dataTables_info {
   
    display: none;
}
.employee_data_length{
	 display: none;
}
.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing {
    color: inherit;
    display: none; 
}
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet"/>   
<!------DataTable Css------------->
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />  
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="css/buttons.dataTables.min.css">
<!------DataTable Css------------->
<div id="pre-load" class="loading-indicator">
   <div id="loader" class="loader">
	   <div class="loader-container">
		   <div class='loader-icon'><img src="https://mquad.org/mis/img/mquad-logo.png" alt=""></div>
	   </div>
   </div>              
</div>
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
		<div class="col-lg-12">
				<ol class="breadcrumb">
					<li><i class="icon_documents_alt"></i>Form</li>
					<li><i class="fa fa-list"></i></i>List Form  </li>
				</ol>
				<!--Start filter-->
				<div class="container-fluid">
					<form method="get ">
						<div class="row filter_css res-filter clearfix" >
							<div class="col-lg-2 col-md-4">
								<select class="form-control" name="project_id" id="project_id" style="text-transform: capitalize;">
									<option value="">Select Project</option>
									<?php
										$sqlproject=mysqli_query($conn,"SELECT project_id,project_name FROM `projects` where status='0' $project_qry order by project_id DESC");
										while($typeProject=mysqli_fetch_array($sqlproject)){?>
									<option value="<?php echo $typeProject['project_id']?>" <?php if($typeProject['project_id']==$_REQUEST['project_id']) { echo "selected";}?>><?php echo $typeProject['project_name']?></option>
									<?php	
										}
										?>
								</select>
							</div>
							<div class="col-lg-2 col-md-4" >
								<input type="text" name="survey_name" Placeholder="Form Name" class="form-control" value="<?=$_REQUEST['survey_name']?>" />
							</div>
							<?php if($_SESSION['role_id']=='1' || $_SESSION['role_id']=='2'){?>
							<div class="col-lg-2 col-md-4">
								<select class="form-control" name="client_id" id="client_id" style="text-transform: capitalize;">
									<option value="">Select Client</option>
									<?php
										$sql=mysqli_query($conn,"SELECT id,name FROM `clients` where del_action='N' order by name DESC");
										while($type=mysqli_fetch_array($sql)){?>
									<option value="<?php echo $type['id']?>" <?php if($type['id']==$_REQUEST['client_id']) { echo "selected";}?>><?php echo $type['name']?></option>
									<?php	
										}
										?>
								</select>
							</div>
							
							<?php }?>
							<div class="col-lg-2 col-md-4">
								<select class="form-control" name="status">
									<option value="">Select Type</option>
									<option value="1"<?php if($_REQUEST['status']=='1') {echo "selected";}?>>Publish</option>
									<option value="0"<?php if($_REQUEST['status']=='0') {echo "selected";}?>>Unpublish</option>
								</select>
							</div>
							<div class="col-lg-2 col-md-4" >
								<input type="text" data-placement="top" id="date1" data-toggle="tooltip" data-original-title="From date" class="form-control" title="From date" placeholder="From Date" name="fdate"value="<?php if ($_REQUEST['fdate']) {echo $_REQUEST['fdate'];} ?>">
							 </div>
							<div class="col-lg-2 col-md-4">
								<input type="text" data-placement="top" id="date2" data-toggle="tooltip" data-original-title="To date" class="form-control" title="To date" placeholder="To Date" name="tdate"value="<?php if ($_REQUEST['tdate']) {echo $_REQUEST['tdate'];} ?>">
							</div>
							<div class="col-lg-2 col-md-4">
								<button type="submit" class="btn btn-secondary width-md waves-effect waves-light form-control" name="search">Search</button>
							</div>
						</div>
					</form>
				</div>
				<!-- Filter End-->
			</div>
		</div>
		<!-- page start-->
		<div class="row"><?php //echo $_SESSION['functional_role_id'];?>
		<div class="col-sm-12">
			<section class="panel">
				<?php 
					$sql="SELECT COUNT(survey.id) as total_publish_data FROM survey  WHERE survey.del_action='N' and survey.status='1' $qry $client_qry $pqry ";
					$getSurvey = mysqli_query($conn, $sql);
					$countdata=mysqli_fetch_array($getSurvey);
					$total_publish=$countdata['total_publish_data'];
					$sql="SELECT COUNT(survey.id) as total_unpublish_data FROM survey  WHERE survey.del_action='N' and survey.status='0' $qry $client_qry $pqry";
					$getSurvey = mysqli_query($conn, $sql);
					$countdata=mysqli_fetch_array($getSurvey);
					$total_unpublish=$countdata['total_unpublish_data'];
					$sqlcom="SELECT COUNT(survey.id) as total_complete_data FROM survey  WHERE survey.del_action='N' and survey.status='2' $qry $client_qry $pqry";
					$getSurveycom = mysqli_query($conn, $sqlcom);
					$countdatacom=mysqli_fetch_array($getSurveycom);
					$total_complete_data=$countdatacom['total_complete_data'];
					?>
				<header class="panel-heading">Total Forms: <?=$total_record?> || Published: <?=$total_publish?> || Unpublished: <?=$total_unpublish?> || Completed: <?=$total_complete_data?>
				</header>
				
				<div class="table-responsive">
					<table class="table table-striped new-design">
						<thead>
							<tr>
								<th>S.No</th>
								<th style="width:5%;">Form version</th>
								<th style="width:25%;">Form name [Form ID]</th>
								<?php if($_SESSION['role_id']==1){ ?>
								<th>Client name</th>
								<?php } ?>
								<th>Created on</th>
								<th># of user assigned</th>
								<th># of interviews</th>
								<th>Manage</th>
								<th>Visualization</th>
								<th>Status</th>
								<th>Action</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$_SESSION['query']="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data,survey.id,survey.form_version,survey.survey_name, survey.created_at,survey.client_id, survey.status, survey.user_id,survey.category_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry $pqry GROUP BY survey.id order by survey.created_at DESC";
								 $sql="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data,survey.id,survey.form_version,survey.project_id, survey.survey_name,survey.questinnour_file,survey.unique_id, survey.created_at,survey.client_id, survey.status, survey.user_id,survey.category_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry $pqry GROUP BY survey.id order by survey.created_at DESC limit $page,$per_page";
								$getSurvey = mysqli_query($conn, $sql);
								$sn=1+$page;
								while ($survey = mysqli_fetch_array($getSurvey)) {
								$surveys_name=$survey['survey_name'];
							?>
							<?php
								$digits = 10;
								$uniq_card_number = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);10;
								?>
							<tr>
								<td><?=$sn++;?></td>
								<td><?=$survey['form_version']?></td>
								<td><?= ucfirst($survey['survey_name']) ?> [<b><?= $survey['unique_id'] ?></b>]</td>
								<?php if($_SESSION['role_id']==1){ ?>
								<td><?= ucfirst($survey['client_name']) ?></td>
								<?php } ?>
								<td><?= date('d-M-Y', strtotime($survey['created_at'])); ?>
								</td>
								<?php 
									$qryUser=mysqli_query($conn,"SELECT COUNT(DISTINCT(user_id))  as total_assign_user FROM assign_survey where survey_id='".$survey['id']."' and status=0");
									$dataUser=mysqli_fetch_array($qryUser);
									
								?>
								<td class="text-center">
									<a href="" data-toggle="modal" data-target="#AssignUserdetails" onclick="assignUserdetails(<?=$survey['id'];?>)"; data-backdrop="static" data-keyboard="false" data-whatever="@fat">
									<?=$dataUser['total_assign_user'];?>
									</a>
								</td>
								<td class="text-center">
									<a href="survey-data-list.php?survey_id=<?= $survey['id'] ?>"><?= $survey['total_survey_data'] ?></a>
									<div id="copied-success" class="copied">
									  <span>Copied!</span>
									</div>
									</td>
								<td>
								<?php if ($survey['status']!=2){ ?>
									<div class="dropdown">
										<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										View
										<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="export-btn">
											<?php
												foreach($manageAllButtons as $manageAllButtonsVal){ ?>
													<?php if($manageAllButtonsVal['activity_name']=="Assign User"){ ?>
															<!--<li><a href="uploaded_questionnaire/<?=$survey['questinnour_file'] ?>" class=""><i class="<?=$manageAllButtonsVal['icon']?>" aria-hidden="true" style="color:#033D66;" ></i> <?=$manageAllButtonsVal['activity_name']?></a></li>-->
															<li><a href="" data-toggle="modal" data-target="#AssignUser" onclick="assignuser(<?=$survey['id'];?>)"; data-backdrop="static" data-keyboard="false" data-whatever="@fat"><i class="fa fa-tasks" aria-hidden="true"></i> Assign User</a></li>
													<?php	
														}else if($manageAllButtonsVal['activity_name']=="Advance"){ ?>
															
															<li><a href="<?=$manageAllButtonsVal['redirect_link']?>=<?= $survey['id'] ?>&btn=<?=$manageAllButtonsVal['btn_group']?>" <?=$manageAllButtonsVal['confirm_msg']?>  class=""><i class="<?=$manageAllButtonsVal['icon']?>" aria-hidden="true" style="color:#033D66;" ></i> <?=$manageAllButtonsVal['activity_name']?></a></li>
															
													<?php	
														}else if($manageAllButtonsVal['activity_name']=="Publish"){?>
															<li><a href="javascript:"  class="publishbtn" data-id="<?=$survey['id'];?>" ><i class="<?=$manageAllButtonsVal['icon']?>" aria-hidden="true" style="color:#033D66;" ></i> <?=$manageAllButtonsVal['activity_name']?></a></li>
														<?php	
														}else if($manageAllButtonsVal['activity_name']=="Unpublish"){?>
															<li><a href="javascript:"  class="unpublishbtn" data-id="<?=$survey['id'];?>" ><i class="<?=$manageAllButtonsVal['icon']?>" aria-hidden="true" style="color:#033D66;" ></i> <?=$manageAllButtonsVal['activity_name']?></a></li>
														<?php	
														}else{ ?>
														<li><a href="<?=$manageAllButtonsVal['redirect_link']?>=<?= $survey['id'] ?>&page=<?=$pages?>" <?=$manageAllButtonsVal['confirm_msg']?>  class=""><i class="<?=$manageAllButtonsVal['icon']?>" aria-hidden="true" style="color:#033D66;" ></i> <?=$manageAllButtonsVal['activity_name']?></a></li>
														
													<?php	
													}
												}
												
											?>
										</ul>
									</div>
									<?php } else { ?>
									<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
										View<span class="caret"></span>
									</button>
									<?php } ?>
								</td>
								<td>
								<?php if ($survey['status']==1){ ?>
									<div class="dropdown">
										<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Dashboard
										<span class="caret"></span>
										</button>
										
										<ul class="dropdown-menu" aria-labelledby="export-btn">
											<?php
												foreach($manageAllVisualButtons as $manageAllVisualButtonsVal){ ?>
													<?php if($manageAllVisualButtonsVal['activity_name']=="Advance"){ ?>
															<li><a href="<?=$manageAllVisualButtonsVal['redirect_link']?>=<?= $survey['id'] ?>&btn=<?=$manageAllVisualButtonsVal['btn_group']?>" class=""><i class="<?=$manageAllVisualButtonsVal['icon']?>" aria-hidden="true" style="color:#033D66;" ></i> <?=$manageAllVisualButtonsVal['activity_name']?></a></li>
												
													<?php }else{ ?>
													<li><a href="<?=$manageAllVisualButtonsVal['redirect_link']?>=<?= $survey['id'] ?>" class=""><i class="<?=$manageAllVisualButtonsVal['icon']?>" aria-hidden="true" style="color:#033D66;" ></i> <?=$manageAllVisualButtonsVal['activity_name']?></a></li>
												<?php
													}
												}
											?>
											<li><p id="<?=encrypt_url($survey['id'])?>" class="chide" >https://mquad.org/mis/externalApi.php/<?=encrypt_url($survey['id'])?></p>
											<a href="javascript:void(0)" onclick="copyToClipboard('#<?=encrypt_url($survey['id'])?>')" data-placement="top" data-toggle="tooltip" class="btn tooltips" data-original-title="External Dashboard" style="margin-right: 12px;"><i class="fa fa-copy" ></i> Data API</a>
											</li>
										</ul>
									</div>
									<?php } else { ?>
									<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
									Dashboard
									<span class="caret"></span>
									</button>
									<?php } ?>
								</td>
								<td>
									<?php if ($survey['status']==1){ ?>
									<div class="dropdown">
										<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Ongoing
										<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="export-btn">
											<?php 
												if(array_search("survey_ongoing", array_column($manageAllStatusButtons, 'button_name'))>=0){
													echo '<li><a href="#">Ongoing</a></li>';
												}
											?>

											<?php 
												if(array_search("survey_complete", array_column($manageAllStatusButtons, 'button_name'))>=0){ ?>
													<li><a href="" data-toggle="modal" data-target="#Modalcomplete" onclick="commsg(<?=$survey['id'];?>)"; data-backdrop="static" data-keyboard="false" data-whatever="@fat"><i class="fa fa-check" style="color:green"></i> Complete</a></li>
												<?php 
												}
											?>
										</ul>
									</div>
									<?php } else if($survey['status']==2){ ?>
									<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
									Completed
									<span class="caret"></span>
									</button>
									<?php } else { ?>
									<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="export-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" disabled>
									Ongoing
									<span class="caret"></span>
									</button>
									<?php } ?>
								</td>
								<td>
									<?php
										foreach ($manageAllDeleteButtons as $manageAllDeleteButtonsVal) { ?>
											<!--<a href="?delid=<?= $survey['id'] ?>" onclick="return confirm('Are you sure that you want to permanently delete the selected Form?');" data-placement="top" data-toggle="tooltip" class="btn-sm btn-danger tooltips" data-original-title="Delete survey" data-toggle="modal" data-target="#myModal<?= $survey['id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>-->
											<a href="" data-toggle="modal" data-target="#ModalDelSurvey" class="btn-sm btn-danger tooltips" onclick="delSurveylist(<?=$survey['id'];?>)"; data-backdrop="static" data-keyboard="false" data-whatever="@fat"><i class="fa fa-trash" aria-hidden="true"></i></a>
										<?php
										}
									?>
								</td>
								
							</tr>
							<?php
								$count++;
								}
								?>
						</tbody>
					</table>
					<!----------------------------------Start form Assign User------------------------------------------------>
					<div class="modal fade" id="AssignUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h1 class="modal-title" id="exampleModalLabel">
									<span>Assign User</span></h1>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form action="" method="POST" enctype="multipart/form-data">
									<div class="modal-body">
											<label style="margin-left:14px;">Select User</label>
										<div class="form-group">
											<input type="hidden" name="survey_id" id="survey_id" class="form-control " value="<?php echo $survey['survey_id'];?>" />
											<div class="col-lg-12" id="usertoassign" >
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
										<button type="submit" name="assign_user" class="btn btn-primary">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!----------------------------------End From Assign User------------------------------------------------>
					<!----------------------------------Start form Assign User------------------------------------------------>
					<div class="modal fade" id="AssignUserdetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h3 class="modal-title" id="exampleModalLabel">
										<span>Assigned User List</span>
									</h3>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form action="" method="POST" enctype="multipart/form-data">
								
								<input type="hidden" id="surveyId" class="form-control " value="<?php echo $survey['survey_id'];?>" />
									<div id="modal-body">
											
									</div>
									
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!----------------------------------End From Assign User------------------------------------------------>
					
						<!--------------------start Delete form data database---------------------------------->
					<div class="modal fade" id="ModalDelSurvey" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<span style="font-size:19px;">Are you sure that you want to permanently delete the selected Form?</span>
								</div>
								<form action="" method="POST" enctype="multipart/form-data" style="background-color: white;">
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="survey_id" id="surveyid" class="form-control" value="<?php echo $survey['id'];?>" />
											<div class="form-group ">
												<label for="cname" class="control-label" style="margin-left:14px;">Enter Email ID <span style="color:red">*</span></label>
												<div class="col-lg-12" >
													<input type="email" class="form-control" id="email" name="email" required />
													<span style="color:red">Please enter your registered Email ID to verify</span>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
										<button  type="submit" class="btn btn-primary" name="delteSurvey">Verify</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!--------------------End Delete the Data from Database---------------------------------->
					
					<!--------------------start complete status---------------------------------->
					
					<div class="modal fade" id="Modalcomplete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									
									<h1 class="modal-title" id="exampleModalLabel"><span style="color:red;">Are you sure to mark it complete?</span></h1>
								</div>
								<form action="" method="POST" enctype="multipart/form-data">
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="survey_id" id="survey_id" class="form-control" value="<?php echo $survey['id'];?>" />
											<!--<input type="text" name="msg" class="form-control" value="Description"/>-->
											<span> Once marked as <b>"Complete"</b>, you will not be able to make any further changes to the form.</span>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
										<button  class="btn btn-secondary"  data-toggle="modal" data-target="#Modalcompletemsg" data-backdrop="static" data-keyboard="false" data-whatever="@fat"  onclick="completemodels()"; >Yes</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!--------------------End start complete status---------------------------------->
					<!------------------study completion---------------------------------------------->
					<div class="modal fade" id="Modalcompletemsg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content" style="height: 300px;">
								<div class="modal-header">
									<h1 class="modal-title" id="exampleModalLabel" style="color:#394A59; font-weight: bold;">Study Completed</h1>
								</div>
								<div class="modal-body" style="color:#bd4141; background-color:white; height:230px;">
									<div class="form-group">
										<input type="hidden" name="survey_id" id="survey_id" class="form-control" value="<?php echo $survey['id'];?>" />
										<span><strong>Congratulations</strong> on completing the data collection. We hope you had a wonderful experience with MQUAD.</br></br> 
										At MQUAD, we promote exchange of data and tools with wider community. Given the information you are collecting is valuable, we encourage you to share the deidentified data whenever convenient to you. The deidentified data can be deposited to <u><a href="add-contribute-databank.php">Data Repository </a></u> at any time without any charge. In the meantime, we would greatly appreciate if you can upload the study tools and instruments into the <u><a href="add-contribute-surveybank.php">Tool Archives</a></u>. 
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
			</section>
		</div>
		</div>
		<!-- page end-->
		<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
			<div class="col-md-10">
				<div class="d-flex align-items-center justify-content-between" id="pagination">
					<?=paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>                               
				</div>
			</div>
			<?php 
				$_SESSION['file_name']='Form-list.csv';
				$_SESSION['header_column']="Survey Id,Survey Name,Client Name,No Of Interview,created_at";
				$_SESSION['db_column']="id,survey_name,client_name,total_survey_data,created_at";
				?>
			<div class=" col-md-2 text-right" style="margin-bottom: 0rem!important; padding-top: 5px">
				<a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
				<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
			</div>
		</div>
	</section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?> 
<!---------Datatable js------------>
<script src="js/jquery.dataTables.min.js"></script>  
<script src="js/dataTables.bootstrap.min.js"></script>  
<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.buttons.min.js"></script>
<script src="js/jszip.min.js"></script>
<script src="js/pdfmake.min.js"></script>
<script src="js/vfs_fonts.js"></script>
<script src="js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="js/buttons.colVis.min.js"></script>
<!---------Datatable js------------>
<?php if(isset($_SESSION['status']) && $_SESSION['status']!=''){ ?>
   <script>
		swal.fire({
		  title: "<?php echo $_SESSION['status'];?>",
		  icon:"<?php echo $_SESSION['status_code']; ?>",
		  confirmButtonColor: '#449A97',
		  confirmButtonText: 'Ok'
		});
	</script>
<?php unset($_SESSION['status']); }  ?>
<script>
	function commsg(val){
	 $("#survey_id").attr("value",val);
	}
	
	function delSurveylist(val){
	 $("#surveyid").attr("value",val);
	}
	function assignuser(val){
		$("#survey_id").attr("value",val);
		$.ajax({
			type:'post',
			url:'survey_list_ajax.php',
			data:{sur_id:val,process:'assign-user'},
			success:function(res){
				//console.log(res);
				$('#usertoassign').html(res);
				$(".check_multiselect").SumoSelect({selectAll:true,search:true});
			}
	   });
	}
	function assignUserdetails(val){
	$("#surveyId").attr("value",val);
	
		$.ajax({
			type:'post',
			url:'survey_list_ajax.php',
			data:{survey_id:val},
			success:function(res){
				//console.log(res);
				$("#modal-body").html(res);
				$('#employee_data').DataTable({
					/*dom: 'Bfrtip',
					buttons: [
						'csv'
					]
					*/
				}); 
                
			}
	   });
	}
	function completemodels(){
	   var yesId=$('#survey_id').val();
	       $.ajax({
	           type:'post',
	           url:'survey_list_ajax.php',
	           data:'surveyid='+yesId,
			success:function(responsedata){
	               // alert(responsedata);
	               $('#Modalcomplete').model('hide');
	           }
	       });
	}
</script>

<script>
	$(".multiple-select").select2({
	
	});
	function copyToClipboard(element) {
	  var $temp = $("<input>");
	  
	  $("body").append($temp);
	  $temp.val($(element).text()).select();
	  document.execCommand("copy");
	  $temp.remove();
	  
	  $('#copied-success').fadeIn(800);
	  $('#copied-success').fadeOut(800);
	}
	
	$(".unpublishbtn").on("click", function(){
		let surveyId = $(this).data("id");
		//alert(surveyId);
		//e.preventDefault();
		Swal.fire({
		  title: 'Are you sure to Unpublish this Form?',
		 // text: "You want to Unpublish this Form",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#3085d6',
		  confirmButtonText: 'Unpublish'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					type:'post',
					url:'survey_list_ajax.php',
					data:'suvId='+surveyId,
					success:function(res){
						console.log(res);
						var ress = JSON.parse(res);
						
						if(ress.status==1){
							customAlert('Unpublish Successfully',icon='success');
							//window.location.reload();
						}
						else{
							customAlert('Already Unpublish.',icon='success');
							//window.location.reload();
						}
					}
				});
			}
		});	
	});
	
	$(".publishbtn").on("click", function(){
		//let surveyId = $(this).data("id");
		//alert(surveyId);
		Swal.fire({
		  title: 'Are you sure to Publish this form?',
		  //text: "You want to Publish this form.",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#3085d6',
		  confirmButtonText: 'Publish'
		}).then((result) => {
		  if (result.isConfirmed) {
			//var process = "delete-chart";
			let ssidd = $(this).data("id");
			$.ajax({
				url:"https://mquad.org/mis/api/createquestionjson.php",
				type:"post",
				data:{ssidd:ssidd},
				beforeSend: function() {
				   $('.loading-indicator').addClass('active');
				},
				success:function(res){
					//console.log(res);
					//let ress = JSON.parse(res);
					$('.loading-indicator').removeClass('active');
					if(res.status=="1"){
						customAlert('Publish Successfully.',icon='success');
						//window.location.reload();
					}
					else{
						customAlert('Already Published.',icon='success');
						//window.location.reload();
					}
					
				}
			})
		  }
		});
	})
	
</script>


<?php
	/*
	if(isset($_REQUEST['publish']) && $_REQUEST['publish']!=""){ $ssidd = mysqli_real_escape_string($conn,$_REQUEST['publish']); ?>
		<script>
			var ssidd = "<?=$ssidd?>";
			//console.log(ssidd);
			$.ajax({
	           type:'post',
	           url:'https://mquad.org/mis/api/createquestionjson.php',
	           data:{ssidd:ssidd},
				success:function(responsedata){
	              // console.log(responsedata);
	               //$('#Modalcomplete').model('hide');
	           }
	       });
		</script>
	<?php	
	}
	
	
	if(isset($_REQUEST['publish']))
	{
		$urvey_id = mysqli_real_escape_string($conn, $_REQUEST['publish']);
		$sqlsurvey = mysqli_query($conn,"SELECT id,user_id,status FROM survey where id='".$urvey_id."' ");
		$getSurvey=mysqli_fetch_array($sqlsurvey);
		if($getSurvey['status']==0){
			$update = mysqli_query($conn,"UPDATE survey SET status='1' WHERE id='".$urvey_id."' ");
			if($update)
			{ 
				if($_REQUEST['page']==''){
					$requestpage='1';
					
				}else{
					$requestpage=$_REQUEST['page'];
				} 
				echo "<script>alert('The form has been successfully published')</script>";
				echo "<script> window.location.href='survey-list.php?&page=$requestpage';  </script>";
			}
		}elseif($getSurvey['status']==1){
			echo "<script>alert('This form is already Published')</script>";
		}
	} 
	*/
?>
<script>
$(document).ready(function() {
// Jquery code here ///
  var startdate;
  var enddate;
  $('#date1').datepicker({
    date_format:'yy-mm-dd'
  });
  $('#date2').datepicker({
    date_format:'yy-mm-dd'
  });

  $('#date1').change(function(){
    startdate=$(this).datepicker('getDate');
    $('#date2').datepicker('option','minDate',startdate);
  });
  $('#date2').change(function(){
    enddate=$(this).datepicker('getDate');
    $('#date1').datepicker('option','maxDate',enddate);
  });
});
</script>

