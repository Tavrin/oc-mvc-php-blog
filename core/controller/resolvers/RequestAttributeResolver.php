<?php


namespace App\core\controller\resolvers;


use App\Core\Http\Request;

class RequestAttributeResolver
{
    public function checkValue(Request $request, $argument): bool
    {
        return $request->hasAttribute($argument['name']);
    }

    public function setValue(Request $request, $argument)
    {
        return $request->getAttribute($argument["name"]);
    }
}