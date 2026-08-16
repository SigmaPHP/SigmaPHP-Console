<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\Argument;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\Option;
use SigmaPHP\Console\Tests\Examples\HelloCommand;
use SigmaPHP\Console\Tests\Helpers;

/**
 * Command Test
 */
class CommandTest extends TestCase
{
    use Helpers;

    /**
     * @var Command $command
     */
    private $command;

    /**
     * CommandTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->command = new HelloCommand();
    }

    /**
     * CommandTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test set command's name.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testSetCommandName()
    {
        $this->command->setName('hello');

        $this->assertEquals(
            'hello',
            $this->inspectProperty(HelloCommand::class, $this->command, 'name')
        );
    }

    /**
     * Test set command's description.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testSetCommandDescription()
    {
        $this->command->setDescription('say hello to user');

        $this->assertEquals(
            'say hello to user',
            $this->inspectProperty(
                HelloCommand::class,
                $this->command,
                'description'
            )
        );
    }

    /**
     * Test get command's name.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetCommandName()
    {
        $this->assertEquals('hello', $this->command->getName());
    }

    /**
     * Test get command's description.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetCommandDescription()
    {
        $this->command->setDescription('say hello to user');

        $this->assertEquals(
            'say hello to user',
            $this->command->getDescription()
        );
    }

    /**
     * Test add argument to command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testAddArgumentToCommand()
    {
        $this->command->addArgument('last_name');

        $this->assertEquals(
            ['name', 'last_name'],
            array_keys($this->inspectProperty(
                HelloCommand::class,
                $this->command,
                'arguments'
            ))
        );
    }

    /**
     * Test remove argument from command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRemoveArgumentFromCommand()
    {
        $this->command->addArgument('last_name');

        $this->command->removeArgument('last_name');

        $this->assertEquals(
            ['name'],
            array_keys($this->inspectProperty(
                HelloCommand::class,
                $this->command,
                'arguments'
            ))
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
        $this->assertInstanceOf(
            Argument::class,
            $this->command->getArgument('name')
        );
    }

    /**
     * Test get arguments.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetArguments()
    {
        $this->command->addArgument('last_name');

        $this->assertEquals(
            ['name', 'last_name'],
            array_keys($this->command->getArguments())
        );
    }

    /**
     * Test argument order.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testArgumentOrder()
    {
        $this->command->addArgument('name');
        $this->command->addArgument('last_name');

        $args = $this->inspectProperty(
            HelloCommand::class,
            $this->command,
            'arguments'
        );

        $this->assertEquals(0, $args['name']->getOrder());
        $this->assertEquals(1, $args['last_name']->getOrder());
    }

    /**
     * Test add option to command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testAddOptionToCommand()
    {
        $this->assertEquals(
            ['help', 'greeting', 'title'],
            array_keys($this->inspectProperty(
                HelloCommand::class,
                $this->command,
                'options'
            ))
        );
    }

    /**
     * Test remove option from command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRemoveOptionFromCommand()
    {
        $this->command->removeOption('greeting');

        $this->assertEquals(
            ['help', 'title'],
            array_keys($this->inspectProperty(
                HelloCommand::class,
                $this->command,
                'options'
            ))
        );
    }

    /**
     * Test get option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetOption()
    {
        $this->assertInstanceOf(
            Option::class,
            $this->command->getOption('greeting')
        );
    }

    /**
     * Test get options.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetOptions()
    {
        $this->command->addOption('no-title');

        $this->assertEquals(
            ['help', 'greeting', 'title', 'no-title'],
            array_keys($this->command->getOptions())
        );
    }

    /**
     * Test help global option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHelpGlobalOption()
    {
        $command = new Test();

        $command->help();

        $this->expectOutputString("Help!\n");
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
    public function testGetArgument2()
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

class Test extends Command
{
    function init() {}

    function execute() {}

    function help() {
        echo "Help!\n";
    }
}
