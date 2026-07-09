<?php

namespace SigmaPHP\Console\Interfaces;

use SigmaPHP\Console\Command;

/**
 * App Interface.
 */
interface App
{
    /**
     * Initialize the app.
     *
     * @param callable $callback
     * @return void
     */
    public function init($callback);

    /**
     * Load commands from directory.
     *
     * @param string $path
     * @return void
     */
    public function loadCommands($path);

    /**
     * Add command to app.
     *
     * @param Command $command
     * @return void
     */
    public function addCommand($command);

    /**
     * Run the app.
     *
     * @return int
     */
    public function run();

    /**
     * Check if command exists in app.
     *
     * @param string $commandName
     * @return bool
     */
    public function hasCommand($commandName);

    /**
     * Get command/s from app.
     *
     * @param string $name
     * @return array<Command>|Command
     */
    public function getCommands($name);

    /**
     * Disable the default functions (version & help).
     *
     * @return void
     */
    public function disableDefaultFunctions();

    /**
     * Add app header/title, that could include name, copy rights
     * some ascii-art, whatever :)
     *
     * @param callable $callback
     * @return void
     */
    public function addHeader($callback);

    /**
     * Do actions before executing any command.
     *
     * @param callable $callback
     * @return void
     */
    public function beforeStart($callback);

    /**
     * Do actions after completing execution any command.
     *
     * @param callable $callback
     * @return void
     */
    public function afterComplete($callback);
}
