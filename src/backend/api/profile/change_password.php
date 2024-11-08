<?php

use Controllers\UserController;
use Database\Database;

include '../../includes/header.php';

if (!isset($_POST['current_password']) || !isset($_POST['new_password']) || !isset($_POST['confirm_password'])) {
  echo json_encode(['message' => 'Missing parameters', 'error' => true]);
  exit;
}
$database = new Database();

$user_check = new UserController();
$user = $user_check->where('id', $database->escape($current_user_id))->first();
if ($user->count == 0) {
  echo json_encode(['message' => 'User not found', 'error' => true]);
  exit;
}
if (!password_verify($_POST['current_password'], $user->result['password'])) {
  echo json_encode(['message' => 'Current password is incorrect', 'error' => true]);
  exit;
}
if ($_POST['new_password'] != $_POST['confirm_password']) {
  echo json_encode(['message' => 'Passwords do not match', 'error' => true]);
  exit;
}
$data = [
  'password' => password_hash($_POST['new_password'], PASSWORD_DEFAULT),
];

$user_update = new UserController();
$user_update->update(data: $data)->where('id', $database->escape($current_user_id))->save();
echo json_encode([
  'error' => false,
  'message' => 'Success',
]);
exit;
