<?php

require_once 'functions.php';
require_once 'data.php';

$page_content = include_template('main.php', [
    'lots' => $lots,
    'categories' => $categories
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Home page',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
