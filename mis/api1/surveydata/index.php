<?php

header('Access-Control-Allow-Origin: *');

require_once "../config.php";
require_once "../mycrypt.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
	ini_set('memory_limit', '-1');
    $createTable = "CREATE TABLE IF NOT EXISTS survey_data_monitoring_encryption (
  survey_data_monitoring_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  survey_data_id varchar(20) DEFAULT NULL,
  survey_form_id int(11) DEFAULT NULL,
  survey_id varchar(30) NOT NULL,
  user_id int(11) NOT NULL,
  survey_data_json longtext NOT NULL,
  full_json longtext NOT NULL,
  cluster_code varchar(20) NOT NULL,
  survey_status varchar(20) NOT NULL COMMENT '1=>submitted,5=>Approved,4=>Rejected,3=>Terminate,6=>Re-submitted',
  supervisor_id int(11) DEFAULT '0',
  user_type varchar(20) NOT NULL,
  termination_reason text,
  created_on datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  survey_name varchar(100) NOT NULL,
  survey_name_id int(11) NOT NULL,
  client_id int(11) NOT NULL,
  latitude varchar(20) NOT NULL,
  longitude varchar(20) NOT NULL,
  para_data text,
  hint_record text,
  error_record text,
  description text NOT NULL,
  survey_data_json_coded longtext NOT NULL,
  language_id tinyint(2) NOT NULL,
  survey_data_json_export longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $CreateTable = mysqli_query($conn, $createTable);

	// synced data client 272, 273, 101, 171, 104, 137, 138, 175, 202, 203, 231, 232,247
    $getClients = "SELECT `id` FROM `clients` WHERE id='247'";
	//die;
    mysqli_set_charset($conn, 'utf8');
    $fetchClients = mysqli_query($conn, $getClients);

    while ($row = mysqli_fetch_array($fetchClients, MYSQLI_ASSOC)) {
        $data = Profile1($conn, $row['id']);
        echo json_encode($data);
        echo "\n";
    }

    $sourceTable = "survey_data_monitoring_encryption";
    $destinationTable = "survey_data_monitoring";

    $updateQuery = "UPDATE $destinationTable
                JOIN $sourceTable ON $destinationTable.survey_data_monitoring_id = $sourceTable.survey_data_monitoring_id
                SET $destinationTable.survey_data_id = $sourceTable.survey_data_id,
                    $destinationTable.survey_form_id = $sourceTable.survey_form_id,
                    $destinationTable.survey_id = $sourceTable.survey_id,
                    $destinationTable.user_id = $sourceTable.user_id,
                    $destinationTable.survey_data_json = $sourceTable.survey_data_json,
                    $destinationTable.full_json = $sourceTable.full_json,
                    $destinationTable.survey_data_json_coded = $sourceTable.survey_data_json_coded,
                    $destinationTable.survey_data_json_export = $sourceTable.survey_data_json_export
                ";

    mysqli_query($conn, $updateQuery);
    mysqli_close($conn);
    exit;

} else {
    http_response_code(405); // Method Not Allowed
    $response = ["status" => 0, "message" => "The call method not allowed"];
    echo json_encode($response);
    exit;
}
function Profile1($conn, $clientId = 1)
{
    if (!isset($finalenc) && $finalenc != $clientId) {
        $enc_key_temp = get_encryption_key(SUPER_ADMIN_KEY, SUPER_ADMIN_URL, $clientId);
        $finalenc = $clientId;
        $enc_key = $enc_key_temp;
    }
    if ($enc_key === 0) {
        return array("success" => "0", "message" => "Your client is not active", "client_id" => $clientId);

    }
    $offset = 0;
    $done = false;
    while (!$done) {
        $mcrypt = new EncryptionUtils($enc_key);
        $Usersql = "SELECT * FROM survey_data_monitoring  where client_id='" . $clientId . "' LIMIT " . $offset . ",4";
        $return_arr = array("sql" => $Usersql);
        mysqli_set_charset($conn, 'utf8');

        if ($fetch = mysqli_query($conn, $Usersql)) {
            if (($numRows = mysqli_num_rows($fetch)) && $numRows > 0) {

                $rows = array();
                while ($row = mysqli_fetch_array($fetch, MYSQLI_ASSOC)) {

                    $row['survey_data_monitoring_id'] = $row['survey_data_monitoring_id'];
                    $row['survey_id'] = $row['survey_id'];
                    $row['user_id'] = $row['user_id'];
                    if ($mcrypt->isJson($row['survey_data_json'])) {

                        $inser_sql = "INSERT INTO survey_data_monitoring_encryption (survey_data_monitoring_id, survey_data_id, survey_form_id, survey_id, user_id, survey_data_json, full_json, cluster_code, survey_status, supervisor_id, user_type, termination_reason, created_on, survey_name, survey_name_id, client_id, latitude, longitude, para_data, hint_record, error_record, description, survey_data_json_coded, language_id, survey_data_json_export) VALUES (" .
                        $row['survey_data_monitoring_id'] . ",'" .
                        $row['survey_data_id'] . "','" .
                        $row['survey_form_id'] . "','" .
                        $row['survey_id'] . "','" .
                        $row['user_id'] . "','" .
                        $mcrypt->encrypt($row['survey_data_json']) . "','" .
                        $mcrypt->encrypt($row['full_json']) . "','" .
                        $row['cluster_code'] . "','" .
                        $row['survey_status'] . "','" .
                        $row['supervisor_id'] . "','" .
                        $row['user_type'] . "','" .
                        $row['termination_reason'] . "','" .
                        $row['created_on'] . "','" .
                        $row['survey_name'] . "','" .
                        $row['survey_name_id'] . "','" .
                        $row['client_id'] . "','" .
                        $row['latitude'] . "','" .
                        $row['longitude'] . "','" .
                        $row['para_data'] . "','" .
                        $row['hint_record'] . "','" .
                        $row['error_record'] . "','" .
                        $row['description'] . "','" .
                        $mcrypt->encrypt($row['survey_data_json_coded']) . "','" .
                        $row['language_id'] . "','" .
                        $mcrypt->encrypt($row['survey_data_json_export']) . "')";
                        $insertquery = mysqli_query($conn, $inser_sql);
                        $rows[] = array('id' => $row['survey_data_monitoring_id'], 'offset' => $offset);
                    }
                }

                $offset += 4;

            } else {
                $done = true;
            }
        }
    }
    return $rows;
}
