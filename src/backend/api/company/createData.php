<?php

use Controllers\CompanyController;
use Database\Database;
include '../../includes/header.php';

if (!isset($_POST['company_name'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$database = new Database();
$company = new CompanyController();
$company_check = $company->where("company_name", $database->escape($_POST['company_name']))->get();
if ($company_check->count > 0) {
    echo json_encode(['message' => 'Company already exists', 'error' => true]);
    exit;
}

$company_insert = new CompanyController();
$company_insert->insert(data: $_POST)->save();
echo json_encode([
    'error' => false,
    'message' => 'Company Created Successfully!',
]);
exit;
