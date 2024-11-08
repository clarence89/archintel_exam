<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\AffiliationController;

$affiliation = new AffiliationController([ "id", "affiliation_name"]);
$affiliation->get();
echo json_encode(value: $affiliation()->result);
exit;
