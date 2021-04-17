<?php


namespace Core\commands;


use Core\database\EntityEnums;
use Core\database\EntityManager;
use DateTime;

class CreateEntityCommand extends Command
{
    protected const ENTITY_CONFIG_DIR = ROOT_DIR . '/config/entities/';
    protected const ENTITY_REPO_DIR = ROOT_DIR . '/src/Repository/';
    protected const ENTITY_DIR = ROOT_DIR . '/src/Entity/';

    public function configure()
    {
        $this->setName('CreateEntity')
            ->setAlias('create:e')
            ->setDescription('Crée une entité')
            ->addOption('newtable', 'creates a migration file of the entity, ready to be inserted into the database');
    }

    public function execute()
    {
        $entityData = EntityManager::getAllEntityData();

        echo 'New Entity' . PHP_EOL . PHP_EOL;

        $newData = $this->getNewEntityData($entityData);

        $this->createEntityFile($newData);
        $this->createJsonFile($newData);
        $this->createRepoFile($newData);

        if ($this->options['newtable']['value'] === true) {
            $this->createMigrationFile($newData);
        }

        echo 'entity ' . $newData['name'] . '\'s config, entity and repository files are created' . PHP_EOL;
        exit();
    }

    private function getNewEntityData(array $entityData): array
    {

        $newEntity = [];
        echo 'Entity name : ';
        $line = $this->getInput();

        if (isset($entityData[$line])) {
            echo 'Entity already exists, exciting' . PHP_EOL;
            exit();
        }

        $newEntity['name'] = $line;
        $newEntity['repository'] = 'App\\Repository\\' . ucfirst($newEntity['name']) . 'Repository';
        $newEntity['entity'] = 'App\\Entity\\' . ucfirst($newEntity['name']);
        echo 'Entity Database Table : ';
        $line = $this->getInput();
        $newEntity['table'] = $line;
        $newEntity['id']['type'] = 'integer';

        echo PHP_EOL . 'Entity fields, type \'' . self::BREAK_KEYWORD . '\' when all the fields are added : ' . PHP_EOL;

        while (true) {

            $currentField = [];
            echo 'Field name : ';
            $line = $this->getInput();

            if (empty($line)) {
                break;
            }

            $newEntity['fields'][$line] = null;
            $currentField['name'] = $line;

            echo 'Database field name : ';
            $line = $this->getInput();
            if (empty($line)) {
                break;
            }

            $newEntity['fields'][$currentField['name']]['fieldName'] = $line;
            $currentField['fieldName'] = $line;

            echo 'Field type : ';
            $line = $this->getInput();
            if (empty($line)) {
                break;
            }

            $newEntity['fields'][$currentField['name']]['type'] = $line;
            $currentField['type'] = $line;

            if ('association' === $currentField['type']) {
                echo 'Associated Entity : ';
                $line = $this->getInput();
                if (empty($line)) {
                    break;
                }

                if (!isset($entityData[$line])) {
                    echo 'Associated Entity doesn\'t exist, exciting' . PHP_EOL;
                    exit();
                }
                $newEntity['fields'][$currentField['name']]['associatedEntity'] = $line;
                $newEntity['fields'][$currentField['name']]['repository'] = $entityData[$line]['repository'];
                $newEntity['fields'][$currentField['name']]['entity'] = $entityData[$line]['entity'];
                $newEntity['hasAssociations'] = true;
            }

            echo 'Nullable(yes/no) : ';
            $line = $this->getInput();
            if (empty($line)) {
                break;
            } elseif ('yes' === $line || 'y' === $line) {
                $line = true;
            } elseif ('no' === $line || 'n' === $line) {
                $line = false;
            } else {
                echo 'Must be true or false' . PHP_EOL;
                exit();
            }

            $newEntity['fields'][$currentField['name']]['nullable'] = $line;
            echo '-------------------------------------' . PHP_EOL;
        }

        return $newEntity;
    }

    private function createRepoFile(array $newData)
    {
        echo 'Creating Repository file : ' .PHP_EOL;

        $doubleLine = PHP_EOL . PHP_EOL;
        $data = "<?php" . $doubleLine . "namespace App\Repository;" . $doubleLine . 'use Core\database\EntityManager;
use Core\database\Repository;

class ' . ucfirst($newData['name']) . 'Repository extends Repository
{
    public function __construct(?EntityManager $entityManager = null)
    {
        parent::__construct($entityManager, \'' . $newData['name'] . '\');
    }
}';
        $result = file_put_contents(self::ENTITY_REPO_DIR . ucfirst($newData['name']) .'Repository.php', $data, );

        if (false === $result) {
            echo 'An error happened during the Entity Repository file creation, exiting.' . PHP_EOL;
            exit();
        } else {
            echo 'Entity Repository file successfully saved.' . PHP_EOL;
        }
    }

    /**
     * @param array $newData
     */
    private function createJsonFile(array $newData)
    {
        echo 'Creating Json Config file : ' .PHP_EOL;

        $result = file_put_contents(self::ENTITY_CONFIG_DIR . ucfirst($newData['name']) . '.json', json_encode($newData, JSON_PRETTY_PRINT));

        if (false === $result) {
            echo 'An error happened during the Entity json Config file creation, exiting.' . PHP_EOL;
            exit();
        } else {
            echo 'Entity json Config file successfully saved.' . PHP_EOL;
        }
    }

    /**
     * @param array $newData
     */
    private function createEntityFile(array $newData)
    {
        echo 'Creating Entity file : ' .PHP_EOL;

        $doubleLine = PHP_EOL . PHP_EOL;
        $data = '<?php '. $doubleLine . 'namespace App\Entity;' . PHP_EOL;

        foreach ($newData['fields'] as $field) {
            if ('association' === $field['type']) {
                $data .= 'use ' . $field['entity'] . ';' . PHP_EOL;
            }

            $data .= PHP_EOL;
        }

        
        
        $data .= 'class ' . ucfirst($newData['name']) . PHP_EOL .
'{
     /**
     * @var int
     */
     private int $id;

';

        foreach ($newData['fields'] as $fieldName => $field) {
            $type = $this->getEntityTypes($field);
            $data .=
        '    /**' . PHP_EOL .
     '    * @var ' . $type['$phpDoctype'] . PHP_EOL .
     '    */' . PHP_EOL .
     '    private ' . $type['type'] . ' $' . $fieldName . ';' . $doubleLine;
        }

        $data .= '    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }' . $doubleLine;

        foreach ($newData['fields'] as $fieldName => $field) {
            $type = $this->getEntityTypes($field);
            $data .=
                '    public function get' . ucfirst($fieldName) . '(): ' . $type['type'] . PHP_EOL .
                '    {' . PHP_EOL .
                '        return $this->' . $fieldName . ';' . PHP_EOL .
                '    }' . $doubleLine .
                '    public function set' . ucfirst($fieldName) . '(' . $type['type'] . ' $' . $fieldName . ')' . PHP_EOL .
                '    {' . PHP_EOL .
                '        $this->' . $fieldName . ' = $' . $fieldName . ';' . PHP_EOL .
                '    }' . $doubleLine;
        }

        $data .= '}';

        $result = file_put_contents(self::ENTITY_DIR . ucfirst($newData['name']) . '.php', $data);

        if (false === $result) {
            echo 'An error happened during the Entity file creation, exiting.' . PHP_EOL;
            exit();
        } else {
            echo 'Entity file successfully saved.' . PHP_EOL;
        }
    }

    private function getEntityTypes(array $field): array
    {
        if ('association' === $field['type']) {
            $type = ucfirst($field['associatedEntity']);
        } else {
            $type = $field['type'];
        }

        if (true === $field['nullable']) {
            $types['$phpDoctype'] = $type . '|null';
            $type = '?' . $type;
        } else {
            $types['$phpDoctype'] = $type;
        }

        $types['type'] = $type;

        return $types;
    }

    private function createMigrationFile(array $newData)
    {
        $doubleLine = PHP_EOL . PHP_EOL;
        $timestamp = new DateTime();
        $timestamp = $timestamp->getTimestamp();
        $migrationTitle = 'Version' . $timestamp;

        $query = 'CREATE TABLE ' . $newData['table'] . ' (id INT AUTO_INCREMENT NOT NULL, ';

        $foreign = '';
        foreach ($newData['fields'] as $fieldName => $field) {
            $fieldName = $field['fieldName'];
            if (isset(EntityEnums::TYPE_CONVERSION[$field['type']])) {
                $type = EntityEnums::TYPE_CONVERSION[$field['type']];
            } else {
                $type = strtoupper($field['type']);
            }

            if ('association' == $field['type']) {
                $foreign .= 'FOREIGN KEY (' . $fieldName . ') REFERENCES ' . $field['associatedEntity'] . ' (id),';
            }

           true == $field['nullable'] ? $nullable = 'NULL' : $nullable = 'NOT NULL';
            $query .= $fieldName . ' ' . $type . ' ' . $nullable . ',';
        }

        $query .= $foreign . ' PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB';

        $data = '<?php' . $doubleLine .
            'namespace Migrations;' . $doubleLine .
            'use Core\database\Migration;' . $doubleLine .
            'class ' . $migrationTitle . ' extends Migration' . PHP_EOL .
            '{' . PHP_EOL .
            '    public function getSQL()' . PHP_EOL .
            '    {' . PHP_EOL .
            '        return $this->query(\'' . $query .'\');' . PHP_EOL .
            '    }' . PHP_EOL .
            '}';

        $result = file_put_contents(ROOT_DIR . '/migrations/'. $migrationTitle . '.php', $data);

        if (false === $result) {
            echo 'An error happened during the Entity file creation, exiting.' . PHP_EOL;
            exit();
        } else {
            echo 'Entity file successfully saved.' . PHP_EOL;
        }
    }
}