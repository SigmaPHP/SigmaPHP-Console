<?php

namespace SigmaPHP\Console\Interfaces;

use SigmaPHP\Console\IO;

/**
 * Table Interface.
 */
interface TableInterface
{
    /**
     * Create new table.
     *
     * @param array<string> $header
     * @param array<array<string>> $data
     * @param string $style
     * @param string $border
     * @return void
     */
    public function create($header, $data, $style, $border);

    /**
     * Set IO handler.
     *
     * @param IO $handler
     * @return void
     */
    public function setIOHandler($handler);
}
