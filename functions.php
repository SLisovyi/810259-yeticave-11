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
