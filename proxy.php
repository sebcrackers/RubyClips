<?php
// Turn on error reporting so we can see if PHP fails
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

 $endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '/';
 $base_url = "https://adcash.myadcash.com/api/v2";
 $url = $base_url . $endpoint;

// Check if cURL is installed on your host
if (!function_exists('curl_init')) {
    echo json_encode(["error" => ["message" => "PHP cURL is NOT installed on your server."]]);
    exit;
}

 $ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

 $headers = [];
 $headers[] = 'Content-Type: application/json';
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
    $headers[] = 'Authorization: ' . $_SERVER['HTTP_AUTHORIZATION'];
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = file_get_contents('php://input');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
}

 $response = curl_exec($ch);
 $err = curl_error($ch);
 $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($http_code);

if ($err) {
    echo json_encode(["error" => ["message" => "Proxy cURL Error: " . $err]]);
} else if (empty($response)) {
    echo json_encode(["error" => ["message" => "Proxy Error: Adcash returned empty. HTTP Code: " . $http_code]]);
} else {
    echo $response;
}
?>
