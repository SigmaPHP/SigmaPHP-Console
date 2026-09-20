# Colors

The `SigmaPHP-Console` library provides color support through ANSI escape sequences. Colors can be applied directly to text when writing output from a command.

## Foreground Colors

The following ANSI-16 colors are available for foreground text:

| Color | ANSI Code |
| --- | ---: |
| `default` | `39` |
| `black` | `30` |
| `red` | `31` |
| `green` | `32` |
| `yellow` | `33` |
| `blue` | `34` |
| `magenta` | `35` |
| `cyan` | `36` |
| `light_gray` | `37` |
| `dark_gray` | `90` |
| `light_red` | `91` |
| `light_green` | `92` |
| `light_yellow` | `93` |
| `light_blue` | `94` |
| `light_magenta` | `95` |
| `light_cyan` | `96` |
| `white` | `97` |

String color names can be used when applying ANSI-16 foreground colors.

## Background Colors

The following ANSI-16 colors are available for backgrounds:

| Color | ANSI Code |
| --- | ---: |
| `default` | `49` |
| `black` | `40` |
| `red` | `41` |
| `green` | `42` |
| `yellow` | `43` |
| `blue` | `44` |
| `magenta` | `45` |
| `cyan` | `46` |
| `light_gray` | `47` |
| `dark_gray` | `100` |
| `light_red` | `101` |
| `light_green` | `102` |
| `light_yellow` | `103` |
| `light_blue` | `104` |
| `light_magenta` | `105` |
| `light_cyan` | `106` |
| `white` | `107` |

Background colors can be specified using the `bg` option when writing command output.

## ANSI-256 Colors

In addition to the standard ANSI-16 colors, the library supports ANSI-256 colors. ANSI-256 colors are specified using integer values rather than color names.

For example, `180` can be used as an ANSI-256 background color.

## Using Colors in a Command

Colors and formatting can be applied directly when writing output from a command using the `write()` `writeln()` and `writeErr()` methods.

For example:

```php
$this->write('Hello World', 'fg=red;bg=180;bold');
```

In this example:

- `fg=red` sets the foreground color to red.
- `bg=180` sets the background to ANSI-256 color `180`.
- `bold` applies bold text formatting.

Multiple formatting options can be combined using semicolons.

### Examples

```php
$this->write('Success', 'fg=green;bg=blue');

$this->writeln('Warning', 'fg=yellow;bold;dim');

$this->writeErr('Error', 'underline;fg=red;bold');
```

This allows commands to produce readable and structured terminal output while keeping formatting declarations directly alongside the output they affect.
