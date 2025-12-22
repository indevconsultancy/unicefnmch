 <?php include('includes/config.php'); ?>
 <?php define("title","Dashboard | MQUAD");?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>

<!--main content start-->
<?php
	$client_qry="";
	$clientsqry='';
	//if($_SESSION['role_id']=="3" || $_SESSION['role_id']=="7"){
	if($_SESSION['role_id']=="3"){
		// $_SESSION['user_id'];
		$client_id = $_SESSION['client_id'];
		$client_qry=" and survey_data_monitoring.client_id='".$client_id."' ";
		$client_qry1=" and survey.client_id='".$client_id."' ";
		$clientqry=" and clients.id='".$client_id."' ";
		$clientsqry=" and client_id='".$client_id."' ";
	}
	if($_SESSION['role_id']=="9"){  //TL
		// $_SESSION['user_id'];
		$user_id = $_SESSION['user_id']; //
		$client_qry=" and survey_data_monitoring.survey_name_id in(SELECT DISTINCT(survey_id) AS survey_id FROM assign_survey WHERE status='0' AND user_id='".$user_id."') ";
		//$clientqry=" and clients.id='".$client_id."' ";
	}
?>
<?php 
	//$client_qry="";
	if($_SESSION['role_id']=="7"){
		$client_id = $_SESSION['client_id'];
		$client_qry=" and survey_data_monitoring.client_id='".$client_id."' ";
		$clientqry=" and clients.id='".$client_id."' ";
	}
?>
<?php
	$qry='';
	if (isset($_REQUEST['search'])){
		 if(isset($_REQUEST['survey_name_id']) && $_REQUEST['survey_name_id']!='') {
			$qry.= " AND survey_data_monitoring.survey_name_id='".$_REQUEST['survey_name_id']."'";
			}
		if(isset($_REQUEST['client_id']) && $_REQUEST['client_id']!='') {
            $qry.= " AND clients.id='".$_REQUEST['client_id']."'";
        }
	}
?>

<style type="text/css">
  .panel-heading{
    font-weight: bold!important;
    background: #033d66!important;
    color: white!important;
	 }
.info-box i {
    display: block;
    height: 40px;
    font-size: 60px;
    line-height: 60px;
    width: 70px;
    float: left;
    text-align: center;
    margin-right: 80px;
    padding-right: 20px;
    color: rgba(255, 255, 255, 0.75);
	}
</style>
<section id="main-content">
  <section class="wrapper">
    <div class="row">
      <div class="col-lg-12">
        <ol class="breadcrumb">
          <li><i class="fa fa-home"></i><a href="dashboard.php">Home </a></li>
        </ol>
      </div>
    </div>
	<!--------filter start------->
		<div class="container-fluid">
          
				<div class="filter_css clearfix">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<section class="panel">
								<div class="panel panel-default"> 
									<div class="panel-body homemain-icotabs">
										<div class="row">	  
											<div  class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
												<a href="add-survey.php">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-wpforms" aria-hidden="true"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="add-survey.php">Add Form</a></div>
													</div>
												</div>
												</a>
											</div>
											<div  class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
												<a href="add-user.php">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-user-plus" aria-hidden="true"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="add-user.php">Add User</a></div>
													</div>
												</div>
												</a>
											</div>
											<div  class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
												<a href="edit-question.php?survey_id=<?=$survey_id;?>">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="edit-question.php?survey_id=<?=$survey_id;?>">Edit Form</a></div>
													</div>
												</div>
												</a>
											</div>
											<div  class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
												<a href="ss_skip.php?survey_id=<?=$survey_id;?>">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
													<i class="fa fa-list"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="ss_skip.php?survey_id=<?=$survey_id;?>">Online Survey Form</a></div>
													 </div>
												</div>
												</a>
											</div>
											<div  class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
												<a href="data-lookup.php?survey_id=<?=$survey_id;?>">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-upload" aria-hidden="true"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="data-lookup.php?survey_id=<?=$survey_id;?>">Upload Sampling Information</a></div>
													</div>
												</div>
												</a>
											</div>
											<div  class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
												<a href="media-lookup.php?survey_id=<?=$survey_id;?>">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-file-archive-o" aria-hidden="true"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="media-lookup.php?survey_id=<?=$survey_id;?>">Upload Media Resources</a></div>
													</div>
												</div>
												</a>
											</div>

											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
												<a href="uploaded_questionnaire/<?=$questinnour_file; ?>">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="uploaded_questionnaire/<?=$questinnour_file; ?>">Download Tool (Excel) </a></div>
													</div>
												</div>
												</a>
											</div>
											<div  class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
												<a href="uploaded_questionnaire/pdf/<?=$questionnaire_pdf; ?>" download >
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="uploaded_questionnaire/pdf/<?=$questinnour_file; ?>">Download Tool (PDF) </a></div>
													</div>
												</div>
												</a>
											</div>
											<div  class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
												<a href="survey-data-list.php?survey_id=<?=$survey_id;?>">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-file-text-o"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="survey-data-list.php?survey_id=<?=$survey_id;?>">Review Data</a></div>
													</div>
												</div>
												</a>
											</div>
											<div  class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
												<a href="bulk_upload.php?survey_id=<?=$survey_id;?>">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-file-excel-o"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="bulk_upload.php?survey_id=<?=$survey_id;?>">Import Data</a></div>
													</div>
												</div>
												</a>
											</div>

											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">	
												<a href="survey-location.php?survey_id=<?=$survey_id;?>">
												<div class="info-box main-bg export-thumb">
													<div class="thumb-icon">
														<i class="fa fa-map-marker" aria-hidden="true"></i>
													</div>
													<div class="count">
													  <div class="title"><a href="survey-location.php?survey_id=<?=$survey_id;?>">Map View</a></div>
													</div>
												</div>
												</a>
											</div>
										</div>
									</div>									
								</div>
							</section>
						</div>
					</div>
					
					
					
					
					
					
					
					
				</div>
			
        </div>
		
	<!--------filter end------->
    <!-- page start-->
	
	
	
	
	  
		<!-- page end-->
		
		</div>
	</div>
  </section>
  </section>
</section>


<!--Dependancy end code--->
<?php include_once('includes/footer.php'); ?>