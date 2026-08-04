<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\CommandInterface;
use SigmaPHP\Console\Console;
use SigmaPHP\Console\Argument;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Command Class.
 */
abstract class Command implements CommandInterface
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var array<Argument> $arguments
     */
    protected $arguments;

    /**
     * @var array<Option> $options
     */
    protected $options;

    /**
     * @var Console $console
     */
    protected $console;

    /**
     * Command Constructor.
     */
    public function __construct()
    {
        $this->arguments = [];
        $this->options = [];

        $this->console = new Console();

        $this->init();
    }

    /**
     * Initialize the command.
     *
     * @return void
     */
    abstract public function init();

    /**
     * Execute.
     *
     * @return void
     */
    abstract public function execute();

    /**
     * Set command's name.
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set command's description.
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get command's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get command's description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add argument.
     *
     * @param string $name
     * @param string $description
     * @param int $order
     * @param DataType $dataType
     * @return void
     */
    public function addArgument(
        $name,
        $description = '',
        $order = -1,
        $dataType = DataType::STRING
    ) {
        if (!is_int($order) || ($order == 0) || ($order == 1)) {
            throw new \InvalidArgumentException(
                'Argument\'s order must be an integer greater than 1!'
            );
        }

        // the order of the argument during the call, for example:
        //
        // COMMAND A B C
        //
        // you can decide the positions of each of A, B and C
        // or leave them with the default ordering
        //
        // please note: this order will be used to get the value from $argv
        $_order = ($order < 0) ? 2 + count($this->arguments) : $order;

        $this->arguments[$name] = new Argument(
            $name,
            $description,
            $_order,
            $dataType
        );
    }

    /**
     * Remove arguments.
     *
     * @param string $name
     * @return void
     */
    public function removeArgument($name)
    {
        unset($this->arguments[$name]);
    }

    /**
     * Get argument.
     *
     * @param string $name
     * @return mixed
     */
    public function getArgument($name)
    {
        return $this->arguments[$name];
    }

    /**
     * Get arguments.
     *
     * @return array<Argument>
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Add option.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param string $parameterOptionality
     * @param DataType $dataType
     * @param mixed $defaultValue
     * @return void
     */
    public function addOption(
        $name,
        $shortcut = '',
        $description = '',
        $parameterOptionality = Option::NONE,
        $dataType = DataType::STRING,
        $defaultValue = null,
    ) {
        $this->options[$name] = new Option(
            $name,
            $shortcut,
            $description,
            $parameterOptionality,
            $dataType,
            $defaultValue
        );
    }

    /**
     * Remove options.
     *
     * @param string $name
     * @return void
     */
    public function removeOption($name)
    {
        unset($this->options[$name]);
    }

    /**
     * Get option.
     *
     * @param string $name
     * @return mixed
     */
    public function getOption($name)
    {
        return $this->options[$name];
    }

    /**
     * Get options.
     *
     * @return array<Option>
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Define custom help section of the command.
     *
     * @return void
     */
    public function addHelpSection()
    {
        // ToDo
    }
}
