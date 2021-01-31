<?php


namespace App\core\controller\resolvers;


use App\Core\Http\Request;

class RequestResolver
{
    public function checkValue(Request $request, $argument):bool
    {
        return Request::class === $argument['type'];
    }

    public function setValue(Request $request, $argument)
    {
        return $request;
    }
}