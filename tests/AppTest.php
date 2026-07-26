<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\App;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\Exceptions\CommandNotFoundException;
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
     * @param string $class
     * @param object $object
     * @param string $property
     * @return mixed
     */
    private function inspectProperty($class, $object, $property)
    {
        $inspect = new \ReflectionProperty($class, $property);
        return $inspect->getValue($object);
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
            ['version', 'help', 'hello'],
            array_keys(
                $this->inspectProperty(App::class, $this->app, 'commands')
            )
        );
    }

    /**
     * Test app will generate a name for the command that doesn't have one.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testAppWillGenerateANameForTheCommandThatDoesNotHaveOne()
    {
        $this->app->addCommand(NoName::class);

        $this->app->disableDefaults();

        $this->assertEquals(
            ['noname'],
            array_keys(
                $this->inspectProperty(App::class, $this->app, 'commands')
            )
        );
    }

    /**
     * Test will throw exception if command doesn't exists.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfCommandsDoesNotExists()
    {
        $this->expectException(CommandNotFoundException::class);

        $this->app->addCommand('App\\Unknown');
    }

    /**
     * Test will throw exception if trying to add a command that already exists.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfTryingToAddACommandAlreadyExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->app->addCommand(HelloCommand::class);

        // try adding again
        $this->app->addCommand(HelloCommand::class);
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
            ['version', 'help', 'hello'],
            array_keys(
                $this->inspectProperty(App::class, $this->app, 'commands')
            )
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
            ['version', 'help'],
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
            array_keys(
                $this->inspectProperty(App::class, $this->app, 'commands')
            )
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
        $this->app->disableDefaults();

        $this->assertEmpty(array_keys(
            $this->inspectProperty(App::class, $this->app, 'commands')
        ));
    }
}

class NoName extends Command
{
    function init() {}
    function execute() {}
}
