<?php

include('includes/config_s3.php');
require 's3bucket/s3_bucket_config.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

ini_set('memory_limit', '-1');

if (isset($_REQUEST['survey_id']) && $_REQUEST['survey_id'] != '') {
    $survey_id = mysqli_real_escape_string($conn, $_REQUEST['survey_id']);
    $sqlquerySurvey = "SELECT id,survey_name FROM `survey` where id='" . $survey_id . "'";
    $resultSurvey = mysqli_query($conn, $sqlquerySurvey);
    $datasurvey = mysqli_fetch_array($resultSurvey);
    $survey_name = $datasurvey['survey_name'];

    $sqlquery = "SELECT media_question_data_id, survey_id, survey_name_id, survey_data_monitoring_id, user_id, client_id, field_name, media_file 
                 FROM `media_question_data` 
                 WHERE survey_name_id='" . $survey_id . "' AND status='0'";

    $resultData = mysqli_query($conn, $sqlquery);

    // Create a temporary file to store the zip
    $zip = new ZipArchive();
    $uniqueID = uniqid('s3_', true);
    $zipFileName = '/tmp/' . $uniqueID . '.zip';

    if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
        throw new Exception('Cannot create zip file');
    }

    while ($result = mysqli_fetch_array($resultData)) {
        $client_id = $result['client_id'];
        $survey_name_id = $result['survey_name_id'];
        $media_file = $result['media_file'];

        $clientID = "C" . $client_id;
        $bucket = 'mquaddata';
        $folderKey = 'media_survey/' . $clientID . '/' . $survey_id . '/' . $media_file;

        try {
            // List all objects in the folder
            $objects = $s3Client->listObjects([
                'Bucket' => $bucket,
                'Prefix' => $folderKey,
            ]);

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
                $s3Result = $s3Client->getObject([
                    'Bucket' => $bucket,
                    'Key' => $fileKey
                ]);

                // Add file to zip archive in the corresponding folder
                $zip->addFromString($folder . $fileName, $s3Result['Body']);
            }

        } catch (AwsException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    $zip->close();

    // Serve the zip file for download after the loop
    $filename = "media_zip/survey_" . $survey_name . ".zip";
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($zipFileName));

    readfile($zipFileName);
    // Delete the temporary zip file
    unlink($zipFileName);

    exit;
}
?>
