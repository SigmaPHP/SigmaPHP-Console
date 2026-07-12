<?php

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\FileUtility;

/**
 * File Utility Test
 */
class FileUtilityTest extends TestCase
{
    /**
     * @var FileUtility $fileUtility
     */
    private $fileUtility;

    /**
     * FileUtilityTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->fileUtility = new FileUtility();
    }

    /**
     * Test list files.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testListFiles()
    {
        $this->assertEquals([
            'ConsoleTest.php',
            'ColorTest.php',
            'CommandTest.php',
            'TestApp.php',
            'AppTest.php',
            'FileUtilityTest.php',
        ], $this->fileUtility->list(__DIR__));

        $this->assertEquals([
            __DIR__ . '/ConsoleTest.php',
            __DIR__ . '/ColorTest.php',
            __DIR__ . '/CommandTest.php',
            __DIR__ . '/TestApp.php',
            __DIR__ . '/AppTest.php',
            __DIR__ . '/FileUtilityTest.php',
        ], $this->fileUtility->list(__DIR__, true));

        $this->assertEquals([
            'ConsoleTest',
            'ColorTest',
            'CommandTest',
            'TestApp',
            'AppTest',
            'FileUtilityTest',
        ], $this->fileUtility->list(__DIR__, false, false));
    }

    /**
     * Test create file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCreateFile()
    {
        $path = __DIR__ . '/testing.txt';

        $this->fileUtility->create($path);

        $this->assertTrue(file_exists($path));
        $this->assertEquals(
            0755,
            substr(sprintf('%o', fileperms('/tmp')), -4)
        );
    }

    /**
     * Test will throw exception if the provided items is not of type array.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfTheProvidedItemsIsNotOfTypeArray()
    {
        $this->expectException(\InvalidArgumentException::class);

        $collection = new Collection(0);
    }

    /**
     * Test count.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCount()
    {
        $this->assertEquals(5, $this->collection->count());
        $this->assertEquals(1, (new Collection([0]))->count());
        $this->assertEquals(1, (new Collection([null]))->count());
        $this->assertEquals(1, (new Collection([false]))->count());
        $this->assertEquals(0, (new Collection([]))->count());
        $this->assertEquals(0, (new Collection())->count());
    }
}
