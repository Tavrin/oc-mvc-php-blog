<?php


namespace Core;


use Throwable;

class FormHandleException extends \Exception
{
    public string $type;
    public string $name;

    public function __construct(string $type = '', string $name = '',$message = "",$input = '', $code = 0, Throwable $previous = null)
    {
        $this->type = $type;
        $this->name = $name;
        parent::__construct($message, $code, $previous);
    }
}