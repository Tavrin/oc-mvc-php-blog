<?php

namespace Migrations;

use Core\database\Migration;

class Version1620082813 extends Migration
{
    public function getSQL()
    {
        return $this->query('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL,email VARCHAR(255) NOT NULL,content VARCHAR(255) NOT NULL,created_at DATETIME NOT NULL,slug VARCHAR(255) NOT NULL,uuid VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
}