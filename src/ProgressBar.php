<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\ProgressBarInterface;

/**
 * Progress Bar Class.
 */
class ProgressBar implements ProgressBarInterface
{
    /**
     * Progress bar width (without the counter !).
     */
    public const BAR_WIDTH = 70;

    /**
     * @var IO $io
     */
    protected $io;

    /**
     * @var string $leftBorder
     */
    protected $leftBorder;

    /**
     * @var string $rightBorder
     */
    protected $rightBorder;

    /**
     * @var string $inProgressSymbol
     */
    protected $inProgressSymbol;

    /**
     * @var string $completeSymbol
     */
    protected $completeSymbol;

    /**
     * @var string $style
     */
    protected $style;

    /**
     * @var int $totalSteps
     *
     * Total number of steps.
     */
    protected $totalSteps;

    /**
     * @var int $currentPosition
     *
     * Current step/position of the bar.
     */
    protected $currentPosition;

    /**
     * ProgressBar Constructor.
     *
     * @param IO $IOHandler
     * @param string $leftBorder
     * @param string $rightBorder
     * @param string $inProgressSymbol
     * @param string $completeSymbol
     * @param string $style
     */
    public function __construct(
        $IOHandler,
        $leftBorder = '[',
        $rightBorder = ']',
        $inProgressSymbol = '-',
        $completeSymbol = '=',
        $style = ''
    ) {
        $this->io = $IOHandler;
        $this->leftBorder = $leftBorder;
        $this->rightBorder = $rightBorder;
        $this->inProgressSymbol = $inProgressSymbol;
        $this->completeSymbol = $completeSymbol;
        $this->style = $style;
    }

    /**
     * Start a progress bar.
     *
     * @param int $total
     * @param int $position
     * @return void
     */
    public function start($total, $position = 0)
    {
        $this->totalSteps = $total;
        $this->currentPosition = $position;
    }

    /**
     * Update a progress bar.
     *
     * @param int $steps
     * @return void
     */
    public function update($steps)
    {
        $this->currentPosition += $steps;
        $this->io->clear();
        $this->draw();
    }

    /**
     * End a progress bar.
     *
     * @return void
     */
    public function end()
    {
        $this->totalSteps = 0;
        $this->currentPosition = 0;
    }

    /**
     * Draw new frame.
     *
     * @return void
     */
    protected function draw()
    {
        $counter = floor(($this->currentPosition / $this->totalSteps) * 100);
        $complete = floor(($counter * self::BAR_WIDTH) / 100);

        $this->io->write($this->leftBorder, $this->style);

        for ($i = 0;$i < self::BAR_WIDTH;$i++) {
            if ($i <= $complete) {
                $this->io->write($this->completeSymbol, $this->style);
            } else {
                $this->io->write($this->inProgressSymbol, $this->style);
            }
        }

        $this->io->write($this->rightBorder, $this->style);

        $this->io->write(' ' . $counter . '%', $this->style);

        $this->io->newLine();
    }
}
