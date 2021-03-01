<?php


namespace App\core\database;


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
        $query = $this->entityManager->getConnection()->prepare('SELECT * FROM ' . $this->entityName . ' WHERE ' . $row . ' = :criteria');
        $query->execute([':criteria'=>$criteria]);
        return $query->fetchAll();
    }

    public function findOneBy(string $row, string $criteria)
    {
        $results = $this->findBy($row, $criteria, null,1);
        return $this->hydrateEntity($results);
    }

    public function hydrateEntity(array $results)
    {
        $entities = [];
        foreach ($results as $entityKey => $result) {
            $entities[$entityKey] = new $this->entityData['entity'];
            $entities[$entityKey]->setId($result['id']) ;

            foreach ($this->entityData['fields'] as $key => $field) {
                $method = "set" . ucfirst($key);

                if (!empty($result[$field['fieldName']])) {
                    if ($field['type'] === 'datetime') {
                        $fieldData = new \DateTime($result[$field['fieldName']]);
                        $entities[$entityKey]->$method($fieldData);
                        continue;
                    }

                    if ($field['type'] == 'association') {
                        $repository = new $field['class'];
                        /* dd($repository->findAll($field['fieldName'])); */
                        $test = $repository->findBy('id', $result[$field['fieldName']]);
                        dd($test);
                    }

                    $entities[$entityKey]->$method($result[$field['fieldName']]);
                }

            }
        }

        return $entities;
    }

    public function findAll()
    {
        $query = $this->entityManager->getConnection()->prepare('SELECT * FROM ' . $this->entityName);
        $query->execute();
        return $query->fetchAll();

    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return $this->entityName;
    }
}