<?php include_once('includes/config.php'); ?>
<?php define("title","List Form | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php 
	$client_qry = "";
	//if($_SESSION['role_id']=="3"  || $_SESSION['role_id']=="7"){
	if($_SESSION['role_id']=="3"){
		$client_id = $_SESSION['client_id'];
		$client_qry=" and survey.client_id='".$client_id."' ";	
	}
?>
<?php	
	//$client_qry = "";
	if($_SESSION['role_id']=="7"){
		$client_id = $_SESSION['client_id'];
		$client_qry=" and survey.client_id='".$client_id."' ";
	}	
?>
<?php
						$qry='';
						$qryServy='';
                         $count = 1;
						if(isset($_REQUEST['search'])) 
						{
                            
                            if(isset($_REQUEST['survey_name_id']) && $_REQUEST['survey_name_id']!='') {
							$qry.= " AND survey_name_id='".$_REQUEST['survey_name_id']."'";
							$qryServy.= " AND id='".$_REQUEST['survey_name_id']."'";
							}
							
							if(isset($_REQUEST['client_id']) && $_REQUEST['client_id']!='') {
								$qry.= " AND clients.id='".$_REQUEST['client_id']."'";
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
                                } else {
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

    //pagination
    $per_page=10;
                    
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page_url = "?";
    $page_url = isset($_GET['client_id'])? $page_url."client_id=".$_GET['client_id']:$page_url;
	$page_url = isset($_GET['survey_name_id'])? $page_url."&survey_name_id=".$_GET['survey_name_id']:$page_url;
    $page_url = isset($_GET['fdate'])? $page_url."&fdate=".$_GET['fdate']:$page_url;
    $page_url = isset($_GET['tdate'])? $page_url."&tdate=".$_GET['tdate']:$page_url;
    $page_url = isset($_GET['search'])? $page_url."&search=".$_GET['search']:$page_url;
 
                
    $page=0;
    $current_page=1;
    if(isset($_GET['page'])){
        $current_page=intval($_GET['page']);
        $page=($current_page-1)*$per_page;
    }
	//$query="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data,survey.id, survey.survey_name, survey.created_at,survey.client_id, survey.status, survey.user_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry GROUP BY survey.survey_name order by survey.created_at DESC";
	if($_SESSION['role_id']!=7){
	$query="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data,survey.id, survey.survey_name, survey.created_at,survey.client_id, survey.status, survey.user_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry GROUP BY survey.survey_name order by survey.created_at DESC ";
	}elseif($_SESSION['role_id']==7) {
	$query="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data,survey.id, survey.survey_name, survey.created_at,survey.client_id, survey.status, survey.user_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry GROUP BY survey.survey_name order by survey.created_at DESC ";
	}
	$get_query=mysqli_query($conn,$query);
    $total_record=mysqli_num_rows($get_query);
    $total_pages=ceil($total_record/$per_page);
?>

    <style>
        .cls {
            color: white;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>
                        <li><i class="fa fa-bar-chart"></i>Form</li>
                       <li><i class="icon_documents_alt"></i>List Form</li>
                    </ol>
                </div>
            </div>
            <!-- page start-->
            <div class="row">
                <div class="col-sm-12">                  
						<div class="container-fluid">
                    <form method="get">
                        <div class="row filter_css clearfix" style="margin-top:10px; padding-bottom: 10px; padding-top: 10px;">
                            <?php if($_SESSION['role_id']!='3'){?>
								<div class="col-lg-3">
									<select class="form-control" name="client_id" id="client_id" onchange="getsurvey(this.value);">
									   <option value="">Select Client</option>
									   <?php
										$sql=mysqli_query($conn,"SELECT id,name FROM `clients` where del_action='N'");
										while($type=mysqli_fetch_array($sql)){?>
										<option value="<?php echo $type['id']?>" <?php if($type['id']==$_REQUEST['client_id']) { echo "selected";}?>><?php echo $type['name']?></option>
										<?php	
										}
										?>
									</select>
                                </div>
							
							<div class="col-lg-3">
                                <div class="form-group select-custom-margin">
                                    <select class="form-control" name="survey_name_id" id="survey_name_id">
                                        <option value="">Survey Name</option>
										<?php if($_GET['client_id']!=''){
											$sqlservey="SELECT id,survey_name,client_id FROM survey where client_id='".$_GET['client_id']."'";
											$selectservey=mysqli_query($conn,$sqlservey);
											?>
	
											<?php
											while($surveydata=mysqli_fetch_array($selectservey))
											{ ?> 
												<option value="<?php echo $surveydata['id'];?>"<?php if($surveydata['id']==$_REQUEST['survey_name_id']){echo "selected";}?>><?php echo $surveydata['survey_name'];?></option>
											
											<?php
											}	
											?>
										
										<?php } ?>
                                    </select>
								</div>
                            </div>
							<?php }?>
							<?php if($_SESSION['role_id']!='1' && $_SESSION['role_id']!='7'){?>
							<div class="col-lg-3">
                                <div class="form-group select-custom-margin">
                                    <select class="form-control" name="survey_name_id" id="survey_name_id">
                                        <option value="">Survey Name</option>
										<?php
									$surveytype=mysqli_query($conn,"SELECT id,survey_name from survey where del_action='N' $client_qry");
									while($type=mysqli_fetch_array($surveytype)){?>
									 <option value="<?php echo $type['id']?>" <?php if($type['id']==$_REQUEST['survey_name_id']) { echo "selected";}?>><?php echo $type['survey_name']?></option>
									 <?php	
									}
								?>
                                    </select>
								</div>
                            </div>
							<?php } ?>
							
								<div class="form-group col-md-2" style="margin-bottom: 0rem!important;">
                                     <input type="date" data-placement="top" data-toggle="tooltip" class="form-control btn btn tooltips" data-original-title="Form date" placeholder="From Date" name="fdate"value="<?php if ($_REQUEST['fdate']) {echo $_REQUEST['fdate'];} ?>">
                                </div>
                                <div class="form-group col-md-2" style="margin-bottom: 0rem!important;">
                                  <input type="date" data-placement="top" data-toggle="tooltip" class="form-control btn btn tooltips" data-original-title="To date" placeholder="To Date" name="tdate"value="<?php if ($_REQUEST['tdate']) {echo $_REQUEST['tdate'];} ?>">
                                </div>
                            <div class="form-group col-md-2" style="margin-bottom: 0rem!important;">
                                <button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>								
							</div>
                        </div>
                    </form>
                </div>
						<section class="panel">
						<header class="panel-heading">Total survey list : <?=$total_record?>
						</header>
                        <table class="table table-striped">
                            <thead>
							<tr>
                               <th>S.No</th>
                                <th>Survey Name</th>
                                <th>Client Name</th>
                                <th>Total survey data</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
							if($_SESSION['role_id']!=7){
							$_SESSION['query']="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data,survey.id, survey.survey_name, survey.created_at,survey.client_id, survey.status, survey.user_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry GROUP BY survey.survey_name order by survey.created_at DESC";
							$sql="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data,survey.id, survey.survey_name, survey.created_at,survey.client_id, survey.status, survey.user_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry GROUP BY survey.survey_name order by survey.created_at DESC limit $page,$per_page";
							}elseif($_SESSION['role_id']==7) {
							$_SESSION['query']="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data,survey.id, survey.survey_name, survey.created_at,survey.client_id, survey.status, survey.user_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry GROUP BY survey.survey_name order by survey.created_at DESC";
							 $sql="SELECT COUNT(survey_data_monitoring.survey_name_id) as total_survey_data,survey.id, survey.survey_name, survey.created_at,survey.client_id, survey.status, survey.user_id,clients.name as client_name FROM survey left join clients on survey.client_id=clients.id left join survey_data_monitoring on survey.id=survey_data_monitoring.survey_name_id WHERE survey.del_action='N' $qry $client_qry GROUP BY survey.survey_name order by survey.created_at DESC limit $page,$per_page ";
							}
							 $getSurvey = mysqli_query($conn, $sql);
							 $sn=1+$page;
                             while ($survey = mysqli_fetch_array($getSurvey)) { ?>

                                <tr>
                                    <td><?=$sn++;?></td>
                                    <td><?= $survey['survey_name'] ?></td>
                                    <td><?= $survey['client_name'] ?></td>
                                    <td><?= $survey['total_survey_data'] ?></td>
                                    <td><?= date('d-M-Y', strtotime($survey['created_at'])); ?></td>
									 <td>
										 <a href="survey-data-list.php?survey_id=<?= $survey['id'] ?>" data-placement="top" data-toggle="tooltip" class="btn-sm btn-primary tooltips" data-original-title="View survey""><i class="fa fa-eye" aria-hidden="true"></i></a>
									 
									</td>
                                </tr>
                            <?php } ?>
							
                            </tbody>
                        </table>
					</section>
                </div>
            </div>
			<div class="d-flex d-title-box-block align-items-center justify-content-between footer-paging">
				
				<div class="col-md-10">
				  <div class="d-flex align-items-center justify-content-between" id="pagination">
					 <?=paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>                               
				 </div>
				 </div>
				 <div class="">
					<?php
					$_SESSION['header_column']="Survey Name,Client Name,Total Survey data,Created On";
					$_SESSION['db_column']="survey_name,client_name,total_survey_data,created_at";
					?>
				 <div class=" col-md-2 " style="margin-bottom: 0rem!important; padding-top: 40px">
				  <a class="btn btn-success btn-sm waves-effect width-md waves-light " href="export/export.php">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export To CSV</a>
				 </div>
				 </div>
			</div>
			
			
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->
<!---dependancy---->
<script>
	function getsurvey(val){
	
		 var surveyIdc=$('#client_id').val();
        // alert (surveyIdc);
        $.ajax({
			type:'post',
            url:'ajax/getsurvey.php',
            data:'client_id='+surveyIdc,
            success:function(responsedata){
                $('#survey_name_id').html(responsedata);
				$('#survey_name_id').selectpicker('refresh');
            }
        });
		
	}
</script>
<!---dependancy end---->
<?php include_once('includes/footer.php'); ?>