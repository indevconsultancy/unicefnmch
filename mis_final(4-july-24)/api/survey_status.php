<?php
header('Access-Control-Allow-Origin: *');
include('config.php');
$_REQUEST = json_decode(file_get_contents('php://input'),true);
    $data = Profile1($conn);
	echo json_encode($data);
	exit;
	
	function Profile1($conn) 
	{
        $qry='';
		$arrResults = array();
          echo  $survey_id=$_REQUEST['survey_id'];
			$user_id=$_REQUEST['user_id'];
			$survey_status=$_REQUEST['survey_status'];
		    // print_r($_REQUEST['tv_data']);
	
            if($survey_id!=''){
               $update_sql="update  survey_data_monitoring set survey_status='".$survey_status."' where survey_id='".$survey_id."'";
                $insertquery=mysqli_query($conn,$update_sql);
                if($insertquery){
					$arrResults=array("success"=>"1","message"=>"Update Succssesful");
                }else{
				$arrResults=array("success"=>"0","message"=>"inviled  survey_id");
			}
            }else{
				$arrResults=array("success"=>"0","message"=>"send survey_id");
			}
		return $arrResults;
	}

?>