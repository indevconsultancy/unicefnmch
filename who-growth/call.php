<?php
$url = 'https://unicef.indevconsultancy.in/who-growth/api.php';
$data = [
    'weight' => 8.4,
    'height' => 70,
    'head_circ' => 43,
    'gender' => 1,
    'age_months' => 10
];

$options = [
    'http' => [
        'header'  => "Content-Type: application/json",
        'method'  => 'POST',
        'content' => json_encode($data)
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo $result;

?>