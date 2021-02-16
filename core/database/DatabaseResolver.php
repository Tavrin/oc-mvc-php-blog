<?php


namespace App\core\database;


use App\core\utils\JsonParser;
use App\core\database\EntityManager;

class DatabaseResolver
{
    public static function instanciateManager(): EntityManager
    {
        $url = self::getDatabaseUrl();

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
            $parsedUrl = $_ENV[$match[3]];
        }
        assert(is_string($parsedUrl));

        return $parsedUrl;
    }
}