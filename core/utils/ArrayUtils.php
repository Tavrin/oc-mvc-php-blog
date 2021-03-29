<?php


namespace Core\utils;


class ArrayUtils
{
    public static function sanitizeArray(array $arr)
    {
       return array_map('htmlspecialchars', $arr);
    }
}