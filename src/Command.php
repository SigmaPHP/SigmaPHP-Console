<?php

namespace SigmaPHP\Console;

use SigmaPHP\Console\Interfaces\CommandInterface;
use SigmaPHP\Console\IO;
use SigmaPHP\Console\Argument;
use SigmaPHP\Console\DataType;
use SigmaPHP\Console\Option;
use SigmaPHP\Console\InputHandler;

/**
 * Command Class.
 */
abstract class Command implements CommandInterface
{
    /**
     * @var string $appName
     */
    protected $appName;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var array<Argument> $arguments
     */
    protected $arguments;

    /**
     * @var array<Option> $options
     */
    protected $options;

    /**
     * @var IO $io
     */
    protected $io;

    /**
     * @var InputHandler $input
     */
    protected $input;

    /**
     * Command Constructor.
     *
     * @param string $appName
     */
    public function __construct($appName = 'App')
    {
        $this->appName = $appName;
        $this->arguments = [];
        $this->options = [];

        // register global options
        $this->addOption('help', 'h', 'Print the help menu');

        $this->init();

        $this->io = new IO();
        $this->input = new InputHandler($this->arguments, $this->options);
    }

    /**
     * Initialize the command.
     *
     * @return void
     */
    abstract public function init();

    /**
     * Execute.
     *
     * @return void
     */
    abstract public function execute();

    /**
     * A proxy for the execution method to force global settings and options.
     *
     * @return void
     */
    public function executionHandler()
    {
        $this->input->process();

        if (($this->input->hasOption('help') || $this->input->isEmpty()) &&
            !empty($this->input->getCommand())
        ) {
            $this->help();
        } else {
            $this->execute();
        }
    }

    /**
     * Set command's name.
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set command's description.
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get command's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get command's description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add argument.
     *
     * @param string $name
     * @param string $description
     * @param DataType $dataType
     * @return void
     */
    public function addArgument(
        $name,
        $description = '',
        $dataType = DataType::STRING
    ) {
        $this->arguments[$name] = new Argument(
            $name,
            $description,
            $dataType
        );

        $this->arguments[$name]->setOrder(count($this->arguments) - 1);
    }

    /**
     * Remove arguments.
     *
     * @param string $name
     * @return void
     */
    public function removeArgument($name)
    {
        if (!isset($this->arguments[$name])) {
            return;
        }

        unset($this->arguments[$name]);
    }

    /**
     * Get argument.
     *
     * @param string $name
     * @return mixed
     */
    public function getArgument($name)
    {
        if (!isset($this->arguments[$name])) {
            return null;
        }

        return $this->arguments[$name];
    }

    /**
     * Get arguments.
     *
     * @return array<Argument>
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Add option.
     *
     * @param string $name
     * @param string $shortcut
     * @param string $description
     * @param string $parameterOptionality
     * @param DataType $dataType
     * @param mixed $defaultValue
     * @return void
     */
    public function addOption(
        $name,
        $shortcut = '',
        $description = '',
        $parameterOptionality = Option::NONE,
        $dataType = DataType::STRING,
        $defaultValue = null,
    ) {
        $this->options[$name] = new Option(
            $name,
            $shortcut,
            $description,
            $parameterOptionality,
            $dataType,
            $defaultValue
        );
    }

    /**
     * Remove options.
     *
     * @param string $name
     * @return void
     */
    public function removeOption($name)
    {
        if (!isset($this->options[$name])) {
            return;
        }

        unset($this->options[$name]);
    }

    /**
     * Get option.
     *
     * @param string $name
     * @return mixed
     */
    public function getOption($name)
    {
        if (!isset($this->arguments[$name])) {
            return null;
        }

        return $this->options[$name];
    }

    /**
     * Get options.
     *
     * @return array<Option>
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Help option's handler.
     *
     * @return void
     */
    public function help()
    {
        $helpContent = "{$this->description}\n\n";

        $helpContent .= "Usage:\n";
        $helpContent .= "\t{$this->appName} {$this->name} " .
            "[OPTIONS] [--] [ARGUMENTS]\n\n";

        if (!empty($this->arguments)) {
            $helpContent .= "Arguments:\n";

            ksort($this->arguments);

            foreach ($this->arguments as $argument) {
                $helpContent .= "\t{$argument->getName()}\t" .
                    "\t{$argument->getDescription()}\n";
            }
        }

        $helpContent .= "\n";

        if (!empty($this->options)) {
            $helpContent .= "Options:\n";

            ksort($this->options);

            foreach ($this->options as $option) {
                $helpContent .= "\t-{$option->getShortcut()}" .
                    ", --{$option->getName()}" .
                    "\t\t{$option->getDescription()}\n";
            }
        }

        $helpContent .= "\n";

        $this->io->write($helpContent);
    }
}
