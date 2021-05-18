<?php


namespace Core\database;

use Core\Kernel;
use PDO;
use PDOException;
use RuntimeException;

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
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function query($statement)
    {
        try {
            return parent::query($statement);
        } catch (RuntimeException $e) {
            return $e;
        }
    }

    public function prepare($query, $options = array())
    {
        return parent::prepare($query, $options);
    }

    public function exec($statement)
    {
        return parent::exec($statement);
    }

    public function delete($table, $conditions)
    {
        $statement = $this->prepare('DELETE FROM' . $table . 'WHERE' . implode(' AND ', $conditions));

        return $this->exec($statement);

    }
}