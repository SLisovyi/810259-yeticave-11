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
    $time_end = [$hours_end, $mins_end];

    return $time_end;
}

function get_end_class($time) {
    [$hour] = get_time_end($time);

    if ($hour == 0) {
        $end_class = 'timer--finishing';

        return $end_class;
    }
}
