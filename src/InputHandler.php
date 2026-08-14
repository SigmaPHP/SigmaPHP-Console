<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\InputHandlerInterface;
use SigmaPHP\Console\Argument;
use SigmaPHP\Console\Option;

/**
 * Input Handler Class.
 */
class InputHandler implements InputHandlerInterface
{
    /**
     * @var string $command
     */
    protected $command;

    /**
     * @var array<Argument> $arguments
     */
    protected $arguments;

    /**
     * @var array<Option> $options
     */
    protected $options;

    /**
     * @var bool $processCommand
     */
    protected $processCommand;

    /**
     * InputHandler Constructor.
     *
     * @param array<Argument> $arguments
     * @param array<Option> $options
     * @param bool $processCommand
     */
    public function __construct($arguments, $options, $processCommand = false)
    {
        $this->command = '';
        $this->arguments = $arguments;
        $this->options = $options;
        $this->processCommand = $processCommand;
    }

    /**
     * Extract command, arguments and options.
     *
     * @return void
     */
    public function process()
    {
        global $argv;
        $_argv = $argv;

        // remove script's name
        unset($_argv[0]);

        // command
        if (isset($argv[1]) && (strpos($argv[1], '-') === false)) {
            $this->command = $argv[1];

            unset($_argv[1]);
        }

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

            if ($this->processCommand && ($unknown == count($this->options))) {
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
        if ($this->processCommand &&
            $this->optionsAreEmpty() &&
            !isset($this->options['help'])
        ) {
            if (count($_argv) < count($this->arguments)) {
                throw new \InvalidArgumentException(
                    "Missing arguments for command '{$this->command}'"
                );
            }
            else if (empty($this->arguments) && count($_argv)) {
                throw new \InvalidArgumentException(
                    "Command '{$this->command}' accepts no arguments"
                );
            }
            else if (count($_argv) > count($this->arguments)) {
                throw new \InvalidArgumentException(
                    "Invalid number of arguments were provided " .
                    "for command '{$this->command}'"
                );
            }
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
     * Get the provided (called by user) command's name.
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Check if an argument was set.
     *
     * @param string $name
     * @return bool
     */
    public function hasArgument($name)
    {
        if (!isset($this->arguments[$name])) {
            return false;
        }

        return !empty($this->arguments[$name]->getValue());
    }

    /**
     * Get argument's value.
     *
     * Please note: this method will return null if the argument wasn't set.
     *
     * @param string $name
     * @return mixed
     */
    public function getArgument($name)
    {
        if (!isset($this->arguments[$name])) {
            return null;
        }

        return $this->arguments[$name]->getValue();
    }

    /**
     * Check if an option was set.
     *
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        if (!isset($this->options[$name])) {
            return false;
        }

        return !empty($this->options[$name]->getValue());
    }

    /**
     * Get option's value.
     *
     * Please note: this method will return null if the option wasn't set.
     *
     * @param string $name
     * @return mixed
     */
    public function getOption($name)
    {
        if (!isset($this->options[$name])) {
            return null;
        }

        return $this->options[$name]->getValue();
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
