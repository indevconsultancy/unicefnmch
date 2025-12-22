<?php

// Security headers
//header("Content-Security-Policy: default-src 'self'; script-src 'self' https://trusted-cdn.com; object-src 'none'; style-src 'self' 'unsafe-inline'");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer-when-downgrade");
header("Feature-Policy: geolocation 'self'; camera 'none'");

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.gc_maxlifetime', 86400); // 24 minutes
session_set_cookie_params(86400); // 24 minutes

// Start the session with a custom name
//session_name('my_secure_session');


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

define('BASE_URL', "https://unicef.indevconsultancy.in/mis/");
function base_url()
{
    return "https://unicef.indevconsultancy.in/mis/";
}
