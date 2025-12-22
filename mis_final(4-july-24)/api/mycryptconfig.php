<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
 
// Secret key for Encrypt
$firstKey = $_ENV['CRYPT_KEY'];

define('CRYPT_KEY', $firstKey);