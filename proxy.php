<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// If it's a preflight request, just exit successfully
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

 $endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '/';
 $base_url = "https://adcash.myadcash.com/api/v2";
 $url = $base_url . $endpoint;

 $ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);

// Forward headers
 $headers = [];
 $headers[] = 'Content-Type: application/json';
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $headers[] = 'Authorization: ' . $_SERVER['HTTP_AUTHORIZATION'];
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Forward body
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = file_get_contents('php://input');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
}

 $response = curl_exec($ch);
 $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
http_response_code($http_code);
echo $response;
curl_close($ch);
?>
