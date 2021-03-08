<?php


namespace Core\database;

require dirname(__DIR__) . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(ROOT_DIR, '.env.local');

$dotenv->load();

abstract class Migration
{
    public $result;
    public Connection $connection;
    public function __construct()
    {
        $this->result = [];
        $url = DatabaseResolver::getDatabaseUrl();
        $this->connection = Driver::getConnection($url);
    }

    public function query(string $query)
    {
       return $this->connection->query($query);

    }

    public function getSQL()
    {

    }
}