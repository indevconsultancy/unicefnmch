<?php
session_start();
define('hostname', 'localhost'); //'65.1.180.162'
define('username', 'unicef_db');
define('password', 'unicef_dblean@!pA');
//define('database','new_mquad');
define('database', 'unicef_db');

$conn = mysqli_connect(hostname, username, password, database) or die(mysqli_error());
mysqli_set_charset($conn, "utf8");
if (!$conn) {
    echo "Connection failed.......!";
}

error_reporting(0);

define('BASE_URL', "https://unicef.indevconsultancy.in/stage/");
function base_url()
{
    return "https://unicef.indevconsultancy.in/stage/";
}
