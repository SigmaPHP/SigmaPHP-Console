<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\ConsoleInterface;

/**
 * Console Class.
 */
class Console implements ConsoleInterface
{
    /**
     * @var resource $outputStream
     */
    protected $outputStream;

    /**
     * @var resource $errorStream
     */
    protected $errorStream;

    /**
     * Console Constructor.
     */
    public function __construct() {
        $this->outputStream = \STDOUT;
        $this->errorStream = \STDERR;
    }

    /**
     * Set the output stream.
     *
     * @param resource $stream
     * @return bool
     */
    public function setOutputStream($stream)
    {
        $this->outputStream = $stream;
    }

    /**
     * Set the error stream.
     *
     * @param resource $stream
     * @return bool
     */
    public function setErrorStream($stream)
    {
        $this->errorStream = $stream;
    }

    /**
     * Write to console (STDOUT).
     *
     * @param string $text
     * @return bool
     */
    public function write($text)
    {
        return fwrite($this->outputStream, $text) !== false;
    }

    /**
     * Write to console (STDERR).
     *
     * @param string $text
     * @return bool
     */
    public function writeErr($text)
    {
        return fwrite($this->errorStream, $text) !== false;
    }

    /**
     * Read from console.
     *
     * @return string
     */
    public function read()
    {
        return fgets(\STDIN) ?? '';
    }

    /**
     * Check if console supports colors.
     *
     * @return bool
     */
    public function hasColorSupport()
    {
        return (
            (
                (exec('tput colors') != -1) ||
                stream_isatty(STDOUT) ||
                isset($_SERVER['FORCE_COLOR'])
            ) &&
            !isset($_SERVER['NO_COLOR'])
        );
    }
}
