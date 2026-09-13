<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\LoadingSpinnerInterface;

/**
 * Loading Spinner Class.
 */
class LoadingSpinner implements LoadingSpinnerInterface
{
    /**
     * Rendering speed in microseconds.
     */
    public const SPEED = 100;

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
     * @var bool $run
     */
    protected $run;

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
        $this->io = $IOHandler;
        $this->pattern = $pattern;
        $this->style = $style;
        $this->run = false;

        if (!isset($this->patterns[$pattern])) {
            throw new \InvalidArgumentException(
                "Invalid loading spinner's pattern '{$pattern}', kindly " .
                "check the documentation for more information about " .
                "the available loading spinner patterns"
            );
        }
    }

    /**
     * Start a loading spinner.
     *
     * @return void
     */
    public function start()
    {
        $this->run = true;

        $this->io->write("\033[?25l");

        while ($this->run) {
            $this->io->clear();
            $this->draw();

            usleep(self::SPEED);
        }
    }

    /**
     * Stop a loading spinner.
     *
     * @return void
     */
    public function stop()
    {
        $this->run = false;
        $this->io->write("\033[?25h");
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
}
