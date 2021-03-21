<?php

namespace Migrations;

use Core\database\Migration;

class Version1615399863 extends Migration
{
    public function getSQL()
    {
        return $this->query('CREATE TABLE test_table (id INT AUTO_INCREMENT NOT NULL, int_field INT NULL,string_field VARCHAR(255) NOT NULL,cat_id INT NOT NULL,post_id INT NULL,FOREIGN KEY (cat_id) REFERENCES category (id),FOREIGN KEY (post_id) REFERENCES post (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
}