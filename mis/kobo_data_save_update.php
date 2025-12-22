<?php
header('Content-Type: application/json; charset=utf-8');
include_once('includes/config.php');
include_once('includes/functions.php');
$formID='amt67t3PhSs69DFpvRXqbF';
$survey_name_id = getformId($conn,$formID);

$count_id =getcountrow($conn, 'amt67t3phss69dfpvrxqbf', 'id', 'id>',1);

//echo "https://unicef.indevconsultancy.in/kobbo/pull-data1.php&formID=$formID&countId=$count_id";
$get_file = file_get_contents("https://unicef.indevconsultancy.in/kobbo/pull-data1.php?formID=$formID&countId=$count_id");
$getFileJson = json_decode($get_file, true);
echo "<pre>";
print_r($getFileJson);
die();
if ($getFileJson && isset($getFileJson['results'])) {
    $results = $getFileJson['results'];
    $output = [];
    $i=1;
	if($survey_name_id>0)
	{
    foreach ($results as $skey => $resultsArray) {
        $userOutput = [];
        
        
        foreach ($resultsArray as $key => $value) {
			    $mkey=$key;
			    if (strpos($key, '/') !== false) {
				$mkey=str_replace('/','__',$key);	
				}
			    if($key=='_id')
				{
					 $userOutput['survey_id'] = $resultsArray['_id'] ?? null;
				}
				if($key=='_index')
				{
					 $userOutput['id'] = $resultsArray['_index'] ?? null;
				}
				if(is_array($resultsArray[$key]))
				{
					$outputValue=implode(" ",$resultsArray[$key]);
				}
				$userOutput[$mkey] = $resultsArray[$key] ?? null;
			
        }
        echo $postdatas = json_encode($userOutput);
		die();
       /*
		$url = 'https://unicef.indevconsultancy.in/mis/api/survey_data_upload_v2_web.php';
		$postdatas = json_encode($userOutput);
		$ch = curl_init($url); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($ch);
		curl_close($ch);
		//echo $result;
       // $userOutput=[];

       */ 
    }
	}
    

    // echo json_encode(['status' => 'Success', 'last_id' => $last_id]);
    // exit;
}
?>
