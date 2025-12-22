<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Methods: PUT, GET, POST");

require_once "config.php";
require_once "jwt.php";
require_once "../s3bucket/s3_bucket_config.php";

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$headers = getallheaders();
$tokendata = $headers["X-Authorization"] ?? "";

ini_set('memory_limit', '-1');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($tokendata) {
        $token_array = explode(" ", $tokendata);
        if($token_array[0] === "Bearer") {
            $token = $token_array[1];
            $decodedToken = verifyToken($token, JWT_SECRET_KEY, $conn);
            if ($decodedToken) {
                $data = media_upload($conn, $s3Client);
                echo json_encode($data);
                exit;
            } else {
                $response = ["status" => 0, "message" => "Auth request is invalid"];
                echo json_encode($response);
                exit;
            }
        }
    } else {
        http_response_code(401); // Unauthorized status code
        $response = ["status" => 0, "error" => "Invalid token"];
        echo json_encode($response);
        exit;
    } 
} else {
    http_response_code(405); // Method Not Allowed
    $response = ["status" => 0, "message" => "The call method not allowed"];
    echo json_encode($response);
    exit;
}

function media_upload($conn, $s3Client)
{
    $response = array();
    $survey_id = isset($_REQUEST['survey_id']) ? $_REQUEST['survey_id'] : '';
    $sequence_id = isset($_REQUEST['sequence_id']) ? $_REQUEST['sequence_id'] : '';
    $survey_name_id = isset($_REQUEST['survey_name_id']) ? $_REQUEST['survey_name_id'] : '';
    $client_id = isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] : '';
    $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $survey_data_monitoring_id = isset($_REQUEST['survey_data_monitoring_id']) ? $_REQUEST['survey_data_monitoring_id'] : '';
    $field_name = isset($_REQUEST['field_name']) ? $_REQUEST['field_name'] : '';
    $media_file = isset($_FILES['picture']) ? $_FILES['picture'] : '';

    $uniqueid = $sequence_id;
    $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    $createdAt = $date->format('Y-m-d H:i:s');

    $filename = $media_file['name'];
    $fileSize = $media_file['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $file_name = $uniqueid . "_" . $field_name . "." . $ext;
    $tmp_name = $media_file['tmp_name'];

    if ($media_file!='' && $survey_name_id!='') {
        $clientUniqueId="C".$client_id;
		$surveyUniqueId = $survey_name_id;
        $bucket = 'mquaddata';
        $s3Key = 'media_survey/' . $clientUniqueId . '/' . $surveyUniqueId . '/' . $file_name;

        try {
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key'    => $s3Key,
                'Body'   => fopen($tmp_name, 'r'),
            ]);

            if(!empty($result['ObjectURL'])) {
				$sqlUploadForm="insert into  media_question_data set survey_id='".$survey_id."',survey_name_id='".$survey_name_id."',field_name='".$field_name."',client_id='".$client_id."',user_id='".$user_id."',survey_data_monitoring_id='".$survey_data_monitoring_id."',media_file='".$file_name."',created_at='".$createdAt."' ";
				
                if (mysqli_query($conn, $sqlUploadForm)) {
                    $response = ["success" => 1, "message" => "Data successfully submitted", "filename" => $file_name, "file_size" => $fileSize];
                } else {
                    $response = ["success" => 0, "message" => "Something went wrong"];
                }
            } else {
                $response = ["success" => 0, "message" => "Upload Failed! S3 Object URL not found."];
            }
        } catch (AwsException $e) {
            $response = ["success" => 0, "message" => "File not uploaded to S3 bucket: " . $e->getMessage()];
        } finally {
            if (isset($tmp_name)) {
                fclose($tmp_name);
            }
        }
    } else {
        $response = ["success" => 0, "message" => "File or survey name is empty"];
    }

    mysqli_close($conn);
    return $response;
}
?>
