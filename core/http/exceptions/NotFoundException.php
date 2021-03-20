<?php


namespace Core\http\exceptions;

class NotFoundException extends HttpException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(404, $message, $code, $previous);
    }
}