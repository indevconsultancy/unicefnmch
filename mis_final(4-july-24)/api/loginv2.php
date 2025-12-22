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
		$user_name = $_REQUEST['user_name'];
		$password=$_REQUEST['user_password'];
		$firebase_token=$_REQUEST['firebase_token'];
		$user_password= md5($password);
        $sql="SELECT `user_id`, `name`, `mobile`, `username`, `password`, `user_type_id`, `status`, `email`, `agency_code`, `created_at` FROM `users` WHERE username='".$user_name."' and password='".$user_password."' and status=0 and user_type_id=1";
		 $run=mysqli_query($conn,$sql);
		 $count=mysqli_num_rows($run);

		if($count==1)
	   {
function getone1($conn,$tablename,$field,$qryfeild,$value)
		{
			
		$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."'")or die(mysqli_error());
		$dn=mysqli_fetch_object($sn);
			return ($dn->$field);
		}
           $data=mysqli_fetch_object($run);
           if($user_password==$data->password){
			   $name=$data->name;
               $user_name=$data->username;
               $user_id=$data->user_id;
			   $user_type_id=$data->user_type_id;
			   $email=$data->email; 
			   $agency_code=$data->agency_code;
			   $sqlsupervisor=mysqli_query($conn,"select user_code,user_id,name from users where agency_code='".$agency_code."' and user_type_id=2 and user_id in(select user_id from user_allocation where census_code in(select census_code from user_allocation where user_id='".$user_id."'))");
			   $datasupervisor=mysqli_fetch_object($sqlsupervisor);
			   $user_code=$datasupervisor->user_code;
			   $sup_name=$datasupervisor->name;
			   //$supervisorid=getone1($conn,"users","user_code","user_id",$user_id);
			   //$sup_id=getone1($conn,"rm_supervisor","supervisor_id","rm_id",$user_id);
			  // $sup_name=getone1($conn,"users","name","user_id",$sup_id);
         //$user_code=getone1($conn,"users","user_code","user_id",$sup_id);
         $agency_name=getone1($conn,"agency","name","agency_code",$agency_code);
				$updateuser="update users set firebase_token='".$firebase_token."' where user_id='".$user_id."'";
				$updatequery=mysqli_query($conn,$updateuser);
				if($updatequery){
                 $arrResults = array("success" =>1, "message" => 'Login Successful.',
                 "user_id"=>$user_id,"interviewer_id"=>$user_id,"interviewer_name"=>$name,"user_name"=>$user_name,"user_type_id"=>$user_type_id,"email"=>$email,"mdl_id"=>$agency_code,"supervisor_id"=>$user_code,"supervisor_name"=>$sup_name,"agency_name"=>$agency_name);
				}else{
                $arrResults=array("success"=>"0", "message"=>"Firebase token missing");
            }
			}else{
                $arrResults=array("success"=>"0", "message"=>"inviled username or password");
            }
			}
	   else
	   {
		  
		   
			 $arrResults=array("success"=>"0", "message"=>"inviled username or password");
	   }
	   
     
	   
	   return $arrResults;
	}



?>