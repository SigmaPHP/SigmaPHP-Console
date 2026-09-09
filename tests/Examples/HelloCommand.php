<?php

namespace SigmaPHP\Console\Tests\Examples;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\Option;

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

        $this->addOption('greeting', 'g',
            'Greeting verb like hi, hello..etc', Option::PARAMETER_REQUIRED);
        $this->addOption('title', 't',
            'User\'s title like Mr., Ms. ..etc', Option::PARAMETER_REQUIRED);
    }

    /**
     * Execute.
     *`
     * @return void
     */
    public function execute()
    {
        $buffer = 'Hello ';

        if ($this->hasOption('greeting')) {
            $buffer = $this->getOption('greeting') . ' ';
        }

        if ($this->hasOption('title')) {
            $buffer .= $this->getOption('title') . ' ';
        }

        if ($this->hasArgument('name')) {
            $buffer .= $this->getArgument('name');
        }

        echo $buffer . PHP_EOL;
    }
}

