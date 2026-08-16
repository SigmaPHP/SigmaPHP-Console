<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\App;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\DefaultCommands\Help;
use SigmaPHP\Console\DefaultCommands\Version;
use SigmaPHP\Console\Exceptions\CommandNotFoundException;
use SigmaPHP\Console\Option;
use SigmaPHP\Console\Tests\Examples\HelloCommand;
use SigmaPHP\Console\Tests\Helpers;

/**
 * App Test.
 */
class AppTest extends TestCase
{
    use Helpers;

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
     * Test set app's name.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testSetAppName()
    {
        $this->app->setAppName('test');

        $this->assertEquals('test', $this->inspectProperty(
            Help::class,
            $this->app->getCommand('help'),
            'appName'
        ));
    }

    /**
     * Test set app's description.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testSetAppDescription()
    {
        $this->app->setAppDescription('an app for testing');

        $this->assertEquals('an app for testing', $this->inspectProperty(
            Help::class,
            $this->app->getCommand('help'),
            'appDescription'
        ));
    }

    /**
     * Test set app's version.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testSetAppVersion()
    {
        $this->app->setAppVersion('beta');

        $this->assertEquals('beta', $this->inspectProperty(
            Version::class,
            $this->app->getCommand('version'),
            'appVersion'
        ));
    }

    /**
     * Test will throw exception if setting app's info while defaults are
     * disabled.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionSettingAppInfoWhileDefaultsDisabled()
    {
        $this->expectException(\Exception::class);

        $this->app->disableDefaults();

        $this->app->setAppVersion('beta');
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
            ['help', 'version', 'debug', 'hello'],
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
            array_keys(
                $this->inspectProperty(App::class, $this->app, 'commands')
            )
        );
    }

    /**
     * Test add global option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testAddGlobalOption()
    {
        $this->app->addGlobalOption('test');

        $this->assertInstanceOf(
            Option::class,
            $this->inspectProperty(
                App::class,
                $this->app,
                'globalOptions'
            )['test']
        );
    }

    /**
     * Test remove global option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRemoveGlobalOption()
    {
        $this->app->addGlobalOption('test');

        $this->app->removeGlobalOption('test');

        $this->assertEquals(
            ['help', 'version'],
            array_keys(
                $this->inspectProperty(App::class, $this->app, 'commands')
            )
        );
    }

    /**
     * Test get global option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetGlobalOption()
    {
        $this->assertEquals(
            'h',
            $this->app->getGlobalOption('help')->getShortcut()
        );
    }

    /**
     * Test get global options.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetGlobalOptions()
    {
        $this->assertEquals(
            ['help', 'version'],
            array_keys($this->app->getGlobalOptions())
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

        $this->assertEmpty(array_keys(
            $this->inspectProperty(App::class, $this->app, 'globalOptions')
        ));
    }

    /**
     * Test set app's name to script's name.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testSetAppNameToScriptName()
    {
        $output = [];

        exec(__DIR__ . '/bin/test_app debug', $output);

        $this->assertEquals(__DIR__ . '/bin/test_app', $output[0]);
    }

    /**
     * Test running command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRunningCommand()
    {
        $output = [];

        exec(__DIR__ . '/bin/test_app hello Ahmed', $output);

        $this->assertEquals('Hello Ahmed', $output[0]);
    }

    /**
     * Test no command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testNoCommand()
    {
        $output = [];

        exec(__DIR__ . '/bin/test_app', $output);

        // verify 'help menu'
        $this->assertEquals('Testing Application', $output[0]);

        $this->assertEquals(16, count($output));
    }

    /**
     * Test help command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHelpCommand()
    {
        $output = [];

        exec(__DIR__ . '/bin/test_app help', $output);

        // verify 'help menu'
        $this->assertEquals('Testing Application', $output[0]);

        $this->assertEquals(16, count($output));
    }

    /**
     * Test help global option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHelpGlobalOption()
    {
        $output = [];

        exec(__DIR__ . '/bin/test_app --help', $output);

        // verify 'help menu'
        $this->assertEquals('Testing Application', $output[0]);

        $this->assertEquals(16, count($output));
    }

    /**
     * Test version command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testVersionCommand()
    {
        $output = [];

        exec(__DIR__ . '/bin/test_app version', $output);

        $this->assertEquals('v1.0.0', $output[0]);
    }

    /**
     * Test version global option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testVersionGlobalOption()
    {
        $output = [];

        exec(__DIR__ . '/bin/test_app --version', $output);

        $this->assertEquals('v1.0.0', $output[0]);
    }
}

class NoName extends Command
{
    function init() {}
    function execute() {}
}
