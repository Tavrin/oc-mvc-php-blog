<?php


namespace App\core\routing;

use App\Core\Http\Request;

class Router
{
    public function match(Request $request)
    {
        $pathInfo = $this->sanitizePath($request->getPathInfo());
        dump($pathInfo);
    }

    public function sanitizePath(string $pathInfo)
    {
        $pathInfo = rawurldecode($pathInfo) ?: '/';
        dump($_SERVER['DOCUMENT_ROOT'] . '\..\core ');
        return $trimmedPathinfo = rtrim($pathInfo, '/') ?: '/';
    }
}