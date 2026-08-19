<?php

namespace SigmaPHP\Console;

/**
 * DataType Class.
 */
class DataType
{
    public const STRING = 'string';
    public const NUMBER = 'number';
    public const LIST   = 'list';
    public const BOOL   = 'bool';

    /**
     * Validate value.
     *
     * @param string $targetType
     * @param string $fieldName
     * @param mixed $value
     * @return mixed
     */
    public static function validate($targetType, $fieldName, $value)
    {
        // validate data type
        // by default everything is a string :D
        switch ($targetType) {
            case DataType::LIST:

                // match list pattern:
                //
                // ["a"]
                // ["a", "b"]
                // ['a', 'b', 'c']

                $pattern =
                    '/^\[' . // open bracket
                    '(?:"[^"]*"|\'[^\']*\')' . // start single or double quote
                    '(?:\s*,\s*' . // some characters
                    '(?:"[^"]*"|\'[^\']*\'))' . // end single or double quote
                    '*' . // match multiple of same pattern
                    '\]$/'; // close bracket

                if (preg_match($pattern, $value) === false) {
                    throw new \InvalidArgumentException(
                        "The option '{$fieldName}' only accepts lists"
                    );
                }

                // ToDO: do it with $matches please!
                $value = explode(',',
                    str_replace(['[', ']', '"', '\'', ' '], '', $value));

                break;
            case DataType::NUMBER:
                if (!is_numeric($value)) {
                    throw new \InvalidArgumentException(
                        "The option '{$fieldName}' only accepts numeric values"
                    );
                }

                break;
            case DataType::BOOL:
                if (!is_bool($value)) {
                    throw new \InvalidArgumentException(
                        "The option '{$fieldName}' only accepts boolean values"
                    );
                }

                break;
        }

        return $value;
    }
}
