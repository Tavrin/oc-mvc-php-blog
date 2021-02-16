<?php


namespace App\core\database;

use App\core\database\Connection;

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
}