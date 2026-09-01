<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * TextFormatter Interface.
 */
interface TextFormatterInterface
{
    /**
     * Format text.
     *
     * @param string $text
     * @param string $style
     * @return string
     */
    public function format($text, $style);
}
