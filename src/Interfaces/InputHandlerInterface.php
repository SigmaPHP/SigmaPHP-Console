<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * Input Handler Interface.
 */
interface InputHandlerInterface
{
    /**
     * Extract command, arguments and options.
     *
     * @return void
     */
    public function process();

    /**
     * Get the provided (called by user) command's name.
     *
     * @return string
     */
    public function getCommand();

    /**
     * Check if an argument was set.
     *
     * @param string $name
     * @return bool
     */
    public function hasArgument($name);

    /**
     * Get argument's value.
     *
     * Please note: this method will return null if the argument wasn't set.
     *
     * @param string $name
     * @return mixed
     */
    public function getArgument($name);

    /**
     * Check if an option was set.
     *
     * @param string $name
     * @return bool
     */
    public function hasOption($name);

    /**
     * Get option's value.
     *
     * Please note: this method will return null if the option wasn't set.
     *
     * @param string $name
     * @return mixed
     */
    public function getOption($name);

    /**
     * Check if no arguments nor options were provided.
     *
     * @return bool
     */
    public function isEmpty();
}
