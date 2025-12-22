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
  <link href="style.css" rel="stylesheet">
<script src="<?=base_url();?>assets/highcharts/highcharts.js"></script>
<script src="<?=base_url();?>assets/highcharts/highcharts-more.js"></script>
<script src="<?=base_url();?>assets/highcharts/exporting.js"></script>
<script src="<?=base_url();?>assets/highcharts/export-data.js"></script>
<script src="<?=base_url();?>assets/highcharts/accessibility.js"></script>
	<style type="text/css">
	.export-thumb.home-export {
		flex-direction: column;
		justify-content: center;
		min-height: 195px;
		text-align: center;
	}
	.export-thumb.home-export  .thumb-icon i{
		margin-right:0;
		margin-bottom:10px;
	}
	.chide{
		display:none;
	}
	.copied {
	  font-family: 'Montserrat', sans-serif;
	  width: 75px;
	  display: none;
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

</style>
<div id="pre-load" class="loading-indicator">
	<div id="loader" class="loader">
	   <div class="loader-container">
		   <div class='loader-icon'><img src="https://mquad.org/mis/img/mquad-logo.png" alt=""></div>
	   </div>
	</div> 
	<!--<span style="font-weight:200px;color:white;margin-top:20px;">Please wait...</span>-->
</div>
<section id="main-content" class="graphArea">
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
            <div class="info-box oneColor">
               <i class="icon_documents_alt"></i>
               <div class="count">
                  <?php  
                     $surveycollect=mysqli_query($conn,"SELECT count(*) total FROM `survey_data_monitoring`where survey_name_id='".$survey_id."'");
                     $survey_result=mysqli_fetch_array($surveycollect);
                     echo $survey_result['total'];
                     
                     ?>
                  <div class="title"><span style="color:white;">Forms Collected</span></div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box twoColor">
               <i class="fa fa-check-circle" aria-hidden="true"></i>
               <div class="count">
                  <?php  
                     $surveyverify=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) as acc_total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_status='5' and survey_name_id='".$survey_id."'");
                     $survey_verify=mysqli_fetch_array($surveyverify);
                     echo $survey_verify['acc_total'];
                     
                     ?>
                  <div class="title"><span style="color:white;">Forms Verified</span></div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box threeColor">
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
            <div class="info-box fourColor">
               <i class="icon_documents_alt"></i>
               <div class="count">
                  <?php
                     $today=date('y-m-d');
					 $todaysurvey=mysqli_query($conn,"SELECT count(survey_data_monitoring_id) as total_date  FROM `survey_data_monitoring` where survey_name_id='".$survey_id."' and date(created_on)='".$today."' ");
                     $data=mysqli_fetch_array($todaysurvey);
                     echo $data['total_date'];
                     ?>	
                  <div class="title"><span style="color:white;">Number of form collected today</span></i></div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <section class="panel">
               <div class="card">
					<?php 
                     $surveyqry=mysqli_query($conn,"SELECT survey_name,questionnaire_pdf,questinnour_file,clients.name as client_name,clients.id as client_id,survey.form_version FROM `survey` left join clients on survey.client_id=clients.id where survey.id='".$survey_id."' $client_qry1");
                     $surveydata=mysqli_fetch_array($surveyqry);
                     $survey_name=$surveydata['survey_name'];
                     $client_name=$surveydata['client_name'];
                     $questinnour_file=$surveydata['questinnour_file'];
                     $questionnaire_pdf=$surveydata['questionnaire_pdf'];
					 $form_version=$surveydata['form_version'];
                     $client_id=$surveydata['client_id'];
                     
                     $cid="C".$client_id;
					 $url="https://mquad.org/mis/uploaded_questionnaire/pdf/".$cid."/".$questionnaire_pdf;
                    ?>
			    
                  </header>
					<?php 
						$sidencode=base64_encode($survey_id);
						$actual_link=base_url()."dashboard_share.php?survey_id=".$sidencode;
					?>
                  <header class="panel-heading"> Form Name: <?php echo $survey_name;?> <?php if($_SESSION['role_id']!='3'){?>|| Client Name: <?php echo $client_name;?> <?php } ?>
                 <?php if($btn!='manage'){ ?> 
				 <a href="mailto:?Subject=Dashboard Share&amp;body=You can access your dashboard by clicking on the URL <?php echo $actual_link; ?>" style="margin:1px;" class="share pull-right"><i class="fa fa-share-alt" aria-hidden="true" style="border:none;"></i> Share</a>
				<a href="javascript:void(0)" onclick="Convert_HTML_To_PDF()"; style="margin:4px;" class="btn-secondary btn-sm pull-right"><i class="fa fa-print"></i> Print</a>
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
					 
					<div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">  
							<div class="row">
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
									<a href="ss_skip.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
										<i class="fa fa-list"></i>
										</div>
										<div class="count">
										  <div class="title">
											<a href="ss_skip.php?survey_id=<?=$survey_id;?>">Web Form</a>
											</div>
										 </div>
									</div>
									</a>
								</div>
								
								<div class="col-md-2">	
									<a href="data-lookup.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
											<i class="fa fa-upload" aria-hidden="true"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="data-lookup.php?survey_id=<?=$survey_id;?>">Upload Lookup Data</a></div>
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
										  <div class="title"><a href="media-lookup.php?survey_id=<?=$survey_id;?>">Upload Media Resources</a></div>
										</div>
									</div>
									</a>
								</div>
								
								<div class="col-md-2">	
									<a href="uploaded_questionnaire/<?=$cid?>/<?=$questinnour_file; ?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
											<i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="uploaded_questionnaire/<?=$cid?>/<?=$questinnour_file; ?>">Download Tool (Excel) </a></div>
										</div>
									</div>
									</a>
								</div>
								<div class="col-md-2">	
										<!--<a href="uploaded_questionnaire/pdf/<?=$cid?>/<?=$questionnaire_pdf; ?>" target="_blank">-->
										<a href="javascript:void(0)" class="downloadPDF" data-id="gpdf.php?survey_id=<?=$survey_id;?>">
										<div class="info-box main-bg export-thumb home-export">
											<div class="thumb-icon">
												<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
											</div>
											<div class="count">
											  <div class="title"><a href="javascript:void(0)" class="downloadPDF" data-id="gpdf.php?survey_id=<?=$survey_id;?>">Download Tool (PDF)</a></div>
											</div>
										</div>
									</a>
								</div>
								<div class="col-md-2">
									<a href="survey-data-list.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
											<i class="fa fa-file-text-o"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="survey-data-list.php?survey_id=<?=$survey_id;?>">Review Data</a></div>
										</div>
									</div>
									</a>
								</div>
								<!--
								<div class="col-md-2">	
									<a href="bulk_upload.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
											<i class="fa fa-file-excel-o"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="bulk_upload.php?survey_id=<?=$survey_id;?>">Import Data</a></div>
										</div>
									</div>
									</a>
								</div>
								-->
								<div class="col-md-2">	
									<a href="assign_question.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb home-export">
										<div class="thumb-icon">
										<i class="fa fa-tasks"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="assign_question.php?survey_id=<?=$survey_id;?>">Assign question to question bank</a></div>
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
									<a href="survey-data-list.php?survey_id=<?=$survey_id;?>">
									<div class="info-box main-bg export-thumb">
										<div class="thumb-icon">
											<i class="fa fa-tachometer"></i>
										</div>
										<div class="count">
										  <div class="title"><a href="survey_dashboard.php?survey_id=<?=$survey_id;?>">Dashboard</a></div>
										</div>
									</div>
									</a>
								</div>
								<div class="col-md-2">	
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
				</div>
				<?php } ?>
            </div>
        </div> 
      </div>
	  
		<div class="row">
	  	<?php
            $sid = $_REQUEST['survey_id'];
            $uid = $_SESSION['user_id'];
            $getCharts = mysqli_query($conn,"SELECT `id`, `title`, `description`,`xtitle`, `ytitle`, `fieldname`, `groupby`, `colors`, `user_id`, `survey_id`, `chart_name`, `chart_type`, `created_on`, `status` FROM `surveydashboard` WHERE  status='0' AND survey_id='".$sid."'  ORDER BY id DESC ");
            while($chart = mysqli_fetch_object($getCharts)){
				$surveyId=$chartName=$fieldname=$groupby="";
				$surveyId = $chart->survey_id;
				$chartName = $chart->chart_name;
				$chartType = $chart->chart_type;
				$colors=[];
				if(!empty($chart->colors)){ $colors = explode(",", $chart->colors); } 
				$fieldname = $chart->fieldname;
				$groupby = $chart->groupby;
				$xTitle =  $chart->xtitle;
				$yTitle =  $chart->ytitle;
				$titles = array('xAxisTitle'=>$xTitle, 'yAxisTitle'=>$yTitle);
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
								<?php dynamic_graph($conn, $surveyId, $chartName, $chartType, $colors, $fieldname, $groupby,"",$titles);?>
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
<link href="<?=base_url();?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?=base_url();?>assets/sweetalerts/sweetalert2.all.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<script>

  function Convert_HTML_To_PDF() {
//	  html2canvas($('#content-sss'), {
	var HTML_Width = $(".graphArea").width();
    var HTML_Height = $(".graphArea").height();
    var top_left_margin = 15;
    var PDF_Width = HTML_Width + (top_left_margin * 2);
    var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;

    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

    html2canvas($(".graphArea")[0]).then(function (canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) { 
            pdf.addPage(PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);
        }
        pdf.save("dashboard.pdf");
       // $("#content-sss").hide();
    });
  
  }
  

</script>
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
				console.log(ress);
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

</script>
<?php 
 if($questionnaire_pdf==""){
	//$questionnaire_pdf=true;
//if($questionnaire_pdf){
	$survey_id = $_REQUEST['survey_id'];
?>
<script>
$(document).ready(function(){
	$(".downloadPDF").on("click", function(){
		var ssuId =$(this).data("id");
		
		$.ajax({
			type:'post',
			url:ssuId,
			data:{},
			beforeSend: function() {
			  $('.loading-indicator').addClass('active');
			},
			success:function(res){
				$('.loading-indicator').removeClass('active');
				var res = JSON.parse(res);
				if(res.status=="1"){
					var urlpdf=res.url;
					window.open(urlpdf+'?type=individual','_blank');
					window.location.reload();
				}
			}
		});
	})
})
</script>
<?php
} else if($form_version!="V1"){
	//$questionnaire_pdf=true;
	//if($questionnaire_pdf){
 	$survey_id = $_REQUEST['survey_id'];	
?>
<script>
	$(".downloadPDF").on("click", function(){
		var ssuId =$(this).data("id");
		$.ajax({
			type:'post',
			url:ssuId,
			data:{},
			beforeSend: function() {
			  $('.loading-indicator').addClass('active');
			},
			success:function(res){
				var res = JSON.parse(res);
				var urlpdf=res.url;
				window.open(urlpdf+'?type=individual','_blank');
				window.location.reload();
				$('.loading-indicator').removeClass('active');
			}
		});
	})

</script>

<?php
}else{ ?>
	<script>
	$(".downloadPDF").on("click", function(){
		var urlpdf = "<?=$url?>";
		if(urlpdf!=''){
			window.open(urlpdf+'?type=individual','_blank');
			// window.location.reload();
		}
	})
	</script>
<?php } ?>




