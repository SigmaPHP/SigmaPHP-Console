Text Format

The `SigmaPHP-Console` library supports text formatting through ANSI escape sequences. Text styles can be combined with foreground and background colors to customize command output.

## Available Text Styles

| Style | ANSI Code | Description |
| --- | ---: | --- |
| `bold` | `1` | Displays text in bold. |
| `dim` | `2` | Displays text with reduced intensity. |
| `underline` | `4` | Underlines the text. |
| `blink` | `5` | Makes the text blink when supported by the terminal. |
| `reverse` | `7` | Reverses the foreground and background colors. |
| `hidden` | `8` | Hides the text when supported by the terminal. |

## Using Text Formatting in a Command

Text styles can be applied directly to command output using the write() method.

```php
$this->write('Hello World', 'bold');
```

Multiple styles can be combined by separating them with semicolons.

```php
$this->write('Important Message', 'bold;underline');
```


Text formatting can be combined with foreground and background colors.

```php
$this->write('Warning', 'fg=yellow;bold');
```

```php
$this->write('Error', 'fg=red;bold;underline');
```

```php
$this->write('Success', 'fg=green;bold');
```

Background colors can also be included:

```php
$this->write('Important', 'fg=white;bg=red;bold');
```

ANSI-256 colors can be used for the background or foreground by specifying their integer value:

```php
$this->write('Highlighted', 'fg=white;bg=180;bold');
```
