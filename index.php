<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';

require_once 'init.php'; // подключаем БД

// берем из БД список категорий и превращаем в двумерный массив
$categories = get_db_categories($link);

// берем из БД список открытых лотов и превращаем в двумерный массив
$lots = get_db_lots($link);

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
