<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\CompanyController;

$company = new CompanyController([ "id", "company_name", "logo", "status"]);
$company->get();
echo json_encode(value: $company()->result);
exit;
