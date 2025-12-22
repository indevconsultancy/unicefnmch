<?php
// Include the AWS SDK autoloader 
require 'vendor/autoload.php'; 
use Aws\S3\S3Client; 
 
// Amazon S3 API credentials 
$s3Client = new S3Client([
    'version' => 'latest',
    'scheme' =>'http',
    'region' => 'ap-south-1',
    'credentials' => [
        'key' => 'AKIAZLY6QIYPRMM6SEOJ',
        'secret' => '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'
    ],
]);
?>