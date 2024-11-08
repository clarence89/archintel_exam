<?php

use Controllers\AffiliationController;
use Database\Database;
include '../../includes/header.php';

if (!isset($_POST['affiliation_name']) || !isset($_POST['affiliation_id'])) {
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
$data = [
    'affiliation_name' => $database->escape($_POST['affiliation_name']),
];
$affiliation_insert->update(data: $data)->where('id', $database->escape($_POST['affiliation_id']))->save();
echo json_encode([
    'error' => false,
    'message' => 'Affiliation Update Success!',
]);
exit;
