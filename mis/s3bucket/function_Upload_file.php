<?php
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

function fileDisplay($s3Client,$mediaKey){
	$cmd = $s3Client->getCommand('GetObject', [
		'Bucket' => 'mquaddata',
		
		'Key'    => $mediaKey //foldername/imagename
	]);
	$request = $s3Client->createPresignedRequest($cmd, '+20 seconds');
	return $signedUrl = (string)$request->getUri();
}

?>