 <?php include('includes/config.php'); ?>
  <?php define("title","Home | MQUAD");?>
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
		<style type="text/css">
		  .dash-card{
			display: flex;
			flex-direction: column;
			border: 1px solid #eee;
			height: 100%;
			border-radius: 4px;
			overflow: hidden;
			min-height: 176px;
			margin-bottom: 30px;
			background: #f5f5f5;
		  }
		  .dash-card .dash-head{
			  display: flex;
			  align-items: center;
			  background: #61b7b8;
			  padding: 3px 10px;
		  }
		  .dash-card .dash-head h5{
			  font-size: 15px;
			  margin: 0;
			  font-weight: 700;
			  color: #fff;
			  
		  }
		  .dash-card .dash-head .thumb-icon > div{
			  display: flex;
			   align-items: center;
			   justify-content: center;
			  width:40px;
			  height: 40px;
			  border-radius: 50%;
			  border:1px solid #a1c3aa;
			   margin-right: 10px;
			  background: #a1c3aa;
		  }
		  .dash-card .dash-head .thumb-icon > div > i{
			  font-size:18px;
			  color: #fff;
		  }
		  
		  .dash-card .dash-body{
			  background: #f5f5f5;
			  padding:10px;
		  }
		  .dash-card .dash-body > ul{
			  padding-left:0;
			  margin-bottom: 0;
		  }
		  .dash-card .dash-body > ul > li:not(:last-child) {
				margin-bottom: 6px;
			}
		  .dash-card .dash-body > ul > li > .accord-head{
			  background: #FFFFFF;
			  padding: 5px 8px;
			  border: 1px solid #e3e3e3;
			  padding-right: 30px;
			  position: relative;
			  display: block;
			  overflow: hidden;
			  transition:all .3s ease-in-out;
		  }
		   .dash-card .dash-body > ul > li > .accord-head > a{
			  transition: all .3s ease-in-out;
		  }
		  .dash-card .dash-body > ul > li > .accord-head > a:hover{
				color: #8cc63f;
			}
		  .dash-card .dash-body > ul > li.hasChild .accord-head .tgltab{
			  display: inline-block;
			  float:right;
		  }
		  .dash-card .dash-body > ul > li.hasChild .accord-head .tgltab:before{
			  content:"\f107";
			  font-family: 'FontAwesome';
			  display: inline-block;
			  right: 13px;
			  top:6px;
			  position: absolute;
			  cursor: pointer;
		  }
		  .dash-card .dash-body > ul > li > .accord-body{
			  border: 1px solid #747474;
			  padding: 5px 8px;
			  display: none;
			  background: #fff; /*#8ed2c3;*/
		  }
		  .dash-card .dash-body > ul > li > .accord-body .inner-boxes{
			  margin: 0 -4px;
			  display: flex;
			  flex-wrap: wrap;				  
		  }
		  .dash-card .dash-body > ul > li > .accord-body .inner-boxes > a {
				padding: 3px 6px;
				display: flex;
				background: #ffffff;
				margin: 0 1px;
				align-items: flex-start;
				border-radius: 2px;
				margin-bottom: 3px;
				color: #6b6969;
				font-size: 12px;
				line-height: 13px;
				transition: all .3s ease-in-out;
				border: 1px solid #eee;
		  }
		  .dash-card .dash-body > ul > li > .accord-body .inner-boxes > a:hover {
				background: #449a97;
				color: #fff;
				border-color:#449a97;
		  }
		  .dash-card .dash-body > ul > li > .accord-body .inner-boxes > a > i {
				margin-right:5px;
		  }
		  .dash-card .dash-body > ul > li:last-child .accord-head{
		/*				  border-bottom: none;*/
		  }
		  .dash-card .dash-body > ul > li.active > .accord-head {
				background: #747474;
				color: #fff;
				border: none;
		  }
		   .dash-card .dash-body > ul > li.active .accord-head > a {
			 color: #fff;
		  }
		</style>
		<div class="container-fluid">
			<div class="filter_css clearfix">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<section class="panel">
							<div class="panel panel-default"> 
								<div class="panel-body homemain-icotabs">
									<div class="row">
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-wpforms" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">Project Management</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="add-project.php">
																	<i class="fa fa-plus"></i> Add Project
																</a>
															</div>																
														</li>
														<li>
															<div class="accord-head">
																<a href="project-list.php">
																	<i class="fa fa-list-ul"></i> List Project
																</a>
															</div>
														</li>															
													</ul>
												</div>
											</div>												
										</div>
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-wpforms" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">Form Management</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="add-survey.php">
																	<i class="fa fa-plus"></i> Add Form
																</a>
															</div>																
														</li>
														<li>
															<div class="accord-head">
																<a href="survey-list.php">
																	<i class="fa fa-list-ul"></i> List Forms
																</a>
															</div>
														</li>
														<li>
															<div class="accord-head">
																<a href="validate_xlsform.php">
																	<i class="fa fa-search"></i> Validate Excel Form
																</a>
															</div>	
															
														</li>
													</ul>
												</div>
											</div>												
										</div>
										<?php if($_SESSION['role_id']==1){?>
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-handshake-o" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">Client Management</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="registration.php">
																	<i class="fa fa-plus"></i> Add Client
																</a>
															</div>																
														</li>
														<li>
															<div class="accord-head">
																<a href="client-list.php">
																	<i class="fa fa-list-ul"></i> List Clients
																</a>
															</div>
														</li>
													</ul>
												</div>
											</div>												
										</div>
										<?php } ?>
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa fa-users" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">User Management</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="add-user.php">
																	<i class="fa fa-plus"></i> Add User
																</a>
															</div>																
														</li>
														<li>
															<div class="accord-head">
																<a href="user-list.php">
																	<i class="fa fa-list-ul"></i> List Users
																</a>
															</div>
														</li>
													</ul>
												</div>
											</div>												
										</div>
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-question-circle" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">Question Bank</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="add-question-bank.php">
																	<i class="fa fa-plus"></i> Add Question
																</a>
															</div>																
														</li>
														<li>
															<div class="accord-head">
																<a href="question-bank-list.php">
																	<i class="fa fa-list-ul"></i> Show Questions
																</a>
															</div>
														</li>
													</ul>
												</div>
											</div>												
										</div>
										
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-wrench" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">Tools Archive</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="add-contribute-surveybank.php">
																	<i class="fa fa-plus"></i> Add Tool
																</a>
															</div>																
														</li>
														<li>
															<div class="accord-head">
																<a href="survey_bank.php">
																	<i class="fa fa-list-ul"></i> Show Tool
																</a>
															</div>
														</li>
													</ul>
												</div>
											</div>												
										</div>
										
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-newspaper-o" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">Data Repository</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="add-contribute-databank.php">
																	<i class="fa fa-plus"></i> Add Dataset
																</a>
															</div>																
														</li>
														<li>
															<div class="accord-head">
																<a href="data_bank.php">
																	<i class="fa fa-list-ul"></i> Show Dataset
																</a>
															</div>
														</li>
													</ul>
												</div>
											</div>												
										</div>
										
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-users" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">Sampling</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="simple-random-samling.php">
																	<i class="fa fa-plus"></i> Simple Random Sampling 
																</a>
															</div>																
														</li>
														<li>
															<div class="accord-head">
																<a href="systematic_random.php">
																	<i class="fa fa-plus"></i> Systematic Random Sampling
																</a>
															</div>																
														</li>
													</ul>
												</div>
											</div>												
										</div>
										
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="icon_document_alt" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">Resources</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li>
															<div class="accord-head">
																<a href="help_file.php">
																	<i class="fa fa-flag-checkered"></i> Help
																</a>
															</div>																
														</li>
														<li>
															<div class="accord-head">
																<a href="faqs.php">
																	<i class="fa fa-wechat"></i> Frequently Asked Question
																</a>
															</div>
														</li>
														<li>
															<div class="accord-head">
																<a href="#">
																	<i class="fa fa-wechat"></i>MQUAD Community
																</a>
															</div>
														</li>
													</ul>
												</div>
											</div>												
										</div>
										<?php if($_SESSION['role_id']==1){?>
										<div  class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
											<div class="dash-card">
												<div class="dash-head">
													<div class="thumb-icon">
														<div>
															<i class="fa fa-cogs" aria-hidden="true"></i>
														</div>															 
													</div>
													<h5 class="title">Settings</h5>
												</div>
												<div class="dash-body">
													<ul>
														<li class="hasChild">
															<div class="accord-head">
																
																	<i class="fa fa-info-circle"></i> Information Area
																
																<span class="tgltab"></span>
															</div>
															<div class="accord-body">
																<div class="inner-boxes">
																	<a href="category-list.php">																		
																		<i class="fa fa-list-ul"></i> List Information Area							
																	</a>
																</div>
															</div>
														</li>
														<li class="hasChild">
															<div class="accord-head">
																	<i class="fa fa-pain-brush"></i> Theme
																<span class="tgltab"></span>
															</div>
															<div class="accord-body">
																<div class="inner-boxes">
																	<a href="theme-list.php">																		
																		<i class="fa fa-list-ul"></i>List Theme							
																	</a>
																</div>
															</div>
														</li>
													</ul>
												</div>
											</div>												
										</div>
										<?php } ?>
									</div>
								</div>									
							</div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
  </section>
  </section>
<!--Dependancy end code--->
<?php include_once('includes/footer.php'); ?>

<script>
$(document).ready(function (){
  // Delete that line if you don't want the first Div to be displayed by default
  //$("div:first").css("display", "block");
  
  // 
  $(".tgltab").click(function () {
	//console.log($(this).parent().parent());
	if($(this).parent().parent().hasClass('active')){
		$(this).parent().parent().removeClass('active')
	}else{
		$(this).parent().parent().addClass('active')
	}
	
    $(this).parent().parent().find('.accord-body').slideToggle(500);
	  
	
	//$(".dash-body li").not($(this).parent().parent()).find('.accord-body').slideUp(500);
	//$(".dash-body li").not($(this).parent().parent()).removeClass('active');
	  
    
  });
	
  $('.dash-body ul li.hasChild:not(.noAct)').each(function(){
	  $(this).addClass('active');
	  $(this).find('.accord-body').css('display','block');
  })
  
});
</script>