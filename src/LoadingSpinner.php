<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\LoadingSpinnerInterface;

/**
 * Loading Spinner Class.
 *
 * !! This feature requires "pcntl" extension to be installed/enabled.
 * !!
 * !! Also, this feature only supported on UNIX-like systems, since there
 * !! no "pcntl" extension for Windows. Maybe in future this part could be
 * !! replaced with the "parallel" extension, which is by the time of writing
 * !! this comment, still not mature enough.
 */
class LoadingSpinner implements LoadingSpinnerInterface
{
    /**
     * Rendering speed in microseconds.
     */
    public const SPEED = 250000;

    /**
     * Spinner Patterns.
     */
    public $patterns = [
        "arc"      => ["◜", "◝", "◞", "◟"],
        "frames"   => ["|", "/", "-", "\\"],
        "dots"     => [".  ", ".. ", "...", "   "],
        "sweep"    => ['*----', '-*---', '--*--', '---*-', '----*'],
        "pipes"    => ["┤", "┘", "┴", "└", "├", "┌", "┬", "┐"],
        "braille"  => ["⠋", "⠙", "⠹", "⠸", "⠼", "⠴", "⠦", "⠧", "⠇", "⠏"],
        "brackets" => ["[ ]", "[=]", "[==]", "[===]", "[ ==]", "[  =]", "[   ]"]
    ];

    /**
     * @var IO $io
     */
    protected $io;

    /**
     * @var string $pattern
     */
    protected $pattern;

    /**
     * @var string $style
     */
    protected $style;

    /**
     * @var int $childPid
     */
    protected $childPid;

    /**
     * LoadingSpinner Constructor.
     *
     * @param IO $IOHandler
     * @param string $pattern
     * @param string $style
     */
    public function __construct(
        $IOHandler,
        $pattern = 'frames',
        $style = ''
    ) {
        if (!extension_loaded('pcntl')) {
            throw new \RuntimeException(
                "Missing extension 'pcntl', required for Loading Spinner"
            );
        }

        if (!isset($this->patterns[$pattern])) {
            throw new \InvalidArgumentException(
                "Invalid loading spinner's pattern '{$pattern}', kindly " .
                "check the documentation for more information about " .
                "the available loading spinner patterns"
            );
        }

        $this->io = $IOHandler;
        $this->pattern = $pattern;
        $this->style = $style;
        $this->childPid = 0;
    }

    /**
     * Run a loading spinner.
     *
     * @param callable $callback
     * @return void
     */
    public function run($callback)
    {
        // some parallelism magic :)

        pcntl_async_signals(true);

        $this->childPid = pcntl_fork();

        if ($this->childPid === -1) {
            throw new \RuntimeException(
                "Process couldn't be forked!"
            );
        }

        // parent process
        else if ($this->childPid !== 0) {
            pcntl_signal(SIGTERM, function () {
                posix_kill($this->childPid, SIGTERM);
            });

            try {
                $callback();
            } finally {
                // terminate the child process
                posix_kill($this->childPid, SIGTERM);

                // wait for the child status
                pcntl_waitpid($this->childPid, $status);
            }
        }

        // child process = 0
        else {
            pcntl_signal(SIGTERM, function () {
                $this->clean();
            });

            pcntl_signal(SIGINT, function () {
                $this->clean();
            });

            $this->io->hideCursor();

            while (true) {
                $this->io->clear();
                $this->draw();

                usleep(self::SPEED);
            }
        }
    }

    /**
     * Draw new frame.
     *
     * @return void
     */
    protected function draw()
    {
        static $frame = 0;

        if ($frame == count($this->patterns[$this->pattern])) {
            $frame = 0;
        }

        $this->io->write($this->patterns[$this->pattern][$frame], $this->style);

        $this->io->newLine();

        $frame += 1;
    }

    /**
     * Clean after execution.
     *
     * @return void
     */
    protected function clean()
    {
        $this->io->showCursor();

        exit(0);
    }
}
