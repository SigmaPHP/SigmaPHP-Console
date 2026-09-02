<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\TextFormatterInterface;

/**
 * TextFormatter Class.
 *
 * Reference:
 * https://misc.flogisoft.com/bash/tip_colors_and_formatting
 */
class TextFormatter implements TextFormatterInterface
{
    /**
     * Reset sequence.
     */
    public const RESET = "\033[0m";

    /**
     * Styles.
     */
    public $styles = [
        "bold"      => "1",
        "dim"       => "2",
        "underline" => "4",
        "blink"     => "5",
        "reverse"   => "7",
        "hidden"    => "8",
    ];

    /**
     * Format text.
     *
     * Please Note:
     * - $style could accept semi-colon ";" separated string 'bold;dim' e.g
     *
     * @param string $text
     * @param string $style
     * @return string
     */
    public function format($text, $style)
    {
        $styles = explode(';', $style);

        $sequence = "\033[";

        foreach ($styles as $_style) {
            if (!isset($this->styles[$_style])) {
                throw new \InvalidArgumentException(
                    "Invalid text format '{$_style}', kindly check the " .
                    "documentation for more information about text formatting"
                );
            }

            $sequence .= $this->styles[$_style] . ';';
        }

        $sequence = trim($sequence, ';') . 'm';

        return $sequence . $text . self::RESET;
    }
}
