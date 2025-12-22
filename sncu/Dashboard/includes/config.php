<?php
define('hostname', 'localhost'); //'65.1.180.162'
define('username', 'unicef_db');
define('password', 'unicef_dblean@!pA');
define('database', 'sncu_db');

$conn = mysqli_connect(hostname, username, password, database) or die(mysqli_error());
mysqli_set_charset($conn, "utf8");
if (!$conn) {
    echo "Connection failed.......!";
}
