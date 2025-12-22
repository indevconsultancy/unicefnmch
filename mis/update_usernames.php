<?php
header('Content-Type: application/json; charset=utf-8');
include_once('includes/config.php');
include_once('includes/functions.php');

$sql=mysqli_query($conn,"select survey_data_monitoring_id,JSON_UNQUOTE(JSON_EXTRACT(survey_data_json, '$.monitor_name')) as mon_name,user_types from survey_data_monitoring where m_name='' or m_name is null");
while($data=mysqli_fetch_object($sql))
{
	$dname=substr($data->mon_name,0,10);
	//echo "select name,organization from consultants where name like'%".$dname."%'";
	$sql1=mysqli_query($conn,"select name,original_name,organization from consultants where name like'%".$dname."%'");
	$data1=mysqli_fetch_object($sql1);
	mysqli_query($conn,"update survey_data_monitoring set m_name='".$data1->original_name."',org='".$data1->organization."',u_status=1 where survey_data_monitoring_id='".$data->survey_data_monitoring_id."'");
	echo "done ";
}
?>
