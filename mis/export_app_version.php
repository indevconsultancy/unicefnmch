<?php include_once('includes/config.php'); ?>
<?php include_once('mycrypt.php'); ?>
<?php 
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
$client_id=$_SESSION['client_id'];

?>
<?php 
$appVersionsql="SELECT users.user_id,users.name,survey_data_monitoring.survey_id,survey_data_monitoring.survey_name,full_json FROM `survey_data_monitoring` inner join users on users.user_id=survey_data_monitoring.user_id where survey_data_monitoring.client_id='".$client_id."' and users.status='0' group by survey_data_monitoring.user_id order by survey_data_monitoring.user_id DESC";
$qryAppVersion = mysqli_query($conn, $appVersionsql);		
if(mysqli_num_rows($qryAppVersion) > 0){ 
    $delimiter = ","; 
    $filename = "App_Version.csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
     
    // Set column headers 
    $fields = array('Form ID', 'Form Name', 'Username','App Version'); 

    fputcsv($f, $fields, $delimiter); 

    // Output each row of the data, format line as csv and write to file pointer
	while($row=mysqli_fetch_assoc($qryAppVersion)){
	
		$survey_id=$row['survey_id'];
		$survey_name=$row['survey_name'];
		$name=$row['name'];
		$DecryptedJson = $mcrypt->decrypt($row['full_json']);
		$full_json = json_decode($DecryptedJson, true);
		$app_version=$full_json[app_version];
        $lineData = array($survey_id,$survey_name, $name, $app_version); 
        fputcsv($f, $lineData, $delimiter); 
    } 
    // Move back to beginning of file 
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
} 
exit; 

?>