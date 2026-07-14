<?php

namespace SigmaPHP\Console\Interfaces;

/**
 * File Utility Interface.
 */
interface FileUtilityInterface
{
    /**
     * List all files inside a path and its sub-directories.
     *
     * @param string $path
     * @param bool $includeFullPath
     * @param bool $withExtension
     * @return array<string>
     */
    public function list($path, $includeFullPath, $withExtension);

    /**
     * Check if a path exists.
     *
     * @param string $path
     * @return bool
     */
    public function exists($path);

    /**
     * Check if a directory has a file or subdirectory.
     *
     * @param string $path
     * @param string $target
     * @return bool
     */
    public function dirHas($path, $target);

    /**
     * Create file.
     *
     * @param string $path
     * @param int $permission
     * @return bool
     */
    public function create($path, $permission);

    /**
     * Rename files and directories.
     *
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function rename($old, $new);

    /**
     * Copy file.
     *
     * @param string $src
     * @param string $dest
     * @return bool
     */
    public function copy($src, $dest);

    /**
     * Move file.
     *
     * @param string $src
     * @param string $dest
     * @return bool
     */
    public function move($src, $dest);

    /**
     * Remove file.
     *
     * @param string $path
     * @return bool
     */
    public function remove($path);

    /**
     * Write content to file.
     *
     * @param string $path
     * @param mixed $content
     * @return bool
     */
    public function write($path, $content);

    /**
     * Append content to file.
     *
     * @param string $path
     * @param mixed $content
     * @return bool
     */
    public function append($path, $content);

    /**
     * Read file's content.
     *
     * @param string $path
     * @param int $offset
     * @param int $length
     * @return string
     */
    public function read($path, $offset, $length);

    /**
     * Create directory.
     *
     * @param string $path
     * @param int $permission
     * @param bool $recursive
     * @return bool
     */
    public function createDir($path, $permission, $recursive);

    /**
     * Remove directory and its content.
     *
     * @param string $path
     * @return bool
     */
    public function removeDir($path);
}
