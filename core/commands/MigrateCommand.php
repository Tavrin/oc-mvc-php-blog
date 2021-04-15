<?php


namespace Core\commands;

use PDOException;
use function PHPUnit\Framework\assertInstanceOf;

class MigrateCommand extends Command
{
    protected const MIGRATION_ROOT= ROOT_DIR . 'migrations/';

    public function configure()
    {
        $this->setName('ExecuteMigration')
            ->setAlias('e:m')
            ->setDescription('Execute une migration vers la base de donnée depuis un fichier de migration')
            ->addArgument('version', 'version du fichier de migration à executer.')
            ->addOption('latest', 'Indique qu\'il faut executer la dernière version de migration');
    }

    public function execute()
    {
        if ($this->options['latest']['value'] == true) {
            $this->executeLatest();
        }
    }

    private function executeLatest()
    {
        $files = scandir(self::MIGRATION_ROOT, SCANDIR_SORT_DESCENDING);
        $newestFile = $files[0];

        $class = rtrim($newestFile, '.php');

        $class = 'Migrations\\' . $class;

        $instantiatedClass = new $class();
        $result = $instantiatedClass->getSQL();

        if ($result instanceof PDOException) {
            echo 'Une erreur est survenue durant la migration: ' . $result->getMessage() . PHP_EOL;
            exit();
        } else {
            echo 'Migration terminée.' . PHP_EOL;
        }
    }
}