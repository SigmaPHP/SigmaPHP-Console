<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\AppInterface;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\DefaultCommands\Help;
use SigmaPHP\Console\DefaultCommands\Version;
use SigmaPHP\Console\FileUtility;
use SigmaPHP\Console\Exceptions\CommandNotFoundException;
use SigmaPHP\Console\Option;
use SigmaPHP\Console\DataType;

/**
 * App Class.
 */
class App implements AppInterface
{
    /**
     * @var string $appName
     */
    protected $appName;

    /**
     * @var array<Command> $commands
     */
    protected $commands;

    /**
     * @var array<Option> $globalOptions
     */
    protected $globalOptions;

    /**
     * App Constructor.
     */
    public function __construct()
    {
        $this->appName = '';
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

        $this->appName = $appName;
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
     * Run the app.
     *
     * @return void
     */
    public function run()
    {
        try {
            global $argv;

            if (empty($this->appName)) {
                $this->appName = $argv[0];
                $this->setAppName($this->appName);
            }

            if (!isset($argv[1])) {
                if ($this->hasCommand('help')){
                    $this->getCommand('help')->execute();
                }

                exit(0);
            }

            $input = $argv[1];

            // if no input was provided, check the global options if any were
            // set else show 'help' if enabled, otherwise do nothing!
            if ($this->hasCommand($input)) {
                // start execution cycle
                $this->beforeStart();

                $this->getCommand($input)->setAppName($this->appName);
                $this->getCommand($input)->executionHandler();

                $this->afterComplete();

                exit(0);
            }
            else if (strpos($input, '-') !== false) {
                $command = '';

                if (in_array($input, ['-v', '--version'])) {
                    $command = 'version';
                }
                else if (in_array($input, ['-h', '--help'])) {
                    $command = 'help';
                }
                else {
                    throw new \InvalidArgumentException(
                        "Unknown option '{$input}'"
                    );
                }

                if ($this->hasCommand($command)) {
                    $this->getCommand($command)->execute();
                }

                exit(0);
            }
            else {
                throw new CommandNotFoundException(
                    "Unknown command: {$input}"
                );
            }
        } catch (\Exception $e) {
            // ToDo: use IO
            echo "Error: {$e->getMessage()}\n\n";
            echo "Run '{$this->appName} --help' ";
            echo "or '{$this->appName} [COMMAND] --help' ";
            echo "for more information\n";

            exit(1);
        }
    }

    /**
     * Add global option.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param string $parameterOptionality
     * @param DataType $dataType
     * @param mixed $defaultValue
     * @return void
     */
    public function addGlobalOption(
        $name,
        $shortcut = '',
        $description = '',
        $parameterOptionality = Option::NONE,
        $dataType = DataType::STRING,
        $defaultValue = null,
    ) {
        $this->globalOptions[$name] = new Option(
            $name,
            $shortcut,
            $description,
            $parameterOptionality,
            $dataType,
            $defaultValue
        );

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
     * @return Option
     */
    public function getGlobalOption($name)
    {
        return $this->globalOptions[$name];
    }

    /**
     * Get global options.
     *
     * @return array<Option>
     */
    public function getGlobalOptions()
    {
        return $this->globalOptions;
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
