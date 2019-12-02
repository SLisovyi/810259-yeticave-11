<?php 

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php'; // подключаем БД

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // находим id html заголовка

if ((bool) $id === false) {
  header('Location:pages/404.html');
}

$lot = get_db_lot_by_id($link, $id);

if (empty($lot)) {
  header('Location:pages/404.html');
} 

$categories = get_db_categories($link); // берем из БД список категорий и превращаем в двумерный массив

$page = include_template('lot.php', [
  'lot' => $lot,
  'categories' => $categories
]);

print($page);
