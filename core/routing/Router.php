<?php


namespace App\core\routing;

use App\Core\Http\Request;
use App\core\utils\JsonParser;
use http\Exception;

class Router
{
    public $pathInfo;

    const ROUTER_CONFIG = ROOT_DIR . '/config/routes.json';

    public function match(Request $request)
    {
        $params = $this->getRouteParams($request->getPathInfo());

        return $params;
    }

    private function getRouteParams(string $pathInfo)
    {
        $this->pathInfo = $this->sanitizePath($pathInfo);
        $parsedRoutes = JsonParser::parseFile(self::ROUTER_CONFIG);

        $explodedPath['url'] = explode('/',trim($this->pathInfo, '/'));
        $explodedPath['url'] = array_map('strtolower',$explodedPath['url']);
        $count['url'] = count($explodedPath['url']);

        foreach ($parsedRoutes as $route) {
            $slugs = [];

            $explodedPath['route'] = explode('/',trim($route['path'], '/'));
            $explodedPath['route'] = array_map('strtolower',$explodedPath['route']);

            $count['route'] = count($explodedPath['route']);

            if ($count['url'] !== $count['route']) {
                continue;
            }

            foreach ($explodedPath['url'] as $key => $explodedUrl) {
                $explodedRoute = $explodedPath['route'][$key];
                if (preg_match('/{(.*?)}/',$explodedRoute, $match)) {
                    $slugs[$match[1]]= $explodedUrl;
                    continue;
                } elseif ($explodedUrl !== $explodedRoute) {
                    continue 2;
                }
            }

            $params['route'] = $route['route'];
            $params['controller'] = $route['controller'];

            foreach ($slugs as $key => $slug) {
                $params[$key] = $slug;
            }

            return $params;
        }

        throw new \RuntimeException(dump('Mauvaise route'));
    }

    private function sanitizePath(string $pathInfo)
    {
        $pathInfo = rawurldecode($pathInfo) ?: '/';

        return $trimmedPathinfo = rtrim($pathInfo, '/') ?: '/';
    }
}