<?php

namespace App\Support;

class UrlSafeKey
{
    public static function encode(string $value): string
    {
        return rtrim(strtr($value, '+/', '-_'), '=');
    }

    public static function decode(string $value): string
    {
        $value = strtr($value, '-_', '+/');
        $remainder = strlen($value) % 4;

        if ($remainder) {
            $value .= str_repeat('=', 4 - $remainder);
        }

        return $value;
    }
}
