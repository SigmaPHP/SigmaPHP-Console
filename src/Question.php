<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\QuestionInterface;
use SigmaPHP\Console\IO;

/**
 * Question Class.
 */
class Question implements QuestionInterface
{
    /**
     * @var IO $io
     */
    protected $io;

    /**
     * Confirmation.
     *
     * @param string $message
     * @return bool
     */
    public function confirmation($message)
    {
        $this->io->writeln($message . ' [Y/n]');

        $input = $this->io->read();

        if (in_array(strtolower($input), ['yes', 'y'])) {
            return true;
        }
        else if (in_array(strtolower($input), ['no', 'n'])) {
            return false;
        }
        else {
            throw new \InvalidArgumentException(
                "Invalid input value '{$input}', only [Yes/No/Y/N] are accepted"
            );
        }
    }

    /**
     * Asking.
     *
     * @param string $question
     * @return string
     */
    public function ask($question)
    {
        $this->io->writeln($question);

        return $this->io->read();
    }

    /**
     * Choice.
     *
     * @param string $question
     * @param array<string> $options
     * @return string
     */
    public function choice($question, $options)
    {
        $this->io->writeln($question);

        foreach ($options as $index => $option) {
            $this->io->writeln("{$index} => {$option}");
        }

        $input = $this->io->read();

        if (!in_array($input, $options)) {
            throw new \InvalidArgumentException(
                "Unknown option '{$input}'"
            );
        } else {
            return $input;
        }
    }

    /**
     * Ask secret.
     *
     * @param string $question
     * @return string
     */
    public function secret($question)
    {
        $this->io->writeln($question);

        // ToDo: add support for windows
        system('stty -echo');

        $input = $this->io->read();

        system('stty echo');

        $this->io->writeln('');

        return $input;
    }

    /**
     * Set IO handler.
     *
     * @param IO $handler
     * @return void
     */
    public function setIOHandler($handler)
    {
        $this->io = $handler;
    }
}
