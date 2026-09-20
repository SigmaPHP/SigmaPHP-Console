# Questions

The `Question` provides a simple way to interact with users through questions and prompts inside a command. It supports confirmations, text input, choices, and secret input.

The question service is available through the command's `$this->question` property.

## Available Methods

| Method           | Parameters              | Return Type | Description                                    |
| ---------------- | ----------------------- | ----------- | ---------------------------------------------- |
| `confirmation()` | `$message`              | `bool`      | Asks the user to confirm an action.            |
| `ask()`          | `$question`             | `string`    | Asks the user for text input.                  |
| `choice()`       | `$question`, `$options` | `string`    | Asks the user to select an option from a list. |
| `secret()`       | `$question`             | `string`    | Asks the user for hidden input.                |

## Confirmation

The `confirmation()` method asks the user to confirm an action and returns a boolean result. The input is case-insensitive and only the following values are accepted:

* `yes/no`
* `y/n`

Both uppercase and lowercase values are accepted, such as YES, Yes, Y, NO, or n.

```php
$input = $this->question->confirmation("Please confirm?");
```

The method returns:

* `true` when the user confirms.
* `false` when the user declines.

## Asking a Question

The `ask()` method prompts the user for text input and returns the entered value as a string.

```php
$input = $this->question->ask("What's your name?");
```

## Choice

The `choice()` method allows the user to select a value from a list of available options.

It accepts the question and an array of string options, and returns the selected value.

```php
$input = $this->question->choice(
    "What's your favorite color?",
    ['red', 'blue', 'green']
);
```

## Secret Input

The `secret()` method prompts the user for sensitive input without displaying the entered characters on the terminal.

```php
$input = $this->question->secret("Enter your password:");
```

This can be used for passwords, tokens, or other sensitive values that should not be displayed while being entered.
