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
    
    $total_pages=ceil($total_recordCl/$per_page);

  $sql = "select clients.id, clients.name, clients.mobile, clients.email, clients.address, clients.status, clients.del_action, roles.name as role, membership.title from clients ";
  $sql .= "left join roles on clients.role_id = roles.id left join membership on membership.membership_id = clients.membership_id limit $page,$per_page";
  $getsql = mysqli_query($conn,$sql);
		
		$rows['page']=$current_page;
		$rows['per_page']=$per_page;
		$rows['total']=$total_recordCl;
		$rows['total_pages']=$total_pages;
		$rows['source']='client';
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