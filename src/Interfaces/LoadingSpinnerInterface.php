<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * Loading Spinner Interface.
 */
interface LoadingSpinnerInterface
{
    /**
     * Start a loading spinner.
     *
     * @return void
     */
    public function start();

    /**
     * Stop a loading spinner.
     *
     * @return void
     */
    public function stop();
}
