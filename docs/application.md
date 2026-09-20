# Application

The `App` component is the central entry point of `SigmaPHP-Console` applications. It is responsible for registering commands, managing global options, configuring input and output streams, and executing the appropriate command based on the arguments provided by the user.

An application can be configured programmatically by adding commands individually or by loading commands from a directory. It can also define options that are available globally to all commands.

## Table of Contents

- [Creating an Application](#creating-an-application)
- [Application Metadata](#application-metadata)
- [Commands](#commands)
- [Global Options](#global-options)
- [Input and Output Streams](#input-and-output-streams)
- [Application Lifecycle](#application-lifecycle)
- [Running the Application](#running-the-application)

## Creating an Application

A basic Console application can be created by instantiating the `App` class:

```php
<?php

require 'vendor/autoload.php';

use SigmaPHP\Console\App;

$app = new App();

$app->run();
```

The application can then be extended by adding commands, global options, and application metadata.

## Application Information

When default commands are enabled, the application can expose basic information such as its name, description, and version.

### Application Name

Use `setAppName()` to define the name of the application.

```php
$app->setAppName('My Console Application');
```

### Application Description

Use `setAppDescription()` to provide a short description of the application.

```php
$app->setAppDescription(
    'A command-line application built with `SigmaPHP-Console`.'
);
```

### Application Version

Use `setAppVersion()` to define the current application version.

```php
$app->setAppVersion('1.0.0');
```

These three methods are intended to be used when the application's **Default Commands** are enabled.

## Commands

Commands represent the executable actions provided by a console application. An application can contain any number of commands.

A command can be registered directly using `addCommand()`:

```php
$app->addCommand($command);
```

Before adding a command, you can check whether a command with the same name already exists:

```php
if (!$app->hasCommand('users')) {
    $app->addCommand($command);
}
```

### Getting a Command

A registered command can be retrieved using its name:

```php
$command = $app->getCommand('users');
```

If you need to inspect all registered commands, use `getCommands()`:

```php
$commands = $app->getCommands();
```

### Removing a Command

Commands can be removed from the application using `removeCommand()`:

```php
$app->removeCommand('users');
```

This is useful when commands are registered automatically but need to be disabled or replaced during application configuration.

### Loading Commands Automatically

Instead of registering every command manually, commands can be loaded from a directory using `loadCommands()`.

```php
$app->loadCommands(__DIR__ . '/Commands', 'App\\Commands');
```

The method accepts:

| Parameter | Description |
|---|---|
| `$path` | Directory containing the command classes. |
| `$nameSpace` | Namespace used by the command classes. |

This allows larger applications to organize commands into separate classes and load them automatically.

## Global Options

Global options are options that are available to the application regardless of which command is being executed.

`SigmaPHP-Console` provides the following global options by default:

| Option | Shortcut | Description |
| --- | --- | --- |
| `--help` | `-h` | Print the help menu |
| `--version` | `-V` | Print the application's version |
| `--quiet` | `-q` | Suppress normal output; show errors only |
| `--silent` | `-s` | Suppress all output, including errors |
| `--verbose` | `-v` | Show detailed debug information |

These options do not require parameters and use the boolean data type.

### Adding a new Global Option

A global option can be registered using `addGlobalOption()`:

```php
$app->addGlobalOption(
    'verbose',
    'v',
    'Show detailed output.',
    Option::PARAMETER_NONE,
    DataType::BOOL,
    true
);
```

The method accepts:

| Parameter | Description |
| --- | --- |
| `$name` | Long option name. |
| `$shortcut` | Short option name. |
| `$description` | Description displayed to the user. |
| `$parameterOptionality` | Defines whether the option parameter is required or optional. |
| `$dataType` | Expected data type of the option value. |
| `$defaultValue` | Value used when the option is not explicitly provided. |

Global options are useful for behavior that should apply consistently across multiple commands.

### Checking a Global Option

Use `hasGlobalOption()` to determine whether an option has been registered:

```php
if ($app->hasGlobalOption('verbose')) {
    // Global option is available.
}
```

### Getting a Global Option

A registered global option can be retrieved using `getGlobalOption()`:

```php
$option = $app->getGlobalOption('verbose');
```

This returns the corresponding `Option` instance.

### Getting All Global Options

Use `getGlobalOptions()` when you need to inspect all global options:

```php
$options = $app->getGlobalOptions();
```

The returned collection contains the registered global options organized by their long and short names.

### Removing a Global Option

A global option can be removed using its name:

```php
$app->removeGlobalOption('verbose');
```

## Input and Output Streams

The application provides separate streams for input, normal output, and error output. Streams allow the application to control where input is read from and where output is written to.

By default, `SigmaPHP-Console` uses the standard Unix streams:

* `STDIN` for input
* `STDOUT` for normal output
* `STDERR` for error output

### Output Stream

The output stream can be configured using `setOutputStream()`:

```
$app->setOutputStream($stream);
```

By default, the application uses `STDOUT`.

`STDOUT` writes directly to the standard output stream and therefore bypasses PHP's default output buffering. This is useful for command-line applications where output needs to be written directly to the console.

However, a custom stream can be useful when testing an application or when its output needs to be captured instead of displayed directly.

For example, an in-memory stream can be used:

```php
$stream = fopen('php://memory', 'w+');

$app->setOutputStream($stream);
```

A file can also be used as an output stream:

```php
$stream = fopen('/path/to/output.txt', 'w');

$app->setOutputStream($stream);
```

This allows the application's output to be inspected, stored, or processed after execution.

The default PHP output buffer can also be used when required, particularly when the application is being tested or when output needs to be captured through PHP's normal output buffering mechanism.

```php
$stream = fopen('php://output', 'w');

$app->setOutputStream($stream);
```

### Error Stream

The error stream can be configured using `setErrorStream()`:

```php
$app->setErrorStream($stream);
```

By default, errors are written to `STDERR`.

As with the output stream, a custom stream can be provided to capture errors during testing or redirect them to another destination.

For example:

```php
$stream = fopen('php://memory', 'w+');

$app->setErrorStream($stream);
```

### Input Stream

The application's input stream can be configured using `setInputStream()`:

```php
$app->setInputStream($stream);
```

By default, input is read from `STDIN`.

A custom stream can be useful when testing commands that require user input. For example, input can be provided through an in-memory stream:

```php
$stream = fopen('php://memory', 'w+');

fwrite($stream, "user input\n");
rewind($stream);

$app->setInputStream($stream);
```

This makes it possible to provide predefined input without requiring interactive user input.

Using custom streams is particularly useful for automated tests, where console input and output need to be controlled and inspected programmatically.

## Application Lifecycle

The application provides two lifecycle methods that can be used to perform actions before and after command execution:

* `beforeStart()`
* `afterComplete()`

These methods are intended to be customized by the application when application-specific lifecycle behavior is required.

To use them, create your own application class by extending the `App` class and define the required methods.

For example:

```php
<?php

namespace App;

use SigmaPHP\Console\App as ConsoleApp;

class App extends ConsoleApp
{
    public function beforeStart()
    {
        // Perform application initialization.
    }

    public function afterComplete()
    {
        // Perform application cleanup.
    }
}
```

The `beforeStart()` method is executed before the command execution begins. It can be used for application-level initialization, such as preparing resources, loading configuration, or setting up services.

The `afterComplete()` method is executed after command execution has completed. It can be used for cleanup, releasing resources, or performing other application-level finalization.

Once the custom application class has been created, it can be used in place of the default `App` class:

```php
$app = new App();

$app->run();
```

This approach keeps application-specific lifecycle logic inside the application's own `App` implementation while allowing SigmaPHP Console to manage the command execution lifecycle.


## Running the Application

Once the application has been configured, call `run()` to start the console application:

```php
$app->run();
```

The application processes the provided command-line arguments, resolves the requested command, processes its options and parameters, and executes the command.

A typical application entry point can therefore look like:

```php
#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use SigmaPHP\Console\App;

$app = new App();

$app->setAppName('My Application');
$app->setAppDescription('A `SigmaPHP-Console` application.');
$app->setAppVersion('1.0.0');

$app->loadCommands(__DIR__ . '/Commands', 'App\\Commands');

$app->run();
```
