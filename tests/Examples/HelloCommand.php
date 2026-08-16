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

        $this->addArgument('name', 'User\'s name that we want to greet');

        $this->addOption('greeting', 'g', 'Greeting verb like hi, hello..etc');
        $this->addOption('title', 't', 'User\'s title like Mr., Ms. ..etc');
    }

    /**
     * Execute.
     *`
     * @return void
     */
    public function execute()
    {
        $buffer = 'Hello ';

        if ($this->getOption('greeting')) {
            $buffer = $this->getOption('greeting')->getValue() . ' ';
        }

        if ($this->getOption('title')) {
            $buffer .= $this->getOption('title')->getValue() . ' ';
        }

        if ($this->getArgument('name')) {
            $buffer .= $this->getArgument('name')->getValue();
        }

        echo $buffer . PHP_EOL;
    }
}

