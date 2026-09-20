# Tables

The Table class provides a simple way to display structured data in a formatted table in the terminal. It can be used to present lists, records, or other tabular information in a readable format.

## Creating a Table

### Parameters

The `create()` method accepts a header, table data, and an optional style.

| Parameter | Type                   | Default | Description                                         |
| --------- | ---------------------- | ------- | --------------------------------------------------- |
| `$header` | `array<string>`        | —       | The header columns .                                 |
| `$data`   | `array<array<string>>` | —       | The rows of table data.                             |
| `$style`  | `string`               | `''`    | Formatting styles and colors to apply to the table. |

## Using a Table in a Command

The `Table` class can be accessed directly from a command using the `$this->table` property.

```php
$this->table->create(
    ['Name', 'Age', 'City'],
    [
        ['John', '25', 'Dubai'],
        ['Sarah', '30', 'New york'],
        ['Mike', '28', 'Tokyo'],
    ]
);
```

This produces a table similar to:

```text
--------------------------
| Name  | Age | City     |
--------------------------
| John  | 25 | Dubai     |
| Sarah | 30 | New york  |
| Mike  | 28 | Tokyo     |
--------------------------
```

## Styling the Table

The `$style` parameter can be used to apply colors and text formatting to the table.

```php
$this->table->create([....], 'fg=blue;bg=green');
```

Multiple formatting options can be combined using semicolons.
