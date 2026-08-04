<?php

namespace SigmaPHP\Console\Tests\Examples;

use SigmaPHP\Console\Command;

/**
 * Hello Class.
 */
class HelloCommand extends Command
{
    /**
     * Initialize the command.
     *
     * @return void
     */
    public function init()
    {
        $this->setName('hello');
        $this->setDescription('say hello to user');
    }

    /**
     * Execute.
     *
     * @return void
     */
    public function execute()
    {
        echo 'Hello' . PHP_EOL;
    }
}

