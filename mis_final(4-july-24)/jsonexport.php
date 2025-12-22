<?php include_once('includes/config.php'); ?>
<?php
include_once('api/mycrypt.php');
ini_set('memory_limit', '-1');
$surveyid = $_REQUEST['survey_id'];
$unique_id = getone($conn,"survey","unique_id","id",$surveyid);
if($_SESSION['enckey'] === 0){
    die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
?>
<?php 
$sql="SELECT * FROM `survey_data_monitoring` WHERE survey_name_id='".$surveyid."' and survey_status!=7"; 

$response = array();
$posts = array();
$result=mysqli_query($conn, $sql);

while($row=mysqli_fetch_array($result)) { 
  $title=$mcrypt->decrypt($row['survey_data_json']); 
  $survey_name=$row['survey_name']; 

  $posts[] = json_decode($title);//array('title'=> $title, 'url'=> $url);
} 
//print_r($posts);
$response['interviews'] = $posts;

$generate_json = json_encode($response);

// Create filename
//$filename = 'Exported-json-'.$unique_id;
$filename = "JSON-".str_replace(" ","_",$survey_name);
// Force download .json file with JSON in it
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/force-download");
header("Content-Type: application/download");
header("Content-disposition: " . $filename . ".json");
header("Content-disposition: filename=" . $filename . ".json");

print $generate_json;
exit;
?> 