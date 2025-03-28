<?php

if (!function_exists('extract_number')) {
    function extract_number($text)
    {
        return preg_replace('/[^0-9]/', '', $text);
    }
}

if (!function_exists('set_date_format')) {
    function set_date_format($date, $format = 'd/m/Y')
    {
        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('get_array_value')) {
    function get_array_value($array, $key) {
        if (!is_array($array) || empty($key)) {
            return null;
        }

        // Manejar la notación de punto para acceder a valores anidados
        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);
            foreach ($keys as $keyPart) {
                if (isset($array[$keyPart])) {
                    $array = $array[$keyPart];
                } else {
                    return null;
                }
            }
            return $array;
        }

        // Devolver el valor si la clave existe
        return $array[$key] ?? null;
    }
}

if (!function_exists('get_date_formatted')) {
    function get_date_formatted()
    {
        return \Carbon\Carbon::now()->format('Ymd');
    }
}
