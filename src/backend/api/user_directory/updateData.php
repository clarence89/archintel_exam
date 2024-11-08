<?php

use Controllers\DirectoryController;
use Controllers\UserController;
use Database\Database;
include '../../includes/header.php';

if (!isset($_POST['edit_data_id'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$database = new Database();
$directory_data = [
    // 'rfid' => $database->escape(string: $_POST['rfid']),
    'fname' => $database->escape(string: $_POST['fname']),
    'mname' => $database->escape(string: $_POST['mname']),
    'lname' => $database->escape(string: $_POST['lname']),
    'address' => $database->escape(string: $_POST['address']),
    'nameinit' => $database->escape(string: $_POST['nameinit']),
    'affiliation' => $database->escape(string: $_POST['affiliation']),
    'gender' => $database->escape(string: $_POST['gender']),
    'birthdate' => $database->escape(string: $_POST['birthdate']),
];

$get_user = new UserController();
$get_user->where(field_name: 'id', value: $database->escape(string: $_POST['edit_data_id']))->get();
$get_user_directory_id = $get_user()->result[0]['directory_id'];

$directory = new DirectoryController(fields: $fields = ['users.id'], distinct: $distinct = false);
// $directory_check = $directory->leftJoin(table: 'users', parent_key: 'users.directory_id', child_key: 'directory.id')->where(field_name: 'rfid', value: $database->escape(string: $directory_data['rfid']))->get();
// # Check if the id of the check is the same as the id of the edit if not check if it exists
// if ($directory_check->count > 0) {
//     if ($directory_check->result[0]['id'] != $_POST['edit_data_id']) {
//         echo json_encode(value: ['message' => 'User with this rfid already exists', 'error' => true]);
//         exit;
//     }
// }

$directory->update(data: $directory_data)->where(field_name: 'id', value: $database->escape(string: $get_user_directory_id))->save();

if (!empty($_POST['username']) && !empty($_POST['role'])) {
    $user_account = new UserController();
    $data = [
        "username" => $database->escape(string: $_POST['username']),
        "role" => $database->escape(string: $_POST['role']),
        "status" => 0,
        "created_by" => $database->escape(string: $current_user_id),
    ];

    if (!empty($_POST['password'])) {
        $data["password"] = password_hash(password: $database->escape(string: $_POST['password']), algo: PASSWORD_DEFAULT);
    }
    $user_check = $user_account->where(field_name: 'username', value: $database->escape(string: $data['username']))->get();
    if ($user_check->count > 0) {
        if ($user_check->result[0]['id'] != $_POST['edit_data_id']) {
            echo json_encode(value: ['message' => 'User with this username already exists', 'error' => true]);
            exit;
        }
    }
    $user_insert_id = $user_account->update(data: $data)->where('id', $database->escape(string: $_POST['edit_data_id']))->save();
}

echo json_encode(value: [
    'error' => false,
    'message' => 'User Updated Successfully!',
]);
exit;
