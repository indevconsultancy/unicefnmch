<?php
include_once('../includes/config.php');
include_once('../includes/functions1.php');

require '../s3bucket/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;



if (isset($_POST['sync_datafile_id']) && $_POST['sync_datafile_id'] != "") {

    $sync_datafile_id = mysqli_real_escape_string($conn, $_POST['sync_datafile_id']);
    $datafile = mysqli_query($conn, "SELECT dataset_id, user_id FROM project_dataformat_file WHERE dataformat_file_id ='$sync_datafile_id'");
    $sync_dataset_id = mysqli_fetch_assoc($datafile);
    $getuserdata = mysqli_query($conn, "SELECT user_id, name, email FROM `users` WHERE user_id= '" . $sync_dataset_id['user_id'] . "'");
    $user_data = mysqli_fetch_array($getuserdata);

    $getdatasql = mysqli_query($conn, "SELECT * FROM project_datasets WHERE status='1' AND dataset_id='" . $sync_dataset_id['dataset_id'] . "'");
    $totaldata = mysqli_num_rows($getdatasql);


    if ($totaldata > 0) {
        $syncdata = mysqli_fetch_assoc($getdatasql);
        $keywords_id = $syncdata['keywords_id'];
        $institution_id = $syncdata['institution_id'];
        $authors_id = $syncdata['authors_id'];
        $institution_str = getKeyWordNames($conn, 'institution', 'institution_id', $institution_id, 'institution_name');
        $authors_str = getKeyWordNames($conn, 'authors', 'authors_id', $authors_id, 'author_name');
        $keyword_str = getKeyWordNames($conn, 'keywords', 'keywords_id', $keywords_id, 'keyword_name');

        $keyword = explode(',', $keyword_str);
        $institution = explode(',', $institution_str);
        $authors = explode(',', $authors_str);

        // print_r($authors);

        // die;
        $data = array(
            "full_name" => $user_data['name'],
            "email" => $user_data['email'],
            // "country_id_user" => $user_data['country_id'],
            // "state_id_user" => $user_data['state_id'],
            // "organization_name" => $user_data['organization_name'],
            "profile_image" => $user_data['profile_image'],
            "title" => $syncdata['title'],
            "description" => $syncdata['description'],
            "type_of_study_id" => $syncdata['type_of_study_id'],
            "type_of_study_other" => $syncdata['type_of_study_other'],
            "thematic_area_id" =>   $syncdata['thematic_area_id'],
            "keywords_id" =>  $keyword,
            "institution_id" =>   $institution,
            "authors_id" =>  $authors,
            "contact_person_name" => $syncdata['contact_person_name'],
            "contact_person_email" => $syncdata['contact_person_email'],
            "from_date" => $syncdata['from_date'],
            "to_date" => $syncdata['to_date'],
            "data_type" => $syncdata['data_type'],
            "related_publication" => $syncdata['related_publication'],
            "use_of_term" => $syncdata['use_of_term'],
            "country_id" => $syncdata['country_id'],
            "state_id" => $syncdata['state_id'],
            "request_admin_status" => "1",
            "uploaded_from" => "MQUAD"


        );

        $jsonData = json_encode($data);
        // die;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dataverse.mquad.org/mis/api/add-dataset.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: PHPSESSID=jooule32v1sgoo7dl6f28h7d0s'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            $responseData = json_decode($response, true);

            // print_r($responseData);
            // die;
            if ($responseData && isset($responseData['last_inserted_id'])) {
                $lastInsertedId = $responseData['last_inserted_id'];
                $user_id = $responseData['user_id'];
                $password = $responseData['password'];

                $datafile = mysqli_query($conn, "SELECT * FROM project_dataformat_file WHERE dataformat_file_id ='$sync_datafile_id'");
                $row = mysqli_fetch_assoc($datafile);

                $foldernam = "D" . $sync_dataset_id['dataset_id'];
                $filePath = "../upload_data_file/dataset/$foldernam/" . $row['dataformat_fie'];

                // if (file_exists($filePath)) {
                //     $flarnam = "D" . $lastInsertedId;
                //     $newPath = "../../dataverse/upload/dataset/$flarnam/dataset/" . $row['dataformat_fie'];
                //     $fileContent = file_get_contents($filePath);
                //     if (file_put_contents($newPath, $fileContent)) {
                //         // File copied successfully
                //     } else {
                //         // Failed to copy file
                //     }
                // }

                $dataformat_fie = $row['dataformat_fie'];

                try {
                    $s3Client = new S3Client([
                        'version' => 'latest',
                        'scheme' => 'http',
                        'region' => 'ap-south-1',
                        'credentials' => [
                            'key' => 'AKIAZLY6QIYPRMM6SEOJ',
                            'secret' => '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'
                        ],
                    ]);

                    $bucketName = 'mquaddata';
                    $keyName = "mquaddataverse/$dataformat_fie";

                    $result = $s3Client->putObject([
                        'Bucket' => $bucketName,
                        'Key'    => $keyName,
                        'Body' => $filePath,
                    ]);

                    if ($result['@metadata']['statusCode'] == 200) {
                        // echo "File moved to S3 and deleted locally.";
                    } else {
                        // echo "Failed to upload the file to S3.";
                    }
                } catch (S3Exception $e) {
                    // echo "Error uploading to S3: " . $e->getMessage();
                }


                $data = array(
                    // "dataset_name" => $row['dataset_name'],
                    // "dataformat_fie" => $row['dataformat_fie'],
                    // "data_access" => $row['data_access'],
                    // "description" => $row['description'],
                    // "dataformat_name" => $row['dataformat_name'],
                    // "dataset_id" => $lastInsertedId,
                    // "user_id" => $user_id

                    "dataset_name" => $row['dataset_name'],
                    "dataformat_fie" => $row['dataformat_fie'],
                    "data_access" => $row['data_access'],
                    "description" => $row['description'],
                    "dataformat_name" => $row['dataformat_name'],
                    "use_of_term" => $row['use_of_term'],
                    "dataset_id" => $lastInsertedId,
                    "user_id" => $user_id,
                    "uploaded_from" => "MQUAD"

                );

                $jsonDataFiles = json_encode($data);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://dataverse.mquad.org/mis/api/add-dataset-file.php',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $jsonDataFiles,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                ));

                $responseFiles = curl_exec($curl);
                curl_close($curl);

                if ($responseFiles) {
                    $updtesync = mysqli_query($conn, "UPDATE project_dataformat_file SET sync_status='1' WHERE dataformat_file_id='$sync_datafile_id'");
                    if ($updtesync) {
                        $result = array("status" => 1, "message" => "Sync successful", "last_inserted_id" => $lastInsertedId);
                    } else {
                        $result = array("status" => 0, "message" => "Failed to update sync status");
                    }
                } else {
                    $result = array("status" => 0, "message" => "Error syncing dataset files");
                }
            } else {
                $result = array("status" => 0, "message" => "Last inserted ID not found in response");
            }
        } else {
            $result = array("status" => 0, "message" => "Error syncing dataset");
        }

        echo json_encode($result);
    } else {
        echo json_encode(array("status" => 0, "message" => "No data found for the provided dataset ID"));
    }
} else {
    echo json_encode(array("status" => 0, "message" => "Invalid dataset ID"));
}
