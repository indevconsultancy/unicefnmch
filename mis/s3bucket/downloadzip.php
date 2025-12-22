<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use ZipArchive;

// Amazon S3 API credentials
$s3Client = new S3Client([
    'version' => 'latest',
    'scheme' => 'http',
    'region' => 'ap-south-1',
    'credentials' => [
        'key' => 'AKIAZLY6QIYPRMM6SEOJ',
        'secret' => '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'
    ],
]);

if (isset($_REQUEST['download_file'])) {
    $client_id = '90';
    $clientID = "C" . $client_id;
    $survey_id = '701';
    $bucket = 'mquaddata';
    $folderKey = 'khushboo/' . $clientID . '/' . $survey_id . '/';

    try {
        // List objects in the specified folder
        $result = $s3Client->listObjectsV2([
            'Bucket' => $bucket,
            'Prefix' => $folderKey
        ]);

        if (empty($result['Contents'])) {
            echo 'No files found in the specified S3 folder.';
            exit;
        }

        // Temporary location to store downloaded files
        $tmpDir = sys_get_temp_dir() . '/' . uniqid('s3_', true);
        if (!mkdir($tmpDir, 0777, true) && !is_dir($tmpDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $tmpDir));
        }

        echo "<pre>";
        print_r($result['Contents']);
        echo "</pre>";

        // Download each object to the temporary location
        foreach ($result['Contents'] as $object) {
            $key = $object['Key'];
            $filePath = $tmpDir . '/' . basename($key);
            echo "Downloading: $key to $filePath<br>";
            $s3Client->getObject([
                'Bucket' => $bucket,
                'Key' => $key,
                'SaveAs' => $filePath
            ]);

            // Ensure the file was downloaded
            if (!file_exists($filePath)) {
                throw new RuntimeException(sprintf('Failed to download file: %s', $filePath));
            } else {
                echo "File downloaded successfully: $filePath<br>";
            }
        }

        // Create ZIP archive
        $zip = new ZipArchive();
        $zipFile = $tmpDir . '.zip';
        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
            $files = glob($tmpDir . '/*');
            echo "<pre>";
            print_r($files);
            echo "</pre>";

            foreach ($files as $file) {
                echo "Adding file to zip: $file<br>";
                if (!$zip->addFile($file, basename($file))) {
                    echo "Failed to add file to zip: $file<br>";
                }
            }
            $zip->close();

            // Verify the ZIP file
            if (!file_exists($zipFile) || filesize($zipFile) == 0) {
                throw new RuntimeException('Failed to create ZIP file or ZIP file is empty');
            }

            // Serve the ZIP file for download
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="files.zip"');
            header('Content-Length: ' . filesize($zipFile));
            flush(); // Flush system output buffer
            readfile($zipFile);

            // Clean up temporary files
            unlink($zipFile);
            array_map('unlink', glob("$tmpDir/*.*"));
            rmdir($tmpDir);
        } else {
            throw new RuntimeException('Failed to create ZIP archive');
        }
    } catch (S3Exception $e) {
        echo 'S3 Error: ' . $e->getMessage();
    } catch (Exception $e) {
        echo 'General Error: ' . $e->getMessage();
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
            <h4 class="mt-2">S3 bucket file Downlaod 1</h4>
            <div class="col-md-12">
                <form method="post" action="" enctype="multipart/form-data">
					<label><b>Download File:</b></label>
					<button type="submit" name="download_file" value="submit" class="btn btn-primary">Downlaod</button>
                </form>
            </div><br>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>