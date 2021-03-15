<?php


namespace Core\http;


class Session
{
    protected ?array $attributes = null;
    protected bool $started = false;

    public function __construct()
    {
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function start(): bool
    {
        if ($this->started) {
            return true;
        }

        try {
            session_start();
            $this->started = true;
            header(sprintf('Set-Cookie: %s=%s', session_name(), session_id()));
            isset($_SESSION) ? $this->attributes = $_SESSION : $this->attributes = [];
        } catch (\Exception $exception)
        {
            throw $exception;
        }

        return true;
    }

    public function get(string $value, $default = null)
    {
        return \array_key_exists($value, $this->attributes) ? $this->attributes[$value] : $default;
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
        $this->attributes[$key] = $value;

    }

    public function has(string $key): bool
    {
        if (!isset($this->attributes)) {
            return false;
        }
        return array_key_exists($key, $this->attributes);
    }

    public function getAll(): ?array
    {
        dump('session getall methode');
        dump($this->attributes);
        return $this->attributes;
    }

    public function remove(string $key)
    {
        if (array_key_exists($key, $this->attributes)) {
            unset($this->attributes[$key]);
        }
    }
}