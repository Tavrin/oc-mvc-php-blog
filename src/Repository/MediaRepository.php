<?php

namespace App\Repository;

use Core\database\EntityManager;
use Core\database\Repository;

class MediaRepository extends Repository
{
    public function __construct(?EntityManager $entityManager = null)
    {
        parent::__construct($entityManager, 'media');
    }
}