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
    // $time_fixed = implode(':', $time_end);

    return $time_end;
}

function get_end_class($hour) {
    $time_interval = strtotime($hour) - time();
    $hours = floor($time_interval / 3600);

    if ($hours < 1) {
        $end_class = 'timer--finishing';
    } else {
        $end_class = null;
    }
    
    return $end_class;
}

