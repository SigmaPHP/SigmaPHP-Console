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
}
