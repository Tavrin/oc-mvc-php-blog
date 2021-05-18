<?php

namespace App\Repository;

use Core\database\EntityManager;
use Core\database\Repository;

class MessageRepository extends Repository
{
    public function __construct(?EntityManager $entityManager = null)
    {
        parent::__construct($entityManager, 'message');
    }
}