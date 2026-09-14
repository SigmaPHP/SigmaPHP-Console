<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * Loading Spinner Interface.
 */
interface LoadingSpinnerInterface
{
    /**
     * Run a loading spinner.
     *
     * @param callable $callback
     * @return void
     */
    public function run($callback);
}
