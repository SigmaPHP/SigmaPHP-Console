<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * Color Interface.
 */
interface ColorInterface
{
    /**
     * Colorize a text using standard ANSI escape characters.
     *
     * @param string $text
     * @param string $color
     * @param bool $bg
     * @return string
     */
    public function colorize($text, $color, $bg = false);
}
