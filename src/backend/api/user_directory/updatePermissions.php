<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\UserPermissionController;
use Database\Database;

if (!isset($_POST['user_id']) || !isset($_POST['permissions'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$user_role = new UserPermissionController();
$database = new Database();
$user_role->delete()->where('user_id', $database->escape($_POST['user_id']))->save();
foreach (json_decode($_POST['permissions']) as $permission) {
    $user_role_insert = new UserPermissionController();
    $data = [
        'user_id' => $database->escape($_POST['user_id']),
        'permission_id' => $database->escape($permission),
    ];
    $user_role_insert->insert(data: $data)->save();
}
echo json_encode([
    'error' => false,
    'message' => 'Permission Update Success!',
]);
exit;
