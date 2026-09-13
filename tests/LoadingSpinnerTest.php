<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\ProgressBar;
use SigmaPHP\Console\IO;

/**
 * Progress Bar Test.
 */
class ProgressBarTest extends TestCase
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
     * @var ProgressBar $progressBar
     */
    private $progressBar;

    /**
     * ProgressBarTest SetUp
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

        $this->progressBar = new ProgressBar($this->io);
    }

    /**
     * ProgressBarTest TearDown
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
     * Test create new progress bar.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCreateNewProgressBar()
    {
        // run
        $items = 10;

        $this->progressBar->start($items);

        for ($i = 0;$i < $items;$i++) {
            $this->progressBar->update(1);
        }

        $this->progressBar->end();

        // assert the output
        $actual = explode("\n",
            file_get_contents(__DIR__ . '/fake_stream'));
        $expected = explode("\n",
            file_get_contents(__DIR__ . '/progress_bar_output'));

        foreach ($expected as $i => $line) {
            $this->assertEquals($line, $actual[$i]);
        }
    }
}
