<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
require_once "config.php";
$_REQUEST = json_decode(file_get_contents('php://input'),true);
			$data = Profile1($conn);
			echo json_encode($data);
			exit;

			
function Profile1($conn)
{
	$arrResults = array();	
	
	if(isset($_REQUEST))
		{
		 $mobile=isset($_REQUEST['visit_id'])?$_REQUEST['visit_id']:'';
		
        if($_REQUEST['visit_id']!='' )
	     {
			 
			$id= $_REQUEST['visit_id'];
			    $sqlMobile=mysqli_query($conn, "select mobile from pw_iron_visit where id='".$id."'");
				$dataMobile=mysqli_fetch_object($sqlMobile);
				$mobiles=$dataMobile->mobile;
			 $Usersql1 = "update pw_iron_visit set visit_status='1' where id='".$id."'";
			 $sqlinsert1=mysqli_query($conn,$Usersql1);
			 //////////////////// Follow-up Calls ///////////////////
			 $curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'http://115.241.99.180/indev/call-to-question.php?did=1135352929&number=0'.$mobiles.'&audio_name=%2Fetc%2Fasterisk%2Fsounds%2FReminder',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				));

				$response = curl_exec($curl);

				curl_close($curl);
				//echo $response;
				//////////////////////////// End IVR Call
		        if($sqlinsert1)
				{
                 $arrResults = array("success" => 1, "message" => 'Success',"message"=>"Follow-up Updated");
				}
				else
				{
                $arrResults = array("success" => 0, "message" => 'Failed1');
				}

        }
		else {
                $arrResults = array("success" => 0, "message" => 'Failed2');
				}
	    }
		else
		{
		 $arrResults = array("success" => 0, "message" => 'Failed3');
		}
	return $arrResults;	
}

?>