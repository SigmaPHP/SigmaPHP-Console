<?php

namespace SigmaPHP\Console\Interfaces;

use SigmaPHP\Console\Command;

/**
 * App Interface.
 */
interface AppInterface
{
    /**
     * Load commands from directory.
     *
     * @param string $path
     * @return void
     */
    public function loadCommands($path);

    /**
     * Add command to app.
     *
     * @param Command $command
     * @return void
     */
    public function addCommand($command);

    /**
     * Remove command from app.
     *
     * @param string $commandName
     * @return void
     */
    public function removeCommand($commandName);

    /**
     * Check the provided arguments and options, make sure they are related
     * to the Command.
     *
     * @return bool
     */
    public function validateInput();

    /**
     * Run the app.
     *
     * @return int
     */
    public function run();

    /**
     * Check if command exists in app.
     *
     * @param string $commandName
     * @return bool
     */
    public function hasCommand($commandName);

    /**
     * Get command/s from app.
     *
     * @param string $name
     * @return array<Command>|Command
     */
    public function getCommands($name);

    /**
     * Add global option.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param regex $validation
     * @return void
     */
    public function addGlobalOption(
        $name,
        $shortcut,
        $description,
        $validation
    );

    /**
     * Disable the default functions (version & help).
     *
     * @return void
     */
    public function disableDefaultFunctions();

    /**
     * Set app's title.
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title);

    /**
     * Get app's title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Customize app's welcome banner (header/title), that could include name,
     * copy rights, some ascii-art or what so ever :)
     *
     * By default this method will print the app's title if set.
     *
     * This method will be used inside the `Help` default command.
     *
     * @return void
     */
    public function welcome();

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
