<?php

include('../includes/config_s3.php');

require 's3_bucket_config.php';


use Aws\S3\S3Client;
use Aws\Exception\AwsException;
ini_set('memory_limit', '-1');


function downloadZipFile($s3Client, $client_id, $survey_id) {
    $clientID = "C" . $client_id;
    $bucket = 'mquaddata';
    $folderKey = 'media_survey/' . $clientID . '/' . $survey_id . '/';

    try {
        // List all objects in the folder
        $objects = $s3Client->listObjects([
            'Bucket' => $bucket,
            'Prefix' => $folderKey,
        ]);

        // Create a temporary file to store the zip
        $zip = new ZipArchive();
        $uniqueID = uniqid('s3_', true);
        $zipFileName = '/tmp/' . $uniqueID . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            throw new Exception('Cannot create zip file');
        }
		
        foreach ($objects['Contents'] as $object) {
            $fileKey = $object['Key'];
            $fileName = basename($fileKey);
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Determine the folder based on file extension
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $folder = 'images/';
            } elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])) {
                $folder = 'videos/';
            } elseif (in_array($fileExtension, ['mp3', 'wav'])) {
                $folder = 'audio/';
            } else {
                $folder = 'others/';
            }

            // Download file content
            $result = $s3Client->getObject([
                'Bucket' => $bucket,
                'Key' => $fileKey
            ]);
	
            // Add file to zip archive in the corresponding folder
            $zip->addFromString($folder . $fileName, $result['Body']);
        }

        $zip->close();

        // Serve the zip file for download
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="files1087.zip"');
        header('Content-Length: ' . filesize($zipFileName));

        readfile($zipFileName);
        // Delete the temporary zip file
        unlink($zipFileName);

        exit;
    } catch (AwsException $e) {
        echo 'Error: ' . $e->getMessage();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

 // if (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != '') {
	// $survey_id=$_REQUEST['survey_id'];
	
	 // $sqlquery = "SELECT survey_name_id,survey_id,client_id FROM `media_question_data` where survey_name_id='" . $survey_id . "' and status='0'";
	
	// $resultData = mysqli_query($conn,$sqlquery);
	// $result=mysqli_fetch_array($resultData);

	 // $client_id = $result['client_id'];
	
	// $survey_name_id = $result['survey_name_id'];
	//$clientId = "C" . $client_id;
    // downloadZipFile($s3Client, '91', '1087');
// }

// if (isset($_POST['download_file'])) {
    // downloadZipFile($s3Client, '91', '1087');
// }
 downloadZipFile($s3Client, '91', '1087');
?>
<!--<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>S-3 Bucket</title>
    <style type="text/css">
        .wrapper {
            width: 55%;
            padding: 34px;
            box-shadow: blue;
            border: 2px solid whitesmoke;
            background-color: whitesmoke;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form method="post" action="" enctype="multipart/form-data">
            <label><b>Download File:</b></label>
            <button type="submit" name="download_file" class="btn btn-primary">Download</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>-->
