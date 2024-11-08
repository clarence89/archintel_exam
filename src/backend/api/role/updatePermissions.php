<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\RolePermissionController;
use Database\Database;

if (!isset($_POST['role_id']) || !isset($_POST['permissions'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$role = new RolePermissionController();
$database = new Database();
$role->delete()->where('role_id', $database->escape($_POST['role_id']))->save();
foreach (json_decode($_POST['permissions']) as $permission) {
    $role_insert = new RolePermissionController();
    $data = [
        'role_id' => $database->escape($_POST['role_id']),
        'permission_id' => $database->escape($permission),
    ];
    $role_insert->insert(data: $data)->save();
}
echo json_encode([
    'error' => false,
    'message' => 'Permission Update Success!',
]);
exit;
