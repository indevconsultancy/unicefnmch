<?php
require_once "../config.php";
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$sql = "SELECT * FROM trigger_events WHERE uploaded = 0";
$result =  mysqli_query($conn, $sql);




if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventId = $row['id'];

        if ($row['changed_status'] != 3) {
            $sql1 = "SELECT * FROM trigger_events WHERE uploaded = 0 AND changed_status != 3 AND record_id =" . $row['record_id'] . "  ORDER BY ID DESC LIMIT 1";
            $result1 =  mysqli_query($conn, $sql);
            $user1 = mysqli_fetch_object($result1);
            if ($user1->changed_status == 0) {
                checkUpdate($user1->record_id, $API_KEY, $API_URL, $conn);
                $updateSql = "UPDATE trigger_events SET uploaded = 1 WHERE id != " . $eventId . " AND record_id = " . $row['record_id'];
                mysqli_query($conn, $updateSql);
            }

            // updateResource($row, $API_KEY, $API_URL,$conn);
        }

        updateResource($row, $API_KEY, $API_URL, $conn);

        $logMessage = json_encode($row) . PHP_EOL;
        file_put_contents('changes.log', $logMessage, FILE_APPEND);
        $updateSql = "UPDATE trigger_events SET uploaded = 1 WHERE id = $eventId";
        $dataUpdate= mysqli_query($conn, $updateSql);
		if($dataUpdate){
		   echo "success";
		}
    }
}

mysqli_close($conn);

function updateResource($row, $API_KEY, $API_URL, $con)
{
    $res = $row['table_name'];
    $id = $row['record_id'];
    $type = $row['action'];
    $urlType = "delete";
    $method = "DELETE";
    if ($type == 'UPDATE') {
        $urlType = "update";
        $method = "POST";
    } elseif ($type == 'INSERT') {
        $urlType = "insert";
        $method = "POST";
    }
    if($method != "DELETE"){
    $data = getData($con, $res, $id, $type);

    $d = http_build_query($data);
     $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                "X-Hamc: " . $API_KEY . "\r\n" .
                "Content-Length: " . strlen($d) . "\r\n",
            'method' => $method,
            'content' => $d,
        ],
    ];
    } else {
         $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n" .
                "X-Hamc: " . $API_KEY . "\r\n",
            'method' => $method,
        ],
    ];
    }
    $apiUrl = $API_URL . '/api/' . $urlType . '/' . $res . '/' . $id;
   


    $context = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);
  
    if ($result === false) {
        return;
    }
}

function checkUpdate($uid, $API_KEY, $API_URL, $conn)
{

    $apiUrl = $API_URL . '/api/active/' . $uid;

    $result = curlGET($API_KEY, $apiUrl);

    if ($result) {
        if ($result['active'] == 0) {
            mysqli_query($conn, 'UPDATE users SET status=1 WHERE user_id = ' . $uid);
            exit;
        }
    }
}

function getData($con, $res, $id, $type)
{
    if ($type == "DELETE") {
        return false;
    }
    if ($res == 'clients') {
        $sql = "SELECT clients.id, clients.name, clients.mobile, clients.email, clients.address, clients.status, clients.del_action, roles.name as role from clients ";
        $sql .= "LEFT JOIN roles on clients.role_id = roles.id WHERE clients.id = $id";
    } else if ($res == 'users') {
        $sql = "SELECT * from users WHERE user_id= $id";
    } else if ($res == 'user_log') {
        $sql = "SELECT * from user_log WHERE user_log_id= $id";
    } else if ($res == 'categories') {
        $sql = "SELECT * from categories WHERE category_id= $id";
    } else if ($res == 'projects') {
        $sql = "SELECT * from projects WHERE project_id= $id";
    } else {
        $sql = "SELECT * from $res WHERE id= $id";
    }
    $getsql = mysqli_query($con, $sql);
    $result = mysqli_fetch_object($getsql);
    return $result;
}



function curlGET($APIKEY, $APIURL)
{

    $headers = array(
        'Content-Type: application/json',
        'X-Hamc: ' . $APIKEY,
    );

    $ch = curl_init($APIURL);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


    $response = curl_exec($ch);


    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    }

    curl_close($ch);

    if ($response) {
        $responseData = json_decode($response, true);
        return $responseData;
    }
    return false;
}


