<?php 

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php'; // подключаем БД

$categories = get_db_categories($link); // берем из БД список категорий и превращаем в двумерный массив
$cats_ids = array_column($categories, 'id');

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Если метод POST, значит сценарий был вызван отправкой формы

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

// получим img из формы
if (!empty($_FILES['img_url']['name'])) {

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
  
} else { // Если метод не POST
	$page = include_template('add-lot.php', ['categories' => $categories]);
}


print($page);
