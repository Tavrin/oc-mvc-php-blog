<?php


namespace Core\commands;


class HelloWorldCommand extends Command
{
    public function configure()
    {
        $this->setName('HelloWorld')
            ->setAlias('hlw')
            ->setDescription('écrit Hello World')
            ->addArgument('test', 'ne sert à rien')
            ->addArgument('testa', )
            ->addArgument('testo', 'lkfsjdfslfk' );

    }

    public function execute()
    {
        echo "Hello World" . PHP_EOL;
    }
}