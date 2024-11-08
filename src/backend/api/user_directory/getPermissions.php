<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\PermissionController;
use Database\Database;

if (!isset($_POST['user_id'])) {
    echo json_encode(value: ['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$user_role = new PermissionController(fields: ['permission.id', 'permission_name']);
$database = new Database();
$user_role

->leftJoin(table: 'user_permissions', parent_key: 'permission.id', child_key: 'user_permissions.permission_id')->leftJoin(table: 'users', parent_key: 'user_permissions.user_id', child_key: 'users.id')

->where(field_name: 'users.id', value: $database->escape(string: $_POST['user_id']))

->get();
echo json_encode(value: $user_role()->result);
exit;
