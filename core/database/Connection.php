<?php


namespace App\core\database;

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
        parent::__construct($dsn, (string) $user, (string) $password);
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
}