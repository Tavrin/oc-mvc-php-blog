<?php


namespace Core\utils;


class JsonParser
{
    public static function parseFile(string $path)
    {
        $file = file_get_contents($path);
        $value = json_decode($file, true);

        return $value;
    }
}
