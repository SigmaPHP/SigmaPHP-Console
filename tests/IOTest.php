<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\IO;

/**
 * IO Test
 */
class IOTest extends TestCase
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
     * IOTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->io = new IO();

        touch('tests/fake_stream');

        $this->testStream = fopen('tests/fake_stream', 'r+');
    }

    /**
     * IOTest TearDown
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
     * Test write.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWrite()
    {
        $this->io->setOutputStream($this->testStream);
        $this->io->write('Hello SigmaPHP-Console');

        $this->assertEquals(
            'Hello SigmaPHP-Console',
            stream_get_contents($this->testStream, -1, 0)
        );
    }

    /**
     * Test write with new line.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWriteln()
    {
        $this->io->setOutputStream($this->testStream);
        $this->io->writeln('Hello SigmaPHP-Console');

        $this->assertEquals(
            "Hello SigmaPHP-Console\n",
            stream_get_contents($this->testStream, -1, 0)
        );
    }

    /**
     * Test write error.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWriteError()
    {
        $this->io->setErrorStream($this->testStream);
        $this->io->writeErr('Oops! Something wrong');

        $this->assertEquals(
            'Oops! Something wrong',
            stream_get_contents($this->testStream, -1, 0)
        );
    }

    /**
     * Test read.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRead()
    {
        $this->io->setInputStream($this->testStream);
        fwrite($this->testStream, 'Some data');
        rewind($this->testStream);
        $input = $this->io->read();

        $this->assertEquals('Some data', $input);
    }

    /**
     * Test has color support.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHasColorSupport()
    {
        $_SERVER['FORCE_COLOR'] = true;

        $this->assertTrue($this->io->hasColorSupport());

        $_SERVER['NO_COLOR'] = true;

        $this->assertFalse($this->io->hasColorSupport());
    }

    /**
     * Test styling.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testStyling()
    {
        $this->io->setOutputStream($this->testStream);
        $this->io->write('Hello World', 'fg=red;bg=180;bold');

        $result = stream_get_contents($this->testStream, -1, 0);

        $this->assertEquals(
            '\033[1m\033[48;5;180m\033[31mHello World\033[0m\033[0m\033[0m',
            str_replace("\033", "\\033", $result)
        );
    }

    /**
     * Test error styling.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testErrorStyling()
    {
        $this->io->setErrorStream($this->testStream);
        $this->io->writeErr('Wrong', 'fg=blue;bg=90;bold');

        $result = stream_get_contents($this->testStream, -1, 0);

        $this->assertEquals(
            '\033[1m\033[48;5;90m\033[34mWrong\033[0m\033[0m\033[0m',
            str_replace("\033", "\\033", $result)
        );
    }

    /**
     * Test will throw exception if invalid style.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfInvalidStyle()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->io->write('Hello World', 'fg=red;gb=180;bold');
    }
}
