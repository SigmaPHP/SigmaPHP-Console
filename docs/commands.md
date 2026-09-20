# Command

A `Command` represents an executable action within a SigmaPHP Console application. Commands are responsible for defining the arguments and options they accept, processing user input, executing application logic, and communicating with the user through the console.

A command can also provide aliases, formatted output, interactive input, progress bars, and loading spinners.

## Table of Contents

- [Creating a Command](#creating-a-command)
- [Command Lifecycle](#command-lifecycle)
- [Arguments](#arguments)
- [Options](#options)
- [Arguments and Options State](#arguments-and-options-state)
- [Global Options](#global-options)
- [Command Aliases](#command-aliases)
- [Help Option](#help)
- [Input and Output](#input-and-output)
- [Message Helpers](#message-helpers)

## Creating a Command

A command is typically created by extending the SigmaPHP Console `Command` class and implementing the required command lifecycle methods.

```
<?php

namespace App\Commands;

use SigmaPHP\Console\Command;

class HelloCommand extends Command
{
    public function init()
    {
        $this->setName('hello');
        $this->setDescription('Print a greeting.');
    }

    public function execute()
    {
        $this->writeln('Hello World!');
    }
}
```

The command can then be registered with the application:

```
$app->addCommand(new HelloCommand());

$app->run();
```

## Command Lifecycle

A command follows a defined execution lifecycle. The main methods involved are:

* `init()`
* `processInput()`
* `execute()`

### Initialization

The `init()` method is used to configure the command.

This is where command metadata, arguments, options, and aliases can be defined.

```
public function init()
{
    $this->setName('hello');
    $this->setDescription('Print a greeting.');

    $this->addArgument(
        'name',
        'Name of the person to greet.',
        DataType::STRING
    );
}
```

### Processing Input

`processInput()` is responsible for loading and processing the arguments and options provided by the user.

```
$this->processInput();
```

In normal usage, input processing is handled by the command lifecycle. Custom implementations can override this behavior when specialized input processing is required.

### Execution

The `execute()` method contains the actual command logic:

```
public function execute()
{
    $this->writeln('Hello World!');
}
```

This method is called when the command is executed by the application.

## Arguments

Arguments represent positional values provided to a command.

Arguments are **required by default**. If a command defines an argument and the user does not provide it, SigmaPHP Console will throw an exception during input processing.

For example:

```php
./app hello Mohamed
```

Here, `Mohamed` is the value of the `name` argument.

Arguments and options can be provided in any order. Their position relative to each other is irrelevant.

For example, the following commands are equivalent:

```php
./app hello Mohamed --verbose
```

```php
./app hello --verbose Mohamed
```

The command parser identifies arguments and options independently, so options do not have to appear before or after arguments.

### Adding an Argument

Use `addArgument()` to define an argument:

```php
$this->addArgument(
    'name',
    'Name of the person to greet.',
    DataType::STRING
);
```

The method accepts:

| Parameter      | Description                             |
| -------------- | --------------------------------------- |
| `$name`        | Argument name.                          |
| `$description` | Description displayed in the help menu. |
| `$dataType`    | Expected data type of the argument.     |

### Getting an Argument

Use `getArgument()` to retrieve the value provided by the user:

```php
$name = $this->getArgument('name');
```

For example:

```php
public function execute()
{
    $name = $this->getArgument('name');

    $this->writeln("Hello {$name}!");
}
```

### Checking an Argument

`hasArgument()` checks whether an argument was **provided in the command input**.

It does not check whether the argument has been defined in the command.

```php
if ($this->hasArgument('name')) {
    $this->writeln('A name was provided.');
}
```

### Removing an Argument

An argument can be removed from the command definition using `removeArgument()`:

```php
$this->removeArgument('name');
```

## Options

Options are named values or flags that can be provided to a command.

Unlike arguments, options are **optional**. A command can be executed without providing any of its defined options.

For example:

```php
./app hello
```

The same command can optionally receive an option:

```php
./app hello --name Mohamed
```

Options and arguments can appear in any order. Their position relative to each other is irrelevant.

For example, both of the following are valid:

```php
./app hello Mohamed --verbose
```

```php
./app hello --verbose Mohamed
```
### Option Parameter Types

Each option can define whether it accepts a parameter. SigmaPHP Console supports three parameter types:

| Parameter Type | Description |
| --- | --- |
| `PARAMETER_REQUIRED` | The option must be followed by a parameter value. |
| `PARAMETER_OPTIONAL` | The option may be followed by a parameter value. |
| `PARAMETER_NONE` | The option does not accept a parameter and acts as a boolean flag. |

The constants are defined as:

```php
Option::PARAMETER_REQUIRED
Option::PARAMETER_OPTIONAL
Option::PARAMETER_NONE
```

#### Required Parameter

An option with a required parameter must receive a value.

```php
$this->addOption(
    'connect',
    'c',
    'Connect to the specified host.',
    Option::PARAMETER_REQUIRED,
    DataType::STRING,
    null
);
```

It can be used with the following formats:

```bash
--connect localhost
--connect=localhost
-c localhost
-clocalhost
```

#### Optional Parameter

An option with an optional parameter may be used with or without a value.

```php
$this->addOption(
    'verbose',
    'v',
    'Show detailed information.',
    Option::PARAMETER_OPTIONAL,
    DataType::STRING,
    null
);
```

#### No Parameter

An option with no parameter does not accept a value and acts as a boolean flag.

```php
$this->addOption(
    'interactive',
    'i',
    'Enable interactive mode.',
    Option::PARAMETER_NONE,
    DataType::BOOL,
    false
);
```

It can be used simply as:

```bash
--interactive
-i
```

### Adding an Option

Use `addOption()` to define an option:

```php
$this->addOption(
    'name',
    'n',
    'Name of the person to greet.',
    Option::PARAMETER_REQUIRED,
    DataType::STRING,
    null
);
```

The method accepts:

| Parameter               | Description                                      |
| ----------------------- | ------------------------------------------------ |
| `$name`                 | Long option name.                                |
| `$shortcut`             | Short option name.                               |
| `$description`          | Description displayed in the help menu.          |
| `$parameterOptionality` | Defines whether the option requires a parameter. |
| `$dataType`             | Expected data type of the option value.          |
| `$defaultValue`         | Value used when the option is not provided.      |

### Supported Option Patterns

SigmaPHP Console supports several common option formats:

```php
-i
--interactive
--connect xyz
--connect=xyz
-c=xyz
-cxyz
-ic
```

These patterns can represent both long and short options, options with parameters, and combined short options.

For example:

```php
-i
```

represents a short boolean option, while:

```php
--interactive
```

represents its long form.

An option that accepts a parameter can use either a space or `=`:

```php
--connect xyz
--connect=xyz
```

Short options can also use the same forms:

```php
-c=xyz
-cxyz
```

Multiple short boolean options can be combined:

```php
-ic
```

This is equivalent to providing both `-i` and `-c` as separate short options when both options are defined as flags.

### Getting an Option

Use `getOption()` to retrieve the value provided by the user:

```php
$name = $this->getOption('name');
```

### Checking an Option

`hasOption()` checks whether an option was **provided in the command input**.

It does not check whether the option has been defined in the command.

```php
if ($this->hasOption('verbose')) {
    $this->writeln('Verbose mode enabled.');
}
```

### Removing an Option

An option can be removed from the command definition using `removeOption()`:

```php
$this->removeOption('verbose');
```

## Data Types

Arguments and options can define an expected data type. SigmaPHP Console provides four built-in data types.

| Data Type          | Value    | Description                                                     |
| ------------------ | -------- | --------------------------------------------------------------- |
| `DataType::STRING` | `string` | A string value.                                                 |
| `DataType::NUMBER` | `number` | A numeric value, including integers and floating-point numbers. |
| `DataType::LIST`   | `list`   | A list of values.                                               |
| `DataType::BOOL`   | `bool`   | A boolean flag that does not accept a parameter.                |

### String

`DataType::STRING` is used for textual values.

```php
$this->addArgument(
    'name',
    'Name of the user.',
    DataType::STRING
);
```

For example:

```php
./app user Ahmed
```

### Number

`DataType::NUMBER` is used for numeric values and supports both integers and floating-point numbers.

```php
$this->addArgument(
    'amount',
    'Amount to process.',
    DataType::NUMBER
);
```

Examples include:

```php
./app process 100
```

and:

```php
./app process 10.5
```

### List

`DataType::LIST` is used when an input contains multiple values.

A list is represented as an array:

```php
['ahmed', 'omar']
```

For example, a list option can be used to provide multiple values to a command.

### Boolean

`DataType::BOOL` represents a boolean option and does not accept a parameter.

For example:

```php
--interactive
```

or:

```php
-i
```

A boolean option is enabled by providing the option itself.

## Arguments and Options State

The command provides methods for determining whether arguments or options were provided by the user.

It is important to distinguish these methods from methods that inspect the command definition.

`hasArgument()` and `hasOption()` check the **current command input**. They determine whether the user actually supplied the corresponding argument or option; they do not determine whether an argument or option has been registered in the command.

### Checking Arguments

Use `argumentsAreEmpty()` to check whether no arguments were provided:

```php
if ($this->argumentsAreEmpty()) {
    $this->writeln('No arguments provided.');
}
```

### Checking Options

Use `optionsAreEmpty()` to check whether no options were provided:

```php
if ($this->optionsAreEmpty()) {
    $this->writeln('No options provided.');
}
```

### Checking All Input

Use `isEmpty()` to determine whether neither arguments nor options were provided:

```php
if ($this->isEmpty()) {
    $this->writeln('No input provided.');
}
```

These methods are useful when a command supports different execution modes depending on the input provided by the user.

## Global Options

By default, every command inherits the application's global options, with the exception of the `version` option.

This means that options such as `--help`, `--quiet`, `--silent`, and `--verbose` are automatically available to commands without having to define them again.

For example, a command can use the application's `--verbose` option directly:

```php
./app hello --verbose
```

Commands can also customize their inherited global options. An inherited option can be removed when it is not applicable to a particular command, or its configuration can be updated when different behavior is required.

For example, a command can remove an inherited option:

```php
$this->removeOption('verbose');
```

A command can also define or update an option according to its own requirements:

```php
$this->addOption(
    'verbose',
    'v',
    'Show detailed command information.',
    Option::PARAMETER_NONE,
    DataType::BOOL,
    false
);
```

The inherited global options therefore provide a common set of application-wide functionality while still allowing individual commands to customize the options available to them.

The `version` option is excluded from command inheritance because it is an application-level option and is handled by the application itself.

## Command Aliases

A command can have one or more aliases. Aliases allow users to execute the same command using different names.

Use `setAliases()` to define aliases:

```
$this->setAliases([
    'greet',
    'hi'
]);
```

The command can then be invoked using any of its registered names.

Use `getAliases()` to retrieve the configured aliases:

```
$aliases = $this->getAliases();
```

## Help

The `help()` method provides the command's help handler:

```
$this->help();
```

The help output can include the command description, arguments, options, aliases, and other information defined by the command.

Commands can use this method when custom logic needs to explicitly display their help information. The method can also be overridden by child commands when custom help behavior is required.

## Input and Output

Commands provide a set of methods for interacting with the standard CLI streams through the command's IO handler.

### Writing to Standard Output

Use `write()` to write text without automatically adding a new line:

```
$this->write('Processing...');
```

Use `writeln()` when a new line should be added:

```
$this->writeln('Processing complete.');
```

Both methods accept an optional style:

```
$this->writeln('Processing complete.', 'success');
```

### Writing to Standard Error

Use `writeErr()` to write directly to the standard error stream:

```
$this->writeErr('Something went wrong.');
```

This is useful for errors and diagnostic messages that should be separated from normal command output.

### Reading Input

Use `read()` to read input from the standard input stream:

```
$name = $this->read();

$this->writeln("Hello {$name}!");
```

The command's IO handler uses the application's configured input stream, making interactive input testable with custom streams.

## Message Helpers

Commands provide several methods for displaying commonly used message types.

### Info

Use `info()` for informational messages:

```
$this->info('Starting the operation...');
```

### Success

Use `success()` for successful operations:

```
$this->success('Operation completed successfully.');
```

### Warning

Use `warning()` for warning messages:

```
$this->warning('The configuration file was not found.');
```

### Error

Use `error()` for error messages:

```
$this->error('Unable to connect to the database.');
```

These methods provide a consistent way to display messages without requiring every command to manually configure their output formatting.
