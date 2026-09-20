# Progress Bar

The `ProgressBar` provides a simple way to display the progress of a long-running operation in the console. It can be customized with different border characters, progress symbols, and styles.

### Creating a Progress Bar

A progress bar can be created using the `createProgressBar()` method inside the command:

```php
$progressBar = $this->createProgressBar();
```

The method accepts the following parameters:

| Parameter | Type | Default | Description |
| --- | --- | --- | --- |
| `$leftBorder` | `string` | `[` | The character or text used for the left border. |
| `$rightBorder` | `string` | `]` | The character or text used for the right border. |
| `$inProgressSymbol` | `string` | `-` | The symbol used for incomplete progress. |
| `$completeSymbol` | `string` | `=` | The symbol used for completed progress. |
| `$style` | `string` | `''` | Formatting styles and colors to apply to the progress bar. |

## Using a Progress Bar

Once created, the progress bar can be used to display the progress of an operation.

```php
$progressBar = $this->createProgressBar();

$items = 100;

$progressBar->start($items);

for ($i = 0; $i < $items; $i++) {
    // Process the item.

    $progressBar->update(1);
}

$progressBar->end();
```

### Starting a Progress Bar

Use the `start()` method to initialize the progress bar. It accepts the total number of steps and an optional starting position.

```php
$items = 10;

$progressBar->start($items);
```

The `$total` parameter defines the total number of steps required to complete the progress bar. The `$position` parameter can be used to specify the initial position and defaults to `0`.

### Updating the Progress

Use the `update()` method to advance the progress bar by a specified number of steps.

```php
for ($i = 0; $i < $items; $i++) {
    $progressBar->update(1);
}
```

The `$steps` parameter specifies how many steps to advance the progress bar. It can be greater than `1` when multiple steps are completed at once.

### Ending a Progress Bar

Use the `end()` method when the operation is complete:

```php
$progressBar->end();
```

This finalizes the progress bar and completes its output.

## Customizing the Progress Bar

The appearance of the progress bar can be customized when it is created:

```php
$progressBar = $this->createProgressBar(
    '<',
    '>',
    '.',
    '#',
    'fg=light_green'
);
```

This allows commands to create progress bars that better match their output style.
