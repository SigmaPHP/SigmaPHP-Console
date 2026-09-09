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
     * @return void
     */
    public function create($header, $data, $style = '')
    {
        $data = array_merge([$header], $data);

        // extract max number of chars for each column
        // these values will be used to align each cell inside
        // the column, so all match exact width
        $maxCellWidth = array_fill(0, count($header), 0);

        foreach ($data as $row) {
            foreach ($row as $index => $column) {
                if (strlen(' ' . $column . ' ') > $maxCellWidth[$index]) {
                    $maxCellWidth[$index] = strlen(' ' . $column . ' ');
                }
            }
        }

        // prepare the render buffer
        $lines = [];
        $_line = '';
        $vBorder = '|';
        $hBorder = '-';

        foreach ($data as $row) {
            $_line = $vBorder;

            foreach ($row as $index => $column) {
                $column = str_replace(["\n", "\r"], '', $column);

                $_line .= str_pad((' ' . $column . ' '),
                    $maxCellWidth[$index], ' ', STR_PAD_RIGHT) . $vBorder;
            }

            $lines[] = $_line;
        }

        // do render
        for ($i = 0;$i < count($lines);$i++) {
            // top border / header separator
            if ($i == 0) {
                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($hBorder, $style);
                }

                $this->io->writeln('');

                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($lines[$i][$j], $style);
                }

                $this->io->writeln('');

                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($hBorder, $style);
                }
            }
            // bottom border
            else if ($i == (count($lines) - 1)) {
                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($lines[$i][$j], $style);
                }

                $this->io->writeln('');

                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($hBorder, $style);
                }
            }
            // rest of the rows
            else {
                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($lines[$i][$j], $style);
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
