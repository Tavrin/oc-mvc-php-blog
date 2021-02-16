<?php

namespace App\core\database;

use App\core\database\Connection;

class Driver
{
    public static function getConnection(string $url)
    {
        $params = self::parseUrl($url);

        $dsn = 'mysql:host=' . $params['host'] . ';port=' . $params['port'] . ';dbname=' . $params['database'];
        $user = $params['user'];
        $password = $params['pass'];
        return new Connection($dsn, $user, $password, $params);
    }

    private static function parseUrl(string $url)
    {

        assert(is_string($url));

        $url = parse_url($url);
        $url = array_map('rawurldecode', $url);

        if (isset($url['host'])) {
            $params['host'] = $url['host'];
        }

        if (isset($url['port'])) {
            $params['port'] = $url['port'];
        }

        if (isset($url['user'])) {
            $params['user'] = $url['user'];
        }

        if (isset($url['pass'])) {
            $params['pass'] = $url['pass'];
        }

        if(isset($url['path'])) {
            $params['database'] = substr($url['path'], 1);
        }

        return $params;
    }
}