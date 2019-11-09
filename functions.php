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

function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}