<?php


namespace App\core\database;


use App\core\utils\JsonParser;
use App\core\database\EntityManager;

class DatabaseResolver
{
    public static function instantiateManager(): ?EntityManager
    {
        $url = self::getDatabaseUrl();
        if (empty($url)) {
            return null;
        }
        return new EntityManager($url);
    }

    private static function getDatabaseUrl()
    {
        $parsedUrl = JsonParser::parseFile(ROOT_DIR . '/config/configuration.json');

        if (empty($parsedUrl['database']['url'])) {
            return false;
        }

        $parsedUrl = $parsedUrl['database']['url'];
        if (preg_match('#\$_(ENV|SERVER)\[(\'|\")(.*?)(\'|\")]#', $parsedUrl, $match)) {
            isset($_ENV[$match[3]]) ? $parsedUrl = $_ENV[$match[3]] : $parsedUrl = null;
        }

        is_string($parsedUrl) ? true : $parsedUrl = null;

        return $parsedUrl;
    }
}