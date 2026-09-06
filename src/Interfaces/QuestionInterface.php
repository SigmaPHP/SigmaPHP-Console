<?php

namespace SigmaPHP\Console\Interfaces;

use SigmaPHP\Console\IO;

/**
 * Question Interface.
 */
interface QuestionInterface
{
    /**
     * Confirmation.
     *
     * @param string $message
     * @return bool
     */
    public function confirmation($message);

    /**
     * Asking.
     *
     * @param string $question
     * @return string
     */
    public function ask($question);

    /**
     * Choice.
     *
     * @param string $question
     * @param array<string> $options
     * @return string
     */
    public function choice($question, $options);

    /**
     * Ask secret.
     *
     * @param string $question
     * @return string
     */
    public function secret($question);

    /**
     * Set IO handler.
     *
     * @param IO $handler
     * @return void
     */
    public function setIOHandler($handler);
}
