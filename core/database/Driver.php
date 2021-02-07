<?php


namespace App\core\database;


class Driver
{
    public static function connect(string $url)
    {
        $url = self::parseUrl($url);

        return $url;
    }

    private static function parseUrl(string $url)
    {

        assert(is_string($url));

        $url = parse_url($url);
        $url = array_map('rawurldecode', $url);

        dump($url);

        return $url;
    }
}