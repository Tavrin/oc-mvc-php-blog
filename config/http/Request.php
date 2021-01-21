<?php

namespace App\Config\Http;

/**
 * Class Request
 * @package App\Config\Http
 */
class Request
{
    protected static $requestFactory;

    public function __construct()
    {
        echo 'test';
    }

    /**
     * @return static
     */
    public static function create()
    {
        var_dump($_GET);
        $request = self::createRequestFromFactory($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);


        return $request;
    }

    private static function createRequestFromFactory(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null): self
    {
        if (self::$requestFactory) {
            $request = (self::$requestFactory)($query, $request, $attributes, $cookies, $files, $server, $content);

            if (!$request instanceof self) {
                throw new \LogicException('The Request factory must return an instance of Symfony\Component\HttpFoundation\Request.');
            }
            var_dump($attributes);
            return $request;
        }
        var_dump($attributes);
        return new static($query, $request, $attributes, $cookies, $files, $server, $content);
    }
}