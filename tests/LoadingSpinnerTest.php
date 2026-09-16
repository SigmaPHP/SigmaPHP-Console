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
     * @var LoadingSpinner $progressBar
     */
    private $loadingSpinner;

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

        $this->loadingSpinner = new LoadingSpinner($this->io);
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

    }
}
