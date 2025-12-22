<?php 
//header('Access-Control-Allow-Origin: *');
require_once "config.php";
if (isset($_REQUEST['cid']) && $_REQUEST['cid']!='') {
	//$_REQUEST = json_decode(file_get_contents('php://input'),true);
	//print_r($_REQUEST);
	echo $cid=$_REQUEST['cid'];
	$data = Profile1($conn,$cid);
	echo json_encode($data);
	exit;
} else {

    http_response_code(405); // Method Not Allowed
    $response = ["status"=>0, "message" => "The call method not allowed"];
    echo json_encode($response);
    exit;
}
function Profile1($conn,$cid=0)
{
	     $response = ["status"=>0, "message" => "Auth request is invalid"];
	     $client_id=$cid;
		
        if($client_id!='' )
	     {
		  
			  echo $Usersql = "SELECT `encryption_key` FROM `clients` where id='".$client_id."' and status='1'";
			  $fetch = mysqli_query($conn,$Usersql);
			  $countdata = mysqli_num_rows($fetch);
			  if($countdata>0)
			  {
				  $datarow=mysqli_fetch_object($fetch)
				  $response = ["status"=>1, "message" => "Success","enckey" =>$datarow->encryption_key];
			  }
			  else
			  {
				$response = ["status"=>0, "message" => "Not an authorized user"];  
			  }
		  
         }
		 else 
		 {
			 $response = ["status"=>0, "message" => "The call method not allowed"];
		 }
         return $response;
	  
		
}


?>