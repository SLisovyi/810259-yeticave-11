<?php 

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php'; // подключаем БД

$categories = get_db_categories($link); // берем из БД список категорий и превращаем в двумерный массив
$cats_ids = array_column($categories, 'id');



if ($_SERVER['REQUEST_METHOD'] == 'POST') {


  $required = ['lot-name', 'message', 'lot-rate', 'lot-date', 'lot-step', 'user_id', 'category'];
  $errors = [];
  
  $rules = [
    'category_id' => function($value) use ($cats_ids) {
        return validateCategory($value, $cats_ids);
    }
  
  ];

  $lot = filter_input_array(INPUT_POST, ['title' => FILTER_DEFAULT, 'description' => FILTER_DEFAULT, 'category_id' => FILTER_DEFAULT], true);

  foreach ($lot as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors[$key] = $rule($value);
    }

    if (in_array($key, $required) && empty($value)) {
        $errors[$key] = "Поле $key надо заполнить";
    }
}

$errors = array_filter($errors);

  $lot = $_POST;

  if (!empty($_FILES['lot_img']['name'])) {
		$tmp_name = $_FILES['lot_img']['tmp_name'];
		$path = $_FILES['lot_img']['name'];
    $filename = uniqid() . '.jpg';

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($finfo, $tmp_name);
    
    if ($file_type !== "image/jpg") {
			$errors['file'] = 'Загрузите картинку в формате jpg';
    }
    else {
			move_uploaded_file($tmp_name, 'uploads/' . $filename);
			$lot['img_url'] = $filename;
		}
  }
  else {
		$errors['file'] = 'Вы не загрузили файл';
  }
  
  if (count($errors)) {
		$page = include_template('add.php', ['errors' => $errors, 'categories' => $categories]);
	}
  else {
    $sql = 'INSERT INTO lot (date_add, name, description, img_url, first_price, end_date, bid_step, user_id, category_id, winner_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, $lot);
    $res = mysqli_stmt_execute($stmt);
    
    if ($res) {
      $lot_id = mysqli_insert_id($link);
      header('Location: lot.php?id=' . $lot_id);
    }
    else {
      $page_content = include_template('add.php', ['categories' => $categories]);
    }
}

$page = include_template('add-lot.php', [
  'categories' => $categories
]);

print($page);
