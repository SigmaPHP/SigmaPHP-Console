<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\IOInterface;

/**
 * IO Class.
 */
class IO implements IOInterface
{
    /**
     * @var resource $outputStream
     */
    protected $outputStream;

    /**
     * @var resource $errorStream
     */
    protected $errorStream;

    /**
     * @var resource $inputStream
     */
    protected $inputStream;

    /**
     * @var bool $isQuiet
     */
    protected $isQuiet;

    /**
     * @var bool $isSilent
     */
    protected $isSilent;

    /**
     * @var Color $color
     */
    protected $color;

    /**
     * @var TextFormatter
     */
    protected $textFormatter;

    /**
     * IO Constructor.
     *
     */
    public function __construct() {
        $this->outputStream = fopen('php://stdout', 'w');
        $this->errorStream = fopen('php://stderr', 'w');
        $this->inputStream = fopen('php://stdin', 'r');

        $this->isQuiet = false;
        $this->isSilent = false;

        $this->color = new Color();
        $this->textFormatter = new TextFormatter();
    }

    /**
     * Set the output stream.
     *
     * @param resource $stream
     * @return bool
     */
    public function setOutputStream($stream)
    {
        $this->outputStream = $stream;
    }

    /**
     * Set the error stream.
     *
     * @param resource $stream
     * @return bool
     */
    public function setErrorStream($stream)
    {
        $this->errorStream = $stream;
    }

    /**
     * Set the input stream.
     *
     * @param resource $stream
     * @return bool
     */
    public function setInputStream($stream)
    {
        $this->inputStream = $stream;
    }

    /**
     * Set is quiet flag.
     *
     * @param bool $value
     * @return void
     */
    public function setIsQuiet($value)
    {
        $this->isQuiet = $value;
    }

    /**
     * Set is silent flag.
     *
     * @param bool $value
     * @return void
     */
    public function setIsSilent($value)
    {
        $this->isSilent = $value;
    }

    /**
     * Write to console (STDOUT).
     *
     * @param string $text
     * @param string $style
     * @return int|false
     */
    public function write($text, $style = '')
    {
        return $this->isQuiet ?
            false :
            fwrite($this->outputStream, $this->stylize($text, $style));
    }

    /**
     * Write to console (STDOUT) with new line.
     *
     * @param string $text
     * @param string $style
     * @return int|false
     */
    public function writeln($text, $style = '')
    {
        return $this->write($text . "\n", $style);
    }

    /**
     * Write to console (STDERR).
     *
     * @param string $text
     * @param string $style
     * @return int|false
     */
    public function writeErr($text, $style = '')
    {
        return $this->isSilent ?
            false :
            fwrite($this->errorStream, $this->stylize($text, $style));
    }

    /**
     * Read from console.
     *
     * @return string|false
     */
    public function read()
    {
        return fgets($this->inputStream);
    }

    /**
     * Check if console supports colors.
     *
     * @return bool
     */
    public function hasColorSupport()
    {
        return (
            (
                (exec('tput colors') != -1) ||
                stream_isatty(STDOUT) ||
                isset($_SERVER['FORCE_COLOR'])
            ) &&
            !isset($_SERVER['NO_COLOR'])
        );
    }

    /**
     * Apply styling to text.
     *
     * @param string $text
     * @param string $style
     * @return string
     */
    public function stylize($text, $style)
    {
        if (empty($style)) {
            return $text;
        }

        $_options = explode(';', $style);

        foreach ($_options as $option) {
            if (strpos($option, '=') !== false) {
                $parts = explode('=', $option);

                if ($parts[0] == 'fg') {
                    $text = $this->color->colorize($text, $parts[1]);
                }
                else if ($parts[0] == 'bg') {
                    $text = $this->color->colorize($text, $parts[1], true);
                } else {
                    throw new \InvalidArgumentException(
                        "Unknown styling option'{$parts[0]}'"
                    );
                }
            } else {
                $text = $this->textFormatter->format($text, $option);
            }
        }

        return $text;
    }
}
