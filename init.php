<?php

require_once 'config/db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

if (!$link) {
    $error = mysqli_connect_error();
    die('Ошибка');
}
mysqli_set_charset($link, "utf8");
