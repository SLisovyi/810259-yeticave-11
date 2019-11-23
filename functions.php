<?php

// function number to price
function to_price($amount) { 
    $amount = preg_replace('/[^0-9]/', '', $amount);
    $amount = ceil($amount);
    $amount = number_format($amount, 0, '.', ' ');
    $amount .= '₽';

    return $amount;  
}

function esc($text) {
    $text = htmlspecialchars($text);
    
    return $text;   
}

date_default_timezone_set("Europe/Moscow");

function get_time_end($time) {
    $time_interval = strtotime($time) - time();
    $hours_end = floor($time_interval / 3600);
    $mins_end = floor(($time_interval % 3600) / 60);
    $time_end = [intval($hours_end), intval($mins_end)];

    return $time_end;
}

function get_end_class($time) {
    [$hour] = get_time_end($time);

    if ($hour === 0) {
        return 'timer--finishing';
    }
    
    return '';
}

// функция получения $categories array из БД
function get_db_categories($link) {

    $sql = 'SELECT name, character_code FROM category';

    $result_query = mysqli_query($link, $sql);

    if ($result_query) {
       $result = mysqli_fetch_all($result_query, MYSQLI_ASSOC);
       return $result;
    }
    return '';
}

// функция получения $lots array из БД
function get_db_lots($link) {

    $sql = 'SELECT l.name, l.first_price, l.img_url, c.name AS category_name, l.end_date,
    (SELECT b.price FROM bid b WHERE b.lot_id = l.id ORDER BY b.price ASC LIMIT 1) as last_price,
    l.category_id
    FROM lot l INNER JOIN category c ON c.id = l.category_id
    WHERE l.end_date > NOW()';

    $result_query = mysqli_query($link, $sql);
    
    if ($result_query) {
       $result = mysqli_fetch_all($result_query, MYSQLI_ASSOC); 
       return $result; 
    }
    return '';
}
