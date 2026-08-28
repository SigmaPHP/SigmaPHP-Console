<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\AppInterface;
use SigmaPHP\Console\Command;
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
     * @var string $appDescription
     */
    protected $appDescription;

    /**
     * @var string $appVersion
     */
    protected $appVersion;

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
        $this->appDescription = 'A CLI utility to preform some tasks';
        $this->appVersion = 'v1.0.0';

        $this->commands = [];
        $this->globalOptions = [];

        $this->addGlobalOption(
            'help',
            'h',
            'Print the help menu',
            Option::PARAMETER_NONE,
            DataType::BOOL
        );

        $this->addGlobalOption(
            'version',
            'V',
            'Print the application\'s version',
            Option::PARAMETER_NONE,
            DataType::BOOL
        );

        $this->addGlobalOption(
            'quiet',
            'q',
            'Suppress normal output; show errors only',
            Option::PARAMETER_NONE,
            DataType::BOOL
        );

        $this->addGlobalOption(
            'silent',
            's',
            'Suppress all output, including errors',
            Option::PARAMETER_NONE,
            DataType::BOOL
        );

        $this->addGlobalOption(
            'verbose',
            'v',
            'Show detailed debug information; use default values',
            Option::PARAMETER_NONE,
            DataType::BOOL
        );

        $this->addGlobalOption(
            'no-color',
            '',
            'Disable colored output',
            Option::PARAMETER_NONE,
            DataType::BOOL
        );

        $this->addGlobalOption(
            'no-interactive',
            '',
            'Disable interactive prompts and input',
            Option::PARAMETER_NONE,
            DataType::BOOL
        );

        $this->addGlobalOption(
            'plain',
            '',
            'Use plain, human-readable output without formatting',
            Option::PARAMETER_NONE,
            DataType::BOOL
        );

        $this->addGlobalOption(
            'json',
            '',
            'Output results in JSON format',
            Option::PARAMETER_NONE,
            DataType::BOOL
        );
    }

    /**
     * Set app's name.
     *
     * @param string $appName
     * @return void
     */
    public function setAppName($appName)
    {
        $this->appName = $appName;
    }

    /**
     * Set app's description.
     *
     * @param string $appDescription
     * @return void
     */
    public function setAppDescription($appDescription)
    {
        $this->appDescription = $appDescription;
    }

    /**
     * Set app's version.
     *
     * @param string $appVersion
     * @return void
     */
    public function setAppVersion($appVersion)
    {
        $this->appVersion = $appVersion;
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
        foreach ($this->commands as $command) {
            if (($name == $command->getName()) ||
                in_array($name, $command->getAliases())
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get command from app.
     *
     * @param string $name
     * @return object<Command>
     */
    public function getCommand($name)
    {
        if (!$this->hasCommand($name)) {
            throw new CommandNotFoundException(
                "Trying to call unknown command '{$name}'"
            );
        }

        $_command = '';

        foreach ($this->commands as $command) {
            if (($name == $command->getName()) ||
                in_array($name, $command->getAliases())
            ) {
                $_command = $command->getName();
            }
        }

        return $this->commands[$_command];
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
        if (!$this->hasCommand($commandName)) {
            throw new CommandNotFoundException(
                "Trying to remove unknown command '{$commandName}'"
            );
        }

        unset($this->commands[$commandName]);
    }

    /**
     * Run the app.
     *
     * @return void
     */
    public function run()
    {
        global $argv;

        try {
            if (empty($this->appName)) {
                $this->appName = $argv[0];
                $this->setAppName($this->appName);
            }

            if (!isset($argv[1])) {
                $this->help();
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
                if (in_array($input, ['-v', '--version'])) {
                    $this->version();
                }
                else if (in_array($input, ['-h', '--help'])) {
                    $this->help();
                }
                else {
                    throw new \InvalidArgumentException(
                        "Unknown option '{$input}'"
                    );
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

            if (isset($argv[1]) && !($e instanceof CommandNotFoundException)) {
                echo "Run '{$this->appName} {$argv[1]} --help' ";
            } else {
                echo "Run '{$this->appName} --help' ";
            }

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
        $parameterOptionality = Option::PARAMETER_NONE,
        $dataType = DataType::BOOL,
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
    }

    /**
     * Remove global option.
     *
     * @param string $name
     * @return void
     */
    public function removeGlobalOption($name)
    {
        if (!isset($this->globalOptions[$name])) {
            throw new CommandNotFoundException(
                "Trying to remove unknown global option '{$name}'"
            );
        }

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
        if (!isset($this->globalOptions[$name])) {
            throw new CommandNotFoundException(
                "Trying to call unknown global option '{$name}'"
            );
        }

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

    /**
     * Help!
     *
     * @return void
     */
    public function help()
    {
        $helpContent = "{$this->appDescription}\n\n";

        $helpContent .= "Usage:\n";
        $helpContent .=
            "\t{$this->appName} [COMMAND] [OPTIONS] [--] [ARGUMENTS]\n\n";

        if (!empty($this->commands)) {
            $maxCommandName = max(array_map(function ($item) {
                return strlen($item);
            }, array_keys($this->commands))) + 2;

            $helpContent .= "Available Commands:\n";

            ksort($this->commands);

            foreach ($this->commands as $command) {
                $helpContent .= "\t" .
                    str_pad($command->getName(), $maxCommandName) .
                    "{$command->getDescription()}\n";
            }
        }

        $helpContent .= "\n";

        if (!empty($this->globalOptions)) {
            $maxGlobalOptionName = max(array_map(function ($option) {
                return !empty($option->getShortcut()) ? strlen(
                    "-{$option->getShortcut()}, --{$option->getName()}"
                ) : strlen("    --{$option->getName()}");
            }, $this->globalOptions)) + 2;

            $helpContent .= "Global Options:\n";

            ksort($this->globalOptions);

            foreach ($this->globalOptions as $option) {
                $optionTitle = !empty($option->getShortcut()) ?
                    "-{$option->getShortcut()}, --{$option->getName()}" :
                    "    --{$option->getName()}";

                $helpContent .= "\t" .
                    str_pad($optionTitle, $maxGlobalOptionName) .
                    "{$option->getDescription()}\n";
            }
        }

        $helpContent .= "\n";

        $helpContent .= "Run '{$this->appName} [COMMAND] --help' to get ";
        $helpContent .= "more information on a command\n";

        // ToDo: use IO
        echo $helpContent;
    }

    /**
     * Print app's version.
     *
     * @return void
     */
    public function version()
    {
        // ToDo: use IO
        echo "{$this->appVersion}\n";
    }
}
