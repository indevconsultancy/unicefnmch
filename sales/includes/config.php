<?php
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$API_KEY=$_ENV['SUPER_ADMIN_KEY'];
$API_URL=$_ENV['SUPER_ADMIN_URL'];


// Secret key for JWT
$jwtSecretKey = $_ENV['JWT_SECRET_KEY'];
// Secret key for refresh token
$refreshTokenSecretKey = $_ENV['REFRESH_TOKEN_SECRET_KEY']; 

define('hostname','65.0.119.62');
define('username','Mqm_22');
define('password','Mquad@22');
//define('database','new_mquad');
define('database','new_mquad');


define('API_KEY', $API_KEY);
define('API_URL', $API_URL);

define('JWT_SECRET_KEY_THIRD', $jwtSecretKey);
define('REFRESH_TOKEN_SECRET_KEY_THIRD', $refreshTokenSecretKey);

$conn = mysqli_connect(hostname,username,password,database) or die(mysqli_error());
mysqli_set_charset($conn,"utf8");
if(!$conn)
{
    echo "Connection failed.......!";
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//error_reporting(0);
function safe_var($conn,$string){
	$string = trim(str_replace("'","",$string));
    return $clean = mysqli_real_escape_string($conn,$string);
}

function plan_text($conn,$string){
	$string = trim(str_replace("'","",$string));
    return $clean = mysqli_real_escape_string($conn,$string);
}

define('BASE_URL', "https://mquad.org/sales/");
function base_url(){
    return "https://mquad.org/sales/";
}

function subscription_services($conn,$clientId)
{
	$sqlss = "call CheckUserSubscriptionStatus($clientId)";
	$statements=mysqli_query($conn,$sqlss);
	$subscriptionData=mysqli_fetch_assoc($statements);
	return($subscriptionData);
}


function getone($conn,$tablename,$field,$qryfeild,$value)
{
//echo  "select $field from $tablename where $qryfeild='".$value."'";
$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."'")or die(mysqli_error());
$dn=mysqli_fetch_object($sn);
	return ($dn->$field);
}

function getMulticolumns($conn,$tablename,$fields,$qryfeild,$value)
{
	$sn=mysqli_query($conn,"select $fields from $tablename where $qryfeild='".$value."'")or die(mysqli_error());
	$dn=mysqli_fetch_object($sn);
	return ($dn);
}

function getdata($conn,$tablename,$field,$where)
{
// echo "select $field from $tablename where $qryfeild='".$value."'";
$sn=mysqli_query($conn,"select $field from $tablename $where ")or die(mysqli_error());
$dn=mysqli_fetch_object($sn);
	return ($dn->$field);
}
function getcounts_multi($conn,$tablename,$field,$qryfeild,$value,$qryfeild1,$value1) {

	// echo "select count($field) as total from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."'  ";	
	$sn=mysqli_query($conn,"select count($field) as total from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."' ")or die(mysqli_error());
	$dn=mysqli_fetch_object($sn);
	return ($dn->total);
}
function getcounts_tool($conn,$tablename,$field,$qryfeild,$value,$qryfeild1,$value1,$qryfeild2,$value2) {

	// echo "select count($field) as total from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."' AND status='1'";	
	$sn=mysqli_query($conn,"select count($field) as total from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."' and $qryfeild2='".$value2."' ")or die(mysqli_error());
	$dn=mysqli_fetch_object($sn);
	return ($dn->total);
	// return "select count($field) as total from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."' and $qryfeild2='".$value2."'";
}

function encrypt_url($string) {
	$output = false;
	$secret_iv = "1234567891011121";
	$secret_key = "Satyendra";
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	$encrypt_method = "AES-256-CBC";
	$result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($result);
	return $output;
}

function decrypt_url($string) {
	$output = false;
	$secret_iv = "1234567891011121";
	$secret_key = "Satyendra";
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	$encrypt_method = "AES-256-CBC";
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}
checksession($API_KEY, $API_URL);
function checksession($API_KEY, $API_URL){
    if(!isset($_SESSION['user_id']))
      return true;


$headers = array(
    'Content-Type: application/json',
    'X-HAMC: '. $API_KEY,
);
$apiUrl = $API_URL . '/api/active/' . $_SESSION['user_id'];


$ch = curl_init($apiUrl);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


$response = curl_exec($ch);


if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
}

curl_close($ch);

if ($response) {
    $responseData = json_decode($response, true);
    if($responseData['active'] == 0){
        mysqli_query($conn, 'UPDATE users SET status=1 WHERE user_id = '.$_SESSION['user_id']);
        session_destroy();
        header('location:https://mquad.org/mis/');
        exit;
    }
    
}
}



?>