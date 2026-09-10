<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\ProgressBarInterface;

/**
 * Progress Bar Class.
 */
class ProgressBar implements ProgressBarInterface
{
    /**
     * @var IO $io
     */
    protected $io;

    /**
     * ProgressBar Constructor.
     *
     * @param IO $IOHandler
     */
    public function __construct($IOHandler)
    {
        $this->io = $IOHandler;
    }

}
