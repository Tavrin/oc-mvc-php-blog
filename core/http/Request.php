<?php

namespace Core\http;

/**
 * Class Request
 * @package App\Config\Http
 */
class Request
{
    /**
     * @var array
     */
    public array $request;

    /**
     * @var array
     */
    public array $query;

    /**
     * @var array
     */
    public array $attributes;

    /**
     * @var array
     */
    public array $cookies;

    /**
     * @var array
     */
    public array $files;

    /**
     * @var array
     */
    public array $server;

    /**
     * @var
     */
    public $content;

    /**
     * @var ?string
     */
    public ?string $pathInfo;

    public $controller;
    /**
     * @var ?string
     */
    protected ?string $method;

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
        $this->pathInfo = null;
        $this->controller = null;
        $this->method = null;
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

    /**
     * @return string
     */
    public function getPathInfo(): string
    {
        if (null === $this->pathInfo) {
            $this->pathInfo = $this->setPathInfo();
        }

        return $this->pathInfo;
    }

    /**
     * @return string
     */
    public function setPathInfo(): string
    {
        if (false === empty($this->server['PATH_INFO'])) {
            $pathInfo = htmlspecialchars($this->server['PATH_INFO']);
        } else {
            $pathInfo = htmlspecialchars($this->server['REQUEST_URI']);
        }


        return $pathInfo;
    }

    public function setController(array $controller): bool
    {
        if (empty($controller['route']) || empty($controller['path']) || empty($controller['controller'])) {
            return false;
        }
        $this->controller = $controller ;
        return true;
    }

    public function getController()
    {
        if (!empty($controller = $this->controller)) {
            return $controller;
        }
        return false;
    }

    public function setAttribute(string $key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function addAttribute(array $attributes = [])
    {
        $this->attributes = array_replace($this->attributes, $attributes);
    }

    public function getAttribute(string $key)
    {
        return \array_key_exists($key, $this->attributes) ? $this->attributes[$key] : null;
    }

    public function hasAttribute(string $key):bool
    {
        return \array_key_exists($key, $this->attributes);
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        if (empty($this->method)) {
            $this->method = $this->server['REQUEST_METHOD'];
        }

        return $this->method;
    }

    public function getRequest(string $key)
    {
        return \array_key_exists($key, $this->request) ? $this->request[$key] : null;
    }
}
