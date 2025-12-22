<?php include('includes/config.php'); ?>
<?php define("title","View Dashboard | MQUAD");?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>
<?php include_once('sscharts.php'); ?>
<?php
   $survey_id=mysqli_real_escape_string($conn, $_GET['survey_id']);
?>
<?php
	$qry='';
	if (isset($_REQUEST['search'])){
		if(isset($_REQUEST['survey_id']) && $_REQUEST['survey_id']!='') {
			$qry.= " AND survey_id='".$_REQUEST['survey_id']."'";
		}
	}
?>
<?php
$client_qry1="";
	if($_SESSION['functional_role_id']=="3"){	
		$client_qry1=" and survey.client_id='".$_SESSION['client_id']."' ";
	}
	if($_SESSION['functional_role_id']!=3 && $_SESSION['functional_role_id']!=1){
		$user_id=$_SESSION['user_id'];
		$client_qry1=" and survey_data_monitoring.survey_name_id in(SELECT DISTINCT(survey_id) AS survey_id  FROM assign_survey WHERE user_id='".$user_id."') ";
	}
?>

<script src="<?=base_url();?>assets/highcharts/highcharts.js"></script>
<script src="<?=base_url();?>assets/highcharts/highcharts-more.js"></script>
<script src="<?=base_url();?>assets/highcharts/exporting.js"></script>
<script src="<?=base_url();?>assets/highcharts/export-data.js"></script>
<script src="<?=base_url();?>assets/highcharts/accessibility.js"></script>
<style>
#main-content .wrapper .row{
	margin-bottom:0px;
}
.panel{
	margin-bottom: 20px;
}
.panel .panel-heading {
    margin-top: -10px;
}
</style>
<section id="main-content" class="graphArea" >
	<section class="wrapper">
	<?php if($survey_id!='' && $_SESSION['functional_role_id']=="3") { ?>
		<div class="add-button-bg">
			<a href="surveydashboard.php?survey_id=<?=$survey_id;?>" class="btn btn-fixed-circle" style="border-radius: 40px;"><i class="fa fa-plus"></i></a>
		</div>
	<?php } ?>	
		<div class="row">
			<div class="col-lg-12">
            <ol class="breadcrumb">
               <li><i class="fa fa-laptop"></i>View Dashboard</li>
            </ol>
			</div>
		</div>
      <!--------filter start------->
		<div class="container-fluid" >
			<form class="form-inline" method="get" role="form">
				<div class="row filter_css clearfix">
					<div class="form-group col-md-10" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
						<select class="form-control select2"  name="survey_id" id="survey_id">
							 <option value="">Select Form</option>
							<?php
								$sqlservey="SELECT DISTINCT(id),survey.survey_name,created_at FROM survey inner join survey_data_monitoring on survey_data_monitoring.survey_name_id=survey.id where survey.del_action='N' $client_qry1 order by survey.id DESC";
								$selectservey=mysqli_query($conn,$sqlservey);
								while($surveydata=mysqli_fetch_array($selectservey)){ ?> 
							  <option value="<?php echo $surveydata['id'];?>"<?php if($surveydata['id']==$_REQUEST['survey_id']){echo "selected";}?>><?php echo "".$surveydata['survey_name']." (".date("j-F-Y",strtotime($surveydata['created_at'])).")";?></option>
							 <?php }  ?>
						</select>
					</div>
					
					<div class="form-group col-md-2" style="margin-bottom: 1rem!important;margin-top: -1rem!important;">
					  <button type="submit" class="btn btn-secondary width-md waves-effect waves-light form-control" name="search">Search</button>
					</div>
				</div>
			</form>
		</div>
		<?php if($survey_id!=''){?>
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
                  <div class="title"><span style="color:white;">Number of entries collected</span></div>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box twoColor">
               <i class="fa fa-check-circle" aria-hidden="true"></i>
               <div class="count">
                  <?php  
                     $surveyverify=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) as acc_total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_status='1' and survey_name_id='".$survey_id."'");
                     $survey_verify=mysqli_fetch_array($surveyverify);
                     echo $survey_verify['acc_total'];
                     
                     ?>
                  <div class="title"><span style="color:white;">Number of entries verified</span></div>
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
                  <div class="title"><span style="color:white;">Number of entries collected today</span></i></div>
               </div>
            </div>
         </div>
      </div>
		<div class="row">
         <div class="col-md-12">
            <section class="panel">
               <div class="card">
					<?php 
					
                   $surveyqry=mysqli_query($conn,"SELECT survey.survey_name,clients.name as client_name,questionnaire_pdf,questinnour_file FROM survey left join clients on clients.id=survey.client_id where survey.id='".$survey_id."' order by survey.id DESC");
             
					$surveydata=mysqli_fetch_array($surveyqry);
                     $survey_name=$surveydata['survey_name'];
                     $client_name=$surveydata['client_name'];
                     $questinnour_file=$surveydata['questinnour_file'];
                     $questionnaire_pdf=$surveydata['questionnaire_pdf'];
                     
                    ?>
			    
					<?php 
						$sidencode=base64_encode($survey_id);
						$actual_link=base_url()."dashboard_share.php?survey_id=".$sidencode;
						
					?>
                  <header class="panel-heading"> Form Name: <?php echo $survey_name;?> <?php if($_SESSION['role_id']!='3'){?>|| Client Name: <?php echo $client_name;?> <?php } ?>
					<a href="mailto:?Subject=Dashboard Share&amp;body=Kindly access dashboard by clicking on the URL <?php echo $actual_link; ?>" style="margin:1px;" class="share pull-right"><i class="fa fa-share-alt" aria-hidden="true" style="border:none;"></i> Share</a>
					<a href="javascript:void(0)" onclick="Convert_HTML_To_PDF()"; class="btn-secondary btn-sm pull-right" style="margin:4px;"><i class="fa fa-print"></i> Print</a>
				</header>
			   </div>
            </section>
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
		<?php } else{ ?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel">
					<div class="panel-body">
						<div class="alert alert-warning alert-dissmissable text-dark" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <strong class="text-dark">Select the Form to continue!</strong></div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
		</div>
	</section>
</section>
<!--main content end-->
<?php include_once('includes/footer.php'); ?>
<link href="<?=base_url();?>assets/sweetalerts/sweetalert2.min.css" rel="stylesheet">
<script src="<?=base_url();?>assets/sweetalerts/sweetalert2.all.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>-->
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

</script>

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