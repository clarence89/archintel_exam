<?php
// require_once  __DIR__ . '/../../vendor/autoload.php';
include '../../includes/header.php';

use Controllers\ArticleController;
use Database\Database;

$article = new ArticleController([
    'article.*',
    'CONCAT(writer_directory.fname, " ", writer_directory.mname, " ", writer_directory.lname) as writer_fullname',
    'CONCAT(editor_directory.fname, " ", editor_directory.mname, " ", editor_directory.lname) as editor_fullname'
]);
$article->leftJoin('users as writer', 'writer.id', 'article.writer_id')->leftJoin('directory as writer_directory', 'writer_directory.id', 'writer.directory_id')
->leftJoin('users as editor', 'editor.id', 'article.editor_id')->leftJoin('directory as editor_directory', 'editor_directory.id', 'editor.directory_id')
->leftJoin('company', 'company.id', 'article.company_id');

if (isset($_POST['company_id'])) {
    $database = new Database();
    $article->where('article.company_id', $database->escape($_POST['company_id']));
}

$article->orderBy('article.created_at', 'DESC')->get();
echo json_encode(value: $article()->result);
exit;
