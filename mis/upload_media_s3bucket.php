<?php
include_once('includes/config.php');
require 's3bucket/s3_bucket_config.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

$survey_id = '1411';

$surveySql = mysqli_query($conn, "SELECT id, client_id FROM survey WHERE id='$survey_id'");
if (!$surveySql) {
    die("Database query failed: " . mysqli_error($conn));
}
$dataSurvey = mysqli_fetch_array($surveySql);
if (!$dataSurvey) {
    die("No survey found with id: " . $survey_id);
}

$clientUniqueId = "C" . $dataSurvey['client_id'];
$surveyUniqueId = $dataSurvey['id'];
$bucket = 'mquaddata';

function url_exists($url) {
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}

$surveymediaSql = mysqli_query($conn, "SELECT survey_name_id, media_file FROM media_question_data WHERE survey_name_id='$survey_id'");
if (!$surveymediaSql) {
    die("Database query failed: " . mysqli_error($conn));
}

while ($dataMedia = mysqli_fetch_array($surveymediaSql)) {
    $media_file = $dataMedia['media_file'];
    $file_url = 'https://mquad.org/mis/media_survey/' . $clientUniqueId . '/' . $surveyUniqueId . '/' . $media_file;

    try {
        if (url_exists($file_url)) {
           echo $s3Key = 'media_survey/' . $clientUniqueId . '/' . $surveyUniqueId . '/' . $media_file;
           // $file_handle = fopen($file_url, 'r');
            //if ($file_handle) {
				//
				
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key'    => $s3Key,
                    'Body'   => fopen($file_url, 'r'),
                ]);
				// echo "ssss";
				// print_r($result);
                if (!empty($result['ObjectURL'])) {
                    echo "File " . basename($file_url) . " successfully uploaded to " . $result['ObjectURL'] . "<br>";
                } else {
                    echo "Upload failed for file " . basename($file_url) . ". S3 Object URL not found.<br>";
                }

                fclose($file_handle);
            // } else {
                // echo "Failed to open file: " . $file_url . "<br>";
            // }
        } else {
            echo "File not found: " . $file_url . "<br>";
        }
    } catch (S3Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}
?>
