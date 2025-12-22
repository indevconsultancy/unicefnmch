<?php
header('Access-Control-Allow-Origin: *');
header("Content-type: application/json; charset=utf-8");
include 'config.php';
require_once 'jwt.php';

$_REQUEST = json_decode(file_get_contents('php://input'), true);
$data = getkeyss($conn);
echo json_encode($data);
exit;

function getkeyss($conn)
{
    $arrResults = array();
	$jwtToken = generateToken($_REQUEST['user_id'], JWT_SECRET_KEY, $_REQUEST['client_id']);
    $arrResults = array("success" => "0", "message" => "Success", "token" => $jwtToken);
    
    return $arrResults;
}
