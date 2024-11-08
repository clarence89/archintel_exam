<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$headers = getallheaders();
$jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

if ($jwt) {
try {
$secretKey = 'your_secret_key';
$decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
print_r($decoded);
// Continue processing secured data
} catch (Exception $e) {
http_response_code(401);
echo json_encode(['message' => 'Unauthorized']);
}
} else {
http_response_code(401);
echo json_encode(['message' => 'Unauthorized']);
}
