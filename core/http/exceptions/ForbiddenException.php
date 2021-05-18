<?php


namespace Core\http\exceptions;


use Throwable;

class ForbiddenException extends HttpException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(403, $message, $code, $previous);
    }
}