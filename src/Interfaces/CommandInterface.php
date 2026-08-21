<?php

namespace SigmaPHP\Console\Interfaces;

use SigmaPHP\Console\DataType;

/**
 * Command Interface.
 */
interface CommandInterface
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init();

    /**
     * Execute.
     *
     * @return void
     */
    public function execute();

    /**
     * Load the arguments and options.
     *
     * @return void
     */
    public function processInput();

    /**
     * A proxy for the execution method to force global settings and options.
     *
     * @return void
     */
    public function executionHandler();

    /**
     * Set app's name.
     *
     * @param string $appName
     * @return void
     */
    public function setAppName($appName);

    /**
     * Add argument.
     *
     * @param string $name
     * @param string $description
     * @param DataType $dataType
     * @return void
     */
    public function addArgument($name, $description, $dataType);

    /**
     * Remove argument.
     *
     * @param string $name
     * @return void
     */
    public function removeArgument($name);

    /**
     * Has argument.
     *
     * @param string $name
     * @return bool
     */
    public function hasArgument($name);

    /**
     * Get argument's value.
     *
     * @param string $name
     * @return mixed
     */
    public function getArgument($name);

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
        $shortcut,
        $description,
        $parameterOptionality,
        $dataType,
        $defaultValue
    );

    /**
     * Remove option.
     *
     * @param string $name
     * @return void
     */
    public function removeOption($name);

    /**
     * Has option.
     *
     * @param string $name
     * @return bool
     */
    public function hasOption($name);

    /**
     * Get option's value.
     *
     * @param string $name
     * @return mixed
     */
    public function getOption($name);

    /**
     * Help option's handler.
     *
     * @return void
     */
    public function help();

    /**
     * Check if no arguments were provided.
     *
     * @return bool
     */
    public function argumentsAreEmpty();

    /**
     * Check if no options were provided.
     *
     * @return bool
     */
    public function optionsAreEmpty();

    /**
     * Check if arguments and options were provided.
     *
     * @return bool
     */
    public function isEmpty();
}
