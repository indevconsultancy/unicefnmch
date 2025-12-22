<?php 
header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: PUT, GET, POST");
	include "config.php";
    $data = imageupload($conn);
	echo json_encode($data);

	function imageupload($conn)
	{
	    $uploadfile=isset($_FILES['uploadfile'])? $_FILES['uploadfile']:'';
	    $file_type=isset($_REQUEST['file_type'])? $_REQUEST['file_type']:'';
        $survey_id=isset($_REQUEST['survey_id'])? $_REQUEST['survey_id']:'';
        $user_id=isset($_REQUEST['user_id'])? $_REQUEST['user_id']:'';
		$survey_data_monitoring_id=isset($_REQUEST['survey_data_monitoring_id'])? $_REQUEST['survey_data_monitoring_id']:'';
	    if($uploadfile!='' && $survey_id!='')
	    {
            if($file_type=='image'){
                $folder_path="uploadedfiles/images/";
            }else if($file_type=='audio'){
                $folder_path="uploadedfiles/audios/";
            }else if($file_type=='video'){
                $folder_path="uploadedfiles/videos/";
            }
	        
	       
	        $filePath="";
	            
                   $file_name = $_FILES['uploadfile']['name'];
            		$position= strpos($file_name, ".");
                    $fileextension= substr($file_name, $position + 1);
                    $fileextension= strtolower($fileextension);
                    date_default_timezone_set('Asia/Kolkata');
                    $date=date("Y_m_d_h_i_s");
                    $file_name=$survey_id."_".$user_id."_".$date.".".$fileextension;
                    
            		$temp_name = $_FILES['uploadfile']['tmp_name'];
            		$location = $folder_path;
    		        $filePath= $location.$file_name;
    		        $status=0;
				//	echo 'audios/'.$_FILES['audio']['name'];
					// if (!is_writeable('images')) {
     //                    die("Cannot write to destination file");
     //                }

    		        if(move_uploaded_file($temp_name , $filePath)) {
                            $response = "success";
                              $status=1;
							  	$sql="INSERT INTO  uploadedfiles set file_name='".$file_name."', survey_id='".$survey_id."', survey_data_monitoring_id='".$survey_data_monitoring_id."', user_id='".$user_id."', file_type='".$file_type."' ";
	        	         		 mysqli_query($conn,$sql);
                        }else
                        {
                            $response = "error";
                        }

                     if($status==1)
        	        {
					
					   $arrResults=array("success"=>"1","message"=>"Data successfuly submitted","name"=>$file_name, "file_status" =>$response); 
        	        }
        	        else
        	        {
        	            $arrResults=array("success"=>"0","message"=>"error", "file_status" =>$response); 
        	            
        	           if (file_exists($filePath)) 
                       {
                       //   unlink($filePath);
                       }
                    
        	        }
                
         
	    }
	    else
	    {
	        $arrResults=array("success"=>"0","message"=>"file name is emplty", "file_status" =>$response); 
	    }
	    mysqli_close($conn);
	  	return $arrResults;
	}
?>