<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\Color;

/**
 * Color Test.
 */
class ColorTest extends TestCase
{
    /**
     * @var Color $color
     */
    private $color;

    /**
     * ColorTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->color = new Color();
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
     * Test foreground ANSI-16.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testForeground16()
    {
        $result = $this->color->colorize("Hello world", "green");

        $this->assertEquals(
            '\033[32mHello world\033[0m',
            str_replace("\033", "\\033", $result)
        );
    }

    /**
     * Test foreground ANSI-256.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testForeground256()
    {
        $result = $this->color->colorize("Hello world", 33);

        $this->assertEquals(
            '\033[38;5;33mHello world\033[0m',
            str_replace("\033", "\\033", $result)
        );
    }

    /**
     * Test background ANSI-16.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testBackground16()
    {
        $result = $this->color->colorize("Hello world", "green", true);

        $this->assertEquals(
            '\033[42mHello world\033[0m',
            str_replace("\033", "\\033", $result)
        );
    }

    /**
     * Test background ANSI-256.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testBackground256()
    {
        $result = $this->color->colorize("Hello world", 33, true);

        $this->assertEquals(
            '\033[48;5;33mHello world\033[0m',
            str_replace("\033", "\\033", $result)
        );
    }

    /**
     * Test will throw exception if invalid ANSI-16 color.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfInvalidAnsi16Color()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->color->colorize("Hello world", "unknown", true);
    }

    /**
     * Test will throw exception if invalid ANSI-256 color.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfInvalidAnsi256Color()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->color->colorize("Hello world", 5000, true);
    }
}
