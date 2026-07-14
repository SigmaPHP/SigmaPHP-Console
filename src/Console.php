<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\ConsoleInterface;

/**
 * Console Class.
 */
class Console implements ConsoleInterface
{
    /**
     * Write to console (STDOUT).
     *
     * @param string $text
     * @return bool
     */
    public function write($text)
    {
        return fwrite(STDOUT, $text) !== false;
    }

    /**
     * Write to console (STDERR).
     *
     * @param string $text
     * @return bool
     */
    public function writeErr($text)
    {
        return fwrite(STDERR, $text) !== false;
    }

    /**
     * Read from console.
     *
     * @return string
     */
    public function read()
    {
        return fgets(STDIN) ?? '';
    }

    /**
     * Check if console supports colors.
     *
     * @return bool
     */
    public function isSupportColor()
    {
        return (
            ((exec('tput colors') != -1) || stream_isatty(STDOUT)) &&
            !isset($_SERVER['NO_COLOR'])
        );
    }
}
