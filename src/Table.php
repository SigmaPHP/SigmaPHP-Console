<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\TableInterface;

/**
 * Table Class.
 */
class Table implements TableInterface
{
    /**
     * @var IO $io
     */
    protected $io;

    /**
     * Create new table.
     *
     * @param array<string> $header
     * @param array<array<string>> $data
     * @param string $style
     * @param string $border
     * @return void
     */
    public function create($header, $data, $style = '', $border = '*')
    {
        // height = (top border) + header + (header separator) + data rows +
        //        = (bottom border)
        $height = 4 + count($data);

        // width = (left border) + ((cell width) + (right border for each cell))
        $width = 1;

        $maxCellWidth = 0;

        foreach ($data as $row) {
            foreach ($row as $cell) {
                if (strlen($cell) > $maxCellWidth) {
                    $maxCellWidth = strlen($cell);
                }

                $width += strlen($cell) + 1;
            }
        }

        // ToDo: create $matrix, that will contain all chars in one array

        // render
        for ($i = 0;$i < $height;$i++) {
            for ($j = 0;$j < $width;$j++) {
                // top border
                if ($i == 0) {
                    $this->io->write($border, $style);
                }
                // header
                else if ($i == 1) {
                    // left border
                    if ($j == 0) {
                        $this->io->write($border, $style);
                    }
                    // data
                    else {
                        $this->io->write(' ' . $border, $style);
                    }
                }
                // header separator
                else if ($i == 2) {
                    $this->io->write($border, $style);
                }
                // bottom border
                else if ($i == ($height - 1)) {
                    $this->io->write($border, $style);
                }
                // data
                else {
                    // left border
                    if ($j == 0) {
                        $this->io->write($border, $style);
                    }
                    // data
                    else {
                        $this->io->write(' ' . $border, $style);
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
}
