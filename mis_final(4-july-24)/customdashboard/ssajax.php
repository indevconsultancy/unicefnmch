<?php require_once "../includes/config.php";
require_once "../api/mycrypt.php";
if($_SESSION['enckey'] === 0){
    die("You are not authorized to see the survey");
}
$mcrypt = new EncryptionUtils($_SESSION['enckey']);
?>

<?php include_once('sscharts.php'); ?>


<?php 
	if(isset($_POST['chartType'])){
		$chartType = mysqli_real_escape_string($conn,$_POST['chartType']);
		$questionFName = mysqli_real_escape_string($conn,$_POST['questionFName']);
		$surveyId = mysqli_real_escape_string($conn,$_POST['surveyId']);
		$cid = mysqli_real_escape_string($conn,$_POST['cid']);
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
		$others = array('cid'=>$cid); //array('xAxisTitle'=>$gTitle, 'yAxisTitle'=>$ggTitle);
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


///CHANGE GRAPH SEQUENCE OF SURVEY DASHBOARD
if(isset($_POST['process']) && $_POST['process']=="sequence-chart" && isset($_POST['survey_id']) && $_POST['survey_id']!=""){
	$survey_id = mysqli_real_escape_string($conn, $_POST['survey_id']);
	$chartOrders = $_POST['chartOrder'];
	foreach($chartOrders as $key=>$chartOrder){
		$seqSql=mysqli_query($conn,"UPDATE surveydashboard SET sequence='".$key."' where id='".$chartOrder."' ");
	}
	if($seqSql){
		$result = array("status"=>1,"message"=>"Sequence updated successfully.");
	}else{
		$result = array("status"=>0,"message"=>"Something weng wrong.");
	}
	echo json_encode($result);
}

if(isset($_POST['process']) && $_POST['process']=="UPDATE-GRAPH"){
	$graphId = mysqli_real_escape_string($conn, $_POST['graphId']);
	$graphTitle = mysqli_real_escape_string($conn, $_POST['graphTitle']);
	$graphDescription = mysqli_real_escape_string($conn, $_POST['graphDescription']);
	$graphDescriptionsql = '';
	if(!empty($graphDescription)){
		$graphDescriptionsql=" description='".$graphDescription."', ";
	}
	$sql = "UPDATE surveydashboard SET $graphDescriptionsql title='".$graphTitle."'  where id='".$graphId."' ";
	$resArr = ['success'=>false,'message'=>'failed'];
	if(!empty($graphTitle)){
		$res = mysqli_query($conn,$sql);
		if($res){
			$resArr = ['success'=>true,'message'=>'success'];
		}
	}
	
	echo json_encode($resArr);
}
?>
