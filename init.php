<?php

$db = require_once 'config/db.php';

// $link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
$link = mysqli_connect('localhost', 'root', '', '810259-yeticave-11');
mysqli_set_charset($link, "utf8");

$categories = [];
$content = '';