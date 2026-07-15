<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\AppInterface;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\DefaultCommands\Help;
use SigmaPHP\Console\DefaultCommands\Version;

/**
 * App Class.
 */
class App implements AppInterface
{
    /**
     * @var array<Command> $commands
     */
    protected $commands;

    /**
     * App Constructor.
     */
    public function __construct()
    {
        $this->commands = [
            'help' => new Help(),
            'version' => new Version()
        ];
    }

    /**
     * Initialize the app.
     *
     * @param callable $callback
     * @return void
     */
    public function init($callback)
    {
        $callback();
    }

    /**
     * Load commands from directory.
     *
     * @param string $path
     * @return void
     */
    public function loadCommands($path)
    {
        // !ToDo
    }

    /**
     * Add command to app.
     *
     * @param Command $command
     * @return void
     */
    public function addCommand($command)
    {
        $commandInst = new $command();

        if ($this->hasCommand($commandInst->getName())) {
            throw new \InvalidArgumentException(
                "Command {$command} already registered in the App!"
            );
        }

        $this->commands[$commandInst->getName()] = $commandInst;
    }

    /**
     * Check the provided arguments and options, make sure they are related
     * to the Command.
     *
     * @return bool
     */
    public function validateInput()
    {
        // !ToDo
    }

    /**
     * Run the app.
     *
     * @return int
     */
    public function run()
    {
        // !ToDo
    }

    /**
     * Check if command exists in app.
     *
     * @param string $name
     * @return bool
     */
    public function hasCommand($name)
    {
        return array_key_exists($name, $this->commands);
    }

    /**
     * Get command/s from app.
     *
     * @param string $name
     * @return array<Command>|Command
     */
    public function getCommands($name = '')
    {
        return (empty($name)) ? $this->commands : $this->commands[$name];
    }

    /**
     * Disable the default functions (version & help).
     *
     * @return void
     */
    public function disableDefaultFunctions()
    {
        unset($this->commands['help']);
        unset($this->commands['version']);
    }

    /**
     * Add app header/title, that could include name, copy rights
     * some ascii-art, whatever :)
     *
     * @param callable $callback
     * @return void
     */
    public function addHeader($callback)
    {
        $callback();
    }

    /**
     * Do actions before executing any command.
     *
     * @param callable $callback
     * @return void
     */
    public function beforeStart($callback)
    {
        $callback();
    }

    /**
     * Do actions after completing execution any command.
     *
     * @param callable $callback
     * @return void
     */
    public function afterComplete($callback)
    {
        $callback();
    }
}
