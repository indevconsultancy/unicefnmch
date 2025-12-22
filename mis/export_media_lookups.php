<?php

include('includes/config_s3.php');
require 's3bucket/s3_bucket_config.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

ini_set('memory_limit', '-1');

if (isset($_REQUEST['survey_ID']) && $_REQUEST['survey_ID'] != '') {
    $survey_id = mysqli_real_escape_string($conn, $_REQUEST['survey_ID']);

    $sqlquerySurvey = "SELECT id,survey_name,client_id FROM `survey` where id='" . $survey_id . "'";
    $resultSurvey = mysqli_query($conn, $sqlquerySurvey);
    $datasurvey = mysqli_fetch_array($resultSurvey);
    $survey_name = $datasurvey['survey_name'];
    $client_id = $datasurvey['client_id'];
    $clientID = "C" . $client_id;
    $bucket = 'mquaddata';
    $folderKey = 'media_lookups/' . $clientID . '/' . $survey_id . '/';

    ///////////////////////////////
    //echo "SELECT COUNT(media_file) AS tot_media FROM questions_language WHERE survey_id='" . $survey_id . "' AND media_file!=''";
    $sqlmedia = mysqli_query($conn, "SELECT COUNT(media_file) AS tot_media FROM questions_language WHERE survey_id='" . $survey_id . "' AND media_file!=''");
    $getMedia = mysqli_fetch_array($sqlmedia);
     $tot_media = $getMedia['tot_media'];

    $clientId = "C" . $client_id;
    //$location = "mis/medialookups/" . $clientId . "/" . $survey_id . "/";
    $bucket = 'mquaddata';
    if ($tot_media > 0) {
      //  echo "djdjdjfjgS";
        //die();
        try {
            // List all objects in the folder
            $objects = $s3Client->listObjects([
                'Bucket' => $bucket,
                'Prefix' => $folderKey,
            ]);

            // Create a temporary file to store the zip
            $zip = new ZipArchive();
            $zipFileName = '/tmp/' . uniqid('s3_', true) . '.zip';

            if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
                throw new Exception('Cannot create zip file');
            }
            echo $fileKey;
            foreach ($objects['Contents'] as $object) {
                $fileKey = $object['Key'];
                $fileName = basename($fileKey);

                // Download file content
                $result = $s3Client->getObject([
                    'Bucket' => $bucket,
                    'Key' => $fileKey
                ]);

                // Add file to zip archive
                $zip->addFromString($fileName, $result['Body']);
            }

            $zip->close();

            // Serve the zip file for download
            $filename = "media_zip/media_" . $survey_name . ".zip";
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($zipFileName));
            //header('Content-Type: application/json');

            readfile($zipFileName);
            // Delete the temporary zip file
            unlink($zipFileName);

            echo json_encode(['status' => 1]);  // Example JSON response
            exit;
        } catch (AwsException $e) {
            echo 'Error: ' . $e->getMessage();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        $existingFiles = $s3Client->listObjectsV2([
            'Bucket' => $bucket,
            'Prefix' => 'media_lookups/' . $clientId . '/' . $survey_id . '/',
        ]);
        if (!isset($existingFiles['Contents']) || empty($existingFiles['Contents'])) {
            echo json_encode(["status" => 0, "total" => $tot_media, "message" => "Media Files are not available!"]);
            exit;
        }
    }
}
