<?php

namespace App\Core\Http;

/**
 * Class Request
 * @package App\Config\Http
 */
class Request
{
    /**
     * @var
     */
    public $request;

    /**
     * @var
     */
    public $query;

    /**
     * @var
     */
    public $attributes;

    /**
     * @var
     */
    public $cookies;

    /**
     * @var
     */
    public $files;

    /**
     * @var
     */
    public $server;

    /**
     * @var
     */
    public $content;

    /**
     * Request constructor.
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     */
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->request = $request;
        $this->query = $query;
        $this->attributes = $attributes;
        $this->cookies = $cookies;
        $this->files = $files;
        $this->server = $server;
    }

    /**
     * @return static
     */
    public static function create(): Request
    {
        return  $request = self::getGlobals($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
    }

    private static function getGlobals(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null): self
    {
        return new static($query, $request, $attributes, $cookies, $files, $server, $content);
    }
}