<?php

function is_number($number, $min = 0, $max = 100): bool {
    return ($number >= $min && $number <= $max);
}

function is_text($text, $min = 0, $max = 1000): bool {
    $length = mb_strlen($text);
    return ($length >= $min && $length <= $max);
}

function is_especie_id($especie_id, array $especies_list): bool {
    foreach ($especies_list as $especie) {
        if ($especie['id'] == $especie_id) {
            return true;
        }
    }
    return false;
}

// Valido en el código
function is_imagen_valida(array $imagen): bool {
    $allowed_types = ['image/jpeg', 'image/png'];
    return in_array($imagen['type'], $allowed_types) && $imagen['size'] > 0;
}

function is_age_valid(string $age): bool {
    return preg_match('/^\d+\s+(años|año|meses|mes|semanas|semana|días|día)$/i', $age) === 1;
}