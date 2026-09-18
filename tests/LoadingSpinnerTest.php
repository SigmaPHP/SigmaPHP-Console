<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\LoadingSpinner;
use SigmaPHP\Console\IO;

/**
 * Loading Spinner Test.
 */
class LoadingSpinnerTest extends TestCase
{
    /**
     * @var IO $io
     */
    private $io;

    /**
     * @var resource $testStream
     */
    private $testStream;

    /**
     * LoadingSpinnerTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->io = new IO();

        touch('tests/fake_stream');

        $this->testStream = fopen('tests/fake_stream', 'r+');

        $this->io->setOutputStream($this->testStream);
    }

    /**
     * LoadingSpinnerTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        if (file_exists('tests/fake_stream')) {
            fclose($this->testStream);
            unlink('tests/fake_stream');
        }
    }

    /**
     * Test create new loading spinner.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCreateNewLoadingSpinner()
    {
        // ? in order not to get into too much hassle with testing forked
        // ? processes. Only the drawing part will be covered by unit testing
        $_spinner = new DummySpinner($this->io, 'frames', 'fg=red');

        $_spinner->doDraw();

        // assert the output
        $actual = explode("\n",
            file_get_contents(__DIR__ . '/fake_stream'));
        $expected = explode("\n",
            file_get_contents(__DIR__ . '/loading_spinner_output'));

        foreach ($expected as $i => $line) {
            $this->assertEquals($line, $actual[$i]);
        }
    }

    /**
     * Test will throw exception if invalid spinner's pattern.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfInvalidSpinnerPattern()
    {
        $this->expectException(\InvalidArgumentException::class);

        $spinner = new LoadingSpinner($this->io, 'unknown');
    }
}

class DummySpinner extends LoadingSpinner
{
    public function doDraw()
    {
        $i = 0;

        while ($i < 100) {
            $this->io->clear();
            $this->draw();

            $i += 1;
        }
    }
}
