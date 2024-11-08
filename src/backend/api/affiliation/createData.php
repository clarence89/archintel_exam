<?php

use Controllers\AffiliationController;
use Database\Database;
include '../../includes/header.php';

if (!isset($_POST['affiliation_name'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$database = new Database();
$affiliation = new AffiliationController();
$affiliation_check = $affiliation->where("affiliation_name", $database->escape($_POST['affiliation_name']))->get();
if ($affiliation_check->count > 0) {
    echo json_encode(['message' => 'Affiliation already exists', 'error' => true]);
    exit;
}

$affiliation_insert = new AffiliationController();
$affiliation_insert->insert(data: $_POST)->save();
echo json_encode([
    'error' => false,
    'message' => 'Affiliation Created Successfully!',
]);
exit;
