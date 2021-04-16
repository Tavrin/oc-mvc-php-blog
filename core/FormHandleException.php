<?php


namespace Core;


use Throwable;

class FormHandleException extends \Exception
{
    public function __construct(string $type , string $name,$message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}