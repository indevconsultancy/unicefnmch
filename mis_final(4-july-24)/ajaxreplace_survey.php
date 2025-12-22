<?php include_once('includes/config.php'); ?>
<?php
if (isset($_POST['client_id'])) { 
    $client_id = $_POST['client_id'];
	
    $getSurvey ="SELECT id,survey_name FROM survey WHERE del_action='N' and client_id='$client_id' order by id DESC";
    $getSurveyclient=mysqli_query($conn,$getSurvey);
   if(mysqli_num_rows($getSurveyclient)>0){
	   while($surveycdata = mysqli_fetch_object($getSurveyclient)){ ?>
		
		<option value="<?=$surveycdata->id;?>"><?=$surveycdata->survey_name;?>
	   <?php
	   }
   }else{ ?>
	   <option value="">No Record Found</option>
 <?php
 }
}
?>