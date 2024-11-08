<?php

use Controllers\ArticleController;
use Database\Database;
include '../../includes/header.php';

if (!isset($_POST['image']) || !isset($_POST['title']) || !isset($_POST['link']) || !isset($_POST['date']) || !isset($_POST['content'])) {
    echo json_encode(['message' => 'Missing parameters', 'error' => true]);
    exit;
}
$database = new Database();
$article = new ArticleController();

// $article_check = $article->where("company_name", $database->escape($_POST['company_name']))->where('id', $database->escape($_POST['id']), "!=")->get();

// if ($article_check->count > 0) {
//     echo json_encode(['message' => 'Company already exists', 'error' => true]);
//     exit;
// }

$article_update = new ArticleController();
$data = [
    'image' => $database->escape($_POST['image']),
    'title' => $database->escape($_POST['title']),
    'link' => $database->escape($_POST['link']),
    'date' => $database->escape($_POST['date']),
    'content' => $database->escape($_POST['content']),
    'company_id' => $database->escape($_POST['company_id']),
    'editor_id' => $database->escape($current_user_id),
    'status' => '0',
];
$article_update->update(data: $data)->where('id', $database->escape($_POST['id']))->save();
echo json_encode([
    'error' => false,
    'message' => 'Article Update Success!',
]);
exit;
