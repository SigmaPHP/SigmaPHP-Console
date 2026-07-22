<?php

namespace SigmaPHP\Console\Interfaces;

use SigmaPHP\Console\Interfaces\ConsoleInterface;
use SigmaPHP\Console\Command;

/**
 * App Interface.
 */
interface AppInterface
{
    /**
     * Add command to app.
     *
     * @param Command $command
     * @return void
     */
    public function addCommand($command);

    /**
     * Load commands from directory.
     *
     * @param string $path
     * @param string $nameSpace
     * @return void
     */
    public function loadCommands($path, $nameSpace);

    /**
     * Check if command exists in app.
     *
     * @param string $commandName
     * @return bool
     */
    public function hasCommand($commandName);

    /**
     * Get command from app.
     *
     * @param string $name
     * @return Command
     */
    public function getCommand($name);

    /**
     * Get all commands from app.
     *
     * @return array<Command>
     */
    public function getCommands();

    /**
     * Remove command from app.
     *
     * @param string $commandName
     * @return void
     */
    public function removeCommand($commandName);

    /**
     * Check the provided arguments and options, make sure they are related
     * to the Command.
     *
     * @return bool
     */
    public function validateInput();

    /**
     * Run the app.
     *
     * @return int
     */
    public function run();

    /**
     * Add global option.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param string $validation 'regex pattern'
     * @return void
     */
    public function addGlobalOption(
        $name,
        $shortcut,
        $description,
        $validation
    );

    /**
     * Disable the default functions (version & help).
     *
     * @return void
     */
    public function disableDefaultFunctions();

    /**
     * Do actions before executing any command.
     *
     * @return void
     */
    public function beforeStart();

    /**
     * Do actions after completing execution any command.
     *
     * @return void
     */
    public function afterComplete();
}
