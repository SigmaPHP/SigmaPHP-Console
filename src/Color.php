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
    public $fgColors = [
        "default"       => "39",
        "black"         => "30",
        "red"           => "31",
        "green"         => "32",
        "yellow"        => "33",
        "blue"          => "34",
        "magenta"       => "35",
        "cyan"          => "36",
        "light_gray"    => "37",
        "dark_gray"     => "90",
        "light_red"     => "91",
        "light_green"   => "92",
        "light_yellow"  => "93",
        "light_blue"    => "94",
        "light_magenta" => "95",
        "light_cyan"    => "96",
        "white"         => "97"
    ];

    /**
     * Background.
     */
    public $bgColors = [
        "default"       => "49",
        "black"         => "40",
        "red"           => "41",
        "green"         => "42",
        "yellow"        => "43",
        "blue"          => "44",
        "magenta"       => "45",
        "cyan"          => "46",
        "light_gray"    => "47",
        "dark_gray"     => "100",
        "light_red"     => "101",
        "light_green"   => "102",
        "light_yellow"  => "103",
        "light_blue"    => "104",
        "light_magenta" => "105",
        "light_cyan"    => "106",
        "white"         => "107"
    ];

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
    public function colorize($text, $color, $bg = false)
    {
        if (is_numeric($color) && (($color < 1) || ($color > 256))) {
            throw new \InvalidArgumentException(
                "Invalid color value '{$color}', ANSI-256 colors " .
                "can only accept values between 1 and 256"
            );
        }
        else if (!is_numeric($color) && !isset($this->bgColors[$color])) {
            throw new \InvalidArgumentException(
                "Invalid ANSI-16 color value '{$color}', kindly " .
                "check the documentation for more information about " .
                "the available ANSI-16 colors"
            );
        }

        $colorSequence = $bg ?
            $this->getBG($color, is_numeric($color)) :
            $this->getFG($color, is_numeric($color));

        return $colorSequence . $text . self::RESET;
    }

    /**
     * Get foreground color sequence.
     *
     * @param string|int $color
     * @param bool $is256
     * @return string
     */
    protected function getFG($color, $is256 = false)
    {
        return $is256 ?
            "\033[38;5;{$color}m" :
            "\033[{$this->fgColors[$color]}m";
    }

    /**
     * Get background color sequence.
     *
     * @param string|int $color
     * @param bool $is256
     * @return string
     */
    protected function getBG($color, $is256)
    {
        return $is256 ?
            "\033[48;5;{$color}m" :
            "\033[{$this->bgColors[$color]}m";
    }
}
