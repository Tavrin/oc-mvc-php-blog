<?php


namespace App\src\Repository;


use App\core\database\EntityManager;
use App\core\database\Repository;

class PostRepository extends Repository
{
    public function __construct(?EntityManager $entityManager = null)
    {
        parent::__construct($entityManager, "Post");
    }
}