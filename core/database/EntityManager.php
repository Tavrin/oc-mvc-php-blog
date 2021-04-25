<?php


namespace Core\database;


use Core\utils\ClassUtils;
use Core\utils\JsonParser;

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
            $entityData[$parsedData['name']] = $parsedData;
        }

        return array_combine(array_map('strtolower', array_keys($entityData)), $entityData);
    }

    public function getChildrenEntities(object $entity, array $entityData = null): ?array
    {
        if (null === $entityData) {
            $className = ClassUtils::getClassNameFromObject($entity);
            $entityData = $this->getEntityData($className);
        }

        if (!isset($entityData['childrenEntities'])) {
            return null;
        }

        $childrenEntities = [];
        $parentEntityId = $entity->getId();
        foreach ($entityData['childrenEntities'] as $childrenEntity) {
            $childrenEntityData = $this->getEntityData($childrenEntity['associatedEntity']);
            foreach ($childrenEntityData['fields'] as $field) {
                if ('association' !== $field['type'] || ('association' === $field['type'] && $entityData['name'] !== $field['associatedEntity'])) {
                    continue;
                }

                $parentTableName = $field['fieldName'];
                $childrenEntityRepo = new $childrenEntityData['repository'];
                 $results = $childrenEntityRepo->findBy($parentTableName, $parentEntityId);
                 foreach ($results as $key => $result) {
                     $childrenEntities[$key]['entity'] = $result;
                     $childrenEntities[$key]['data'] = $childrenEntityData;
                 }
            }
        }
        return $childrenEntities;
    }

    /**
     * @param object $entity
     */
    public function save(object $entity)
    {
        $className = ClassUtils::getClassNameFromObject($entity);
        $currentEntityData = $this->getEntityData($className);

        $preparedStatement = $this->prepareInsert($currentEntityData, $entity, 'insert');
        $this->preparedStatements[] = $preparedStatement;
    }

    /**
     * @param object $entity
     */
    public function update(object $entity)
    {
        $className = ClassUtils::getClassNameFromObject($entity);
        $currentEntityData = $this->getEntityData($className);

        $preparedStatement = $this->prepareInsert($currentEntityData, $entity, 'update');
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
        $className = ClassUtils::getClassNameFromObject($entity);
        $currentEntityData = $this->getEntityData($className);

        if (isset($currentEntityData['childrenEntities'])) {

            $childrenEntities = $this->getChildrenEntities($entity, $currentEntityData);

            foreach ($childrenEntities as $childrenEntity) {
                $preparedStatement = $this->prepareDelete($childrenEntity['data'], $childrenEntity['entity']);
                $this->preparedStatements[] = $preparedStatement;
            }
        }

        $preparedStatement = $this->prepareDelete($currentEntityData, $entity);
        $this->preparedStatements[] = $preparedStatement;
    }

    /**
     * @return bool
     */
    public function flush(): bool
    {
        $connection = $this->getConnection();

        foreach ($this->preparedStatements as $key =>$statement) {
            $stmt = $connection->prepare($statement['prepare']);
            $stmt->execute($statement['execute']);
            unset($this->preparedStatements[$key]);
        }

        return true;
    }

    public function getStatements(): ?array
    {
        return $this->preparedStatements;
    }

    /**
     * @param array $entityData
     * @param object $entity
     * @param string $operation
     * @return array
     */
    private function prepareInsert(array $entityData, object $entity, string $operation): array
    {
        $rows = '';
        $insertedData = [];
        $placeholders = '';

        foreach ($entityData[EntityEnums::FIELDS_CATEGORY] as $attribute => $metadata)
        {
            $currentGetMethod = "get" . ucfirst($attribute);
            $currentField = $metadata[EntityEnums::FIELD_NAME];

            if ( 'insert' === $operation) {
                $placeholders .= ":{$currentField}, ";
                $rows .= "{$currentField}, ";
            }

            if('update' === $operation) {
                $rows .= "{$currentField} = :{$currentField} , ";
            }

            if (EntityEnums::TYPE_DATE === $metadata[EntityEnums::FIELD_TYPE]) {
                $date = $entity->$currentGetMethod() ? date(EntityEnums::DEFAULT_DATE_FORMAT, $entity->$currentGetMethod()->getTimestamp()): null;
                $insertedData[":{$currentField}"] = $date;
                continue;
            }

            if (EntityEnums::TYPE_JSON === $metadata[EntityEnums::FIELD_TYPE]) {
                $insertedData[":{$currentField}"] = json_encode($entity->$currentGetMethod())?:null;
                continue;
            }

            if ((EntityEnums::TYPE_BOOLEAN === $metadata[EntityEnums::FIELD_TYPE] || EntityEnums::TYPE_BOOL === $metadata[EntityEnums::FIELD_TYPE] ) && EntityEnums::TYPE_BOOLEAN === gettype($boolValue = $entity->$currentGetMethod())) {
                true === $boolValue ? $insertedData[":{$currentField}"] = 1 : $insertedData[":{$currentField}"] = 0;
                continue;
            }

            if (EntityEnums::TYPE_ASSOCIATION === $metadata[EntityEnums::FIELD_TYPE]) {
                $associatedEntity = $entity->$currentGetMethod();
                isset($associatedEntity) ? $insertedData[":{$currentField}"] = $associatedEntity->getId() : $insertedData[":{$currentField}"] = null;
                continue;
            }

            $data = $entity->$currentGetMethod()?? null;
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


        if ('insert' === $operation) {
            $statement['prepare'] = 'INSERT INTO ' . $entityData[EntityEnums::TABLE_NAME] . " ({$rows}) VALUES ($placeholders)";
        }

        if('update' === $operation) {
            $statement['prepare'] = 'UPDATE ' . $entityData[EntityEnums::TABLE_NAME] . " SET $rows WHERE id = :id";
            $insertedData[':id'] = $entity->getId();
        }

        $statement['execute'] = $insertedData;

        return $statement;
    }

    private function prepareDelete(array $entityData, object $entity): array
    {
        /** @noinspection SqlResolve */
        $statement['prepare'] = 'DELETE FROM ' . $entityData[EntityEnums::TABLE_NAME] . ' WHERE id = :id';
        $statement['execute'] = [':id'=>$entity->getId()];
        return $statement;
    }
}