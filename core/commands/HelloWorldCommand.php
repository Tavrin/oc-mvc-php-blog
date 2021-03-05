<?php


namespace Core\commands;


class HelloWorldCommand extends Command
{
    public function configure()
    {
        $this->setName('HelloWorld')
            ->setAlias('hlw')
            ->setDescription('écrit Hello World')
            ->addArgument('test');
    }

    public function execute()
    {
        echo "Hello World";
    }
}