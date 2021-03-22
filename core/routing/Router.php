<?php


namespace Core\routing;

use Core\http\exceptions\NotFoundException;
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
        $requestPath = array_map('strtolower',explode('/', trim($this->pathInfo, '/')));

        foreach ($parsedRoutes as $testedRoute) {
            $params = [];
            $params['breadcrumb'][] = ['path' => '/', 'name' => 'accueil'];

            $testedRoute['path'] = array_map('strtolower', explode('/', trim($testedRoute['path'], '/')));

            if (count($requestPath) !== count($testedRoute['path'] )) {
                continue;
            }

            foreach ($requestPath as $key => $explodedUrl) {
                $testedRouteItem = $testedRoute['path'][$key];

                if (isset($testedRouteItem)) {
                    $params['breadcrumb'][] = ['path' => '/' . $testedRouteItem, 'name' => $testedRouteItem];
                }

                if (preg_match('/{(.*?)}/', $testedRouteItem, $match)) {
                    $params[$match[1]] = $explodedUrl;
                    continue;
                } elseif ($explodedUrl !== $testedRouteItem) {
                    continue 2;
                }
            }

            $params['route'] = $testedRoute['route'];
            $params['path'] = '/' . implode($testedRoute['path']);
            $params['controller'] = $testedRoute['controller'];

            return $params;
        }

        throw new NotFoundException(sprintf('Mauvaise route'), 404);
    }

    public static function matchError(): string
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
