<?php

namespace SigmaPHP\Console\Interfaces;

use SigmaPHP\Console\IO;

/**
 * Box Interface.
 */
interface BoxInterface
{
    /**
     * Create new box.
     *
     * @param string $text
     * @param string $style
     * @param string $border
     * @return void
     */
    public function create($text, $style, $border);

    /**
     * Set IO handler.
     *
     * @param IO $handler
     * @return void
     */
    public function setIOHandler($handler);

    /**
     * Create info box.
     *
     * @param string $text
     * @return void
     */
    public function info($text);

    /**
     * Create success box.
     *
     * @param string $text
     * @return void
     */
    public function success($text);

    /**
     * Create warning box.
     *
     * @param string $text
     * @return void
     */
    public function warning($text);
    /**
     * Create error box.
     *
     * @param string $text
     * @return void
     */
    public function error($text);
}
