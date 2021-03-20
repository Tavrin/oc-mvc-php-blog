<?php


namespace Core\http\exceptions;


class HttpException extends \RuntimeException
{
    protected int $statusCode;

    public function __construct(int $statusCode, $message = "", $code = 0, \Throwable $previous = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}