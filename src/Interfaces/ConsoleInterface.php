<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * Console Interface.
 */
interface ConsoleInterface
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
