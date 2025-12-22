<?php
session_start();

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

					
?>
