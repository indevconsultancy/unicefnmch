<?php include_once('../includes/config.php');
	// if($conn){
		// echo "connect";
		
	// }else{
		// echo "not";
	// }
 ?>

<?php 
	if(isset($_POST['client_id'])){
	
		$sqlservey="SELECT id,survey_name,client_id FROM survey where client_id='".$_POST['client_id']."'";
		$selectservey=mysqli_query($conn,$sqlservey);
		?>
		<option value="">Select Survey</option>
		<?php
		while($surveydata=mysqli_fetch_array($selectservey))
		{ ?> 
			<option value="<?php echo $surveydata['id'];?>"<?php if($surveydata['id']==$_REQUEST['survey_name_id']){echo "selected";}?>><?php echo $surveydata['survey_name'];?></option>
		
		<?php
		}	
	}

?>