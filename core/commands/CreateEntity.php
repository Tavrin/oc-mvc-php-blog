<?php


namespace Core\commands;


use Core\database\EntityManager;

class CreateEntity extends Command
{
    protected const BREAK_KEYWORD = 'stop';
    protected const ENTITY_CONFIG_DIR = ROOT_DIR . '/config/entities/';
    protected const ENTITY_REPO_DIR = ROOT_DIR . '/src/Repository/';
    protected const ENTITY_DIR = ROOT_DIR . '/src/Entity/';

    public function configure()
    {
        $this->setName('CreateEntity')
            ->setAlias('create:e')
            ->setDescription('Crée une entité');
    }

    public function execute()
    {
        $entityData = EntityManager::getAllEntityData();
        $newData = $this->getNewEntityData($entityData);

        $this->createEntityFile($newData);
        $this->createJsonFile($newData);
        $this->createRepoFile($newData);

        echo 'entity ' . $newData['name'] . '\'s config, entity and repository files are created' . PHP_EOL;
        exit();
    }

    private function getInput(): ?string
    {
        $line = trim(fgets(STDIN)); // reads one line from STDIN
        if (empty($line) || self::BREAK_KEYWORD === $line) {
            return null;
        }

        return lcfirst($line);
    }

    private function getNewEntityData(array $entityData): array
    {

        $newEntity = [];
        echo 'New Entity' . PHP_EOL . PHP_EOL;
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
            echo '-------------------------------------' . PHP_EOL;
        }

        return $newEntity;
    }

    private function createRepoFile(array $newData)
    {
        $doubleLine = PHP_EOL . PHP_EOL;
        $data = "<?php" . $doubleLine . "namespace App\Repository;" . $doubleLine . 'use Core\database\EntityManager;
use Core\database\Repository;

class ' . $newData['name'] . 'Repository extends Repository
{
    public function __construct(?EntityManager $entityManager = null)
    {
        parent::__construct($entityManager, \'' . $newData['name'] . '\');
    }
}';
        file_put_contents(self::ENTITY_REPO_DIR . ucfirst($newData['name']) .'Repository.php', $data, );
    }

    private function createJsonFile(array $newData)
    {
        file_put_contents(self::ENTITY_CONFIG_DIR . ucfirst($newData['name']) . '.json', json_encode($newData, JSON_PRETTY_PRINT));
    }

    private function createEntityFile(array $newData)
    {
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
     private $id;

';

        foreach ($newData['fields'] as $fieldName => $field) {
            if ('association' === $field['type']) {
                $type = ucfirst($field['associatedEntity']);
            } else {
                $type = $field['type'];
            }

            $data .=
        '    /**' . PHP_EOL .
     '    * @var ' . $type . PHP_EOL .
     '    */' . PHP_EOL .
     '    private $' . $fieldName . ';' . $doubleLine;
        }

        $data .= '    public function getId(): ?int
        {
            return $this->id;
        }
    
        public function setId(int $id)
        {
            $this->id = $id;
        }';

        foreach ($newData['fields'] as $fieldName => $field) {
            if ('association' === $field['type']) {
                $type = ucfirst($field['associatedEntity']);
            } else {
                $type = $field['type'];
            }

            $data .=
                '    public function get' . ucfirst($fieldName) . '(): ?' . $type . PHP_EOL .
                '    {' . PHP_EOL .
                '        return $this->' . $fieldName . ';' . PHP_EOL .
                '    }' . $doubleLine .
                '    public function set' . ucfirst($fieldName) . '(' . $type . ' $' . $fieldName . ')' . PHP_EOL .
                '    {' . PHP_EOL .
                '        $this->' . $fieldName . ' = $' . $fieldName . ';' . PHP_EOL .
                '    }' . $doubleLine;
        }

        $data .= '}';

        file_put_contents(self::ENTITY_DIR . ucfirst($newData['name']) . '.php', $data);
    }
}