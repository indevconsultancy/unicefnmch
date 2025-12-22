<?php include('includes/config.php'); ?>
<?php define("title","Dashboard | MQUAD");?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>
<?php include_once('sscharts.php'); ?>
<?php

   $survey_id=mysqli_real_escape_string($conn, $_GET['survey_id']);
   $btn=mysqli_real_escape_string($conn, $_GET['btn']);
   $Visualization="Visualization";
  
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
 ?>

<style>
   .panel-heading{
   font-weight: bold!important;
   background: #394a59!important;
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
   .modal-body .form-group label {   
   display: block;
   }
   .select2-container {
   width: 100% !important;
   }
   .select2-container .select2-selection--single {
   height: 40px !important;
   }
   .select2-container--default .select2-selection--single .select2-selection__rendered {
   line-height: 40px !important;
   }
   .select2-container--default .select2-selection--single .select2-selection__arrow {
   height: 40px !important;
   }
   .modal-header {
   display: flex;
   justify-content: space-between;
   }
   
   .c-center-align {
   height: 200px;
   display: flex;
   align-items: center;
   justify-content: center;
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
	a.share{
		color: #fff;
       text-decoration: none;
	}
	a.share i{
    display: inline-block;
    width: 30px;
    height: 30px;
    text-align: center;    
    border: none;
    padding: 0;
    line-height: 30px;
    margin-top: 2px;
    background: #8cc63f;
    border-radius: 8vmax;
	}
	.btn-success{
		background-color: #8cc63f;
		border-color: #8cc63f;
		color: #ffffff;
	}
	.custom-panel .panel-heading {
		background: #8cc63f!important;
	}
	.custom-panel .panel-body {
		padding: 20px;
	}
	.box-width{
		width: 132rem;
	}	
	.box-width-v{
		width: 170rem;
	}
	.export-thumb .thumb-icon i{
		height: 67px;
		width: 66px;
		margin-right: 15px;
		padding: 3px;
		background: #033d66;
		border-radius: 50%;
		font-size: 30px;
		color: #fff;
		transition:all .3s ease-in-out;
	}
	.wclose{
		color: white;
		float: right;
		text-align: center;
	}
	.panel-footer{
		color: #000;
	}
	.panel-title{
		font-weight: bold;
	}
	
	
	
	
	
	
	
	.highcharts-figure,
	.highcharts-data-table table {
	  min-width: 310px;
	  max-width: 800px;
	  margin: 1em auto;
	}
	.highcharts-data-table table {
	  font-family: Verdana, sans-serif;
	  border-collapse: collapse;
	  border: 1px solid #ebebeb;
	  margin: 10px auto;
	  text-align: center;
	  width: 100%;
	  max-width: 500px;
	}

	.highcharts-data-table caption {
	  padding: 1em 0;
	  font-size: 1.2em;
	  color: #555;
	}

	.highcharts-data-table th {
	  font-weight: 600;
	  padding: 0.5em;
	}

	.highcharts-data-table td,
	.highcharts-data-table th,
	.highcharts-data-table caption {
	  padding: 0.5em;
	}

	.highcharts-data-table thead tr,
	.highcharts-data-table tr:nth-child(even) {
	  background: #f8f8f8;
	}

	.highcharts-data-table tr:hover {
	  background: #f1f7ff;
	}
	
</style>
<!--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->


<script src="<?=base_url();?>assets/highcharts/highcharts.js"></script>
<script src="<?=base_url();?>assets/highcharts/highcharts-more.js"></script>
<script src="<?=base_url();?>assets/highcharts/exporting.js"></script>
<script src="<?=base_url();?>assets/highcharts/export-data.js"></script>
<script src="<?=base_url();?>assets/highcharts/accessibility.js"></script>

<section id="main-content">
   <section class="wrapper">
   <div class="add-button-bg">
   <?php if($btn!='manage'){ ?>
    <!--<a href="" class="btn btn-fixed-circle" title="Add Indicator"  data-toggle="modal" data-target="#exampleModal" data-whatever="@fat" style="border-radius: 40px;"><i class="fa fa-plus"></i></a>-->
    <a href="surveydashboard.php?survey_id=<?=$survey_id;?>" class="btn btn-fixed-circle" style="border-radius: 40px;"><i class="fa fa-plus"></i></a>
   <?php } ?>
	</div>
      <div class="row">
         <div class="col-lg-12">
            <ol class="breadcrumb">
               <!-- <li><i class="fa fa-home"></i><a href="dashboard.php">Home </a></li> -->
                <li><i class="icon_documents_alt"></i>Form</li>
               <li><i class="icon_document_alt"></i>List Form </a></li>
               <li><i class="fa fa-laptop"></i> Dashboard</li>
            </ol>
         </div>
      </div>
     
      <div class="row">
         <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box dark-bg">
               <i class="icon_documents_alt"></i>
               <div class="count">
                  <?php  
                     $surveycollect=mysqli_query($conn,"SELECT count(*) total FROM `survey_data_monitoring`where survey_name_id='".$survey_id."'");
                     $survey_result=mysqli_fetch_array($surveycollect);
                     echo $survey_result['total'];
                     
                     ?>
                  <div class="title"><span style="color:white;">Form Collected</span></div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box green-bg">
               <i class="fa fa-check-circle" aria-hidden="true"></i>
               <div class="count">
                  <?php  
                     $surveyverify=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) as acc_total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_status='5' and survey_name_id='".$survey_id."'");
                     $survey_verify=mysqli_fetch_array($surveyverify);
                     echo $survey_verify['acc_total'];
                     
                     ?>
                  <div class="title"><span style="color:white;">Form Verified</span></div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box blue-bg">
               <i class="fa fa-users" aria-hidden="true"></i>
               <div class="count">
                  <?php
                     $usersql=mysqli_query($conn,"SELECT count(DISTINCT(user_id)) as total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_name_id='".$survey_id."'");
                     $data=mysqli_fetch_array($usersql);
                     echo $data['total'];
                     ?>
                  <div class="title"><span style="color:white;">Total Users</span></div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box brown-bg">
               <i class="icon_documents_alt"></i>
               <div class="count">
                  <?php
                     $today=date('y-m-d');
					 $todaysurvey=mysqli_query($conn,"SELECT count(survey_data_monitoring_id) as total_date  FROM `survey_data_monitoring` where survey_name_id='".$survey_id."' and date(created_on)='".$today."' ");
                     $data=mysqli_fetch_array($todaysurvey);
                     echo $data['total_date'];
                     ?>	
                  <div class="title"><span style="color:white;">Today Form Collected</span></i></div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <section class="panel">
               <div class="card">
					<?php 
                     $surveyqry=mysqli_query($conn,"SELECT survey_name,questionnaire_pdf,questinnour_file,clients.name as client_name FROM `survey` left join clients on survey.client_id=clients.id where survey.id='".$survey_id."'");
                     $surveydata=mysqli_fetch_array($surveyqry);
                     $survey_name=$surveydata['survey_name'];
                     $client_name=$surveydata['client_name'];
                     $questinnour_file=$surveydata['questinnour_file'];
                     $questionnaire_pdf=$surveydata['questionnaire_pdf'];
                     
                    ?>
			    
                  </header>
					<?php 
						$sidencode=base64_encode($survey_id);
						$actual_link=base_url()."dashboard_share.php?survey_id=".$sidencode;
					?>
                  <header class="panel-heading"> Survey Name: <?php echo $survey_name;?> || Client Name: <?=$client_name?>
                 <?php if($btn!='manage'){ ?> 
				 <a href="mailto:?Subject=Dashboard Share&amp;body=Click on the URL and Access Your Dashboard <?php echo $actual_link; ?>" class="share pull-right"><i class="fa fa-share-alt" aria-hidden="true" style="border:none;"></i> Share</a>
				 <?php } ?>
				</header>
			   </div>
            </section>
         </div>
		 <div class="col-md-12">
            <div class="panel-group" id="accordion">
			<?php if($btn=='manage'){ ?>
                <div class="panel panel-default custom-panel">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="font-size: 20px;">Advanced Manage Option
							<i class="fa fa-angle-down rotate-icon pull-right" style="margin-top: 4px;font-size: 30px;border: none;"></i>
							</a>
                        </h4>
                    </div>
					<style type="text/css">
						.export-thumb.home-export {
							flex-direction: column;
							justify-content: center;
							min-height: 165px;
							text-align: center;
						}
						.export-thumb.home-export  .thumb-icon i{
							margin-right:0;
							margin-bottom:10px;
						}
					</style>
					
					<div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">  
							<div class="row">
								<div class="col-md-2">	
									<a href="data-lookup.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
											<i class="fa fa-upload" aria-hidden="true"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="data-lookup.php?survey_id=<?=$survey_id;?>">Data lookup</a></div>
										</div>
									</div>
									</a>
								</div>
								<div class="col-md-2">
									<a href="media-lookup.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
											<i class="fa fa-file-archive-o" aria-hidden="true"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="media-lookup.php?survey_id=<?=$survey_id;?>">Media lookup</a></div>
										</div>
									</div>
									</a>
								</div>
								<div class="col-md-2">
									<a href="edit-question.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
											<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="edit-question.php?survey_id=<?=$survey_id;?>">Edit Form</a></div>
										</div>
									</div>
									</a>
								</div>
								<div class="col-md-2">	
									<a href="uploaded_questionnaire/<?=$questinnour_file; ?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
											<i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="uploaded_questionnaire/<?=$questinnour_file; ?>">Downlaod Tool</a></div>
										</div>
									</div>
									</a>
								</div>
								<div class="col-md-2">	
									<a href="uploaded_questionnaire/pdf/<?=$questionnaire_pdf; ?>" download >
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
											<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="uploaded_questionnaire/pdf/<?=$questinnour_file; ?>">Downlaod Tool PDF</a></div>
										</div>
									</div>
									</a>
								</div>
								
								<div class="col-md-2">	
									<a href="ss_skip.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
										<i class="fa fa-list"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="ss_skip.php?survey_id=<?=$survey_id;?>">E-Survey</a></div>
										 </div>
									</div>
									</a>
								</div>
								
							</div>
                        </div>
                    </div>
                </div>
				
				<?php 	} ?>
				<?php if($btn=='Visualization'){ ?>
				<div class="panel panel-default custom-panel">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapsethree" style="font-size: 20px;">Advanced Visualization Option
							<i class="fa fa-angle-down rotate-icon pull-right" style="margin-top: 4px;font-size: 30px;border: none;"></i>
							</a>
						</h4>
					</div>
					
					<div id="collapsethree" class="panel-collapse collapse in">
						<div class="panel-body">  
							<div class="row  box-width-v">
								<div class="col-md-2">	
									<a href="survey-location.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb">
										<div class="thumb-icon">
											<i class="fa fa-map-marker" aria-hidden="true"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="survey-location.php?survey_id=<?=$survey_id;?>">Walkthrough Map</a></div>
										</div>
									</div>
									</a>
								</div>
								<div class="col-md-2">
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
								<div class="col-md-2">	
									<a href="bulk_upload.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb">
										<div class="thumb-icon">
											<i class="fa fa-file-excel-o"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="bulk_upload.php?survey_id=<?=$survey_id;?>">Import to MQUAD</a></div>
										</div>
									</div>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
            </div>
        </div> 
      </div>
	  
		<div class="row">
	  	<?php
            $sid = $_REQUEST['survey_id'];
            $uid = $_SESSION['user_id'];
            $getCharts = mysqli_query($conn,"SELECT `id`, `title`, `description`, `fieldname`, `groupby`, `colors`, `user_id`, `survey_id`, `chart_name`, `chart_type`, `created_on`, `status` FROM `surveydashboard` WHERE  status='0' AND survey_id='".$sid."'  ORDER BY id DESC ");
            while($chart = mysqli_fetch_object($getCharts)){
				$surveyId=$chartName=$fieldname=$groupby="";
				$surveyId = $chart->survey_id;
				$chartName = $chart->chart_name;
				$chartType = $chart->chart_type;
				$colors=[];
				if(!empty($chart->colors)){ $colors = explode(",", $chart->colors); } 
				$fieldname = $chart->fieldname;
				$groupby = $chart->groupby;
        ?>
				<div class="col-md-6 " id="sss-<?=$chart->id;?>">
					<section class="panel ">
						<div class="card ">
							<div class="panel-heading">
								<div class="pull-left"><?=$chart->title;?></div>
								<div class="widget-icons pull-right">
									<a href="javascript:void(0);" data-id="<?=$chart->id;?>" class="wclose"  ><i class="fa fa-trash" style="border:none;color:#f3f37c;"></i></a>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="card-body ">
								<?php dynamic_graph($conn, $surveyId, $chartName, $chartType, $colors, $fieldname, $groupby,"");?>
							</div>
							<div class="panel-footer">
								
								<h4 class="panel-title">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$chart->id;?>">
										Description <span class="menu-arrow arrow_carrot-down"></span> <span style="float:right;" ><i class="fa fa-calendar"></i> <?=date("d-M-Y",strtotime($chart->created_on));?></span>
									</a>
								</h4>
								
								<div id="collapse<?=$chart->id;?>" class="panel-collapse collapse">
								<br>
								<p style="text-align:justify"><?=$chart->description?></p>
								</div>
		
							</div>
						</div>
					</section>
				</div>
         <?php	
            }
            ?>
          
		</div>
	</section>
</section>
<!--main content end-->

<?php include_once('includes/footer.php'); ?>
<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
<link href="<?=base_url();?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?=base_url();?>assets/sweetalerts/sweetalert2.all.min.js"></script>
<script>
$(".wclose").on("click", function(e){
	e.preventDefault();
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You want to Delete this survey chart.",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.isConfirmed) {
		var process = "delete-chart";
		let sdid = $(this).data("id");
		$.ajax({
			url:"ssajax.php",
			type:"post",
			data:{process:process,sdid:sdid},
			success:function(res){
				var ress = JSON.parse(res);
				//console.log(ress);
				if(ress.status=="1"){
					$("#sss-"+sdid).hide();
					Swal.fire(
					  'Deleted!',
					  'Graph deleted successfully.',
					  'success'
					)
				}
			}
		})
	  }
	});
})
$("#chart_type").click(function(){
 var chart_type=$("#chart_type").val();
// console.log(chart_type);
if(chart_type=='Pie' || chart_type=='Basic_area'){
	$("#hidden_div").hide();
}else{
	$("#hidden_div").show();
}
});
</script>

<?php 
if($questionnaire_pdf==""){
	$survey_id = $_REQUEST['survey_id'];
?>

<script>
$(document).ready(function(){
	var ssId = "<?=$survey_id?>";
	$.ajax({
		url:"https://mquad.org/mis/gpdf.php",
		type:"get",
		data:{survey_id:ssId},
		success:function(res){
			alert('PDF Created');
		}
	});
});
</script>

<?php
}
?>




