<?php

namespace SigmaPHP\Console\Tests\Examples;

use SigmaPHP\Console\Command;

/**
 * Debug Class.
 */
class DebugCommand extends Command
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('debug');
        $this->setDescription('check different internal of the console app');
    }

    /**
     * Execute.
     *`
     * @return void
     */
    public function execute()
    {
        echo $this->appName . PHP_EOL;
    }
}

