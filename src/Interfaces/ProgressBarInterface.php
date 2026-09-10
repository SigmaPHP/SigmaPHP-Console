<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * Progress Bar Interface.
 */
interface ProgressBarInterface
{
    /**
     * Start a new progress bar.
     *
     * @param int $position
     * @return void
     */
    public function start($position);

    /**
     * Update a progress bar.
     *
     * @param int $steps
     * @return void
     */
    public function update($steps);

    /**
     * End a progress bar.
     *
     * @return void
     */
    public function end();
}
