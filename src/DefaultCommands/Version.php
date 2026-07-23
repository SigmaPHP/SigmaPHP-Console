<?php

namespace SigmaPHP\Console\DefaultCommands;

use SigmaPHP\Console\Command;

/**
 * Version Class.
 */
class Version extends Command
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('version');
        $this->setDescription('Print the application\'s version');
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $this->console->write('v1.0.0');
    }
}
