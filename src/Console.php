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
     * @var resource $inputStream
     */
    protected $inputStream;

    /**
     * Console Constructor.
     */
    public function __construct() {
        $this->outputStream = fopen('php://stdout', 'w');
        $this->errorStream = fopen('php://stderr', 'w');
        $this->inputStream = fopen('php://stdin', 'r');
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
     * Set the input stream.
     *
     * @param resource $stream
     * @return bool
     */
    public function setInputStream($stream)
    {
        $this->inputStream = $stream;
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
        return fgets($this->inputStream) ?: '';
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
