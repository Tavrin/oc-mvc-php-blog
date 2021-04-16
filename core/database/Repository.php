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

    public function findBy(string $row, string $criteria, array $order = null, int $limit = null): array
    {
        if (isset($order['column'], $order['order']) ) {
            $query = $this->entityManager->getConnection()->prepare(self::SELECT_ALL . $this->entityData[EntityEnums::TABLE_NAME] . ' WHERE ' . $row . ' = :criteria' . ' ORDER BY ' . $order['column'] . ' ' . $order['order']);
        } else {
            $query = $this->entityManager->getConnection()->prepare(self::SELECT_ALL . $this->entityData[EntityEnums::TABLE_NAME] . ' WHERE ' . $row . ' = :criteria');
        }
        $query->execute([':criteria'=>$criteria]);
        $results = $query->fetchAll();
        return $this->hydrateEntity($results);
    }

    public function findOneBy(string $row, string $criteria)
    {
        $result = $this->findBy($row, $criteria, null,1);
        if (empty($result)) {
            return null;
        }
        return $result[0];
    }

    public function hydrateEntity(array $results): array
    {
        $entities = [];
        foreach ($results as $entityKey => $result) {
            $entities[$entityKey] = new $this->entityData[EntityEnums::ENTITY_CLASS];
            $entities[$entityKey]->setId($result[EntityEnums::ID_FIELD_NAME]) ;

            foreach ($this->entityData[EntityEnums::FIELDS_CATEGORY] as $key => $field) {
                $insertData = $result[$field[EntityEnums::FIELD_NAME]];
                $method = "set" . ucfirst($key);

                if ($field[EntityEnums::FIELD_TYPE] === EntityEnums::TYPE_DATE) {
                    $fieldData = new \DateTime($insertData);
                    $entities[$entityKey]->$method($fieldData);
                    continue;
                }

                if ($field[EntityEnums::FIELD_TYPE] === EntityEnums::TYPE_ASSOCIATION) {
                    $repository = $field[EntityEnums::ENTITY_REPOSITORY];
                    $repository = new $repository($this->entityManager);
                    $associatedEntity = $repository->findBy(EntityEnums::ID_FIELD_NAME, $insertData);
                    $entities[$entityKey]->$method($associatedEntity[0]);
                    continue;
                }

                if ($field[EntityEnums::FIELD_TYPE] === EntityEnums::TYPE_JSON) {
                    $decodedData = json_decode($insertData);
                    $entities[$entityKey]->$method($decodedData);
                    continue;
                }

                $entities[$entityKey]->$method($insertData);


            }
        }

        return $entities;
    }

    public function findAll(array $orderBy = null): array
    {
        if (isset($orderBy['column'], $orderBy['order']) ) {
            $query = $this->entityManager->getConnection()->prepare(self::SELECT_ALL . $this->entityData[EntityEnums::TABLE_NAME] . ' ORDER BY ' . $orderBy['column'] . ' ' . $orderBy['order']);
        } else {
            $query = $this->entityManager->getConnection()->prepare(self::SELECT_ALL . $this->entityData[EntityEnums::TABLE_NAME]);
        }

        $query->execute();
        $results = $query->fetchAll();
        return $this->hydrateEntity($results);
    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return $this->entityName;
    }
}