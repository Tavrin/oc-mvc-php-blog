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

    /**
     * @var array|null
     */
    private ?array $preparedStatements;

    public const ENTITY_DATA_DIR = ROOT_DIR . '/config/entities/';

    public function __construct(string $url)
    {
        $this->connection = $this->createConnection($url);
        $this->preparedStatements = [];
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
        $entityName = ucfirst($entityName);
        if (isset ($this->entityData[$entityName])) {
            return $this->entityData[$entityName];
        }
        $currentFile = self::ENTITY_DATA_DIR . $entityName . '.json';

        if (file_exists($currentFile)) {
            $entityData = JsonParser::parseFile($currentFile);
            $this->entityData[$entityName] = $entityData;

            return $entityData;
        }

        return null;
    }

    public function save(object $entity)
    {
        $entityData = $this->getEntityData(substr(strrchr(get_class($entity), '\\'), 1));

        $preparedStatement = $this->prepareInsert($entityData, $entity);
        $this->preparedStatements[] = $preparedStatement;
    }

    public function remove($entity)
    {
        $entity;

    }

    /**
     * @return void
     */
    public function flush()
    {
        $connection = $this->getConnection();

        foreach ($this->preparedStatements as $key =>$statement) {
            $stmt = $connection->prepare($statement['prepare']);
            $stmt->execute($statement['execute']);
            unset($this->preparedStatements[$key]);
        }
    }

    public function getStatements(): ?array
    {
        return $this->preparedStatements;
    }

    /**
     * @param array $entityData
     * @param object $entity
     * @return array
     */
    private function prepareInsert(array $entityData, object $entity): array
    {
        $rows = '';
        $insertedData = [];
        $placeholders = '';

        foreach ($entityData['fields'] as $attribute => $metadata)
        {
            $method = "get" . ucfirst($attribute);
            $currentField = $metadata['fieldName'];
            $placeholders .= ":{$currentField}, ";
            $rows .= "{$currentField}, ";

            if ('datetime' === $metadata['type']) {
                $date = $entity->$method() ? date('Y-m-d H:i:s',$entity->$method()->getTimestamp()): null;
                $insertedData[":{$currentField}"] = $date;
                continue;
            }

            if ('tinyInt' === $metadata['type'] && 'boolean' === gettype($boolValue = $entity->$method())) {
                true === $boolValue ? $insertedData[":{$currentField}"] = 1 : $insertedData[":{$currentField}"] = 0;
                continue;
            }

            if ('association' === $metadata['type']) {
                $insertedData[":{$currentField}"] = $entity->$method()->getId();
                continue;
            }

            $data = $entity->$method()?:null;
            $insertedData[":{$currentField}"] = $data;
        }

        if (substr($rows, -2, 1) == ',')
        {
            $rows = rtrim($rows, ', ');

        }

        if (substr($placeholders, -2, 1) == ',')
        {
            $placeholders = rtrim($placeholders, ', ');

        }

        $statement['prepare'] = 'INSERT INTO ' . $entityData['table'] . " ({$rows}) VALUES ($placeholders)";
        $statement['execute'] = $insertedData;

        return $statement;
    }
}