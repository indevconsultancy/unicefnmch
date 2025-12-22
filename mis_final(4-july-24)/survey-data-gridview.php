<?php include_once('includes/config.php'); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php $client_id=$_SESSION['client_id'];?>
<?php $surveyid=$_GET['survey_id'];?>
<?php
						
	$qry='';
		if(isset($_REQUEST['search'])) 
		{
			if(isset($_REQUEST['survey']) && $_REQUEST['survey']!='') {
			$qry.= " AND survey_id='".$_REQUEST['survey']."'";
			}
            if (isset($_REQUEST['fdate']) && isset($_REQUEST['tdate'])) {
					$d1 = date('Y-m-d h:i:s', strtotime($_REQUEST['fdate']));
					$d1 = $_REQUEST['fdate'] . ' 00:00:00';
					$d2 = date('Y-m-d h:i:s', strtotime($_REQUEST['tdate']));
					$d2 = $_REQUEST['tdate'] . ' 23:59:00';
				if (!empty($_REQUEST['fdate']) && !empty($_REQUEST['tdate'])) {
					if ($qry != ' ') {
					$qry .= 'AND ';
					}
					$qry .= "survey_data_monitoring.created_on BETWEEN '" . $d1 . "' AND '" . $d2 . "'";
				} else{
						if (!empty($_REQUEST['fdate'])) {
							if ($qry != ' ') {
								$qry .= 'AND ';
								}
								$qry .= "survey_data_monitoring.created_on >= '" . $d1 . "'";
							}
						if (!empty($_REQUEST['tdate'])) {
							if ($qry != ' ') {
							$qry .= 'AND ';
							}
							$qry .= "survey_data_monitoring.created_on <= '" . $d2 . "'";
						}
					}
            }
		}
?>

<?php
$client_qry="";
$clientqry="";
$client_qry1="";
	if($_SESSION['role_id']=="3" || $_SESSION['role_id']=="7"){
		
		$client_qry=" and survey_data_monitoring.client_id='".$_SESSION['client_id']."' ";
		$client_qry1=" and survey.client_id='".$_SESSION['client_id']."' ";
		$clientqry=" and client_id='".$_SESSION['client_id']."' ";
	}
?>

<?php

	/*
    //pagination
    $per_page=10;
                    
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page_url = "?";
    $page_url = isset($_GET['survey_id'])? $page_url."survey_id=".$_GET['survey_id']:$page_url;
    $page_url = isset($_GET['survey'])? $page_url."&survey=".$_GET['survey']:$page_url;
    $page_url = isset($_GET['fdate'])? $page_url."&fdate=".$_GET['fdate']:$page_url;
    $page_url = isset($_GET['tdate'])? $page_url."&tdate=".$_GET['tdate']:$page_url;
	$page_url = isset($_GET['search'])? $page_url."&search=".$_GET['search']:$page_url;
                
    $page=0;
    $current_page=1;
    if(isset($_GET['page'])){
        $current_page=intval($_GET['page']);
        $page=($current_page-1)*$per_page;
    }
	$query = "SELECT survey_name_id,survey_data_monitoring_id,survey_id,created_on,users.name,survey_status_master.name AS survey_status,survey_name,survey_data_monitoring.survey_status as survey_status_id,clients.name as client_name FROM survey_data_monitoring left JOIN users ON survey_data_monitoring.user_id=users.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id left join clients on survey_data_monitoring.client_id=clients.id where survey_name_id='".$surveyid."' $qry $client_qry order by survey_data_monitoring.survey_data_monitoring_id DESC";               
	$get_query=mysqli_query($conn,$query);
    $total_record=mysqli_num_rows($get_query);
    $total_pages=ceil($total_record/$per_page);
	
	*/
	
	
	
	function searchFromArray($array, $search){
		$result = array();
		foreach ($array as $key => $value){
		  foreach ($search as $k => $v){
			if (!isset($value[$k]) || $value[$k] != $v){
			  continue 2;
			}
		  }
		  //$result[] = $key;
		  $result[] = $array[$key];
		}
		return $result;
	}
?>

  <style>
  .info-box i {
    display: block;
    height: 20px;
    font-size: 30px;
    line-height: 20px;
    width: 70px;
    float: left;
    text-align: center;
    margin-right: 80px;
    padding-right: 20px;
    color: rgba(255, 255, 255, 0.75);
	}	
	.info-box {
    min-height:5px;
    margin-bottom: 20px;
	}
	.info-box .count {
    margin-top: 0px;
    font-size: 20px;
    font-weight: 1000;
	}
	.panel-heading {
    background: #394a59;
    color: white;
    font-weight: unset;
  </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
         <div class="row">
          <div class="col-lg-12">
            <ol class="breadcrumb">
            <!--  <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>  -->
              <li><i class="icon_documents_alt"></i>Form</a></li>  <!-- <a href="survey-list.php"> -->
              <li><i class="fa fa-list"></i>List Forms</li>
			    <li><i class='fa fa-eye'></i>Review Data</li>
            </ol>
          </div>
        </div>
		<!-- page start-->
        <div class="row">
          <div class="col-sm-12">
                <!--Start Filter-->
                <div class="container-fluid">
                    <form method="get">
					<input type="hidden" name="survey_id" value="<?=@$_REQUEST['survey_id']?>" />
                        <!--<div class="row filter_css clearfix" style="margin-top:10px; padding-bottom: 10px; padding-top: 10px;">-->
                        <div class="row filter_css clearfix">
							<div class="form-group col-md-3" style="margin-bottom: 1rem!important;margin-top:-1rem!important;">
                                     <input type="text" class="form-control" name="survey" value="" placeholder="Form ID">
                            </div>
								<div class="form-group col-md-3" style="margin-bottom: 1rem!important;margin-top:-1rem!important;">
                                     <input type="date" data-placement="top" data-toggle="tooltip" class="form-control btn btn tooltips" data-original-title="Form date" placeholder="From Date" name="fdate"value="<?php if ($_REQUEST['fdate']) {echo $_REQUEST['fdate'];} ?>">
                                </div>
                                <div class="form-group col-md-3" style="margin-bottom: 1rem!important;margin-top:-1rem!important;">
                                  <input type="date" data-placement="top" data-toggle="tooltip" class="form-control btn btn tooltips" data-original-title="To date" placeholder="To Date" name="tdate"value="<?php if ($_REQUEST['tdate']) {echo $_REQUEST['tdate'];} ?>">
                                </div>
                            <div class="form-group col-md-3"style="margin-bottom: 1rem!important;margin-top:-1rem!important;">
                                <button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>								
							</div>
                        </div>
                    </form>
                </div>
				
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="info-box brown-bg">
					  <i class="icon_documents_alt"></i>
						<div class="count">
							<?php
								$surveysql=mysqli_query($conn,"SELECT count(survey_data_monitoring_id) as total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_name_id='".$surveyid."' $qry $client_qry ");
								$data=mysqli_fetch_array($surveysql);
								 echo $data['total'];
							?>
							<div class="title"><span style="color:white;">Total No. of Form data</span></div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="info-box green-bg">
					  <i class="fa fa-check-circle" aria-hidden="true"></i>
						<div class="count">
							<?php
								$accsql=mysqli_query($conn,"SELECT COUNT(survey_data_monitoring_id) as acc_total,survey_name FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_status='5' and survey_name_id='".$surveyid."' $qry $client_qry");
								$data=mysqli_fetch_array($accsql);
								echo $data['acc_total'];
								 $survey_name=$data['survey_name'];
							?>
							<div class="title"><span style="color:white;">Verified Form</span></div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div class="info-box blue-bg">
					  <i class="fa fa-users" aria-hidden="true"></i>
						<div class="count">
							<?php
								$usersql=mysqli_query($conn,"SELECT count(DISTINCT(user_id)) as total FROM survey_data_monitoring LEFT JOIN clients on survey_data_monitoring.client_id=clients.id where survey_name_id='".$surveyid."' $qry $client_qry");
								$data=mysqli_fetch_array($usersql);
								echo $data['total'];
							?>
							<div class="title"><span style="color:white;">Total Users</span></div>
						</div>
					</div>
				</div>
				
			</div>
			<?php
				//$sql = "SELECT survey_name_id,survey_data_monitoring_id,survey_id,created_on,users.name,survey_status_master.name AS survey_status,survey_name,survey_data_monitoring.survey_status as survey_status_id,clients.name as client_name FROM survey_data_monitoring left JOIN users ON survey_data_monitoring.user_id=users.user_id left JOIN survey_status_master ON survey_data_monitoring.survey_status=survey_status_master.id left join clients on survey_data_monitoring.client_id=clients.id where survey_name_id='".$surveyid."' $qry $client_qry order by survey_data_monitoring.survey_data_monitoring_id DESC limit $page,$per_page";
				$sql = "SELECT survey.survey_name,clients.name as client_name FROM survey left join clients on clients.id=survey.client_id where survey.id='".$surveyid."' $qry $client_qry1 order by survey.id DESC limit $page,$per_page";
                $getSurvey = mysqli_query($conn,$sql );
				$row1=mysqli_fetch_array($getSurvey);
			?>
			<section class="panel">
				<header class="panel-heading">Form Name: <?php echo $row1['survey_name'];?> <?php if($_SESSION['role_id']!='3'){?>|| Client Name: <?php echo $row1['client_name'];?> <?php } ?> || Total Records: <?=$total_record?></header>
				
				<?php
					$survey_id_exported = $_REQUEST['survey_id'];
					$get_expt_fields = mysqli_query($conn,"SELECT id,title,field_lable FROM report_format WHERE survey_id='".$survey_id_exported."' AND group_id='0' AND title!='' $accessLabels ORDER BY seq_no ASC");
					while($expt_fields = mysqli_fetch_object($get_expt_fields)){
						$export_headers[]=$expt_fields->field_lable;//str_replace(",","~",$expt_fields->title);
						$exp_columns[] = $expt_fields->field_lable;
					}
				?>
				
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Survey ID</th>
								<?php foreach($export_headers as $exportheader){ ?>
									<th class=""><?=$exportheader;?></th>
								<?php
								}
								?>
								
							</tr>
						</thead>
						<tbody>
						<?php 
						
							$get_sdmon = mysqli_query($conn,"SELECT survey_data_monitoring_id,survey_id,user_id, survey_name, survey_data_json_coded, full_json  FROM survey_data_monitoring WHERE survey_name_id = '".$survey_id_exported."' order by survey_data_monitoring_id asc");
							while($sdmon = mysqli_fetch_object($get_sdmon)){
								$survey_data_json=$sdmon->survey_data_json_coded;
								$survey_id=$sdmon->survey_id;
								$user_id=$sdmon->user_id;
								$survey_name = $sdmon->survey_name;
								$jsondata = json_decode($survey_data_json, true);
							?>
								<tr>
									<td><?=$survey_id;?></td>
									<?php
									
										foreach($exp_columns as $exp_column){
											$export_main_value[]=$jsondata[$exp_column]?:"NA"; //str_replace(",","~",$jsondata[$exp_column])?:"NA";
											$mon_data_value = $jsondata[$exp_column]?:"NA";
											// echo "<pre>";
											// print_r($exp_column);
											
											$searchArr = array("field_name"=>$exp_column);
											$fArr = searchFromArray($allOption, $searchArr);
											
											if(count($fArr)>0){
												//print_r($fArr);
												$mon_data_valueArr = explode(",", $mon_data_value);
												foreach($mon_data_valueArr as $mon_data_valueVal){
													$fsearchArr = array("field_name"=>$exp_column,"option_sequence"=>$mon_data_valueVal);
													$foptArr = searchFromArray($fArr, $fsearchArr);
													//print_r($foptArr);
													$optOutput[] = $foptArr[0]["option_name"];
												}
												
												$mon_data_value = implode(",",$optOutput);
												$optOutput = array();
											}
											?>
											<td><?=$mon_data_value;?></td>
											<?php
										}
									
									?>
								</tr>
							<?php
							}
						?>
						</tbody>
					</table>
				</div>
				
            </section>
          </div>
        </div>
        <!-- page end-->
		<div class="text-left">
		  <div class="d-flex align-items-center justify-content-between" id="pagination">
				 <?php //paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>                               
			</div>
		</div>
		
	</div>
    </section>
</section>

</body>
</html>
<?php include_once('includes/footer.php'); ?>