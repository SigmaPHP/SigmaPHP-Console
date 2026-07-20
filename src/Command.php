<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\CommandInterface;

/**
 * Command Class.
 */
abstract class Command implements CommandInterface
{
    /**
     * @var array $arguments
     */
    protected $arguments;

    /**
     * @var array $options
     */
    protected $options;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * App Constructor.
     */
    public function __construct()
    {
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
     * Get Argument/s.
     *
     * @param string $name
     * @return mixed
     */
    public function arguments($name = '')
    {

    }

    /**
     * Get Option/s.
     *
     * @param string $name
     * @return mixed
     */
    public function options($name = '')
    {

    }

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
     * Define custom help section of the command.
     *
     * @return void
     */
    public function addHelpSection()
    {

    }

    /**
     * Add argument.
     *
     * @param string $name
     * @param string $description
     * @param regex $validation
     * @return void
     */
    public function addArg($name, $description, $validation)
    {

    }

    /**
     * Add option.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param string $validation 'regex pattern'
     * @return void
     */
    public function addOption($name, $shortcut, $description, $validation)
    {

    }
}
