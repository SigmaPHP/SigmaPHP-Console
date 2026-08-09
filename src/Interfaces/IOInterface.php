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
     * Write to console (STDOUT).
     *
     * @param string $text
     * @return bool
     */
    public function write($text);

    /**
     * Write to console (STDOUT) with new line.
     *
     * @param string $text
     * @return bool
     */
    public function writeln($text);

    /**
     * Write to console (STDERR).
     *
     * @param string $text
     * @return bool
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
     * @return bool
     */
    public function hasColorSupport();
}
