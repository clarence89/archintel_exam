<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\DirectoryController;

$affiliation = new DirectoryController();
$affiliation->leftJoin('users', 'users.directory_id', 'directory.id')->leftJoin('affiliation', 'directory.affiliation', 'affiliation.id') ->leftJoin('roles', 'users.role', 'roles.id');
if (!hasPermission(current_user_id: $current_user_id, permission: 'administrator')) {
$affiliation->where('role_name', "%Admin%", 'NOT LIKE');
}

$affiliation->get();
$users = $affiliation()->result;
foreach ($users as $index => $user) {
    unset($users[$index]['password']);
}
echo json_encode($users);
exit;
