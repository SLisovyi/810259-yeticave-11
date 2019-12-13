<?php 

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php'; // подключаем БД

$categories = get_db_categories($link); // берем из БД список категорий и превращаем в двумерный массив

$errors = [];
$lot = [];

// Если метод POST, значит сценарий был вызван отправкой формы (Общая проверкa)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // получим массив из формы
  $sign_up = filter_input_array(INPUT_POST, [
    'email' => FILTER_DEFAULT,
    'password' => FILTER_DEFAULT,
    'name' => FILTER_DEFAULT,
    'contact' => FILTER_DEFAULT
  ], true);

  // Проверку заполнения обязательных полей, по списку п.1
  if (empty($sign_up['email'])) {
    $errors['email'] = 'Заполните e-mail';
  }
  if (empty($sign_up['password'])) {
    $errors['password'] = 'Заполните пароль';
  }
  if (empty($sign_up['name'])) {
    $errors['name'] = 'Заполните имя';
  }
  if (empty($sign_up['contact'])) {
    $errors['contact'] = 'Напишите как с вами связаться';
  }

  // Проверку конкретных полей
  if (empty($errors)) {

    // Проверим существование пользователя с email
    $email = mysqli_real_escape_string($link, $sign_up['email']);
    $sql = "SELECT id FROM user WHERE email = '$email'";
    $res = mysqli_query($link, $sql);

    // проверять является валидным E-mail 
    if (!filter_var($sign_up['email'], FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Неправильный email';
    }

    // Если запрос вернул больше нуля записей, значит такой поьзователь уже существует
    if (mysqli_num_rows($res) > 0) {
      $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    }
  }

  // Добавим нового пользователя в БД
  if (empty($errors)) {
    // Чтобы не хранить пароль в открытом виде преобразуем его в хеш
    $password = password_hash($sign_up['password'], PASSWORD_DEFAULT);

    $sql = 'INSERT INTO user (date_add, email, name, password, contact) VALUES (NOW(), ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, [$sign_up['email'], $password, $sign_up['name'], $sign_up['contact']]);
    $res = mysqli_stmt_execute($stmt);

    if ($res && empty($errors)) {
      header("Location: login.php");
      exit();
    }
  }
}

$page = include_template('sign-up.php', [
  'categories' => $categories,
  'errors' => $errors,
  'sign_up' => $sign_up
]);

print($page);
