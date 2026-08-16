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
     * @var IO $io
     */
    protected $io;

    /**
     * Command Constructor.
     */
    public function __construct()
    {
        $this->appName = '';
        $this->arguments = [];
        $this->options = [];

        // register global options
        $this->addOption('help', 'h', 'Print the help menu');

        $this->init();

        $this->io = new IO();
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

        // options
        $markForDelete = [];
        foreach ($_argv as $order => $_arg) {
            $opt = '';
            $unknown = 0;

            // if it start with '--' or '-' then it's an option, otherwise skip
            if (strpos($_arg, '--') !== false) {
                $opt = str_replace('--', '', $_arg);
            }
            else if (strpos($_arg, '-') !== false) {
                $opt = str_replace('-', '', $_arg);
            }
            else {
                continue;
            }

            // match the option, it could be name or shortcut, based on that
            // check the next if it's the option's parameter save it otherwise
            // just put the value to true!
            foreach ($this->options as $option) {
                if (($option->getName() == $opt) ||
                    ($option->getShortcut() == $opt)
                ) {
                    if (isset($_argv[$order + 1]) &&
                        strpos($_argv[$order + 1], '-') === false
                    ) {
                        $option->setValue($_argv[$order + 1]);
                        $markForDelete[] = $order + 1;
                    } else {
                        $option->setValue(true);
                    }

                    $markForDelete[] = $order;
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
                unset($_argv[$i]);
            }
        }

        $_argv = array_values($_argv);

        // arguments
        if (count($_argv) < count($this->arguments)) {
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

        if (!empty($this->getOption('help')) &&
            $this->getOption('help')->getValue()
        ) {
            $this->help();
        } else {
            $this->execute();
        }
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
            return;
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
        if (!isset($this->arguments[$name])) {
            return null;
        }

        return $this->arguments[$name];
    }

    /**
     * Get arguments.
     *
     * @return array<Argument>
     */
    public function getArguments()
    {
        return $this->arguments;
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
        $parameterOptionality = Option::NONE,
        $dataType = DataType::STRING,
        $defaultValue = null,
    ) {
        $this->options[$name] = new Option(
            $name,
            $shortcut,
            $description,
            $parameterOptionality,
            $dataType,
            $defaultValue
        );
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
            return;
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
     * Get option.
     *
     * @param string $name
     * @return Option|null
     */
    public function getOption($name)
    {
        if (!isset($this->options[$name])) {
            return null;
        }

        return $this->options[$name];
    }

    /**
     * Get options.
     *
     * @return array<Option>
     */
    public function getOptions()
    {
        return $this->options;
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

        if (!empty($this->arguments)) {
            $helpContent .= "Arguments:\n";

            ksort($this->arguments);

            foreach ($this->arguments as $argument) {
                $helpContent .= "\t{$argument->getName()}\t" .
                    "\t{$argument->getDescription()}\n";
            }
        }

        $helpContent .= "\n";

        if (!empty($this->options)) {
            $helpContent .= "Options:\n";

            ksort($this->options);

            foreach ($this->options as $option) {
                $helpContent .= "\t-{$option->getShortcut()}" .
                    ", --{$option->getName()}" .
                    "\t\t{$option->getDescription()}\n";
            }
        }

        $helpContent .= "\n";

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
}
