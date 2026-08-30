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
        $this->setAliases(['old_debug']);
    }

    /**
     * Execute.
     *`
     * @return void
     */
    public function execute()
    {
        if ($this->hasOption('verbose')) {
            echo $this->getName() . PHP_EOL;
            echo $this->getDescription() . PHP_EOL;
        }

        echo $this->appName . PHP_EOL;
    }
}

