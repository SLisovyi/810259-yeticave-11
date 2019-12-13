<?php

// добавили старт сессии, чтобы не писать это в каждом сценарии
session_start();
define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));

date_default_timezone_set("Europe/Moscow");

require_once 'config/db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

if (!$link) {
    $error = mysqli_connect_error();
    die('Ошибка');
}
mysqli_set_charset($link, "utf8");


