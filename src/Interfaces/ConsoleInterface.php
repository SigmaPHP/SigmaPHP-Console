<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * Console Interface.
 */
interface ConsoleInterface
{
    /**
     * Write to console (STDOUT).
     *
     * @param string $text
     * @return void
     */
    public function write($text);

    /**
     * Write to console (STDERR).
     *
     * @param string $text
     * @return void
     */
    public function writeErr($text);

    /**
     * Read from console.
     *
     * @return string
     */
    public function read();

    /**
     * Check if console supports colors.
     *
     * @return string // ansi, 256 or empty
     */
    public function isSupportColor();
}
