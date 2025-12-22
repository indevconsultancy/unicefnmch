<?php include('includes/config.php'); ?>
 <?php define("title","Dashboard | MQUAD");?>
<?php include('includes/header_dashboard.php'); ?>
<?php include_once('sscharts.php'); ?>

<?php
    $surveyid=mysqli_real_escape_string($conn,$_GET['survey_id']);
    $survey_id=base64_decode($surveyid);
	//echo "select id,user_id from survey where id='".$survey_id."'";
	$getUser=mysqli_query($conn,"select id,user_id from survey where id='".$survey_id."'");
	$dataUser=mysqli_fetch_array($getUser);
	 $user_id=$dataUser['user_id'];
   
 ?>
 
<style>
   .c-center-align {
   height: 200px;
   display: flex;
   align-items: center;
   justify-content: center;
   }
	#main-content {
    margin-left: 0px;
}
.toggle-nav {
	display: none;
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
</style>
<script src="<?=base_url();?>assets/highcharts/highcharts.js"></script>
<script src="<?=base_url();?>assets/highcharts/highcharts-more.js"></script>
<script src="<?=base_url();?>assets/highcharts/exporting.js"></script>
<script src="<?=base_url();?>assets/highcharts/export-data.js"></script>
<script src="<?=base_url();?>assets/highcharts/accessibility.js"></script>

<section id="main-content">
   <section class="wrapper">
      <div class="row">
         <div class="col-lg-12">
            <ol class="breadcrumb">
               <li><i class="fa fa-laptop"></i>View Dashboard</li>
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
                  <div class="title"><span style="color:white;">Form Collected</div>
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
                  <div class="title"><span style="color:white;">Form Verified</div>
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
                  <div class="title"><span style="color:white;">Total Users</div>
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
                  <div class="title"><span style="color:white;">Number of form collected today</div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <section class="panel">
               <div class="card">
                  <?php 
                     $surveyqry=mysqli_query($conn,"SELECT survey_name,clients.name as client_name FROM `survey` left join clients on survey.client_id=clients.id where survey.id='".$survey_id."'");
                     $surveydata=mysqli_fetch_array($surveyqry);
                     $survey_name=$surveydata['survey_name'];
                     $client_name=$surveydata['client_name'];
                     ?>
                  <header class="panel-heading"> Form Name: <?php echo $survey_name;?> || Client Name: <?=$client_name?>
				 </header>
			   </div>
            </section>
         </div>
		 
      </div>
			<div class="row">
	  	<?php
            $sid = base64_decode($_REQUEST['survey_id']);
            $uid = $_SESSION['user_id'];
			//echo "SELECT `id`, `title`, `description`, `fieldname`, `groupby`, `colors`, `user_id`, `survey_id`, `chart_name`, `chart_type`, `created_on`, `status` FROM `surveydashboard` WHERE  status='0' AND survey_id='".$sid."'  ORDER BY id DESC ";
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




