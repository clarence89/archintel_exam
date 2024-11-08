<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\PermissionController;
use Database\Database;

if (!isset($_POST['role_id'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$role = new PermissionController(['permission.id', 'permission_name']);
$database = new Database();
$role->leftJoin('role_permissions', 'permission.id', 'role_permissions.permission_id')->leftJoin('roles', 'role_permissions.role_id', 'roles.id')->where('roles.id', $database->escape($_POST['role_id']))->get();
echo json_encode(value: $role()->result);
exit;
