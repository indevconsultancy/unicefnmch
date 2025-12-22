<?php include_once('includes/config.php'); ?>
<?php include_once('includes/functions.php'); ?>
<?php 
	if(isset($_REQUEST['surveyid'])){
		 $survey_id=$_REQUEST['surveyid'];
		$completedmodule="UPDATE survey set status='2' where id='".$survey_id."' ";
		$updstsmodule=mysqli_query($conn,$completedmodule);
		if($updstsmodule){
			echo "completed";
		}else{
			echo "not completed";
		}
	}	
?>

<?php 
//Start Unpublish Form
	if(isset($_POST['suvId'])){
		
		$survey_id = mysqli_real_escape_string($conn,$_POST['suvId']);
		$sqlsurvey = mysqli_query($conn,"SELECT id,user_id,status FROM survey where id='".$survey_id."' ");
		$getSurvey=mysqli_fetch_array($sqlsurvey);
		if($getSurvey['status']==1){
			$update = mysqli_query($conn,"UPDATE survey SET status=0 WHERE id='".$survey_id."' ");
			if($update)
			{ 
				$result = array("status"=>1,"message"=>"Unpublish Successfully");
			}
		}else{
			$result = array("status"=>0,"message"=>"Already Unpublish");
		}
		echo json_encode($result);
	}
//End Unpublish Form	
?>
<?php
//WEB FORM Redirect  
if(isset($_POST['survey_id']) && $_POST['dataprocess']=='webform'){
    $survey_id = $_POST['survey_id'];
    $sqlQues = mysqli_query($conn, "SELECT id FROM `questionnaires` WHERE survey_id='".$survey_id."'");
    $dataQues = mysqli_num_rows($sqlQues);
    if($dataQues > 0){
        $result = array("status" => 1, "message" => "Form Already Created");
    }else{
        $result = array("status" => 0, "message" => "Form Not create");
    }
    header('Content-Type: application/json');
    echo json_encode($result);
    exit; 
}
?>

<?php 
	if(isset($_REQUEST['survey_ID'])){
		$survey_name_id=$_REQUEST['survey_ID'];
		$sqllang=mysqli_query($conn,"SELECT languages.language_id, languages.language_name FROM languages INNER JOIN questions_language ON languages.language_id=questions_language.language_id WHERE languages.status='0' AND questions_language.survey_id='".$survey_name_id."'  GROUP BY questions_language.language_id");
		echo '<option value="">Select Language</option>';
		while($langtype=mysqli_fetch_array($sqllang)){ ?>
		
		<option value="<?php echo $langtype['language_id']?>"<?php if($langtype['language_id']==$_REQUEST['language_id']) { echo "selected";}?>><?php echo $langtype['language_name']?></option>
<?php	
	} 
}
?>

<?php
//Assign User to survey List
if(isset($_POST['sur_id']) && $_POST['sur_id']!="" && $_POST['process']=='assign-user'){
	$survey_id = mysqli_real_escape_string($conn,$_POST['sur_id']);
	
	$clnt='';
	if($_SESSION['role_id']=='3'){
		$clnt = " and users.client_id='".$_SESSION['client_id']."' ";
	}
	?>
	<select name="user_id[]" class="form-control check_multiselect" multiple="multiple" data-placeholder="Assign User" required style="width: 500px;"  >
	<?php
	$form_sql="SELECT DISTINCT(users.user_id),users.name,users.username FROM `functional_role` inner join users on users.user_id=functional_role.user_id where users.status=0 $clnt AND users.user_id NOT IN(SELECT user_id FROM assign_survey WHERE survey_id='".$survey_id."' and assign_survey.status=0) order by users.name ASC";
	$form_query=mysqli_query($conn,$form_sql);
	if(mysqli_num_rows($form_query)>0)
	{
		foreach($form_query as $rowform)
		{ ?>
			<option value="<?php echo $rowform['user_id'];?>"><?php echo $rowform['name']; ?> (<b><?php echo $rowform['username']; ?></b>)</option>
		<?php
		}
	}
	else
	{
		echo "No Record Found";
	}
	
	echo '</select>';
}
//End Assign User to survey List
?>

<?php 
//Assign User List to survey List
if(isset($_POST['survey_id'])){
	$survey_id=mysqli_real_escape_string($conn,$_POST['survey_id']);
	$sqlassignUser=mysqli_query($conn,"SELECT DISTINCT(users.name) as name,users.user_id,users.username,users.orignal_password FROM assign_survey left join users on users.user_id=assign_survey.user_id where survey_id='".$survey_id."' and assign_survey.status=0 order by name ASC"); 
	$rowCount=mysqli_num_rows($sqlassignUser);
			
?>

<div class="row">
	<div class="col-md-10"><h4 style="margin: 10px;">Total User(s): <?=$rowCount;?></h4></div>
	
	<div class="col-md-2">
		<a class="btn btn-success btn-sm waves-effect width-md waves-light" href="export/export-assign-user.php?surid=<?=$survey_id;?>" style="float:right;margin: 10px;">
		<i class="fa fa-file-excel-o" aria-hidden="true"></i> Export User Details</a>
	</div>
</div>
	<input type="hidden" name="survey_ids" value="<?=$survey_id;?>">
	<table id="employee_data" style="width:100%;padding: 10px;margin-top: -13px;" class="table table-striped table-bordered display nowrap">
		<thead style="background: #61b7b8;border: 2px solid #54a3a4;vertical-align: middle;">
			<tr>
				<th class="text-center" style="color: #FFFFFF;width:50px;padding:0px;"><input type="checkbox" name="check[]" id="checkAll"> </th>
				<th class="text-center" style="color: #FFFFFF;width:80px;">S.No</th>
				<th style="color: #FFFFFF;">User Name</th>
				
			</tr>
		</thead>
		<tbody>
		<?php
			$i=1;
			while($dataassignUser=mysqli_fetch_object($sqlassignUser))
			{ ?>
			<tr>
				<td class="text-center p-0"><input type="checkbox" name="checked_id[]" value="<?=$dataassignUser->user_id;?>"></td>
				<td class="text-center" ><?=$i++?></td>
				<td><?=$dataassignUser->name;?></td>
			</tr>
		<?php	}
		?>
		</tbody>
	</table>
	<script>
   $("#checkAll").click(function() {
      $('input:checkbox').not(this).prop('checked', this.checked);
   });
</script>
<?php	
}
//End Assign User List to survey List
?>
<?php
//update status Question bank 
if(isset($_POST['questionid']) && $_POST['questionid']!=""){
	$question_bank_id=mysqli_real_escape_string($conn,$_POST['questionid']);
	$updateQuestion="UPDATE question_bank SET status='1' where question_bank_id='".$question_bank_id."' ";
	$delQuestion=mysqli_query($conn,$updateQuestion);
	if($delQuestion){
		$UpdateOption="UPDATE question_bank_option SET status='1' where question_bank_id='".$question_bank_id."'";
		$delOption=mysqli_query($conn,$UpdateOption);
		if($delOption){
			$result = array("status"=>1,"message"=>"Question deleted successfully.");
		}else{
			$result = array("status"=>0,"message"=>"Something weng wrong.");
		}
		
	}else{
			$result = array("status"=>0,"message"=>"Something weng wrong.");
	}
	echo json_encode($result);
}

?>	

		
