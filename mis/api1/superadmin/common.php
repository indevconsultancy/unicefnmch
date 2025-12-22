<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-type:application/json");
require_once "../config.php";


$headers = apache_request_headers();
$headers = array_change_key_case($headers,CASE_UPPER);

$clientHmac = $headers['X-HAMC'] ?? '';
 
if ($API_KEY !== $clientHmac) {

    http_response_code(401); 
    $response = ["status"=>401, "message" => "Not allowed!", "header"=> $headers];
    echo json_encode($response);
    exit;
    }

 $per_page=25;  
 $page=0;
 $current_page=1;
 
 // categories count
 $queryC ="select category_id from categories where 1";
 $get_queryC=mysqli_query($conn,$queryC);
 $total_recordC=mysqli_num_rows($get_queryC);
  
 // survey count
 $queryS ="select id from survey where 1";
 $get_queryS=mysqli_query($conn,$queryS);
 $total_recordS=mysqli_num_rows($get_queryS);
  
 // clients count
 $queryCl ="select id from clients where 1";
 $get_queryCl=mysqli_query($conn,$queryCl);
 $total_recordCl=mysqli_num_rows($get_queryCl);
  
 // users count
 $queryU ="select user_id from users where 1";
 $get_queryU=mysqli_query($conn,$queryU);
 $total_recordU=mysqli_num_rows($get_queryU);
 
 // projects count
 $queryP ="select project_id from  projects where 1";
 $get_queryP=mysqli_query($conn,$queryP);
 $total_recordP=mysqli_num_rows($get_queryP);
 
  // logs count
 $queryL ="select user_log_id from  user_log where 1";
 $get_queryL=mysqli_query($conn,$queryL);
 $total_recordL=mysqli_num_rows($get_queryL);
 
 $final_total = $total_recordP + $total_recordU + $total_recordCl + $total_recordS + $total_recordC + $total_recordL;
 
 $rows = array(); 
 $rows['final_total']=$final_total;
    