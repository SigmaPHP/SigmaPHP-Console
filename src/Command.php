<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\CommandInterface;
use SigmaPHP\Console\IO;
use SigmaPHP\Console\Argument;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;

/**
 * Command Class.
 */
abstract class Command implements CommandInterface
{
    /**
     * @var string $appName
     */
    protected $appName;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var array<Argument> $arguments
     */
    protected $arguments;

    /**
     * @var array<Option> $options
     */
    protected $options;

    /**
     * @var array<string> $shortcuts
     */
    protected $shortcuts;

    /**
     * @var array<string> $aliases
     */
    protected $aliases;

    /**
     * @var IO $io
     */
    protected $io;

    /**
     * Command Constructor.
     *
     * @param string $appName
     * @param array<Option> $options
     * @param array<string> $shortcuts
     */
    public function __construct($appName, $options = [], $shortcuts = [])
    {
        $this->appName = $appName;
        $this->options = $options;
        $this->shortcuts = $shortcuts;
        $this->arguments = [];
        $this->aliases = [];

        // set default command name, using some lightweight dark magic :D
        $this->setName(
            strtolower(array_reverse(explode("\\", get_class($this)))[0])
        );

        $this->init();
    }

    /**
     * Initialize the command.
     *
     * @return void
     */
    abstract public function init();

    /**
     * Execute.
     *
     * @return void
     */
    abstract public function execute();

    /**
     * Load the arguments and options.
     *
     * @return void
     */
    public function processInput()
    {
        global $argv;
        $_argv = $argv;

        unset($_argv[0]);
        unset($_argv[1]);

        $_argv = array_values($_argv);
        $_argc = count($_argv);

        // options
        //
        // Source:
        // https://sourceware.org/glibc/manual/2.44/html_mono/
        // libc.html#Argument-Syntax
        //
        // supported option patterns:
        // -i
        // --interactive
        // --connect xyz
        // --connect=xyz
        // -c=xyz
        // -cxyz
        // -ic
        $markForDelete = [];
        for ($order = 0;$order < $_argc;$order++) {
            $_arg = $_argv[$order];
            $opt = '';
            $val = '';
            $unknown = 0;

            // if it start with '--' or '-' then it's an option, otherwise skip
            if (strpos($_arg, '--') !== false) {
                $opt = str_replace('--', '', $_arg);
            }
            else if (strpos($_arg, '-') !== false) {
                $opt = str_replace('-', '', $_arg);

                // check chained options and option/parameter combined cases
                $isChained = false;

                if ((strlen($opt) > 1) && (strpos($opt, '=') === false)) {
                    $_opt = str_split($opt);

                    foreach ($_opt as $o) {
                        if (in_array($o, $this->shortcuts)) {
                            $isChained = true;
                        } else {
                            $isChained = false;
                        }
                    }

                    if ($isChained) {
                        $opt = $_opt[0];
                        unset($_opt[0]);

                        // if options are chained, we take the first for loop
                        // and append the rest for the upcoming loops
                        //
                        // ! yes i know, appending during iterating array is BAD
                        foreach ($_opt as $o) {
                            $_argv[] = '-' . $o;
                            $_argc += 1;
                        }
                    } else {
                        $val = substr($opt, 1);
                        $opt = $opt[0];
                    }

                    $markForDelete[] = $order;
                }
            }
            else {
                continue;
            }

            // if the option has and '=' symbol we extract the value
            if (strpos($opt, '=') !== false) {
                $_parts = explode('=', $opt);

                $opt = $_parts[0];
                $val = $_parts[1];

                $markForDelete[] = $order;
            }

            foreach ($this->options as $option) {
                if (($option->getName() == $opt) ||
                    ($option->getShortcut() == $opt)
                ) {
                    if ($option->getParameterOptionality() ==
                        Option::PARAMETER_REQUIRED
                    ) {
                        // take next argument as a value
                        if (empty($val) && isset($_argv[$order + 1]) &&
                            strpos($_argv[$order + 1], '-') === false
                        ) {
                            $val = $_argv[$order + 1];

                            $markForDelete[] = $order;
                            $markForDelete[] = $order + 1;
                        }

                        if (!empty($val)) {
                            $option->setValue($val);
                            $markForDelete[] = $order;
                        } else {
                            throw new \InvalidArgumentException(
                                "Missing require parameter for option '{$_arg}'"
                            );
                        }
                    }
                    else if ($option->getParameterOptionality() ==
                        Option::PARAMETER_OPTIONAL
                    ) {
                        // take next argument as a value
                        if (empty($val) && isset($_argv[$order + 1]) &&
                            strpos($_argv[$order + 1], '-') === false
                        ) {
                            $val = $_argv[$order + 1];

                            $markForDelete[] = $order;
                            $markForDelete[] = $order + 1;
                        }

                        if (!empty($val)) {
                            $option->setValue($val);
                            $markForDelete[] = $order;
                        } else {
                            if (!is_null($option->getDefaultValue())) {
                                $option->setValue($option->getDefaultValue());
                                $markForDelete[] = $order;
                            } else {
                                throw new \InvalidArgumentException(
                                    "The option '{$_arg}' is missing default " .
                                    "value for its parameter"
                                );
                            }
                        }
                    }
                    else if ($option->getParameterOptionality() ==
                        Option::PARAMETER_NONE
                    ) {
                        if (!empty($val)) {
                            throw new \InvalidArgumentException(
                                "The option '{$_arg}' doesn't require parameter"
                            );
                        } else {
                            $option->setValue(true);
                            $markForDelete[] = $order;
                        }
                    }
                } else {
                    $unknown += 1;
                }
            }

            if ($unknown == count($this->options)) {
                throw new \InvalidArgumentException("Unknown option '{$_arg}'");
            }
        }

        if (!empty($markForDelete)) {
            foreach ($markForDelete as $i) {
                if (isset($_argv[$i])) {
                    unset($_argv[$i]);
                }
            }
        }

        $_argv = array_values($_argv);

        // arguments
        if (empty($_argv) && $this->getOption('help')) {
            return;
        }
        else if (count($_argv) < count($this->arguments)) {
            throw new \InvalidArgumentException(
                "Missing arguments for command '{$this->getName()}'"
            );
        }
        else if (empty($this->arguments) && count($_argv)) {
            throw new \InvalidArgumentException(
                "Command '{$this->getName()}' accepts no arguments"
            );
        }
        else if (count($_argv) > count($this->arguments)) {
            throw new \InvalidArgumentException(
                "Invalid number of arguments were provided " .
                "for command '{$this->getName()}'"
            );
        }

        foreach ($_argv as $order => $_arg) {
            foreach ($this->arguments as $argument) {
                if ($argument->getOrder() == $order) {
                    $argument->setValue($_arg);
                }
            }
        }
    }

    /**
     * A proxy for the execution method to force global settings and options.
     *
     * @return void
     */
    public function executionHandler()
    {
        $this->processInput();

        if ($this->getOption('help')) {
            $this->help();
        } else {
            $this->execute();
        }
    }

    /**
     * Set IO handler.
     *
     * @param IO $handler
     * @return void
     */
    public function setIOHandler($handler)
    {
        $this->io = $handler;
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
     * Set command's name.
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set command's description.
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get command's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get command's description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add argument.
     *
     * @param string $name
     * @param string $description
     * @param DataType $dataType
     * @return void
     */
    public function addArgument(
        $name,
        $description = '',
        $dataType = DataType::STRING
    ) {
        if (isset($this->arguments[$name])) {
            throw new \InvalidArgumentException(
                "The argument '{$name}' is already registered in the command " .
                "'{$this->name}'"
            );
        }

        $this->arguments[$name] = new Argument(
            $name,
            $description,
            $dataType
        );

        $this->arguments[$name]->setOrder(count($this->arguments) - 1);
    }

    /**
     * Remove argument.
     *
     * @param string $name
     * @return void
     */
    public function removeArgument($name)
    {
        if (!isset($this->arguments[$name])) {
            throw new \InvalidArgumentException(
                "Trying to remove unknown argument '{$name}' from the command "
                . "'{$this->name}'"
            );
        }

        unset($this->arguments[$name]);
    }

    /**
     * Has argument.
     *
     * @param string $name
     * @return bool
     */
    public function hasArgument($name)
    {
        if (
            !isset($this->arguments[$name]) ||
            empty($this->arguments[$name]->getValue())
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get argument.
     *
     * @param string $name
     * @return Argument|null
     */
    public function getArgument($name)
    {
        if (
            !isset($this->arguments[$name]) ||
            empty($this->arguments[$name]->getValue())
        ) {
            return null;
        }

        return $this->arguments[$name]->getValue();
    }

    /**
     * Add option.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param string $parameterOptionality
     * @param DataType $dataType
     * @param mixed $defaultValue
     * @return void
     */
    public function addOption(
        $name,
        $shortcut = '',
        $description = '',
        $parameterOptionality = Option::PARAMETER_OPTIONAL,
        $dataType = DataType::STRING,
        $defaultValue = ''
    ) {
        if (isset($this->options[$name])) {
            throw new \InvalidArgumentException(
                "The option '{$name}' is already registered in the command " .
                "'{$this->name}'"
            );
        }

        if (in_array($shortcut, $this->shortcuts)) {
            throw new \InvalidArgumentException(
                "The shortcut '{$shortcut}' is already registered for " .
                "another option"
            );
        }

        $this->options[$name] = new Option(
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
     * Remove option.
     *
     * @param string $name
     * @return void
     */
    public function removeOption($name)
    {
        if (!isset($this->options[$name])) {
            throw new \InvalidArgumentException(
                "Trying to remove unknown option '{$name}' from the command " .
                "'{$this->name}'"
            );
        }

        if (!empty($this->options[$name]->getShortcut())) {
            unset($this->shortcuts[$this->options[$name]->getShortcut()]);
        }

        unset($this->options[$name]);
    }

    /**
     * Has option.
     *
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        if (
            !isset($this->options[$name]) ||
            empty($this->options[$name]->getValue())
        ) {
            return false;
        }

        return true;
    }

    /**
     * Get option's value.
     *
     * @param string $name
     * @return Option|null
     */
    public function getOption($name)
    {
        if (
            !isset($this->options[$name]) ||
            empty($this->options[$name]->getValue())
        ) {
            return null;
        }

        return $this->options[$name]->getValue();
    }

    /**
     * Help option's handler.
     *
     * @return void
     */
    public function help()
    {
        $helpContent = "{$this->description}\n\n";

        $helpContent .= "Usage:\n";
        $helpContent .= "\t{$this->appName} {$this->name} " .
            "[OPTIONS] [--] [ARGUMENTS]\n\n";

        if (!empty($this->aliases)) {
            $helpContent .= "Aliases:\n";

            $helpContent .= "\t" . trim(implode(',', $this->aliases), ',') .
                "\n";
        }

        if (!empty($this->arguments)) {
            $maxArgumentName = max(array_map(function ($arg) {
                return strlen("{$arg->getName()} <{$arg->getDataType()}>");
            }, $this->arguments)) + 2;

            $helpContent .= "Arguments:\n";

            ksort($this->arguments);

            foreach ($this->arguments as $argument) {
                $helpContent .= "\t" .
                    str_pad(
                        "{$argument->getName()} <{$argument->getDataType()}>",
                        $maxArgumentName) .
                    "{$argument->getDescription()}\n";
            }
        }

        $helpContent .= "\n";

        if (!empty($this->options)) {
            $maxOptionName = max(array_map(function ($option) {
                $shortcut = empty($option->getShortcut()) ? "    " :
                    "-{$option->getShortcut()}, ";

                return strlen(
                    $shortcut . "--{$option->getName()} " .
                    "<{$option->getParameterDataType()}>"
                );
            }, $this->options)) + 2;

            $helpContent .= "Options:\n";

            ksort($this->options);

            foreach ($this->options as $name => $option) {
                $shortcut = empty($option->getShortcut()) ? "    " :
                    "-{$option->getShortcut()}, ";

                $helpContent .= "\t" .
                    str_pad(
                        $shortcut . "--{$option->getName()} " .
                        "<{$option->getParameterDataType()}>",
                        $maxOptionName) .
                    "{$option->getDescription()}\n";
            }
        }

        $this->io->write($helpContent);
    }

    /**
     * Check if no arguments were provided.
     *
     * @return bool
     */
    public function argumentsAreEmpty()
    {
        foreach ($this->arguments as $argument) {
            if (!empty($argument->getValue())) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if no options were provided.
     *
     * @return bool
     */
    public function optionsAreEmpty()
    {
        foreach ($this->options as $option) {
            if (!empty($option->getValue())) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if no arguments nor options were provided.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->argumentsAreEmpty() && $this->optionsAreEmpty();
    }

    /**
     * Set aliases.
     *
     * @param array<string> $aliases
     * @return void
     */
    public function setAliases($aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * Get aliases.
     *
     * @return array<string>
     */
    public function getAliases()
    {
        return $this->aliases;
    }
}
