<?php 

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php'; // подключаем БД

$categories = get_db_categories($link); // берем из БД список категорий и превращаем в двумерный массив

$cats_ids = array_column($categories, 'id');

$errors = [];
$lot = [];

// Если метод POST, значит сценарий был вызван отправкой формы (Общая проверкa)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // получим массив из формы
  $lot = filter_input_array(INPUT_POST, [
    'name' => FILTER_DEFAULT,
    'description' => FILTER_DEFAULT,
    'img_url' => FILTER_DEFAULT,
    'first_price' => FILTER_DEFAULT,
    'end_date' => FILTER_DEFAULT,
    'bid_step' => FILTER_DEFAULT,
    'category_id' => FILTER_DEFAULT
  ], true);

  // Проверку заполнения обязательных полей, по списку п.1
  if (empty($lot['name'])) {
    $errors['name'] = 'Заполните Наименование лота';
  }
  if (empty($lot['description'])) {
    $errors['description'] = 'Заполните Описание лота';
  }
  if (empty($lot['first_price'])) {
    $errors['first_price'] = 'Заполните Начальная цена';
  }
  if (empty($lot['bid_step'])) {
    $errors['bid_step'] = 'Заполните Шаг ставки';
  }
  if (empty($lot['category_id'])) {
    $errors['category_id'] = 'Заполните Категория';
  }
  if (empty($lot['end_date'])) {
    $errors['end_date'] = 'Заполните Дата завершения';
  }
  // Проверку конкретных полей п.2
  if (empty($errors)) {
    // Проверку существования категории с указанным id
    $errors['category_id'] = validateCategory($lot['category_id'], $cats_ids);
    // Проверкa начальной цены
    if (!is_numeric($lot['first_price']) || !is_int($lot['first_price'] * 1) || !intval($lot['first_price']) > 0) {
      $errors['first_price'] = 'Содержимое поля «начальная цена» должно быть числом больше нуля';
    }
    // Проверкa шага ставки
    if (!is_numeric($lot['bid_step']) || !is_int($lot['bid_step'] * 1) || !intval($lot['bid_step']) > 0) {
      $errors['bid_step'] = 'Содержимое поля «шаг ставки» должно быть целым числом больше нуля';
    }
    // Проверка даты завершения
    if (!is_date_valid($lot['end_date']) || strtotime($lot['end_date']) - time() > 8400) {
      $errors['end_date'] = 'Дата завершения должна быть больше текущей даты, хотя бы на один день';
    }
  }
  //  отфильтровать пустые значения из масива ошибок if (empty errors) {
  $errors = array_filter($errors, function($element) {
    return !empty($element);
  });

  if (empty($errors)) {
    if (!empty($_FILES['img_url']['name'])) {
      // получим img из формы
      $tmp_name = $_FILES['img_url']['tmp_name'];
      $path = $_FILES['img_url']['name'];
      $ext = pathinfo($path, PATHINFO_EXTENSION);
      $filename = uniqid() . $ext;
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $file_type = finfo_file($finfo, $tmp_name);

      if ($file_type !== "image/jpeg" && $file_type !== "image/png") { // ошибка с проверкой типа файла jpg -> jpeg
        $errors['img_url'] = 'Загрузите картинку в формате jpg or png';
      } else {
        move_uploaded_file($tmp_name, 'uploads/' . $filename);
        $lot['img_url'] = $filename;
      }
    } else {
      $errors['img_url'] = 'Вы не загрузили файл';
    }
  }

  //  отфильтровать пустые значения из масива ошибок if (empty errors) {
  $errors = array_filter($errors, function($element) {
    return !empty($element);
  });

  if (empty($errors)) {
    // формируем массив лота и сохраняем его
    $sql = 'INSERT INTO lot (date_add, user_id,  name, description, img_url, first_price, end_date, bid_step, category_id) VALUES (NOW(), 1, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, $lot);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
      $lot_id = mysqli_insert_id($link);

      header("Location: lot.php?id=" . $lot_id);
    }
  }
}

$page = include_template('add-lot.php', [
  'categories' => $categories,
  'lot' => $lot,
  'errors' => $errors
]);

print($page);
