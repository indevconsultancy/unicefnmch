<?php
session_start();

session_unset();
session_destroy();

header("Location: https://unicef.indevconsultancy.in/sncu/index.php");
exit;
