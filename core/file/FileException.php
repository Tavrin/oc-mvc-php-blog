<?php


namespace Core\file;


use Throwable;

class FileException extends \RuntimeException
{
    public $file;
    public function __construct(File $file,$message = "", $code = 0, Throwable $previous = null)
    {
        $this->file = $file;
        parent::__construct($message, $code, $previous);
    }
}