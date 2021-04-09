<?php


namespace Core\http;


use Core\http\exceptions\SessionException;

class Session
{
    protected ?array $attributes = [];
    protected array $flash = ['new' => [], 'display' => []];
    protected bool $started = false;

    /**
     * @return bool
     * @throws \Exception
     */
    public function start(): bool
    {
        if ($this->started || isset($_SESSION)) {
            isset($_SESSION['attributes']) ? $this->attributes = $_SESSION['attributes'] : $this->attributes = [];
            return true;
        }

        try {
            session_start();
            $this->started = true;
            header(sprintf('Set-Cookie: %s=%s', session_name(), session_id()));
            isset($_SESSION['attributes']) ? $this->attributes = $_SESSION['attributes'] : $this->attributes = [];
            $this->initializeFlash();
        } catch (\Exception $exception)
        {
            throw new SessionException('an error occured during the session start', 500, $exception);
        }

        return true;
    }

    public function get(string $key, $default = null)
    {
        $this->getAll();
        return \array_key_exists($key, $this->attributes) ? $this->attributes[$key] : $default;
    }

    public function set(string $key, $value): void
    {
        $_SESSION['attributes'][$key] = $value;
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
        if (isset($_SESSION['attributes'])) {
            foreach ($_SESSION['attributes'] as $key => $value)
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
        if (isset($_SESSION['attributes'][$key]) || isset($this->attributes[$key])) {
            unset($_SESSION['attributes'][$key]);
            unset($this->attributes[$key]);
            return true;
        }

        return false;
    }

    public function removeAll(): bool
    {
        if (!isset($_SESSION['attributes'])) {
            return false;
        }

        unset($_SESSION['attributes']);
        $this->attributes = [];

        return true;
    }

    private function initializeFlash()
    {
        if (isset($_SESSION['flash'])) {
            if (array_key_exists('new', $_SESSION['flash'])) {
                $this->flash['display'] = $_SESSION['flash']['new'];
                unset($_SESSION['flash']['new']);
            }

            if (array_key_exists('display', $_SESSION['flash']) && !isset($this->flash['display'])) {
                unset($_SESSION['flash']['display']);
            } else {
                $_SESSION['flash']['display'] = $this->flash['display'];
            }
        }
    }

    public function addFlash(string $key, string $message)
    {
        $this->flash['new'][$key][] = $message;
        $_SESSION['flash']['new'][$key][] = $message;
    }

    public function getAllFlash(): ?array
    {
        if (isset($_SESSION['flash']['display'])) {
            $this->flash['display'] = $_SESSION['flash']['display'];
            return $this->flash['display'];
        }
        return null;
    }
}