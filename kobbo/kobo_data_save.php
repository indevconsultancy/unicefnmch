<?php
header('Content-Type: application/json; charset=utf-8');
include_once('includes/config.php');
include_once('includes/functions.php');

$survey_name_id = '1';

$surveySql = mysqli_query($conn, "SELECT client_id,user_id,survey_name FROM survey WHERE id = '" . $survey_name_id . "' ");
$dataSurvey=mysqli_fetch_array($surveySql);
$user_id=$dataSurvey['user_id'];
$client_id=$dataSurvey['client_id'];
$survey_name=$dataSurvey['survey_name'];

$get_file = file_get_contents("https://unicef.indevconsultancy.in/kobbo/pull-data.php");
$getFileJson = json_decode($get_file, true);

if ($getFileJson && isset($getFileJson['results'])) {
    $results = $getFileJson['results'];
    $output = [];

    $groupNameSql = mysqli_query($conn, "SELECT id, group_name FROM questions_group WHERE survey_id = '" . $survey_name_id . "' AND group_type = 'screen'");
    $groupMap = [];
    while ($group = mysqli_fetch_assoc($groupNameSql)) {
        $groupMap[$group['group_name']] = $group['id'];
    }

    $excludeKeys = [
        '_id',
        '__version__',
        'start',
        'end',
        '_xform_id_string',
        '_uuid',
        '_attachments',
        '_status',
        '_geolocation',
        '_submission_time',
        '_tags',
        '_notes',
        '_validation_status',
        '_submitted_by'
    ];
    $i=1;
    foreach ($results as $skey => $resultsArray) {
        $userOutput = [];

        $userOutput['survey_id'] = $resultsArray['_id'] ?? null;
        $userOutput['survey_name_id'] = $survey_name_id;
        $userOutput['survey_data_id'] = $resultsArray['_id'] ?? null;
        $userOutput['user_id'] = $user_id;
        $userOutput['client_id'] = $client_id;
        $userOutput['app_version'] = 'Kobo_web';
        $userOutput['start_date_time'] = $resultsArray['start'] ?? null;
        $userOutput['end_date_time'] = $resultsArray['end'] ?? null;
        $userOutput['survey_status'] = '1';
        $userOutput['send_survey_data'] = 'complete';
        $userOutput['survey_name'] = $survey_name;
        $userOutput['_xform_id_string'] = $resultsArray['_xform_id_string'] ?? null;
        $userOutput['_uuid'] = $resultsArray['_uuid'] ?? null;
        $userOutput['_attachments'] = $resultsArray['_attachments'] ?? [];
        $userOutput['_status'] = $resultsArray['_status'] ?? 'submitted_via_web';
        $userOutput['_geolocation'] = $resultsArray['_geolocation'] ?? [null, null];
        $userOutput['_submission_time'] = $resultsArray['_submission_time'] ?? null;
        $userOutput['_tags'] = $resultsArray['_tags'] ?? [];
        $userOutput['_notes'] = $resultsArray['_notes'] ?? [];
        $userOutput['_validation_status'] = $resultsArray['_validation_status'] ?? [];
        $userOutput['_submitted_by'] = $resultsArray['_submitted_by'] ?? 'Kobo';

        $userOutput['survey_data'] = [];

        foreach ($resultsArray as $key => $value) {
            if (!in_array($key, $excludeKeys)) {
                if (strpos($key, '/') !== false) {
                    list($group, $field) = explode('/', $key);

                    if (array_key_exists($group, $groupMap)) {
                        if (!isset($userOutput[$group])) {
                            $userOutput[$group] = [[]];
                        }

                        $questionIdSql = mysqli_query($conn, "SELECT question_id, input_field_type FROM questions_language WHERE field_name = '$field' AND survey_id = '$survey_name_id'");
                        $question = mysqli_fetch_assoc($questionIdSql);
                        $questionId = $question ? $question['question_id'] : '';
                        $input_field_type = $question ? $question['input_field_type'] : '';
                        $input_field_types[$questionId] = $input_field_type;

                        $option_id = '';
                        $option_value = '';

                        if ($input_field_type === 'select_one') {
                            $option_id = $value ?? '';
                        } elseif ($input_field_type === 'select_multiple') {
                            $option_value = str_replace(' ', ',', $value);
                        } else {
                            $option_value = $value ?? '';
                        }

                        $fieldData = [
                            'duration' => '',
                            'field_name' => $field,
                            'gps' => '',
                            'option_id' => $option_id,
                            'option_value' => $option_value,
                            'para_data' => '',
                            'pre_field' => '',
                            'question_id' => $questionId,
                            'timestamp' => ''
                        ];

                        $userOutput[$group][0][] = $fieldData;
                    }
                } else {
                    $questionIdSql = mysqli_query($conn, "SELECT question_id FROM questions_language WHERE field_name = '$key' AND survey_id = '$survey_name_id'");
                    $question = mysqli_fetch_assoc($questionIdSql);
                    $questionId = $question ? $question['question_id'] : '';
                    $input_field_type = $question ? $question['input_field_type'] : '';
                    $input_field_types[$questionId] = $input_field_type;

                    $option_id = '';
                    $option_value = '';

                    if ($input_field_type === 'select_one') {
                        $option_id = $value ?? '';
                    } elseif ($input_field_type === 'select_multiple') {
                        $option_value = str_replace(' ', ',', $value);
                    } else {
                        $option_value = $value ?? '';
                    }

                    $fieldData = [
                        'duration' => '',
                        'field_name' => $key,
                        'gps' => '',
                        'option_id' => $option_id,
                        'option_value' => $option_value,
                        'para_data' => '',
                        'pre_field' => '',
                        'question_id' => $questionId,
                        'timestamp' => ''
                    ];

                    $userOutput['survey_data'][] = $fieldData;
                }
            }
        }

        $postdatas = json_encode($userOutput);

		 $url = 'https://unicef.indevconsultancy.in/mis/api/survey_data_upload_v2_web.php';
		$postdatas = json_encode($userOutput);
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($ch);
		curl_close($ch);
		echo $result;
       // $userOutput=[];
        
    }
    

    // echo json_encode(['status' => 'Success', 'last_id' => $last_id]);
    // exit;
}
?>
