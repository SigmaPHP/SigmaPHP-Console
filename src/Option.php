<?php

namespace SigmaPHP\Console;

/**
 * Option Class.
 */
class Option
{
    /**
     * Option's parameter optionality.
     */
    public const REQUIRED = 'required';
    public const OPTIONAL = 'optional';
    public const NONE     = 'none';

    /**
     * Option's parameter data types.
     */
    public const STRING = 'string';
    public const NUMBER = 'number';
    public const LIST   = 'list';
    public const BOOL   = 'bool';

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
     * @var string $parameterDataType
     */
    protected $parameterDataType;

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
     * @param string $parameterOptionality
     * @param string $parameterDataType
     * @param mixed $defaultValue
     */
    public function __construct(
        $name,
        $shortcut = '',
        $description = '',
        $parameterOptionality = self::NONE,
        $parameterDataType = self::STRING,
        $defaultValue = null,
    ) {
        $this->name = $name;
        $this->shortcut = $shortcut;
        $this->description = $description;
        $this->parameterOptionality = $parameterOptionality;
        $this->parameterDataType = $parameterDataType;
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
     * @param string $parameterDataType
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
     * @return string
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
}
