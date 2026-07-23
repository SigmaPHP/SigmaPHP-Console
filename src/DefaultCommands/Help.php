<?php

namespace SigmaPHP\Console\DefaultCommands;

use SigmaPHP\Console\Command;

/**
 * Help Class.
 */
class Help extends Command
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('help');
        $this->setDescription('Print this help menu');
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        $helpContent = "Usage:\n";
        $helpContent .= "\tapp [COMMAND] [OPTIONS] [--] [ARGUMENTS]\n";

        $this->console->write($helpContent);
    }
}
