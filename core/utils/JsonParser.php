<?php


namespace Core\utils;


class JsonParser
{
    public static function parseFile(string $path)
    {
        $file = file_get_contents($path);
        return json_decode($file, true);
    }
}
