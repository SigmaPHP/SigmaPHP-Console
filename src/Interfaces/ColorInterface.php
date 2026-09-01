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
     * Please Note:
     * - For ANSI-16 use string color values
     * - For ANSI-256 use integer color values
     *
     * @param string $text
     * @param string|int $color
     * @param bool $bg
     * @return string
     */
    public function colorize($text, $color, $bg = false);
}
