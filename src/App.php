<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\AppInterface;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\DefaultCommands\Help;
use SigmaPHP\Console\DefaultCommands\Version;
use SigmaPHP\Console\FileUtility;

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
     * @var array $globalOptions
     */
    protected $globalOptions;

    /**
     * App Constructor.
     */
    public function __construct()
    {
        $this->commands = [];
        $this->globalOptions = [];

        $this->addCommand(Help::class);
        $this->addCommand(Version::class);
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

        if (empty($commandInst->getName())) {
            throw new \InvalidArgumentException(
                "Command {$command} should have a name!"
            );
        }

        if ($this->hasCommand($commandInst->getName())) {
            throw new \InvalidArgumentException(
                "Command {$command} already registered in the App!"
            );
        }

        $this->commands[$commandInst->getName()] = $commandInst;
    }

    /**
     * Load commands from directory.
     *
     * @param string $path
     * @param string $nameSpace
     * @return void
     */
    public function loadCommands($path, $nameSpace = '')
    {
        foreach ((new FileUtility())->list($path, false, false) as $command) {
            require_once($path . '/' . $command . '.php');

            $fullClassPath = $command;

            if (!empty($nameSpace)) {
                $fullClassPath = $nameSpace . "\\" . $command;
            }

            $this->addCommand($fullClassPath);
        }
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
     * Get command from app.
     *
     * @param string $name
     * @return Command
     */
    public function getCommand($name)
    {
        return $this->commands[$name];
    }

    /**
     * Get all commands from app.
     *
     * @return array<Command>
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Remove command from app.
     *
     * @param string $commandName
     * @return void
     */
    public function removeCommand($commandName)
    {
        unset($this->commands[$commandName]);
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
        $shortcut = '',
        $description = '',
        $validation = ''
    ) {
        // !ToDo
    }

    /**
     * Disable the default functions (version & help).
     *
     * @return void
     */
    public function disableDefaultFunctions()
    {
        $this->removeCommand('help');
        $this->removeCommand('version');
    }

    /**
     * Do actions before executing any command.
     *
     * @return void
     */
    public function beforeStart()
    {
        // Up to the developer to use!
    }

    /**
     * Do actions after completing execution any command.
     *
     * @return void
     */
    public function afterComplete()
    {
        // Up to the developer to use!
    }
}
