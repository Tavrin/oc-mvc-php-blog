<?php


namespace Core\controller\resolvers;


use Core\http\Request;

class RequestResolver
{
    public function checkValue($argument):bool
    {
        return Request::class === $argument['type'];
    }

    public function setValue(Request $request): Request
    {
        return $request;
    }
}