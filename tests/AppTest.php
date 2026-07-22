<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\App;
use SigmaPHP\Console\Tests\Examples\HelloCommand;

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
     * Get value of property.
     *
     * @param string $name
     * @return mixed
     */
    private function inspectProperty($name)
    {
        $inspect = new \ReflectionProperty(App::class, $name);
        return $inspect->getValue($this->app);
    }

    /**
     * Test add command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testAddCommand()
    {
        $this->app->addCommand(HelloCommand::class);

        $this->assertEquals(
            ['help', 'version', 'hello'],
            array_keys($this->inspectProperty('commands'))
        );
    }

    /**
     * Test load commands.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testLoadCommands()
    {
        $this->app->loadCommands(
            __DIR__ . "/Examples",
            'SigmaPHP\Console\Tests\Examples'
        );

        $this->assertEquals(
            ['help', 'version', 'hello'],
            array_keys($this->inspectProperty('commands'))
        );
    }

    /**
     * Test has commands.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHasCommand()
    {
        $this->assertTrue($this->app->hasCommand('version'));
    }

    /**
     * Test get command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetCommand()
    {
        $this->assertInstanceOf(
            \SigmaPHP\Console\DefaultCommands\Version::class,
            $this->app->getCommand('version')
        );
    }

    /**
     * Test get all commands.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetAllCommands()
    {
        $this->assertEquals(
            ['help', 'version'],
            array_keys($this->app->getCommands())
        );
    }

    /**
     * Test remove command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRemoveCommand()
    {
        $this->app->removeCommand('version');

        $this->assertEquals(
            ['help'],
            array_keys($this->inspectProperty('commands'))
        );
    }

    /**
     * Test disable default commands.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDisableDefaultCommands()
    {
        $this->app->disableDefaultFunctions();

        $this->assertEquals([], array_keys($this->inspectProperty('commands')));
    }
}
