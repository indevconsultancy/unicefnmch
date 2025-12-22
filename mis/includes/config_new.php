<?php
session_start();

?>


<?php

// Configure session settings
ini_set('session.cookie_secure', $secure);
ini_set('session.cookie_httponly', $httponly);
ini_set('session.use_only_cookies', 1);


// if ($_SERVER['REQUEST_METHOD'] === 'TRACE') {
// header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
// exit;
// }
/*// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.gc_maxlifetime', 86400); // 24 minutes
session_set_cookie_params(86400); // 24 minutes
*/
// Start the session with a custom name
//session_name('my_secure_session');


require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$API_KEY = $_ENV['SUPER_ADMIN_KEY'];
$API_URL = $_ENV['SUPER_ADMIN_URL'];


// Secret key for JWT
$jwtSecretKey = $_ENV['JWT_SECRET_KEY'];
// Secret key for refresh token
$refreshTokenSecretKey = $_ENV['REFRESH_TOKEN_SECRET_KEY'];

define('hostname', 'localhost');
define('username', 'unicef_db');
define('password', 'unicef_dblean@!pA');
//define('database','new_mquad');
define('database', 'unicef_db');


define('API_KEY', $API_KEY);
define('API_URL', $API_URL);

define('JWT_SECRET_KEY_THIRD', $jwtSecretKey);
define('REFRESH_TOKEN_SECRET_KEY_THIRD', $refreshTokenSecretKey);

$conn = mysqli_connect(hostname, username, password, database) or die(mysqli_error());
mysqli_set_charset($conn, "utf8");
if (!$conn) {
	echo "Connection failed.......!";
}

error_reporting(0);
function safe_var($conn, $string)
{
	$string = trim(str_replace("'", "", $string));
	//$string = trim(str_replace('\','\\',$string));
	return $clean = mysqli_real_escape_string($conn, $string);
}

function plan_text($conn, $string)
{
	$string = trim(str_replace("'", "", $string));
	return $clean = mysqli_real_escape_string($conn, $string);
}

define('BASE_URL', "https://unicef.indevconsultancy.com/mis/");
function base_url()
{
	return "https://unicef.indevconsultancy.com/mis/";
}

function subscription_services($conn, $clientId)
{
	$sqlss = "call CheckUserSubscriptionStatus($clientId)";
	$statements = mysqli_query($conn, $sqlss);
	$subscriptionData = mysqli_fetch_assoc($statements);
	return ($subscriptionData);
}


function getone($conn, $tablename, $field, $qryfeild, $value)
{
	//echo  "select $field from $tablename where $qryfeild='".$value."'";
	$sn = mysqli_query($conn, "select $field from $tablename where $qryfeild='" . $value . "'") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn->$field);
}

function getMulticolumns($conn, $tablename, $fields, $qryfeild, $value)
{
	$sn = mysqli_query($conn, "select $fields from $tablename where $qryfeild='" . $value . "'") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn);
}

function getdata($conn, $tablename, $field, $where)
{
	// echo "select $field from $tablename where $qryfeild='".$value."'";
	$sn = mysqli_query($conn, "select $field from $tablename $where ") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn->$field);
}
function getcounts_multi($conn, $tablename, $field, $qryfeild, $value, $qryfeild1, $value1)
{

	// echo "select count($field) as total from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."'  ";	
	$sn = mysqli_query($conn, "select count($field) as total from $tablename where $qryfeild='" . $value . "' and $qryfeild1='" . $value1 . "' ") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn->total);
}
function getcounts_tool($conn, $tablename, $field, $qryfeild, $value, $qryfeild1, $value1, $qryfeild2, $value2)
{

	// echo "select count($field) as total from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."' AND status='1'";	
	$sn = mysqli_query($conn, "select count($field) as total from $tablename where $qryfeild='" . $value . "' and $qryfeild1='" . $value1 . "' and $qryfeild2='" . $value2 . "' ") or die(mysqli_error());
	$dn = mysqli_fetch_object($sn);
	return ($dn->total);
	// return "select count($field) as total from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."' and $qryfeild2='".$value2."'";
}

function encrypt_url($string)
{
	$output = false;
	$secret_iv = "MQUAD53421M5Q4U3A2D1";
	$secret_key = "D12A3QU45M21";
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	$encrypt_method = "AES-256-CBC";
	$result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($result);
	return $output;
}

function decrypt_url($string)
{
	$output = false;
	$secret_iv = "MQUAD53421M5Q4U3A2D1";
	$secret_key = "D12A3QU45M21";
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	$encrypt_method = "AES-256-CBC";
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}

checksession($API_KEY, $API_URL);

function checksession($API_KEY, $API_URL)
{
	if (!isset($_SESSION['user_id']))
		return true;


	$headers = array(
		'Content-Type: application/json',
		'X-HAMC: ' . $API_KEY,
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
		if ($responseData['active'] == 0) {
			mysqli_query($conn, 'UPDATE users SET status=1 WHERE user_id = ' . $_SESSION['user_id']);
			session_destroy();
			header('location:https://nmch.indevconsultancy.com/mis/');
			exit;
		}
	}
}



?>


<?php
function checkQuota($conn, $clientId, $quotaFor)
{
	$target = '';
	$achievment = '';
	$credits = -1;
	if ($quotaFor == 'project_space') {

		$target = 'pu.project_space';
		$achievment = 'pus.project_space_created';
	} else if ($quotaFor == 'storage_usage') {
		$target = 'pu.storage_usage';
		$achievment = 'pus.storage_usage';
	}
	if ($_SESSION['subscription_scope'] == 0) {
		//echo $_SESSION['subscription_scope'];
		//echo "SELECT $target as target,$achievment as achievment from pm_userSubscription_status pus,pm_usersubscriptions pu where pus.client_id='$clientId' and pus.client_id=pu.client_id";
		$qrySubscription_status = mysqli_query($conn, "SELECT $target as target,$achievment as achievment from pm_userSubscription_status pus,pm_usersubscriptions pu where pus.client_id='$clientId' and pus.client_id=pu.client_id");
		$dataSubscription_status = mysqli_fetch_array($qrySubscription_status);
		$credits = $dataSubscription_status['achievment'] - $dataSubscription_status['target'];
	} else {
		$credits = -1;
	}
	return $credits;
}

function checkPermission($conn, $survey_id, $permission)
{
	if ($_SESSION['role_id'] != "1" && $_SESSION['registered_as'] == "") {
		//echo "SELECT COUNT(id) AS totAcc FROM formlabel_controls WHERE user_id='".$_SESSION['user_id']."' AND survey_id='".$survey_id."' AND FIND_IN_SET($permission, page_button_id) ";
		$getCheckPerms = mysqli_query($conn, "SELECT COUNT(id) AS totAcc FROM formlabel_controls WHERE user_id='" . $_SESSION['user_id'] . "' AND survey_id='" . $survey_id . "' AND FIND_IN_SET($permission, page_button_id) ");
		$checkPerms = mysqli_fetch_object($getCheckPerms);
		if ($checkPerms->totAcc == 0) {
			echo "<script>window.location.href='access-denied.php'</script>";
			exit;
			//$res = array("status"=>0,"msg"=>"Access denied.");
		}
		// else{
		// $res = array("status"=>1,"msg"=>"Access granted.");
		// }
		// return $res;
	}
}


?>
