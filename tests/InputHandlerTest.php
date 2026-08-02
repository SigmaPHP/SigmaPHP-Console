<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\InputHandler;
use SigmaPHP\Console\Argument;
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
            ['name' => new Argument('name', '', 2)],
            ['title' => new Option('title')],
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

        $this->assertTrue(
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

        $this->assertEquals(
            'Ahmed',
            $this->inputHandler->getArgument('name')
        );
    }
}
