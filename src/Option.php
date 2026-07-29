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
     * @var mixed $defaultValue
     */
    protected $defaultValue;

    /**
     * @var string $dataType
     */
    protected $dataType;

    /**
     * @var string $parameterType
     */
    protected $parameterType;

    /**
     * Option Constructor.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param mixed $defaultValue
     * @param string $dataType
     * @param string $parameterType
     */
    public function __construct(
        $name,
        $shortcut,
        $description,
        $dataType,
        $defaultValue,
        $parameterType
    ) {
        $this->name = $name;
        $this->shortcut = $shortcut;
        $this->description = $description;
        $this->dataType = $dataType;
        $this->defaultValue = $defaultValue;
        $this->parameterType = $parameterType;
    }
}

class OptionDataType
{
    public const STRING = 'string';
    public const NUMBER = 'number';
    public const LIST   = 'list';
    public const BOOL   = 'bool';
}

class OptionParameterType
{
    public const REQUIRED = 'required';
    public const OPTIONAL = 'optional';
    public const NONE     = 'none';
}
