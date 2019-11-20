<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';

require_once 'init.php'; // подключаем БД

// берем из БД список категорий и превращаем в двумерный массив 
// mysqli_fetch_all($result_categories, MYSQLI_ASSOC)
if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $sql_categories = 'SELECT name, character_code FROM category';
    $result_categories = mysqli_query($link, $sql_categories);

    if ($result_categories) {
        $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    }
}

// берем из БД список открытых лотов и превращаем в двумерный массив 
// mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $sql_lots = 'SELECT l.date_add, l.end_date, l.name, l.first_price, l.img_url, 
    (SELECT c.name FROM category c WHERE c.id = l.category_id ) as cat_name,
    (SELECT b.price FROM bid b WHERE b.lot_id = l.id ORDER BY b.price ASC LIMIT 1) as last_price,
    l.category_id
    FROM lot l WHERE l.end_date > NOW();';
    $result_lots = mysqli_query($link, $sql_lots);

    if ($result_lots) {
        $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
    }
}

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
