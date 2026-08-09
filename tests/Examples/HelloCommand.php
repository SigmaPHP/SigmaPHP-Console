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

        $this->addArgument('name');

        $this->addOption('greeting', 'g');
        $this->addOption('title', 't');
    }

    /**
     * Execute.
     *`
     * @return void
     */
    public function execute()
    {
        $buffer = 'Hello ';

        if ($this->input->hasOption('greeting')) {
            $buffer = $this->input->getOption('greeting') . ' ';
        }

        if ($this->input->hasOption('title')) {
            $buffer .= $this->input->getOption('title') . ' ';
        }

        if ($this->input->hasArgument('name')) {
            $buffer .= $this->input->getArgument('name');
        }

        echo $buffer . PHP_EOL;
    }
}

