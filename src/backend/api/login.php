<?php

use Controllers\PermissionController;
use Controllers\RoleController;

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
require '../vendor/autoload.php';

use Controllers\AffiliationController;
use Controllers\UserController;
use Database\Database;
use Firebase\JWT\JWT;

if (isset($_POST['username'])) {
    $secretKey = 'your_secret_key';
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;

    $conn = new Database();
    $username = $conn->escape(string: $_POST['username']);
    $password = $conn->escape(string: $_POST['password']);

    $user = new UserController([
        'users.id',
        'users.directory_id',
        'users.role',
        'users.password',
        'directory.affiliation',
        'directory.fname',
        'directory.mname',
        'directory.lname',
        'directory.nameinit'
    ]);
    $stmt = $user->leftJoin(table: 'directory', parent_key: 'users.directory_id', child_key: 'directory.id')->where(field_name: 'username', value: $username)->where(field_name: 'status', value: 0)->first();
    if ($stmt->count > 0) {
        if (password_verify(password: $password, hash: $stmt->result['password'])) {
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'sub' => $stmt->result['id']
            ];
            $jwt = JWT::encode(payload: $payload, key: $secretKey, alg: 'HS256');

            $sql_affiliation = new AffiliationController();
            $stmt_affiliation = $sql_affiliation->where(field_name: 'id', value: $conn->escape(string: $stmt->result['affiliation']))->first();
            $affiliation = $stmt_affiliation->result['affiliation_name'];

            $sql_role = new RoleController();
            $stmt_role = $sql_role->where(field_name: 'id', value: $conn->escape(string: $stmt->result['role']))->first();
            $role = $stmt_role->result['role_name'];


            $sql_permissions = new PermissionController(fields: ["permission_value", "permission_name"]);
            $stmt_permissions = $sql_permissions
                ->leftJoin(table: 'role_permissions', parent_key: 'permission.id', child_key: 'role_permissions.permission_id')
                ->leftJoin(table: 'user_permissions', parent_key: 'user_permissions.permission_id', child_key: 'permission.id')
                ->where(field_name: 'role_permissions.role_id', value: $conn->escape(string: $stmt->result['role']))
                ->orWhere(field_name: 'user_permissions.user_id', value: $conn->escape(string: $stmt->result['id']))
                ->get();

            $permissions = $stmt_permissions->result;

            echo json_encode(value: [
                'error' => false,
                'token' => $jwt,
                'session_expire' => $expirationTime,
                'user' => [
                    'firstname' => $stmt->result['fname'],
                    'middlename' => $stmt->result['mname'],
                    'lastname' => $stmt->result['lname'],
                    'nameinit' => $stmt->result['nameinit'],
                    'affiliation' => $affiliation,
                    'role' => $role,
                    'permissions' => $permissions
                ],

            ]);
        } else {
            echo json_encode(value: ['message' => 'Login failed: Credentials do not match', 'error' => true]);
        }
    } else {
        echo json_encode(value: ['message' => 'Login failed: Credentials do not match', 'error' => true]);
    }
}
