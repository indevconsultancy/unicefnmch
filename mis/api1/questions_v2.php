<?php
include('config.php');
header('Content-Type: application/json');
$surveyid=mysqli_real_escape_string($conn, $_REQUEST['survey_id']);
$path = "questionsjson/s".$surveyid.".json";
$myfile = fopen($path, "r") or die("Unable to open file!");
echo fread($myfile,filesize($path));
fclose($myfile);
?>