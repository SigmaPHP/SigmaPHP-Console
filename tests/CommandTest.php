<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\Argument;
use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;
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
     * Test has argument.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHasArgument()
    {
        global $argv;

        $argv[2] = 'Ahmed';

        $this->command->processInput();

        $this->assertTrue(
            $this->command->hasArgument('name')
        );
    }

    /**
     * Test will throw exception if argument is not provided.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfArgumentIsNotProvided()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        unset($argv[2]);

        $this->command->processInput();
    }

    /**
     * Test will throw exception if command has no arguments and we provided.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfCommandHasNoArgumentsAndWeProvided()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = 'Test';

        (new Test())->processInput();
    }

    /**
     * Test will throw exception if we provided more arguments.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfWeProvidedMoreArguments()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = 'Test 1';
        $argv[3] = 'Test 2';

        $this->command->processInput();
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

        $this->command->processInput();

        $this->assertEquals(
            'Ahmed',
            $this->command->getArgument('name')
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
     * Test has option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testHasOption()
    {
        global $argv;

        $argv[2] = '-t';
        $argv[3] = 'Mr.';
        $argv[4] = 'Ahmed';

        $this->command->processInput();

        $this->assertTrue(
            $this->command->hasOption('title')
        );
    }

    /**
     * Test get options.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testGetOption()
    {
        global $argv;

        $argv[2] = '-t';
        $argv[3] = 'Mr.';
        $argv[4] = 'Ahmed';

        $this->command->processInput();

        $this->assertEquals(
            'Mr.',
            $this->command->getOption('title')
        );
    }

    /**
     * Test will throw exception if unknown option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfUnknownOption()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = '--option unknown';
        $argv[3] = 'Ahmed';

        $this->command->processInput();
    }

    /**
     * Test will throw exception if missing required parameter.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfMissingRequiredParameter()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = '--foo';

        $command = new Test();

        $command->processInput();
    }

    /**
     * Test will throw exception if provide parameter for none-parameterized
     * option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowExceptionIfProvideForNoneParameterizedOption()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = '-bz';
        $argv[3] = 'Wrong';

        $command = new Test();

        $command->processInput();
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
     * Test is empty.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testIsEmpty()
    {
        global $argv;

        unset($argv[2]);

        $command = new NoArgOrOptTest();

        $command->processInput();

        $this->assertTrue($command->isEmpty());
    }

    /**
     * Test data type.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDataType()
    {
        global $argv;

        $argv[1] = 'test';

        $index = 2;
        $argv[$index++] = '[\'ahmed\', \'omar\']';
        $argv[$index++] = 15;
        $argv[$index++] = true;
        $argv[$index++] = '--foo';
        $argv[$index++] = '[\'a\', \'b\', \'c\']';
        $argv[$index++] = '--bar';
        $argv[$index++] = 100.99;
        $argv[$index++] = '-bz';
        $argv[$index++] = '--qux';
        $argv[$index++] = true;

        $command = new Test();

        $command->processInput();

        $this->assertEquals(['ahmed', 'omar'], $command->getArgument('name'));
        $this->assertEquals(15, $command->getArgument('age'));
        $this->assertEquals(true, $command->getArgument('test'));
        $this->assertEquals(['a', 'b', 'c'], $command->getOption('foo'));
        $this->assertEquals(100.99, $command->getOption('bar'));
        $this->assertEquals(true, $command->getOption('baz'));
        $this->assertEquals(true, $command->getOption('qux'));
    }

    /**
     * Test will throw exception if wrong list data type for argument.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfWrongListDataTypeForArgument()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = 'wrong list';

        $command = new Test();

        $command->processInput();
    }

    /**
     * Test will throw exception if wrong numeric data type for argument.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfWrongNumericDataTypeForArgument()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = '[\'list\']';
        $argv[3] = 'wrong number';

        $command = new Test();

        $command->processInput();
    }

    /**
     * Test will throw exception if wrong boolean data type for argument.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfWrongBooleanDataTypeForArgument()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = '[\'list\']';
        $argv[3] = 15;
        $argv[4] = 'wrong boolean';

        $command = new Test();

        $command->processInput();
    }

    /**
     * Test will throw exception if wrong list data type for option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfWrongListDataTypeForOption()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = '--foo';
        $argv[3] = 'this is not a list';

        $command = new Test();

        $command->processInput();
    }

    /**
     * Test will throw exception if wrong numeric data type for option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfWrongNumericDataTypeForOption()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = '--bar';
        $argv[3] = 'this is not a number';

        $command = new Test();

        $command->processInput();
    }

    /**
     * Test will throw exception if wrong boolean data type for option.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfWrongBooleanDataTypeForOption()
    {
        $this->expectException(\InvalidArgumentException::class);

        global $argv;

        $argv[2] = '--qux';
        $argv[3] = 'this is not a boolean';

        $command = new Test();

        $command->processInput();
    }
}

class Test extends Command
{
    function init() {
        $this->addArgument('name', '', DataType::LIST);
        $this->addArgument('age', '', DataType::NUMBER);
        $this->addArgument('test', '', DataType::BOOL);

        $this->addOption('foo', 'fo', '', Option::REQUIRED, DataType::LIST);
        $this->addOption('bar', 'br', '', Option::OPTIONAL, DataType::NUMBER,
            1000);
        $this->addOption('baz', 'bz', '', Option::NONE);
        $this->addOption('qux', 'qx', '', Option::REQUIRED, DataType::BOOL);
    }

    function execute() {}

    function help() {
        echo "Help!\n";
    }
}

class NoArgOrOptTest extends Command
{
    function init() {}
    function execute() {}
}
