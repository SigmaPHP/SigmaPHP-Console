<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\App;

/**
 * App Test.
 */
class AppTest extends TestCase
{
    /**
     * @var App $app
     */
    private $app;

    /**
     * AppTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->app = new App();
    }

    /**
     * AppTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test load commands.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testLoadCommands()
    {
        $this->app->disableDefaultFunctions();

        $this->app->loadCommands(dirname(__DIR__, 1) . "/src/DefaultCommands");

        $this->assertEquals(
            ['help', 'version'],
            array_keys($this->app->getCommands())
        );
    }
}
