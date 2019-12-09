<?php

// function number to price
function to_price(int $amount): string
{
    $amount = preg_replace('/[^0-9]/', '', $amount);
    $amount = ceil($amount);
    $amount = number_format($amount, 0, '.', ' ');
    return $amount . '₽';
}

function esc($text)
{
    $text = htmlspecialchars($text);
    
    return $text;
}

function get_time_end($time)
{
    $time_interval = strtotime($time) - time();
    $hours_end = floor($time_interval / 3600);
    $mins_end = floor(($time_interval % 3600) / 60);
    $time_end = [intval($hours_end), intval($mins_end)];

    return $time_end;
}

function get_end_class($time)
{
    [$hour] = get_time_end($time);

    if ($hour === 0) {
        return 'timer--finishing';
    }
    return '';
}

// функция получения $categories array из БД
function get_db_categories($link)
{
    $sql = 'SELECT id, name, character_code FROM category';

    $result_query = mysqli_query($link, $sql);

    if ($result_query) {
        return mysqli_fetch_all($result_query, MYSQLI_ASSOC);
    }
    return [];
}

// функция получения $lots array из БД
function get_db_lots($link)
{
    $sql = 'SELECT l.id, l.name, l.first_price, l.img_url, c.name AS category_name, l.end_date,
    (SELECT b.price FROM bid b WHERE b.lot_id = l.id ORDER BY b.price ASC LIMIT 1) as last_price,
    l.category_id
    FROM lot l INNER JOIN category c ON c.id = l.category_id
    WHERE l.end_date > NOW()';

    $result_query = mysqli_query($link, $sql);
    
    if ($result_query) {
        return mysqli_fetch_all($result_query, MYSQLI_ASSOC);
    }
    return '';
}

// функция получения необходимого $lot по id
function get_db_lot_by_id($link, $id)
{
    $id = mysqli_real_escape_string($link, $id);
    $sql = 'SELECT l.name, l.description, l.first_price, l.img_url, c.name AS category_name, l.end_date,
         (SELECT b.price FROM bid b WHERE b.lot_id = l.id ORDER BY b.price ASC LIMIT 1) as last_price,
         l.category_id
        FROM lot l INNER JOIN category c ON c.id = l.category_id
        WHERE end_date > NOW() and  l.id = ' . $id . ';';

    $result_query = mysqli_query($link, $sql);
    
    if ($result_query) {
        return mysqli_fetch_assoc($result_query); 
    }
    return null;
}

// функция вывода конечной цены лота
function get_last_price($lot)
{
    return $lot['last_price'] ?? $lot['first_price'];
}

// функция сохранения введенного в поле POST значения
function get_post_val($name)
{
    return filter_input(INPUT_POST, $name);
}

// функция валидации существования категории
function validateCategory($id, $allowed_list)
{
    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }
    return 'null';
}

// функция валидации заполнения
function validateNotEmpty($value)
{
    if (empty($value)) {
        return "Поле не обязательно для заполнения";
    }
    return null;
}

// Проверяет переданную дату
function date_diff_in_days($date1, $date2) {
    if (strtotime($date1) - strtotime($date1) >= 86400) {
        return true
    }
    return false
}

