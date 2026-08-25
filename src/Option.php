<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\DataType;

/**
 * Option Class.
 */
class Option
{
    /**
     * Option's parameter optionality.
     */
    public const PARAMETER_REQUIRED = 'required';
    public const PARAMETER_OPTIONAL = 'optional';
    public const PARAMETER_NONE     = 'none';

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $shortcut
     */
    protected $shortcut;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var string $parameterOptionality
     */
    protected $parameterOptionality;

    /**
     * @var DataType $parameterDataType
     */
    protected $parameterDataType;

    /**
     * @var mixed $defaultValue
     */
    protected $defaultValue;

    /**
     * @var mixed $value
     */
    protected $value;

    /**
     * Option Constructor.
     *
     * Note: PHP built-in getopt() function has weird rule for required/optional
     * parameters, use ':' for 'required' parameters and '::' for 'optional'.
     *
     * Also, $name here refers to 'longname' while $shortcut for 'shortname'.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param string $parameterOptionality
     * @param DataType $parameterDataType
     * @param mixed $defaultValue
     */
    public function __construct(
        $name,
        $shortcut = '',
        $description = '',
        $parameterOptionality = self::PARAMETER_OPTIONAL,
        $parameterDataType = DataType::STRING,
        $defaultValue = null,
    ) {
        $this->name = $name;
        $this->shortcut = $shortcut;
        $this->description = $description;
        $this->parameterOptionality = $parameterOptionality;
        $this->parameterDataType = $parameterDataType;
        $this->defaultValue = $defaultValue;
        $this->value = null;

        $this->validate();
    }

    /**
     * Validate the data type and parameter's optionality of the option.
     *
     * @return void
     */
    protected function validate()
    {
        // make sure that optionality and data type are valid
        if (!in_array($this->parameterOptionality, [
            Option::PARAMETER_REQUIRED,
            Option::PARAMETER_OPTIONAL,
            Option::PARAMETER_NONE,
        ])) {
            throw new \InvalidArgumentException(
                "Invalid optionality '{$this->parameterOptionality}' for " .
                "option '{$this->name}'"
            );
        }

        if (!in_array($this->parameterDataType, [
            DataType::LIST,
            DataType::STRING,
            DataType::NUMBER,
            DataType::BOOL,
        ])) {
            throw new \InvalidArgumentException(
                "Invalid data type '{$this->parameterDataType}' for option " .
                "'{$this->name}'"
            );
        }

        // if the option accepts no parameter, then its data type should be bool
        if ($this->parameterOptionality == self::PARAMETER_NONE &&
            $this->parameterDataType != DataType::BOOL
        ) {
            throw new \InvalidArgumentException(
                "Invalid data type for option '{$this->name}', none " .
                "parameterized options can only be of type Boolean"
            );
        }

        // also, goes the other way :D
        if ($this->parameterDataType == DataType::BOOL &&
            $this->parameterOptionality != self::PARAMETER_NONE
        ) {
            throw new \InvalidArgumentException(
                "Invalid parameter's optionality for option '{$this->name}', " .
                "options of type Boolean can't accept parameters"
            );
        }

        // default values doesn't work with REQUIRED and NONE
        if ($this->parameterOptionality == self::PARAMETER_REQUIRED &&
            !empty($this->defaultValue)
        ) {
            throw new \InvalidArgumentException(
                "Invalid parameter's default value for option " .
                "'{$this->name}', options with required parameters can't " .
                "accept default values"
            );
        }

        if ($this->parameterOptionality == self::PARAMETER_NONE &&
            !empty($this->defaultValue)
        ) {
            throw new \InvalidArgumentException(
                "Invalid parameter's default value for option " .
                "'{$this->name}', options with no parameters can't " .
                "accept default values"
            );
        }

        // optional parameters should have default values
        if ($this->parameterOptionality == self::PARAMETER_OPTIONAL &&
            is_null($this->defaultValue)
        ) {
            throw new \InvalidArgumentException(
                "Invalid default value for option '{$this->name}', options " .
                "with parameters of type optional, should have default value"
            );
        }
    }

    /**
     * Set options's name.
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set options's shortcut.
     *
     * @param string $shortcut
     * @return void
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;
    }

    /**
     * Set options's description.
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set options's parameter optionality.
     *
     * @param string $parameterOptionality
     * @return void
     */
    public function setParameterOptionality($parameterOptionality)
    {
        $this->parameterOptionality = $parameterOptionality;
    }

    /**
     * Set options's parameter data type.
     *
     * @param DataType $parameterDataType
     * @return void
     */
    public function setParameterDataType($parameterDataType)
    {
        $this->parameterDataType = $parameterDataType;
    }

    /**
     * Set options's default value.
     *
     * @param mixed $defaultValue
     * @return void
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Set options's value.
     *
     * @param mixed $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = DataType::validate(
            $this->parameterDataType,
            $this->name,
            $value
        );
    }

    /**
     * Get options's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get options's shortcut.
     *
     * @return string
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }

    /**
     * Get options's description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get options's parameter optionality.
     *
     * @return string
     */
    public function getParameterOptionality()
    {
        return $this->parameterOptionality;
    }

    /**
     * Get options's parameter data type.
     *
     * @return DataType
     */
    public function getParameterDataType()
    {
        return $this->parameterDataType;
    }

    /**
     * Get options's default value.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Get options's value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
