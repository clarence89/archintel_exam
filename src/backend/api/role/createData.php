<?php

use Controllers\RoleController;
use Database\Database;
include '../../includes/header.php';

if (!isset($_POST['role_name'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$database = new Database();
$role = new RoleController();
$role_check = $role->where("role_name", $database->escape($_POST['role_name']))->get();
if ($role_check->count > 0) {
    echo json_encode(['message' => 'Role already exists', 'error' => true]);
    exit;
}

$role_insert = new RoleController();
$role_insert->insert(data: $_POST)->save();
echo json_encode([
    'error' => false,
    'message' => 'Role Created Successfully!',
]);
exit;
