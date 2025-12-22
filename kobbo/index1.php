<?php
$api_key = '7ff2c617c94d41aff433c8d8c0c15bb0ba0bc406';
$api_url = 'https://kf.kobotoolbox.org/api/v2/assets/';

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Token $api_key",
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);
print_r($response);
if(curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
} else {
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_code == 200) {
        $assets = json_decode($response, true);
		
		/////////////////////////////

		// Assuming $response is the variable you're working with
		if (isset($assets) && is_array($assets)) {
			foreach ($assets as $asset) {
				// Process your array here
				 echo "Form Name: " . $asset['name'] . "\n";
                 echo "Asset UID: " . $asset['uid'] . "\n\n";
			}
		} else {
			echo "Data is not available or is not in the expected format.";
		}
    } else {
        echo "Failed to retrieve assets. HTTP Status Code: $http_code";
    }
}

curl_close($curl);
?>

