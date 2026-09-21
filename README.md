# SigmaPHP-Console

**SigmaPHP-Console** is a lightweight and powerful PHP library for building modern command-line applications with minimal effort. It provides everything you need to create structured CLI applications, including commands, arguments, options, aliases, validation, interactive input, formatted output, tables, progress bars, spinners, colors, and more. Its simple API and sensible defaults allow you to focus on your application's functionality instead of dealing with the complexity of CLI input and output handling.

Designed with flexibility and customization in mind, **SigmaPHP-Console** gives you full control over how your application behaves and how information is presented to users. Whether you are building a small utility, a developer tool, an automation script, or a complete command-line application, **SigmaPHP-Console** provides the building blocks to create a clean, consistent, and user-friendly CLI experience without unnecessary boilerplate.

## Installation

``` 
composer require sigmaphp/sigmaphp-console
```

## Features

- **Zero Configuration** — Get started immediately with sensible defaults and no boilerplate configuration.
- **Easy Setup** — Build console applications quickly with a simple and intuitive API.
- **Arguments & Options Parsing** — Parse arguments, options, and option shortcuts with ease.
- **Data Types & Validation** — Built-in support for strings, numbers, booleans, and lists, with validation.
- **Command Aliases** — Define multiple aliases for commands for greater flexibility.
- **ANSI Color Support** — Support for ANSI 8-color and 256-color palettes.
- **Foreground & Background Colors** — Full control over text foreground and background colors.
- **Text Formatting** — Format console output with bold, underline, dim, inverse, and more.
- **Full Customization** — Customize virtually every aspect of your console application's behavior and output.
- **Automatic Help Menus** — Built-in help menus for applications and individual commands.
- **Text Boxes** — Create visually structured text sections using customizable box shapes.
- **Interactive Questions** — Prompt users with text input, choices, and hidden secret/password input.
- **Tables** — Display structured data in clean, formatted tables with headers.
- **Progress Bars** — Provide visual feedback for long-running operations with customizable progress bars.
- **Loading Spinners** — Display animated loading spinners while tasks are running.

## Getting Started

To create a new console app, you can start from the provided template by copy it and rename it
as you like, in the example below, we copy the default template and create new app called `my-app`:

```bash
cp bin/app.template my-app

chmod +x my-app
```
Before running the app, make sure to edit `my-app`, add your commands and other functions. By default
this is default content of any new console app:

```php
#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

$app = new SigmaPHP\Console\App();

// add your magic here

$app->run();
```

Finally, you can run your app using standard linux method:

```bash
./my-app
```
Also, in case your environment doesn't allow you to modify or execute scripts directly and since the the file is a valid, you can run the application using php runtime:

```bash
php my-app
```
## Documentation

* [Application](https://github.com/SigmaPHP/SigmaPHP-Console/blob/master/docs/application.md)
* [Commands](https://github.com/SigmaPHP/SigmaPHP-Console/blob/master/docs/commands.md)
* [Colors](https://github.com/SigmaPHP/SigmaPHP-Console/blob/master/docs/colors.md)
* [Text Format](https://github.com/SigmaPHP/SigmaPHP-Console/blob/master/docs/text-format.md)
* [Boxes](https://github.com/SigmaPHP/SigmaPHP-Console/blob/master/docs/boxes.md)
* [Tables](https://github.com/SigmaPHP/SigmaPHP-Console/blob/master/docs/tables.md)
* [Questions](https://github.com/SigmaPHP/SigmaPHP-Console/blob/master/docs/questions.md)
* [Progress Bar](https://github.com/SigmaPHP/SigmaPHP-Console/blob/master/docs/progress-bar.md)
* [Loading Spinner](https://github.com/SigmaPHP/SigmaPHP-Console/blob/master/docs/loading-spinner.md)

## Example

Below is a simple calculator application:

### `calculator`

```php
#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use SigmaPHP\Console\App;
use ConsoleCalc\Commands\AddCommand;
use ConsoleCalc\Commands\SubCommand;
use ConsoleCalc\Commands\MulCommand;
use ConsoleCalc\Commands\DivCommand;

$app = new App();

$app->setAppName('Console-Calc');
$app->setAppDescription('A simple command-line calculator');
$app->setAppVersion('1.0');

$app->addCommand(AddCommand::class);
$app->addCommand(SubCommand::class);
$app->addCommand(MulCommand::class);
$app->addCommand(DivCommand::class);

$app->run();
```

### `AddCommand.php`

```php
<?php

namespace ConsoleCalc\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;

class AddCommand extends Command
{
    public function init()
    {
        $this->setName('add');
        $this->setDescription('Add two numbers');
        $this->setAliases(['sum']);

        $this->addArgument('a', 'Number A', DataType::NUMBER);
        $this->addArgument('b', 'Number B', DataType::NUMBER);
    }

    public function execute()
    {
        $a = $this->getArgument('a');
        $b = $this->getArgument('b');

        $this->writeln("Result is: " . ($a + $b));
    }
}
```

### `SubCommand.php`

```php
<?php

namespace ConsoleCalc\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;

class SubCommand extends Command
{
    public function init()
    {
        $this->setName('sub');
        $this->setDescription('Subtract two numbers');
        
        $this->addArgument('a', 'Number A', DataType::NUMBER);
        $this->addArgument('b', 'Number B', DataType::NUMBER);
    }

    public function execute()
    {
        $a = $this->getArgument('a');
        $b = $this->getArgument('b');

        $this->writeln("Result is: " . ($a - $b));
    }
}
```

### `MulCommand.php`

```php
<?php

namespace ConsoleCalc\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;

class MulCommand extends Command
{
    public function init()
    {
        $this->setName('mul');
        $this->setDescription('Multiply two numbers');
    
        $this->addArgument('a', 'Number A', DataType::NUMBER);
        $this->addArgument('b', 'Number B', DataType::NUMBER);
    }

    public function execute()
    {
        $a = $this->getArgument('a');
        $b = $this->getArgument('b');

        $this->writeln("Result is: " . ($a * $b));
    }
}
```

### `DivCommand.php`

```php
<?php

namespace ConsoleCalc\Commands;

use SigmaPHP\Console\Command;
use SigmaPHP\Console\DataType;

class DivCommand extends Command
{
    public function init()
    {
        $this->setName('div');
        $this->setDescription('Divide two numbers');

        $this->addArgument('a', 'Number A', DataType::NUMBER);
        $this->addArgument('b', 'Number B', DataType::NUMBER);
    }

    public function execute()
    {
        $a = $this->getArgument('a');
        $b = $this->getArgument('b');

        if ($b == 0) {
            $this->error('Error: Division by zero is not allowed');
            return;
        }

        $this->writeln("Result is: " . ($a / $b));
    }
}
```

### Usage

```bash
./calculator add 10 5
# 15

./calculator sub 10 5
# 5

./calculator mul 10 5
# 50

./calculator div 10 5
# 2
```

## License
(SigmaPHP-Console) released under the terms of the MIT license.
