<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\AppInterface;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\FileUtility;
use SigmaPHP\Console\Exceptions\CommandNotFoundException;

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

        $this->loadCommands(
            __DIR__ . '/DefaultCommands',
            'SigmaPHP\Console\DefaultCommands'
        );
    }

    /**
     * Set app's name.
     *
     * Note: could only be used when Default Commands are enabled.
     *
     * @param string $appName
     * @return void
     */
    public function setAppName($appName)
    {
        if (!$this->hasCommand('help')) {
            throw new \Exception('App information could only be set when ' .
                'default commands are enabled!');
        }

        $this->getCommand('help')->setAppName($appName);
    }

    /**
     * Set app's description.
     *
     * Note: could only be used when Default Commands are enabled.
     *
     * @param string $appDescription
     * @return void
     */
    public function setAppDescription($appDescription)
    {
        if (!$this->hasCommand('help')) {
            throw new \Exception('App information could only be set when' .
                'default commands are enabled!');
        }

        $this->getCommand('help')->setAppDescription($appDescription);
    }

    /**
     * Set app's version.
     *
     * Note: could only be used when Default Commands are enabled.
     *
     * @param string $appVersion
     * @return void
     */
    public function setAppVersion($appVersion)
    {
        if (!$this->hasCommand('version')) {
            throw new \Exception('App information could only be set when' .
                'default commands are enabled!');
        }

        $this->getCommand('version')->setAppVersion($appVersion);
    }

    /**
     * Add command to app.
     *
     * @param Command $command
     * @return void
     */
    public function addCommand($command)
    {
        if (!class_exists((string) $command)) {
            throw new CommandNotFoundException("Unknown command: {$command}");
        }

        $commandInst = new $command();

        // if the command's name is not defined, we will generate a one
        // based on the class name
        if (empty($commandInst->getName())) {
            $_parts = explode('\\', (string) $command);
            $commandInst->setName(strtolower($_parts[count($_parts) - 1]));
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
     * @return object<Command>
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
     * @param string $name
     * @return bool
     */
    public function validateInput($name)
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
        global $argc;
        global $argv;

        // if no command was selected show 'help' if enabled!
        // otherwise do nothing
        if (($argc == 1) && !isset($argv[1])) {
            if ($this->hasCommand('help')) {
                $argv[1] = 'help';
            } else {
                return;
            }
        }

        // in case the command doesn't exists
        if (!$this->hasCommand($argv[1])) {
            throw new CommandNotFoundException("Unknown command: {$argv[1]}");
        }

        // start execution cycle
        $this->validateInput($argv[1]);

        $this->beforeStart();

        $this->getCommand($argv[1])->execute();

        $this->afterComplete();
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
     * Disable the defaults options and commands (version & help).
     *
     * @return void
     */
    public function disableDefaults()
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
