<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\ColorInterface;

/**
 * Color Class.
 *
 * Supporting both standard ANSI 16 colors and ANSI 256-Colors.
 *
 * Reference:
 * https://misc.flogisoft.com/bash/tip_colors_and_formatting
 */
class Color implements ColorInterface
{
    /**
     * Reset sequence.
     */
    public const RESET = "\033[0m";

    /**
     * Foreground.
     */
    public const FG_DEFAULT       = "39";
    public const FG_BLACK         = "30";
    public const FG_RED           = "31";
    public const FG_GREEN         = "32";
    public const FG_YELLOW        = "33";
    public const FG_BLUE          = "34";
    public const FG_MAGENTA       = "35";
    public const FG_CYAN          = "36";
    public const FG_LIGHT_GRAY    = "37";
    public const FG_DARK_GRAY     = "90";
    public const FG_LIGHT_RED     = "91";
    public const FG_LIGHT_GREEN   = "92";
    public const FG_LIGHT_YELLOW  = "93";
    public const FG_LIGHT_BLUE    = "94";
    public const FG_LIGHT_MAGENTA = "95";
    public const FG_LIGHT_CYAN    = "96";
    public const FG_WHITE         = "97";

    /**
     * Background.
     */
    public const BG_DEFAULT = "49";
    public const BG_BLACK = "40";
    public const BG_RED = "41";
    public const BG_GREEN = "42";
    public const BG_YELLOW = "43";
    public const BG_BLUE = "44";
    public const BG_MAGENTA = "45";
    public const BG_CYAN = "46";
    public const BG_LIGHT_GRAY = "47";
    public const BG_DARK_GRAY = "100";
    public const BG_LIGHT_RED = "101";
    public const BG_LIGHT_GREEN = "102";
    public const BG_LIGHT_YELLOW = "103";
    public const BG_LIGHT_BLUE = "104";
    public const BG_LIGHT_MAGENTA = "105";
    public const BG_LIGHT_CYAN = "106";
    public const BG_WHITE = "107";

    /**
     * Colorize a text using standard ANSI escape characters.
     *
     * @param string $text
     * @param string $color
     * @param bool $bg
     * @return string
     */
    public function colorize($text, $color, $bg = false)
    {

    }

    /**
     * Get foreground color sequence.
     *
     * @param string $color
     * @return string
     */
    protected function getFG($color)
    {
        if (($color < 1) || ($color > 256)) {
            $color = self::FG_DEFAULT;
        }

        // ToDo: check the 16 or 256 for the correct sequence

        return ($color > 16) ? "" : "\033[{$color}m";
    }

    /**
     * Get background color sequence.
     *
     * @param string $color
     * @return string
     */
    protected function getBG($color)
    {
        if (($color < 1) || ($color > 256)) {
            $color = self::FG_DEFAULT;
        }

        // ToDo: check the 16 or 256 for the correct sequence

        return ($color > 16) ? "" : "\033[{$color}m";
    }
}
