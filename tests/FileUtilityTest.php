<?php

namespace SigmaPHP\Console\Tests;

use PHPUnit\Framework\TestCase;
use SigmaPHP\Console\FileUtility;
use SigmaPHP\Console\Exceptions\PathNotFoundException;

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
     * @var string $path
     */
    private $path;

    /**
     * @var string $newPath
     */
    private $newPath;

    /**
     * @var string $dirPath
     */
    private $dirPath;

    /**
     * FileUtilityTest SetUp
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->fileUtility = new FileUtility();

        $this->path = __DIR__ . '/testing.txt';

        $this->newPath = __DIR__ . '/dump.txt';

        $this->dirPath = __DIR__ . '/examples';
    }

    /**
     * FileUtilityTest TearDown
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        // remove the dummy files and directories
        if (file_exists(__DIR__ . '/testing.txt')) {
            unlink(__DIR__ . '/testing.txt');
        }

        if (file_exists(__DIR__ . '/dump.txt')) {
            unlink(__DIR__ . '/dump.txt');
        }

        if (file_exists(__DIR__ . '/examples')) {
            rmdir(__DIR__ . '/examples');
        }
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
            'AppTest.php',
            'FileUtilityTest.php',
        ], $this->fileUtility->list(__DIR__));

        $this->assertEquals([
            __DIR__ . '/ConsoleTest.php',
            __DIR__ . '/ColorTest.php',
            __DIR__ . '/CommandTest.php',
            __DIR__ . '/AppTest.php',
            __DIR__ . '/FileUtilityTest.php',
        ], $this->fileUtility->list(__DIR__, true));

        $this->assertEquals([
            'ConsoleTest',
            'ColorTest',
            'CommandTest',
            'AppTest',
            'FileUtilityTest',
        ], $this->fileUtility->list(__DIR__, false, false));

        $this->assertEquals(
            [__DIR__ . '/FileUtilityTest.php'],
            $this->fileUtility->list(__DIR__ . '/FileUtilityTest.php')
        );
    }

    /**
     * Test will throw exception if the provided path doesn't exist.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWillThrowExceptionIfTheProvidedPathDoesNotExist()
    {
        $this->expectException(PathNotFoundException::class);

        $this->fileUtility->list(__DIR__ . '/nowhere');
    }

    /**
     * Test file exists.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testFileExists()
    {
        $this->assertTrue(
            $this->fileUtility->exists(__DIR__ . '/FileUtilityTest.php')
        );
    }

    /**
     * Test directory has a file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testDirectoryHasAFile()
    {
        $this->assertTrue(
            $this->fileUtility->dirHas(__DIR__, 'UtilityTest')
        );
    }

    /**
     * Test create file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCreateFile()
    {
        $this->fileUtility->create($this->path);

        $this->assertTrue(file_exists($this->path));

        $this->assertEquals(
            '0755',
            substr(sprintf('%o', fileperms($this->path)), -4)
        );
    }

    /**
     * Test rename file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRenameFile()
    {
        $this->fileUtility->create($this->path);

        $this->fileUtility->rename($this->path, $this->newPath);

        $this->assertTrue(file_exists($this->newPath));
        $this->assertFalse(file_exists($this->path));
    }

    /**
     * Test copy file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCopyFile()
    {
        $this->fileUtility->create($this->path);

        $this->fileUtility->copy($this->path, $this->newPath);

        $this->assertTrue(file_exists($this->newPath));
        $this->assertTrue(file_exists($this->path));
    }

    /**
     * Test move file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testMoveFile()
    {
        $this->fileUtility->create($this->path);

        $this->fileUtility->move($this->path, $this->newPath);

        $this->assertTrue(file_exists($this->newPath));
        $this->assertFalse(file_exists($this->path));
    }

    /**
     * Test remove file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRemoveFile()
    {
        $this->fileUtility->create($this->path);

        $this->fileUtility->remove($this->path);

        $this->assertFalse(file_exists($this->path));
    }

    /**
     * Test write to file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testWriteToFile()
    {
        $this->fileUtility->create($this->path);

        $this->fileUtility->write($this->path, 'Hello SigmaPHP!');

        $this->assertEquals('Hello SigmaPHP!', file_get_contents($this->path));
    }

    /**
     * Test append to file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testAppendToFile()
    {
        $this->fileUtility->create($this->path);

        $this->fileUtility->write($this->path, 'Hello ');

        $this->fileUtility->append($this->path, 'SigmaPHP!');

        $this->assertEquals('Hello SigmaPHP!', file_get_contents($this->path));
    }

    /**
     * Test read from file.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testReadFromFile()
    {
        $this->fileUtility->create($this->path);

        $this->fileUtility->write($this->path, 'Hello SigmaPHP!');

        $this->assertEquals(
            'Hello SigmaPHP!',
            $this->fileUtility->read($this->path)
        );
    }

    /**
     * Test create directory.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testCreateDirectory()
    {
        $this->fileUtility->createDir($this->dirPath);

        $this->assertTrue(file_exists($this->dirPath));
        $this->assertTrue(is_dir($this->dirPath));
    }

    /**
     * Test remove directory.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRemoveDirectory()
    {
        $this->fileUtility->createDir($this->dirPath);

        $this->fileUtility->removeDir($this->dirPath);

        $this->assertFalse(file_exists($this->dirPath));
    }

    /**
     * Test remove non-empty directory.
     *
     * @runInSeparateProcess
     * @return void
     */
    public function testRemoveNonEmptyDirectory()
    {
        $this->fileUtility->createDir($this->dirPath);

        $this->fileUtility->create($this->path);

        $this->fileUtility->move($this->path, $this->dirPath . '/testing.txt');

        $this->fileUtility->removeDir($this->dirPath);

        $this->assertFalse(file_exists($this->dirPath));
    }
}
