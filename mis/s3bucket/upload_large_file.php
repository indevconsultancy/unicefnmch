<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

// Amazon S3 API credentials
$s3Client = new S3Client([
    'version' => 'latest',
    'scheme' => 'https', // Use HTTPS for security
    'region' => 'ap-south-1',
    'credentials' => [
        'key' => 'AKIAZLY6QIYPRMM6SEOJ',
        'secret' => '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'
    ],
]);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['submit'])) {
    $filename = $_FILES['userfile']['name'];
    $tmp_name = $_FILES['userfile']['tmp_name'];

    if ($filename != "" && $tmp_name != "") {
        $bucket = 'mquaddata';
        $s3Key = 'khushboo/' . time() . '-' . $filename; // Unique key for each upload

        // Check if temporary file exists and is readable
        if (file_exists($tmp_name) && is_readable($tmp_name)) {
            try {
                $uploader = new MultipartUploader($s3Client, $tmp_name, [
                    'bucket' => $bucket,
                    'key'    => $s3Key,
                    'partSize' => 10 * 1024 * 1024 // 10MB per part
                ]);

                $result = $uploader->upload();
                echo "<pre>";
                print_r($result);
                echo "File uploaded successfully: " . $result['ObjectURL'];
            } catch (MultipartUploadException $e) {
                echo "Upload failed: " . $e->getMessage();
            } catch (AwsException $e) {
                echo "AWS error: " . $e->getMessage();
            } catch (Exception $e) {
                echo "General error: " . $e->getMessage();
            }
        } else {
            echo "Temporary file is not accessible.";
        }
    } else {
        echo "Please select a file!";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload to S3</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="userfile" required>
        <input type="submit" name="submit" value="Upload">
    </form>
</body>
</html>
