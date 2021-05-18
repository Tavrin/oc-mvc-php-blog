<?php


namespace Core\database;


abstract class Repository
{

    protected ?EntityManager $entityManager = null;

    protected string $entityName;

    protected ?array $entityData;

    protected const SELECT_ALL = 'SELECT * FROM ';

    public function __construct(?EntityManager $entityManager, string $entityName)
    {
        if (null == $entityManager) {
            $entityManager = DatabaseResolver::instantiateManager();
        }
        if (!empty($entityManager)) {
            $this->entityManager = $entityManager;
        }

        $this->entityData = $this->entityManager->getEntityData($entityName);
        $this->entityName = $entityName;
    }

    /**
     * @throws \Exception
     */
    public function findBy(string $row, string $criteria, string $column = null, string $order = null, int $limit = null, int $offset = 0): array
    {
        $statement = self::SELECT_ALL . $this->entityData[EntityEnums::TABLE_NAME] . ' WHERE ' . $row . ' = :criteria';

        $statement = $this->setOptionalStatements($statement, $column, $order, $limit, $offset);

        $query = $this->entityManager->getConnection()->prepare($statement);
        $query->execute([':criteria'=>$criteria]);
        $results = $query->fetchAll();
        return $this->hydrateEntity($results);
    }

    private function setOptionalStatements(string $statement, string $column = null, string $order = null, int $limit = null, int $offset = 0): string
    {
        if (isset($column, $order)) {
            $statement .= ' ORDER BY ' . $column. ' ' . $order;
        }
        if (isset($limit)) {
            $statement .= ' LIMIT ' . $offset . ',' . $limit;
        }

        return $statement;
    }

    public function findOneBy(string $row, string $criteria)
    {
        $result = $this->findBy($row, $criteria, null, null,1);
        if (empty($result)) {
            return null;
        }
        return $result[0];
    }

    /**
     * @throws \Exception
     */
    public function hydrateEntity(array $results): array
    {
        $entities = [];
        foreach ($results as $entityKey => $result) {
            $entities[$entityKey] = new $this->entityData[EntityEnums::ENTITY_CLASS];
            $entities[$entityKey]->setId($result[EntityEnums::ID_FIELD_NAME]) ;

            foreach ($this->entityData[EntityEnums::FIELDS_CATEGORY] as $key => $field) {
                $method = "set" . ucfirst($key);
                $property = $this->setProperty($result, $field);
                $entities[$entityKey]->$method($property);
            }
        }

        return $entities;
    }

    private function setProperty($result, $field)
    {
        if (!isset($result[$field[EntityEnums::FIELD_NAME]])) {
            return null;
        }

        $insertData = $result[$field[EntityEnums::FIELD_NAME]];

        if ($field[EntityEnums::FIELD_TYPE] === EntityEnums::TYPE_DATE) {
            $insertData = new \DateTime($insertData);
        }

        if ($field[EntityEnums::FIELD_TYPE] === EntityEnums::TYPE_ASSOCIATION) {
            $insertData = $this->setAssociation($field, $insertData);
        }

        if ($field[EntityEnums::FIELD_TYPE] === EntityEnums::TYPE_JSON) {
            $insertData = json_decode($insertData);
        }

        if ($field[EntityEnums::FIELD_TYPE] === EntityEnums::TYPE_BOOLEAN || $field[EntityEnums::FIELD_TYPE] === EntityEnums::TYPE_BOOL) {
            1 == $insertData ? $insertData = true : $insertData =  false;
        }

        return $insertData;
    }

    private function setAssociation($field, $insertData)
    {
        $repository = $field[EntityEnums::ENTITY_REPOSITORY];
        $repository = new $repository($this->entityManager);
        $associatedEntity = $repository->findBy(EntityEnums::ID_FIELD_NAME, $insertData);
        if (isset($associatedEntity) && !empty($associatedEntity)) {
            return $associatedEntity[0];
        } else {
            return null;
        }
    }

    public function findAll(string $column = null, string $order = null, int $limit = null, int $offset = 0): array
    {
        $statement = self::SELECT_ALL . $this->entityData[EntityEnums::TABLE_NAME];
        $statement = $this->setOptionalStatements($statement, $column, $order, $limit, $offset);

        $query = $this->entityManager->getConnection()->prepare($statement);
        $query->execute();
        $results = $query->fetchAll();
        return $this->hydrateEntity($results);
    }

    public function count(string $row = null, string $criteria = null): array
    {
        $statement = 'SELECT count(*) FROM ' . $this->entityData[EntityEnums::TABLE_NAME];

        if (isset($row) && isset($criteria)) {
            $statement .= ' WHERE ' . $row . '= :criteria';
        }

        $query = $this->entityManager->getConnection()->prepare($statement);
        $query->execute([':criteria'=>$criteria]);
        return $query->fetchAll()[0];
    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return $this->entityName;
    }
}