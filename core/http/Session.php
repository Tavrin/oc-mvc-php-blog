<?php


namespace Core\http;


class Session
{
    protected ?array $attributes = [];
    protected bool $started = false;

    /**
     * @return bool
     * @throws \Exception
     */
    public function start(): bool
    {
        if ($this->started) {
            return true;
        }

        if (headers_sent()) {
            return false;
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
        $this->getAll();
        return \array_key_exists($value, $this->attributes) ? $this->attributes[$value] : $default;
    }

    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
        $this->attributes[$key] = $value;

    }

    public function has(string $key): bool
    {
        if ([] === $this->attributes) {
            return false;
        }
        return array_key_exists($key, $this->attributes);
    }

    public function getAll(): ?array
    {
        if (isset($_SESSION)) {
            foreach ($_SESSION as $key => $value)
            {
                if (!array_key_exists($key, $this->attributes)) {
                    $this->attributes[$key] = $value;
                }
            }
        }

        return $this->attributes;
    }

    public function remove(string $key): bool
    {
        if (array_key_exists($key, $_SESSION) || array_key_exists($key, $this->attributes)) {
            unset($_SESSION[$key]);
            unset($this->attributes[$key]);
            return true;
        }

        return false;
    }

    public function removeAll(): bool
    {
        if (!isset($_SESSION)) {
            return false;
        }

        unset($_SESSION);
        $this->attributes = [];

        return true;
    }
}