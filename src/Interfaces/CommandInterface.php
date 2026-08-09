<?php

namespace SigmaPHP\Console\Interfaces;

use SigmaPHP\Console\Argument;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

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
     * A proxy for the execution method to force global settings and options.
     *
     * @return void
     */
    public function executionHandler();

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
     * Remove arguments.
     *
     * @param string $name
     * @return void
     */
    public function removeArgument($name);

    /**
     * Get argument.
     *
     * @param string $name
     * @return mixed
     */
    public function getArgument($name);

    /**
     * Get arguments.
     *
     * @return array<Argument>
     */
    public function getArguments();

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
    );

    /**
     * Remove options.
     *
     * @param string $name
     * @return void
     */
    public function removeOption($name);

    /**
     * Get option.
     *
     * @param string $name
     * @return mixed
     */
    public function getOption($name);

    /**
     * Get options.
     *
     * @return array<Option>
     */
    public function getOptions();

    /**
     * Process the input.
     *
     * This method is important specially before execution to get the arguments
     * and options values before execution.
     *
     * @return void
     */
    public function processInput();

    /**
     * Help option's handler.
     *
     * @return void
     */
    public function help();
}
