<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\BoxInterface;

/**
 * Box Class.
 */
class Box implements BoxInterface
{
    /**
     * @var IO $io
     */
    protected $io;

    /**
     * Create new box.
     *
     * @param string $text
     * @param string $style
     * @param string $border
     * @return void
     */
    public function create($text, $style = '', $border = '*')
    {
        $lines = explode("\n", $text);

        // height = (1 line before) + (broken lines) + (1 line after) +
        //          (2 borders)
        $height = count($lines) + 2 + 2;

        // width = (10 chars before) + (max line chars) + (10 chars after) +
        //       = (2 borders)
        $maxLine = 0;

        foreach ($lines as $line) {
            if (strlen($line) > $maxLine) {
                $maxLine = strlen($line);
            }
        }

        $width = $maxLine + 20 + 2;

        for ($i = 0;$i < count($lines);$i++) {
            $lines[$i] = str_pad($lines[$i], $width, ' ', STR_PAD_BOTH);
        }

        for ($i = 0;$i < $height;$i++) {
            for ($j = 0;$j < $width;$j++) {
                // top border
                if ($i == 0) {
                    $this->io->write($border, $style);
                }
                // top padding
                else if ($i == 1) {
                    // left border
                    if ($j == 0) {
                        $this->io->write($border, $style);
                    }
                    // right border
                    else if ($j == ($width - 1)) {
                        $this->io->write($border, $style);
                    }
                    // padding
                    else {
                        $this->io->write(' ', $style);
                    }
                }
                // bottom padding
                else if ($i == ($height - 2)) {
                    // left border
                    if ($j == 0) {
                        $this->io->write($border, $style);
                    }
                    // right border
                    else if ($j == ($width - 1)) {
                        $this->io->write($border, $style);
                    }
                    // padding
                    else {
                        $this->io->write(' ', $style);
                    }
                }
                // bottom border
                else if ($i == ($height - 1)) {
                    $this->io->write($border, $style);
                }
                // print line
                else if (($i > 1) && ($i < ($height - 2))) {
                    // left border
                    if ($j == 0) {
                        $this->io->write($border, $style);
                    }
                    // right border
                    else if ($j == ($width - 1)) {
                        $this->io->write($border, $style);
                    }
                    // print char
                    else {
                        if (isset($lines[$i - 2][$j - 1])) {
                            $this->io->write($lines[$i - 2][$j - 1], $style);
                        } else {
                            $this->io->write(' ', $style);
                        }
                    }
                }
            }

            $this->io->writeln('');
        }
    }

    /**
     * Set IO handler.
     *
     * @param IO $handler
     * @return void
     */
    public function setIOHandler($handler)
    {
        $this->io = $handler;
    }

    /**
     * Create info box.
     *
     * @param string $text
     * @return void
     */
    public function info($text)
    {
        $this->create($text, 'fg=light_blue;bold', ' ');
    }

    /**
     * Create success box.
     *
     * @param string $text
     * @return void
     */
    public function success($text)
    {
        $this->create($text, 'fg=light_green;bold', ' ');
    }

    /**
     * Create warning box.
     *
     * @param string $text
     * @return void
     */
    public function warning($text)
    {
        $this->create($text, 'fg=light_yellow;bold', ' ');
    }

    /**
     * Create error box.
     *
     * @param string $text
     * @return void
     */
    public function error($text)
    {
        $this->create($text, 'fg=light_red;bold', ' ');
    }
}
