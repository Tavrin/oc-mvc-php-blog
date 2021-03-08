<?php


namespace Core\commands;

define('ROOT_DIR', dirname(__DIR__) . '/../');


abstract class Command
{
    protected const BREAK_KEYWORD = 'stop';
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
        $this->options = [];
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
        $argumentData['value'] = null;

        $this->arguments[$argument] = $argumentData;
        return $this;
    }

    public function addOption(string $option, string $description = null)
    {
        if (in_array($option, $this->options)) {
            return false;
        }
        $optionData['name'] = $option;
        $optionData['description'] = $description?? null;
        $optionData['value'] = null;

        $this->options[$option] = $optionData;
        return $this;
    }


    public function setParemValues(array $arguments = null, array $options = null)
    {
        if (!empty($arguments)) {
            foreach ($arguments as $name => $argument) {
                $this->arguments[$name]['value'] = $argument;
            }
        }

        if (!empty($options)) {
            foreach ($options as $name => $option) {
                $this->options[$name]['value'] = $option;
            }
        }
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $argument
     * @return mixed
     */
    public function getArgument(string $argument)
    {
        return $this->arguments[$argument];
    }

    public function hasArgument(string $argument): bool
    {
        return isset($this->arguments[$argument]);
    }

    public function hasOption(string $option): bool
    {
        return isset($this->options[$option]);
    }

    public function run()
    {
        $this->execute();
    }


    protected function execute()
    {
    }


    protected function getInput(bool $noLower = false): ?string
    {
        $line = trim(fgets(STDIN)); // reads one line from STDIN
        if (empty($line) || self::BREAK_KEYWORD === $line) {
            return null;
        }

        false === $noLower ? $line = lcfirst($line) : true;
        return $line;
    }
}