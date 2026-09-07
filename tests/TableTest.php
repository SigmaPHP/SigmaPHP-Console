<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\Table;
use SigmaPHP\Console\IO;

/**
 * Table Test.
 */
class TableTest extends TestCase
{
    /**
     * @var IO $io
     */
    private $io;

    /**
     * @var Table $table
     */
    private $table;

    /**
     * TableTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->io = new IO();
        $this->io->setOutputStream(fopen('php://output', 'r+'));

        $this->table = new Table();
        $this->table->setIOHandler($this->io);
    }

    /**
     * TableTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test create a new table.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCreateNewTable()
    {

    }
}
