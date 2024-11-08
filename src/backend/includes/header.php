<?php

use Controllers\UserController;
use Controllers\PermissionController;
use Database\Database;

// Fetch the origin from the request header
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// List of allowed origins
$allowed_origins = [
    'http://localhost',
    'http://localhost:3000',
    'http://54.197.67.109',
    'https://54.197.67.109',
];


// Check if the origin is in the allowed list
if (in_array($origin, $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$headers = getallheaders();
$jwt = isset($headers['authorization']) ? str_replace('Bearer ', '', $headers['authorization']) : null;
if (!$jwt) {
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}
try {
    $conn = new Database();
    $secretKey = 'your_secret_key';
    $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
    $current_user_id = $decoded->sub;
    function hasPermission($current_user_id, $permission): bool {
        $conn = new Database();
        $current_user = new UserController();
        $current_user = $current_user->where(field_name: 'id', value: $current_user_id)->first();

        $current_user_sql_permissions = new PermissionController(fields: ["permission_value", "permission_name"]);
        $current_user_permissions = $current_user_sql_permissions
            ->leftJoin(table: 'role_permissions', parent_key: 'permission.id', child_key: 'role_permissions.permission_id')
            ->leftJoin(table: 'user_permissions', parent_key: 'user_permissions.permission_id', child_key: 'permission.id')
            ->where(field_name: 'role_permissions.role_id', value: $conn->escape(string: $current_user->result['role']))
            ->orWhere(field_name: 'user_permissions.user_id', value: $conn->escape(string: $current_user->result['id']))
            ->get();
        $current_user_permissions = $current_user_permissions->result;
        foreach ($current_user_permissions as $value) {
            if ($value['permission_value'] == $permission) {
                return true;
            }
        }
        return false;
    }
} catch (Exception $e) {
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}
