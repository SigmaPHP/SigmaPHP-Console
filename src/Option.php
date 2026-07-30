<?php

namespace SigmaPHP\Console;

/**
 * Option Class.
 */
class Option
{
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
     * @var string $parameterType
     */
    protected $parameterType;

    /**
     * @var string $dataType
     */
    protected $dataType;

    /**
     * @var mixed $defaultValue
     */
    protected $defaultValue;


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
     * @param OptionParameterType $parameterType
     * @param OptionDataType $dataType
     * @param mixed $defaultValue
     */
    public function __construct(
        $name,
        $shortcut = '',
        $description = '',
        $parameterType = OptionParameterType::NONE,
        $dataType = OptionDataType::STRING,
        $defaultValue = null,
    ) {
        $this->name = $name;
        $this->shortcut = $shortcut;
        $this->description = $description;
        $this->parameterType = $parameterType;
        $this->dataType = $dataType;
        $this->defaultValue = $defaultValue;
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
     * Set options's parameter type.
     *
     * @param OptionParameterType $parameterType
     * @return void
     */
    public function setParameterType($parameterType)
    {
        $this->parameterType = $parameterType;
    }

    /**
     * Set options's data type.
     *
     * @param OptionDataType $dataType
     * @return void
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
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
     * Get options's parameter type.
     *
     * @return OptionParameterType
     */
    public function getParameterType()
    {
        return $this->parameterType;
    }

    /**
     * Get options's data type.
     *
     * @return OptionDataType
     */
    public function getDataType()
    {
        return $this->dataType;
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
}

class OptionParameterType
{
    public const REQUIRED = 'required';
    public const OPTIONAL = 'optional';
    public const NONE     = 'none';
}

class OptionDataType
{
    public const STRING = 'string';
    public const NUMBER = 'number';
    public const LIST   = 'list';
    public const BOOL   = 'bool';
}
