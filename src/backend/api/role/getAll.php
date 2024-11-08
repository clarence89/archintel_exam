<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\RoleController;

$role = new RoleController([ "id", "role_name"]);
if (!hasPermission(current_user_id: $current_user_id, permission: 'administrator')) {
    $role->where('role_name', "%Admin%", 'NOT LIKE');
}
$role->get();
echo json_encode(value: $role()->result);
exit;
