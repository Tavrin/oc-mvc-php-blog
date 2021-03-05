<?php


namespace Core\database;


use Core\utils\JsonParser;
use PDO;

class EntityManager
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var array
     */
    private array $entityData;

    public const ENTITY_DATA_DIR = ROOT_DIR . '/config/entities/';

    public function __construct(string $url)
    {
        $this->connection = $this->createConnection($url);
    }

    protected function createConnection(string $url): Connection
    {
        return Driver::getConnection($url);
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @param string $entityName
     * @return array|null
     */
    public function getEntityData(string $entityName): ?array
    {

        if (isset ($this->entityData[$entityName])) {
            return $this->entityData[$entityName];
        }
        $currentFile = self::ENTITY_DATA_DIR . $entityName . '.json';

        if (file_exists($currentFile)) {
            $entityData = JsonParser::parseFile($currentFile);
            $this->entityData[$entityName] = $entityData;
            dump($this->entityData);

            return $entityData;
        }

        return null;
    }

    public function save($entity)
    {

    }

    public function remove($entity)
    {
        $entity;

    }
}