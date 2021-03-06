<?php


namespace Core\database;

use Core\Kernel;
use PDO;

class Connection extends PDO
{
    /**
     * @var array
     */
    protected $params;

    public function __construct(string $dsn, string $user, string $password, array $params)
    {
        $this->params = $params;

        try {
           return parent::__construct($dsn, (string) $user, (string) $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function query($statement)
    {
        $statement = parent::query($statement);
        return $statement;
    }

    public function prepare($query, $options = array())
    {
        $statement = parent::prepare($query, $options);

        return $statement;
    }

    public function exec($statement)
    {
        $result = parent::exec($statement);

        return $result;
    }

    public function delete($table, $conditions)
    {
        $statement = $this->prepare('DELETE FROM' . $table . 'WHERE' . implode(' AND ', $conditions));

        return $this->exec($statement);

    }
}