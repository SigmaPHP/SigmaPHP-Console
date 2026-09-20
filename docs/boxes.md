# Boxes

The `Box` class provides a simple way to display text inside a bordered box in the terminal. It can be used to highlight messages, information, or sections of command output.

## Creating a Box

The `create()` method accepts the text to display, an optional style, and an optional border character.

### Parameters

| Parameter | Type     | Default | Description                                       |
| --------- | -------- | ------- | ------------------------------------------------- |
| `$text`   | `string` | —       | The text to display inside the box.               |
| `$style`  | `string` | `''`    | Formatting styles and colors to apply to the box. |
| `$border` | `string` | `'*'`   | The character used to draw the box border.        |

## Using a Box in a Command

The `Box` class can be accessed directly from a command using the `$this->box` property.

```php
$this->box->create("Hello, World!");
```

This produces:

```text
***********************************
*                                 *
*         Hello, World!           *
*                                 *
***********************************
```

## Custom Borders

A different character can be provided as the border:

```php
$this->box->create("Hello, World!", '', '#');
```

This produces a box using `#` as the border character:

```text
###################################
#                                 #
#         Hello, World!           #
#                                 #
###################################
```

## Styling the Box

The `$style` parameter can be used to apply colors and text formatting to the box.

```php
$this->box->create("Hello, World!", 'fg=green;bold');
```

Multiple formatting options can be combined using semicolons.
