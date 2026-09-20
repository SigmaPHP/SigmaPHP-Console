# Loading Spinner

The `LoadingSpinner` provides an animated indicator for long-running operations where a progress percentage is not required.

## Creating a Loading Spinner

A new loading spinner can be created inside a command using the `createLoadingSpinner()` method:

```php
$spinner = $this->createLoadingSpinner();
```

The method accepts a pattern and an optional style:

```php
$spinner = $this->createLoadingSpinner(
    $pattern = 'frames',
    $style = ''
) {}
```

| Parameter | Type | Default | Description |
| --- | --- | --- | --- |
| `$pattern` | `string` | `frames` | The animation pattern used by the spinner. |
| `$style` | `string` | `''` | Formatting styles and colors to apply to the spinner. |

## Spinner Patterns

SigmaPHP Console provides the following built-in spinner patterns:

| Pattern | Animation |
| --- | --- |
| `arc` | `◜ ◝ ◞ ◟` |
| `frames` | `\| / - \` |
| `dots` | `.  ..  ...` |
| `sweep` | `*---- -*--- --*-- ---*- ----*` |
| `pipes` | `┤ ┘ ┴ └ ├ ┌ ┬ ┐` |
| `braille` | `⠋ ⠙ ⠹ ⠸ ⠼ ⠴ ⠦ ⠧ ⠇ ⠏` |
| `brackets` | `[ ] [=] [==] [===] [ ==] [  =] [   ]` |

The default pattern is `frames`.

A specific pattern can be selected when creating the spinner:

```php
$spinner = $this->createLoadingSpinner('dots');
```

## Running a Loading Spinner

A loading spinner can be executed using the `run()` method. The method accepts a callback containing the operation that should run while the spinner is displayed.

```php
$spinner = $this->createLoadingSpinner('dots');

$spinner->run(function () {
    // Perform a long-running operation.
});
```

The callback is executed while the spinner is running. Once the callback completes, the spinner is stopped and its output is finalized.

The `run()` method accepts the following parameter:

| Parameter | Type | Description |
| --- | --- | --- |
| `$callback` | `callable` | The operation to execute while the spinner is running. |

## Custom Spinner Style

A style can be provided when creating the spinner:

```php
$spinner = $this->createLoadingSpinner(
    'dots',
    'fg=white;bg=red;bold'
);
```

## Platform Support

The loading spinner relies on the PHP `pcntl` extension to run the animation while the callback is executing.

This feature is currently supported on UNIX-like systems. Windows is not supported because the `pcntl` extension is not available on Windows.

A future implementation may use the PHP `parallel` extension instead. However, at the time of writing, the `parallel` extension is not considered mature enough to replace the current implementation.
