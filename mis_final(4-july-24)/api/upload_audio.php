<?php 
header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: PUT, GET, POST");
	include "config.php";
    $data = imageupload($conn);
	echo json_encode($data);

	function imageupload($conn)
	{
	    $audio_name=isset($_FILES['audio_name'])? $_FILES['audio_name']:'';
	    $survey_id=isset($_REQUEST['survey_id'])? $_REQUEST['survey_id']:'';
        $user_id=isset($_REQUEST['user_id'])? $_REQUEST['user_id']:'';
		$survey_data_monitoring_id=isset($_REQUEST['survey_data_monitoring_id'])? $_REQUEST['survey_data_monitoring_id']:'';
	    if($audio_name!='' && $survey_id!='')
	    {
			
			/*
	        $folder_path="audios/";
	        //$folder_path="/etc/asterisk/sounds";
	       
	        $filePath="";
                   $file_name = $_FILES['audio_name']['name'];
            		$position= strpos($file_name, ".");
                    $fileextension= substr($file_name, $position + 1);
                    $fileextension= strtolower($fileextension);
                    date_default_timezone_set('Asia/Kolkata');
                    $date=date("Y_m_d_h_i_s");
                    $file_name=$survey_id."_".$user_id."_".$date.".".$fileextension;
                    
            		$temp_name = $_FILES['audio_name']['tmp_name'];
            		$location = $folder_path;
    		        $filePath= $location.$file_name;
    		        $status=0;
				//	echo 'audios/'.$_FILES['audio']['name'];
					if (!is_writeable('audios')) {
				   die("Cannot write to destination file");
				}
    		        if(move_uploaded_file($temp_name , $filePath)) {
                            $response = "success";
                              $status=1;
							  	$sql="update survey_data_monitoring set audio='".$file_name."' where survey_data_monitoring_id='".$survey_data_monitoring_id."'";
	        	         		 mysqli_query($conn,$sql);
                        }else
                        {
                            $response = "error";
                        }
*/
			
				$status=1;
				$response="success";
				$file_name="test";
                     if($status==1)
        	        {
					
					   $arrResults=array("success"=>"1","message"=>"Data successfuly submitted","name"=>$file_name, "file_status" =>$response); 
        	        }
        	        else
        	        {
        	            $arrResults=array("success"=>"0","message"=>"error", "file_status" =>$response); 
        	            
        	        //   if (file_exists($filePath)) 
                     //  {
                       //   unlink($filePath);
                     //  }
                    
        	        }
                
         
	    }
	    else
	    {
	        $arrResults=array("success"=>"0","message"=>"file name is emplty", "file_status" =>$response); 
	    }
	    mysqli_close();
	  	return $arrResults;
	}
?>