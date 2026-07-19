<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\CommandInterface;

/**
 * Command Class.
 */
abstract class Command implements CommandInterface
{
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
    public function arguments($name)
    {

    }

    /**
     * Get Option/s.
     *
     * @param string $name
     * @return mixed
     */
    public function options($name)
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

    }

    /**
     * Set command's description.
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {

    }

    /**
     * Get command's name.
     *
     * @return string
     */
    public function getName()
    {

    }

    /**
     * Get command's description.
     *
     * @return string
     */
    public function getDescription()
    {

    }

    /**
     * Define custom help section of the command.
     *
     * @return void
     */
    public function defineCustomHelp()
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
     * @param regex $validation
     * @return void
     */
    public function addOption($name, $shortcut, $description, $validation)
    {

    }
}
