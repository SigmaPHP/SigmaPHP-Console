<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\AppInterface;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\DefaultCommands\Help;
use SigmaPHP\Console\DefaultCommands\Version;
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

        $this->addCommand(Help::class);
        $this->addCommand(Version::class);

        $this->addGlobalOption('help', 'h', 'Print the help menu');
        $this->addGlobalOption('version', 'v',
            'Print the application\'s version');
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

        // add the command to the help menu
        if ($this->hasCommand('help')) {
            $commands = $this->getCommand('help')->getCommandsList();
            $commands[$commandInst->getName()] = $commandInst->getDescription();
            $this->getCommand('help')->setCommandsList($commands);
        }
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
     * Check if an argument was provided to the app.
     *
     * @param string $name
     * @return bool
     */
    public function inputHasArgument($name)
    {
        // !ToDo
    }

    /**
     * Check if an option was provided to the app.
     *
     * @param string $name
     * @return bool
     */
    public function inputHasOption($name)
    {
        $options = getopt(
            $this->getGlobalOptions()['short'],
            $this->getGlobalOptions()['long']
        );

        $option = $this->getGlobalOption($name);

        // please note: getopt() set the option that exists as 'false' :D
        return (isset($options[$option['name']]) &&
            !$options[$option['name']]) ||
            (isset($options[$option['shortcut']]) &&
            !$options[$option['shortcut']]);
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

        // if no input was provided, show 'help' if enabled!
        // otherwise do nothing
        if (($argc == 1) && !isset($argv[1])) {
            if ($this->hasCommand('help')) {
                $argv[1] = 'help';
            } else {
                return;
            }
        }

        if ($this->inputHasOption('help')){
            $this->getCommand('help')->execute();
            return;
        }
        else if ($this->inputHasOption('version')){
            $this->getCommand('version')->execute();
            return;
        }

        if (!$this->hasCommand($argv[1])) {
            throw new CommandNotFoundException("Unknown command: {$argv[1]}");
        }

        // start execution cycle
        $this->validateInput($argv);

        $this->beforeStart();

        $this->getCommand($argv[1])->execute();

        $this->afterComplete();
    }

    /**
     * Add global option.
     *
     * Note: PHP built-in getopt() function has weird rule for required/optional
     * parameters, use ':' for 'required' parameters and '::' for 'optional'.
     *
     * Also, $name here refers to 'longname' while $shortcut for 'shortname'.
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
        $requireValue = false,
        $validation = ''
    ) {
        $this->globalOptions[$name] = [
            'name' => $name . ($requireValue ? ':' : ''),
            'shortcut' => $shortcut . ($requireValue ? ':' : ''),
            'description' => $description,
            'validation' => $validation,
        ];

        // add the command to the help menu
        if ($this->hasCommand('help')) {
            $options = $this->getCommand('help')->getGlobalOptionsList();
            $options[$name] = [
                'shortcut' => $shortcut,
                'description' => $description,
            ];
            $this->getCommand('help')->setGlobalOptionsList($options);
        }
    }

    /**
     * Remove global option.
     *
     * @param string $name
     * @return void
     */
    public function removeGlobalOption($name)
    {
        unset($this->globalOptions[$name]);
    }

    /**
     * Get global option.
     *
     * @param string $name
     * @return array
     */
    public function getGlobalOption($name)
    {
        return $this->globalOptions[$name];
    }

    /**
     * Get global options.
     *
     * @return array contains 'longname' array and 'shortname' string
     */
    public function getGlobalOptions()
    {
        $options = [
            'short' => '',
            'long' => [],
        ];

        foreach ($this->globalOptions as $option) {
            $options['short'] .= $option['shortcut'];
            $options['long'][] = $option['name'];
        }

        return $options;
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

        $this->removeGlobalOption('help');
        $this->removeGlobalOption('version');
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
