<?php


namespace Core\utils;


class ArrayUtils
{
    public static function sanitizeArray(array $arr): array
    {
       return array_map('htmlspecialchars', $arr);
    }

    public static function keysInArray(array $keys, array $arr): bool
    {
        return !array_diff_key(array_flip($keys), $arr);
    }
}