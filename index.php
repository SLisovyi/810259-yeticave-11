<?php

require_once('functions.php');
require_once('data.php');

function show_date($timestamp) {
    $dt = date_create();
    $dt = date_time_set($dt, $timestamp);

    $format = date_format($dt, "d.m.Y H:i");

    return $format;
}

$page_content = include_template('main.php', ['lots' => $lot_list]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'YetiCave - Home page'
]);

print($layout_content);

