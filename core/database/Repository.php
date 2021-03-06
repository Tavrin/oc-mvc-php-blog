<?php


namespace Core\database;


abstract class Repository
{

    protected EntityManager $entityManager;

    protected string $entityName;

    protected ?array $entityData;

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
        $query = $this->entityManager->getConnection()->prepare('SELECT * FROM ' . $this->entityData['table'] . ' WHERE ' . $row . ' = :criteria');
        $query->execute([':criteria'=>$criteria]);
        $results = $query->fetchAll();
        return $this->hydrateEntity($results);
    }

    public function findOneBy(string $row, string $criteria)
    {
        $result = $this->findBy($row, $criteria, null,1);
        return $result[0];
    }

    public function hydrateEntity(array $results)
    {
        $entities = [];
        foreach ($results as $entityKey => $result) {
            $entities[$entityKey] = new $this->entityData['entity'];
            $entities[$entityKey]->setId($result['id']) ;

            foreach ($this->entityData['fields'] as $key => $field) {
                $insertData = $result[$field['fieldName']];
                $method = "set" . ucfirst($key);

                $r = new \ReflectionMethod($entities[$entityKey], $method);
                $params = $r->getParameters();
                foreach ($params as $param) {
                    //$param is an instance of ReflectionParameter
                    $type = $param->getType()->getName();
                }

                if (!empty($result[$field['fieldName']])) {
                    if ($field['type'] === 'datetime') {
                        $fieldData = new \DateTime($insertData);
                        $entities[$entityKey]->$method($fieldData);
                        continue;
                    }

                    if ($field['type'] == 'association') {
                        $repository = $field['repository'];
                        $repository = new $repository($this->entityManager);
                        $associatedEntity = $repository->findBy('id', $insertData);
                        $entities[$entityKey]->$method($associatedEntity[0]);
                        continue;
                    }

                    if ($field['type'] == 'json') {
                        $decodedData = json_decode($insertData);
                        $entities[$entityKey]->$method($decodedData);
                        continue;
                    }

                    $entities[$entityKey]->$method($insertData);
                }

            }
        }

        return $entities;
    }

    public function findAll(): array
    {
        $query = $this->entityManager->getConnection()->prepare('SELECT * FROM ' . $this->entityData['table']);
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