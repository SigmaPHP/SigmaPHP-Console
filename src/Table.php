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
        $data = array_merge([$header], $data);

        $maxCellWidth = array_fill(0, count($header), 0);

        foreach ($data as $row) {
            foreach ($row as $index => $cell) {
                if (strlen(' ' . $cell . ' ') > $maxCellWidth[$index]) {
                    $maxCellWidth[$index] = strlen(' ' . $cell . ' ');
                }
            }
        }

        $lines = [];
        $_line = '';

        foreach ($data as $row) {
            $_line = $border;

            foreach ($row as $index => $cell) {
                $_line .= str_pad((' ' . $cell . ' '),
                    $maxCellWidth[$index], ' ', STR_PAD_BOTH) . $border;
            }

            $lines[] = $_line;
        }

        // render
        for ($i = 0;$i < count($lines);$i++) {
            // top border / header separator
            if ($i == 0) {
                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($border, $style);
                }

                $this->io->writeln('');

                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($lines[$i][$j], $style);
                }

                $this->io->writeln('');

                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($border, $style);
                }
            }
            // bottom border
            else if ($i == (count($lines) - 1)) {
                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($lines[$i][$j], $style);
                }

                $this->io->writeln('');

                for ($j = 0;$j < strlen($lines[$i]);$j++) {
                    $this->io->write($border, $style);
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
