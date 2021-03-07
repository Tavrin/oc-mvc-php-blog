<?php


namespace Core\commands;

define('ROOT_DIR', dirname(__DIR__) . '/../');


abstract class Command
{
    protected ?string $name;
    protected ?string $alias;
    protected ?string $description;
    protected array $arguments;
    protected array $options;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $alias
     * @return $this
     */
    public function setAlias(string $alias): Command
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $argument
     * @param string|null $description
     * @return $this|false
     */
    public function addArgument(string $argument, string $description = null)
    {
        if (in_array($argument, $this->arguments)) {
            return false;
        }
        $argumentData['name'] = $argument;
        $argumentData['description'] = $description?? null;

        $this->arguments[$argument] = $argumentData;
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