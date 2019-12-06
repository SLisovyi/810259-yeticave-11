<?php 

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php'; // подключаем БД

$categories = get_db_categories($link); // берем из БД список категорий и превращаем в двумерный массив
$cats_ids = [];
$cats_ids = array_column($categories, 'id');

// Если метод POST, значит сценарий был вызван отправкой формы (Общая проверкa)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $error = [];

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
  elseif (empty($lot['description'])) {
    $errors['description'] = 'Заполните Описание лота';
  }
  elseif (empty($lot['first_price'])) {
    $errors['first_price'] = 'Заполните Начальная цена';
  }
  elseif (empty($lot['bid_step'])) {
    $errors['bid_step'] = 'Заполните Шаг ставки';
  }
  elseif (empty($lot['category_id'])) {
    $errors['category_id'] = 'Заполните Категория';
  }
  // Проверку конкретных полей п.2
  else {
    // Проверку существования категории с указанным id
    if (validateCategory($lot['category_id'], $allowed_list)) {
      $errors['category_id'] = 'Выбрана не существующая Категория';
    }
    // Проверкa начальной цены
    elseif ($lot['bid_step'] <= 0) {
      $errors['bid_step'] = 'Содержимое поля «шаг ставки» должно быть целым числом больше ноля.';
    }
    // Проверкa шага ставки
    elseif (!is_int($lot['first_price']) && $lot['first_price'] <= 0) {
      $errors['first_price'] = 'Содержимое поля «начальная цена» должно быть числом больше нуля';
    }
    // Проверка даты завершения ?????????

    else { // Проверка файла п.3
      
      if (!empty($_FILES['img_url']['name'])) {
        // получим img из формы
        $tmp_name = $_FILES['img_url']['tmp_name'];
        $path = $_FILES['img_url']['name'];
        $filename = uniqid() . '.jpg';
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type !== "image/jpeg") { // ошибка с проверкой типа файла jpg -> jpeg
          $errors['file'] = 'Загрузите картинку в формате jpg';
        } else {
          move_uploaded_file($tmp_name, 'uploads/' . $filename);
          $lot['img_url'] = $filename;
        }  
      } else {
        $errors['file'] = 'Вы не загрузили файл';
      }
    }

  else {  // формируем массив лота и сохраняем его
    $page = include_template('add-lot.php', [
      'categories' => $categories,
      'lot' => $lot
    ]);
  
    $sql = 'INSERT INTO lot (date_add, user_id,  name, description, img_url, first_price, end_date, bid_step, category_id) VALUES (NOW(), 1, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, $lot);
    $res = mysqli_stmt_execute($stmt);
  
    if ($res) {
        $lot_id = mysqli_insert_id($link);
  
        header("Location: lot.php?id=" . $lot_id);
    }
  }
}

print($page);
