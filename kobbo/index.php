<?php
// Replace with your actual API key
$api_key = '7ff2c617c94d41aff433c8d8c0c15bb0ba0bc406';

// API base URL
$base_url = 'https://kf.kobotoolbox.org/api/v2/';

// Initialize a cURL session to list assets
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $base_url . 'assets/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Token $api_key",
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);

if(curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
} else {
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_code == 200) {
        $assets = json_decode($response, true);
        print_r($assets);
    } else {
        echo "Failed to retrieve assets. HTTP Status Code: $http_code";
    }
}

curl_close($curl);

// To retrieve data for a specific form, use the asset UID
$asset_uid = 'your_asset_uid_here'; // Replace with your form's UID

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $base_url . "assets/$asset_uid/data/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Token $api_key",
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);

if(curl_errno($curl)) {
    echo 'Error: ' . curl_error($curl);
} else {
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($http_code == 200) {
        $data = json_decode($response, true);
        print_r($data);
    } else {
        echo "Failed to retrieve data. HTTP Status Code: $http_code";
    }
}

curl_close($curl);
?>
