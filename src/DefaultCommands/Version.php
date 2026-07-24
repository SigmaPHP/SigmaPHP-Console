<?php

namespace SigmaPHP\Console\DefaultCommands;

use SigmaPHP\Console\Command;

/**
 * Version Class.
 */
class Version extends Command
{
    /**
     * @var string $appVersion
     */
    protected $appVersion;

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
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('version');
        $this->setDescription('Print the application\'s version');

        $this->setAppVersion('v1.0.0');
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $this->console->write("{$this->appVersion}");
    }
}
