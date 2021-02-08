<?php


namespace App\core\commands;


abstract class Command
{
    protected ?string $name;
    protected ?string $alias;
    protected ?string $description;
    protected array $arguments;

    /**
     * Command constructor.
     */
    public function __construct()
    {
        $this->arguments = [];
        $this->name = null;
        $this->alias = null;
        $this->description = null;
        $this->configure();
    }

    public function configure()
    {

    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $alias
     * @return $this
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $argument
     * @return $this|false
     */
    public function addArgument(string $argument)
    {
        if (in_array($argument, $this->arguments)) {
            return false;
        }

        array_push($this->arguments, $argument);
        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    public function hasArgument(string $argument)
    {
        return in_array($argument, $this->arguments);
    }

    public function run()
    {
        $this->execute();
    }

    protected function execute()
    {
    }
}