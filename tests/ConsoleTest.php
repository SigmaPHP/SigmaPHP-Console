<?php

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\Console;

/**
 * Console Test
 */
class ConsoleTest extends TestCase
{
    /**
     * @var Console $console
     */
    private $console;

    /**
     * ConsoleTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->console = new Console();
    }

    /**
     * Test write.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWrite()
    {
        stream_filter_register("intercept", InterceptFilter::class);

        $stdout = fopen('php://stdout', 'w');
        $filter = stream_filter_append($stdout, "intercept");

        // Code executing the stdout write
        $this->console->setOutputStream($stdout);
        $this->console->write('Hello SigmaPHP-Console');

        stream_filter_remove($filter);
        $this->assertEquals("Hello SigmaPHP-Console", InterceptFilter::$cache);
    }
}

/**
 * This class was added specifically to test the output. Unfortunately testing
 * output streams in PHP is tricky, since it bypass the default output buffer
 * used by `echo` and `print`.
 *
 * So basically this interceptor will interrupt the output and make it easier
 * for us to check the result.
 *
 * Source: https://gist.github.com/alfredbez/07b246046c7823a17557c844f21ae989
 */
class InterceptFilter extends \php_user_filter
{
    public static $cache = '';

    public function filter($in, $out, &$consumed, $closing): int
    {
        while ($bucket = stream_bucket_make_writeable($in)) {
            self::$cache .= $bucket->data;
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}
