<?php

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\Console;

/**
 * Console Test
 */
class ConsoleTest extends TestCase
{
    /**
     * @var Console $console
     */
    private $console;

    /**
     * @var resource $testStream
     */
    private $testStream;

    /**
     * ConsoleTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->console = new Console();

        touch('tests/fake_stream');

        $this->testStream = fopen('tests/fake_stream', 'r+');
    }

    /**
     * ConsoleTest TearDown
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
        $this->console->setOutputStream($this->testStream);
        $this->console->write('Hello SigmaPHP-Console');

        $this->assertEquals(
            'Hello SigmaPHP-Console',
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
        $this->console->setErrorStream($this->testStream);
        $this->console->writeErr('Oops! Something wrong');

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
        $this->console->setInputStream($this->testStream);
        fwrite($this->testStream, 'Some data');
        rewind($this->testStream);
        $input = $this->console->read();

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

        $this->assertTrue($this->console->hasColorSupport());

        $_SERVER['NO_COLOR'] = true;

        $this->assertFalse($this->console->hasColorSupport());
    }
}
