<?php

require_once 'config/db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
    die('Ошибка соединения: ' . mysql_error());
} else {
    echo 'Успешно соединились';
    mysql_close($link);
}
