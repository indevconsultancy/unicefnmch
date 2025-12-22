<?php 
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

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

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

function downloadZipFile($s3Client,$client_id,$survey_id){
	 // $client_id = '91';
    $clientID = "C" . $client_id;
    // $survey_id = '2027';
    $bucket = 'mquaddata';
    $folderKey = 'media_lookups/' . $clientID . '/' . $survey_id . '/';

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
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="files.zip"');
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

if (isset($_POST['download_file'])) {
   downloadZipFile($s3Client,'91','2027');
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
    <div class="wrapper">
        <form method="post" action="" enctype="multipart/form-data">
            <label><b>Download File:</b></label>
            <button type="submit" name="download_file" class="btn btn-primary">Download</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
