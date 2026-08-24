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
        $this->command->removeArgument('name');

        $this->command->addArgument('first_name');
        $this->command->addArgument('last_name');

        $args = $this->inspectProperty(
            HelloCommand::class,
            $this->command,
            'arguments'
        );

        $this->assertEquals(0, $args['first_name']->getOrder());
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
        $argv[$index++] = 'test';
        $argv[$index++] = '--foo';
        $argv[$index++] = '[\'a\', \'b\', \'c\']';
        $argv[$index++] = '--bar';
        $argv[$index++] = 100.99;
        $argv[$index++] = '-bz';
        $argv[$index++] = '--qux';

        $command = new Test();

        $command->processInput();

        $this->assertEquals(['ahmed', 'omar'], $command->getArgument('name'));
        $this->assertEquals(15, $command->getArgument('age'));
        $this->assertEquals('test', $command->getArgument('test'));
        $this->assertEquals(['a', 'b', 'c'], $command->getOption('foo'));
        $this->assertEquals(100.99, $command->getOption('bar'));
        $this->assertEquals(true, $command->getOption('baz'));
        $this->assertEquals(true, $command->getOption('qux'));
    }

    /**
     * Test option with no shortcut.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testOptionWithNoShortcut()
    {
        global $argv;

        $argv[1] = 'test';

        $index = 2;
        $argv[$index++] = '[\'ahmed\', \'omar\']';
        $argv[$index++] = 15;
        $argv[$index++] = 'test';
        $argv[$index++] = '--no-shortcut';

        $command = new Test();

        $command->addOption('no-shortcut');

        $command->processInput();

        $this->assertEquals(true, $command->getOption('no-shortcut'));
    }

    /**
     * Test default values for option's parameter.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDefaultValuesForOptionParameter()
    {
        global $argv;

        $argv[1] = 'test';

        $index = 2;
        $argv[$index++] = '[\'ahmed\', \'omar\']';
        $argv[$index++] = 15;
        $argv[$index++] = 'test';
        $argv[$index++] = '-hd';

        $command = new Test();

        $command->addOption('has-default', 'hd', '', Option::PARAMETER_OPTIONAL,
            DataType::LIST, ['x', 'y', 'z']);

        $command->processInput();

        $this->assertEquals(
            ['x', 'y', 'z'],
            $command->getOption('has-default')
        );
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
     * Test will throw exception if argument is of type boolean.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfArgumentIsOfTypeBoolean()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addArgument('y', '', DataType::BOOL);
    }

    /**
     * Test will throw exception if argument is of wrong type.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfArgumentIsOfWngType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addArgument('y', '', 'Nobody_Knows');
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

    /**
     * Test will throw exception if no parameter option has none boolean type.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfNoParamOptionHasNoneBooleanType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addOption('x', '', '',
            Option::PARAMETER_NONE, DataType::LIST);
    }

    /**
     * Test will throw exception if an option of type boolean got optionality
     * of not none.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testThrowExceptionIfOptionOfTypeBoolGotOptionalityNotNone()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addOption('x', '', '',
            Option::PARAMETER_REQUIRED, DataType::BOOL);
    }

    /**
     * Test will throw exception if option has wrong optionality.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfOptionHasWrongOptionality()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addOption('test', 't', '', 'Whatever');
    }

    /**
     * Test will throw exception if option has wrong data type.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfOptionHasWrongDataType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addOption('test', 't', '', option::PARAMETER_OPTIONAL, 'xyz');
    }

    /**
     * Test will throw exception if default value was set with required.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfDefaultValueWasSetWithRequired()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addOption('test', 't', '', Option::PARAMETER_REQUIRED,
            DataType::STRING, 'Not_Allowed');
    }

    /**
     * Test will throw exception if default value was set with none.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfDefaultValueWasSetWithNone()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addOption('test', 't', '', Option::PARAMETER_NONE,
            DataType::STRING, 'Not_Allowed');
    }

    /**
     * Test will throw exception if argument already registered.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfArgumentAlreadyRegistered()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addArgument('name', '', DataType::LIST);
    }

    /**
     * Test will throw exception if option already registered.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfOptionAlreadyRegistered()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addOption('qux', 'qx', '', Option::PARAMETER_NONE,
            DataType::BOOL);
    }

    /**
     * Test will throw exception if shortcut already registered.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfShortcutAlreadyRegistered()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new Test();

        $command->addOption('new', 'qx');
    }
}

class Test extends Command
{
    function init() {
        $this->addArgument('name', '', DataType::LIST);
        $this->addArgument('age', '', DataType::NUMBER);
        $this->addArgument('test', '', DataType::STRING);

        $this->addOption('foo', 'fo', '', Option::PARAMETER_REQUIRED,
            DataType::LIST);
        $this->addOption('bar', 'br', '', Option::PARAMETER_OPTIONAL,
            DataType::NUMBER, 1000);
        $this->addOption('baz', 'bz', '', Option::PARAMETER_NONE,
            DataType::BOOL);
        $this->addOption('qux', 'qx', '', Option::PARAMETER_NONE,
            DataType::BOOL);
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
