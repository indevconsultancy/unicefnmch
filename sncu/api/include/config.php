<?php
$host = "localhost";
$db_name = "sncu_db";
$username = "unicef_db";
$password = "unicef_dblean@!pA";

$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
