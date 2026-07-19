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
     * @var array $options
     */
    protected $options;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var FileUtility $fileUtility
     */
    protected $fileUtility;

    /**
     * App Constructor.
     */
    public function __construct()
    {
        $this->commands = [];
        $this->options = [];

        $this->addCommand(Help::class);
        $this->addCommand(Version::class);

        $this->fileUtility = new FileUtility();
    }

    /**
     * Load commands from directory.
     *
     * @param string $path
     * @return void
     */
    public function loadCommands($path)
    {
        foreach ($this->fileUtility->list($path, false, false) as $command) {
            $this->addCommand($command::class);
        }
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
     * Add global option.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param regex $validation
     * @return void
     */
    public function addGlobalOption(
        $name,
        $shortcut,
        $description,
        $validation
    ) {

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
     * Set app's title.
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {

    }

    /**
     * Get app's title.
     *
     * @return string
     */
    public function getTitle()
    {

    }

    /**
     * Customize app's welcome banner (header/title), that could include name,
     * copy rights, some ascii-art or what so ever :)
     *
     * By default this method will print the app's title if set.
     *
     * This method will be used inside the `Help` default command.
     *
     * @return void
     */
    public function welcome()
    {

    }

    /**
     * Do actions before executing any command.
     *
     * @return void
     */
    public function beforeStart()
    {

    }

    /**
     * Do actions after completing execution any command.
     *
     * @return void
     */
    public function afterComplete()
    {

    }
}
