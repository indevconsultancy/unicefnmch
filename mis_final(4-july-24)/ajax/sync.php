<?php
include_once('../includes/config.php');
// include_once('../includes/functions.php');

if (isset($_POST['sync_datafile_id']) && $_POST['sync_datafile_id'] != "") {

    $sync_datafile_id = mysqli_real_escape_string($conn, $_POST['sync_datafile_id']);
    $datafile = mysqli_query($conn, "SELECT dataset_id, user_id FROM project_dataformat_file WHERE dataformat_file_id ='$sync_datafile_id'");
    $sync_dataset_id = mysqli_fetch_assoc($datafile);

    $getuserdata = mysqli_query($conn, "SELECT user_id, name, email FROM `users` WHERE user_id= '" . $sync_dataset_id['user_id'] . "'");
    $user_data = mysqli_fetch_assoc($getuserdata);

    $getdatasql = mysqli_query($conn, "SELECT * FROM project_datasets WHERE status='1' AND dataset_id='" . $sync_dataset_id['dataset_id'] . "'");
    $totaldata = mysqli_num_rows($getdatasql);


    if ($totaldata > 0) {
        $syncdata = mysqli_fetch_assoc($getdatasql);

        $data = array(
            "fullname" => $user_data['name'],
            "email" => $user_data['email'],
            "title" => $syncdata['title'],
            "description" => $syncdata['description'],
            "type_of_study_id" => $syncdata['type_of_study_id'],
            "type_of_study_other" => $syncdata['type_of_study_other'],
            "thematic_area_id" => $syncdata['thematic_area_id'],
            "keywords_id" => $syncdata['keywords_id'],
            "institution_id" => $syncdata['institution_id'],
            "authors_id" => $syncdata['authors_id'],
            "contact_person_name" => $syncdata['contact_person_name'],
            "contact_person_email" => $syncdata['contact_person_email'],
            "from_date" => $syncdata['from_date'],
            "to_date" => $syncdata['to_date'],
            "data_type" => $syncdata['data_type'],
            "related_publication" => $syncdata['related_publication'],
            "use_of_term" => $syncdata['use_of_term'],
            "country_id" => $syncdata['country_id'],
            "state_id" => $syncdata['state_id']
        );

        $jsonData = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mquad.org/dataverse/mis/api/add-dataset.php',
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

                // if ($user_id != "") {
                //     $curl = curl_init();

                //     curl_setopt_array($curl, array(
                //         CURLOPT_URL => 'https://mquad.org/dataverse/mis/api/user_deaitls_fatch.php',
                //         CURLOPT_RETURNTRANSFER => true,
                //         CURLOPT_ENCODING => '',
                //         CURLOPT_MAXREDIRS => 10,
                //         CURLOPT_TIMEOUT => 0,
                //         CURLOPT_FOLLOWLOCATION => true,
                //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //         CURLOPT_CUSTOMREQUEST => 'POST', // Changed to POST
                //         CURLOPT_POSTFIELDS => json_encode(array("user_id" => $user_id)),
                //         CURLOPT_HTTPHEADER => array(
                //             'Content-Type: application/json',
                //             'Cookie: PHPSESSID=0i4kk8q8simpusfc0o1tghfvq5'
                //         ),
                //     ));

                //     $response = curl_exec($curl);
                //     curl_close($curl);

                //     $response_data = json_decode($response, true);

                //     if ($response_data) {

                //         $full_name = $response_data['full_name'];
                //         $emaile = $response_data['email'];
                //         $country_id = $response_data['country_id'];
                //         $organization_name = $response_data['organization_name'];
                //         $profile_image = $response_data['profile_image'];
                //         $state_id = $response_data['state_id'];
                //         if ($full_name != "" && $emaile != "") {

                //             // $creator_email = $emaile;
                //             // $full_name = $full_name;

                //             // $dataset_message = '<html>
                //             // <head>
                //             //     <style>
                //             //         .email-body {
                //             //             background-color: #f4f4f4;
                //             //             padding: 20px;
                //             //         }
                //             //         .email-body p {
                //             //             font-family: Arial, sans-serif;
                //             //             font-size: 14px;
                //             //             color: #333333;
                //             //         }
                //             //         .email-body a {
                //             //             color: #1a73e8;
                //             //             text-decoration: none;
                //             //         }
                //             //     </style>
                //             // </head>
                //             // <body>
                //             //     <div class="email-body">
                //             //         <p>Dear ' . $full_name . ',</p>
                //             //         <p>Greetings!</p>
                //             //         <p>Your user account has been successfully created on the MQUAD Datavarse.</p>
                //             //         <p>Here are your login details:</p>
                //             //         <p><strong>Email ID:</strong> ' . $email . '</p>
                //             //         <p><strong>Password:</strong> ' . $randomPassword . '</p>
                //             //         <p>Please visit the following link to log in and check your profile:</p>
                //             //         <p><a href="https://mquad.org/dataverse/mis/">MQUAD Database Login</a></p>
                //             //     </div>
                //             // </body>
                //             // </html>';


                //             // $dataset_subject = base64_encode("New User Created in Mquad Dataverse");
                //             // $dataset_message_encoded = base64_encode($dataset_message);

                //             // send_mail_function($creator_email, $dataset_message_encoded, $dataset_subject);
                //         }
                //     } else {
                //         echo "Failed to fetch user details or user not found.";
                //     }
                // }


                $datafile = mysqli_query($conn, "SELECT * FROM project_dataformat_file WHERE dataformat_file_id ='$sync_datafile_id'");
                $row = mysqli_fetch_assoc($datafile);

                $foldernam = "D" . $sync_dataset_id['dataset_id'];
                $filePath = "../upload_data_file/dataset/$foldernam/" . $row['dataformat_fie'];

                if (file_exists($filePath)) {
                    $flarnam = "D" . $lastInsertedId;
                    $newPath = "../../dataverse/upload/dataset/$flarnam/dataset/" . $row['dataformat_fie'];
                    $fileContent = file_get_contents($filePath);
                    if (file_put_contents($newPath, $fileContent)) {
                        // File copied successfully
                    } else {
                        // Failed to copy file
                    }
                }

                $data = array(
                    "dataset_name" => $row['dataset_name'],
                    "dataformat_fie" => $row['dataformat_fie'],
                    "data_access" => $row['data_access'],
                    "description" => $row['description'],
                    "dataformat_name" => $row['dataformat_name'],
                    "dataset_id" => $lastInsertedId,
                    "user_id" => $user_id

                );

                $jsonDataFiles = json_encode($data);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://mquad.org/dataverse/mis/api/add-dataset-file.php',
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
