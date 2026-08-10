<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\InputHandler;
use SigmaPHP\Console\Argument;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\Option;

/**
 * Input Handler Test
 */
class InputHandlerTest extends TestCase
{
    /**
     * @var InputHandler $inputHandler
     */
    private $inputHandler;

    /**
     * InputHandlerTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->inputHandler = new InputHandler(
            ['name' => new Argument('name')],
            ['title' => new Option('title', 't', Option::OPTIONAL)],
        );
    }

    /**
     * Test get command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetCommand()
    {
        global $argv;

        $argv[1] = 'test';

        $this->inputHandler->process();

        $this->assertEquals(
            'test',
            $this->inputHandler->getCommand()
        );
    }

    /**
     * Test has argument.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHasArgument()
    {
        global $argv;

        $argv[2] = 'Ahmed';

        $this->inputHandler->process();

        $this->assertTrue(
            $this->inputHandler->hasArgument('name')
        );
    }

    /**
     * Test has no argument.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHasNoArgument()
    {
        global $argv;

        unset($argv[2]);

        $this->inputHandler->process();

        $this->assertFalse(
            $this->inputHandler->hasArgument('name')
        );
    }

    /**
     * Test get argument.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetArgument()
    {
        global $argv;

        $argv[2] = 'Ahmed';

        $this->inputHandler->process();

        $this->assertEquals(
            'Ahmed',
            $this->inputHandler->getArgument('name')
        );
    }

    /**
     * Test has/get options.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testOptions()
    {
        $output = [];

        exec(__DIR__ . '/bin/test_app hello Ahmed -t Mr. --greeting Hi',
            $output);

        $this->assertEquals('Hi Mr. Ahmed', $output[0]);
    }

    /**
     * Test is empty.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testIsEmpty()
    {
        global $argv;

        unset($argv[1]);
        unset($argv[2]);

        $this->inputHandler->process();

        $this->assertTrue(
            $this->inputHandler->isEmpty()
        );
    }
}
