<?php


namespace App\core\utils;


class JsonParser
{
    public function parseFile(string $path)
    {
        $file = file_get_contents($path);
        $value = json_decode($file,true);

        return $value;
    }
}