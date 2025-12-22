<?php
require '../includes/config.php';
require 's3_bucket_config.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use ZipArchive;

function extractAndUpload($bucket, $key, $destination) {
    global $s3Client;

    // Download the ZIP file from S3
    $result = $s3Client->getObject([
        'Bucket' => $bucket,
        'Key'    => $key,
    ]);

    $zipFilePath = '/tmp/' . basename($key);
    file_put_contents($zipFilePath, $result['Body']);

    // Generate a unique temporary directory for extraction
    $extractPath = '/tmp/extracted_' . uniqid();
    mkdir($extractPath, 0777, true);

    // Extract the ZIP file
    $zip = new ZipArchive();
    if ($zip->open($zipFilePath) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();

        // Upload extracted files back to S3
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($extractPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($extractPath) + 1);
                $destinationKey = $destination . '/' . $relativePath;

                try {
                    $result = $s3Client->putObject([
                        'Bucket' => $bucket,
                        'Key'    => $destinationKey,
                        'SourceFile' => $filePath,
                    ]);
                  //  echo "File uploaded successfully: " . $result['ObjectURL'] . "\n";
                } catch (S3Exception $e) {
                    echo $e->getMessage() . "\n";
                    return false;
                }
            }
        }

        // Remove the ZIP file from S3
        try {
            $result = $s3Client->deleteObject([
                'Bucket' => $bucket,
                'Key'    => $key,
            ]);
           // echo "Zip file deleted successfully from S3.\n";
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
            return false;
        }

        // Clean up temporary files
        array_map('unlink', glob("$extractPath/*.*"));
        rmdir($extractPath);

        return true;
    } else {
        echo "Failed to open the zip file.\n";
        return false;
    }
}

// If file upload form is submitted 
if(isset($_POST['submit'])){
    
    $filename = $_FILES['userfile']['name'];
    $tmp_name = $_FILES['userfile']['tmp_name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
	$survey_id = '1754';
    $clientId = '91';
    $surveyUniqueId = $survey_id;

    $clientUniqueId = "C" . $clientId;
    $file_name = $surveyUniqueId . "." . $ext;

    if ($survey_id != "" && $file_name != "") {
        // S3 Key (path in the bucket)
        $bucket = 'mquaddata';
        $s3Key = 'media_survey/' . $clientUniqueId . '/' . $file_name;
        $destination = 'media_survey/' . $clientUniqueId . '/' . pathinfo($file_name, PATHINFO_FILENAME);

        // Check if the folder exists
        $existingFiles = $s3Client->listObjectsV2([
            'Bucket' => $bucket,
            'Prefix' => 'media_survey/' . $clientUniqueId . '/' . $surveyUniqueId . '/',
        ]);

        // Delete existing files in the folder
        if (isset($existingFiles['Contents'])) {
            foreach ($existingFiles['Contents'] as $content) {
                try {
                    $s3Client->deleteObject([
                        'Bucket' => $bucket,
                        'Key'    => $content['Key'],
                    ]);
                    // echo "Existing file deleted: " . $content['Key'] . "\n";
                } catch (S3Exception $e) {
                    echo $e->getMessage() . "\n";
                }
            }
        }

        // Upload the zip file to S3
        try {
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key'    => $s3Key,
                'SourceFile' => $tmp_name,
            ]);

            // Extract and upload files
            $finalstatus = extractAndUpload($bucket, $s3Key, $destination);

            if ($finalstatus) {
                echo "Media File Uploaded Successfully: " . $result['ObjectURL'] . "";
            } else {
                echo "Something went wrong!!";
            }
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
        }

    } else {
        echo "Please select the file!!";
    }
}
?>



<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
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
    <div class="container">

        <div class="wrapper mt-5">
            <h4 class="mt-2">S3 bucket file upload 1</h4>

            <div class="col-md-12">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><b>Name:</b></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label><b>Select File:</b></label>
                        <input type="file" name="userfile" class="form-control" accept=".zip" required>
                    </div>
                    <div class="form-group float-end mt-2">
                        <input type="submit" class="btn btn-primary" name="submit" value="Upload">
                    </div>
                </form>
            </div><br>

        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
