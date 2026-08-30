<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\IOInterface;

/**
 * IO Class.
 */
class IO implements IOInterface
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
     * @var bool $isQuiet
     */
    protected $isQuiet;

    /**
     * @var bool $isSilent
     */
    protected $isSilent;

    /**
     * IO Constructor.
     *
     */
    public function __construct() {
        $this->outputStream = fopen('php://stdout', 'w');
        $this->errorStream = fopen('php://stderr', 'w');
        $this->inputStream = fopen('php://stdin', 'r');

        $this->isQuiet = false;
        $this->isSilent = false;
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
     * Set is quiet flag.
     *
     * @param bool $value
     * @return void
     */
    public function setIsQuiet($value)
    {
        $this->isQuiet = $value;
    }

    /**
     * Set is silent flag.
     *
     * @param bool $value
     * @return void
     */
    public function setIsSilent($value)
    {
        $this->isSilent = $value;
    }

    /**
     * Write to console (STDOUT).
     *
     * @param string $text
     * @return int|false
     */
    public function write($text)
    {
        return $this->isQuiet ? false: fwrite($this->outputStream, $text);
    }

    /**
     * Write to console (STDOUT) with new line.
     *
     * @param string $text
     * @return int|false
     */
    public function writeln($text)
    {
        return $this->write($text . "\n");
    }

    /**
     * Write to console (STDERR).
     *
     * @param string $text
     * @return int|false
     */
    public function writeErr($text)
    {
        return $this->isSilent ? false: fwrite($this->errorStream, $text);
    }

    /**
     * Read from console.
     *
     * @return string|false
     */
    public function read()
    {
        return fgets($this->inputStream);
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
