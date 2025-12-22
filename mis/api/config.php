<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
 
// Secret key for JWT
$jwtSecretKey = $_ENV['JWT_SECRET_KEY'];
// Secret key for refresh token
$refreshTokenSecretKey = $_ENV['REFRESH_TOKEN_SECRET_KEY']; 

// Secret key for Encrypt
$firstKey = $_ENV['CRYPT_KEY'];

$API_KEY=$_ENV['SUPER_ADMIN_KEY'];
$API_URL=$_ENV['SUPER_ADMIN_URL'];

//DATABASE CONFIG
// define('hostname',$_ENV['DB_HOST']);
// define('username',$_ENV['DB_USER_NAME']);
// define('password',$_ENV['DB_PASSWORD']);
// define('database',$_ENV['DB_DATABASE']);
// JWT configuration
define('JWT_SECRET_KEY', $jwtSecretKey);
define('REFRESH_TOKEN_SECRET_KEY', $refreshTokenSecretKey);
define('CRYPT_KEY', $firstKey);

define('SUPER_ADMIN_KEY', $API_KEY);
define('SUPER_ADMIN_URL', $API_URL);

define('hostname', 'localhost');
define('username', 'unicef_db');
define('password', 'unicef_dblean@!pA');
//define('database','new_mquad');
define('database', 'unicef_db');

  $conn = mysqli_connect(hostname,username,password,database);
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
mysqli_set_charset($conn,"utf8");

function getone($tablename,$field,$qryfeild,$value,$conn)
{
	$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."'")or die(mysqli_error($conn));
	$dn=mysqli_fetch_object($sn);

	return ($dn->$field);
}
/////////// For sup survey
function getoneuser($tablename,$field,$qryfeild,$value,$conn)
{
	$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."' GROUP BY user_id order by survey_data_monitoring_id desc")or die(mysqli_error());
	$ids='';
	while($dn=mysqli_fetch_object($sn)){
		$ids.=$dn->$field.',';	
	}
	$data=substr(trim($ids),0,-1);
	return ($data);
}
////////////////////////////////////
function getone_multi($tablename,$field,$qryfeild,$value,$qryfeild1,$value1,$conn)
{
	$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."'")or die(mysqli_error());
	$dn=mysqli_fetch_object($sn);
	return ($dn->$field);
}

function getone_multi_language($tablename,$field,$qryfeild,$value,$qryfeild1,$value1,$language_id,$conn)
{
	$sn=mysqli_query($conn,"select $field from $tablename where $qryfeild='".$value."' and $qryfeild1='".$value1."' and language_id='".$language_id."' ")or die(mysqli_error());
	$dn=mysqli_fetch_object($sn);
	return ($dn->$field);
}

function countone($tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$conn)
{
	$sn2=mysqli_query($conn,"select count($fields) as counts from $tablename where $qryfeild1='".$value1."' and $qryfeild2='".$value2."'")or die(mysqli_error());
	$dn2=mysqli_fetch_object($sn2);
	return ($dn2->counts);
}
function getid($tablename,$fields,$qryfeild1,$value1,$conn)
{
	$sn3=mysqli_query($conn,"select $fields as ids from $tablename where $qryfeild1='".$value1."'")or die(mysqli_error());
	$dn3=mysqli_fetch_array($sn3);
	return ($dn3['ids']);
}

/////// count servey status////
function getsurvey($tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$conn)
{
	$sn4=mysqli_query($conn,"select $fields as ids from $tablename where $qryfeild1='".$value1."' and  $qryfeild2='".$value2."'")or die(mysqli_error());
	$dn4=mysqli_fetch_array($sn4);
	return ($dn4['ids']);
}


function getsurvey1($tablename,$fields,$qryfeild1,$value1,$qryfeild2,$value2,$qryfeild3,$value3,$conn)
{
	$sn4=mysqli_query($conn,"select $fields as ids from $tablename where $qryfeild1='".$value1."' and  $qryfeild2='".$value2."' and $qryfeild3='".$value3."'")or die(mysqli_error());
	$dn4=mysqli_fetch_array($sn4);
	return ($dn4['ids']);
}

function get_encryption_key($API_KEY, $API_URL, $cid){
	$arrResults = array("success" => "0", "enckey" => "123456");
	
/*
$headers = array(
    'Content-Type: application/json',
    'X-HAMC: '. $API_KEY,
);
$apiUrl = $API_URL . '/api/getenckey.php?cid='.$cid;

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
    
    return $responseData['enckey'];
}*/
return $arrResults['enckey'];
}

?>