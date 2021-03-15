<?php


namespace Core\routing;

use Core\http\Request;
use Core\utils\JsonParser;
use http\Exception;

class Router
{
    public string $pathInfo;

    public const ROUTER_CONFIG = ROOT_DIR . '/config/routes.json';

    public function match(Request $request): array
    {
        return $this->getRouteParams($request->getPathInfo());
    }

    private function getRouteParams(string $pathInfo): array
    {
        $this->pathInfo = $this->sanitizePath($pathInfo);
        $parsedRoutes = JsonParser::parseFile(self::ROUTER_CONFIG);

        $explodedPath['url'] = explode('/', trim($this->pathInfo, '/'));
        $explodedPath['url'] = array_map('strtolower', $explodedPath['url']);
        $count['url'] = count($explodedPath['url']);

        foreach ($parsedRoutes as $route) {
            $slugs = [];

            $explodedPath['route'] = explode('/', trim($route['path'], '/'));
            $explodedPath['route'] = array_map('strtolower', $explodedPath['route']);

            $count['route'] = count($explodedPath['route']);

            if ($count['url'] !== $count['route']) {
                continue;
            }

            foreach ($explodedPath['url'] as $key => $explodedUrl) {
                $explodedRoute = $explodedPath['route'][$key];

                if (isset($explodedRoute) && $explodedRoute === '') {
                    $breadcrumbs['path'] = '/';
                    $breadcrumbs['name'] = 'accueil';
                    $params['breadcrumb'][] = $breadcrumbs;
                }

                if (preg_match('/{(.*?)}/', $explodedRoute, $match)) {
                    $slugs[$match[1]] = $explodedUrl;
                    continue;
                } elseif ($explodedUrl !== $explodedRoute) {
                    continue 2;
                }
            }

            if (isset($explodedRoute)) {
                $breadcrumbs['path'] = '/' . $explodedRoute;
                $breadcrumbs['name'] = $explodedRoute;
                $params['breadcrumb'][] = $breadcrumbs;
            }

            $params['route'] = $route['route'];
            $params['controller'] = $route['controller'];

            foreach ($slugs as $key => $slug) {
                $params[$key] = $slug;
            }

            return $params;
        }

        throw new \RuntimeException(sprintf('Mauvaise route'), 404);
    }

    public static function matchError(\Throwable $e): string
    {
        $parsedRoutes = JsonParser::parseFile(self::ROUTER_CONFIG);

        foreach ($parsedRoutes as $route) {
            if ($route['route'] === 'error') {
                return $route['controller'];
            }
        }

        return  sprintf('No error page');
    }

    private function sanitizePath(string $pathInfo): string
    {
        $pathInfo = rawurldecode($pathInfo) ?: '/';

        return $trimmedPathinfo = rtrim($pathInfo, '/') ?: '/';
    }
}
