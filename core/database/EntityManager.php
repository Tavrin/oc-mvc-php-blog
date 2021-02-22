<?php


namespace App\core\database;


use PDO;

class EntityManager
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(string $url)
    {
        $this->connection = $this->createConnection($url);
    }

    protected function createConnection(string $url): Connection
    {
        return Driver::getConnection($url);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function findAll(string $entity)
    {
        $query = $this->getConnection()->prepare('SELECT * FROM ' . $entity);
        $query->execute();
        return $query->fetchAll();

    }

    public function findBy(string $entity, string $row, string $criteria, array $order = null, int $limit = null)
    {
        $query = $this->getConnection()->prepare('SELECT * FROM ' . $entity . ' WHERE ' . $row . '= :criteria');
        $query->execute([':criteria'=>$criteria]);
        return $query->fetchAll();
    }
}