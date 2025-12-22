<?php
header('Access-Control-Allow-Origin: *');
include('config.php');
$_REQUEST = json_decode(file_get_contents('php://input'),true);

$data = login($conn);
echo json_encode($data);
exit;
function login($conn) 
	{
		$arrResults = array();
		$version = $_REQUEST['version'];
		//$password=$_REQUEST['user_password'];
		if($version==1.0){
			 $arrResults=array("success"=>"1", "version"=>"1.0");
		}
		return $arrResults;
	}
	