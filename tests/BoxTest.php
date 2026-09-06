<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\Box;
use SigmaPHP\Console\IO;

/**
 * Box Test.
 */
class BoxTest extends TestCase
{
    /**
     * @var IO $io
     */
    private $io;

    /**
     * @var Box $box
     */
    private $box;

    /**
     * BoxTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->io = new IO();
        $this->io->setOutputStream(fopen('php://output', 'r+'));

        $this->box = new Box();
        $this->box->setIOHandler($this->io);
    }

    /**
     * BoxTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test create a new box.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCreateNewBox()
    {
        $this->expectOutputString(
            "***********************************\n" .
            "*                                 *\n" .
            "*           Hello, World!         *\n" .
            "*                                 *\n" .
            "***********************************\n"
        );

        $this->box->create("Hello, World!");
    }
}
