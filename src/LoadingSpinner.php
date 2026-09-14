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
    public const SPEED = 250;

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

        $this->io = $IOHandler;
        $this->pattern = $pattern;
        $this->style = $style;

        if (!isset($this->patterns[$pattern])) {
            throw new \InvalidArgumentException(
                "Invalid loading spinner's pattern '{$pattern}', kindly " .
                "check the documentation for more information about " .
                "the available loading spinner patterns"
            );
        }
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

        // pcntl_signal(SIGTERM, $this->clean());
        // pcntl_signal(SIGQUIT, $this->clean());
        // pcntl_signal(SIGINT, $this->clean());
        pcntl_async_signals(true);

        $pId = pcntl_fork();

        if ($pId == -1) {
            die("Process couldn't be forked!");
        }
        // parent process = $pId
        else if ($pId) {
            pcntl_wait($status);

            // hide cursor
            $this->io->write("\033[?25l");

            while (true) {
                $this->io->clear();
                $this->draw();

                usleep(self::SPEED);
            }
        }
        // child process = 0
        else {
            $callback();
            $this->io->write("\033[?25h");

            exit;
            // $this->clean();
        }

        // detach from the controlling terminal
        if (posix_setsid() == -1) {
            die("Could not detach from terminal!");
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
        // show cursor
        $this->io->write("\033[?25h");

        exit;
    }
}
