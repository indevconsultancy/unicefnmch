<?php include_once('includes/config.php'); ?>
<?php define("title","View Dataset | MQUAD");?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>
<?php  
	$data_repositroy_id=mysqli_real_escape_string($conn,$_REQUEST['vid']);
	$client_id=mysqli_real_escape_string($conn, $_SESSION['client_id']);
    $cid="C".$client_id;	
 ?>   
<style>
.panel-heading {
    background: #394a59;
    color: white;
    font-weight: unset;
}

</style>
    <section id="main-content">
        <div class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><i class="icon_documents_alt"></i>Data Repository</li>
                        <li><i class="fa fa-eye" aria-hidden="true"></i>View Dataset</li>
                    </ol>
                    <div class="container-fluid">
                    </div>
                </div>
            </div>
            <!-- page start-->
            <div class="row ">
            	<div class="col-sm-12">
                	<div class="panel panel-default mt-5">
                    	 <div class="row">
                            <div class="col-md-8"> 
								
                                <div class="row pl-0 pr-0">
                            		<div class="col-md-12"> 
										<?php 
											 $sqlcontribute="SELECT users.client_id,data_repositroy.data_repository_id,data_repositroy.study_name,data_repositroy.description,data_repositroy.institution_name, data_repositroy.published_date,data_repositroy.category_id,data_repositroy.data_study_year FROM `data_repositroy` left join users on users.user_id=data_repositroy.user_id where data_repositroy.status='1' and data_repositroy.data_repository_id='".$data_repositroy_id."' ";
											$contribute_survey=mysqli_query($conn,$sqlcontribute);
											
											while($contribute_row=mysqli_fetch_array($contribute_survey)){
												$published_date=$contribute_row['published_date'];
												$published_year= date("d-m-Y", strtotime($published_date));
												$category_id=$contribute_row['category_id'];		
											
												$clientid=$contribute_row['client_id'];
												$cid="C".$clientid;
											
											?>		
											<div class="survey-box" style="margin-top: 20px;">
												<div class="survey-container">
													<div class="survey-heading titled">
														<h4>Study Name: <?php echo $contribute_row['study_name'];?></h4>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-clock-o"></i> Publication date:</p>
													</div>
													<div class="survey-data">
														<p><?php echo $published_year;?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-clock-o"></i> Study Year:</p>
													</div>
													<div class="survey-data">
														<p><?php echo $contribute_row['data_study_year'];?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-file"></i> Data Name:</p>
													</div>
												
													<div class="survey-data">
														<?php 
														 $sqldata="SELECT data_name,data_access FROM data_repositroy_otherdata where data_repository_id='".$data_repositroy_id."' ";	
														$qrydata=mysqli_query($conn,$sqldata);
														while($dataname=mysqli_fetch_array($qrydata)){ 
															$data_access[]= $dataname['data_access'];
														 //echo $datass=implode(',',$datasname);
														
														?>
														<p><?php echo $dataname['data_name'];?></p>
														<?php } ?>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-clock-o"></i> Data Access:</p>
													</div>
													<div class="survey-data">
													<?php
													 $sqldataaccess="SELECT data_name,data_access,upload_codebook FROM data_repositroy_otherdata where data_repository_id='".$data_repositroy_id."' ";	
														$qrydataacc=mysqli_query($conn,$sqldataaccess);
														$dataaccess=mysqli_fetch_array($qrydataacc);
														
													?>	
														<p><?php echo $dataaccess['data_access'];?></p>
													</div>
												</div>
													<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-list-alt"></i> Thematic Area:</p>
													</div>
													<div class="survey-data">
													<?php
														$sqlcdata="SELECT * FROM `categories` WHERE `category_id` IN (".$category_id.") $qrycat";
														$sqlcates=mysqli_query($conn,$sqlcdata);
														while($rows=mysqli_fetch_array($sqlcates)){
															$categories[]=$rows['category_name'];
														}
														?>
														
														<p><?php echo implode(',',array_unique($categories));?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-file"></i> Description:</p>
													</div>
													<div class="survey-data">
														<p><?php echo $contribute_row['description'];?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-user"></i> Institution :</p>
													</div>
													<div class="survey-data">
														<p><?php echo $contribute_row['institution_name'];?></p>
													</div>
												</div>
												<div class="survey-container">
													<div class="survey-heading">
														<p><i class="fa fa-download"></i> Download Code Book: </p>
													</div>
													<div class="survey-data">
														<p><a href="upload_data_file/upload_codebook/<?=$cid?>/<?php echo $dataaccess['upload_codebook'];?>" target="_blank"><?php echo $dataaccess['upload_codebook'];?></a></p>
													</div>
												</div>
												
											</div>
											
										<?php } ?>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-4">
                            	<div class="sticky-top" style="margin-top: 20px;">
                                	<div class="sidebar">
                                        <div class="side-heading">
                                            <h4>Data Format</h4>
                                        </div>
										<form action="GET">
                                        <div class="side-data">
                                            <ul>
												<?php 
												 $dataformatesql="SELECT data_format_name,data_formate_file,data_repository_id FROM repository_dataformat where data_repository_id='".$data_repositroy_id."'";
												$dataformateqry=mysqli_query($conn,$dataformatesql);
												while($dataformate=mysqli_fetch_array($dataformateqry)){
												$dataformat[]=$dataformate['data_format_name'];
											   
												?>
												<div class="row"> 
													<div class="col-sm-6"><?php echo $dataformate['data_format_name'];?>: </div>
													<div class="col-sm-6"><a href="upload_data_file/upload_dataformate_file/<?=$cid?>/<?=$dataformate['data_formate_file']?>"><i class="fa fa-download"></i>Download</a></div>
												</div>
												
												<?php } ?>
                                            </ul>
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>              
                </div>
            </div>
            <!-- page end-->
	    </div>
    </section>
  <!--main content end-->
 <?php include_once('includes/footer.php'); ?> 
 <!-- Optional JavaScript -->
 
