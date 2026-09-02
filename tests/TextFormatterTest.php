<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\TextFormatter;

/**
 * Text Formatter Test.
 */
class TextFormatterTest extends TestCase
{
    /**
     * @var TextFormatter $textFormatter
     */
    private $textFormatter;

    /**
     * TextFormatterTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->textFormatter = new TextFormatter();
    }

    /**
     * ColorTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test style combinations.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testStyleCombinations()
    {
        $result = $this->textFormatter->format("Hello world", "bold;blink");

        $this->assertEquals(
            '\033[1;5mHello world\033[0m',
            str_replace("\033", "\\033", $result)
        );
    }

    /**
     * Test will throw exception if invalid style.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfInvalidStyle()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->textFormatter->format("Hello world", "unknown");
    }
}
