<?php

use Controllers\CompanyController;
use Database\Database;
include '../../includes/header.php';

if (!isset($_POST['company_name']) || !isset($_POST['id'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$database = new Database();
$company = new CompanyController();

$company_check = $company->where("company_name", $database->escape($_POST['company_name']))->where('id', $database->escape($_POST['id']), "!=")->get();

if ($company_check->count > 0) {
    echo json_encode(['message' => 'Company already exists', 'error' => true]);
    exit;
}

$company_insert = new CompanyController();
$data = [
    'company_name' => $database->escape($_POST['company_name']),
    'logo' => $database->escape($_POST['logo']),
    'status' => $database->escape($_POST['status']),
];
$company_insert->update(data: $data)->where('id', $database->escape($_POST['id']))->save();
echo json_encode([
    'error' => false,
    'message' => 'Company Update Success!',
]);
exit;
