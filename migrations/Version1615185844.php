<?php

namespace Migrations;

use Core\database\Migration;

class Version1615185844 extends Migration
{
    public function getSQL()
    {
        return $this->query('CREATE TABLE test_table_deux (id INT AUTO_INCREMENT NOT NULL, string_id VARCHAR(255) NULL,text_id LONGTEXT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
}