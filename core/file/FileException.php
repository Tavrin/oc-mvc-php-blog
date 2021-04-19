<?php


namespace Core\file;


use Throwable;

class FileException extends \RuntimeException
{
    public function __construct(File $file,$message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}