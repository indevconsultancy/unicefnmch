<?php
session_start();
header('Clear-Site-Data: "cache", "cookies", "storage", "executionContexts"');
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
//session_destroy();

header('location:login');
exit;
?>
