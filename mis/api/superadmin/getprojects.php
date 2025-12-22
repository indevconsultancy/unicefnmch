<?php
require_once "common.php";
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    
    mysqli_set_charset($conn,'utf8'); 
    if(isset($_GET['per_page'])){
        $per_page=intval($_GET['per_page']);
    }
    if(isset($_GET['page'])){
        $current_page=intval($_GET['page']);
        $page=($current_page-1)*$per_page;
    }
    
    $total_pages=ceil($total_recordP/$per_page);
    $sql="select * from projects limit $page,$per_page";
	$getsql = mysqli_query($conn,$sql);
		
		$rows['page']=$current_page;
		$rows['per_page']=$per_page;
		$rows['total']=$total_recordP;
		$rows['total_pages']=$total_pages;
		$rows['source']='project';
	while ($row = mysqli_fetch_array($getsql, MYSQLI_ASSOC)) 
		{
		    $rows['data'][]=$row;
		}
		echo json_encode($rows);
		exit;
} else {
    http_response_code(405); // Method Not Allowed
    $response = ["status"=>0, "message" => "The call method not allowed"];
    echo json_encode($response);
    exit;
}