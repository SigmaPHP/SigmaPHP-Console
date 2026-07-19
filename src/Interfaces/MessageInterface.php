<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * Message Interface.
 */
interface MessageInterface
{
    /**
     * Show success message.
     *
     * @param string $message
     * @return void
     */
    public function success($message);

    /**
     * Show info message.
     *
     * @param string $message
     * @return void
     */
    public function info($message);

    /**
     * Show warning message.
     *
     * @param string $message
     * @return void
     */
    public function warning($message);

    /**
     * Show error message.
     *
     * @param string $message
     * @return void
     */
    public function error($message);
}
