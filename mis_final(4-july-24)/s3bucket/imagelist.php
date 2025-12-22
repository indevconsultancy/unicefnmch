<?php
require 's3_bucket_config.php';

//Get a command to GetObject
$cmd = $s3Client->getCommand('GetObject', [
    'Bucket' => 'mquaddata',
    'Key'    => 'mquad_img/logo_-562d53284c473d.png'
]);

//The period of availability
$request = $s3Client->createPresignedRequest($cmd, '+20 seconds');

//Get the pre-signed URL
echo $signedUrl = (string) $request->getUri();


 echo '<img src="'.$signedUrl.'" height="400" />';

?>