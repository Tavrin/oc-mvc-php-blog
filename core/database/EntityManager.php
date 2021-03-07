<?php


namespace Core\database;


use Core\utils\JsonParser;
use http\Exception\RuntimeException;
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

    public static function getAllEntityData(): array
    {
        $entityData = [];
        foreach (glob(self::ENTITY_DATA_DIR . '*') as $currentFile)
        {
            if(is_dir($currentFile)) {
                continue;
            }

            $parsedData = JsonParser::parseFile($currentFile);
            $entityData[$parsedData['table']] = $parsedData;
        }

        return $entityData;
    }

    /**
     * @param object $entity
     */
    public function save(object $entity)
    {
        $entityData = $this->getEntityData(substr(strrchr(get_class($entity), '\\'), 1));

        $preparedStatement = $this->prepareInsert($entityData, $entity);
        $this->preparedStatements[] = $preparedStatement;
    }

    /**
     * @param $entity
     */
    public function remove($entity)
    {
        if (! is_object($entity)) {
            throw new \InvalidArgumentException('EntityManager->remove() Wrong Entity type given : ' . gettype($entity), 500);
        }
        $entityData = $this->getEntityData(substr(strrchr(get_class($entity), '\\'), 1));

        $preparedStatement = $this->prepareDelete($entityData, $entity);
        $this->preparedStatements[] = $preparedStatement;
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

        foreach ($entityData[EntityEnums::FIELDS_CATEGORY] as $attribute => $metadata)
        {
            $currentGetMethod = "get" . ucfirst($attribute);
            $currentField = $metadata[EntityEnums::FIELD_NAME];
            $placeholders .= ":{$currentField}, ";
            $rows .= "{$currentField}, ";

            if (EntityEnums::TYPE_DATE === $metadata[EntityEnums::FIELD_TYPE]) {
                $date = $entity->$currentGetMethod() ? date(EntityEnums::DEFAULT_DATE_FORMAT, $entity->$currentGetMethod()->getTimestamp()): null;
                $insertedData[":{$currentField}"] = $date;
                continue;
            }

            if (EntityEnums::TYPE_BOOL === $metadata[EntityEnums::FIELD_TYPE] && EntityEnums::TYPE_BOOL === gettype($boolValue = $entity->$currentGetMethod())) {
                true === $boolValue ? $insertedData[":{$currentField}"] = 1 : $insertedData[":{$currentField}"] = 0;
                continue;
            }

            if (EntityEnums::TYPE_ASSOCIATION === $metadata[EntityEnums::FIELD_TYPE]) {
                $insertedData[":{$currentField}"] = $entity->$currentGetMethod()->getId();
                continue;
            }

            $data = $entity->$currentGetMethod()?:null;
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

        $statement['prepare'] = 'INSERT INTO ' . $entityData[EntityEnums::TABLE_NAME] . " ({$rows}) VALUES ($placeholders)";
        $statement['execute'] = $insertedData;

        return $statement;
    }

    private function prepareDelete(array $entityData, object $entity): array
    {
        $statement['prepare'] = 'DELETE FROM ' . $entityData[EntityEnums::TABLE_NAME] . ' WHERE id = :id';
        $statement['execute'] = [':id'=>$entity->getId()];
        return $statement;
    }
}