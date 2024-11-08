<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\PermissionController;

$role = new PermissionController([ "id", "permission_name"]);

if (!hasPermission(current_user_id: $current_user_id, permission: 'administrator')) {
$role->where('permission_value', "administrator", '!=');
}
$role->get();
echo json_encode(value: $role()->result);
exit;
