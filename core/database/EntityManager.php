<?php


namespace App\core\database;


class EntityManager
{
    private $connection;

    public function __construct(string $url)
    {
        $this->connection = $this->createConnection($url);
    }

    protected function createConnection(string $url)
    {
        return Driver::connect($url);
    }
}