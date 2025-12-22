<?php include_once('includes/config.php'); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

  <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <!-- <h3 class="page-header"><i class="fa fa fa-bars"></i> Pages</h3> -->
                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="dashboard.php">Home</a></li>
                        <li><i class="icon_documents_alt"></i>Survey</li>
                        <li><i class="fa fa-bar-chart"></i></i>Survey list</li>
                    </ol>
                    <!--Start filter-->
                    <div class="container-fluid">
					<?php
						$qry="";
						if(isset($_REQUEST['search'])){
							if(isset($_REQUEST['survey_id']) && $_REQUEST['survey_id']!="" && isset($_REQUEST['client_id']) && $_REQUEST['client_id']!=""){
								$qry=" and survey_id='".$_REQUEST['survey_id']."' and client_id='".$_REQUEST['client_id']."' ";
							}
							
							// if(isset($_REQUEST['client_id']) && $_REQUEST['client_id']!=""){
								// $qry.=" and client_id='".$_REQUEST['client_id']."' ";
							// }
							
						}
					?>
                        <form method="get">
                            <div class="row filter_css clearfix" style="margin-top:10px; padding-bottom: 20px; padding-top: 10px;">
                                <div class="col-lg-5">
									<select class="form-control" name="survey_id" id="survey_id" required >
									   <option value="">Select Survey</option>
										<?php
											$getSurvey = mysqli_query($conn,"SELECT id,survey_name FROM survey WHERE del_action='N'");
											while($survey = mysqli_fetch_object($getSurvey)){ ?>
												<option value="<?=$survey->id;?>" <?php if($survey->id==$_REQUEST['survey_id']) { echo "selected";}?> ><?=$survey->survey_name;?></option>
											<?php	
											}
										?>
									</select>
                                </div>
								<div class="col-lg-5">
									<select class="form-control" name="client_id" id="client_id" required>
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
								
                                <div class="form-group col-lg-2" style="margin-bottom: 0rem!important;">
                                  <button type="submit" class="btn btn-primary width-md waves-effect waves-light form-control" name="search">Search</button>
								</div>
                            </div>
                        </form>
                    </div>
                    <!-- Filter End-->
                </div>
            </div>
            <!-- page start-->
            <div class="row">
				<div class="col-sm-12">
                    <section class="panel">	
						<?php
						
							if(isset($_POST['export'])){
								$selected_fields = implode(",",$_POST['selected_fields']);
								$_SESSION['selectedfields'] = " and survey_id='".$_REQUEST['survey_id']."' AND client_id='".$_REQUEST['client_id']."' and id IN($selected_fields)";
								$_SESSION['survey_qry'] = " and survey_name_id='".$_REQUEST['survey_id']."' AND client_id='".$_REQUEST['client_id']."'  ";
								echo "<script>window.location.href='export/ss_export.php';</script>";
							}
						
						?>
                        <header class="panel-heading">
                       Export Survey Data 
                        </header>
						<form method="post">
						
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th><input type="checkbox" id="checkAll" /></th>
                                <th>Field Name</th>
                            </tr>
                            </thead>
                            <tbody>
								<?php
									if($qry!=""){
									$getReportData = mysqli_query($conn,"SELECT id,title,field_lable,seq_no FROM report_format WHERE status='0' and title!='' and group_id='0' $qry ORDER BY seq_no ASC");
									if(mysqli_num_rows($getReportData)>0){
									while($report_data = mysqli_fetch_object($getReportData)){ ?>
										<tr>
											<td><?=++$i;?></td>
											<td><input type="checkbox" value="<?=$report_data->id;?>" name="selected_fields[]" /></td>
											<td><?=$report_data->title;?></td>
										</tr>
									<?php	
									} }else{ echo '<tr><td colspan="3" style="text-align:center;">Survey Data Not Available..!</td></tr>'; }
									}else{
										echo '<tr><td colspan="3" style="text-align:center;">Search Data For Export..!</td></tr>';
									}
								?>
								
                            </tbody>
                        </table>
						<input type="submit" name="export" value="Export data" onclick="exportData()" class="btn btn-primary"/>
						</form>
                    </section>
                </div>				
            </div>

            <!-- page end-->
			<div class="text-left">
			<div class="d-flex align-items-center justify-content-between" id="pagination">
				 <?=paginate($per_page, $current_page, $total_record, $total_pages, $page_url); ?>                               
			 </div>
			</div>
			
	    </section>
    </section>
    <!--main content end-->
  <?php include_once('includes/footer.php'); ?> 
  
<script>
$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
</script>
  