<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

$API_KEY=$_ENV['SUPER_ADMIN_KEY'];
$API_URL=$_ENV['SUPER_ADMIN_URL'];
