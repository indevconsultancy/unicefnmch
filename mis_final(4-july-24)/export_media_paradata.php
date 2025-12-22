<?php
include('includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('memory_limit', '256M');
function createZip($zip, $dir)
{
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                // If file
                if (is_file($dir . $file)) {
                    if ($file != '' && $file != '.' && $file != '..') {
                        $zip->addFile($dir . $file);
                    }
                } else {
                    // If directory
                    if (is_dir($dir . $file)) {
                        if ($file != '' && $file != '.' && $file != '..') {
                            // Add empty directory
                            $zip->addEmptyDir($dir . $file);
                            $folder = $dir . $file . '/';
                            // Read data of the folder
                            createZip($zip, $folder);
                        }
                    }
                }
            }
            closedir($dh);
        }
    }
}
$survey_id = $_REQUEST['survey_id']; //'1356'; 
$sqlquery = "SELECT survey_document_id,survey_id,survey_name_id,survey_data_monitoring_id,user_id,client_id,field_name,media_file FROM `survey_document` where survey_name_id='".$survey_id."' and status='0'";
$result = mysqli_query($conn, $sqlquery);

// Files path
$survey_name=getone($conn,'survey','survey_name','id',$survey_id);
$zipFileName = "media_zip/paradata_$survey_name.zip";

if (mysqli_num_rows($result) > 0) {
   
	$zip = new ZipArchive();
	if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
		exit("Cannot open <$zipFileName>\n");
	}
	
    $audioFolder = 'audio/';
   while ($row = mysqli_fetch_array($result)) {
		$media_file = $row['media_file'];
		$client_id = $row['client_id'];
		$clientId = "C" . $client_id;
		$fileUrl = base_url() . "media_survey/" . $clientId . '/' . $survey_id . '/' . $media_file;
		
		// Check file type based on extension
		$fileExtension = strtolower(pathinfo($media_file, PATHINFO_EXTENSION));

		// Define directories based on file type
		$directories = [
			'mp3' => 'audio',
		];

		// Download file content
		$fileContent = @file_get_contents($fileUrl);  // Suppress errors

		if ($fileContent !== false && array_key_exists($fileExtension, $directories)) {
			// Add file to the appropriate category in the ZIP archive
			$zip->addFromString($directories[$fileExtension] . '/' . $media_file, $fileContent);
			//$zip->addFromString($media_file, $fileContent);
		} else {
			// Print error details or skip unknown file types
			$lastError = error_get_last();
			echo"<script>alert('Failed to download or skipped unknown file type: $fileUrl. Error: " . $lastError['message'] . "')</script>";
			echo"<script>window.location.href='export_data.php?survey_id=$survey_id&search='</script>";
		}
	}
	
	$zip->close();

    // Check if audio, video, and image folders exist
    if (file_exists($audioFolder)) {
        // Remove other folders
        // Assuming there are other folders in the 'media_zip' directory
        foreach (glob('media_zip/*') as $file) {
            if (is_dir($file) && $file != $audioFolder) {
                // Remove the folder and its contents
                array_map('unlink', glob("$file/*.*"));
                rmdir($file);
            }
        }
    }

    // Send headers to force download the zip file
    if (file_exists($zipFileName)) {
		
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$zipFileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        // Read the file
        readfile($zipFileName);
		 if (file_exists($zipFileName)) {
			unlink($zipFileName);
		} 
        exit;
    } else {
        echo"<script>alert('Failed to create the ZIP file.')</script>";
		echo"<script>window.location.href='export_data.php?survey_id=$survey_id&search='</script>";
    }
} else {
	echo"<script>alert('No media files found for the specified survey.')</script>";
	echo"<script>window.location.href='export_data.php?survey_id=$survey_id&search='</script>";
}
?>
