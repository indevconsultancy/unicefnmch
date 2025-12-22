<?php include('includes/config.php'); ?>
<?php define("title","Assign Question | MQUAD");?>
<?php include('includes/header.php'); ?>
<?php include('includes/left-sidebar.php'); ?>
<?php include('includes/charts_functions.php');?>
<!--main content start-->

<link rel="stylesheet" type="text/css" href="js/transfer/css/jquery.transfer.css">
<style type="text/css">
	.panel-heading {
		font-weight: bold !important;
		background: #033d66 !important;
		color: white !important;
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
					<li><i class="fa fa-question"></i>Assign Question </li>
				</ol>
			</div>
		</div>
		<!--------filter start------->
		<div class="container-fluid">
			<div class="container-fluid">
				<div class="filter_css clearfix">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<section class="panel">
								<div class="panel panel-default">
									<div class="panel-body homemain-icotabs" style="padding-bottom: 22px;">
										<div class="row">
											<div class="col-md-6">
												<div class="form">
													<form  method="POST" name="theForm" id="theForm">
														<div class="form-group form-inline">
														</div>
												</div>
											</div>
										</div>
										<hr>
										<div class="row">

											<div class="col-md-12">
												<div id="transfer" class="transfer-demo">

												</div>
												<input type="hidden" name="assignedQuestion" id="sf">
												<div class="col-lg-offset-2 col-lg-10  text-right">
												<input type="button" name="sub" class="btn btn-success pull-right" id="select_all" onClick="selectall()" value="Submit" style="margin-right:13px;margin-top:15px">
													<!-- <button class="btn btn-primary" type="submit" id="selectall" onClick="selectall()" value="submit" name="submit">Assign</button> -->
												</div>
												</form>
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
		</div>
		</div>
	</section>
</section>
<?php include_once('includes/footer.php'); ?>
<script type="text/javascript" src="js/transfer/js/jquery.transfer.js"></script>
<?php
      $survey_id = $_REQUEST['survey_id'];
      function CatID($conn,$cid){
		//$sqlcat="SELECT category_id,survey_name,client_id from survey where id='$cid'";
		
		$sqlcat=mysqli_query($conn,"SELECT category_id,survey_name,client_id from survey where id='$cid'");
		$datamm=mysqli_fetch_array($sqlcat);
		$category_id=$datamm['category_id'];
		$survey_name=$datamm['survey_name'];
		$client_id=$datamm['client_id'];
		$data=['id'=>$category_id,'survey_name'=>$survey_name,'client_id'=>$client_id];
		return $data;
	  }
      if(isset($_POST['assignedQuestion']))
      {
            $assignedQuestion = json_decode($_POST['assignedQuestion']);
			$query_values= implode(',', $assignedQuestion);
			
			 $sqlquestion=mysqli_query($conn,"SELECT question_name,survey_id,question_id,questions_type_id,input_field_type,field_name,category_name  FROM questions_language  where question_name!=''and language_id='1' and question_id in ($query_values)");
			 
		    foreach($sqlquestion as $quest ){
				$question_name= $quest['question_name'];
				//$unique_id=rand(100000,10000000000);
				$unique_id = rand(10000000000,10);
				$field_name=$quest['field_name'];
				$questions_type_id=$quest['input_field_type'];
				$user_id=$_SESSION['user_id'];
				
				$surveyqry=mysqli_query($conn,"SELECT clients.name as client_name FROM  clients  where id='".CatID($conn,$quest['survey_id'])['client_id']."'");
				$surveydata=mysqli_fetch_array($surveyqry);
				 $category_id=CatID($conn,$quest['survey_id'])['id'];
				 $survey_name=CatID($conn,$quest['survey_id'])['survey_name'];
				 $client_name=$surveydata['client_name'];
				
				$sqlinsert=mysqli_query($conn,"INSERT INTO question_bank SET question_bank_name='".$question_name."',unique_id='".$unique_id."', field_name='".$field_name."', question_type='".$questions_type_id."', category_id='".$category_id."', data_source='".$client_name."',target_group='".$survey_name."',status_type='0',user_id='".$user_id."' ");
				
			}
             
			if($sqlinsert){
				if($questions_type_id ='select_multiple' || $questions_type_id ='select_one' ){ 
					$qid = mysqli_insert_id($conn);
					$sqloption=mysqli_query($conn,"SELECT option_name,survey_id,question_id,option_id,option_sequence FROM options_language where language_id='1' and question_id in ($query_values)");
					
					foreach($sqloption as $option){
						$option_name=$option['option_name'];
						$option_value=$option['option_sequence'];
						$question_id=$option['question_id'];
						$sqlinsert=mysqli_query($conn,"INSERT INTO question_bank_option SET question_bank_id='".$qid."', question_option_name='".$option_name."', option_value='".$option_value."'");
					}
				
				}
				
			}else{
				 $_SESSION['status_error'] = "Something went wrong!!";
				$_SESSION['status_error_code'] = "error";
			}
			 
			 if($sqlinsert){
				 $_SESSION['status'] = "Question Assigned Successfully";
				$_SESSION['status_code'] = "success";	
				echo "<script>window.location.href='my_question.php'</script>";
			 }else{
				$_SESSION['status_error'] = "Something went wrong!!";
				$_SESSION['status_error_code'] = "error";
			 }
		  
	   }
      
    ?>

<?php if(isset($_SESSION['status_error']) && $_SESSION['status_error']!=''){ ?>
<script>
	swal.fire({
		title: "<?php echo $_SESSION['status_error'];?>",
		icon:"<?php echo $_SESSION['status_error_code']; ?>",
		confirmButtonColor: '#449A97',
		confirmButtonText: 'Ok'
	});
</script>
<?php unset($_SESSION['status_error']);}  ?>
<script>
var dataArray = [
        
		<?php
		 
		   $selfarmer=mysqli_query($conn,"SELECT question_name,survey_id,question_id  FROM questions_language  where question_name!=''and language_id='1' and survey_id='" . $survey_id . "'");
		   while($faemerdata=mysqli_fetch_array($selfarmer))
		   {
			 
			 ?>
			 
			 {
				"farmer-name": "<?=$faemerdata['question_name']?>",
				"value": <?=$faemerdata['question_id']?>,
				"selected": false,
			},
			 
		   <?php     
		   }
		?>
	
		
	];

	var settings = {
		"dataArray": dataArray,
		"itemName": "farmer-name",
		"valueName": "value",
		"callable": function (items) {
			//console.dir(items)
		}
	};

	var transfer = $("#transfer").transfer(settings);
  
  
	//console.log("Manually get selected items: %o", items);
	
	function selectall() {
		var result = [];
		var items = transfer.getSelectedItems()
	   // console.log(this.items);
		objArray = items;
		result = objArray.map(({ value }) => value)
		var jsonString = JSON.stringify(result);
		//console.log(jsonString);
		document.getElementById('sf').value=jsonString;
		document.theForm.submit();
	}
	
</script>