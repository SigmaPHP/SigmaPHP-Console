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
     * @var array<Argument> $arguments
     */
    protected $arguments;

    /**
     * @var array<Option> $options
     */
    protected $options;

    /**
     * InputHandler Constructor.
     *
     * @param array<Argument> $arguments
     * @param array<Option> $options
     */
    public function __construct($arguments, $options)
    {
        $this->arguments = $arguments;
        $this->options = $options;
    }

    /**
     * Get the provided (called by user) command's name.
     *
     * @return string|null
     */
    public function getCommand()
    {
        global $argv;

        return isset($argv[1]) ? $argv[1] : null;
    }

    /**
     * Check if an argument was set.
     *
     * @param string $name
     * @return bool
     */
    public function hasArgument($name)
    {
        global $argv;

        d(array_slice($argv, $this->getArgumentValues()[0]));

        if (!isset($this->arguments[$name])) {
            return false;
        }


        $argument = $this->arguments[$name];

        return isset($argv[$argument->getOrder()]);
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

        global $argv;

        $argument = $this->arguments[$name];

        return $argv[$argument->getOrder()];
    }

    /**
     * Check if an option was set.
     *
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        $options = $this->getOptionValues();
        $option = $this->options[$name];

        // please note: getopt() set the option that exists as 'false' :D
        return (isset($options[$option->getName()]) &&
            !$options[$option->getName()]) ||
            (isset($options[$option->getShortcut()]) &&
            !$options[$option->getShortcut()]);
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
        $options = $this->getOptionValues();
        $option = $this->options[$name];
        $value = null;

        if (!$this->hasOption($name)) {
            return $value;
        }
        else if (isset($options[$option->getName()])) {
            $value = $options[$option->getName()];
        }
        else if (isset($options[$option->getShortcut()])) {
            $value = $options[$option->getShortcut()];
        }

        // please note: getopt() set the option that exists as 'false' :D
        return is_bool($value) ? !$value : $value;
    }

    /**
     * Get argument values.
     *
     * @return array
     */
    protected function getArgumentValues()
    {
        $_options = [
            'short' => '',
            'long' => [],
        ];

        foreach ($this->options as $option) {
            $_options['short'] .= $option->getShortcut();
            $_options['long'][] = $option->getName();
        }

        $result = null;

        getopt($_options['short'], $_options['long'], $result);

        return  $result;
    }

    /**
     * Get option values.
     *
     * Please note: this method is just a wrapper for PHP built-in getopt().
     *
     * @return array
     */
    protected function getOptionValues()
    {
        $_options = [
            'short' => '',
            'long' => [],
        ];

        foreach ($this->options as $option) {
            $_options['short'] .= $option->getShortcut();
            $_options['long'][] = $option->getName();
        }

        return getopt($_options['short'], $_options['long']) ?: [];
    }
}
