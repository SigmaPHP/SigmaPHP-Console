<?php

namespace SigmaPHP\Console\Interfaces;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\Option;

/**
 * App Interface.
 */
interface AppInterface
{
    /**
     * Set app's name.
     *
     * Note: could only be used when Default Commands are enabled.
     *
     * @param string $appName
     * @return void
     */
    public function setAppName($appName);

    /**
     * Set app's description.
     *
     * Note: could only be used when Default Commands are enabled.
     *
     * @param string $appDescription
     * @return void
     */
    public function setAppDescription($appDescription);

    /**
     * Set app's version.
     *
     * Note: could only be used when Default Commands are enabled.
     *
     * @param string $appVersion
     * @return void
     */
    public function setAppVersion($appVersion);

    /**
     * Add command to app.
     *
     * @param Command $command
     * @return void
     */
    public function addCommand($command);

    /**
     * Load commands from directory.
     *
     * @param string $path
     * @param string $nameSpace
     * @return void
     */
    public function loadCommands($path, $nameSpace);

    /**
     * Check if command exists in app.
     *
     * @param string $commandName
     * @return bool
     */
    public function hasCommand($commandName);

    /**
     * Get command from app.
     *
     * @param string $name
     * @return object<Command>
     */
    public function getCommand($name);

    /**
     * Get all commands from app.
     *
     * @return array<Command>
     */
    public function getCommands();

    /**
     * Remove command from app.
     *
     * @param string $commandName
     * @return void
     */
    public function removeCommand($commandName);

    /**
     * Run the app.
     *
     * @return int
     */
    public function run();

    /**
     * Add global option.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param string $parameterOptionality
     * @param string $dataType
     * @param mixed $defaultValue
     * @return void
     */
    public function addGlobalOption(
        $name,
        $shortcut,
        $description,
        $parameterOptionality,
        $dataType,
        $defaultValue
    );

    /**
     * Remove global option.
     *
     * @param string $name
     * @return void
     */
    public function removeGlobalOption($name);

    /**
     * Get global option' value.
     *
     * @param string $name
     * @return Option
     */
    public function getGlobalOption($name);

    /**
     * Get global options.
     *
     * @return array contains 'longname' array and 'shortname' string
     */
    public function getGlobalOptions();

    /**
     * Disable the defaults options and commands (version & help).
     *
     * @return void
     */
    public function disableDefaults();

    /**
     * Do actions before executing any command.
     *
     * @return void
     */
    public function beforeStart();

    /**
     * Do actions after completing execution any command.
     *
     * @return void
     */
    public function afterComplete();
}
