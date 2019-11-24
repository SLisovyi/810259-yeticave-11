<?php

require_once 'helpers.php';
require_once 'functions.php';
require_once 'data.php';

require_once 'init.php'; // подключаем БД

// берем из БД список категорий и превращаем в двумерный массив
$categories = get_db_categories($link);

// берем из БД список открытых лотов и превращаем в двумерный массив
$lots = get_db_lots($link);


$id = filter_input(INPUT_GET, 'tab'); // находим tab html заголовка

if (isset($_GET['tab'])) { // если tab есть, меняем main на lot.php
    $sql = 'SELECT l.id, l.name, l.first_price, l.img_url, c.name AS category_name, l.end_date,
        (SELECT b.price FROM bid b WHERE b.lot_id = l.id ORDER BY b.price ASC LIMIT 1) as last_price,
        l.category_id
        FROM lot l INNER JOIN category c ON c.id = l.category_id
        WHERE l.id =' . $id . ';';
    
    $res = mysqli_query($link, $sql);
    $lot = mysqli_fetch_all($res, MYSQLI_ASSOC);

    $page_content = include_template('../pages/lot.php', [
        'lot' => $lot,
        'categories' => $categories
    ]);
} else {
    $page_content = include_template('main.php', [
        'lots' => $lots,
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Home page',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
