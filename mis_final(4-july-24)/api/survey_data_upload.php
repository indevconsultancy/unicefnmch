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
	 //	print_r($_REQUEST['survey_data']);
		/// echo $_REQUEST['survey_data']['0']['field_name'];
      $full_json= json_encode($_REQUEST['survey_data']);
			$i=1;

			$arr['survey_id']=$_REQUEST['survey_id'];
			$arr['user_id']=$_REQUEST['user_id'];
            $survey_id=$_REQUEST['survey_id'];
			$user_id=$_REQUEST['user_id'];
		     //$_REQUEST['survey_data']['']
		     foreach($_REQUEST['survey_data'] as $key => $value)
			{
		    $question_id=$value['question_id'];
			$questions=getone_multi('questions','question_name','question_id',$question_id,'status','0',$conn);
		    $question_field_name=$value['field_name'];
		    $question_name='';
            $option_id=$value['option_id'];
			$option_value=$value['option_value'];
            
			$option_name='';
            if($option_value!=''){
                $option_data=$option_value;
            }else{
				$option_data=getone_multi('options','option_name','question_id',$question_id,'option_sequence',$option_id,$conn);
                //$option_data=$option_id;
            }
            $arr[$question_id]=$option_data;
			 //$arr[$questions]=$option_data;
            }
			
            $json=json_encode($arr);
            if($json!=''){
               // SELECT `survey_data_monitoring_id`, `survey_id`, `survey_data_id`, `survey_group_id`, `survey_data_json`, `user_id`, `survey_status`, `sent_date`,
                // `survey_created_on`, `full_json` FROM `survey_data_monitoring` WHERE 1
                $inser_sql="insert into survey_data_monitoring set survey_id='".$survey_id."',user_id='".$user_id."',survey_data_json='".$json."',full_json='".$full_json."'";
                $insertquery=mysqli_query($conn,$inser_sql);
                $last_id=mysqli_insert_id($conn);
                if($insertquery){
                    $arrResults=array("success"=>"1","message"=>"Save Succssesful", "survey_data_monitoring_id"=>$last_id);
                }
            }
		return $arrResults;
	}

?>