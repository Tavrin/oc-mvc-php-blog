<?php


namespace Core\database;


use Core\utils\JsonParser;
use Core\database\EntityManager;

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

    public static function getDatabaseUrl(): ?string
    {
        $parsedUrl = JsonParser::parseFile(ROOT_DIR . '/config/database.json');

        if (empty($parsedUrl['database']['url'])) {
            return null;
        }

        $parsedUrl = $parsedUrl['database']['url'];
        if (preg_match('#\$_(ENV|SERVER)\[(\'|\")(.*?)(\'|\")]#', $parsedUrl, $match)) {
            isset($_ENV[$match[3]]) ? $parsedUrl = $_ENV[$match[3]] : $parsedUrl = null;
        }

        is_string($parsedUrl) ? true : $parsedUrl = null;

        return $parsedUrl;
    }
}