<?php include_once('../includes/config.php'); ?>
<?php 
 $survey_id=$_REQUEST['surid'];
 $sqlSurvey=mysqli_query($conn,"select survey_name from survey where id='".$survey_id."'");
 $dataForm=mysqli_fetch_array($sqlSurvey);
 $survey_name=$dataForm['survey_name'];
// Fetch records from database 

$queryData=mysqli_query($conn,"SELECT DISTINCT(users.name) as name,users.username,users.orignal_password FROM assign_survey left join users on users.user_id=assign_survey.user_id where survey_id='".$survey_id."' and assign_survey.status=0 order by users.name ASC"); 
			
if(mysqli_num_rows($queryData) > 0){ 
    $delimiter = ","; 
    $filename = $survey_name.".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
     
    // Set column headers 
    $fields = array('Full Name', 'Username', 'Password'); 

    fputcsv($f, $fields, $delimiter); 

    // Output each row of the data, format line as csv and write to file pointer
	while($row=mysqli_fetch_assoc($queryData)){
	
		$name=$row['name'];
		$username=$row['username'];
		$orignal_password=$row['orignal_password'];
        $lineData = array($name, $username, $orignal_password); 
        fputcsv($f, $lineData, $delimiter); 
    } 
    // Move back to beginning of file 
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
} 
exit; 
 
?>