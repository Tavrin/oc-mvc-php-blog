<?php


namespace Core;


use Throwable;

class FormHandleException extends \Exception
{
    public string $type;
    public string $name;
    public string $input;

    public function __construct(string $type = '', string $name = '',$message = "",$input = '', $code = 0, Throwable $previous = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->input = $input;
        parent::__construct($message, $code, $previous);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInput(): string
    {
        return $this->input;
    }
}