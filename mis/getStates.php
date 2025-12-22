<?php include('includes/config.php');?>
<?php
$countryID = $_POST['country_id'];
///echo "select state_id,state_name from states where country_id='".$countryID."' order by state_name asc";
$sqlstate=mysqli_query($conn,"select state_id,state_name from states where country_id='".$countryID."' order by state_name asc");
if (mysqli_num_rows($sqlstate) > 0) {
	 echo '<option value="">Select State</option>';
    while($datastate=mysqli_fetch_object($sqlstate))
	{?>
	<option value="<?=$datastate->state_id?>"><?=$datastate->state_name?></option>
<?php } } else {
    echo '<option value="">No districts available</option>';
} ?>
