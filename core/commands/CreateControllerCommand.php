<?php


namespace Core\commands;


use Core\utils\JsonParser;
use SebastianBergmann\CodeCoverage\Report\PHP;

class CreateControllerCommand extends Command
{
    protected const ROUTES_CONFIG = ROOT_DIR . '/config/routes.json';
    protected const CONTROLLER_ROOT = ROOT_DIR . 'src/Controller/';
    protected const CONTROLLER_BASE_NAMESPACE = 'App\\Controller';

    private array $routes = [];
    private bool $setRoute = false;

    public function __construct()
    {
        $this->routes = JsonParser::parseFile(self::ROUTES_CONFIG);
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('CreateController')
            ->setAlias('create:c')
            ->setDescription('Crée un controller')
            ->addArgument('subfolder')
            ->addOption('setroute');
    }

    public function execute()
    {

        echo 'New Controller' . PHP_EOL . PHP_EOL;

        $newController = $this->getNewControllerData();

        $this->setController($newController);

        if (true === $this->options['setroute']['value']) {
            $this->setRoute($newController);
        }

        echo 'New Controller saved at :' . $newController['filepath'] . PHP_EOL;
        PHP_EOL;
        exit();

    }

    private function getNewControllerData(): array
    {
        $controllerPath = self::CONTROLLER_ROOT;
        if (!empty($subfolder = $this->arguments['subfolder']['value'])) {
            $controllerPath .= $subfolder . '/';
        }

        $newController = [];
        echo 'Controller name : ';
        $line = $this->getInput();

        if (file_exists($controllerPath = $controllerPath . ucfirst($line) . '.php')) {
            echo 'Controller already exists, aborting' . PHP_EOL;
            exit();
        }

        $newController['name'] = $line;
        $newController['filepath'] = $controllerPath;
        !empty($subfolder) ? $newController['namespace'] = self::CONTROLLER_BASE_NAMESPACE . '\\' . $subfolder : $newController['namespace'] = self::CONTROLLER_BASE_NAMESPACE;
        echo PHP_EOL . 'Adding arguments, press enter or type ' . self::BREAK_KEYWORD . 'when finished : ' . PHP_EOL;

        while (true) {
            $currentArg=[];
            echo 'Argument variable name : ';
            $line = $this->getInput();
            if (empty($line)) {
                break;
            }

            '$' == substr($line, 0, 1) ? $currentArg['name'] = $line : $currentArg['name'] = '$' . $line;
            $newController['arguments'][$currentArg['name']] = null;

            echo 'Argument type : ';
            $currentArg[$line] = $line = $this->getInput(true);
            if (empty($line)) {
                break;
            }

            $newController['arguments'][$currentArg['name']]['type'] = $line;

            echo '-------------------------------------' . PHP_EOL;
        }

        echo PHP_EOL;
        if (true === $this->options['setroute']['value']) {
            $this->setRoute = true;
            echo 'Adding a route :' . PHP_EOL . PHP_EOL;
            echo 'Route name : ';
            $routeName = $this->getInput();

            echo 'Route path : ';
            $path = $this->getInput();

            foreach ($this->routes as $route) {
                if ($route['route'] == $routeName) {
                    echo 'This route name is already defined, aborting' . PHP_EOL;
                    exit();
                }
                if ($route['path'] == $path) {
                    echo 'This route path is already defined, aborting' . PHP_EOL;
                    exit();
                }
            }
            $newController['newroute']['route'] = $routeName;
            '/' == substr($path, 0, 1) ? $newController['newroute']['path'] = $path : $newController['newroute']['path'] = '/' . $path;
            $newController['newroute']['controller'] = $newController['namespace'] . '\\' . ucfirst($newController['name']) . '::indexAction';
        }

        return $newController;
    }

    private function setController(array $controllerData)
    {
        $doubleLine = PHP_EOL . PHP_EOL;
        $arguments = '';
        if (!empty($controllerData['arguments'])) {
            foreach ($controllerData['arguments'] as $name => $argument) {
                $arguments .= $argument['type'] . ' ' . $name . ', ';
            }
        }

        $arguments = rtrim($arguments, ', ');

        $data = '<?php' . $doubleLine .
            'namespace ' . $controllerData['namespace'] . ';' . $doubleLine .
            'use Core\controller\Controller;' . $doubleLine .
            'class ' . ucfirst($controllerData['name']) . ' extends Controller' . PHP_EOL .
            '{' . PHP_EOL .
            '    public function indexAction(' . $arguments . ')' . PHP_EOL .
            '    {' . PHP_EOL .
            '    }' . $doubleLine .
            '    public function newAction(' . $arguments . ')' . PHP_EOL .
            '    {' . PHP_EOL .
            '    }' . $doubleLine .
            '    public function showAction(' . $arguments . ')' . PHP_EOL .
            '    {' . PHP_EOL .
            '    }' . $doubleLine .
            '    public function editAction(' . $arguments . ')' . PHP_EOL .
            '    {' . PHP_EOL .
            '    }' . $doubleLine .
            '    public function deleteAction(' . $arguments . ')' . PHP_EOL .
            '    {' . PHP_EOL .
            '    }' . PHP_EOL .
            '}';

        $result = file_put_contents($controllerData['filepath'], $data);

        if (false === $result) {
            echo 'An error happened during the Entity file creation, exiting.' . PHP_EOL;
            exit();
        } else {
            echo 'Entity file successfully saved.' . PHP_EOL;
        }
    }

    private function setRoute(array $controllerData)
    {
        array_push($this->routes, $controllerData['newroute']);
        $result = file_put_contents(self::ROUTES_CONFIG, json_encode($this->routes, JSON_PRETTY_PRINT));

        if (false === $result) {
            echo 'An error happened during the Entity file creation, exiting.' . PHP_EOL;
            exit();
        } else {
            echo 'Entity file successfully saved.' . PHP_EOL;
        }
    }
}