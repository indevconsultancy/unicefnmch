<?php 
session_start();
require_once "../api/mycrypt.php";
define('hostname','65.0.119.62');
define('username','Mqm_22');
define('password','Mquad@22');
//define('database','new_mquad');
define('database','new_mquad');
$conn = mysqli_connect(hostname,username,password,database) or die(mysqli_error());
mysqli_set_charset($conn,"utf8");
if(!$conn)
{
    echo "Connection failed.......!";
}
if($_SESSION['enckey'] === 0){
    die("You are not authorized to see the survey");
}

$mcrypt = new EncryptionUtils($_SESSION['enckey']);
date_default_timezone_set("Asia/Kolkata");
error_reporting(0);
function safe_var($conn, $string)
{
	return $clean = mysqli_real_escape_string($conn, $string);
}
define('BASE_URL', "https://mquad.org/");
function base_url()
{
	return "https://mquad.org/";
}
if(empty($_SESSION['user_id'])){ echo "access denied!"; exit(); }
if($_SESSION['enckey'] === 0){
    die("You are not authorized to see the survey");
}
?>
<?php include_once('sscharts.php'); ?>



<?php 
	if(isset($_POST['chartType'])){
		$chartType = mysqli_real_escape_string($conn,$_POST['chartType']);
		$questionFName = mysqli_real_escape_string($conn,$_POST['questionFName']);
		$surveyId = mysqli_real_escape_string($conn,$_POST['surveyId']);
		$gTitle = mysqli_real_escape_string($conn,$_POST['gTitle']);
		$legendColors=[];
		if($_POST['legendColors']!=""){ $legendColors = explode(",", $_POST['legendColors']); }
		$gquestionFName = mysqli_real_escape_string($conn,$_POST['gquestionFName']);
		$ggTitle = mysqli_real_escape_string($conn,$_POST['ggTitle']);
		$cType = mysqli_real_escape_string($conn,$_POST['cType']);
		
		$title=$gTitle;
		$survey_id = $surveyId;
		$field_name = $questionFName;
		
		//echo '<input type="hidden" id="xTitle" value="'.$gTitle.'" /><input type="hidden" id="yTitle" value="'.$ggTitle.'" />';
		$others = array(); //array('xAxisTitle'=>$gTitle, 'yAxisTitle'=>$ggTitle);
		$response = dynamic_graph($conn, $survey_id, $chartType, $cType, $legendColors, $field_name, $gquestionFName,"",$others,$mcrypt);
		echo json_encode($response);
	}
?>



<?php
/// SAVE GRAPH DETAILS
if(isset($_POST['graph_title']) && $_POST['graph_title']!="" 
	&& isset($_POST['graph_survey_id']) && $_POST['graph_survey_id']!="" 
	&& isset($_POST['graph_fieldname']) && $_POST['graph_fieldname']!="" )
{
	$graph_title = mysqli_real_escape_string($conn, $_POST['graph_title']);
	$graph_description = mysqli_real_escape_string($conn, $_POST['graph_description']);
	$graph_survey_id = mysqli_real_escape_string($conn, $_POST['graph_survey_id']);
	$graph_fieldname = mysqli_real_escape_string($conn, $_POST['graph_fieldname']);
	$graph_group_field_name = mysqli_real_escape_string($conn, $_POST['graph_group_field_name']);
	$graph_chart_name = mysqli_real_escape_string($conn, $_POST['graph_chart_name']);
	$graph_chart_type = mysqli_real_escape_string($conn, $_POST['graph_chart_type']);
	$legendColors = mysqli_real_escape_string($conn, $_POST['legendColors']);
	$xtitle = mysqli_real_escape_string($conn, $_POST['xtitle']);
	$ytitle = mysqli_real_escape_string($conn, $_POST['ytitle']);
	$userid = $_SESSION['user_id'];
	$created_on = date("Y-m-d H:i:s");
	$chart_options = $_POST['customChartOptions'];
	$insert = mysqli_query($conn, "INSERT INTO surveydashboard SET title='".$graph_title."', xtitle='".$xtitle."', ytitle='".$ytitle."', description='".$graph_description."', fieldname='".$graph_fieldname."', groupby='".$graph_group_field_name."', colors='".$legendColors."', user_id='".$userid."', survey_id='".$graph_survey_id."', chart_name='".$graph_chart_name."', chart_type='".$graph_chart_type."', chart_options='".$chart_options."', created_on='".$created_on."'  ");
	if($insert){
		$result=array("status"=>1,"message"=>"Graph Added Successfully.");
	}else{
		$result=array("status"=>0,"message"=>"Something went wrong.");
	}
	echo json_encode($result);
	
}
?>
<?php
///DELETE GRAPH FROM SURVEY DASHBOARD
if(isset($_POST['process']) && $_POST['process']=="delete-chart" && isset($_POST['sdid']) && $_POST['sdid']!=""){
	$sdid = mysqli_real_escape_string($conn, $_POST['sdid']);
	$delsql=mysqli_query($conn,"UPDATE surveydashboard SET status='1' where id='".$sdid."' ");
	if($delsql){
		$result = array("status"=>1,"message"=>"Graph deleted successfully.");
	}else{
		$result = array("status"=>0,"message"=>"Something weng wrong.");
	}
	echo json_encode($result);
}
?>
