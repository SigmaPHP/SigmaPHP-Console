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
     * Color could be standard ANSI 16 colors or ANSI 256-Colors, depending on
     * terminal used itself, so $color accept both color names and HEX values.
     *
     * @param string $text
     * @param string $color
     * @return string
     */
    public function colorize($text, $color);
}
