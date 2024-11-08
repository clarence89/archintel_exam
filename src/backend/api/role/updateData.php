<?php

use Controllers\RoleController;
use Database\Database;
include '../../includes/header.php';

if (!isset($_POST['role_name']) || !isset($_POST['role_id'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$database = new Database();
$role = new RoleController();
$role_check = $role->where("role_name", $database->escape($_POST['role_name']))->get();
if ($role_check->count > 0) {
    echo json_encode(['message' => 'Affiliation already exists', 'error' => true]);
    exit;
}

$role_insert = new RoleController();
$data = [
    'role_name' => $database->escape($_POST['role_name']),
];
$role_insert->update(data: $data)->where('id', $database->escape($_POST['role_id']))->save();
echo json_encode([
    'error' => false,
    'message' => 'Role Update Success!',
]);
exit;
