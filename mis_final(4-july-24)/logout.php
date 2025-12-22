<?php
session_start();
header('Clear-Site-Data: "cache", "cookies", "storage", "executionContexts"');
session_destroy();
header('location:https://mquad.org/');
exit;
?>