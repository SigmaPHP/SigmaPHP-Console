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
        $this->command->addArgument('name');

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
     * Test remove argument from command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRemoveArgumentFromCommand()
    {
        $this->command->addArgument('name');

        $this->command->removeArgument('name');

        $this->assertEquals(
            [],
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
        $this->command->addArgument('name');

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
        $this->command->addArgument('name');
        $this->command->addArgument('title');

        $this->assertEquals(
            ['name', 'title'],
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
        $this->command->addArgument('title', '', 5);

        $args = $this->inspectProperty(
            HelloCommand::class,
            $this->command,
            'arguments'
        );

        $this->assertEquals(2, $args['name']->getOrder());
        $this->assertEquals(5, $args['title']->getOrder());
    }

    /**
     * Test will throw exception if order is invalid.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfOrderIsInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->command->addArgument('name', '', 'wrong');
    }

    /**
     * Test add option to command.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testAddOptionToCommand()
    {
        $this->command->addOption('greeting');

        $this->assertEquals(
            ['greeting'],
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
        $this->command->addOption('greeting');

        $this->command->removeOption('greeting');

        $this->assertEquals(
            [],
            array_keys($this->inspectProperty(
                HelloCommand::class,
                $this->command,
                'arguments'
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
        $this->command->addOption('greeting');

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
        $this->command->addOption('greeting');
        $this->command->addOption('no-title');

        $this->assertEquals(
            ['greeting', 'no-title'],
            array_keys($this->command->getOptions())
        );
    }
}
