<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\DataType;

/**
 * Argument Class.
 */
class Argument
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var int $order
     */
    protected $order;

    /**
     * @var DataType $dataType
     */
    protected $dataType;

    /**
     * @var mixed $value
     */
    protected $value;

    /**
     * Option Constructor.
     *
     * @param string $name
     * @param string $description
     * @param DataType $dataType
     */
    public function __construct(
        $name,
        $description = '',
        $dataType = DataType::STRING,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->dataType = $dataType;
        $this->value = null;
    }

    /**
     * Set argument's name.
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set argument's description.
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set argument's order in the $argv.
     *
     * @param int $order
     * @return void
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Set argument's data type.
     *
     * @param string $dataType
     * @return void
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
    }

    /**
     * Set argument's value.
     *
     * @param mixed $value
     * @return void
     */
    public function setValue($value)
    {
        // validate data type
        // by default everything is a string :D
        switch ($this->dataType) {
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
                    '*'; // match multiple of same pattern
                    '\]$/'; // close bracket

                if (preg_match($pattern, $value) !== false) {
                    throw new \InvalidArgumentException(
                        "The option '{$this->name}' only accepts lists"
                    );
                }
                break;
            case DataType::NUMBER:
                if (!is_numeric($value)) {
                    throw new \InvalidArgumentException(
                        "The option '{$this->name}' only accepts numeric values"
                    );
                }

                break;
            case DataType::BOOL:
                if (!is_bool($value)) {
                    throw new \InvalidArgumentException(
                        "The option '{$this->name}' only accepts boolean values"
                    );
                }

                break;
        }

        $this->value = $value;
    }

    /**
     * Get argument's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get argument's description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get argument's order in the $argv.
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Get argument's data type.
     *
     * @return string
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * Get argument's value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
