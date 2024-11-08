<?php

use Controllers\DirectoryController;
use Controllers\UserController;
use Database\Database;
include '../../includes/header.php';

if (!isset($_POST['fname'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$database = new Database();
$directory_data = [
    'fname' => $database->escape($_POST['fname']),
    'mname' => $database->escape($_POST['mname']),
    'lname' => $database->escape($_POST['lname']),
    'address' => $database->escape($_POST['address']),
    'nameinit' => $database->escape($_POST['nameinit']),
    'affiliation' => $database->escape($_POST['affiliation']),
    'gender' => $database->escape($_POST['gender']),
    'birthdate' => $database->escape($_POST['birthdate']),
];

$directory = new DirectoryController();
// $directory_check = $directory->where(field_name: 'rfid', value: $database->escape($directory_data['rfid']))->get();
// if ($directory_check->count > 0) {
//     echo json_encode(['message' => 'User with this rfid already exists', 'error' => true]);
//     exit;
// }
$directory_insert_id = $directory->insert(data: $directory_data)->save();

if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['role'])) {
    $user_account = new UserController();
    $data = [
        "directory_id" => $directory_insert_id->result,
        "username" => $database->escape($_POST['username']),
        "password" => password_hash($database->escape($_POST['password']), PASSWORD_DEFAULT),
        "role" => $database->escape($_POST['role']),
        "status" => 0,
        "created_by" => $database->escape($current_user_id),
    ];
    $user_check = $user_account->where(field_name: 'username', value: $database->escape($data['username']))->get();
    if ($user_check->count > 0) {
        $delete_directory = $directory->delete()->where(field_name: 'id', value: $directory_insert_id->result)->save();
        echo json_encode(['message' => 'User with this username already exists', 'error' => true]);
        exit;
    }
    $user_insert_id = $user_account->insert(data: $data)->save();
}

echo json_encode([
    'error' => false,
    'message' => 'User Created Successfully!',
]);
exit;
