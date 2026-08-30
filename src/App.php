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
     * @var array<string> $shortcuts
     */
    protected $shortcuts;

    /**
     * @var IO $io
     */
    protected $io;

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
        $this->shortcuts = [];

        $this->io = new IO();

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
            'Show detailed debug information',
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

        // register global options except 'version'
        $_globalOptions = $this->globalOptions;
        unset($_globalOptions['version']);

        $_shortcuts = $this->shortcuts;
        unset($_shortcuts['v']);

        $commandInst = new $command(
            $_globalOptions,
            $_shortcuts
        );

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
        global $argc;

        $command = '';

        try {
            if (count($argv) == 1) {
                $this->help();
                exit(0);
            }

            // parse input
            for ($i = 0; $i < $argc;$i++) {
                // app name
                if (($i == 0) && empty($this->appName))  {
                    $this->setAppName($argv[$i]);
                    continue;
                }

                // command
                if (($i == 1) && (str_split($argv[$i])[0] !== '-')) {
                    $command = $argv[$i];

                    if (!$this->hasCommand($command)) {
                        throw new CommandNotFoundException(
                            "Unknown command: {$command}"
                        );
                    }

                    continue;
                }

                // global options
                $unknown = 0;
                $opt = str_replace('-', '', $argv[$i]);

                foreach ($this->globalOptions as $option) {
                    if (($option->getName() == $opt) ||
                        ($option->getShortcut() == $opt)
                    ) {
                        $option->setValue(true);
                    } else {
                        $unknown += 1;
                    }
                }

                if (
                    empty($command) && ($unknown == count($this->globalOptions))
                ) {
                    throw new \InvalidArgumentException(
                        "Unknown global option '{$argv[$i]}'"
                    );
                }
            }

            $isQuiet = $this->hasGlobalOption('quiet');
            $isSilent = $this->hasGlobalOption('silent');
            $isVerbose = $this->hasGlobalOption('verbose');

            if (!empty($command)) {
                $this->io->setIsQuiet($isQuiet);
                $this->io->setIsSilent($isSilent);

                if ($isQuiet || $isSilent) {
                    ob_start();
                }

                // start execution cycle
                $this->beforeStart();

                $runningCommand = $this->getCommand($command);

                $runningCommand->setAppName($this->appName);
                $runningCommand->setIOHandler($this->io);
                $runningCommand->executionHandler();

                $this->afterComplete();

                if ($isQuiet || $isSilent) {
                    ob_end_clean();
                }

                exit(0);
            }

            if ($this->hasGlobalOption('help')) {
                $this->help();
                exit(0);
            }

            if ($this->hasGlobalOption('version')) {
                $this->version();
                exit(0);
            }

            if (empty($command) && ($isQuiet || $isSilent || $isVerbose)) {
                $buffer = "No command was provided!\n\n";
                $buffer .= "Run '{$this->appName} --help' ";
                $buffer .= "for more information\n";

                $this->io->write($buffer);

                exit(0);
            }
        } catch (\Exception $e) {
            $buffer = "Error: {$e->getMessage()}\n\n";

            if ($this->hasGlobalOption('verbose')) {
                $buffer .= "Debug Trace:\n{$e->getTraceAsString()}\n\n";
            }

            if (isset($argv[1]) && !($e instanceof CommandNotFoundException)) {
                $buffer .= "Run '{$this->appName} {$argv[1]} --help' ";
            } else {
                $buffer .= "Run '{$this->appName} --help' ";
            }

            $buffer .= "for more information\n";

            $this->io->writeErr($buffer);

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
        if (isset($this->globalOptions[$name])) {
            throw new \InvalidArgumentException(
                "The global option '{$name}' is already registered in the app"
            );
        }

        if (in_array($shortcut, $this->shortcuts)) {
            throw new \InvalidArgumentException(
                "The shortcut '{$shortcut}' is already registered for " .
                "another global option"
            );
        }

        $this->globalOptions[$name] = new Option(
            $name,
            $shortcut,
            $description,
            $parameterOptionality,
            $dataType,
            $defaultValue
        );

        if (!empty($shortcut)) {
            $this->shortcuts[] = $shortcut;
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
        if (!isset($this->globalOptions[$name])) {
            throw new \InvalidArgumentException(
                "Trying to remove unknown global option '{$name}'"
            );
        }

        if (!empty($this->globalOptions[$name]->getShortcut())) {
            unset($this->shortcuts[$this->globalOptions[$name]->getShortcut()]);
        }

        unset($this->globalOptions[$name]);
    }

    /**
     * Has global option.
     *
     * @param string $name
     * @return bool
     */
    public function hasGlobalOption($name)
    {
        if (
            !isset($this->globalOptions[$name]) ||
            empty($this->globalOptions[$name]->getValue())
        ) {
            return false;
        }

        return true;
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
            $maxCommandName = max(array_map(function ($command) {
                return strlen($command);
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
