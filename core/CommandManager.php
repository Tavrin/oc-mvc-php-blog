<?php


namespace App\core;

require dirname(__DIR__) . '\vendor\autoload.php';

use App\core\utils\JsonParser;

class CommandManager
{
    private $commandList = [];
    private $argumentsList = [];

    public function __construct($command, $arguments)
    {
        $this->initialize($arguments);
        $this->runCommand($command);

    }

    private function initialize(array $arguments): void
    {
        $this->addCommands();
        $this->addArguments($arguments);
    }

    private function addCommands(): void
    {
        $commandList = JsonParser::parseFile(dirname(__DIR__). '/config/commands.json');

        foreach ($commandList as $command) {
            $this->commandList[$command['name']] = new $command['class'];
        }
    }

    private function addArguments(array $arguments): void
    {
        foreach ($arguments as $arg) {
            if (preg_match("#(.+?)=(.+)#", $arg, $parsedArgs)) {
                $this->argumentsList[$parsedArgs[1]] = $parsedArgs[2];
            }
        }
    }

    private function runCommand(string $command)
    {
        if(!key_exists($command, $this->commandList)) {
            echo "Cette commande n'existe pas";
            return;
        }
        $class = $this->commandList[$command];
        foreach ($this->argumentsList as $argument => $value) {
            if (!$class->hasArgument($argument)) {
                echo 'cet argument n\'existe pas: ' . $argument;
                return;
            }
        }

        $class->run($this->argumentsList);
    }
}

if (php_sapi_name() == 'cli') {
    if (!empty($argv[1])) {
        $commandName = $argv[1];
        $arguments = [];
        foreach ($argv as $key => $arg) {
            if ($key < 2) {
                continue;
            }

            $arguments[] = $arg;
        }

        $manager = new CommandManager($commandName, $arguments);

    } else {
        return printf('Pas de commande fournie');
    }

}