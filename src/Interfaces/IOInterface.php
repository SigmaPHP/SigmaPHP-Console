<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * IO Interface.
 */
interface IOInterface
{
    /**
     * Set the output stream.
     *
     * @param resource $stream
     * @return void
     */
    public function setOutputStream($stream);

    /**
     * Set the error stream.
     *
     * @param resource $stream
     * @return void
     */
    public function setErrorStream($stream);

    /**
     * Set the input stream.
     *
     * @param resource $stream
     * @return bool
     */
    public function setInputStream($stream);

    /**
     * Set is quiet flag.
     *
     * @param bool $value
     * @return void
     */
    public function setIsQuiet($value);

    /**
     * Set is silent flag.
     *
     * @param bool $value
     * @return void
     */
    public function setIsSilent($value);

    /**
     * Write to console (STDOUT).
     *
     * @param string $text
     * @param string $style
     * @return int|false
     */
    public function write($text, $style);

    /**
     * Write to console (STDOUT) with new line.
     *
     * @param string $text
     * @param string $style
     * @return int|false
     */
    public function writeln($text, $style);

    /**
     * Write to console (STDERR).
     *
     * @param string $text
     * @param string $style
     * @return int|false
     */
    public function writeErr($text, $style);

    /**
     * Read from console.
     *
     * @return string|false
     */
    public function read();

    /**
     * Check if console supports colors.
     *
     * @return bool
     */
    public function hasColorSupport();
}
