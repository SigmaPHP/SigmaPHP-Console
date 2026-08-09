<?php

namespace SigmaPHP\Console\DefaultCommands;

use SigmaPHP\Console\Command;

/**
 * Help Class.
 */
class Help extends Command
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
     * @var array $commandsList
     */
    protected $commandsList;

    /**
     * @var array $globalOptionsList
     */
    protected $globalOptionsList;

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
     * Get app's name.
     *
     * @return string
     */
    public function getAppName()
    {
        return $this->appName;
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
     * Get app's description.
     *
     * @return string
     */
    public function getAppDescription()
    {
        return $this->appDescription;
    }

    /**
     * Set commands list.
     *
     * @param array $commandsList
     * @return void
     */
    public function setCommandsList($commandsList)
    {
        $this->commandsList = $commandsList;
    }

    /**
     * Get commands list.
     *
     * @return array
     */
    public function getCommandsList()
    {
        return $this->commandsList;
    }

    /**
     * Set global options list.
     *
     * @param array $globalOptionsList
     * @return void
     */
    public function setGlobalOptionsList($globalOptionsList)
    {
        $this->globalOptionsList = $globalOptionsList;
    }

    /**
     * get global options list.
     *
     * @return array
     */
    public function getGlobalOptionsList()
    {
        return $this->globalOptionsList;
    }

    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('help');
        $this->setDescription('Print the help menu');

        // app's default details
        $this->setAppName('App');
        $this->setAppDescription('A CLI utility to preform some tasks');
        $this->setCommandsList([]);
        $this->setGlobalOptionsList([]);
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $helpContent = "{$this->appDescription}\n\n";

        $helpContent .= "Usage:\n";
        $helpContent .= "\tapp [COMMAND] [OPTIONS] [--] [ARGUMENTS]\n\n";

        if (!empty($this->commandsList)) {
            $helpContent .= "Available Commands:\n";

            foreach ($this->commandsList as $name => $description) {
                $helpContent .= "\t{$name}\t\t{$description}\n";
            }
        }

        $helpContent .= "\n";

        if (!empty($this->globalOptionsList)) {
            $helpContent .= "Global Options:\n";

            foreach ($this->globalOptionsList as $name => $option) {
                $helpContent .= "\t-{$option['shortcut']}, --{$name}" .
                    "\t\t{$option['description']}\n";
            }
        }

        $helpContent .= "\n";

        $helpContent .= "Run {$this->appName} [COMMAND] --help to get ";
        $helpContent .= "more information on a command\n";

        $this->io->write($helpContent);
    }
}
